


function toggleFloor(floorClass) {
    var rows = document.querySelectorAll('.' + floorClass);
    rows.forEach(row => {
        row.style.display = row.style.display === "none" ? "table-row" : "none";
    });
}

document.addEventListener("DOMContentLoaded", function () {
    const savedView = localStorage.getItem('selectedView') || 'list';
    console.log(savedView);

    function getAllDatesInTableBooking() {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: "GET",
                url: roomBoookingHistory,
                success: function (response) {
                    if (response.status === 'success') {
                        resolve(response.data);
                    } else {
                        reject("Không lấy được dữ liệu");
                    }
                },
                error: function (error) {
                    reject(error);
                }
            });
        });
    }


    function formatDate(date) {
        return date.toISOString().split('T')[0];
    }

    function initDates() {
        let today = new Date();
        let futureDate = new Date();
        futureDate.setDate(today.getDate() + 5);
        document.getElementById("startDate").value = formatDate(today);
        document.getElementById("endDate").value = formatDate(futureDate);
        generateTable();
    }

    async function generateTable() {
        const startDate = new Date(document.getElementById("startDate").value);
        const endDate = new Date(document.getElementById("endDate").value);
        const headerRow = document.getElementById("headerRow");
        const tableBody = document.getElementById("tableBody");

        headerRow.innerHTML = "<th>Phòng</th>";
        tableBody.innerHTML = "";

        let showHours = startDate.toDateString() === endDate.toDateString();
        let dateHeaders = [];

        if (showHours) {
            for (let hour = 0; hour < 24; hour++) {
                headerRow.innerHTML += `<th>${hour}:00</th>`;
                dateHeaders.push(hour);
            }
        } else {
            let currentDate = new Date(startDate);
            while (currentDate <= endDate) {
                let formattedDate = currentDate.toLocaleDateString("vi-VN");
                headerRow.innerHTML += `<th>${formattedDate}</th>`;
                dateHeaders.push(new Date(currentDate));
                currentDate.setDate(currentDate.getDate() + 1);
            }
        }

        try {
            // 🚀 Lấy dữ liệu từ API
            let rooms = await getAllDatesInTableBooking();
            let groupedRooms = {};

            // ✅ Nhóm phòng theo `room_type_id`
            rooms.forEach(room => {

                if (!groupedRooms[room['room_type']['name']]) {
                    groupedRooms[room['room_type']['name']] = [];
                }
                groupedRooms[room['room_type']['name']].push(room);
            });


            // 🎨 Render bảng
            for (let roomType in groupedRooms) {


                tableBody.innerHTML += `<tr class="room-type-header">
                    <td colspan="${dateHeaders.length + 1}" style="background:#ddd; font-weight:bold;">
                       <span style="float: left;font-size: 13px;">  ${roomType}</span>
                    </td>
                </tr>`;

                groupedRooms[roomType].forEach(room => {
                    let row = `<tr><td class="text-left" >${room.room_number}</td>`;


                    if (showHours) {
                        dateHeaders.forEach(hour => {
                            let cellTime = new Date(startDate);
                            cellTime.setHours(hour, 0, 0, 0);

                            let bgColor = 0;
                            let isBooked = room.room_booking_history.some(booking => {


                                let checkin = new Date(booking.start_date);
                                let checkout = new Date(booking.end_date);
                                if (booking.status_code == 2) {
                                    bgColor = 2; // Trạng thái 2: Màu xanh lá
                                } else if (booking.status_code == 3) {
                                    bgColor = 3; // Trạng thái 3: Màu đỏ
                                }
                                return cellTime >= checkin && cellTime < checkout;
                            });


                            if (isBooked && bgColor == 2) {
                                row += `<td  class="room_book" style="background: ${isBooked ? 'lightgreen' : 'transparent'};"></td>`;
                            } else if (isBooked && bgColor == 3) {
                                row += `<td class="check_in_room" style="background: ${isBooked ? '#e6454d' : 'transparent'};"></td>`; // màu đỏ
                            } else if (!isBooked) {
                                row += `<td style="background: transparent;"></td>`;
                            }
                          

                        });
                    } else { 
                        // ngày
                        console.log('ngày');
                        
                        dateHeaders.forEach(date => {
                            let cellDate = new Date(date).setHours(0, 0, 0, 0);
                            // Kiểm tra từng bản ghi đặt phòng
                            let styles = room.room_booking_history.map(booking => {

                                let checkin = new Date(booking.start_date);
                                let checkout = new Date(booking.end_date);
                                let checkinDate = new Date(checkin).setHours(0, 0, 0, 0);
                                let checkoutDate = new Date(checkout).setHours(0, 0, 0, 0);
                                let color = ""; let className = "";
                                if (booking.status_code == 2) {
                                    color = "lightgreen"; // Màu xanh lá
                                    className = "room_book";
                                } else if (booking.status_code == 3) {
                                    color = "#e6454d"; // Màu đỏ nhạt
                                    className = "check_in_room";
                                }
                                if (color) {
                                    let startPercent = ((checkin.getHours() * 60 + checkin.getMinutes()) / (24 * 60)) * 100;
                                    let endPercent = ((checkout.getHours() * 60 + checkout.getMinutes()) / (24 * 60)) * 100;
                        
                                    if (cellDate === checkinDate && cellDate === checkoutDate) {
                                        // Trường hợp đặt phòng và checkout trong cùng một ngày
                                        return { style: `linear-gradient(to right, transparent ${startPercent}%, ${color} ${startPercent}%, ${color} ${endPercent}%, transparent ${endPercent}%)`, className };
                                    } else if (cellDate === checkinDate) {
                                        // Ngày check-in: bôi màu từ giờ check-in đến hết ngày
                                        return { style: `linear-gradient(to right, transparent ${startPercent}%, ${color} ${startPercent}%)`, className };
                                    } else if (cellDate === checkoutDate) {
                                        // Ngày check-out: bôi màu từ đầu ngày đến giờ check-out
                                        return { style: `linear-gradient(to right, ${color} 0%, ${color} ${endPercent}%, transparent ${endPercent}%)`, className };
                                    } else if (cellDate > checkinDate && cellDate < checkoutDate) {
                                        // Những ngày ở giữa check-in và check-out: full màu
                                        return { style: color, className };
                                    }
                                }
                                return null;
                            }).filter(s => s !== null);

                            let finalStyle = styles.map(s => s.style).join(", ");
                            let finalClass = styles.map(s => s.className).filter(c => c).join(" ");

                            row += `<td class="${finalClass}" style="background: ${finalStyle};"></td>`;
                        });
                    }

                    row += "</tr>";
                    tableBody.innerHTML += row;
                });

            }
            updateRealtime();
        } catch (error) {
            console.error("Lỗi khi lấy dữ liệu phòng:", error);
        }


    }


    // Danh sách phòng đã cảnh báo (tránh spam alert)
    let lastAlertedRooms = new Set();

    function updateRealtime() {
        const now = new Date();
        const today = now.toLocaleDateString("vi-VN");

        const currentHour = now.getHours();
        const currentMinute = now.getMinutes();

        const ths = document.querySelectorAll("thead th");
        const realtimeLine = document.getElementById("realtimeLine");
        const realtimeTime = document.getElementById("realtimeTime");

        let found = false;
        let table = document.querySelector("table");
        let tableRect = table.getBoundingClientRect();
        let tableBody = document.getElementById("tableBody");
        ths.forEach(th => {
            let text = th.textContent.trim();
            let rect = th.getBoundingClientRect();
            let columnWidth = rect.width;

            // Nếu cột là ngày hôm nay
            if (text.includes(today)) {
                let hourWidth = columnWidth / 24; // Chia cột thành 24 phần cho từng giờ
                let offset = hourWidth * currentHour + (hourWidth * currentMinute / 60);

                realtimeLine.style.left = `${rect.left - tableRect.left + offset - 10}px`;
                realtimeLine.style.height = `${tableBody.offsetHeight}px`;
                realtimeLine.style.display = "block";

                realtimeTime.innerText = `${currentHour}:${currentMinute < 10 ? '0' + currentMinute : currentMinute}`;
                realtimeTime.style.left = `${rect.left - tableRect.left + offset - 10}px`;

                found = true;
            }

            // Nếu cột chứa giờ cụ thể
            if (text.includes(":")) {
                let hour = parseInt(text);
                if (hour === currentHour) {
                    let offset = columnWidth * (currentMinute / 60);
                    realtimeLine.style.left = `${rect.left - tableRect.left + offset - 10}px`;
                    realtimeLine.style.height = `${tableBody.offsetHeight}px`;
                    realtimeLine.style.display = "block";

                    realtimeTime.innerText = `${currentHour}:${currentMinute < 10 ? '0' + currentMinute : currentMinute}`;
                    realtimeTime.style.left = `${rect.left - tableRect.left + offset - 10}px`;

                    found = true;
                }
            }
        });

        if (!found) {
            realtimeLine.style.display = "none";
        }

        checkRealtimeCheckout(now); // Kiểm tra checkout
    }
    let rooms = null; // Biến lưu danh sách phòng, chỉ cập nhật mỗi 30 phút
    let lastUpdatedTime = 0; // Thời điểm cập nhật cuối cùng
    const CHECK_INTERVAL = 30 * 60 * 1000; // 30 phút (milliseconds)


    async function updateRoomsData() {
        try {
            rooms = await getAllDatesInTableBooking(); // Gọi API lấy danh sách phòng
            lastUpdatedTime = Date.now(); // Lưu lại thời gian cập nhật
        } catch (error) {
            console.error("Lỗi khi cập nhật danh sách phòng:", error);
        }
    }

    // Gọi API ngay lần đầu tiên khi khởi chạy
    updateRoomsData();

    // Lập lịch gọi API mỗi 30 phút
    setInterval(updateRoomsData, CHECK_INTERVAL);

    async function checkRealtimeCheckout(now) {

        try {
            rooms.forEach(room => {
                room.room_booking_history.map(booking => {
                    let checkoutTime = new Date(booking.end_date).getTime(); // Chuyển thành timestamp
                    let nowTime = now.getTime(); // Chuyển 'now' thành timestamp

                    // Nếu đã đến giờ checkout và trong vòng 1 phút
                    if (nowTime >= checkoutTime && (nowTime - checkoutTime) < 60000) {
                        if (!lastAlertedRooms.has(room.id)) {


                            // trả phòng muộn 

                            alert(`⚠️ Phòng ${room.room_number} đã checkout vào ${formatDate(new Date(checkoutTime))} lúc ${new Date(checkoutTime).getHours()}:${new Date(checkoutTime).getMinutes()}`);
                            lastAlertedRooms.add(room.id); // Đánh dấu đã cảnh báo
                        }
                    }
                });


            });
        } catch (error) {
            console.error("Lỗi khi kiểm tra checkout:", error);
        }
    }



    document.getElementById("tableBody").addEventListener("click", (e) => {
        if (e.target.tagName === "TD" && e.target.cellIndex > 0) {
            let roomName = e.target.parentElement.firstChild.textContent.trim();
            // let dateHeaders = document.querySelectorAll("thead th");
            let dateHeaders = document.querySelectorAll("#headerRow th");


            // Lấy ngày tương ứng từ `<th>`, bỏ qua cột "Phòng"
            let date = dateHeaders[e.target.cellIndex].textContent.trim();

            if (roomName && date) {
                alert(`📅 Phòng: ${roomName}, Ngày: ${date}`);
            }
        }
    });

    document.getElementById("startDate").addEventListener("change", generateTable);
    document.getElementById("endDate").addEventListener("change", generateTable);

    setInterval(updateRealtime, 1000);
    initDates();
});
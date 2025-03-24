


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
                    let row = `<tr><td class="text-left" data-price=${room['room_type']['room_type_price']['unit_price']} >${room.room_number}</td>`;


                    if (showHours) {
                        dateHeaders.forEach(hour => {
                            let cellTime = new Date(startDate);
                            cellTime.setHours(hour, 0, 0, 0);

                            let bgColor = 0;
                            let bookingId = '';
                            let isBooked = room.room_booking_history.some(booking => {

                                let checkin = new Date(booking.start_date);
                                let checkout = new Date(booking.end_date);
                                if (booking.status_code == 2) {
                                    bgColor = 2; // Trạng thái 2: Màu xanh lá
                                    bookingId = booking.booking_data['booking_id'];
                                } else if (booking.status_code == 3) {
                                    bgColor = 3; // Trạng thái 3: Màu đỏ
                                    bookingId = booking.check_in_data['check_in_id'];
                                }
                                return cellTime >= checkin && cellTime < checkout;
                            });


                            if (isBooked && bgColor == 2) {
                                row += `<td  class="room_book" data-booking="${bookingId}" 
                                style="background: ${isBooked ? '#ebb579' : 'transparent'};"></td>`;
                            } else if (isBooked && bgColor == 3) {
                                row += `<td class="check_in_room"  data-booking="${bookingId}" 
                                style="background: ${isBooked ? '#e6454d' : 'transparent'};"></td>`; // màu đỏ
                            } else if (!isBooked) {
                                row += `<td style="background: transparent;"></td>`;
                            }


                        });
                    } else {
                        // ngày

                        dateHeaders.forEach(date => {
                            let cellDate = new Date(date).setHours(0, 0, 0, 0);
                            // Kiểm tra từng bản ghi đặt phòng
                            let styles = room.room_booking_history.map(booking => {
                                let checkin = new Date(booking.start_date);
                                let checkout = new Date(booking.end_date);
                                let checkinDate = new Date(checkin).setHours(0, 0, 0, 0);
                                let checkoutDate = new Date(checkout).setHours(0, 0, 0, 0);

                                let color = "";
                                let className = "";
                                let bookingId = ""; // Khai báo lại bên trong map()

                                if (booking.status_code == 2) {
                                    color = "#ebb579"; // Màu vàng (đặt phòng)
                                    className = "room_book";
                                    bookingId = booking.booking_data ? booking.booking_data['booking_id'] : "";
                                } else if (booking.status_code == 3) {
                                    color = "#e6454d"; // Màu đỏ (check-in)
                                    className = "check_in_room";
                                    bookingId = booking.check_in_data ? booking.check_in_data['check_in_id'] : "";
                                }

                                if (color) {
                                    let startPercent = ((checkin.getHours() * 60 + checkin.getMinutes()) / (24 * 60)) * 100;
                                    let endPercent = ((checkout.getHours() * 60 + checkout.getMinutes()) / (24 * 60)) * 100;

                                    if (cellDate === checkinDate && cellDate === checkoutDate) {
                                        return {
                                            style: `linear-gradient(to right, transparent ${startPercent}%, ${color} ${startPercent}%, ${color} ${endPercent}%, transparent ${endPercent}%)`,
                                            className,
                                            bookingId
                                        };
                                    } else if (cellDate === checkinDate) {
                                        return {
                                            style: `linear-gradient(to right, transparent ${startPercent}%, ${color} ${startPercent}%)`,
                                            className,
                                            bookingId
                                        };
                                    } else if (cellDate === checkoutDate) {
                                        return {
                                            style: `linear-gradient(to right, ${color} 0%, ${color} ${endPercent}%, transparent ${endPercent}%)`,
                                            className,
                                            bookingId
                                        };
                                    } else if (cellDate > checkinDate && cellDate < checkoutDate) {
                                        return {
                                            style: color,
                                            className,
                                            bookingId
                                        };
                                    }
                                }
                                return null;
                            }).filter(s => s !== null);

                            // Gộp các style lại
                            let finalStyle = styles.map(s => s.style).join(", ");
                            let finalClass = styles.map(s => s.className).filter(c => c).join(" ");

                            // Lấy bookingId đầu tiên nếu có nhiều booking
                            let finalBookingId = styles.length > 0 ? styles[0].bookingId : "";

                            row += `<td class="${finalClass}" data-booking="${finalBookingId}" style="background: ${finalStyle};"></td>`;

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

    function addRoomInBooking(data, list) {
        $('#loading').show();
        $.ajax({
            url: checkRoomBookingUrl,
            type: 'POST',
            data: {
                data: JSON.stringify(data)
            },
            success: function (response) {
                var tbody = list;

                let targetId = list[0]?.id;
                targetId === 'list-booking-edit' ?
                    $('#list-booking').empty() :
                    targetId === 'list-booking' ?
                        $('#list-booking-edit').empty() :
                        null;
                if (response.status === 'success') {
                    const seenRooms = new Set();
                    let totalPrice = 0;

                    response.data.forEach(item => {


                        let date = new Date(item.date);
                        date.setDate(date.getDate() + 1);
                        const roomId = item.room["id"];
                        const roomTypeId = item.room_type["id"];
                        const roomDate = item.date;
                        const key = `${roomId}-${roomTypeId}-${roomDate}`;

                        if (seenRooms.has(key)) {
                            return;
                        }
                        seenRooms.add(key);

                        var tr = `
                        <tr  data-status="0" data-room-id="${roomId}"  data-room-type-id="${roomTypeId}" data-date="${item.date}">
                            <td>
                                <input type="checkbox">
                            </td>

                            <td>
                                <p class="room__name"> ${item.room['room_number']}</p>
                            </td>
                             <td>
                                 <input type="number" min="1" name="adult" class="form-control adult"  value="1"  style="margin-left: 16px;">

                            </td>
                            <td >
                                <select id="bookingType" class="form-select" name="optionRoom" style="width: 93px; font-size:15px">
                                     <option value="ngay">Ngày</option>
                                     <option value="gio">Giờ</option>
                                </select>
                            </td>
                             <td>
                                <div class="d-flex align-items-center justify-content-start" style="gap: 3px">
                                    <input type="date" name="checkInDate" id="date-book-room" class="form-control date-book-room"  value="${item.date}" readonly>

                                    <input type="time" name="checkInTime" id="time-book-room" class="form-control time-book-room"   value="${item.room['room_type']['room_type_price']['setup_pricing']['check_in_time']}">
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-start" style="gap: 3px">
                                    <input type="date" name="checkOutDate"  class="form-control date-book-room" readonly  value="${date.toISOString().split('T')[0]}">

                                    <input type="time" name="checkOutTime" id="time-book-room" class="form-control time-book-room"  value="${item.room['room_type']['room_type_price']['setup_pricing']['check_out_time']}">

                                </div>
                            </td>
                            <td>
                                 <p id="price" data-price="${item.room['room_type']['room_type_price']['unit_price']}">${formatCurrency(item.room['room_type']['room_type_price']['unit_price'])}</p>
                            </td>
                            <td>
                                  <input type="text" class="form-control deposit number-input money-input"  name="deposit"  placeholder="0">
                            </td>
                             <td>
                                  <input type="text" class="form-control discount number-input-discount money-input"  name="discount"  placeholder="0">
                            </td>
                            <td>
                                <input type="text" name="note_room" class="form-control note_room" value="" id="note">
                            </td>
                        </tr>
                    `;

                        // tbody.append(tr); 
                        const tableSelector = targetId === 'list-booking-edit' ?
                            '#list-booking-edit' : '#list-booking';
                        let isDuplicate = $(`${tableSelector} tr`).filter(function () {
                            return $(this).attr('data-room-id') == roomId &&
                                $(this).attr('data-room-type-id') ==
                                roomTypeId &&
                                $(this).attr('data-date') == item.date;
                        }).length > 0;

                        if (!isDuplicate) {
                            tbody.append(tr);
                        }
                    })
                    totalPrice = calculateTotalPrice();

                    $('#loading').hide();
                    let totalDeposit = 0;
                    let totalBalance = 0;
                    $('tr').find('input.deposit').on('blur', function () {
                        let rowTotal = 0;
                        $('tr').each(function () {
                            $(this).find('input.deposit').each(function () {
                                let depositValue = $(this).val()
                                    .replace(/[,.]/g, '');
                                let numericDeposit = parseInt(
                                    depositValue) || 0;
                                rowTotal += numericDeposit;
                            });
                        });

                        $('.total_deposit').text(formatCurrency(rowTotal));
                        let priceString = $('.total_discount').text();
                        let price = parseInt(priceString.replace(/\./g, ""), 10);
                        price = isNaN(price) ? 0 : price;
                        totalBalance = totalPrice - rowTotal - price;
                        $('.total_balance').text(formatCurrency(totalBalance));
                    });
                    $('tr').find('input.discount').on('blur', function () {
                        let rowTotal = 0;
                        $('tr').each(function () {
                            $(this).find('input.discount').each(function () {
                                let depositValue = $(this).val()
                                    .replace(/[,.]/g, '');
                                let numericDeposit = parseInt(
                                    depositValue) || 0;
                                rowTotal += numericDeposit;
                            });
                        });

                        $('.total_discount').text(formatCurrency(rowTotal));
                        let priceString = $('.total_deposit').text();
                        let price = parseInt(priceString.replace(/\./g, ""), 10);
                        price = isNaN(price) ? 0 : price;
                        totalBalance = totalPrice - rowTotal - price;
                        $('.total_balance').text(formatCurrency(totalBalance));
                    });

                    $('#addRoomModal').modal('hide');
                    $('#myModal-booking').modal('show');
                    document.body.classList.remove("modal-open");
                } else if (response.status === 'error') {
                    $('#loading').hide();
                    var tr = ``;
                    tbody.append(tr);
                    $('#addRoomModal').modal('hide');
                    document.body.classList.remove("modal-open");
                }
            },
            error: function (error) {
                $('#loading').hide();
                console.log('Error:', error);
            }
        });
    }

    document.getElementById("tableBody").addEventListener("click", (e) => {
        if (e.target.tagName === "TD" && e.target.cellIndex > 0) {
            let firstTd = e.target.parentElement.firstChild;
            let roomName = firstTd.textContent.trim();
            let roomPrice = firstTd.getAttribute("data-price");
    
            let dateHeaders = document.querySelectorAll("#headerRow th");
            let date = dateHeaders[e.target.cellIndex].textContent.trim();
    
            let background = e.target.getAttribute("style");
            let tdWidth = e.target.clientWidth;
            let clickX = e.offsetX;
    
            let colorMessage = "🔲 Bạn đã bấm vào phần không màu!";
    
            if (background && background.includes("background:")) {
                let bgValue = background.match(/background:\s*(.*?);/);
    
                if (bgValue && bgValue[1]) {
                    let bgStyle = bgValue[1].trim();
    
                    if (bgStyle === "" || bgStyle === "transparent") {
                        colorMessage = "🔲 Bạn đã bấm vào phần không màu!";
                    } else if (bgStyle.startsWith("rgb") || bgStyle.startsWith("#")) {
                        colorMessage = "📌 Bạn đã bấm vào phần có màu!";
                    } else if (bgStyle.includes("linear-gradient")) {
                        let gradientMatch = bgStyle.match(/(transparent|#[0-9A-Fa-f]+|\w+\(\d+,\s*\d+,\s*\d+\))\s*(\d+(\.\d+)?)%/g);
    
                        if (gradientMatch && gradientMatch.length > 0) {
                            let colorRanges = [];
                            let lastEnd = 0;
    
                            for (let i = 0; i < gradientMatch.length; i++) {
                                let match = gradientMatch[i].match(/(transparent|#[0-9A-Fa-f]+|\w+\(\d+,\s*\d+,\s*\d+\))\s*(\d+(\.\d+)?)%/);
                                if (match) {
                                    let color = match[1];
                                    let percent = parseFloat(match[2]);
                                    let pixelPos = (percent / 100) * tdWidth;
    
                                    if (color !== "transparent") {
                                        colorRanges.push({ start: lastEnd, end: pixelPos });
                                    }
                                    lastEnd = pixelPos;
                                }
                            }
    
                            let isInColor = colorRanges.some(range => clickX >= range.start && clickX <= range.end);
                            console.log(isInColor);
                            
                            colorMessage = isInColor ? "📌 Bạn đã bấm vào phần có màu!" : "🔲 Bạn đã bấm vào phần không màu!";
                        }
                    }
                }
            }
    
            if (roomName && date && roomPrice) {
                alert(`📅 Phòng: ${roomName}, Ngày: ${date}, Giá: ${roomPrice}\n${colorMessage}`);
            }
        }
    });
    

    document.getElementById("startDate").addEventListener("change", generateTable);
    document.getElementById("endDate").addEventListener("change", generateTable);

    setInterval(updateRealtime, 1000);
    initDates();
});
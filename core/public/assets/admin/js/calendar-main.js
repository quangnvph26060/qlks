function initGridMain(data) {
    const savedView = localStorage.getItem('selectedView') || 'list';

    let htmlrow = '';
    if (savedView === "calendar") {
        htmlrow = `  <input type="date" id="startDate" class="form-control w-auto" style="height: 35px"
                        placeholder="Từ ngày">
                    <input type="date" id="endDate" class="form-control w-auto" style="height: 35"
                        placeholder="Đến ngày"></input>`
            ;
    }
    $('#date-input-booking').html(htmlrow);

    function getAllDatesInTableBooking() {
        $('#loading-overlay').css('display', 'flex');
        return new Promise((resolve, reject) => {
            $.ajax({
                type: "GET",
                url: roomBoookingHistory,
                data: { data },
                success: function (response) {
                    if (response.status === 'success') {
                        resolve(response.data);
                        setTimeout(function () {
                            $('#loading-overlay').css('display', 'none');
                        }, 1000);
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

        headerRow.innerHTML = '<th class="w-10">Phòng</th>';
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


                tableBody.innerHTML += `<tr class="room-type-header" style="height:35px">
                    <td colspan="${dateHeaders.length + 1}" style="background:#ddd; font-weight:bold;height:35px; padding:0px 0px !important">
                       <span style="float: left;font-size: 13px;">  ${roomType}</span>
                    </td>
                </tr>`;

                groupedRooms[roomType].forEach(room => {




                    let row = `<tr><td class=" text-left truncate-text d-flex flex-column"  style="line-height:6px"
                    data-room-type-id="${room['room_type_id']}" 
                    data-room-id="${room['id']}" 
                    data-price="${room['applied_price']['unit_price']}"
                    data-name="${room['room_number']}"
                    title="${room.room_number}">
                        <span style="font-size:13px;font-weight:bold;" class="name_number"> ${room.room_number}</span>
                        <span class="mt-0" style="font-size:10px;font-weight:bold;" class="room-number-name"> ${room?.direction?.name}</span>
                        <span class=" ${room.room_fix == 1 ? 'modal_fixroom' : 'modal_clean'}" style="font-size:10px;font-weight:600;cursor: pointer;">Tình trạng phòng: <span class="text-ttp"style="font-size:10px;font-weight:600;"> ${room.room_fix == 1 ? "🛠 Phòng đang sửa" : (room.is_clean ? "✨ Sạch" : "🚨 Chưa dọn")} </span></span>
                    </td>`;


                    if (showHours) {
                        // giờ
                        dateHeaders.forEach(hour => {
                            let cellTime = new Date(startDate);
                            cellTime.setHours(hour, 0, 0, 0);

                            let bgColor = 0;
                            let bookingId = '';
                            let Id = "";
                            let roomType = "";
                            let isBooked = room.room_booking_history.some(booking => {


                                let checkin = new Date(booking.start_date);
                                let checkout = new Date(booking.end_date);
                                if (booking.status_code == 2) {
                                    bgColor = 2;
                                    if (booking.booking_data) {
                                        booking?.booking_data?.forEach(item => {
                                            if (item.room_code == booking.room_id) {
                                                bookingId = item.booking_id;
                                                Id = item.id;
                                                roomType = item.room_type_id;
                                            }
                                        });

                                    }
                                } else if (booking.status_code == 3) {
                                    bgColor = 3; // Trạng thái 3: Màu đỏ
                                    bookingId = booking.check_in_data['check_in_id'];
                                }
                                return cellTime >= checkin && cellTime < checkout;
                            });



                            if (isBooked && bgColor == 2) {
                                row += `<td  class="room_book" data-booking="${bookingId}" data-id="${Id}" data-room-type-id="${roomType}"
                                style="background: ${isBooked ? '#ebb579' : 'transparent'};"></td>`;
                            } else if (isBooked && bgColor == 3) {
                                row += `<td class="check_in_room"  data-booking="${bookingId}" data-id="${Id}" data-room-type-id="${roomType}"
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
                                let bookingId = "";
                                let Id = "";
                                let roomType = "";
                                let roomId = "";
                                let bookDate = "";
                                if (booking.status_code == 2) {
                                    color = "#ebb579";
                                    className = "room_book";
                                    if (booking.booking_data) {
                                        booking?.booking_data?.forEach(item => {




                                            if (item.room_code == booking.room_id) {
                                                bookingId = item.booking_id;
                                                Id = item.id;
                                                roomType = room['room_type_id'];
                                                roomId = room['id'];
                                                bookDate = item.checkin_date;
                                            }
                                        });

                                    }
                                } else if (booking.status_code == 3) {
                                    color = "#e6454d";
                                    className = "check_in_room";
                                    // bookingId = booking.check_in_data ? booking.check_in_data['check_in_id'] : "";
                                    // Id = booking.check_in_data ? booking.check_in_data['id'] : "";
                                    // roomType = booking.check_in_data ? booking.check_in_data['room_type_id'] : "";
                                    if (booking.check_in_data) {
                                        booking?.check_in_data?.forEach(item => {

                                            if (item.room_code == booking.room_id) {
                                                bookingId = item.check_in_id;
                                                Id = item.id;
                                                roomType = room['room_type_id'];
                                                roomId = room['id'];
                                                bookDate = item.checkin_date;
                                            }
                                        });
                                    }
                                }

                                if (color) {
                                    let startPercent = ((checkin.getHours() * 60 + checkin.getMinutes()) / (24 * 60)) * 100;
                                    let endPercent = ((checkout.getHours() * 60 + checkout.getMinutes()) / (24 * 60)) * 100;

                                    if (cellDate === checkinDate && cellDate === checkoutDate) {
                                        return {
                                            style: `linear-gradient(to right, transparent ${startPercent}%, ${color} ${startPercent}%, ${color} ${endPercent}%, transparent ${endPercent}%)`,
                                            className,
                                            bookingId,
                                            Id,
                                            roomType,
                                            roomId,
                                            bookDate
                                        };
                                    } else if (cellDate === checkinDate) {
                                        return {
                                            style: `linear-gradient(to right, transparent ${startPercent}%, ${color} ${startPercent}%)`,
                                            className,
                                            bookingId,
                                            Id,
                                            roomType,
                                            roomId,
                                            bookDate
                                        };
                                    } else if (cellDate === checkoutDate) {
                                        return {
                                            style: `linear-gradient(to right, ${color} 0%, ${color} ${endPercent}%, transparent ${endPercent}%)`,
                                            className,
                                            bookingId,
                                            Id,
                                            roomType,
                                            roomId,
                                            bookDate
                                        };
                                    } else if (cellDate > checkinDate && cellDate < checkoutDate) {
                                        return {
                                            style: color,
                                            className,
                                            bookingId,
                                            Id,
                                            roomType,
                                            roomId,
                                            bookDate
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
                            let finalId = styles.length > 0 ? styles[0].Id : "";
                            let finalRoomType = styles.length > 0 ? styles[0].roomType : "";
                            let finalRoomId = styles.length > 0 ? styles[0].roomId : "";
                            let finalbookDate = styles.length > 0 ? styles[0].bookDate : "";
                            row += `<td  class="${finalClass}" data-date="${finalbookDate}"  data-room-id="${finalRoomId}" data-room-type-id="${finalRoomType}"  data-id="${finalId}" data-booking="${finalBookingId}" style="cursor: pointer;background: ${finalStyle};"></td>`;

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
    $(document).on('click', '.modal_clean', function () {
        const roomName = $(this).data("name");
        const roomId = $(this).data("room-id");
        let statusText = $(this).find('.text-ttp').text().trim();

        switch (statusText) {
            case '🚨 Chưa dọn':
                statusText = '✨ Sạch';
                break;
            case '✨ Sạch':
                statusText = '🚨 Chưa dọn';
                break;
        }
        $("#roomStatusModal .modal-body strong").text(roomName);
        $("#roomStatusModal .modal-body .status-text").text(statusText);
        $('.change_clean_room_btn').attr('data-id', roomId);
        const modal = new bootstrap.Modal(document.getElementById("roomStatusModal"));
        modal.show();
    });
    $(document).on('click', '.modal_fixroom', function () {
        const roomName = $(this).data("name");
        const roomId = $(this).data("room-id");
        let statusText = $(this).find('.text-ttp').text().trim();
        switch (statusText) {
            case '🛠 Phòng đang sửa':
                statusText = 'Sửa phòng hoàn thành';
                break;
            case '✨ Sạch':
                statusText = '🚨 Chưa dọn';
                break;
        }
        $("#roomfixStatusModal .modal-body strong").text(roomName);
        $("#roomfixStatusModal .modal-body .status-text").text(statusText);
        $('.change_room_fix_btn').attr('data-id', roomId);
        const modal = new bootstrap.Modal(document.getElementById("roomfixStatusModal"));
        modal.show();
    });
    $(document).off("click", ".change_clean_room_btn").on("click", ".change_clean_room_btn", function (e) {
        const id = $(this).attr("data-id");
        $.ajax({
            url: cleanRoomUrl,
            type: 'POST',
            data: {
                id: id,
            },
            success: function (data) {
                if (data.status === 'success') {
                    notify('success', data.success);
                    const modal = bootstrap.Modal.getInstance(document.getElementById("roomStatusModal"));
                    if (modal) modal.hide();

                    let selectedDate = $('#startDate').val();
                    initGridMain('', selectedDate);
                }

            },
            error: function (error) {
                $('#loading').hide();
                console.log('Error:', error);
            }
        });

    });
    $(document).off("click", ".change_room_fix_btn").on("click", ".change_room_fix_btn", function (e) {
        const id = $(this).attr("data-id");
        $.ajax({
            url: changeRoomFixUrl,
            type: 'POST',
            data: {
                id: id,
            },
            success: function (data) {
                if (data.status === 'success') {
                    notify('success', data.success);
                    const modal = bootstrap.Modal.getInstance(document.getElementById("roomfixStatusModal"));
                    if (modal) modal.hide();

                    let selectedDate = $('#startDate').val();
                    initGridMain('', selectedDate);
                }

            },
            error: function (error) {
                $('#loading').hide();
                console.log('Error:', error);
            }
        });

    });
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
            let lines = text.split("\n");
            let headerDate = lines[0].trim();
            // Nếu cột là ngày hôm nay
            if (headerDate === today && !found) {
                let hourWidth = columnWidth / 24; // Chia cột thành 24 phần cho từng giờ
                let offset = hourWidth * currentHour + (hourWidth * currentMinute / 60);

                realtimeLine.style.left = `${rect.left - tableRect.left + offset - 10}px`;
                realtimeLine.style.height = `${tableBody.offsetHeight}px`;
                realtimeLine.style.display = "block";

                realtimeTime.innerText = `${currentHour}:${currentMinute < 10 ? '0' + currentMinute : currentMinute}`;
                realtimeTime.style.left = `${rect.left - tableRect.left + offset - 10}px`;

                found = true;
            }
            if (!found) {
                realtimeLine.style.display = "none";
                realtimeTime.style.display = "none";
            } else {
                realtimeTime.style.display = "block";
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

        //  checkRealtimeCheckout(now); // Kiểm tra checkout
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
    //  setInterval(updateRoomsData, CHECK_INTERVAL);

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
            let firstTd = e.target.parentElement.firstChild;
            let roomName = firstTd.textContent.trim();
            let roomPrice = firstTd.getAttribute("data-price");
            let roomId = firstTd.getAttribute("data-room-id");
            let roomType = firstTd.getAttribute("data-room-type-id");
            let clickedTd = e.target; // Lấy <td> được click
            let bookingId = clickedTd.getAttribute("data-booking");
            let Id = clickedTd.getAttribute("data-id");

            let dateHeaders = document.querySelectorAll("#headerRow th");
            let date = dateHeaders[e.target.cellIndex].textContent.trim();


            let td = e.target;
            let clickX = e.offsetX; // Vị trí click từ trái qua phải
            let tdWidth = td.clientWidth; // Chiều rộng của ô td
            let percentX = ((clickX / tdWidth) * 100).toFixed(2); // Tính phần trăm
            let inlineStyle = td.style.cssText;
            if (inlineStyle === '') {
                let [day, month, year] = date.split("/");
                month = month.padStart(2, "0");
                day = day.padStart(2, "0");
                date = `${year}-${month}-${day}`;
                if (roomId && date && roomType) {
                    let data = {
                        room: roomId,
                        room_type: roomType,
                        date: date,
                    }
                    roomBooking(data);
                    console.log('không có gì');

                }
            } else if (inlineStyle.includes("linear-gradient")) {
                let gradientData = parseLinearGradient(inlineStyle);
                let foundTransparent = false;

                gradientData.forEach(item => {
                    if (item.color === "transparent" && item.direction === 'PHAI' && item.percent < percentX) {
                        foundTransparent = true;
                    } else if (item.color === "transparent" && item.direction === 'TRAI' && item.percent > percentX) {
                        foundTransparent = true;
                    }
                });


                if (foundTransparent) {

                    if (roomId && date && roomType) {
                        let [day, month, year] = date.split("/");
                        month = month.padStart(2, "0");
                        day = day.padStart(2, "0");
                        date = `${year}-${month}-${day}`;
                        let data = {
                            room: roomId,
                            room_type: roomType,
                            date: date,
                        };
                        roomBooking(data, "");
                    }
                } else {
                    if (td.className === "room_book") {
                        // đặt phòng
                        roomBooked(Id, bookingId);
                    } else {
                        // thanh toán
                        checkIn(Id, bookingId);
                    }
                }

                //  alert(`📅 Phòng: ${roomName}, Ngày: ${date}, Giá: ${roomPrice}, Click: ${percentX}%\nKết quả: ${clickedRegion}`);


            } else {
                if (roomName && date && roomPrice) {
                    const parts = date.split("/");
                    let formattedDate;
                    let time = "";
                    if (parts.length === 3) {
                        let [day, month, year] = parts.map(p => p.trim());
                        month = month.padStart(2, "0");
                        day = day.padStart(2, "0");
                        formattedDate = `${year}-${month}-${day}`;
                    } else {
                        // Lấy ngày hiện tại
                        const now = new Date();
                        const yyyy = now.getFullYear();
                        const mm = String(now.getMonth() + 1).padStart(2, "0");
                        const dd = String(now.getDate()).padStart(2, "0");
                        formattedDate = `${yyyy}-${mm}-${dd}`;
                        time = date;
                    }

                    let data = {
                        room: roomId,
                        room_type: roomType,
                        date: formattedDate,
                    };

                    roomBooking(data, formattedDate, time);
                }


            }

            // Hiển thị thông tin

        }
    });
    function formatCurrency(amount) {
        if (!amount || isNaN(amount)) {
            return '0 VND'; // Nếu amount không hợp lệ, trả về 0 VND
        }

        const parts = parseFloat(amount).toFixed(2).toString().split('.');
        const integerPart = parts[0];
        const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        return formattedInteger + ' VND';
    }
    function formatCurrencyEdit(amount) {
        const parts = amount.toString().split('.');
        const integerPart = parts[0];
        const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        return formattedInteger;
    }
    function calculateTotalPrice() {
        let totalPrice = 0;
        let totalDeposit = 0;
        let totalDiscount = 0;
        $('#list-booking, #list-booking-edit, #list-booking-edit-letan').find('p#price').each(function () {
            let priceString = $(this).attr('data-price');
            let price = parseFloat(priceString.replace(' VND', '').replace(',', '.'));
            totalPrice += price;
        });
        $('#list-booking, #list-booking-edit, #list-booking-edit-letan').find('input.deposit').each(function () {
            let priceString = $(this).val(); // Lấy giá trị nhập trong input
            let price = parseFloat(priceString.replace(/[,.]/g, '')); // Loại bỏ ký tự không phải số

            if (!isNaN(price)) {
                totalDeposit += price;
            }
        });
        $('#list-booking, #list-booking-edit, #list-booking-edit-letan').find('input.discount').each(function () {
            let priceString = $(this).val(); // Lấy giá trị nhập trong input
            let price = parseFloat(priceString.replace(/[,.]/g, '')); // Loại bỏ ký tự không phải số
            if (!isNaN(price)) {
                totalDiscount += price;
            }
        });

        console.log('tổng giá: ' + totalPrice);

        // let pricediscount = 0;
        // let discountInputValue = $('#discountInput').val();

        // if (discountInputValue) {
        //     pricediscount = parseInt(discountInputValue.replace(/\./g, ''));
        //     pricediscount = isNaN(pricediscount) ? 0 : pricediscount;
        // }

        $('.total_balance').each(function () {
            $(this).text(formatCurrency(totalPrice - totalDeposit - totalDiscount)); // tiền còn lại
        });

        $('.total_amount').each(function () {
            $(this).text(formatCurrency(totalPrice));
        });
        // giảm giá

        $('.total_discount').each(function () {

            $(this).text(formatCurrency(totalDiscount));
        });

        $('.total_deposit').each(function () {
            $(this).text(formatCurrency(totalDeposit));
        });

        //  $('#total_balance').text(formatCurrency(totalPrice));
        // $('#total_deposit').text(formatCurrency(totalPrice));
        return totalPrice;
    }
    function roomBooking(data, currentdate, time) {
        $.ajax({
            url: checkRoomBookingUrl,
            type: 'POST',
            data: {
                data: JSON.stringify(data),
                method: 'LETAN',
            },
            success: function (response) {
               if (time) {
                // Nếu time có giá trị thật → dùng luôn
                result = time;
            } else if (typeof date_booking !== 'undefined' && date_booking) {
                // Nếu có date_booking → lấy ngày giờ từ đó
                const date_yyyy = date_booking.getFullYear();
                const date_mm = String(date_booking.getMonth() + 1).padStart(2, '0');
                const date_dd = String(date_booking.getDate()).padStart(2, '0');
                const date_hour = String(date_booking.getHours()).padStart(2, '0');
                const date_minutes = String(date_booking.getMinutes()).padStart(2, '0');

                const formattedDates = `${date_yyyy}-${date_mm}-${date_dd}`;
                const formattedTimes = `${date_hour}:${date_minutes}`;

                result = (formattedDates === currentdate) ? formattedTimes : "12:00";
            } else {
                // Nếu không có date_booking → dùng ngày giờ hiện tại
                const now = new Date();
                const date_hour = String(now.getHours()).padStart(2, '0');
                const date_minutes = String(now.getMinutes()).padStart(2, '0');
                result = (currentdate === now.toISOString().slice(0, 10))
                    ? `${date_hour}:${date_minutes}`
                    : "12:00";
            }
            
                


                $('#list-booking-edit').empty();
                $('#list-booking').empty();
                $('#list-booking-edit-letan').empty();
                var tbody = $('#list-booking');
                tbody.empty();
                if (response.status === 'success') {
                    const seenRooms = new Set();
                    let totalPrice = 0;

                    response.data.forEach(item => {

                        // nhân viên
                        var selected_select_staff = $('#select-staff');
                        selected_select_staff.empty();
                        let option_staff = `<option value="">Chọn nhân viên</option>`;
                        item.admin.forEach(function (data) {
                            if (data.id == data.option_customer_source) {
                                option_staff +=
                                    `<option value="${data.id}" selected>${data.username}</option>`;
                            } else {
                                option_staff +=
                                    `<option value="${data.id}">${data.username}</option>`;
                            }
                        });
                        selected_select_staff.append(option_staff);
                        // nguồn khách 
                        var selected_customer_source = $('#select-customer-source');
                        selected_customer_source.empty();
                        let option = `<option value="">Chọn nguồn khách hàng</option>`;
                        item.customerSourse.forEach(function (data) {
                            if (data.id == data.option_customer_source) {
                                option +=
                                    `<option value="${data.source_code}" selected>${data.source_name}</option>`;
                            } else {
                                option +=
                                    `<option value="${data.source_code}">${data.source_name}</option>`;
                            }
                        });
                        selected_customer_source.append(option);
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
                        <tr  data-status="0" data-room-id="${roomId}" data-price="${item.room.applied_price['unit_price']}"  data-room-type-id="${roomTypeId}" data-date="${item.date}">
                            <td>
                                <input type="checkbox">
                            </td>

                            <td>
                                <p class="room__name"> ${item.room['room_number']}</p>
                            </td>
                             <td>
                                 <input type="number" min="1" name="adult" class="form-control adult"  value="1"  >

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

                                    <input type="time" name="checkInTime" id="time-book-room" class="form-control time-book-room"   
                                    value="${result ?? item.room['room_type']['room_type_price']['setup_pricing']['check_in_time']}" 
                                    style="display: flex; justify-content: flex-start;  width: 110px; padding: 1px 9px !important;">
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-start" style="gap: 3px">
                                   <input type="date" name="checkOutDate"  class="form-control date-book-room" readonly  value="${date.toISOString().split('T')[0]}">
                                    <input type="time" name="checkOutTime" id="time-book-room" class="form-control time-book-room"  value="${item.room['room_type']['room_type_price']['setup_pricing']['check_out_time']}"style="    display: flex;
                                    justify-content: flex-start;  width: 110px;padding: 1px 9px !important;">

                                </div>
                            </td>
                            <td>
                                 <p id="price" data-price="${item.room['applied_price']['unit_price']}">${formatCurrency(item.room['applied_price']['unit_price'])}</p>
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

                        tbody.append(tr);

                    })

                    totalPrice = calculateTotalPrice();
                    console.log(totalPrice);

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
                    $('#select-option-pttt').hide();
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
    function roomBooked(Id, dataId) {
        var url = roomBookingEditUrl.replace(':id',
            dataId);
        // $('.booking-form-pttt').attr('action', CheckInUrl);
        const date_booking = new Date();
        const date_yyyy = date_booking.getFullYear();
        const date_mm = String(date_booking.getMonth() + 1).padStart(2, '0');
        const date_dd = String(date_booking.getDate()).padStart(2, '0');
        const date_hour = String(date_booking.getHours()).padStart(2, '0'); // Giờ
        const date_minutes = String(date_booking.getMinutes()).padStart(2, '0'); // Phút

        const formattedDates = `${date_yyyy}-${date_mm}-${date_dd}`;
        const formattedTimes = `${date_hour}:${date_minutes}`;

        $('[id="time-book-room-booking"]').val(formattedTimes);
        $('[id="date-book-room-booking-edit"]').val(formattedDates);
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                method: "LETAN",
                id: Id,
            },
            success: function (response) {
                if (response.status == 'success') {
                    var selected_customer_source = $('#select-customer-source-edit');
                    selected_customer_source.empty();
                    let option = `<option value="">Chọn nguồn khách hàng</option>`;
                    response.customerSourse.forEach(function (item) {
                        if (item.source_code == response.option_customer_source) {
                            option += `<option value="${item.source_code}" selected>${item.source_name}</option>`;
                        } else {
                            option += `<option value="${item.source_code}">${item.source_name}</option>`;
                        }

                    });
                    //title  myModal-check-in-edit
                    $('.pageModal').text(response.pageModal);
                    selected_customer_source.append(option);
                    // nhân viên
                    var selected_select_staff = $('#select-staff-edit');
                    selected_select_staff.empty();
                    let option_staff = `<option value="">Chọn nhân viên</option>`;
                    response.admin.forEach(function (item) {
                        option_staff += `<option value="${item.id}">${item.username}</option>`;
                    });
                    selected_select_staff.append(option_staff);
                    $('#myModal-booking-edit').modal('show').on('shown.bs.modal', function () {
                        // $('#myModal-check-in-edit').modal('show').on('shown.bs.modal', function () {
                        $('.pageModal').text('Nhận phòng');
                        $('.name-edit, .phone-edit').val('');
                        $('#list-booking-edit-letan').empty();
                        $('#list-booking-edit').empty();
                        $('#list-booking').empty();
                        // var tbody = $('#list-booking-edit-letan');
                        var tbody = $('#list-booking-edit');


                        let totalPrice, total_deposit_amount, total_deposit_discount = 0;
                        response.data.forEach(item => {
                            $('.name-edit').val(item.customer_name);
                            $('.phone-edit').val(item.phone_number);

                            item.room_bookings.forEach((room, index) => {
                                if (index === 0) {
                                    $('.booking_id').val(room.booking_id);
                                }




                                let [checkinDate, checkinTime] = room.checkin_date.split(' ');
                                checkinTime = checkinTime.slice(0, 5);
                                let [checkoutDate, checkoutTime] = room
                                    .checkout_date.split(' ');
                                checkoutTime = checkoutTime.slice(0, 5);
                                total_deposit_amount += parseFloat(room.deposit_amount);
                                total_deposit_discount += parseFloat(room.discount);
                                // const hasSameRoomCode = item.rooms.some(r => r !== room && r.room_code === room.room_code);

                                // let datePart = '', timePart = '';

                                // if (hasSameRoomCode) {
                                //     [datePart, timePart] = room.checkin_date.split(' ');
                                // } else {
                                //     const dateOnly = formattedDates;
                                //     const now = new Date();
                                //     const currentHour = String(now.getHours()).padStart(2, '0');
                                //     const currentMinute = String(now.getMinutes()).padStart(2, '0');
                                //     const currentTime = `${currentHour}:${currentMinute}`;

                                //     datePart = dateOnly;
                                //     timePart = currentTime;
                                // }data-date="${formattedDates}" 

                                var tr = `
                                        <tr data-price="${room.total_amount}" data-room-id="${room.room_id}" data-status="${room.status}"
                                        data-room-booking-id="${room.id}" data-room-type-id="${room.room_type_id}"  class="${room.status === 1 ? "check_in_status" : ""}">
                                            <td>
                                                <input type="checkbox">
                                            </td>
    
                                            <td>
                                                <p class="room__name"> ${room.room_number}</p>
                                            </td>
                                            <td>
                                                <input type="number" min="1" name="adult" class="form-control adult"  value="${room.guest_count}"  style="margin-left: 16px;">
                                            </td>
                                            <td >
                                                <select id="bookingType" class="form-select" name="optionRoom" style="width: 93px; font-size:15px">
                                                    <option value="ngay">Ngày</option>
                                                    <option value="gio">Giờ</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-start" style="gap: 10px">
                                                    <input type="date" name="checkInDate" id="date-book-room" class="form-control date-book-room"  value="${checkinDate}" readonly>
    
                                                    <input type="time" name="checkInTime" id="time-book-room" class="form-control time-book-room"  value="${checkinTime}" >
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-start" style="gap: 10px">
                                                    <input type="date" name="checkOutDate"  class="form-control date-book-room" value="${checkoutDate}" readonly>
    
                                                    <input type="time" name="checkOutTime" id="time-book-room" class="form-control time-book-room"  value="${checkoutTime}" >
    
                                                </div>
                                            </td>
                                            <td>
                                                <p id="price" data-price="${room.total_amount}">${formatCurrency(room.total_amount)}</p>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control deposit number-input money-input"  name="deposit" value="${formatCurrencyEdit(room.deposit_amount)}" placeholder="0">
                                            </td>
                                              <td>
                                                <input type="text" class="form-control discount number-input-discount money-input"   name="discount" value="${formatCurrencyEdit(room.discount ?? 0)}" placeholder="0">
                                            </td>
                                            <td>
                                                <input type="text" name="note_room" class="form-control note_room" value="${room.note}" id="note">
                                            </td>
    
    
                                        </tr>
                                    `;
                                tbody.append(tr);
                            });
                        });
                        totalPrice = calculateTotalPrice();
                        $('.total_deposit').text(formatCurrency(total_deposit_amount));

                        console.log(totalPrice);

                        $('.total_discount').text(formatCurrency(total_deposit_discount));

                        $('.total_amount').text(formatCurrency(totalPrice));
                        $('.total_balance').text(formatCurrency(totalPrice));
                        $('#loading').hide();
                        let totalDeposit = 0;
                        let totalBalance = 0;

                        function calculateDepositAndBalance() {
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
                            let price = parseInt(priceString.replace(/\./g, '')) || 0;

                            totalBalance = totalPrice - rowTotal - price;
                            $('.total_balance').text(formatCurrency(totalBalance));
                            //  $('.total_deposit').text(formatCurrency(price));
                        }

                        // Chạy khi trang load
                        $(document).ready(function () {
                            calculateDepositAndBalance();
                        });
                        $(document).on('blur', 'input.deposit', function () {
                            let rowTotal = 0;
                            $('tr').each(function () {
                                $(this).find('input.deposit').each(function () {
                                    let depositValue = $(this).val().replace(/[,.]/g, '');
                                    let numericDeposit = parseInt(depositValue) || 0;
                                    rowTotal += numericDeposit;
                                });
                            });

                            $('.total_deposit').text(formatCurrency(rowTotal));

                            let priceString = $('.total_discount').text();
                            let price = parseInt(priceString.replace(/\./g, ""), 10);
                            price = isNaN(price) ? 0 : price;

                            let total_amount = $('.total_amount').text();
                            let total_amount_price = parseInt(total_amount.replace(/\./g, '')) || 0;
                            total_amount_price = isNaN(total_amount_price) ? 0 : total_amount_price;

                            totalBalance = total_amount_price - rowTotal - price;



                            $('.total_balance').text(formatCurrency(totalBalance));
                        });

                        $(document).on('blur', 'input.discount', function () {
                            let rowTotal = 0;
                            $('tr').each(function () {
                                $(this).find('input.discount').each(function () {
                                    let depositValue = $(this).val().replace(/[,.]/g, '');
                                    let numericDeposit = parseInt(depositValue) || 0;
                                    rowTotal += numericDeposit;
                                });
                            });
                            $('.total_discount').text(formatCurrency(rowTotal));
                            let priceString = $('.total_deposit').text();

                            let price = parseInt(priceString.replace(/\./g, ""), 10);
                            price = isNaN(price) ? 0 : price;
                            let total_amount = $('.total_amount').text();
                            let total_amount_price = parseInt(total_amount.replace(/\./g, '')) || 0;
                            total_amount_price = isNaN(total_amount_price) ? 0 : total_amount_price;
                            totalBalance = total_amount_price - rowTotal - price;


                            $('.total_balance').text(formatCurrency(totalBalance));
                        });
                        $('#addBookedRoom').modal('hide');
                        $('#loading').hide();


                    });
                    // notify('success', response.success);
                    // loadRoomBookings();

                } else {
                    notify('error', response.success);
                }
            },
            error: function (error) {
                $('#loading').hide();
                // notify('error', error.responseJSON.message);
                console.log('Error:', error);
            }
        });
    }
    let selectedItems = [];
    var productList = document.getElementById('productList');
    var selectedItemsBox = document.getElementById('selectedItems');
    var searchInput = document.getElementById('searchInput');

    var currentFilter = 'Tất cả';
    function renderList(data) {
        if (!Array.isArray(data)) {
            console.warn('renderList nhận data không hợp lệ:', data);
            return;
        }

        const search = searchInput.value?.toLowerCase() ?? '';
        productList.innerHTML = '';

        if (data.length > 0) {
            const filtered = data.filter(item =>
                (currentFilter === 'Tất cả' || item.category === currentFilter) &&
                item.name.toLowerCase().includes(search)
            );

            filtered.forEach(item => {
                const col = document.createElement('div');
                col.className = `col ${item.type}`;

                const product = document.createElement('div');
                product.className = 'service-product-item';

                product.innerHTML = `
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRhFX4Bsx5WvQfDXoJsgXQQG-ae0GpzcPcmkQ&s" class="service-product-img">
                <div><strong>${item.name}</strong></div>
                <div>${formatCurrency(item.price)}</div>
            `;

                product.addEventListener('click', () => {
                    selectItem(item);
                });

                col.appendChild(product);
                productList.appendChild(col);
            });
        } else {
            productList.innerHTML = '<p>Không có dữ liệu.</p>';
        }
    }
    function renderSelected() {
        let totalPrice = 0;
        if (selectedItems.length === 0) {
            selectedItemsBox.innerHTML = '<div class="text-muted">Chưa có dịch vụ, sản phẩm</div>';
            $('.total_product_service_display').text(0);
            return;
        }

        let sumService = selectedItems.reduce((sum, item) => {
            const value = parseFloat(item.price * item.quantity);
            return sum + (isNaN(value) ? 0 : value);
        }, 0);

        $('.total_product_service_display').text(formatCurrency(sumService));
        totalPrice = calculateTotalPrice();

        let totalpayment = $('.total_payment').text(); // tổng thanh toán
        let total_payment = parseInt(totalpayment.replace(/\./g, '')) || 0;
        // giảm giá
        let total_discount = $('.total_discount').text();
        let total_discount_price = parseInt(total_discount.replace(/\./g, '')) || 0;
        // tiên dich vụ

        let priceString = $('.total_deposit').text();
        // đặt cọc
        let price = parseInt(priceString.replace(/\./g, ""), 10);
        price = isNaN(price) ? 0 : price;

        totalBalance = totalPrice + sumService - total_payment - price - total_discount_price;
        $('.total_balance').text(formatCurrency(totalBalance));

        selectedItemsBox.innerHTML = selectedItems.map((item, index) => `
        <div class="row align-items-center border rounded p-1 mb-2 bg-white gx-1" data-id="${item.id}" data-type="${item.type}">
          <div class="col-md-4 fw-bold text-dark">${item.name}</div>
            <div class="col-md-2 text-end text-primary no-wrap price-service">${formatCurrency(item.price)}</div>
                <div class="col-md-4 d-flex align-items-center justify-content-center gap-1">
                    <button class="btn btn-sm btn-outline-secondary" onclick="changeQty(${index}, -1)">-</button>
                        <input type="number" min="1" value="${item.quantity}" onchange="updateQty(${index}, this.value)" max="${item.stock ?? item?.product?.stock}" class="form-control form-control-sm text-center" style="width: 60px;">
                    <button class="btn btn-sm btn-outline-secondary" onclick="changeQty(${index}, 1)">+</button>
                </div>
            <div class="col-md-2 text-center">
            <button class="btn btn-sm btn-outline-danger remove-btn-service"
             data-id="${item?.product_id ?? item?.service_id}" 
             data-check-in-id="${item?.check_in_id}" 
             data-room-id="${item?.room_code}"
             data-index="${index}">✕</button>
          </div>
        </div>
      `).join('');
    }

    window.updateQty = function (index, qty) {
        let quantity = parseInt(qty);
        if (isNaN(quantity) || quantity < 1) quantity = 1;

        const maxStock = selectedItems[index].stock ?? selectedItems[index]?.product?.stock;
        console.log(maxStock);

        if (quantity > maxStock) quantity = maxStock;

        selectedItems[index].quantity = quantity;
        renderSelected();
    }


    window.changeQty = function (index, delta) {
        let newQty = selectedItems[index].quantity + delta;
        if (newQty < 1) newQty = 1;

        const maxStock = selectedItems[index].stock ?? selectedItems[index]?.product?.stock;
        if (newQty > maxStock) newQty = maxStock;

        selectedItems[index].quantity = newQty;
        renderSelected();
    }



    searchInput.addEventListener('input', renderList);
    renderList();
    window.selectItem = function (item) {
        const existing = selectedItems.find(i => i.id === item.id);
        console.log(item);

        if (existing) {
            if (item.type === 'product') {
                if (existing.quantity < item.stock) {
                    existing.quantity += 1;
                } else {
                    alert('Đã đạt số lượng tối đa trong kho!');
                }
            } else {
                // Các loại khác (dịch vụ...) vẫn tăng bình thường
                existing.quantity += 1;
            }
        } else {
            if (item.type === 'product') {
                if (item.stock > 0) {
                    selectedItems.push({ ...item, quantity: 1 });
                } else {
                    alert('Sản phẩm này đã hết hàng!');
                }
            } else {
                // Các loại khác vẫn cho thêm luôn
                selectedItems.push({ ...item, quantity: 1 });
            }
        }

        renderSelected();
    }
    $(document).off("click", ".add_product_service_payment").on("click", ".add_product_service_payment", function (e) {
        e.stopPropagation();

        const button = document.getElementById("add-service-room-booking");

        button.setAttribute('data-room-id', $(this).attr('data-room-id'));
        button.setAttribute('data-coustomer', $(this).attr('data-coustomer'));
        button.setAttribute('data-room', $(this).attr('data-room'));
        button.setAttribute('data-id', $(this).attr('data-id'));

        $('#add-service-room-booking').off("click").on("click", function () {
            var roomId = $(this).attr('data-room-id');
            var checkInId = $(this).attr('data-id');
            const $select = $('.warehouses-selected');
            $.ajax({
                url: getAllService,
                type: 'GET',
                data: {
                    search: "",
                    room_code: roomId,
                    check_in_id: checkInId,
                    warehouses_id: $select.val(),
                },
                success: function (data) {
                    if (data.status === 'success') {
                        renderList(data.data)
                        let total_service = 0;
                        if (data.serviceInRoom && data.serviceInRoom.length > 0) {
                            selectedItems = data.serviceInRoom.map(item => {
                                return {
                                    ...item,
                                    quantity: item.quantity,
                                    name: item?.product?.name ?? item?.service?.name,
                                    id: item?.product?.id ?? item?.service?.id,
                                };
                            });
                            renderSelected();
                        } else {
                            selectedItemsBox.innerHTML = '<div class="text-muted">Chưa có dịch vụ, sản phẩm</div>';
                            $('.total_product_service_display').text(0);
                        }

                    }

                },
                error: function (error) {
                    $('#loading').hide();
                    console.log('Error:', error);
                }
            });
            const modal = new bootstrap.Modal(document.getElementById('serviceModal'));
            $('.service-room-number').text($(this).attr('data-room'));
            $('#room_code_service').val($(this).data("room-id"));
            $('#check_in_id_service').val($(this).data("id"));
            $('.service-customer').text($(this).data("coustomer"));
            modal.show();

        });
    });
    $('.btn-add-service').on('click', function () {
        const $select = $('.warehouses-selected');
        let grouped = {
            premium_service: [],
            product: [],
            checkin_id: $('#check_in_id_service').val(),
            room_code: $('#room_code_service').val(),
            warehouse_id: $select.val(),
        };

        $('#selectedItems .row').each(function () {
            const type = $(this).data('type');
            const id = $(this).data('id');
            const qty = $(this).find('input[type="number"]').val();

            // Lấy giá từ cột thứ 2 (có class text-primary), bỏ "VND" và dấu chấm
            let priceText = $(this).find('.text-primary').text().trim(); // VD: "30.000 VND"
            let price = parseInt(priceText.replace(/\./g, '').replace(/[^0-9]/g, ''));
            //123456
            if (grouped[type]) {
                grouped[type].push({
                    id: id,
                    quantity: parseInt(qty),
                    price: price,
                });
            } else {
                grouped[type] = [{
                    id: id,
                    quantity: parseInt(qty),
                    price: price,
                }];
            }
        });

        $.ajax({
            url: storeService,
            type: 'POST',
            data: {
                data: grouped,
            },
            success: function (data) {
                if (data.status === 'success') {
                    notify('success', data.message);
                    $('#serviceModal').modal('hide');
                    const calcTotal = (arr) => {
                        return arr.reduce((sum, item) => {
                            const subtotal = parseFloat(item.price) * parseInt(item.quantity);
                            return sum + (isNaN(subtotal) ? 0 : subtotal);
                        }, 0);
                    };
                    const totalPremium = calcTotal(grouped.premium_service);
                    const totalProduct = calcTotal(grouped.product);
                    const grandTotal = totalPremium + totalProduct;
                    $('.total_service_display').text(formatCurrency(grandTotal)); // hiển thị 
                    $('#total_service').val(grandTotal);
                }

            },
            error: function (error) {
                $('#loading').hide();
                console.log('Error:', error);
            }
        });
    });
    function checkIn(Id, dataId) {
        const date_booking = new Date();
        const date_yyyy = date_booking.getFullYear();
        const date_mm = String(date_booking.getMonth() + 1).padStart(2, '0');
        const date_dd = String(date_booking.getDate()).padStart(2, '0');
        const date_hour = String(date_booking.getHours()).padStart(2, '0'); // Giờ
        const date_minutes = String(date_booking.getMinutes()).padStart(2, '0'); // Phút
        $('.booking-form-pttt').attr('action', paymentRoomUrl); // thanh toán
        $('#select-option-pttt_error1').text('');
        const formattedDates = `${date_yyyy}-${date_mm}-${date_dd}`;
        const formattedTimes = `${date_hour}:${date_minutes}`;
        var url = checkInEditUrl.replace(':id', dataId);

        $('[id="date-book-room-booking-edit"]').val(formattedDates);
        // $('[id="date-book-room-booking"]').val(formattedDates);
        // $('[id="time-book-room-booking"]').val(formattedTimes);
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                method: 'LETAN',
                id: Id,
            },
            success: function (response) {
                if (response.status == 'success') {
                    var selected_customer_source = $('#select-customer-source-edit-letan');
                    selected_customer_source.empty();
                    let option = `<option value="">Chọn nguồn khách hàng</option>`;
                    response.customerSourse.forEach(function (item) {
                        if (item.source_code == response.option_customer_source) {
                            option += `<option value="${item.source_code}" selected>${item.source_name}</option>`;
                        } else {
                            option += `<option value="${item.source_code}">${item.source_name}</option>`;
                        }

                    });
                    //title 
                    $('.pageModal').text(response.pageModal);
                    selected_customer_source.append(option);

                    // nhân viên
                    var selected_select_staff = $('#select-staff-edit-letan');
                    selected_select_staff.empty();
                    let option_staff = `<option value="">Chọn nhân viên</option>`;
                    response.admin.forEach(function (item) {
                        option_staff += `<option value="${item.id}">${item.username}</option>`;
                    });
                    selected_select_staff.append(option_staff);
                    $('#myModal-check-in-edit').modal('show').on('shown.bs.modal', function () {
                        $('.name-edit, .phone-edit').val('');
                        $('#list-booking-edit').empty();
                        $('#list-booking').empty();
                        $('#list-booking-edit-letan').empty();
                        var tbody = $('#list-booking-edit-letan');
                        let totalPrice, total_deposit_amount, total_deposit_discount = 0;
                        response.data.forEach(item => {
                            $('.name-edit').val(item.customer_name);
                            $('.phone-edit').val(item.phone_number);
                            const button = document.getElementById("add-service-room-booking");
                            item.room_bookings.forEach((room, index) => {
                                if (index === 0) {
                                    $('.id_room_booking').val(room.booking_id);
                                }

                                button.setAttribute('data-room-id', room.room_id);
                                button.setAttribute('data-coustomer', item.customer_name);
                                button.setAttribute('data-room', room.room_number);
                                button.setAttribute('data-id', room.booking_id);

                                let [checkinDate, checkinTime] = room.checkin_date.split(' ');
                                checkinTime = checkinTime.slice(0, 5);
                                let [checkoutDate, checkoutTime] = room
                                    .checkout_date.split(' ');
                                checkoutTime = checkoutTime.slice(0, 5);
                                total_deposit_amount += parseFloat(room.deposit_amount);
                                total_deposit_discount += parseFloat(room.discount);


                                var tr = `
                                        <tr data-room-id="${room.room_id}"
                                        data-room-booking-id="${room.id}"
                                        data-room-type-id="${room.room_type_id}"
                                        class="${room.status === 1 ? "check_in_status" : ""}">
                                            <td>
                                                <input type="checkbox">
                                            </td>
    
                                            <td>
                                                <p class="room__name"> ${room.room_number}</p>
                                            </td>
                                            <td>
                                                <input type="number" min="1" name="adult" class="form-control adult"  value="${room.guest_count}"  style="margin-left: 16px;">
                                            </td>
                                            <td >
                                                <select id="bookingType" class="form-select" name="optionRoom" style="width: 93px; font-size:15px">
                                                    <option value="ngay">Ngày</option>
                                                    <option value="gio">Giờ</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-start" style="gap: 10px">
                                                    <input type="date" name="checkInDate" id="date-book-room" class="form-control date-book-room"  value="${formattedDates}" readonly>
    
                                                    <input type="time" name="checkInTime" id="time-book-room" class="form-control time-book-room"  value="${checkinTime}" >
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-start" style="gap: 10px">
                                                    <input type="date" name="checkOutDate"  class="form-control date-book-room" value="${checkoutDate}" readonly>
    
                                                    <input type="time" name="checkOutTime" id="time-book-room" class="form-control time-book-room"  value="${checkoutTime}" >
    
                                                </div>
                                            </td>
                                            <td>
                                                <p id="price" data-price="${room.total_amount}">${formatCurrency(room.total_amount)}</p>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control deposit number-input money-input"  name="deposit" value="${formatCurrencyEdit(room.deposit_amount)}" placeholder="0">
                                            </td>
                                              <td>
                                                <input type="text" class="form-control discount number-input-discount money-input"   name="discount" value="${formatCurrencyEdit(room.discount ?? 0)}" placeholder="0">
                                            </td>
                                            <td>
                                                <input type="text" name="note_room" class="form-control note_room" value="${room.note}" id="note">
                                            </td>
                                              <td class="d-none">
                                                <input type="text" name="payment" class="form-control money-input payment" value="${formatCurrencyEdit(response.payment)}" id="payment">
                                            </td>
                                            <td class="d-none">
                                                <input type="text" name="total_service" class="form-control money-input total_service" value="${formatCurrencyEdit(room.total_service ?? 0)}" id="total_service">
                                            </td>
    
                                        </tr>
                                    `;
                                tbody.append(tr);
                            });
                        });
                        totalPrice = calculateTotalPrice();






                        // tiên dich vụ
                        let intValueService = parseInt($('#total_service').val().replace(/\./g, ''));
                        $('.total_service_display').text(formatCurrency(intValueService));
                        $('.total_deposit').text(formatCurrency(total_deposit_amount));
                        //$('.total_discount').text(formatCurrency(total_deposit_discount));
                        $('.total_amount').text(formatCurrency(totalPrice));
                        $('.total_balance').text(formatCurrency(totalPrice));


                        $('#loading').hide();
                        let totalDeposit = 0;
                        let totalBalance = 0;

                        function calculateDepositAndBalance() {
                            let rowTotal = 0;
                            let payment = 0;
                            let service = 0;
                            // đặt cọc
                            $('tr').each(function () {
                                $(this).find('input.deposit').each(function () {
                                    let depositValue = $(this).val()
                                        .replace(/[,.]/g, '');
                                    let numericDeposit = parseInt(
                                        depositValue) || 0;
                                    rowTotal += numericDeposit;
                                });
                            });
                            // giảm giá
                            $('tr').each(function () {
                                $(this).find('input.payment').each(function () {
                                    let depositValue = $(this).val()
                                        .replace(/[,.]/g, '');
                                    let numericDeposit = parseInt(
                                        depositValue) || 0;
                                    payment = numericDeposit;
                                });
                            });
                            //dịch vụ
                            $('tr').each(function () {
                                $(this).find('input.total_service').each(function () {
                                    let totalServiceValue = $(this).val()
                                        .replace(/[,.]/g, '');


                                    let numericDeposit = parseInt(
                                        totalServiceValue) || 0;
                                    service += numericDeposit;
                                });
                            });

                            $('.total_service_display').text(formatCurrency(service));
                            let serviceString = $('.total_service_display').text();
                            let service_payment = parseInt(serviceString.replace(/\./g, '')) || 0;
                            $('.total_deposit').text(formatCurrency(rowTotal));

                            let priceString = $('.total_discount').text();
                            let price = parseInt(priceString.replace(/\./g, '')) || 0;


                            $('.total_payment').text(formatCurrency(payment));

                            let paymentString = $('.total_payment').text();
                            let total_payment = parseInt(paymentString.replace(/\./g, '')) || 0;


                            totalBalance = totalPrice - rowTotal - price - total_payment + service_payment;

                            if (totalBalance > 0) {
                                $('#checkout_room').prop('disabled', true);
                                $('#print_invoice').prop('disabled', true);
                                $('#print_sales_invoice').prop('disabled', true);
                            } else {
                                $('#checkout_room').prop('disabled', false);
                                $('#print_invoice').prop('disabled', false);
                                $('#print_sales_invoice').prop('disabled', false);
                            }


                            $('.total_balance').text(formatCurrency(totalBalance));

                        }

                        // Chạy khi trang load
                        $(document).ready(function () {
                            calculateDepositAndBalance();
                        });


                        // đặt cọc
                        $(document).on('blur', 'input.deposit', function () {
                            let rowTotal = 0;
                            $('tr').each(function () {
                                $(this).find('input.deposit').each(function () {
                                    let depositValue = $(this).val().replace(/[,.]/g, '');
                                    let numericDeposit = parseInt(depositValue) || 0;
                                    rowTotal += numericDeposit;
                                });
                            });

                            let total_deposit = $('.total_deposit').text();
                            let total_deposit_price = parseInt(total_deposit.replace(/\./g, '')) || 0;

                            if (total_deposit_price == rowTotal) {
                                return;
                            }
                            $('.total_deposit').text(formatCurrency(rowTotal));

                            let priceString = $('.total_discount').text();
                            let price = parseInt(priceString.replace(/\./g, ""), 10);
                            price = isNaN(price) ? 0 : price;
                            // tiền phòng
                            let total_amount = $('.total_amount').text();
                            let total_amount_price = parseInt(total_amount.replace(/\./g, '')) || 0;
                            total_amount_price = isNaN(total_amount_price) ? 0 : total_amount_price;
                            // giảm giá
                            let total_discount = $('.total_discount').text();
                            let total_discount_price = parseInt(total_discount.replace(/\./g, '')) || 0;
                            // đã thanh toán
                            let total_payment = $('.total_payment').text();
                            let total_payment_price = parseInt(total_payment.replace(/\./g, '')) || 0;
                            // tiên dich vụ
                            let total_service = $('.total_service_display').text();
                            let total_service_price = parseInt(total_service.replace(/\./g, '')) || 0;
                            totalBalance = total_amount_price + total_service_price - rowTotal - price - total_discount_price - total_payment_price;
                            console.log('đặt cọc:' + totalBalance);
                            $('.total_balance').text(formatCurrency(totalBalance));
                        });
                        // giảm giá
                        $(document).on('blur', 'input.discount', function () {
                            let rowTotal = 0;
                            $('tr').each(function () {
                                $(this).find('input.discount').each(function () {
                                    let depositValue = $(this).val().replace(/[,.]/g, '');
                                    let numericDeposit = parseInt(depositValue) || 0;
                                    rowTotal += numericDeposit;
                                });
                            });
                            let total_payment = $('.total_payment').text();
                            let total_payment_price = parseInt(total_payment.replace(/\./g, '')) || 0;
                            if (total_payment_price == rowTotal) {
                                return;
                            }
                            $('.total_discount').text(formatCurrency(rowTotal));
                            let priceString = $('.total_deposit').text();

                            let price = parseInt(priceString.replace(/\./g, ""), 10);
                            price = isNaN(price) ? 0 : price;
                            let total_amount = $('.total_amount').text();
                            let total_amount_price = parseInt(total_amount.replace(/\./g, '')) || 0;
                            total_amount_price = isNaN(total_amount_price) ? 0 : total_amount_price;
                            // giảm giá
                            let total_discount = $('.total_discount').text();
                            let total_discount_price = parseInt(total_discount.replace(/\./g, '')) || 0;
                            // đã thanh toán

                            // tiên dich vụ
                            let total_service = $('.total_service_display').text();
                            let total_service_price = parseInt(total_service.replace(/\./g, '')) || 0;

                            // tính số dư 
                            totalBalance = total_amount_price - rowTotal - price - total_discount_price - total_payment_price + total_service_price;

                            console.log('giảm giá:' + totalBalance);
                            $('.total_balance').text(formatCurrency(totalBalance));
                        });
                        // khách thanh toán
                        $(document).on('blur', 'input.css-main-input-pttt', function () {
                            let rowTotal = 0;

                            $('input.css-main-input-pttt').each(function () {
                                let depositValue = $(this).val().replace(/[,.]/g, '');
                                let numericDeposit = parseInt(depositValue) || 0;
                                rowTotal += numericDeposit;
                            });

                            // Cập nhật phần hiển thị tổng (không cập nhật lại input đang nhập)
                            $('.total_entered_deposit').text(formatCurrency(rowTotal));
                            console.log(rowTotal);

                            // Lấy giá trị tiền đã cọc
                            let priceString = $('.total_deposit').text();
                            let price = parseInt(priceString.replace(/\./g, "")) || 0;

                            // Tổng tiền cần thanh toán
                            let total_amount = $('.total_amount').text();
                            let total_amount_price = parseInt(total_amount.replace(/\./g, '')) || 0;


                            let total_discount = $('.total_discount').text();
                            let total_discount_price = parseInt(total_discount.replace(/\./g, '')) || 0;

                            let total_payment = $('.total_payment').text();
                            let total_payment_price = parseInt(total_payment.replace(/\./g, '')) || 0;
                            // tiên dich vụ
                            let total_service = $('.total_service_display').text();
                            let total_service_price = parseInt(total_service.replace(/\./g, '')) || 0;
                            // Tính số dư
                            let totalBalance = total_amount_price - rowTotal - price - total_discount_price - total_payment_price + total_service_price;
                            console.log(' thanh toán:' + totalBalance);
                            if (totalBalance > 0) {


                                $('#checkout_room').prop('disabled', true);
                                $('#print_sales_invoice').prop('disabled', true);
                                $('#print_invoice').prop('disabled', true);
                            } else {


                                $('#checkout_room').prop('disabled', false);
                                $('#print_sales_invoice').prop('disabled', false);
                                $('#print_invoice').prop('disabled', false);
                            }
                            $('.total_balance').text(formatCurrency(totalBalance));
                        });

                    });
                    // notify('success', response.success);
                    // loadRoomBookings();

                } else {
                    notify('error', response.success);
                }
            },
            error: function (error) {
                // notify('error', error.responseJSON.message);
                console.log('Error:', error);
            }
        });
    }
    function validatePhone(value) {
        const allErrors = document.querySelectorAll("[id='phone_error']");
        const phoneInputs = document.querySelectorAll("input[name='phone']");
        const trimmed = value.trim();

        let isValid = true;

        allErrors.forEach((errorSpan, index) => {
            const input = phoneInputs[index];

            if (trimmed === "") {
                errorSpan.textContent = "Số điện thoại không để trống.";
                input.classList.add("is-invalid");
                isValid = false;
            } else if (!/^\d+$/.test(trimmed)) {
                errorSpan.textContent = "Số điện thoại chỉ được chứa chữ số.";
                input.classList.add("is-invalid");
                isValid = false;
            } else {
                errorSpan.textContent = "";
                input.classList.remove("is-invalid");
            }
        });

        return isValid;
    }
    $('.btn-book-pttt').off('click').on('click', function (e) {


        e.preventDefault();
        function validator(selectedOption, inputPtttValue) {
            let isValid = true;

            // Reset lỗi cũ
            $('#select-option-pttt_error1').text('');
            $('#input_pttt_error').text('');

            // Kiểm tra chọn phương thức thanh toán
            if (selectedOption === '') {
                $('#select-option-pttt_error1').text('Vui lòng chọn phương thức thanh toán');
                isValid = false;
            }

            // Kiểm tra ô nhập số tiền
            if (!inputPtttValue || inputPtttValue.trim() === '' || inputPtttValue <= 0) {
                $('#input_pttt_error').text('Vui lòng nhập số tiền');
                isValid = false;
            }

            return isValid;
        }
        const selectedOption = $('#select-option-pttt-main').val();
        const inputPtttValue = $('#input_pttt').val();
        const method = $(this).attr('data-method');

        if (method === "check_in") {
            $('.booking-form-pttt').submit();
        } else if (method == "payment") {// thanh toán
            if (validator(selectedOption, inputPtttValue)) {
                $('.booking-form-pttt').submit(); // Gửi form nếu hợp lệ
            }
        }
        else {
            // sửa thông tin nhận phòng
            $('.booking-form-pttt').submit();
        }

    });
    $('.booking-form-pttt').on('submit', function (e) {
        let selectedBookingIds = [];
        e.preventDefault();
        let formData = $(this).serializeArray();
        let formObject = {};
        formData.forEach(function (field) {
            formObject[field.name] = field.value;
        });
        if (!validatePhone(formData[3]['value'])) {
            return;
        }

        let url = $(this).attr('action');
        var roomData = [];
        var filteredRoomData = [];
        const method = $('.btn-book-pttt').attr('data-method');
        //1234567
        $('#list-booking-edit-letan tr').each(function () {

            var checkbox = $(this).find('input[type="checkbox"]');
            if (checkbox.prop('checked')) {
                var bookingId = $(this).data('room-booking-id');
                selectedBookingIds.push(bookingId);
                // $(this).remove();
            }
            var priceRoom = $(this).data('price');
            var roomBookingId = $(this).data('room-booking-id');
            var roomId = $(this).data('room-id');
            var roomTypeId = $(this).data('room-type-id');
            var checkInDate = $(this).find('input[name="checkInDate"]').val();
            var checkInTime = $(this).find('input[name="checkInTime"]').val();
            var checkOutDate = $(this).find('input[name="checkOutDate"]').val();
            var checkOutTime = $(this).find('input[name="checkOutTime"]').val();
            var adult = $(this).find('input[name="adult"]').val();
            var note = $(this).closest('tr').find('input[name="note_room"]').val();
            var deposit = $(this).closest('tr').find('input[name="deposit"]').val();
            var discount = $(this).closest('tr').find('input[name="discount"]').val();

            roomData.push({
                roomId: roomId,
                roomTypeId: roomTypeId,
                checkInDate: checkInDate,
                checkInTime: checkInTime,
                checkOutDate: checkOutDate,
                checkOutTime: checkOutTime,
                adult: adult,
                note: note,
                deposit: deposit,
                discount: discount,
                roomBookingId: roomBookingId,
                priceRoom: priceRoom,
            });





        });
        if (method == "check_in") {
            if (selectedBookingIds.length === 0) {
                notify('error', 'Vui lòng chọn phòng')
                return; // Dừng xử lý tiếp theo
            }
            filteredRoomData = roomData.filter(item => selectedBookingIds.includes(item.roomBookingId));
        } else {
            filteredRoomData = roomData;
        }

        filteredRoomData.forEach(function (item) {
            const roomDates = getDatesBetween(item['checkInDate'], item['checkInTime'],
                item['checkOutDate'], item['checkOutTime'], item['roomId'], item['roomTypeId'],
                item['adult'], item['note'], item['deposit'], item['discount'], item['roomBookingId'], item['priceRoom']);

            roomDates.forEach(function (date, index) {
                formData.push({
                    name: 'room[]',
                    value: JSON.stringify(date)
                });
            });
        })
        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            success: function (response) {

                if (response.status === 'success') {
                    notify('success', response.success);
                    $('#myModal-check-in-edit').modal('hide');
                    $('#input_pttt').val('');
                    console.log(response.total);

                    $('.total_payment').text(formatCurrency(response.total));
                    let selectedDate = $('#startDate').val();
                    initGridMain('', selectedDate);
                } else {
                    notify('error', response.success);
                    $('#input_pttt_error').text(response.errors['amount'] ?? "");
                    $('#select-option-pttt_error1').text(response.errors['payment_pttt'] ?? "");
                }
            },
        });
    });
    function getDatesBetween(checkInDate, checkInTime, checkOutDate, checkOutTime, room, roomType, adult, note,
        deposit, discount, roomBookingId, priceRoom) {

        let dates = [];
        let currentDate = new Date(checkInDate);
        let currentDateOut = new Date(checkOutDate);

        const [checkOutHours, checkOutMinutes] = checkOutTime.split(':').map(Number);
        const [checkInHours, checkInMinutes] = checkInTime.split(':').map(Number);

        while (currentDate && currentDateOut) {
            currentDate.setHours(checkInHours);
            currentDate.setMinutes(checkInMinutes);
            currentDate.setSeconds(0); // Đặt giây về 0

            currentDateOut.setHours(checkOutHours);
            currentDateOut.setMinutes(checkOutMinutes);
            currentDateOut.setSeconds(0); // Đặt giây về 0

            let formattedDate =
                `${currentDate.getMonth() + 1}/${String(currentDate.getDate()).padStart(2, '0')}/${currentDate.getFullYear()} ` +
                `${String(currentDate.getHours()).padStart(2, '0')}:${String(currentDate.getMinutes()).padStart(2, '0')}:${String(currentDate.getSeconds()).padStart(2, '0')}`;

            let formattedDateOut =
                `${currentDateOut.getMonth() + 1}/${String(currentDateOut.getDate()).padStart(2, '0')}/${currentDateOut.getFullYear()} ` +
                `${String(currentDateOut.getHours()).padStart(2, '0')}:${String(currentDateOut.getMinutes()).padStart(2, '0')}:${String(currentDateOut.getSeconds()).padStart(2, '0')}`;
            dates.push({
                roomType: roomType,
                room: room,
                dateIn: formattedDate,
                dateOut: formattedDateOut,
                adult: adult,
                note: note,
                deposit: deposit,
                discount: discount,
                bookingId: roomBookingId,
                priceRoom: priceRoom,
            });
            break;
        }
        return dates;
    }
    $('.btn-check-in-room').on('click', function (e) {

        let selectedBookingIds = [];
        e.preventDefault();
        // let formData = $(this).serializeArray();
        let formData = $('.booking-form-edit').serializeArray();
        let formObject = {};
        formData.forEach(function (field) {
            formObject[field.name] = field.value;
        });
        // if (!validatePhone(formData[3]['value'])) {
        //     return;
        // }


        var roomData = [];
        var filteredRoomData = [];
        const method = $('.btn-check-in-room').attr('data-method');
        const url = $('.btn-check-in-room').attr('data-url');
        //1234567
        $('#list-booking-edit tr').each(function () {

            var checkbox = $(this).find('input[type="checkbox"]');
            if (checkbox.prop('checked')) {
                var bookingId = $(this).data('room-booking-id');
                selectedBookingIds.push(bookingId);
                // $(this).remove();
            }
            var priceRoom = $(this).data('price');
            var roomBookingId = $(this).data('room-booking-id');
            var roomId = $(this).data('room-id');
            var roomTypeId = $(this).data('room-type-id');
            var checkInDate = $(this).find('input[name="checkInDate"]').val();
            var checkInTime = $(this).find('input[name="checkInTime"]').val();
            var checkOutDate = $(this).find('input[name="checkOutDate"]').val();
            var checkOutTime = $(this).find('input[name="checkOutTime"]').val();
            var adult = $(this).find('input[name="adult"]').val();
            var note = $(this).closest('tr').find('input[name="note_room"]').val();
            var deposit = $(this).closest('tr').find('input[name="deposit"]').val();
            var discount = $(this).closest('tr').find('input[name="discount"]').val();

            roomData.push({
                roomId: roomId,
                roomTypeId: roomTypeId,
                checkInDate: checkInDate,
                checkInTime: checkInTime,
                checkOutDate: checkOutDate,
                checkOutTime: checkOutTime,
                adult: adult,
                note: note,
                deposit: deposit,
                discount: discount,
                roomBookingId: roomBookingId,
                priceRoom: priceRoom,
            });





        });
        if (method == "check_in") {
            if (selectedBookingIds.length === 0) {
                notify('error', 'Vui lòng chọn phòng')
                return; // Dừng xử lý tiếp theo
            }
            filteredRoomData = roomData.filter(item => selectedBookingIds.includes(item.roomBookingId));
        } else {
            filteredRoomData = roomData;
        }

        filteredRoomData.forEach(function (item) {
            const roomDates = getDatesBetween(item['checkInDate'], item['checkInTime'],
                item['checkOutDate'], item['checkOutTime'], item['roomId'], item['roomTypeId'],
                item['adult'], item['note'], item['deposit'], item['discount'], item['roomBookingId'], item['priceRoom']);

            roomDates.forEach(function (date, index) {
                formData.push({
                    name: 'room[]',
                    value: JSON.stringify(date)
                });
            });
        })
        let checkInUrl = url;

        $.ajax({
            type: "POST",
            url: checkInUrl,
            data: formData,
            success: function (response) {

                if (response.status === 'success') {
                    notify('success', response.success);
                    $('#myModal-booking-edit').modal('hide');
                    $('#input_pttt').val('');


                    $('.total_payment').text(formatCurrency(response.total));
                    let selectedDate = $('#startDate').val();
                    initGridMain('', selectedDate);
                } else {
                    notify('error', response.success);
                    $('#input_pttt_error').text(response.errors['amount'] ?? "");
                    $('#select-option-pttt_error1').text(response.errors['payment_pttt'] ?? "");
                }
            },
        });
    })
    function parseLinearGradient(gradientString) {
        let result = [];
        let regex = /(rgb\(\d+, \d+, \d+\)|transparent) (\d+(\.\d+)?)%/g;
        let matches;

        while ((matches = regex.exec(gradientString)) !== null) {
            let color = matches[1]; // Lấy màu (rgb hoặc transparent)
            let percent = parseFloat(matches[2]); // Lấy phần trăm

            result.push({ color, percent });
        }

        // Chỉ giữ lại 2 giá trị cuối cùng
        if (result.length > 2) {
            result = result.slice(-2);
        }

        // Kiểm tra vị trí của `transparent` và thêm "direction"
        if (result.length === 2) {
            if (result[1].color === "transparent") {
                result[1].direction = "PHAI"; // Nếu transparent ở cuối
            } else if (result[0].color === "transparent") {
                result[0].direction = "TRAI"; // Nếu transparent ở đầu
            }
        }

        return result;
    }
    document.getElementById("startDate").addEventListener("change", generateTable);
    document.getElementById("endDate").addEventListener("change", generateTable);

    setInterval(updateRealtime, 1800000);
    initDates();
};
initGridMain("", "");
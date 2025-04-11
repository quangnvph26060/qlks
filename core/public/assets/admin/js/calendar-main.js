function initGridMain(data) {
    const savedView = localStorage.getItem('selectedView') || 'list';

    let htmlrow = '';
    if (savedView === "calendar") {
        htmlrow = `  <input type="date" id="startDate" class="form-control w-auto" style="height: 40px"
                        placeholder="Từ ngày">
                    <input type="date" id="endDate" class="form-control w-auto" style="height: 40px"
                        placeholder="Đến ngày"></input>`
            ;
        document.getElementById('date-input-booking').innerHTML = htmlrow;
    }

    function getAllDatesInTableBooking() {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: "GET",
                url: roomBoookingHistory,
                data: {data},
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
                    let row = `<tr><td class="text-left" 
                    data-room-type-id="${room['room_type_id']}" 
                     data-room-id="${room['id']}" 
                    data-price=${room['room_type']['room_type_price']['unit_price']} 

                    >${room.room_number}</td>`;


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

                                if (booking.status_code == 2) {
                                    color = "#ebb579";
                                    className = "room_book";
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
                                    color = "#e6454d";
                                    className = "check_in_room";
                                    // bookingId = booking.check_in_data ? booking.check_in_data['check_in_id'] : "";
                                    // Id = booking.check_in_data ? booking.check_in_data['id'] : "";
                                    // roomType = booking.check_in_data ? booking.check_in_data['room_type_id'] : "";
                                    if(booking.check_in_data){
                                        booking?.check_in_data?.forEach(item => {
                                            if(item.room_code == booking.room_id ){
                                                bookingId = item.check_in_id;
                                                Id = item.id;
                                                roomType = item.room_type_id;
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
                                            roomType
                                        };
                                    } else if (cellDate === checkinDate) {
                                        return {
                                            style: `linear-gradient(to right, transparent ${startPercent}%, ${color} ${startPercent}%)`,
                                            className,
                                            bookingId,
                                            Id,
                                            roomType
                                        };
                                    } else if (cellDate === checkoutDate) {
                                        return {
                                            style: `linear-gradient(to right, ${color} 0%, ${color} ${endPercent}%, transparent ${endPercent}%)`,
                                            className,
                                            bookingId,
                                            Id,
                                            roomType
                                        };
                                    } else if (cellDate > checkinDate && cellDate < checkoutDate) {
                                        return {
                                            style: color,
                                            className,
                                            bookingId,
                                            Id,
                                            roomType
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
                            row += `<td class="${finalClass}"  data-room-type-id="${finalRoomType}"  data-id="${finalId}" data-booking="${finalBookingId}" style="background: ${finalStyle};"></td>`;

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
                        }
                        roomBooking(data, "");

                    }
                } else {
                    if (td.className === "room_book") {
                        roomBooked(Id, bookingId);
                        console.log('1234');
                        
                    } else {
                        console.log(bookingId);
                        
                        checkIn(Id, bookingId);
                        console.log('123');

                    }
                }

                //  alert(`📅 Phòng: ${roomName}, Ngày: ${date}, Giá: ${roomPrice}, Click: ${percentX}%\nKết quả: ${clickedRegion}`);


            } else {
                if (roomName && date && roomPrice) {
                    const startDate = new Date(document.getElementById("startDate").value);
                    let currentDate = new Date(startDate).toISOString().split('T')[0];
                    function formatTime(time) {
                        let [hour, minute] = time.split(":").map(num => num.padStart(2, '0'));
                        return `${hour}:${minute}:00`;
                    }
                    let data = {
                        room: roomId,
                        room_type: roomType,
                        date: currentDate,
                    }
                    roomBooking(data, formatTime(date));
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

        console.log('tổng giá: '+ totalPrice );
        
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
    function roomBooking(data, currentdate) {
        $.ajax({
            url: checkRoomBookingUrl,
            type: 'POST',
            data: {
                data: JSON.stringify(data),
                method: 'LETAN',
            },
            success: function (response) {
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
                        <tr  data-status="0" data-room-id="${roomId}"  data-room-type-id="${roomTypeId}" data-date="${item.date}">
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
                                    value="${currentdate && currentdate.trim() !== "" ? currentdate : item.room['room_type']['room_type_price']['setup_pricing']['check_in_time']}">
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
                    //title 
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
                        $('.name-edit, .phone-edit').val('');
                        $('#list-booking-edit-letan').empty();
                        $('#list-booking-edit').empty();
                        $('#list-booking').empty();
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


                                var tr = `
                                        <tr data-room-id="${room.room_id}" data-status="${room.status}"
                                        data-room-booking-id="${room.id}"  data-room-type-id="${room.room_type_id}"  class="${room.status === 1 ? "check_in_status" : ""}">
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

                        //$('.total_discount').text(formatCurrency(total_deposit_discount));

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
    function checkIn(Id, dataId) {
        const date_booking = new Date();
        const date_yyyy = date_booking.getFullYear();
        const date_mm = String(date_booking.getMonth() + 1).padStart(2, '0');
        const date_dd = String(date_booking.getDate()).padStart(2, '0');
        const date_hour = String(date_booking.getHours()).padStart(2, '0'); // Giờ
        const date_minutes = String(date_booking.getMinutes()).padStart(2, '0'); // Phút

        const formattedDates = `${date_yyyy}-${date_mm}-${date_dd}`;
        const formattedTimes = `${date_hour}:${date_minutes}`;
        var url = checkInEditUrl.replace(':id', dataId);

        // $('[id="date-book-room-booking-edit"]').val(formattedDates);
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


                            item.room_bookings.forEach((room, index) => {
                                if (index === 0) {
                                    $('.id_room_booking').val(room.booking_id);
                                }
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
    
    
                                        </tr>
                                    `;
                                tbody.append(tr);
                            });
                        });
                        totalPrice = calculateTotalPrice();



                        $('.total_deposit').text(formatCurrency(total_deposit_amount));
                        //$('.total_discount').text(formatCurrency(total_deposit_discount));

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


                        // $('.custom-input-giam-gia').on('blur', function () {
                        //     // Lấy giá trị từ trường nhập liệu
                        //     let discountValue = $(this).val();
                        //     let number = parseInt(discountValue.replace('.', ''));
                        //     number = isNaN(number) ? 0 : number;
                        //     let priceString = $('.total_amount').text();
                        //     let price = parseFloat(priceString.replace(/\./g, '')
                        //         .replace(' VND', ''));

                        //     let pricedeposit = $('.total_deposit').text();
                        //     let deposit = parseFloat(pricedeposit.replace(/\./g, '')
                        //         .replace(' VND',
                        //             ''));
                        //     $('.total_balance').text(formatCurrency(price -
                        //         deposit - number));
                        //     formatNumber(this);
                        // });
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

    setInterval(updateRealtime, 1000);
    initDates();
};
initGridMain("", "");
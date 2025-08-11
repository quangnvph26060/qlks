function getCurrentDate() {
    const currentDate = new Date();
    const year = currentDate.getFullYear();
    const month = String(currentDate.getMonth() + 1).padStart(2, '0'); // Thêm 1 vì tháng bắt đầu từ 0
    const day = String(currentDate.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}
function formatCurrency(amount) {
    if (!amount || isNaN(amount)) {
        return '0 VND'; // Nếu amount không hợp lệ, trả về 0 VND
    }

    const parts = parseFloat(amount).toFixed(2).toString().split('.');
    const integerPart = parts[0];
    const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    return formattedInteger + ' VND';
}
function formatDateTime(dateTimeStr) {
    const date = new Date(dateTimeStr);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0'); // tháng bắt đầu từ 0
    const year = date.getFullYear();

    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');

    return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
}
function formatDate(date) {
    return date.toISOString().split('T')[0];
}

$(document).ready(function () {
    const savedView = localStorage.getItem('selectedView') || 'list';
    let htmlrow = '';
    if (savedView === "calendar") {
        htmlrow = `
            <input type="date" id="startDate" class="form-control w-auto" style="height: 35px" placeholder="Từ ngày">
            <input type="date" id="endDate" class="form-control w-auto" style="height: 35px" placeholder="Đến ngày">
        `;
    } else {
        htmlrow = `<input type="date" id="startDate" class="form-control w-auto" style="height: 35px">`;
    }

    $('#date-input-booking').html(htmlrow);

    let today = new Date();
    let futureDate = new Date();
    futureDate.setDate(today.getDate() + 5);

    // Hàm định dạng ngày yyyy-mm-dd
    function formatDate(date) {
        let d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }

    $('#startDate').val(formatDate(today));
    $('#endDate').val(formatDate(futureDate)); // Chỉ set nếu có endDate
});
function initGridMain(data, date) {
    $('#loading-overlay').css('display', 'flex');
    $.ajax({
        type: "GET",
        url: roomBoookingHistory,
        data: {
            date: date,
            data: data,
            method: 'list-room-booking',
        },
        success: function (response) {
            if (response.status === 'success') {
                let roomHTML = '';
                let stt = 1;
                response.data.forEach(item => {

                    let dem = stt++;
                    item.room_booking_history.forEach(booked => {

                        if (booked['status_code'] == 3 && booked['check_in_data'].length > 0) {
                            let seen = new Set();

                            booked['check_in_data']
                                .filter(checkIn => {
                                    if (seen.has(checkIn.check_in_id)) {
                                        return false; // bỏ qua nếu đã gặp check_in_id này
                                    }
                                    seen.add(checkIn.check_in_id);
                                    return true;
                                })
                                .forEach(checkIn => {

                                    if (booked['room_id'] === checkIn['room_code']) {
                                        roomHTML += `
                                              <tr class="main-row" data-booking="${checkIn['check_in_id']}">
                                                <td>${dem}</td>
                                                  <td class="w-20" style="position: relative;">
                                                    <div class="d-flex align-items-center justify-content-center" style="gap:10px;">
                                                            <button class="menu-toggle-btn" data-id="${checkIn['id']}" data-booking="${checkIn['check_in_id']}">
                                                                <svg class="" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"></circle><circle cx="10.5" cy="5.5" r="1"></circle><circle cx="10.5" cy="15.5" r="1"></circle></g></svg>
                                                            </button>
                                                    </div>
                                                    <div class="room-action-menu menu-main-list" data-id="${checkIn['id']}" data-booking="${checkIn['check_in_id']}">
                                                        <div class="dropdown-item room_clean"  data-id="${item.id}"> ${item['is_clean'] == 1 ? 'Chưa dọn' : 'Làm sạch'}</div>
                                                        <div class="dropdown-item">Thêm sản phẩm, dịch vụ</div>
                                                        <div class="dropdown-item">Đổi phòng</div>
                                                        <div class="dropdown-item">Thanh toán</div>
                                                    </div>
                                                </td>
                                                <td>${checkIn['check_in_id']}</td>
                                                <td>
                                                    <span style="color:red">${item['room_number']}</span>
                                                <br>  <span class="status-clean">${item['is_clean'] == 1 ? "✨ Sạch" : "🚨 Chưa dọn"}</span></td>
                                                
                                                
                                                <td>${checkIn['customer_name']} <br> ${checkIn['phone_number'] ?? 'N/A'}</td>
                                                <td class="w-10">${formatDateTime(checkIn['checkin_date'])}</td>
                                                <td class="w-10">${formatDateTime(checkIn['checkout_date'])}</td>
                                                <td class="text-right">
                                                    ${formatCurrency(
                                            Number(checkIn['total_amount']) + Number(checkIn['check_in_service_products'])
                                        )}
                                                </td>

                                                 <td class="text-right">${formatCurrency(checkIn['deposit_amount'])}</td>
                                              

                                              </tr>
                                            `;


                                    }
                                });
                          

                        } else if (booked['status_code'] == 2 && booked['booking_data'].length > 0) {
                            booked['booking_data'].forEach(checkIn => {
                                if (booked['room_id'] === checkIn['room_code']) {
                                    roomHTML += `
                                            <tr class="main-row" data-booking="${checkIn['booking_id']}">
                                                <td>${dem}</td>
                                                 <td class="w-20" style="position: relative;">
                                                    <div class="d-flex align-items-center justify-content-center" style="gap:10px;">
                                                          
                                                            <button class="menu-toggle-btn" data-id="${checkIn['id']}"  data-booking="${checkIn['booking_id']}">
                                                            <svg class="" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"></circle><circle cx="10.5" cy="5.5" r="1"></circle><circle cx="10.5" cy="15.5" r="1"></circle></g></svg>
                                                            </button>
                                                    </div>
                                                    <div class="room-action-menu menu-main-list" data-id="${checkIn['id']}" data-booking="${checkIn['booking_id']}">
                                                        <div class="dropdown-item room_clean"  data-id="${item.id}" > ${item['is_clean'] == 1 ? 'Chưa dọn' : 'Làm sạch'}</div>
                                                        <div class="dropdown-item check_in_room" data-id="${item.id}" data-date="${checkIn['checkin_date']}" data-book="${checkIn['booking_id']}">Nhận phòng </div>
                                                        <div class="dropdown-item">Hủy phòng</div>
                                                    </div>
                                                </td>
                                                <td>${checkIn['booking_id']}</td>
                                                <td><span style="color:#ebb579">${item['room_number']}</span> <br>  <span class="status-clean">${item['is_clean'] == 1 ? "✨ Sạch" : "🚨 Chưa dọn"}</span></td>
                                                <td>${checkIn['customer_name']} <br> ${checkIn['phone_number'] ?? 'N/A'}</td>
                                                <td class="w-10">${formatDateTime(checkIn['checkin_date'])}</td>
                                                <td class="w-10">${formatDateTime(checkIn['checkout_date'])}</td>
                                                <td class="text-right">${formatCurrency(checkIn['total_amount'])}</td>
                                                <td class="text-right">${formatCurrency(checkIn['deposit_amount'])}</td>
                                               
                                              

                                            </tr>
                                            `;


                                }
                            })
                        }
                    });


                })
                // Gắn vào DOM
                $("#booking-table").html(roomHTML);

                // Toggle dòng con
                $(".toggle-rooms").on("click", function (e) {
                    e.preventDefault();
                    const bookingId = $(this).data("booking");
                    $(`.room-detail-row[data-parent="${bookingId}"]`).toggle();
                });
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

}
$(document).off("click", ".room_clean").on("click", ".room_clean", function (e) {
    e.stopPropagation();

    // Lấy thông tin từ div được bấm
    const roomName = $(this).data("name");
    const roomId = $(this).data("id");
    const statusText = $(this).text().trim();

    // Gán vào modal
    $("#roomStatusModal .modal-body strong").text(roomName);
    $("#roomStatusModal .modal-body .status-text").text(statusText);
    $('.change_clean_room_btn').attr('data-id', roomId);
    // Mở modal
    const modal = new bootstrap.Modal(document.getElementById("roomStatusModal"));
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
    $(document).off("click", ".check_in_room").on("click", ".check_in_room", function (e) {
        e.stopPropagation();
        let selectedData = [];
        $('.btn-book-pttt').removeData('data-method').attr('data-method', 'check_in');
        $('#select-option-pttt_error1').text('');
        $('#input_pttt_error').text('');
        $('#add-service-room-booking').hide();
        $('[id="select-option-pttt-main"]').hide();
        $('[id="select-option-pttt"]').hide();
        $('[id="print_invoice"]').hide();
        $('[id="print_sales_invoice"]').hide();
        $('[id="checkout_room"]').hide();
        $('[id="total_payment_display"]').hide();
        let dataId = $(this).attr("data-id");
        let book = $(this).attr("data-book");
        let date = $(this).attr("data-date");
        let room_type = $(this).attr("data-room-type");
        selectedData.push({
            id: dataId,         // Lấy data-id
            book: book,         // Lấy data-book
            date: date,         // Lấy ngày check-in
            room_code: dataId,   // Lấy loại phòng
            room_type: room_type,
            method: "LETAN"
        });

        findRoomBookingId(selectedData)
    });
    function findRoomBookingId(id) {
        $('.booking-form-pttt').attr('action', CheckInUrl);
        $.ajax({
            url: findRoomBookingIdUrl,
            type: 'POST',
            data: {
                booking_id: id,
            },
            success: function (data) {

                $('#myModal-check-in-edit').modal('show').on('shown.bs.modal', function () {
                    $('[id="date-book-room-booking-edit"]').val(formattedDates);
                    $('.pageModal').text('Nhận phòng');



                    let totalPrice, total_deposit_amount, total_deposit_discount = 0;
                    var tbody = $('#list-booking-edit-letan');
                    tbody.empty();
                    // nhân viên
                    var selected_select_staff = $('#select-staff-edit-letan');
                    selected_select_staff.empty();
                    let option_staff = `<option value="">Chọn nhân viên</option>`;
                    data.admin.forEach(function (item) {
                        if (item.name === data.option_admin) {
                            option_staff += `<option value="${item.id}" selected>${item.username}</option>`;
                        } else {
                            option_staff += `<option value="${item.id}">${item.username}</option>`;
                        }

                    });
                    selected_select_staff.append(option_staff);

                    // khách hàng
                    var selected_customer_source = $('#select-customer-source-edit-letan');
                    selected_customer_source.empty();
                    let option = `<option value="">Chọn nguồn khách hàng</option>`;
                    data.customerSourse.forEach(function (item) {
                        if (item.source_code == data.option_customer_source) {
                            option += `<option value="${item.source_code}" selected>${item.source_name}</option>`;
                        } else {
                            option += `<option value="${item.source_code}">${item.source_name}</option>`;
                        }

                    });
                    selected_customer_source.append(option);
                    data.data.forEach(function (item) {
                        $('input[name="name"]').val(item.customer_name);
                        $('input[name="phone"]').val(item.phone_number);
                        $('input[name="customer_code"]').val(item.customer_code);
                        $('input[name="id_room_booking"]').val(item.booking_id);
                        $("#select-customer-source").val(item.user_source).change();
                        item.rooms.forEach(function (room) {
                            let checkinDateTime = room.checkin_date;

                            let [dateIn, timeIn] = checkinDateTime.split(" ");
                            let checkoutDateTime = room.checkout_date;

                            let [dateOut, timeOut] = checkoutDateTime.split(" ");
                            total_deposit_amount += parseFloat(room.deposit_amount);
                            total_deposit_discount += parseFloat(room.discount);
                            // $('.total_amount').text('');
                            // $('.total_deposit').text('');
                            // $('.total_discount').text('');
                            const hasSameRoomCode = item.rooms.some(r => r !== room && r.room_code === room.room_code);

                            let datePart = '', timePart = '';

                            if (hasSameRoomCode) {
                                [datePart, timePart] = room.checkin_date.split(' ');
                            } else {
                                const dateOnly = formattedDates;
                                const now = new Date();
                                const currentHour = String(now.getHours()).padStart(2, '0');
                                const currentMinute = String(now.getMinutes()).padStart(2, '0');
                                const currentTime = `${currentHour}:${currentMinute}`;

                                datePart = dateOnly;
                                timePart = currentTime;
                            }

                            var tr = `
                                <tr data-room-booking-id="${room.booking_id}" data-price="${room.total_amount}" data-room-id="${room.room_code}"  data-room-type-id="${room.room_type}" data-date="${formattedDates}">
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
                                        <div class="d-flex align-items-center justify-content-start" style="gap: 3px">
                                           <input type="date" name="checkInDate" id="date-book-room" class="form-control date-book-room"  value="${datePart}" readonly>

                                    <input type="time" name="checkInTime" id="time-book-room" class="form-control time-book-room"  value="${timePart}" style=" display: flex;justify-content: flex-start;width: 110px;padding: 1px 9px !important;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-start" style="gap: 3px">
                                            <input type="date" name="checkOutDate"  class="form-control date-book-room" readonly value="${dateOut}">
                                            <input type="time" name="checkOutTime" id="time-book-room" class="form-control time-book-room" value="${timeOut}"  value=""style=" display: flex;justify-content: flex-start;width: 110px;padding: 1px 9px !important;">
                                        </div>
                                    </td>
                                    <td>
                                         <p id="price" class="d-flex justify-content-center" data-price="${room.total_amount}">${formatCurrency(room.total_amount)}</p>
                                    </td>
                                    <td>
                                      <input type="text" class="form-control deposit number-input money-input"
                                        value="${new Intl.NumberFormat('vi-VN').format(room.deposit_amount)}"
                                        name="deposit"  placeholder="0"
                                        oninput="formatMoneyInput(this)">
        
                                    </td>
                                    <td>
                                          <input type="text" class="form-control discount number-input-discount money-input"
                                          value="${new Intl.NumberFormat('vi-VN').format(room.discount)}"
                                            name="discount"  placeholder="0"     oninput="formatMoneyInput(this)">
                                    </td>
                                    <td>
                                        <input type="text" name="note_room" class="form-control note_room" value="" id="note" value="${room.note}">
                                    </td>
                                </tr>
                            `;
                            tbody.append(tr);
                        });

                    });

                    totalPrice = calculateTotalPrice();
                    $('.total_deposit').text(formatCurrency(total_deposit_amount));
                    $('.total_discount').text(formatCurrency(total_deposit_discount));

                    $('.total_amount').text(formatCurrency(totalPrice));
                    $('.total_balance').text(formatCurrency(totalPrice));
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


            },
            error: function (error) {
                $('#loading').hide();
                console.log('Error:', error);
            }
        });
    }
    function calculateTotalPrice() {
    let totalPrice = 0;
    let totalDeposit = 0;
    let totalDiscount = 0;
    let payment = 0;
    let service = 0;
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
    $('#list-booking-edit-letan').find('input.payment').each(function () {
        let priceString = $(this).val(); // Lấy giá trị nhập trong input
        let price = parseFloat(priceString.replace(/[,.]/g, '')); // Loại bỏ ký tự không phải số
        if (!isNaN(price)) {
            payment += price;
        }
    });
    $('#list-booking-edit-letan').find('input.total_service').each(function () {
        let priceString = $(this).val(); // Lấy giá trị nhập trong input
        let price = parseFloat(priceString.replace(/[,.]/g, '')); // Loại bỏ ký tự không phải số


        if (!isNaN(price)) {
            service += price;
        }
    });


    // let pricediscount = 0;
    // let discountInputValue = $('#discountInput').val();

    // if (discountInputValue) {
    //     pricediscount = parseInt(discountInputValue.replace(/\./g, ''));
    //     pricediscount = isNaN(pricediscount) ? 0 : pricediscount;
    // }
    $('.total_balance').each(function () {
        $(this).text(formatCurrency((totalPrice - totalDeposit - totalDiscount - payment))); // tiền còn lại
    });
    $('.total_service_display').text(formatCurrency(service));

    $('.total_amount').each(function () {
        $(this).text(formatCurrency(totalPrice));
    });
    // giảm giá

    $('.total_discount').each(function () {
        $(this).text(formatCurrency(totalDiscount));
    });
    // đặt cọc
    $('.total_deposit').each(function () {
        $(this).text(formatCurrency(totalDeposit));
    });

    $('.total_payment').each(function () {
        $(this).text(formatCurrency(payment));
    });

    //  $('#total_balance').text(formatCurrency(totalPrice));

    // $('#total_deposit').text(formatCurrency(totalPrice));
    return totalPrice;
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
// Toggle menu dropdown
$(document).on("click", ".menu-toggle-btn", function (e) {
    e.stopPropagation();
    const bookingId = $(this).data("booking");
    const roomId = $(this).data("room-id");

    // Ẩn tất cả menu cũ
    $(".room-action-menu").hide();

    // Hiện menu tương ứng với cả bookingId và roomId
    $(`.room-action-menu[data-booking="${bookingId}"][data-room-id="${roomId}"]`).toggle();
});
$(document).on("click", ".menu-toggle-btn", function (e) {
    e.stopPropagation();
    const bookingId = $(this).data("booking");

    $(".room-action-menu").hide();

    $(`.room-action-menu[data-booking="${bookingId}"]`).toggle();
});

// Ẩn menu khi click ra ngoài
$(document).on("click", function () {
    $(".room-action-menu").hide();
});

$(document).ready(function () {
    let selectedDate = $('#startDate').val();
    initGridMain('', selectedDate);
});
$(document).on('change', '#startDate', function () {
    let selectedDate = $(this).val();
    initGridMain('', selectedDate);
});
document.addEventListener("DOMContentLoaded", function () {
    console.log("Danh sách JS đã chạy!");

    const buttons = document.querySelectorAll(".view-toggle button");

    buttons.forEach(button => {
        button.addEventListener("click", function () {
            buttons.forEach(btn => {
                btn.classList.remove("active");
                btn.querySelector(".text").style.display = "none";
            });

            this.classList.add("active");
            this.querySelector(".text").style.display = "inline";
        });
    });
});


function toggleFloor(id) {
    var floor = document.getElementById(id);
    var icon = floor.previousElementSibling.querySelector("i");
    if (floor.style.display === "none") {
        floor.style.display = "grid";
        icon.classList.remove("fa-chevron-right");
        icon.classList.add("fa-chevron-down");
    } else {
        floor.style.display = "none";
        icon.classList.remove("fa-chevron-down");
        icon.classList.add("fa-chevron-right");
    }
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

function initGridMain(data) {


    let gridView = $('#grid-main');
    const savedView = localStorage.getItem('selectedView') || 'list';
    let htmlrow = '';
    document.getElementById('date-input-booking').innerHTML = '';
    if (savedView === "calendar") {
        htmlrow = `  <input type="date" id="startDate" class="form-control w-auto" style="height: 40px"
                    placeholder="Từ ngày">
                <input type="date" id="endDate" class="form-control w-auto" style="height: 40px"
                    placeholder="Đến ngày"></input>`
            ;
        document.getElementById('date-input-booking').innerHTML = htmlrow;
    }

    function getCurrentDate() {
        const currentDate = new Date();
        const year = currentDate.getFullYear();
        const month = String(currentDate.getMonth() + 1).padStart(2, '0'); // Thêm 1 vì tháng bắt đầu từ 0
        const day = String(currentDate.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    }
    $.ajax({
        type: "GET",
        url: roomBoookingHistory,
        data: {
            date: getCurrentDate(),
            data:data
        },
        success: function (response) {
            if (response.status === 'success') {
                // resolve(response.data);
                let groupedRooms = {};

                response.data.forEach(item => {
                    if (!groupedRooms[item.room_type_id]) {
                        groupedRooms[item.room_type_id] = {
                            name: item.room_type.name,
                            code: item.room_type_id,
                            rooms: []
                        };
                    }
                    groupedRooms[item.room_type_id].rooms.push(item);
                });

                let roomHTML = "";
                Object.values(groupedRooms).forEach(group => {
                    roomHTML += `
                        <div class="floor-header" onclick="toggleFloor('${group.code}')">
                            ${group.name} (${group.rooms.length}) <span><i class="fas fa-chevron-down"></i></span>
                        </div>
                        <div id="${group.code}" class="room-grid">
                    `;
                    //     <div class="time-info">
                    //     <span class="clock-icon">🕒</span> 97 giờ 11 phút / 1 giờ
                    // </div>
                    group.rooms.forEach(item => {
                        let isBooking = null;
                        let status_code = null;
                        let flag = 'room-booked';
                        if (item.room_booking_history.length > 0 && item.room_booking_history) {
                            item.room_booking_history.forEach(booking => {
                                status_code = booking.status_code;

                                if (Array.isArray(booking.booking_data) && booking.booking_data.length > 0) {
                                    let matchingBooking = booking.booking_data.find(booking_data => booking_data.room_code == item.id);
                                    if (matchingBooking) {
                                        isBooking = matchingBooking; // Lưu bản ghi nếu tìm thấy
                                        flag = 'room-booking';
                                    }

                                } else if (booking.check_in_data && booking.check_in_data.check_in_id) {
                                    if (booking.check_in_data.room_code == item.id) {
                                        isBooking = booking.check_in_data; // Lưu bản ghi nếu tìm thấy
                                        flag = 'check_in_data';
                                    }
                                } else {
                                    isBooking = '';
                                }
                            });

                        }
                        //  console.log(isBooking);

                        roomHTML += `
                        <div class="room-card ${item.status} ${flag}" 
                            data-booking-id="${isBooking?.booking_id ?? isBooking?.check_in_id ?? ''}"
                            data-id="${isBooking?.id ?? ''}"
                            data-room-id="${isBooking?.room_code ?? item.id}"
                            data-room-type-id="${item.room_type_id}"
>           
                            <div class="room-header">
                            <span class="status">${item.is_clean ? "✨ Sạch" : "🚨 Chưa dọn"}</span>
                                <span class="menu-btn">⋮</span>
                                  <div class="dropdown-menu">
                                    <div class="dropdown-item">${item.is_clean ? "Chưa dọn" : "Làm sạch"}</div>
                                    </div>
                            </div>
                            <div class="room-number
                            ${status_code == 2
                                ? 'text-yellow'
                                : status_code == 3
                                    ? 'text-red'
                                    : ''}
                             
                            ">${item.room_number}</div>
                            <div class="room-info">${isBooking?.customer_name ??
                            isBooking?.customer_name ?? " "}</div>
                            <div class="time-info">
                                    ${formatCurrency(item.room_type.room_type_price.unit_price)}
                            </div>      

                        </div>
                    `;
                    });

                    roomHTML += `</div>`;
                });

                // Đổ vào HTML
                $("#grid-main").html(roomHTML);

            } else {
                reject("Không lấy được dữ liệu");
            }
        },
        error: function (error) {
            reject(error);
        }
    });
 

    // Logic JS cho giao diện dạng Lưới
    document.querySelectorAll(".grid-item").forEach(item => {
        item.addEventListener("click", function () {
            alert("Bạn đã chọn một mục trong Grid!");
        });
    });



};
initGridMain("", "");
  // Sự kiện click cho menu-btn (ngăn click lan ra cha)
 


function initViewScript() {

    $('.delete-room-booking-edit').on('click', function () {
        let selectedBookingIds = [];
        Swal.fire({
            title: 'Bạn có chắc chắn xoá phòng này không',
            text: 'Bạn có chắc chắn xoá phòng này không',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy bỏ',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $('#list-booking-edit tr').each(function () {
                    var checkbox = $(this).find('input[type="checkbox"]');
                    if (checkbox.prop('checked')) {
                        var bookingId = $(this).data('room-booking-id');
                        selectedBookingIds.push(bookingId);
                        $(this).remove();
                    }

                });
                //  console.log(selectedBookingIds);
                $.ajax({
                    url: deleteRoomEdit,
                    type: 'POST',
                    data: {
                        data: JSON.stringify(selectedBookingIds)
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            //  loadRoomBookings();
                            let totalPrice = 0;
                            totalPrice = calculateTotalPrice();
                            $('.total_amount').text(formatCurrency(totalPrice));
                            // $('#total_balance').text(formatCurrency(totalPrice));
                            notify('success', response.success);
                            $('#myModal-booking-edit').modal('hide');
                            window.location.reload();
                        }
                    },
                    error: function (error) {
                        $('#loading').hide();
                        console.log('Error:', error);
                    }
                });
            }
        });

    });
}
$(document).ready(initViewScript);
function initViewScript1() {
    const date_booking = new Date();
    const date_yyyy = date_booking.getFullYear();
    const date_mm = String(date_booking.getMonth() + 1).padStart(2, '0');
    const date_dd = String(date_booking.getDate()).padStart(2, '0');
    const date_hour = String(date_booking.getHours()).padStart(2, '0'); // Giờ
    const date_minutes = String(date_booking.getMinutes()).padStart(2, '0'); // Phút

    const formattedDates = `${date_yyyy}-${date_mm}-${date_dd}`;
    const formattedTimes = `${date_hour}:${date_minutes}`;
    $(document).off("click", ".check_in_data").on("click", ".check_in_data", function (e) {
        if ($(e.target).closest('.menu-btn').length > 0) return;
        let dataId = $(this).attr("data-booking-id");
        let Id = $(this).attr("data-id");
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
                        $('#list-booking-edit-letan').empty();
                        $('#list-booking-edit').empty();
                        $('#list-booking').empty();
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
                        $('#date-book-room-booking-edit').val(formattedDates)
                        


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

    });
    $(document).off("click", ".room-booking").on("click", ".room-booking", function (e) {
        if ($(e.target).closest('.menu-btn').length > 0) return;
        let dataId = $(this).attr("data-booking-id");
        let Id = $(this).attr("data-id");
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
                    console.log(formattedDates);
                        $('#date-book-room-booking-edit').val(formattedDates)
                    $('#myModal-booking-edit').modal('show').on('shown.bs.modal', function () {
                        $('.name-edit, .phone-edit').val('');
                        $('#list-booking-edit').empty();
                        $('#list-booking').empty();
                        $('#list-booking-edit-letan').empty();
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
    });
    $(document).off("click", ".room-booked").on("click", ".room-booked", function (e) {
        if ($(e.target).closest('.menu-btn').length > 0) return;
        let roomId = $(this).attr("data-room-id");
        let roomType = $(this).attr("data-room-type-id");
        let data = {
            room: roomId,
            room_type: roomType,
            date: formattedDates,
        }
        $.ajax({
            url: checkRoomBookingUrl,
            type: 'POST',
            data: {
                data: JSON.stringify(data),
                method: 'LETAN',
            },
            success: function (response) {
                var tbody = $('#list-booking');
                $('#list-booking-edit').empty();
                $('#list-booking-edit-letan').empty();
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

                        tbody.append(tr);

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
    });
    $('.delete-room-booking-edit-letan').on('click', function () {
        let selectedBookingIds = [];
        Swal.fire({
            title: 'Bạn có chắc chắn xoá phòng này không',
            text: 'Bạn có chắc chắn xoá phòng này không',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy bỏ',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                let selectedRows = [];
                $('#list-booking-edit-letan tr').each(function () {
                    var checkbox = $(this).find('input[type="checkbox"]');
                    if (checkbox.prop('checked')) {
                        var bookingId = $(this).data('room-booking-id');
                        selectedBookingIds.push(bookingId); $(this).remove();
                        selectedRows.push($(this));
                    }
                });
                //  console.log(selectedBookingIds);
                $.ajax({
                    url: deleteCheckin,
                    type: 'POST',
                    data: {
                        data: JSON.stringify(selectedBookingIds)
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            // loadRoomBookings();
                            $('#myModal-check-in-edit').modal('hide');
                            window.location.reload();
                            let totalPrice = 0;
                            totalPrice = calculateTotalPrice();
                            $('.total_amount').text(formatCurrency(totalPrice));
                            // $('#total_balance').text(formatCurrency(totalPrice));
                            notify('success', response.success);
                            selectedRows.forEach(row => row.remove());
                            let remainingRows = $('#list-booking-edit tr').length;
                            if (remainingRows === 0) {
                                $('#myModal-check-in-edit').modal('hide');
                            }
                        } else {
                            Swal.fire({
                                title: response.message,
                                text: response.message,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Đồng ý',
                                cancelButtonText: 'Hủy bỏ',
                                reverseButtons: true
                            })
                        }
                    },
                    error: function (error) {
                        $('#loading').hide();
                        console.log('Error:', error);
                    }
                });
            }
        });

    });
// Toggle dropdown khi click vào .menu-btn
$(document).off("click", ".menu-btn").on("click", ".menu-btn", function (e) {
    e.stopPropagation(); // Ngăn không lan ra sự kiện click cha

    const $card = $(this).closest('.room-card');
    const $menu = $card.find('.dropdown-menu');

    const isVisible = $menu.is(':visible');

    // Ẩn tất cả menu khác
    $('.dropdown-menu').hide();

    // Hiển thị nếu chưa hiển thị
    if (!isVisible) {
        $menu.show();
    }
});

// Ẩn menu nếu click ra ngoài
$(document).on("click", function (e) {
    // Nếu click không nằm trong menu-btn hoặc dropdown-menu thì ẩn
    if (!$(e.target).closest('.menu-btn, .dropdown-menu').length) {
        $('.dropdown-menu').hide();
    }
});

}

$(document).ready(initViewScript1);

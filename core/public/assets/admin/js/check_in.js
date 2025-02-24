
"use strict";
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
const date_booking = new Date();
const date_yyyy = date_booking.getFullYear();
const date_mm = String(date_booking.getMonth() + 1).padStart(2, '0');
const date_dd = String(date_booking.getDate()).padStart(2, '0');
const date_hour = String(date_booking.getHours()).padStart(2, '0'); // Giờ
const date_minutes = String(date_booking.getMinutes()).padStart(2, '0'); // Phút

const formattedDates = `${date_yyyy}-${date_mm}-${date_dd}`;
const formattedTimes = `${date_hour}:${date_minutes}`;
$('[id="date-book-room-booking-edit"]').val(formattedDates);
$('[id="date-book-room-booking"]').val(formattedDates);
$('[id="time-book-room-booking"]').val(formattedTimes);
// Tạo một đối tượng Date mới và cộng thêm 1 ngày
const nextDay = new Date(date_booking);
nextDay.setDate(date_booking.getDate() + 1);

const nextDay_yyyy = nextDay.getFullYear();
const nextDay_mm = String(nextDay.getMonth() + 1).padStart(2, '0');
const nextDay_dd = String(nextDay.getDate()).padStart(2, '0');

const formattedNextDay = `${nextDay_yyyy}-${nextDay_mm}-${nextDay_dd}`;
$('[id="date-book-room-date"]').val(formattedNextDay);
$('[id="time-book-room-date"]').val(formattedTimes);
var validatorForm = {
    'name': { // passwword thì nên đặt là name trong input đó
        'element': document.getElementById('name'), // id trong input đó
        'error': document.getElementById('name_error'), // thẻ hiển thị lỗi
        'validations': [{
            'func': function (value) {
                return checkRequired(value); // check trống
            },
            'message': generateErrorMessage('P001', 'Tên')
        }, // viết tiếp điều kiện validate vào đây (validations)
        ]
    },

}
var formEconomyEdit = {
    'name': { // passwword thì nên đặt là name trong input đó
        'element': document.getElementById('name_edit'), // id trong input đó
        'error': document.getElementById('name_error_edit'), // thẻ hiển thị lỗi
        'validations': [{
            'func': function (value) {
                return checkRequired(value); // check trống
            },
            'message': generateErrorMessage('P001', 'Tên')
        }, // viết tiếp điều kiện validate vào đây (validations)
        ]
    },

}

function formatDate(inputDate) {
    // Chia chuỗi ngày thành các phần tử: năm, tháng, ngày
    var parts = inputDate.split('-');

    // Định dạng lại chuỗi ngày
    var formattedDate = parts[2] + '/' + parts[1] + '/' + parts[0];

    return formattedDate;
}

function formatDateTime(inputDateTime) {
    var dateTimeParts = inputDateTime.split(' ');
    var dateParts = dateTimeParts[0].split('-');
    var formattedDate = dateParts[2] + '/' + dateParts[1] + '/' + dateParts[0];
    var formattedDateTime = formattedDate + ' ' + dateTimeParts[1];
    return formattedDateTime;
}

function showRoom(data = "", checkInDateValue = "", checkOutDateValue = "", selectedOptionHangPhong = "",
    selectedOptionNamePhong = "", selectedOptionStatusPhong = "") {
    $('#loading').show();
    $('[id="date-chon-phong-in"]').val(checkInDateValue);
    $('[id="date-chon-phong-out"]').val(checkOutDateValue);
    $.ajax({
        url: showRoomUrl,
        type: 'POST',
        data: {
            roomIds: data,
            checkInDate: checkInDateValue,
            checkOutDate: checkOutDateValue,
            optionHangPhong: selectedOptionHangPhong,
            optionNamePhong: selectedOptionNamePhong,
            optionStatusPhong: selectedOptionStatusPhong,
        },
        success: function (data) {
            var tbody = $('#show-room');
            const dataNew = Object.values(data.data);
            let seenRooms = new Set();
            tbody.empty();
            
            
            dataNew.forEach(function (item) {
                let rowClass = '';
                let isFirst = !seenRooms.has(item.room_number);
                seenRooms.add(item.room_number);
                if (!isFirst) {

                    if (item.check_booked === 'Đã nhận') {
                        rowClass = "background-red";
                    } else if (item.check_booked === 'Đã đặt') {
                        rowClass = 'background-yellow';
                    } else if (item.check_booked === 'Trống') {
                        rowClass = "background-primary";
                    }
                } else {
                    if (item.check_booked === 'Đã nhận') {
                        rowClass = "background-red";
                    } else if (item.check_booked === 'Đã đặt') {
                        rowClass = 'background-yellow';
                    } else if (item.check_booked === 'Trống') {
                        rowClass = "background-primary";
                    }
                    else {
                        rowClass = "background-white";
                    }
                }
                let firstRowClass = isFirst ? "first-row" : "";
                var tr = `
                        <tr class="${firstRowClass}">
                            <td style="${isFirst ? 'font-weight: bold;' : ''}" class="text-left"> ${item.room_type['name']} </td>
                            <td style="${isFirst ? 'font-weight: bold;' : ''}"class="text-left"> ${item.room_number} </td>
                            <td style="${isFirst ? 'font-weight: bold;' : ''}"class="text-left"> ${formatDate(item.date)} </td>
                            <td style="${isFirst ? 'font-weight: bold;' : ''}"class="text-left ${rowClass}"> ${item.check_booked} </td>
                            <td style="${isFirst ? 'font-weight: bold;' : ''}"class="text-right"> ${formatCurrency(item.room_type.room_type_price['unit_price'])} </td>
                            <td>
                                <input type="checkbox" ${item.status == 1 ? 'disabled' : ''} data-date="${item.date}" data-id="${item.id}" data-room_type_id="${item.room_type_id}" id="checkbox-${item.id}">
                            </td>
                        </tr>
                    `;
                tbody.append(tr);

            });
            // hạng phòng 
            var selected_hang = $('#selected-hang-phong');
            selected_hang.empty();
            let option = `<option value="">Chọn hạng phòng</option>`;
            data.roomType.forEach(function (item) {
                if (item.id == data.option_hang_phong) {
                    option += `<option value="${item.id}" selected>${item.name}</option>`;
                } else {
                    option += `<option value="${item.id}">${item.name}</option>`;
                }
            });
            selected_hang.append(option);

            // tên phòng    
            var selected_name = $('#selected-name-phong');
            selected_name.empty();
            let options = `<option value="">Chọn tên phòng</option>`;


            data.room.forEach(function (item) {
                if (item.id == data.option_name_phong) {
                    options +=
                        `<option value="${item.id}" selected>${item.room_number}</option>`;
                } else {
                    options += `<option value="${item.id}">${item.room_number}</option>`;
                }
            });

            selected_name.append(options);
            // trạng thái phòng
            // var selected_status = $('#status-room');
            // selected_status.empty();
            // let status = ``;
            // if(data.option_status_phong === null){
            //      status += `<option value="">Chọn trạng thái phòng</option>`;
            // }else{
            //      status += `<option value="${data.option_status_phong}">${data.option_status_phong}</option>`;
            // }
            // selected_status.append(status);
            $('#loading').hide();
        },
        error: function (error) {
            $('#loading').hide();
            console.log('Error:', error);
        }
    });
}
$('#selected-name-phong, #selected-hang-phong, #date-chon-phong-out, #date-chon-phong-in, #status-room').on(
    'change',
    function () {
        var selectedOptionHangPhong = $('#selected-hang-phong').val();
        var selectedOptionNamePhong = $('#selected-name-phong').val();
        var selectedOptionStatusPhong = $('#status-room').val();
        const roomIds = [];
        $('#list-booking tr').each(function () {
            const roomId = $(this).attr('data-room-id');

            if (roomId) {
                roomIds.push(roomId);
            }
        });
        const checkInDateValue = $('#date-chon-phong-in').val();
        const checkOutDateValue = $('#date-chon-phong-out').val();
        showRoom(roomIds, checkInDateValue, checkOutDateValue, selectedOptionHangPhong, selectedOptionNamePhong,
            selectedOptionStatusPhong)
    });
// xóa phòng 
$('.delete-room-booking').on('click', function () {
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
            $('#list-booking tr').each(function () {
                var checkbox = $(this).find('input[type="checkbox"]');
                if (checkbox.prop('checked')) {
                    var bookingId = $(this).data('room-booking-id');
                    selectedBookingIds.push(bookingId);
                    $(this).remove();
                    let totalPrice = 0;
                    totalPrice = calculateTotalPrice();
                    $('.total_amount').text(formatCurrency(totalPrice));
                    // $('#total_balance').text(formatCurrency(totalPrice));
                }
            });
            $.ajax({
                url: deleteRoomEdit,
                type: 'POST',
                data: {
                    data: JSON.stringify(selectedBookingIds)
                },
                success: function (response) {
                    if (response.status === 'success') {
                        loadRoomBookings();
                        let totalPrice = 0;
                        totalPrice = calculateTotalPrice();
                        $('.total_amount').text(formatCurrency(totalPrice));
                        // $('#total_balance').text(formatCurrency(totalPrice));
                        notify('success', response.success);
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
$(document).on('click', '#btn-search', function () {
    var customerName = $('#name').val();
    let flag = true;
    if (customerName == '') {
        flag = false;
    }
    if (flag) {
        // ajax request
        $.ajax({
            url: searchCustomerUrl,
            type: 'GET',
            data: {
                name: customerName,
            },
            success: function (response) {
                // notify('success', response.success);
                // $('.note-booking').html(note);
                // $('#noteModal').modal('hide');
                if (response.status == 'success') {
                    if (response.data !== null) {
                        $('#phone').val(response.data['phone']);
                    } else {
                        $('#phone').val('');
                    }

                }
            },
            error: function (xhr, status, error) {

                // alert('Có lỗi xảy ra khi lưu ghi chú!');
            }
        });
    }

});


function formatNumber(input) {
    var value = input.value;
    var numericValue = value.replace(/[^0-9,]/g, '');
    var parts = numericValue.split(',');
    var integerPart = parts[0].replace(/\./g, '');
    var formattedValue = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    if (parts.length > 1) {
        formattedValue += ',' + parts[1];
    }
    input.value = formattedValue;
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
            if (response.status === 'success') {
                //   getRoomType(response.room_type['id'], response.room['room_number'])
                const todays = new Date();
                const yyyys = todays.getFullYear();
                const mms = String(todays.getMonth() + 1).padStart(2, '0');
                const dds = String(todays.getDate()).padStart(2, '0');
                const hoursss = String(todays.getHours()).padStart(2, '0'); // Giờ
                const minutesss = String(todays.getMinutes()).padStart(2, '0'); // Phút

                const formattedDates = `${yyyys}-${mms}-${dds}`;
                const formattedTimes = `${hoursss}:${minutesss}`;

                let totalPrice = 0;
                response.data.forEach(item => {
                    let date = new Date(item.date);
                    date.setDate(date.getDate() + 1);
                    var tr = `
                            <tr data-room-id="${item.room['id']}"  data-room-type-id="${item.room_type['id']}">
                                <td>
                                    <input type="checkbox"> 
                                </td>

                                <td>
                                    <p class="room__name"> ${item.room['room_number']}</p>
                                </td>
                                 <td>
                                     <input type="number" min="1" name="adult" class="form-control adult"  value="1"  style="margin-left: 16px;">
                                     
                                </td>
                                <td style="display: flex; justify-content: center">
                                    <select id="bookingType" class="form-select" name="optionRoom" style="width: 93px; font-size:15px">
                                         <option value="ngay">Ngày</option> 
                                         <option value="gio">Giờ</option>
                                      
                                        
                                    </select>
                                </td>
                                 <td>
                                    <div class="d-flex align-items-center justify-content-start" style="gap: 3px">
                                        <input type="date" name="checkInDate" id="date-book-room" class="form-control date-book-room"  value="${item.date}" readonly>

                                        <input type="time" name="checkInTime" id="time-book-room" class="form-control time-book-room"  value="${item.room['room_type']['room_type_price']['setup_pricing']['check_in_time']}" readonly>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-start" style="gap: 3px">
                                        <input type="date" name="checkOutDate"  class="form-control date-book-room" readonly value="${date.toISOString().split('T')[0]}">
                                        <input type="time" name="checkOutTime" id="time-book-room" class="form-control time-book-room" readonly value="${item.room['room_type']['room_type_price']['setup_pricing']['check_out_time']}">
                                    </div>
                                </td>
                                <td>
                                     <p id="price" data-price="${item.room['room_type']['room_type_price']['unit_price']}">${formatCurrency(item.room['room_type']['room_type_price']['unit_price'])}</p>
                                </td>
                                <td>
                                      <input type="text" class="form-control deposit number-input money-input"   name="deposit"  placeholder="0">
                                </td>
                                <td>
                                      <input type="text" class="form-control discount number-input-discount money-input"  name="discount"  placeholder="0">
                                </td>
                                <td>
                                    <input type="text" name="note_room" class="form-control note_room" value="" id="note">
                                </td>


                            </tr>
                        `;
                    tbody.append(tr);
                })
                // $('#list-booking').find('p#price').each(function() {
                //     let priceString = $(this).attr('data-price');
                //     let price = parseFloat(priceString.replace(' VND', '').replace(',', '.'));
                //     totalPrice += price;
                // });
                totalPrice = calculateTotalPrice();
                // $('.total_amount').text(formatCurrency(totalPrice));
                // $('.total_balance').text(formatCurrency(totalPrice));
                $('#loading').hide();
                let totalDeposit = 0;
                let totalBalance = 0;
                $('tr').find('input.deposit').on('blur', function () {
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
                    totalBalance = totalPrice - rowTotal - price;
                    $('.total_balance').text(formatCurrency(totalBalance));
                });
                $('tr').find('input.discount').on('blur', function () {
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
                    totalBalance = totalPrice - rowTotal - price;
                    $('.total_balance').text(formatCurrency(totalBalance));
                });
                $('#addRoomModal').modal('hide');
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
function validator(checkInDate, checkOutDate, dataRowValue) {
    const checkInDateTimeString = `${checkInDate}`;
    const checkInDateTimeStringOut = `${checkOutDate}`;
    // Chuyển đổi chuỗi ngày và giờ thành đối tượng Date
    const checkInDateTimeObj = new Date(checkInDateTimeString);
    const checkInDateTimeObjOut = new Date(checkInDateTimeStringOut);
    // Lấy thời gian hiện tại
    const currentDateTime = new Date();
    currentDateTime.setSeconds(0);

    const errorDiv = document.querySelector('.message-error');
    const checkInTimeInt = Math.floor(checkInDateTimeObj.getTime() / 1000);
    const checkInTimeIntOut = Math.floor(checkInDateTimeObjOut.getTime() / 1000);
    const currentTimeInt = Math.floor(currentDateTime.getTime() / 1000);
    // So sánh thời gian check-in với thời gian hiện tại
    if (checkInTimeInt > currentTimeInt && dataRowValue === 'checkin') {
        errorDiv.textContent =
            `Không thể nhận phòng trong tương lai. Bạn có thể Đặt trước hoặc cập nhật giờ nhận về giờ hiện tại để nhận phòng.`;
        errorDiv.classList.add('alert', 'alert-danger');
        errorDiv.style.display = 'block';
        return false;
    } else if (checkInTimeInt > currentTimeInt && dataRowValue === 'booked') {
        errorDiv.style.display = 'none'; // Ẩn div thông báo lỗi
        return true;
    } else {
        errorDiv.style.display = 'none'; // Ẩn div thông báo lỗi
        return true;
    }
}

function getDatesBetween(checkInDate, checkInTime, checkOutDate, checkOutTime, room, roomType, adult, note,
    deposit, discount, roomBookingId) {

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
            bookingId: roomBookingId
        });
        break;
    }
    return dates;
}
$(document).ready(function () {
    $(document).on('click', '.btn-submit-sync-book', function () {
        $('#booking_code').val('');
        $('#select_room_number').val('');
        $('#name_book').val('');
        loadRoomBookings();
    });
    $(document).on('click', '.btn-submit-search-book', function () {
        let bookingCode = $('#booking_code').val();
        let roomCode = $('#select_room_number').val();
        let customerName = $('#name_book').val();

        let data = {
            bookingCode: bookingCode,
            roomCode: roomCode,
            customerName: customerName,
        }
        loadRoomBookings(1, data);
    });
});
//123
function loadRoomBookings(page = 1, data) {
    $.ajax({
        url: allCheckInUrl,
        type: 'GET',
        data: {
            page: page,
            data: data,
        },
        success: function (response) {

            if (response.status === 'success') {
                var data = response.data.data;
                var pagination = response.pagination;

                $('.data-table').html('');
                var html = '';
                Object.entries(data).forEach(function ([bookingId, bookingData], index) {
                    let firstRecord = bookingData[0]; let collapseContent = '';

                     
                    let totalGuests = bookingData.reduce((sum, booking) => sum + (booking.guest_count || 0), 0);
                    const totalPrice = bookingData.reduce((sum, booking) => sum + parseFloat(booking.total_amount || 0), 0);
                    const totalDiscount = bookingData.reduce((sum, booking) => sum + parseFloat(booking.discount || 0), 0);
                    const totalAmount = bookingData.reduce((sum, booking) => sum + parseFloat(booking.deposit_amount || 0), 0);
                    bookingData.forEach(function (record, idx) {
                        
                        if (idx === 0) {
                            html += `
                                        <tr data-id="${record['id']}">
                                            <td>
                                                <button class="btn btn-link btn-toggle" type="button"
                                                    onclick="toggleRepresentatives('${record['check_in_id']}', this)"></button>
                                            </td>
                                            <td class="text-center">
                                                <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"/><circle cx="10.5" cy="5.5" r="1"/><circle cx="10.5" cy="15.5" r="1"/></g></svg>
                                                <div class="dropdown menu_dropdown_check_in" id="dropdown-menu">
                                                    <div class="dropdown-item booked_room_edit" data-room-id="${record['check_in_id']}">Sửa phòng</div>
                                                    <div class="dropdown-item booked_room" data-room-id="${record['id']}">Nhận phòng</div>
                                                    <div class="dropdown-item booked_room" data-room-id="${record['id']}">Đổi phòng</div>
                                                    <div class="dropdown-item delete-booked-room" data-room-id="${record['booking_id']}">Xóa phòng</div>
                                                </div>
                                            </td>
                                            <td class="text-right">${index + 1}</td>
                                            <td class="text-left">${record['check_in_id']}</td>
                                           
                                            <td class="text-right">${bookingData.length}</td>
                                            <td class="text-right">${formatDateTime(record['document_date'])}</td>
                                            <td class="text-left">${record['customer_name'] ? record['customer_name'] : 'N/A'}</td>
                                            <td class="text-right">${record['phone_number'] ? record['phone_number'] : 'N/A'}</td>
                                            <td class="text-right">${totalGuests}</td>
                                            <td class="text-right">${formatCurrency(totalPrice)}</td>
                                            <td class="text-right">${formatCurrency(totalAmount)}</td>   
                                            <td class="text-right">${formatCurrency(totalDiscount)}</td>
                                        </tr>
                                          <tr class="collapse" id="rep-${firstRecord['check_in_id']}">
                                    <td colspan="12">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th colspan="6">Phòng</th>
                                                    <th>Ngày check-in</th>
                                                    <th>Ngày check-out</th>
                                                    <th>Số người</th>
                                                    <th>Tổng tiền</th>
                                                    <th>Đặt cọc</th>
                                                    <th>Giảm giá</th>
                                                    <th>Ghi chú</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                    `;
                        }
                        html += `
                              
                                                <tr class="background-tr">
                                                    <td class="text-left" colspan="6">${record['room']['room_number']}</td>
                                                    <td class="text-right w-10">${formatDateTime(record['checkin_date'])}</td>
                                                    <td class="text-right w-10" >${formatDateTime(record['checkout_date'])}</td>
                                                  
                                                    <td class="text-right w-10" >${record['guest_count']}</td>
                                                    <td class="text-right w-10">${formatCurrency(record['total_amount'])}</td>
                                                    <td class="text-right w-10">${formatCurrency(record['deposit_amount'])}</td>
                                                    <td class="text-right w-10">${formatCurrency(record['discount'])}</td>
                                                    <td class="text-right">${record['note']}</td>
                                                </tr>
                                         
                            `;
                        if (idx === bookingData.length - 1) {
                            html += `
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                `;
                        }

                    });
                });
                // data.forEach(function (data, index) {
                //     var html = `
                //         <tr data-id="${data['id']}">
                //            <td>
                //                 <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"/><circle cx="10.5" cy="5.5" r="1"/><circle cx="10.5" cy="15.5" r="1"/></g></svg>
                //                 <div class="dropdown menu_dropdown_check_in" id="dropdown-menu">
                //                     <div class="dropdown-item check_in_edit" data-room-id="${data['booking_id']}">Sửa phòng</div>
                //                     <div class="dropdown-item booked_room_caned" data-room-id="${data['id']}">Trả phòng</div>
                //                     <div class="dropdown-item booked_room" data-room-id="${data['id']}">Đổi phòng</div>
                //                     <div class="dropdown-item delete-booked-room"  data-room-id="${data['id']}" >Xóa phòng</div>
                //                 </div>
                //             </td>
                //             <td>${index + 1}</td>
                //             <td>${data['check_in_id']}</td>
                //             <td>${data['id_room_booking'] ? data['id_room_booking'] : ''}</td>
                //            <td>${data['room']['room_number']}</td>

                //             <td>${formatDateTime(data['document_date'])}</td>
                //             <td>${formatDateTime(data['checkin_date'])}</td>
                //             <td>${formatDateTime(data['checkout_date'])}</td>
                         

                //             <td>${data['customer_name'] ? data['customer_name'] : 'N/A'}</td>
                //             <td>${data['phone_number'] ? data['phone_number'] : 'N/A'}</td>

                //             <td>${data['guest_count']}</td>
                //             <td>${formatCurrency(data['total_amount'])}</td>
                //             <td>${formatCurrency(data['deposit_amount'])}</td>
                //         </tr>
                //     `
                //     $('.data-table').append(html);
                // })

                $('.data-table').append(html);

                var selected_select = $('#select_room_number');
                selected_select.empty();
                let option = `<option value="">Chọn mã phòng</option>`;
                response.rooms.forEach(function (item) {
                    if (item.id == response.option_selected) {
                        option += `<option value="${item.id}" selected>${item.room_number}</option>`;
                    } else {
                        option += `<option value="${item.id}">${item.room_number}</option>`;
                    }
                });
                selected_select.append(option);
                updatePagination(pagination, 'loadRoomBookings');
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX request failed: " + error);
        }
    });
}
$(document).on('click', '.booked_room_edit', function () {
    $('#list-booking').empty();
    var roomId = $(this).data('room-id');
    // ajax request
    var url = checkInEditUrl.replace(':id', roomId);
       
    $.ajax({
        url: url,
        type: 'POST',
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
                selected_customer_source.append(option);
                // nhân viên 
                var selected_select_staff = $('#select-staff-edit');
                selected_select_staff.empty();
                let option_staff = `<option value="">Chọn nhân viên</option>`;
                response.admin.forEach(function (item) {
                    option_staff += `<option value="${item.id}">${item.username}</option>`;
                });
                selected_select_staff.append(option_staff);
                $('#myModal-check-in-edit').modal('show').on('shown.bs.modal', function () {
                    $('.name-edit, .phone-edit').val('');
                    $('#list-booking-edit').empty();
                    var tbody = $('#list-booking-edit');
                    let totalPrice, total_deposit_amount, total_deposit_discount = 0;
                    response.data.forEach(item => {
                        $('.name-edit').val(item.customer_name);
                        $('.phone-edit').val(item.phone_number);

                        item.room_bookings.forEach((room, index) => {
                            if(index === 0) {
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
                                                <input type="date" name="checkInDate" id="date-book-room" class="form-control date-book-room"  value="${checkinDate}" readonly>

                                                <input type="time" name="checkInTime" id="time-book-room" class="form-control time-book-room"  value="${checkinTime}" readonly>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start" style="gap: 10px">
                                                <input type="date" name="checkOutDate"  class="form-control date-book-room" value="${checkoutDate}" readonly>

                                                <input type="time" name="checkOutTime" id="time-book-room" class="form-control time-book-room"  value="${checkoutTime}" readonly>

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

});
$('.btn-book').on('click', function () {
    const dataRowValue = $(this).data('row');
    $('.booking-form').data('row', dataRowValue);
    if (validateAllFields(validatorForm)) {
        $('.booking-form').submit(); // Gửi form
    }

});
$('.btn-book-edit').on('click', function () {
    const dataRowValue = $(this).data('row');
    $('.booking-form-edit').data('row', dataRowValue);

    if (validateAllFields(formEconomyEdit)) {
        $('.booking-form-edit').submit(); // Gửi form
    }
    // if (name !== "" && name.trim()) {
    //     $('.name_error').text('');
    //     $('input[name="name"]').removeClass('is-invalid');

    // } else {
    //     $('input[name="name"]').addClass('is-invalid');
    //     $('.name_error').text('Tên không được bỏ trống');
    // }
});
$('.booking-form-edit').on('submit', function (e) {
    e.preventDefault();
    let formData = $(this).serializeArray();
    let formObject = {};
    formData.forEach(function (field) {
        formObject[field.name] = field.value;
    });

    let queryString = $.param(formObject);

    const params = new URLSearchParams(queryString);
    const checkInDate = params.get('checkInDate');
    const checkInTime = params.get('checkInTime');
    var roomData = []; // Mảng để chứa thông tin các phòng
    const dataRowValue = $(this).data('row'); // Lấy giá trị data-row đã thiết lập trước đó



    let hasError = true;
    // Duyệt qua từng dòng trong bảng 
    $('#list-booking-edit tr').each(function () {
        
        // var status = $(this).data('status');
        // if (status !== 0) {
        //     return;
        // }
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
        const errorDiv = document.querySelector('.message-error-edit');
        if (new Date(checkOutDate) <= new Date(checkInDate)) {
            errorDiv.textContent = `Ngày trả phòng phải lớn hơn ngày nhận phòng`;
            errorDiv.classList.add('alert', 'alert-danger');
            errorDiv.style.display = 'block';
            hasError = false;
            return false;
        }
        // Thêm thông tin của phòng vào mảng
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
        });
    });
    if (hasError) {
        roomData.forEach(function (item) {
            const roomDates = getDatesBetween(item['checkInDate'], item['checkInTime'],
                item['checkOutDate'], item['checkOutTime'], item['roomId'], item['roomTypeId'],
                item['adult'], item['note'], item['deposit'], item['discount'], item['roomBookingId']);

            roomDates.forEach(function (date, index) {
                formData.push({
                    name: 'room[]',
                    value: JSON.stringify(date)
                });
            });
        })
        formData.push({
            name: 'method',
            value: 'booked_room',
        });

        // formData.push({
        //     name: 'is_method',
        //     value: 'receptionist',
        // });
        let shouldSubmit = true;
        formData.some(function (item) {
            if (item.name === 'room[]') {
                const data = item.value;
                let dataArray = JSON.parse(data);
                const timeCheckIn = dataArray['dateIn'];
                const timeCheckOut = dataArray['dateOut'];
                // const resultData = validator(timeCheckIn, timeCheckOut, dataRowValue);
                // if (!resultData) {
                //     shouldSubmit = false;
                //     return true;
                // }
            }

        });

        // Kiểm tra th��i gian check-in với th��i gian hiện tại
        let url = $(this).attr('action');
        if (shouldSubmit) {
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                success: function (response) {
                    if (response.success) {
                        notify('success', response.success);
                        $('#myModal-check-in-edit').modal('hide');
                        loadRoomBookings();
                    } else {
                        notify('error', response.error);
                    }
                },
            });
        }
    }

});
$(document).on('click', '.check-in-room', function () {
    $('#bookingForm').attr('action', `${roomBook}`);
    var roomId = $(this).data('id');
    var roomTypeId = $(this).data('room_type_id');
    $('#list-booking').empty();     
    $('#name, #phone, #name_book').val("");
    $('#myModal-booking').modal('show');
    allStaffandCustomerSource()
    // $('#myModal-booking-edit').modal('hide');
    hasBookings() ? "" : ($('.total_balance').text(0), $('.total_amount').text(0), $('.total_deposit').text(0));
});
$('.add-customer-booked').on('click', function () {
    let selectedCustomer = $('#show-customer input[type="radio"]:checked');
    if (selectedCustomer.length === 0) {
        notify('error', 'Vui lòng chọn một khách hàng');
        return;
    }
    let Id = selectedCustomer.data('id');
    findCustomerById(Id)
});
function findRoomBookingId(id) {
    $.ajax({
        url: findRoomBookingIdUrl,
        type: 'POST',
        data: {
            booking_id: id,
        },
        success: function (data) {
            if(data.status === 'success')
            {
                $('#bookingForm').attr('action', `${CheckInUrl}`);
                let totalPrice, total_deposit_amount, total_deposit_discount = 0;
                var tbody = $('#list-booking');
                tbody.empty();
                data.data.forEach(function (item) {
                    $('input[name="name"]').val(item.customer_name);
                    $('input[name="phone"]').val(item.phone_number);
                    $('input[name="customer_code"]').val(item.customer_code);
                    $('input[name="id_room_booking"]').val(item.booking_id);
                    $("#select-customer-source").val(item.user_source).change();
                    item.rooms.forEach(function(room){
                        let checkinDateTime = room.checkin_date;

                        let [dateIn, timeIn] = checkinDateTime.split(" ");
                        let checkoutDateTime = room.checkout_date;

                        let [dateOut, timeOut] = checkoutDateTime.split(" ");
                        total_deposit_amount += parseFloat(room.deposit_amount);
                        total_deposit_discount += parseFloat(room.discount);
                        
                        var tr = `
                        <tr data-room-booking-id="${room.booking_id}" data-room-id="${room.room_code}"  data-room-type-id="${room.room_type}">
                            <td>
                                <input type="checkbox"> 
                            </td>

                            <td>
                                <p class="room__name"> ${room.room_number}</p>
                            </td>
                             <td>
                                 <input type="number" min="1" name="adult" class="form-control adult"  value="${room.guest_count}"  style="margin-left: 16px;">
                                 
                            </td>
                            <td style="display: flex; justify-content: center">
                                <select id="bookingType" class="form-select" name="optionRoom" style="width: 93px; font-size:15px">
                                     <option value="ngay">Ngày</option> 
                                     <option value="gio">Giờ</option>
                                  
                                    
                                </select>
                            </td>
                             <td>
                                <div class="d-flex align-items-center justify-content-start" style="gap: 3px">
                                    <input type="date" name="checkInDate" id="date-book-room" class="form-control date-book-room"  value="${dateIn}" readonly>

                                    <input type="time" name="checkInTime" id="time-book-room" class="form-control time-book-room"  value="${timeIn}" readonly>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-start" style="gap: 3px">
                                    <input type="date" name="checkOutDate"  class="form-control date-book-room" readonly value="${dateOut}">
                                    <input type="time" name="checkOutTime" id="time-book-room" class="form-control time-book-room" value="${timeOut}" readonly value="">
                                </div>
                            </td>
                            <td>
                                 <p id="price" data-price="${room.total_amount}">${formatCurrency(room.total_amount)}</p>
                            </td>
                            <td>
                              <input type="text" class="form-control deposit number-input money-input" 
                                value="${new Intl.NumberFormat('vi-VN').format(room.deposit_amount)}"  
                                name="deposit"  placeholder="0" 
                                oninput="formatMoneyInput(this)">

                            </td>
                            <td>
                                  <input type="text" class="form-control discount number-input-discount money-input"
                                   v   value="${new Intl.NumberFormat('vi-VN').format(room.discount)}"  
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
            }            
              
            // $('input[name="name"]').val(data.data.name);
            // $('input[name="phone"]').val(data.data.phone);
            // $('input[name="customer_code"]').val(data.data.customer_code);
            // $("#select-customer-source").val(data.data.group_code).change();
            // $("#select-customer-source-edit").val(data.data.group_code).change();
            $('#addBookedRoom').modal('hide');
            $('#loading').hide();
        },
        error: function (error) {
            $('#loading').hide();
            console.log('Error:', error);
        }
    });
}
$('.add-booked-room-form').on('click', function () {
    let selectedCustomer = $('#show-booked-room input[type="radio"]:checked');
    if (selectedCustomer.length === 0) {
        notify('error', 'Vui lòng chọn đặt phòng');
        return;
    }
    let Id = selectedCustomer.data('id');
    findRoomBookingId(Id)
});
$(document).on("dblclick", "#data-table #show-booked-room tr", function () {
    let customerId = $(this).find('input[type="radio"]').data("id");
    if (!customerId) {
        return;
    }
    findRoomBookingId(customerId);
});

// function showRoomBooking (data){

// }
$(document).on("dblclick", "#data-table #show-customer tr", function () {
    let customerId = $(this).find('input[type="radio"]').data("id");
    if (!customerId) {
        return;
    }
    findCustomerById(customerId);
});
function allStaffandCustomerSource() {
    $.ajax({
        url: getCustomerStaff,
        type: 'GET',
        success: function (data) {

            var selected_customer_source = $('#select-customer-source');
            selected_customer_source.empty();
            let option = `<option value="">Chọn nguồn khách hàng</option>`;
            data.customerSourse.forEach(function (item) {
                if (item.id == data.option_customer_source) {
                    option += `<option value="${item.source_code}" selected>${item.source_name}</option>`;
                } else {
                    option += `<option value="${item.source_code}">${item.source_name}</option>`;
                }
            });
            selected_customer_source.append(option);
            // nhân viên 
            var selected_select_staff = $('#select-staff');
            selected_select_staff.empty();
            let option_staff = `<option value="">Chọn nhân viên</option>`;
            data.admin.forEach(function (item) {
                if (item.id == data.option_customer_source) {
                    option_staff += `<option value="${item.id}" selected>${item.username}</option>`;
                } else {
                    option_staff += `<option value="${item.id}">${item.username}</option>`;
                }
            });
            selected_select_staff.append(option_staff);
            $('#loading').hide();
        },
        error: function (error) {
            $('#loading').hide();
            console.log('Error:', error);
        }
    });
}
$('.modal--search-customer').on('click', function () {
    showCustomer("");
    $('#addCustomerModal').modal('show');
    $('#addCustomerModal').on('shown.bs.modal', function () {
        document.body.classList.add('modal-open');
        $('#addCustomerModal').addClass('z__index-mod');
    });
});
$('.modal--search-booked').on('click', function () {
    showBookedRoom("");
  
    $('#addBookedRoom').modal('show');
    $('#addBookedRoom').on('shown.bs.modal', function () {
        document.body.classList.add('modal-open');
        $('#addBookedRoom').addClass('z__index-mod');
    });
});
// $('#addBookedRoom').on('hidden.bs.modal', function () {
//     $('#bookingForm').attr('action',`${roomBook}`); // Cập nhật action của form
// });

$(document).ready(function () {
    let typingTimer;
    const searchInput = $('#search_input_booked');
    searchInput.on('input', function () {
        clearTimeout(typingTimer);
        let searchValue = $(this).val().trim(); // Xóa khoảng trắng 2 đầu
        typingTimer = setTimeout(function () {
            showBookedRoom(searchValue, '');
        }, 300); // Chỉ tìm kiếm sau 300ms nếu không nhập liệu mới
    });
});

function showBookedRoom(value = "", option_customer_source = "") {
    $('#loading').show();
    $.ajax({
        url: getRoomBookingUrl,
        type: 'GET',
        data: {
            name: value
        },
        success: function (data) {
            // <p data-id="${ item.id }" data-room_type_id="${ item.room_type_id }" class="add-book-room" id="add-book-room">Đặt phòng</p>
            var tbody = $('#show-booked-room');
            tbody.empty();
            data.data.forEach(function (item) {
                var tr = `
                <tr class="booked-room-row">
                    <td class="text-left "> ${item.booking_id} </td>
                     <td class="text-right "> ${item.total_records} </td>
                     <td class="text-right "> ${item.document_date} </td>
                    <td class="text-left "> ${item.customer_name} </td>
                    <td class="text-right"> ${item.phone_number} </td>
                      <td class="text-right"> ${item.total_guest} </td>
                        <td class="text-right"> ${formatCurrency(item.deposit_amount)} </td>
                          <td class="text-right"> ${formatCurrency(item.total_discount)} </td>
                    <td class="text-center">
                        <input type="radio" name="booked_room_select" data-id="${item.booking_id}">
                    </td>
                </tr>
            `;
                tbody.append(tr);
            });
            // hạng phòng
            // var selected_customer_source = $('#selected-customer-source');
            // selected_customer_source.empty();
            // let option = `<option value="">Chọn nguồn khách hàng</option>`;
            // data.customerSourse.forEach(function (item) {
            //     if (item.id == data.option_customer_source) {
            //         option += `<option value="${item.source_code}" selected>${item.source_name}</option>`;
            //     } else {
            //         option += `<option value="${item.source_code}">${item.source_name}</option>`;
            //     }
            // });
            // selected_customer_source.append(option);
            $('#loading').hide();
        },
        error: function (error) {
            $('#loading').hide();
            console.log('Error:', error);
        }
    });
}
$('.add-room-booking').on('click', function () {

    const roomIds = [];
    $('.add-room-list').attr('data-list', 'list-booking');
    $('#list-booking tr').each(function () {
        const roomId = $(this).attr('data-room-id');
        if (roomId) {
            roomIds.push(roomId);
        }
    });
    const checkInDateValue = $('#date-book-room-booking').val();

    // Lấy giá trị của input checkOutDate
    const checkOutDateValue = $('#date-book-room-date').val();

    showRoom(roomIds, checkInDateValue, checkOutDateValue, '', '')
    $('#addRoomModal').modal('show');

    $('#addRoomModal').on('shown.bs.modal', function () {
        document.body.classList.add('modal-open');
        $('#addRoomModal').addClass('z__index-mod');
    });

});
$('#date-book-room-booking').on('change', function () {
    let checkInDateValue = $(this).val();
    if (checkInDateValue) {
        let checkInDate = new Date(checkInDateValue);
        checkInDate.setDate(checkInDate.getDate() + 1); // Cộng thêm 1 ngày

        let checkOutDateValue = checkInDate.toISOString().split('T')[0]; // Định dạng YYYY-MM-DD
        $('#date-book-room-date').val(checkOutDateValue);
    }
});
$(document).on('click', '.close_modal', function () {
    $('#myModal-booking').modal('hide');
});
$(document).on('click', '.close_modal_booked_room', function () {
    $('#addRoomModal').modal('hide');
    $('#addCustomerModal').modal('hide');
    $('#addBookedRoom').modal('hide');
});
$(document).on('click', '.note-booked-room', function () {
    var roomId = $(this).data('room-id');
    var note = $(this).closest('tr').find('input[name="note"]').val();
    $('#noteModal').modal('show');
    $('#note-input').val(note);
    $('#note-input').data('room-id', roomId);
});
$(document).on('click', '.booked_room_detail', function () {
    var roomId = $(this).data('room-id');
    var url = checkInDetailUrl.replace(':id',
        roomId);
    window.location.href = url;
});
$(document).on('click', '.save-note', function () {
    var newNote = $('#note-input').val();
    var roomId = $('#note-input').data('room-id');

    $('#list-booking tr[data-room-id="' + roomId + '"]')
        .find('input[name="note"]')
        .val(newNote);

    $('#list-booking tr[data-room-id="' + roomId + '"]')
        .find('input[name="note_room"]')
        .val(newNote);

    $('#noteModal').modal('hide');
});
$('.booking-form').on('submit', function (e) {
    e.preventDefault();
    let formData = $(this).serializeArray();
    // var adultsValue = parseInt($('#adults').val(), 10) || 0;
    // var childrenValue = parseInt($('#children').val(), 10) || 0;

    // var totalPeople = adultsValue + childrenValue;

    let formObject = {};
    formData.forEach(function (field) {
        formObject[field.name] = field.value;
    });

    let queryString = $.param(formObject);

    const params = new URLSearchParams(queryString);
    const checkInDate = params.get('checkInDate');
    const checkInTime = params.get('checkInTime');
    var roomData = []; // Mảng để chứa thông tin các phòng
    const dataRowValue = $(this).data('row'); // Lấy giá trị data-row đã thiết lập trước đó



    let hasError = true;
    // Duyệt qua từng dòng trong bảng
    $('#list-booking tr').each(function () {
        var roomId = $(this).data('room-id');
        var roomBookingId = $(this).data('room-booking-id');
        var roomTypeId = $(this).data('room-type-id');
        var checkInDate = $(this).find('input[name="checkInDate"]').val();
        var checkInTime = $(this).find('input[name="checkInTime"]').val();
        var checkOutDate = $(this).find('input[name="checkOutDate"]').val();
        var checkOutTime = $(this).find('input[name="checkOutTime"]').val();
        var adult = $(this).find('input[name="adult"]').val();
        var note = $(this).closest('tr').find('input[name="note_room"]').val();
        var deposit = $(this).closest('tr').find('input[name="deposit"]').val();
        var discount = $(this).closest('tr').find('input[name="discount"]').val();
        // console.log(roomId, roomTypeId, checkInDate, checkInTime, checkOutDate, checkOutTime, adult, note);
        const errorDiv = document.querySelector('.message-error');
        if (new Date(checkOutDate) < new Date(checkInDate)) {
            errorDiv.textContent = `Ngày trả phòng phải lớn hơn ngày nhận phòng`;
            errorDiv.classList.add('alert', 'alert-danger');
            errorDiv.style.display = 'block';
            hasError = false;
            return false;
        }
        // Thêm thông tin của phòng vào mảng
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
        });
    });

    if (hasError) {
        roomData.forEach(function (item) {
            const roomDates = getDatesBetween(item['checkInDate'], item['checkInTime'],
                item['checkOutDate'], item['checkOutTime'], item['roomId'], item['roomTypeId'],
                item['adult'], item['note'], item['deposit'], item['discount'],item['roomBookingId']);

            roomDates.forEach(function (date, index) {
                formData.push({
                    name: 'room[]',
                    value: JSON.stringify(date)
                });
            });
        })
        formData.push({
            name: 'method',
            value: 'check_in',
        });

        // formData.push({
        //     name: 'is_method',
        //     value: 'receptionist',
        // });
        let shouldSubmit = true;
        formData.some(function (item) {
            if (item.name === 'room[]') {
                const data = item.value;
                let dataArray = JSON.parse(data);
                const timeCheckIn = dataArray['dateIn'];
                const timeCheckOut = dataArray['dateOut'];
                const resultData = validator(timeCheckIn, timeCheckOut, dataRowValue);
                if (!resultData) {
                    shouldSubmit = false;
                    return true;
                }
            }

        });
        // Kiểm tra th��i gian check-in với th��i gian hiện tại

        let url = $(this).attr('action');
        if (shouldSubmit) {
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                success: function (response) {
                    if (response.success) {
                        notify('success', response.success);
                        $('#list-booking').empty();     
                        $('#name, #phone, #name_book').val("");
                        $('#myModal-booking').modal('hide');
                        loadRoomBookings();
                    } else {
                        notify('error', response.error);
                    }
                },
            });
        }
    }

});
$(document).on('click', '.icon-delete-room', function () {
    const row = $(this).closest('tr');
    const roomId = row.data('room-id');
    const roomTypeId = row.data('room-type-id');
    $('#confirmDeleteModal').modal('show');
    $('#confirmDeleteButton').off('click').on('click', function () {
        // Xử lý xóa
        row.remove();
        $('#confirmDeleteModal').modal('hide');
    });
});
$(document).ready(function () {
    loadRoomBookings(); // Function to load the room bookings
});

$(document).on('click', '.add-book-room', function () {
    
    var roomId = $(this).data('id');
    var roomTypeId = $(this).data('room_type_id');
    $('#myModal-booking').modal('show');
    addRoomInBooking(roomId, roomTypeId);
});

$(document).ready(function () {
    $(document).on('click', '.svg-icon', function (e) {
        e.stopPropagation();
        const $dropdown = $(this).siblings('.menu_dropdown');
        $('.menu_dropdown').not($dropdown).removeClass('show');
        $dropdown.toggleClass('show');
    });

    $(document).on('click', '.svg_menu_check_in', function (e) {
        e.stopPropagation();
        const $dropdown = $(this).siblings('.menu_dropdown_check_in');
        $('.menu_dropdown_check_in').not($dropdown).removeClass('show');
        $dropdown.toggleClass('show');
    });
    $(document).on('click', function () {
        $('.menu_dropdown').removeClass('show');
    });
    $(document).on('click', function () {
        $('.menu_dropdown_check_in').removeClass('show');
    });
});
$(document).on('click', '[id="hour_current"]', function () {
    // Lấy ngày và giờ hiện tại
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0'); // Tháng bắt đầu từ 0
    const date = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');

    // Kết hợp thành giá trị datetime-local
    const currentDate = `${year}-${month}-${date}`; // Format YYYY-MM-DD
    const currentTime = `${hours}:${minutes}`; // Format HH:mm

    // Gán giá trị vào các input
    $('[id="date-book-room"]').val(currentDate);
    $('[id="time-book-room"]').val(currentTime);
});


function updatePagination(pagination) {
    var paginationHtml = '';
    if (pagination.current_page > 1) {
        paginationHtml += `<button onclick="loadRoomBookings(${pagination.current_page - 1})">Trước</button>`;
    }
    for (var i = 1; i <= pagination.last_page; i++) {
        paginationHtml += `
    <button onclick="loadRoomBookings(${i})">${i}</button>
    `;
    }
    if (pagination.current_page < pagination.last_page) {
        paginationHtml += `<button onclick="loadRoomBookings(${pagination.current_page + 1})">Tiếp theo</button>`;
    }
    $('.pagination-container').html(paginationHtml);
}

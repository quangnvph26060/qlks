
"use strict";

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

const start = moment();
const end = moment().add(1, 'days');

const date_booking = new Date();
const date_yyyy = date_booking.getFullYear();
const date_mm = String(date_booking.getMonth() + 1).padStart(2, '0');
const date_dd = String(date_booking.getDate()).padStart(2, '0');
const date_hour = String(date_booking.getHours()).padStart(2, '0'); // Giờ
const date_minutes = String(date_booking.getMinutes()).padStart(2, '0'); // Phút

const formattedDates = `${date_yyyy}-${date_mm}-${date_dd}`;
const formattedTimes = `${date_hour}:${date_minutes}`;

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

function formatCurrency(amount) {
    const parts = amount.toString().split('.');
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
const dateRangeOptions = {
    minDate: start,
    startDate: start,
    endDate: end,
    ranges: {
        'Today': [moment(), moment().add(1, 'days')],
        'Tomorrow': [moment().add(1, 'days'), moment().add(2, 'days')],
        'Next 3 Days': [moment(), moment().add(2, 'days')],
        'Next 7 Days': [moment(), moment().add(6, 'days')],
        'Next 15 Days': [moment(), moment().add(14, 'days')],
        'Next 30 Days': [moment(), moment().add(30, 'days')]
    }
}
var formEconomyEdit = {
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

const changeDatePickerText = (element, startDate, endDate) => {
    $(element).val(startDate.format('L') + ' - ' + endDate.format('L'));
}

$('.bookingDatePicker').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('.bookingDatePicker',
    start, end));
$('.bookingDatePicker').on('apply.daterangepicker', (event, picker) => changeDatePickerText(event.target, picker
    .startDate, picker.endDate));



$('[name=guest_type]').on('change', function () {
    if ($(this).val() == 1) {
        $('.guestInputDiv').addClass('d-none');
        $('.forGuest').attr("required", false);
    } else {
        $('.guestInputDiv').removeClass('d-none');
        $('.forGuest').attr("required", true);
    }
});


// $('.formRoomSearch').on('submit', function(e) {
//     e.preventDefault();

//     let searchDate = $('[name=date]').val();
//     if (searchDate.split(" - ").length < 2) {
//         notify('error', `@lang('Ngày nhận phòng và ngày trả phòng phải được cung cấp khi đặt phòng.')`);
//         return false;
//     }

//      resetDOM();
//     let formData = $(this).serialize();
//     let url = $(this).attr('action');

//     $.ajax({
//         type: "get",
//         url: url,
//         data: formData,
//         success: function(response) {
//             $('.bookingInfo').html('');
//             $('.booking-wrapper').addClass('d-none');
//             if (response.error) {
//                 notify('error', response.error);
//             } else if (response.html.error) {
//                 notify('error', response.html.error);
//             } else {
//                 $('.bookingInfo').html(response.html);
//                 let roomTypeId = $('[name=room_type]').val();
//                 $('[name=room_type_id]').val(roomTypeId);


//                 $('.booking-wrapper').removeClass('d-none');
//             }
//         },
//         processData: false,
//         contentType: false,
//     });
// });

// function resetDOM() {
//     $(document).find('.orderListItem').remove();
//     $('.totalFare').data('amount', 0);
//     $('.totalFare').text(`0 {{ __(gs()->cur_text) }}`);
//     $('.taxCharge').text('0');
//     $('[name=tax_charge]').val('0');
//     $('.grandTotalFare').text(`0 {{ __(gs()->cur_text) }}`);
//     $('[name=total_amount]').val('0');
//     $('[name=paid_amount]').val('');
//     $('[name=room_type_id]').val('');
// }

$(document).on('click', '.confirmBookingBtn', function () {
    var modal = $('#confirmBookingModal');
    modal.modal('show');
});

// $('.btn-confirm').on('click', function() {
//     $('#confirmBookingModal').modal('hide');
//     $('.booking-form').submit();
// });

// $('.booking-form').on('submit', function(e) {
//     e.preventDefault();
//     let formData = $(this).serialize();
//     let url = $(this).attr('action');
//     $.ajax({
//         type: "POST",
//         url: url,
//         data: formData,
//         success: function(response) {
//             if (response.success) {
//                 notify('success', response.success);
//                 $('.bookingInfo').html('');
//                 $('.booking-wrapper').addClass('d-none');
//                 $(document).find('.orderListItem').remove();
//                 $('.orderList').addClass('d-none');
//                 $('.formRoomSearch').trigger('reset');
//             } else {
//                 notify('error', response.error);
//             }
//         },
//     });
// });
// $('.select2-basic').select2({
//     dropdownParent: $('.select2-parent')
// });
$(document).ready(function () {
    $('#searchInput').blur(function () {
        var search_value = $(this).val();
        var option_customer_source = $('#selected-customer-source').val();
        showCustomer(search_value, option_customer_source);
    });
    
});

function showCustomer(value = "", option_customer_source = "") {
    $('#loading').show();
    $.ajax({
        url: searchCustomerUrl,
        type: 'GET',
        data: {
            name: value,
            option_customer_source: option_customer_source
        },
        success: function (data) {
            // <p data-id="${ item.id }" data-room_type_id="${ item.room_type_id }" class="add-book-room" id="add-book-room">Đặt phòng</p>
            var tbody = $('#show-customer');
            tbody.empty();
            data.data.forEach(function (item) {
                var tr = `
                        <tr class="">
                            <td> ${item.customer_code} </td>
                            <td> ${item.name} </td>
                            <td> ${item.phone} </td>
                            <td> ${item.group_code} </td>
                            <td>
                                <input type="radio" name="customer_select" data-id="${item.id}">
                            </td>
                        </tr>
                    `;
                tbody.append(tr);
            });
            // hạng phòng
            var selected_customer_source = $('#selected-customer-source');
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
            $('#loading').hide();
        },
        error: function (error) {
            $('#loading').hide();
            console.log('Error:', error);
        }
    });
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
            // <p data-id="${ item.id }" data-room_type_id="${ item.room_type_id }" class="add-book-room" id="add-book-room">Đặt phòng</p>
            var tbody = $('#show-room');

            const dataNew = Object.values(data.data);
            let seenRooms = new Set();
            tbody.empty();
            dataNew.forEach(function (item) {
                let rowClass = '';
                let isFirst = !seenRooms.has(item.room_number);
                seenRooms.add(item.room_number);
                // Nếu không phải bản ghi đầu tiên, đặt class theo trạng thái
                if (!isFirst) {
                    if (item.check_booked === 'Đã nhận') {
                        rowClass = "background-primary";
                    } else if (item.check_booked === 'Đã đặt') {
                        rowClass = 'background-yellow';
                    }
                } else {
                    if (item.check_booked === 'Đã nhận') {
                        rowClass = "background-primary";
                    } else if (item.check_booked === 'Đã đặt') {
                        rowClass = 'background-yellow';
                    } else {
                        rowClass = "background-white";
                    }
                }

                var tr = `
                        <tr class="${rowClass}">
                            <td style="${isFirst ? 'font-weight: bold;' : ''}"> ${item.room_type['name']} </td>
                            <td style="${isFirst ? 'font-weight: bold;' : ''}"> ${item.room_number} </td>
                            <td style="${isFirst ? 'font-weight: bold;' : ''}"> ${formatDate(item.date)} </td>
                            <td style="${isFirst ? 'font-weight: bold;' : ''}"> ${item.check_booked} </td>
                            <td style="${isFirst ? 'font-weight: bold;' : ''}"> ${formatCurrency(item.room_type.room_type_price['unit_price'])} </td>
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
$('#selected-customer-source').on('change', function () {
    var selectedOptionCustomerSource = $('#selected-customer-source').val();
    var searchInput = $('#searchInput').val();
    showCustomer(searchInput, selectedOptionCustomerSource)
});

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
    $('#list-booking tr').each(function () {
        var checkbox = $(this).find('input[type="checkbox"]');
        if (checkbox.prop('checked')) {

            $(this).remove();
            let totalPrice = 0;
            totalPrice = calculateTotalPrice();
            $('#total_amount').text(formatCurrency(totalPrice));
            // $('#total_amount').text(formatCurrency(totalPrice));
            // $('#total_balance').text(formatCurrency(totalPrice));
        }
    });
});
// add phòng vào booked
$('.add-room-list').on('click', function () {
    const selectedCheckboxes = [];
    $('#show-room input[type="checkbox"]:checked').each(function () {
        const checkboxData = {
            room: $(this).data('id'),
            room_type: $(this).data('room_type_id'),
            date: $(this).data('date')
        };
        selectedCheckboxes.push(checkboxData);
    });
    addRoomInBooking(selectedCheckboxes)
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

$(document).on("dblclick", "#data-table tbody tr", function () {
    let customerId = $(this).find('input[type="radio"]').data("id");
    if (!customerId) {
        alert("Không tìm thấy ID khách hàng. Kiểm tra lại input radio!");
        return;
    }
    findCustomerById(customerId);
});
function findCustomerById(id){
    $.ajax({
        url: findCustomerUrl,
        type: 'GET',
        data: {
            id: id,
        },
        success: function (data) {
            console.log(data);
            $('#name').val(data.data.name);
            $('#phone').val(data.data.phone);
            $('#customer_code').val(data.data.customer_code);
            $("#select-customer-source").val(data.data.group_code).change();
            $('#addCustomerModal').modal('hide');
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
$('.add-room-booking').on('click', function () {
    const roomIds = [];
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
$('#addRoomModal').on('hidden.bs.modal', function () {
    if (!$('#addRoomModal').hasClass('show')) {
        $('#show-room tr').remove();
        document.body.classList.remove('modal-open');
        $('#addRoomModal').removeClass('z__index-mod');
        // xóa các phòng thêm vào damh sách booking
    }
});
$('#addCustomerModal').on('hidden.bs.modal', function () {
    if (!$('#addCustomerModal').hasClass('show')) {
        $('#show-room tr').remove();
        document.body.classList.remove('modal-open');
        $('#addCustomerModal').removeClass('z__index-mod');
        // xóa các phòng thêm vào damh sách booking
    }
});

function countBookings() {
    let bookingList = document.getElementById("list-booking");
    let rows = bookingList.getElementsByTagName("tr");
    return rows.length; // Trả về số lượng hàng <tr>
}

// Kiểm tra nếu danh sách đặt phòng có ít nhất một hàng
function hasBookings() {
    return countBookings() > 0;
}
$(document).on('click', '.add-book-room', function () {
    $('#list-booking-edit').empty();
    $('#myModal-booking').modal('show');
    // var roomId = $(this).data('id');
    // var roomTypeId = $(this).data('room_type_id');
    allStaffandCustomerSource()
    // $('#myModal-booking-edit').modal('hide');
    hasBookings() ? "" : ($('#total_balance').text(0), $('#total_amount').text(0));

    // addRoomInBooking(roomId, roomTypeId);
    var selectedCheckboxes = [];

});
function allStaffandCustomerSource(){
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
function formatDate(inputDate) {
    // Chia chuỗi ngày thành các phần tử: năm, tháng, ngày
    var parts = inputDate.split('-');

    // Định dạng lại chuỗi ngày
    var formattedDate = parts[2] + '/' + parts[1] + '/' + parts[0];

    return formattedDate;
}

function formatDateTime(inputDateTime) {
    // Chia chuỗi ngày và giờ thành các phần tử
    var dateTimeParts = inputDateTime.split(' ');

    // Chia phần ngày thành các phần tử: năm, tháng, ngày
    var dateParts = dateTimeParts[0].split('-');

    // Định dạng lại chuỗi ngày
    var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];

    // Nối lại chuỗi ngày và giờ
    var formattedDateTime = formattedDate + ' ' + dateTimeParts[1];

    return formattedDateTime;
}
$(document).on('click', '.close_modal', function () {
    $('#myModal-booking').modal('hide');
    $('#myModal-booking-edit').modal('hide');
});
$(document).on('click', '.close_modal_booked_room', function () {
    $('#addRoomModal').modal('hide');
    $('#addCustomerModal').modal('hide');
});

function calculateTotalPrice() {
    let totalPrice = 0;

    $('#list-booking, #list-booking-edit').find('p#price').each(function () {
        let priceString = $(this).attr('data-price');
        let price = parseFloat(priceString.replace(' VND', '').replace(',', '.'));


        totalPrice += price;
    });
    let pricediscount = 0;
    let discountInputValue = $('#discountInput').val();

    if (discountInputValue) {
        pricediscount = parseInt(discountInputValue.replace(/\./g, ''));
        pricediscount = isNaN(pricediscount) ? 0 : pricediscount;
    }
    $('.total_balance').text(formatCurrency(totalPrice - pricediscount));
    $('#total_balance').text(formatCurrency(totalPrice - pricediscount));
    // $('#total_deposit').text(formatCurrency(totalPrice));
    return totalPrice;
}

function addRoomInBooking(data) {
    $('#loading').show();
    $.ajax({
        url: checkRoomBookingUrl,
        type: 'POST',
        data: {
            data: JSON.stringify(data)
        },
        success: function (response) {
            var tbody = $('#list-booking');
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
                // let date = new Date(formattedDates);
                // date.setDate(date.getDate() + 1);
                // let yyyy2 = date.getFullYear();
                // let mm2 = String(date.getMonth() + 1).padStart(2, '0');
                // let dd2 = String(date.getDate()).padStart(2, '0');

                // const nextDay = `${yyyy2}-${mm2}-${dd2}`;
                let totalPrice = 0;
                // nextDay đang là time lớn hơn formattedDates 1 ngày
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
                                    <div class="d-flex align-items-center justify-content-start" style="gap: 10px">
                                        <input type="date" name="checkInDate" id="date-book-room" class="form-control date-book-room"  value="${item.date}">

                                        <input type="time" name="checkInTime" id="time-book-room" class="form-control time-book-room"  value="${item.room['room_type']['room_type_price']['setup_pricing']['check_in_time']}">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-start" style="gap: 10px">
                                        <input type="date" name="checkOutDate"  class="form-control date-book-room"  value="${date.toISOString().split('T')[0]}">

                                        <input type="time" name="checkOutTime" id="time-book-room" class="form-control time-book-room"  value="${item.room['room_type']['room_type_price']['setup_pricing']['check_out_time']}">

                                    </div>
                                </td>
                                <td>
                                     <p id="price" data-price="${item.room['room_type']['room_type_price']['unit_price']}">${formatCurrency(item.room['room_type']['room_type_price']['unit_price'])}</p>
                                </td>
                                <td>
                                      <input type="text" class="form-control deposit number-input" oninput="this.value = this.value.slice(0, 16)"  name="deposit"  placeholder="0">

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
                $('#total_amount').text(formatCurrency(totalPrice));
                $('#total_balance').text(formatCurrency(totalPrice));
                $('#loading').hide();
                let totalDeposit = 0;
                let totalBalance = 0;
                $('tr').find('input.deposit').on('blur', function () {
                    let rowTotal = 0;
                    $('tr').each(function () {
                        $(this).find('input.deposit').each(function () {
                            let depositValue = $(this).val().replace(/[,.]/g, '');
                            console.log(depositValue);
                            let numericDeposit = parseInt(depositValue) || 0;
                            rowTotal += numericDeposit;
                        });
                    });
                    $('#total_deposit').text(formatCurrency(rowTotal));
                    let priceString = $('#discountInput').val();
                    let price = parseInt(priceString.replace(/\./g, ""), 10);
                    price = isNaN(price) ? 0 : price;
                    totalBalance = totalPrice - rowTotal - price;
                    $('#total_balance').text(formatCurrency(totalBalance));
                });

                $('.number-input').on('blur', function () {
                    formatNumber(this);
                });

                $('.custom-input-giam-gia').on('blur', function () {
                    // Lấy giá trị từ trường nhập liệu
                    let discountValue = $(this).val();
                    let number = parseInt(discountValue.replace(/\./g, ""), 10);
                    number = isNaN(number) ? 0 : number;
                    let priceString = $('#total_amount').text();
                    let price = parseFloat(priceString.replace(/\./g, "").replace(' VND', ""), 10);

                    let pricedeposit = $('#total_deposit').text();
                    let deposit = parseFloat(pricedeposit.replace(/\./g, "").replace(' VND', ""), 10);

                    $('#total_balance').text(formatCurrency(price - deposit - number));
                    formatNumber(this);
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
    // Gán giá trị vào các input
    $('[id="date-book-room"]').val(currentDate);
    $('[id="time-book-room"]').val(currentTime);
});
// xóa phòng
$(document).on('click', '.delete-booked-room', function () {
    var roomId = $(this).data('room-id');


    if (confirm("Bạn có chắc chắn muốn xóa đặt phòng này?")) {
        var url = deleteBookedRoomUrl.replace(':id', roomId);
        $.ajax({
            url: url,
            type: 'POST',
            success: function (response) {
                if (response.status == 'success') {
                    notify('success', response.success);
                    loadRoomBookings();
                } else {
                    notify('error', response.success);
                }
            },
            error: function (error) {
                console.log('Error:', error);
            }
        });
    }
});
// chi tiết
$(document).on('click', '.booked_room_detail', function () {
    var roomId = $(this).data('room-id');
    var url = bookingDetailUrl.replace(':id',
        roomId);
    window.location.href = url;
});
// sửa phòng
$(document).on('click', '.booked_room_edit', function () {
    $('#list-booking').empty();
    var roomId = $(this).data('room-id');
    // ajax request
    var url = roomBookingEditUrl.replace(':id',
        roomId);
    $.ajax({
        url: url,
        type: 'POST',
        success: function (response) {
            if (response.status == 'success') {
                $('#myModal-booking-edit').modal('show').on('shown.bs.modal', function () {
                    $('.name-edit, .phone-edit').val('');
                    $('#list-booking-edit').empty();
                    var tbody = $('#list-booking-edit');
                    let totalPrice = 0;
                    response.data.forEach(item => {
                        $('.name-edit').val(item.customer_name);
                        $('.phone-edit').val(item.phone_number);
                        item.room_bookings.forEach(room => {

                            let [checkinDate, checkinTime] = room
                                .checkin_date.split(' ');
                            checkinTime = checkinTime.slice(0, 5);
                            let [checkoutDate, checkoutTime] = room
                                .checkout_date.split(' ');
                            checkoutTime = checkoutTime.slice(0, 5);

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
                                        <td style="display: flex; justify-content: center">
                                            <select id="bookingType" class="form-select" name="optionRoom" style="width: 93px; font-size:15px">
                                                <option value="ngay">Ngày</option>
                                                <option value="gio">Giờ</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start" style="gap: 10px">
                                                <input type="date" name="checkInDate" id="date-book-room" class="form-control date-book-room"  value="${checkinDate}">

                                                <input type="time" name="checkInTime" id="time-book-room" class="form-control time-book-room"  value="${checkinTime}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start" style="gap: 10px">
                                                <input type="date" name="checkOutDate"  class="form-control date-book-room" value="${checkoutDate}">

                                                <input type="time" name="checkOutTime" id="time-book-room" class="form-control time-book-room"  value="${checkoutTime}">

                                            </div>
                                        </td>
                                        <td>
                                            <p id="price" data-price="${room.total_amount}">${formatCurrency(room.total_amount)}</p>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control deposit number-input" oninput="this.value = this.value.slice(0, 16)"  name="deposit" value="${formatCurrencyEdit(room.deposit_amount)}" placeholder="0">
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

                        let priceString = $('.custom-input-giam-gia').val();
                        let price = parseInt(priceString.replace(/\./g, '')) || 0;

                        totalBalance = totalPrice - rowTotal - price;
                        $('.total_balance').text(formatCurrency(totalBalance));
                    }

                    // Chạy khi trang load
                    $(document).ready(function () {
                        calculateDepositAndBalance();
                    });
                    $('tr').find('input.deposit').on('blur', function () {
                        let rowTotal = 0;
                        $('tr').each(function () {
                            $(this).find('input.deposit').each(
                                function () {
                                    let depositValue = $(this).val()
                                        .replace(/[,.]/g,
                                            '');
                                    let numericDeposit = parseInt(
                                        depositValue) || 0;
                                    rowTotal += numericDeposit;
                                });
                        });
                        $('.total_deposit').text(formatCurrency(rowTotal));
                        let priceString = $('.custom-input-giam-gia').val();
                        let price = parseInt(priceString.replace(/\./g, ''));
                        price = isNaN(price) ? 0 : price;
                        totalBalance = totalPrice - rowTotal - price;
                        $('.total_balance').text(formatCurrency(totalBalance));
                    });

                    $('.number-input').on('blur', function () {
                        formatNumber(this);
                    });

                    $('.custom-input-giam-gia').on('blur', function () {
                        // Lấy giá trị từ trường nhập liệu
                        let discountValue = $(this).val();
                        let number = parseInt(discountValue.replace('.', ''));
                        number = isNaN(number) ? 0 : number;
                        let priceString = $('.total_amount').text();
                        let price = parseFloat(priceString.replace(/\./g, '')
                            .replace(' VND', ''));

                        let pricedeposit = $('.total_deposit').text();
                        let deposit = parseFloat(pricedeposit.replace(/\./g, '')
                            .replace(' VND',
                                ''));
                        $('.total_balance').text(formatCurrency(price -
                            deposit - number));
                        formatNumber(this);
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

// nhận phòng
$(document).on('click', '.booked_room', function () {
    var roomId = $(this).data('room-id');

    // ajax request
    var url = checkInUrl.replace(':id',
        roomId);
    $.ajax({
        url: url,
        type: 'POST',
        success: function (response) {
            if (response.status == 'success') {
                notify('success', response.success);
                loadRoomBookings();
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
$(document).on('click', '.icon-delete-room', function () {
    const row = $(this).closest('tr');
    const roomId = row.data('room-id');
    const roomTypeId = row.data('room-type-id');


    $('#confirmDeleteModal').modal('show');

    // Lưu thông tin phòng để xóa vào modal
    $('#confirmDeleteButton').off('click').on('click', function () {
        // Xử lý xóa
        row.remove();
        $('#confirmDeleteModal').modal('hide');
        // console.log('Xóa phòng với roomId:', roomId, 'và roomTypeId:',
        //     roomTypeId); // In ra thông tin xóa
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
                // if (response.status == 'success') {
                //     if (response.data !== null) {
                //         $('#phone').val(response.data['phone']);
                //     } else {
                //         $('#phone').val('');
                //     }

                // }
                let selectPhone = $('#selectphone');

                if (response.status == 'success') {
                   
                    selectPhone.empty();
                    // Nếu có số điện thoại, cập nhật giá trị vào select
                    if (response.data.length > 0) {
                        // selectPhone.append('<option value="">' + 'Chọn số điện thoại' + '</option>');
                        response.data.forEach(function (phoneInfo) {
                            selectPhone.append('<option value="' + phoneInfo.phone + '">' + phoneInfo.phone + '</option>');
                        });
                    } else {
                        // Nếu không có số điện thoại, thêm tùy chọn "Chưa có số điện thoại"
                        selectPhone.empty(); // Xóa tất cả các tùy chọn hiện tại trong select
                        selectPhone.append('<option value="">Chưa có số điện thoại</option>'); // Thêm tùy chọn mới
                        selectPhone.val('').trigger('change'); // Đặt giá trị mặc định là "Chưa có số điện thoại" và kích hoạt lại select2
                    }
                }

            },
            error: function (xhr, status, error) {

                // alert('Có lỗi xảy ra khi lưu ghi chú!');
            }
        });
    }

});

$(document).ready(function () {

    $(document).on('click', '.svg-icon', function (e) {
        e.stopPropagation();
        const $dropdown = $(this).siblings('.menu_dropdown');
        $('.menu_dropdown').not($dropdown).removeClass('show');
        $dropdown.toggleClass('show');
    });
    $(document).on('click', function () {
        $('.menu_dropdown').removeClass('show');
    });
    $(document).on('click', '.svg_menu_check_in', function (e) {
        e.stopPropagation();
        const $dropdown = $(this).siblings('.menu_dropdown_check_in');
        $('.menu_dropdown_check_in').not($dropdown).removeClass('show');
        $dropdown.toggleClass('show');
    });
    $(document).on('click', function () {
        $('.menu_dropdown_check_in').removeClass('show');
    });
    $(document).on('click', function () {
        $('.menu_dropdown').removeClass('show');
    });

});

function getDatesBetween(checkInDate, checkInTime, checkOutDate, checkOutTime, room, roomType, adult, note,
    deposit, roomBookingId) {

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
        //  dates.push(`${roomType}-${room}-${formattedDate}-${formattedDateOut}`);
        dates.push({
            roomType: roomType,
            room: room,
            dateIn: formattedDate,
            dateOut: formattedDateOut,
            adult: adult,
            note: note,
            deposit: deposit,
            bookingId: roomBookingId
        });

        break;
        currentDate.setDate(currentDate.getDate() + 1);


    }
    return dates;
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
    } else if (checkInTimeInt < currentTimeInt) {
        errorDiv.textContent =
            `Không thể nhận phòng trong quá khứ. Bạn có thể Đặt trước hoặc cập nhật giờ nhận về giờ hiện tại để nhận phòng.`;
        errorDiv.classList.add('alert', 'alert-danger');
        errorDiv.style.display = 'block';
        return false;
    } else {
        errorDiv.style.display = 'none'; // Ẩn div thông báo lỗi
        return true;
    }
}
// add
$('.btn-book').on('click', function () {
    const dataRowValue = $(this).data('row');
    $('.booking-form').data('row', dataRowValue);
    if (validateAllFields(formEconomyEdit)) {
        $('.booking-form').submit(); // Gửi form
    }
});
// edit
$('.btn-book-edit').on('click', function () {
    const dataRowValue = $(this).data('row');
    $('.booking-form-edit').data('row', dataRowValue);
    var name = $('.name-edit').val();
    $('.booking-form-edit').submit(); // Gửi form
    // if (name !== "" && name.trim()) {
    //     $('.name_error').text('');
    //     $('input[name="name"]').removeClass('is-invalid');

    // } else {
    //     $('input[name="name"]').addClass('is-invalid');
    //     $('.name_error').text('Tên không được bỏ trống');
    // }
});
$('.booking-form').on('submit', function (e) {
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
    $('#list-booking tr').each(function () {
        var roomId = $(this).data('room-id');
        var roomTypeId = $(this).data('room-type-id');
        var checkInDate = $(this).find('input[name="checkInDate"]').val();
        var checkInTime = $(this).find('input[name="checkInTime"]').val();
        var checkOutDate = $(this).find('input[name="checkOutDate"]').val();
        var checkOutTime = $(this).find('input[name="checkOutTime"]').val();
        var adult = $(this).find('input[name="adult"]').val();
        var note = $(this).closest('tr').find('input[name="note_room"]').val();
        var deposit = $(this).closest('tr').find('input[name="deposit"]').val();
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
        });
    });

    if (hasError) {
        roomData.forEach(function (item) {
            const roomDates = getDatesBetween(item['checkInDate'], item['checkInTime'],
                item['checkOutDate'], item['checkOutTime'], item['roomId'], item['roomTypeId'],
                item['adult'], item['note'], item['deposit'], "");

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
                        $('.bookingInfo').html('');
                        $('.booking-wrapper').addClass('d-none');
                        $(document).find('.orderListItem').remove();
                        $('.orderList').addClass('d-none');
                        $('.formRoomSearch').trigger('reset');
                        $('#myModal-booking').hide();
                        window.location.reload();
                    } else {
                        notify('error', response.error);
                    }
                },
            });
        }
    }

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
    // Duyệt qua từng dòng trong bảng 1234
    $('#list-booking-edit tr').each(function () {
        var status = $(this).data('status');

        if (status !== 0) {
            return;
        }


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
        // console.log(roomId, roomTypeId, checkInDate, checkInTime, checkOutDate, checkOutTime, adult, note);
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
            roomBookingId: roomBookingId,
        });
    });

    if (hasError) {
        roomData.forEach(function (item) {
            const roomDates = getDatesBetween(item['checkInDate'], item['checkInTime'],
                item['checkOutDate'], item['checkOutTime'], item['roomId'], item['roomTypeId'],
                item['adult'], item['note'], item['deposit'], item['roomBookingId']);

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
                        $('.bookingInfo').html('');
                        $('.booking-wrapper').addClass('d-none');
                        $(document).find('.orderListItem').remove();
                        $('.orderList').addClass('d-none');
                        $('.formRoomSearch').trigger('reset');
                        $('#myModal-booking-edit').hide();
                        window.location.reload();
                    } else {
                        notify('error', response.error);
                    }
                },
            });
        }
    }

});
$(document).on('click', '.note-booked-room', function () {
    var roomId = $(this).data('room-id');
    var note = $(this).closest('tr').find('input[name="note"]').val();
    $('#noteModal').modal('show');
    $('#note-input').val(note);
    $('#note-input').data('room-id', roomId);
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
$(document).ready(function () {
    loadRoomBookings(); // Function to load the room bookings
});

function formatCurrency(amount) {
    const parts = amount.toString().split('.');
    const integerPart = parts[0];
    const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    return formattedInteger + ' VND';
}

function formatDateTime(isoDateString) {
    if (!isoDateString) return '';

    const date = new Date(isoDateString);

    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear(); // Năm

    const hours = String(date.getHours()).padStart(2, '0'); // Giờ (2 chữ số)
    const minutes = String(date.getMinutes()).padStart(2, '0'); // Phút (2 chữ số)

    // Định dạng DD/MM/YYYY HH:MM
    return `${day}/${month}/${year} ${hours}:${minutes}`;
}

function loadRoomBookings(page = 1) {
    $.ajax({
        url: roomBookingUrl, // Adjust this to your route
        type: 'GET',
        data: {
            page: page
        },
        success: function (response) {

            if (response.status === 'success') {
                var data = response.data;
                var pagination = response.pagination;

                $('.data-table').html('');
                // <td>${data['admin']['name']}</td> user tạo
                data.forEach(function (data, index) {
                    var html = `
                        <tr data-id="${data['id']}">
                          <td>
                                <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"/><circle cx="10.5" cy="5.5" r="1"/><circle cx="10.5" cy="15.5" r="1"/></g></svg>
                                <div class="dropdown menu_dropdown_check_in" id="dropdown-menu">
                                    <div class="dropdown-item booked_room_edit" data-room-id="${data['booking_id']}">Sửa phòng</div>
                                    <div class="dropdown-item booked_room" data-room-id="${data['id']}">Nhận phòng</div>
                                    <div class="dropdown-item booked_room" data-room-id="${data['id']}">Đổi phòng</div>
                                    <div class="dropdown-item delete-booked-room"  data-room-id="${data['id']}" >Xóa phòng</div>

                                </div>
                            </td>
                             <td>${index + 1}</td>
                            <td>${data['booking_id']}</td>
                            <td>${data['room']['room_number']}</td>
                            <td>${formatDateTime(data['document_date'])}</td>
                            <td>${formatDateTime(data['checkin_date'])}</td>
                            <td>${formatDateTime(data['checkout_date'])}</td>
                            <td>${data['customer_name'] ? data['customer_name'] : 'N/A'}</td>
                            <td>${data['phone_number'] ? data['phone_number'] : 'N/A'}</td>
                            <td>${data['guest_count']}</td>
                            <td>${formatCurrency(data['total_amount'])}</td>
                            <td>${formatCurrency(data['deposit_amount'])}</td>



                        </tr>
                    `
                    $('.data-table').append(html);
                })
                updatePagination(pagination, 'loadRoomBookings');
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX request failed: " + error);
        }
    });
}




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
// allCheckInUrl
function loadRoomBookings(page = 1, data) {
    $('#loading-overlay').css('display', 'flex');
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
                const currentView = localStorage.getItem('viewMode');
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
                                        <tr data-id="${record['id']}" class="table-row">
                                            <td>
                                                <button class="btn btn-link btn-toggle" type="button"
                                                    onclick="toggleRepresentatives('${record['check_in_id']}', this)"></button>
                                            </td>
                                            <td class="text-center w-10">
                                                <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"/><circle cx="10.5" cy="5.5" r="1"/><circle cx="10.5" cy="15.5" r="1"/></g></svg>
                                                <div class="dropdown menu_dropdown_check_in" id="dropdown-menu">
                                                    <div class="dropdown-item booked_room_edit" data-room-id="${record['check_in_id']}">Sửa phòng</div>
                                                    <div class="dropdown-item booked_room" data-room-id="${record['id']}">Nhận phòng</div>
                                                    <div class="dropdown-item booked_room" data-room-id="${record['id']}">Đổi phòng</div>
                                                    <div class="dropdown-item delete-booked-room" data-room-id="${record['booking_id']}">Xóa phòng</div>
                                                </div>
                                            </td>
                                            <td class="text-right">${index + 1}</td>
                                            <td class="text-left w-10" >${record['check_in_id']}</td>

                                            <td class="text-right w-10">${bookingData.length}</td>
                                            <td class="text-right">${formatDateTime(record['document_date'])}</td>
                                            <td class="text-left">${record['customer_name'] ? record['customer_name'] : 'N/A'}</td>
                                            <td class="text-right">${record['phone_number'] ? record['phone_number'] : 'N/A'}</td>
                                            <td class="text-right w-10">${totalGuests}</td>
                                            <td class="text-right">${formatCurrency(totalPrice)}</td>
                                            <td class="text-right">${formatCurrency(totalAmount)}</td>
                                            <td class="text-right">${formatCurrency(totalDiscount)}</td>
                                        </tr>
                                          <tr class="collapse ${currentView === 'viewModel' ? "show" : ""}" id="rep-${firstRecord['check_in_id']}">
                                    <td colspan="12">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th colspan="1"></th>
                                                    <th colspan="6">Phòng</th>
                                                    <th colspan="6">Phòng mới</th>
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
                                    <td class="text-center w-10" colspan="1">
                                    <p  class="btn btn-primary change-room" style="font-size:13px"
                                        data-id="${record['id']}"
                                        data-name="${record['customer_name']}"
                                        data-room-id="${record['room_change_info'] ? record['room_change_info']['new_room_code'] : record['room_code']}"
                                        data-booking-id="${record['check_in_id']}" 
                                        data-booking-date="${formattedDates}" 
                                    >Đổi phòng</p>
                                    </td>
                                    <td class="text-left" colspan="6">${record['room']['room_number']}</td>
                                    <td class="text-left ${record['status'] == 1 ? "color-red" : ""}" colspan="6">
                                        ${record['room_change_info'] ? record['room_change_info']['room']['room_number'] : ""} 
                                    </td>
                                    <td class="text-right w-10">${formatDateTime(record['checkin_date'])}</td>
                                    <td class="text-right w-10" >${formatDateTime(record['checkout_date'])}</td>

                                    <td class="text-right w-10" >${record['guest_count']}</td>
                                  <td class="text-right w-10">
                                                        ${record['room_change_info'] ? formatCurrency(record['room_change_info']['total_amount']) : formatCurrency(record['total_amount'])}
                                                    </td>
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
                highlightOddRows();
                updatePagination(pagination, 'loadRoomBookings');
                setTimeout(function () {
                    $('#loading-overlay').css('display', 'none'); 
                }, 1000);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX request failed: " + error);
        }
    });
}
$(document).ready(function () {
    loadRoomBookings(); // Function to load the room bookings
    $(document).on('click', '.btn-submit-sync-book', function () {
        $('#booking_code').val('');
        $('#room_name').val('');
        $('#name_book').val('');
        loadRoomBookings();
    });
    // tìm kiếm 
    $(document).on('click', '.btn-submit-search-book', function () {
        let bookingCode = $('#booking_code').val();
        let roomName = $('#room_name').val();
        let customerName = $('#name_book').val();

        let data = {
            bookingCode: bookingCode,
            roomName: roomName,
            customerName: customerName,
        }
        loadRoomBookings(1, data);
    });
});
$(document).on('click', '.change-room', function () {
    let bookingId = $(this).data('booking-id');
    let roomId = $(this).data('room-id');
    let dateId = $(this).data('booking-date');
    let Id = $(this).data('id');
    let name = $(this).data('name');
    changeRoom(Id, bookingId, roomId, dateId, name)
    $('#changeRoomModal').modal('show');
    $('#changeRoomModal').on('shown.bs.modal', function () {
        document.body.classList.add('modal-open');
        $('#changeRoomModal').addClass('z__index-mod');
    });
});

$(document).on('click', '.change-booking-room', function () {
    let selectedRoom = $('input[name="change-room"]:checked'); // Lấy radio đã chọn
    if (selectedRoom.length === 0) {
        notify('error', 'Vui lòng chọn phòng');
        return;
    }
    let roomId = selectedRoom.data('id');
    let roomTypeId = selectedRoom.data('room_type_id');
    let date = selectedRoom.data('date');

    let roomNew = {
        room_id: roomId,
        room_type_id: roomTypeId,
        date: date
    }
    // Xóa các input cũ để tránh trùng lặp
    $('#btn-change-booking-room input[name="room_id_new"]').remove();
    $('#btn-change-booking-room input[name="room_type_id_new"]').remove();
    $('#btn-change-booking-room input[name="date_new"]').remove();

    $('#btn-change-booking-room').append(`
        <input type="hidden" name="room_id_new" value="${roomNew.room_id}">
        <input type="hidden" name="room_type_id_new" value="${roomNew.room_type_id}">
        <input type="hidden" name="date_new" value="${roomNew.date}">
    `);
    $('#btn-change-booking-room').submit();

});
$('#btn-change-booking-room').on('submit', function (e) {
    e.preventDefault();
    let formData = $(this).serializeArray();
    let formObject = {};
    formData.forEach(function (field) {
        formObject[field.name] = field.value;
    });
    formData.push({
        name: 'choice',
        value: 'room_booking',
    });
    let url = $(this).attr('action');
    $.ajax({
        type: "POST",
        url: url,
        data: formData,
        success: function (response) {
            if (response.status === 'success') {
                notify('success', response.data);
                $('#changeRoomModal').modal('hide');
                loadRoomBookings();
            } else {
                notify('error', response.message);
                // changeRoom(Id, bookingId, roomId, dateId);
            }
        },
    });

});
function updatePagination(pagination) {
    var paginationHtml = '';
    if (pagination.current_page > 1) {
        paginationHtml += `<button onclick="loadRoomBookings(${pagination.current_page - 1})">Trước</button>`;
    }
    for (var i = 1; i <= pagination.last_page; i++) {
        var activeClass = pagination.current_page === i ? 'active' : '';
        paginationHtml += `
            <button class="${activeClass}" onclick="loadRoomBookings(${i})">${i}</button>
        `;
    }
    if (pagination.current_page < pagination.last_page) {
        paginationHtml += `<button onclick="loadRoomBookings(${pagination.current_page + 1})">Tiếp theo</button>`;
    }
    $('.pagination-container').html(paginationHtml);
}

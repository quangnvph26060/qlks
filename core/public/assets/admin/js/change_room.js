
"use strict";

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
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
                                                    onclick="toggleRepresentatives('${record['room_change_id']}', this)"></button>
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
                                            <td class="text-left">${record['room_change_id']}</td>

                                            <td class="text-right">${bookingData.length}</td>
                                            <td class="text-right">${formatDateTime(record['document_date'])}</td>
                                            <td class="text-left">${record['customer_name'] ? record['customer_name'] : 'N/A'}</td>
                                            <td class="text-right">${record['phone_number'] ? record['phone_number'] : 'N/A'}</td>
                                            <td class="text-right">${totalGuests}</td>
                                            <td class="text-right">${formatCurrency(totalPrice)}</td>
                                            <td class="text-right">${formatCurrency(totalAmount)}</td>
                                            <td class="text-right">${formatCurrency(totalDiscount)}</td>
                                        </tr>
                                          <tr class="collapse" id="rep-${firstRecord['room_change_id']}">
                                    <td colspan="12">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th colspan="1">Mã nhận phòng</th>
                                                    <th colspan="1">Mã đặt phòng</th>
                                                    <th colspan="6">Phòng cũ</th>
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
                                                    <td class="text-left w-10" colspan="1">${record['id_check_in']}</td>
                                                     <td class="text-left w-10" colspan="1">${record['id_room_booking']}</td>
                                                      <td class="text-left" colspan="6">${record['room_old']['room_number']}</td>
                                                         <td class="text-left" colspan="6">${record['room_new']['room_number']}</td>
                                                    <td class="text-right w-10">${formatDateTime(record['checkin_date'])}</td>
                                                    <td class="text-right w-10" >${formatDateTime(record['checkout_date'])}</td>

                                                    <td class="text-right w-10" >${record['guest_count']}</td>
                                                    <td class="text-right w-10">${formatCurrency(record['total_amount'])}</td>
                                                    <td class="text-right w-10">${formatCurrency(record['deposit_amount'])}</td>
                                                    <td class="text-right w-10">${formatCurrency(record['discount'])}</td>
                                                    <td class="text-left">${record['note']}</td>
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
$(document).ready(function () {
    loadRoomBookings(); // Function to load the room bookings
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

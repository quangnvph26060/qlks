"use strict";

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function () {
    loadRoomBookings(); // Function to load the room bookings
    $(document).on('click', '.svg_menu_check_in', function (e) {
        e.stopPropagation();
        const $dropdown = $(this).siblings('.menu_dropdown_check_in');
        $('.menu_dropdown_check_in').not($dropdown).removeClass('show');
        $dropdown.toggleClass('show');
    });
    $(document).on('click', '.btn-submit-search-book', function () {
        let bookingCode = $('#booking_code').val();

        let data = {
            bookingCode: bookingCode,
        }

        loadRoomBookings(1, data);
    });
    $(document).on('click', '.btn-submit-sync-book', function () {
        $('#booking_code').val('');
        $('#room_name').val('');
        $('#name_book').val('');
        loadRoomBookings();
    });
});
function loadRoomBookings(page = 1, data) {
    $('#loading-overlay').css('display', 'flex');
    $.ajax({
        url: paymentUrl, // Adjust this to your route
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
                let countRoom = 0;
                data.forEach(function (record, idx) {
                    html += `<tr data-id="${record['id']}" class="table-row">
                                       <td class="text-right">${idx + 1}</td>
                                        <td class="text-center w-10" >
                                            <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"/><circle cx="10.5" cy="5.5" r="1"/><circle cx="10.5" cy="15.5" r="1"/></g></svg>
                                            <div class="dropdown menu_dropdown_check_in" id="dropdown-menu">
                                                <div class="dropdown-item booked_room_edit" data-room-id="${record['id']}">Sửa</div>
                                                <div class="dropdown-item delete-booked-room" data-room-id="${record['id']}">Xóa </div>
                                            </div>
                                        </td>
                                       
                                        <td class="text-left w-10">${record['booking_id']}</td>
                                        <td class="text-left w-10">${record['checkin_id']}</td>
                                        <td class="text-left">${formatDateTime(record['created_date'])}</td>
                                        <td class="text-right">${formatCurrency(record['room_price'])}</td>
                                        <td class="text-right">${formatCurrency(record['deposit_amount'])}</td>
                                        <td class="text-right">${formatCurrency(record['discount_amount'])}</td>
                                         <td class="text-right">${formatCurrency(record['total_payment'])}</td>
                                           <td class="text-left">${record['payment_method']}</td>
                                    </tr>`

                });
                // <td class="text-left">${record['customer_name'] ? record['customer_name'] : 'N/A'}</td>
                // <td class="text-right">${record['phone_number'] ? record['phone_number'] : 'N/A'}</td>
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

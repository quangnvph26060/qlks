@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between mb-3 row order-1">
                <div class="dt-length col-md-6 col-4">
                    <select name="example_length" id="perPage" style=" padding: 1px 3px; margin-right: 8px;"
                        aria-controls="example" class="perPage">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select><label for="perPage"> entries per page</label>
                </div>
            </div>
        </div>
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--md">
                    <table class="table--light style--two table table-striped" id="data-table">
                        <thead>
                            <tr>

                                <th>@lang('STT')</th>
                                <th>@lang('Mã đặt hàng')</th>
                                <th>@lang('Mã phòng')</th>
                                <th>@lang('Ngày chứng từ')</th>
                                <th>@lang('Ngày nhận')</th>
                                <th>@lang('Ngày trả')</th>
                                <th>@lang('Mã khách')</th>
                                <th>@lang('Tên khách hàng')</th>
                                <th>@lang('Số điện thoại')</th>
                              
                                <th>@lang('Số người')</th>
                                <th>@lang('Thành tiền')</th>
                                <th>@lang('Đặt cọc')</th>
                                <th>@lang('Ghi chú')</th>
                                <th>@lang('Nguồn khách')</th>
                                <th>@lang('User tạo')</th>
                                @can(['admin.hotel.room.type.edit', 'admin.hotel.room.type.status',
                                    'admin.hotel.room.type.destroy'])
                                    <th>@lang('Hành động')</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            <thead class="data-table">

                            </thead>
                        </tbody>
                    </table>

                </div>
            </div>
            <div id="pagination" class="m-3">

            </div>
        </div>
        <div class="pagination-container"></div>
    </div>

    @include('admin.booking.partials.room_booking')

    @include('admin.booking.partials.confirm-room')
@endsection

@can('admin.booking.all')
    @push('breadcrumb-plugins')
        {{-- <a class="btn btn-sm btn--primary" href="{{ route('admin.booking.all') }}">
            <i class="la la-list"></i>@lang('Tất cả các đặt phòng')
        </a> --}}
        <a class="btn btn-sm btn--primary add-room-booking">
            <i class="la la-plus"></i>@lang('Đặt phòng')
        </a>
        <div class="modal fade" id="addRoomModal" tabindex="-1" aria-hidden="true" style="overflow: unset">
            <div class="modal-dialog modal-dialog-centered" style="top: 4px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chọn Phòng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body overflow-add-room">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th data-table="Hạng phòng">Hạng phòng</th>
                                    <th data-table="Phòng">Phòng</th>
                                    <th data-table="Giá">Giá</th>
                                    <th data-table="Thao tác">Thao tác</th>

                                </tr>
                            </thead>

                            <tbody id="show-room">

                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
        </div>
    @endpush
@endcan

@push('script-lib')
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/validator/validator.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/book.css') }}">
@endpush

@push('style')
    <style scoped>
    .table-responsive--md {
        overflow-x: auto; /* Enable horizontal scrolling if the table overflows */
    }

    /* Optional: Adjust the font size and padding for smaller screens */
    @media (max-width: 768px) {
        .table--light {
            font-size: 12px; /* Reduce font size on smaller screens */
        }
        .table td, .table th {
            padding: 5px; /* Reduce padding for more compact view */
        }
    }
        .booking-table td {
            white-space: unset;
        }

        .modal-open .select2-container {
            z-index: 9 !important;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination-container button {
            background-color: #4634ff;
            color: white;
            border: 1px solid #ddd;
            padding: 10px 15px;
            margin: 0 5px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .pagination-container button:hover {
            background-color: #4634ff;
            transform: scale(1.05);
        }

        .pagination-container button:disabled {
            background-color: #ddd;
            cursor: not-allowed;
        }

        .pagination-container button.active {
            background-color: #4634ff;
            border-color: #4634ff;
        }

        .pagination-container button:first-child {
            border-radius: 5px 0 0 5px;
        }

        .pagination-container button:last-child {
            border-radius: 0 5px 5px 0;
        }
    </style>
@endpush

@push('script')
    <script>
        "use strict";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const start = moment();
        const end = moment().add(1, 'days');

        function formatCurrency(amount) {
            const parts = amount.toString().split('.');
            const integerPart = parts[0];
            const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            return formattedInteger + ' VND';
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
                        'func': function(value) {
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



        $('[name=guest_type]').on('change', function() {
            if ($(this).val() == 1) {
                $('.guestInputDiv').addClass('d-none');
                $('.forGuest').attr("required", false);
            } else {
                $('.guestInputDiv').removeClass('d-none');
                $('.forGuest').attr("required", true);
            }
        });


        $('.formRoomSearch').on('submit', function(e) {
            e.preventDefault();

            let searchDate = $('[name=date]').val();
            if (searchDate.split(" - ").length < 2) {
                notify('error', `@lang('Ngày nhận phòng và ngày trả phòng phải được cung cấp khi đặt phòng.')`);
                return false;
            }

            resetDOM();
            let formData = $(this).serialize();
            let url = $(this).attr('action');

            $.ajax({
                type: "get",
                url: url,
                data: formData,
                success: function(response) {
                    $('.bookingInfo').html('');
                    $('.booking-wrapper').addClass('d-none');
                    if (response.error) {
                        notify('error', response.error);
                    } else if (response.html.error) {
                        notify('error', response.html.error);
                    } else {
                        $('.bookingInfo').html(response.html);
                        let roomTypeId = $('[name=room_type]').val();
                        $('[name=room_type_id]').val(roomTypeId);


                        $('.booking-wrapper').removeClass('d-none');
                    }
                },
                processData: false,
                contentType: false,
            });
        });

        function resetDOM() {
            $(document).find('.orderListItem').remove();
            $('.totalFare').data('amount', 0);
            $('.totalFare').text(`0 {{ __(gs()->cur_text) }}`);
            $('.taxCharge').text('0');
            $('[name=tax_charge]').val('0');
            $('.grandTotalFare').text(`0 {{ __(gs()->cur_text) }}`);
            $('[name=total_amount]').val('0');
            $('[name=paid_amount]').val('');
            $('[name=room_type_id]').val('');
        }

        $(document).on('click', '.confirmBookingBtn', function() {
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
        $('.select2-basic').select2({
            dropdownParent: $('.select2-parent')
        });

        ///////////////////
        function showRoom(data) {
            $('#loading').show();
            $.ajax({
                url: '{{ route('admin.booking.showRoom') }}',
                type: 'POST',
                data: {
                    roomIds: data
                },
                success: function(data) {
                    var tbody = $('#show-room');
                    data.data.forEach(function(item) {
                        var tr = `
                                    <tr>
                                        <td> ${ item.room_type['name'] } </td>
                                        <td> ${ item.room_number } </td>
                                        <td> ${ formatCurrency(item.room_type.room_type_price['unit_price']) } </td>
                                        <td> <p data-id="${ item.id }" data-room_type_id="${ item.room_type_id }" class="add-book-room" id="add-book-room">Đặt phòng</p> </td>
                                    </tr>
                                `;
                        tbody.append(tr);
                        $('#loading').hide();
                    });
                },
                error: function(error) {
                    $('#loading').hide();
                    console.log('Error:', error);
                }
            });
        }
        $('.add-room-booking').on('click', function() {

            const roomIds = [];
            $('#list-booking tr').each(function() {
                const roomId = $(this).attr('data-room-id');

                if (roomId) {
                    roomIds.push(roomId);
                }
            });
            showRoom(roomIds)
            $('#addRoomModal').modal('show');

            $('#addRoomModal').on('shown.bs.modal', function() {
                document.body.classList.add('modal-open');
                $('#addRoomModal').addClass('z__index-mod');
            });

        });
        $('#addRoomModal').on('hidden.bs.modal', function() {
            if (!$('#addRoomModal').hasClass('show')) {
                $('#show-room tr').remove();
                document.body.classList.remove('modal-open');
                $('#addRoomModal').removeClass('z__index-mod');
                // xóa các phòng thêm vào damh sách booking
            }
        });
        $(document).on('click', '.add-book-room', function() {
            var roomId = $(this).data('id');
            var roomTypeId = $(this).data('room_type_id');
            $('#myModal-booking').modal('show');
            addRoomInBooking(roomId, roomTypeId);
        });

        function addRoomInBooking(roomId, roomTypeId) {
            $('#loading').show();
            $.ajax({
                url: '{{ route('admin.booking.checkRoomBooking') }}',
                type: 'POST',
                data: {
                    room_id: roomId,
                    room_type_id: roomTypeId
                },
                success: function(response) {
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
                        let date = new Date(formattedDates);
                        date.setDate(date.getDate() + 1);
                        let yyyy2 = date.getFullYear();
                        let mm2 = String(date.getMonth() + 1).padStart(2, '0');
                        let dd2 = String(date.getDate()).padStart(2, '0');

                        const nextDay = `${yyyy2}-${mm2}-${dd2}`;


                        const formattedTimes = `${hoursss}:${minutesss}`;
                        // <td>
                        //      <p id="book_name" class="book_name">${response.room_type['name']}</p>
                        // </td>
                        var tr = `
                                    <tr data-room-id="${response.room['id']}"  data-room-type-id="${response.room_type['id']}">
                                    
                                        <td>
                                            <p class="room__name"> ${response.room['room_number']}</p>
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
                                                <input type="date" name="checkInDate" id="date-book-room" class="form-control date-book-room"  value="${formattedDates}">

                                                <input type="time" name="checkInTime" id="time-book-room" class="form-control time-book-room"  value="${formattedTimes}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start" style="gap: 10px">
                                                <input type="date" name="checkOutDate"  class="form-control date-book-room"  value="${nextDay}">

                                                <input type="time" name="checkOutTime" id="time-book-room" class="form-control time-book-room"  value="${formattedTimes}">
                                               
                                            </div>
                                        </td>
                                        <td>
                                              <input type="text" class="form-control deposit" id="number-input" oninput="this.value = this.value.slice(0, 16)"  name="deposit"  placeholder="0">
                                           
                                        </td>
                                        <td>
                                             
                                                <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" style="width: 420px;">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="noteModalLabel">Ghi chú</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input class="form-control" type="text" name="note" id="note-input" placeholder="Nhập ghi chú...">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bỏ qua</button>
                                                                <button type="button" class="btn btn-success save-note" id="save-note">Lưu</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="position: relative; display: inline-block;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 21 21" class="svg-icon">
                                                        <g fill="currentColor" fill-rule="evenodd">
                                                            <circle cx="10.5" cy="10.5" r="1" />
                                                            <circle cx="10.5" cy="5.5" r="1" />
                                                            <circle cx="10.5" cy="15.5" r="1" />
                                                        </g>
                                                    </svg>
                                                    <div class="dropdown menu_dropdown" id="dropdown-menu">
                                                        <div class="dropdown-item note-booked-room" data-note="" data-room-id="${response.room['id']}" >Ghi chú</div>
                                                        <div class="dropdown-item icon-delete-room">Xóa phòng</div>
                                                    </div>
                                                </div>
                                                   <input type="hidden" name="note_room" value="" id="hidden_note">
                                        </td>


                                    </tr>
                                `;

                        $('#loading').hide();
                        tbody.append(tr);
                        $('#number-input').on('input', function() {
                            formatNumber(this);
                        });

                        $('#addRoomModal').modal('hide');
                        document.body.classList.remove("modal-open");
                    }
                },
                error: function(error) {
                    $('#loading').hide();
                    console.log('Error:', error);
                }
            });
        }
        $(document).on('click', '[id="hour_current"]', function() {
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
        // xóa phòng
        $(document).on('click', '.delete-booked-room', function() {
            var roomId = $(this).data('room-id');
            console.log(roomId);

        });
        // nhận phòng
        $(document).on('click', '.booked_room', function() {
            var roomId = $(this).data('room-id');
        
            // ajax request
            var url = `{{ route('admin.room.check.in', ['id' => ':id']) }}`.replace(':id',
                roomId);
            $.ajax({
                url: url,
                type: 'POST',
                success: function(response) {
                    if (response.status == 'success') {
                        notify('success', response.success);
                        loadRoomBookings();
                    } else {
                        notify('error', response.success);
                    }
                },
                error: function(error) {
                    // notify('error', error.responseJSON.message);
                    console.log('Error:', error);
                }
            });
        });
        $(document).on('click', '.icon-delete-room', function() {
            const row = $(this).closest('tr');
            const roomId = row.data('room-id');
            const roomTypeId = row.data('room-type-id');


            $('#confirmDeleteModal').modal('show');

            // Lưu thông tin phòng để xóa vào modal
            $('#confirmDeleteButton').off('click').on('click', function() {
                // Xử lý xóa
                row.remove();
                $('#confirmDeleteModal').modal('hide');
                // console.log('Xóa phòng với roomId:', roomId, 'và roomTypeId:',
                //     roomTypeId); // In ra thông tin xóa
            });
        });

        $(document).on('click', '#btn-search', function() {
            var customerName = $('#name').val();
            let flag = true;
            if (customerName == '') {
                flag = false;
            }
            if (flag) {
                // ajax request
                $.ajax({
                    url: '{{ route('admin.search.customer') }}',
                    type: 'GET',
                    data: {
                        name: customerName,
                    },
                    success: function(response) {
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
                    error: function(xhr, status, error) {

                        // alert('Có lỗi xảy ra khi lưu ghi chú!');
                    }
                });
            }

        });

        $(document).ready(function() {

            $(document).on('click', '.svg-icon', function(e) {
                e.stopPropagation();
                const $dropdown = $(this).siblings('.menu_dropdown');
                $('.menu_dropdown').not($dropdown).removeClass('show');
                $dropdown.toggleClass('show');
            });
            $(document).on('click', '.svg_menu_check_in', function(e) {
                e.stopPropagation();
                const $dropdown = $(this).siblings('.menu_dropdown_check_in');
                $('.menu_dropdown_check_in').not($dropdown).removeClass('show');
                $dropdown.toggleClass('show');
            });
            $(document).on('click', function() {
                $('.menu_dropdown_check_in').removeClass('show');
            });
            $(document).on('click', function() {
                $('.menu_dropdown').removeClass('show');
            });

        });





        function getDatesBetween(checkInDate, checkInTime, checkOutDate, checkOutTime, room, roomType, adult, note,
            deposit) {

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
                    deposit: deposit
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
        $('.btn-confirm, .btn-book').on('click', function() {
            const dataRowValue = $(this).data('row');
            $('.booking-form').data('row', dataRowValue);
            if (validateAllFields(formEconomyEdit)) {
                $('.booking-form').submit(); // Gửi form
            }

        });

        $('.booking-form').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serializeArray();
            // var adultsValue = parseInt($('#adults').val(), 10) || 0;
            // var childrenValue = parseInt($('#children').val(), 10) || 0;

            // var totalPeople = adultsValue + childrenValue;

            let formObject = {};
            formData.forEach(function(field) {
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
            $('#list-booking tr').each(function() {
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
                });
            });

            if (hasError) {
                roomData.forEach(function(item) {
                    const roomDates = getDatesBetween(item['checkInDate'], item['checkInTime'],
                        item['checkOutDate'], item['checkOutTime'], item['roomId'], item['roomTypeId'],
                        item['adult'], item['note'], item['deposit']);

                    roomDates.forEach(function(date, index) {
                        formData.push({
                            name: 'room[]',
                            value: JSON.stringify(date)
                        });
                    });
                })


                // formData.push({
                //     name: 'is_method',
                //     value: 'receptionist',
                // });
                let shouldSubmit = true;
                formData.some(function(item) {
                    if (item.name === 'room[]') {
                        const data = item.value;
                        let dataArray = JSON.parse(data);
                        const timeCheckIn =  dataArray['dateIn'];
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
                        success: function(response) {
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
        $(document).on('click', '.note-booked-room', function() {
            var roomId = $(this).data('room-id');
            var note = $(this).closest('tr').find('input[name="note"]').val();
            $('#noteModal').modal('show');
            $('#note-input').val(note);
            $('#note-input').data('room-id', roomId);
        });

        $(document).on('click', '.save-note', function() {
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
        $(document).ready(function() {
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
                url: '{{ route('admin.room.booking') }}', // Adjust this to your route
                type: 'GET',
                data: {
                    page: page
                },
                success: function(response) {

                    if (response.status === 'success') {
                        var data = response.data;
                        var pagination = response.pagination;

                        $('.data-table').html('');

                        data.forEach(function(data, index) {
                            var html = `
                                <tr data-id="${data['id']}">
                                     <td>${index + 1  }</td>
                                    <td>${data['booking_id']}</td>
                                    <td>${data['room']['room_number']}</td>
                                    <td>${data['document_date']}</td>
                                    <td>${data['checkin_date']}</td>
                                    <td>${data['checkout_date']}</td>
                                    <td>${data['customer_code'] ? data['customer_code'] : 'N/A'}</td>
                                    <td>${data['customer_name'] ? data['customer_name'] : 'N/A'}</td>
                                    <td>${data['phone_number'] ? data['phone_number'] : 'N/A'}</td>
                                  
                                    <td>${data['guest_count']}</td>
                                    <td>${ formatCurrency( data['total_amount'])}</td>
                                    <td>${formatCurrency(data['deposit_amount'])}</td>
                                    <td>${data['note']}</td>
                                    <td>${data['user_source']}</td>
                                    <td>${data['admin']['name']}</td>
                                    <td>
                                        <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"/><circle cx="10.5" cy="5.5" r="1"/><circle cx="10.5" cy="15.5" r="1"/></g></svg>    
                                        <div class="dropdown menu_dropdown_check_in" id="dropdown-menu">
                                            <div class="dropdown-item booked_room" data-room-id="${data['id']}">Nhận phòng</div>
                                            <div class="dropdown-item booked_room" data-room-id="${data['id']}">Đổi phòng</div>
                                            <div class="dropdown-item delete-booked-room"  data-room-id="${data['id']}" >Xóa phòng</div>
                                           
                                        </div>
                                    </td>
                                </tr>
                            `
                            $('.data-table').append(html);
                        })
                        updatePagination(pagination);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request failed: " + error);
                }
            });
        }

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
    </script>
@endpush

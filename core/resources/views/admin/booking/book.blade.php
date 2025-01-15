@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        @can('admin.room.search')
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.room.search') }}" class="formRoomSearch" method="get">
                            <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
                                <div class="form-group flex-fill">
                                    <label>@lang('Loại phòng')</label>
                                    <select class="form-control" name="room_type" required>
                                        <option value="">@lang('Chọn một')</option>
                                        @foreach ($roomTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group flex-fill">
                                    <label>@lang('Ngày nhận phòng - Ngày trả phòng')</label>
                                    <input autocomplete="off" class="bookingDatePicker form-control bg--white" name="date"
                                        placeholder="@lang('Chọn ngày')" required type="text">
                                </div>
                                <div class="form-group flex-fill">
                                    <label>@lang('Danh sách phòng')</label>
                                    <input class="form-control" name="rooms" placeholder="@lang('Có bao nhiêu phòng?')" required
                                        type="text">
                                </div>

                                <div class="form-group flex-fill">
                                    <button class="btn btn--primary w-100 h-45 search" type="submit">
                                        <i class="la la-search"></i>@lang('Tìm kiếm')
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        @endcan
    </div>
    <div class="row booking-wrapper d-none">
        <div class="col-lg-8 mt-3">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-flex justify-content-between booking-info-title mb-0">
                        <h5>@lang('Thông tin đặt phòng')</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="pb-3">
                        <span class="fas fa-circle text--danger" disabled></span>
                        <span class="mr-5">@lang('Đã đặt chỗ')</span>
                        <span class="fas fa-circle text--success"></span>
                        <span class="mr-5">@lang('Đã chọn')</span>
                        <span class="fas fa-circle text--primary"></span>
                        <span>@lang('Có sẵn')</span>
                    </div>
                    <div class="alert alert-info room-assign-alert p-3" role="alert">
                    </div>
                    <div class="bookingInfo">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mt-3">
            <div class="card">
                <div class="card-header">
                    <div class="card-title mb-0">
                        <h5>@lang('Đặt phòng')</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.room.book') }}" class="booking-form" id="booking-form" method="POST">
                        @csrf
                        <input name="room_type_id" type="hidden">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>@lang('Loại khách hàng')</label>
                                    <select class="form-control" name="guest_type">
                                        <option selected value="0">@lang('Khách lưu trú')</option>
                                        <option value="1">@lang('Khách đã đăng ký')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 guestInputDiv">
                                <div class="form-group">
                                    <label>@lang('Tên')</label>
                                    <input class="form-control forGuest" name="guest_name" required type="text">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label>@lang('Email')</label>
                                    <input class="form-control" name="email" required type="email">
                                </div>
                            </div>

                            <div class="col-12 guestInputDiv">
                                <div class="form-group">
                                    <label>@lang('Số điện thoại')</label>
                                    <input class="form-control forGuest" name="mobile" required type="number">
                                </div>
                            </div>
                            <div class="col-12 guestInputDiv">
                                <div class="form-group">
                                    <label>@lang('Địa chỉ')</label>
                                    <input class="form-control forGuest" name="address" required type="text">
                                </div>
                            </div>

                            <div class="orderList d-none">
                                <ul class="list-group list-group-flush orderItem">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <h6>@lang('Danh sách phòng ')</h6>
                                        <h6>@lang('Ngày')</h6>
                                        <span>
                                            <h6>@lang('Giá')</h6>
                                        </span>
                                        <span>
                                            <h6>@lang('Tổng cộng')</h6>
                                        </span>
                                    </li>
                                </ul>
                                <div class="d-flex justify-content-between align-items-center border-top p-2">
                                    <span>@lang('Tổng cộng')</span>
                                    <span class="totalFare" data-amount="0"></span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center border-top p-2">
                                    <span>{{ gs()->tax_name }} <small>({{ gs()->tax }}%)</small></span>
                                    <span><span class="taxCharge" data-percent_charge="{{ gs()->tax }}"></span>
                                        {{ __(gs()->cur_text) }}</span>
                                    <input name="tax_charge" type="hidden">
                                </div>

                                <div class="d-flex justify-content-between align-items-center border-top p-2">
                                    <span>@lang('Tổng tiền')</span>
                                    <span class="grandTotalFare"></span>
                                    <input hidden name="total_amount" type="text">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label>@lang('Nhập tiền thanh toán')</label>
                                    <input class="form-control" min="0" name="paid_amount"
                                        placeholder="@lang('Tổng cộng')" step="any" type="number">
                                </div>
                            </div>
                            @can('admin.room.book')
                                <div class="form-group mb-0">
                                    <button class="btn btn--primary h-45 w-100 btn-book confirmBookingBtn"
                                        type="button">@lang('Đặt ngay')</button>
                                </div>
                            @endcan
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmBookingModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Thông báo xác nhận!')</h5>
                    <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('Bạn có chắc chắn đặt phòng này không?')</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn--dark" data-bs-dismiss="modal" type="button">@lang('Không')</button>
                    <button class="btn btn--primary btn-confirm" type="button">@lang('Có')</button>
                </div>
            </div>
        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/book.css') }}">
@endpush

@push('style')
    <style>
        .booking-table td {
            white-space: unset;
        }

        .modal-open .select2-container {
            z-index: 9 !important;
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

        $('.btn-confirm').on('click', function() {
            $('#confirmBookingModal').modal('hide');
            $('.booking-form').submit();
        });

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
                                             <svg  class="icon-delete-room" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
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

        $(document).on('click', '.icon-delete-room', function() {
            const row = $(this).closest('tr');
            const roomId = row.data('room-id');
            const roomTypeId = row.data('room-type-id');

            console.log(roomId, roomTypeId);

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
            var customerName = $('#customer-name').val();
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

        function getDatesBetween(checkInDate, checkInTime, checkOutDate, checkOutTime, room, roomType,
            optionRoom) {
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
                dates.push(`${roomType}-${room}-${formattedDate}-${formattedDateOut}-${optionRoom}`);
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
            $('.booking-form').submit(); // Gửi form
        });

        $('.booking-form').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serializeArray();
            var adultsValue = parseInt($('#adults').val(), 10) || 0;
            var childrenValue = parseInt($('#children').val(), 10) || 0;

            var totalPeople = adultsValue + childrenValue;

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
                var checkInDate = $(this).find('input[name="checkInDate"]')
                    .val(); // Lấy giá trị ngày
                var checkInTime = $(this).find('input[name="checkInTime"]')
                    .val(); // Lấy giá trị giờ
                var checkOutDate = $(this).find('input[name="checkOutDate"]')
                    .val(); // Lấy giá trị ngày
                var checkOutTime = $(this).find('input[name="checkOutTime"]')
                    .val(); // Lấy giá trị giờ
                var optionRoom = $(this).find('select[name="optionRoom"]')
                    .val(); // lấy giá trị option gio/ngay/dem
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
                    optionRoom: optionRoom
                });
            });

            if (hasError) {
                roomData.forEach(function(item) {
                    const roomDates = getDatesBetween(item['checkInDate'], item['checkInTime'],
                        item['checkOutDate'], item['checkOutTime'], item['roomId'], item[
                            'roomTypeId'], item['optionRoom']);
                    roomDates.forEach(function(date, index) {
                        formData.push({
                            name: 'room[]',
                            value: date
                        });
                    });
                })

                // const guest_type = $('.guest_type').val();

                // if (guest_type === "") {
                //     formData.push({
                //         name: 'guest_type',
                //         value: 1
                //     })
                // } else {
                //     formData.push({
                //         name: 'guest_type',
                //         value: 0
                //     })
                // }
                formData.push({
                    name: 'is_method',
                    value: 'receptionist',
                });
                // formData.push({
                //     name: '',
                //     value: 'receptionist',
                // });
                // const data = validator(formData);
                // return;
                let shouldSubmit = true;
                formData.some(function(item) {
                    if (item.name === 'room[]') {
                        const data = item.value;


                        const parts = data.split('-');
                        const timeCheckIn = parts[2];
                        const timeCheckOut = parts[3];


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
    </script>
@endpush

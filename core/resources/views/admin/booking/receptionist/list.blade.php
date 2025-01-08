@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-2 ">
            <label>@lang('Ngày nhận phòng - Ngày trả phòng')</label>
            <div class="d-flex">
                <input autocomplete="off" class="bookingDatePicker form-control bg--white" name="dates"
                    placeholder="@lang('Chọn ngày')" required type="text">

            </div>
        </div>
        <div class="col-lg-2">
            <label>@lang('Tên khách hàng')</label>
            <div class="d-flex">
                <input type="text" id="search-custom" class="input-group form-control " placeholder="Tên khách hàng">

            </div>
        </div>
        <div class="col-lg-2">
            <label>@lang('Mã đặt phòng')</label>
            <div class="d-flex">
                <input type="text" id="search-code-room" class="input-group form-control " placeholder="Mã đặt phòng">

            </div>
        </div>
        <div class="col-lg-2">
            <label>@lang('Hạng phòng')</label>
            <div class="d-flex">
                <select name="" id="search-room-type" class="form-group form-control">
                    <option value="">Chọn hạng phòng</option>
                    @foreach ($roomType as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>

            </div>
        </div>
        <div class="col-lg-2 d-flex flex-column h-45 m-top-10 search-main">

            <label>&nbsp;</label>
            <div class="d-flex search-main">
                <button class="btn-primary-dates" id="search-rooms-dates" style="width: 100px">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="20" viewBox="0 0 20 20">
                        <path fill="currentColor"
                            d="M8.195 0c4.527 0 8.196 3.62 8.196 8.084a7.989 7.989 0 0 1-1.977 5.267l5.388 5.473a.686.686 0 0 1-.015.98a.71.71 0 0 1-.993-.014l-5.383-5.47a8.23 8.23 0 0 1-5.216 1.849C3.67 16.169 0 12.549 0 8.084C0 3.62 3.67 0 8.195 0Zm0 1.386c-3.75 0-6.79 2.999-6.79 6.698c0 3.7 3.04 6.699 6.79 6.699s6.791-3 6.791-6.699c0-3.7-3.04-6.698-6.79-6.698Z" />
                    </svg>
                    Tìm kiếm</button>
                <a href="{{ url()->current() }}" class="btn btn-warning" id="search-rooms-dates"
                    style="width: 100px;color: #ffffff">

                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="20" viewBox="0 0 17 17">
                        <path fill="currentColor" fill-rule="evenodd"
                            d="M.04 7.379C.115 3.257 3.392-.033 7.344.042c3.917.073 6.854 2.929 6.83 7.003h1.545a.572.572 0 0 1 0 .799L13.873 9.86a.546.546 0 0 1-.785 0l-1.725-2.016a.57.57 0 0 1 0-.799h1.422c.023-3.338-2.259-5.584-5.467-5.644c-3.233-.062-5.912 2.63-5.975 6.002c-.062 3.322 2.445 6.077 5.613 6.216v1.361C3.059 14.842-.035 11.46.04 7.379z" />
                    </svg>
                    Tải lại</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            @include('admin.booking.partials.system-1')
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="header-row">
                <h3>{{ $Title }}</h3>
                <span class="header-line"></span>
            </div>
        </div>
    </div>
    <div class="row">
        {{-- <div id="empty-rooms" class="row">
            @include('admin.booking.partials.empty_rooms', ['dataRooms' => $dataRooms ?? []])
        </div> --}}
    </div>
    <div class="row" id="roomListContainer">

        @include('admin.booking.partials.empty_rooms', ['dataRooms' => $emptyRooms ?? []])
        {{-- 123 --}}
        {{-- @include('admin.booking.partials.booked_rooms', ['bookings' => $bookings ?? []]) --}}

    </div>
    <!-- modal dặt hàng  -->
    @include('admin.booking.partials.booking_rooms')

    @include('admin.booking.partials.modal_extraChargeModal')
    {{-- NHẬN PHÒNG MUỘN  --}}
    <div class="modal fade" id="myModal-booking-status" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel-booking" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 873px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel-booking">
                        Chi tiết phòng <span class="booking-no"></span>
                    </h5>
                    <div class="main-icon-booked">
                        <div class="dropdown">
                            <svg class="option-booked" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 24 24">
                                <g fill="none" stroke="currentColor" stroke-width="1.5">
                                    <circle cx="5" cy="12" r="2" />
                                    <circle cx="12" cy="12" r="2" />
                                    <circle cx="19" cy="12" r="2" />
                                </g>
                            </svg>
                            <div class="dropdown-menu dropdown-main-booked">
                                <a class="dropdown-item" href="#"> <a href="#" class=" add_premium_service">
                                        <i class="las la-plus-circle"></i> Thêm dịch vụ cao cấp</a></a>
                                <a class="dropdown-item" href="#"> <a href="javascript:void(0)"
                                        class="  add_product_room"> <i class="las la-plus-circle"></i> Thêm sản
                                        phẩm</a></a>
                                {{-- <a class="dropdown-item" href="#">Option 3</a> --}}
                            </div>
                        </div>
                        <svg class="option-cancel-room" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                            viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                        </svg>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                </div>
                <div class="modal-body">

                    <!-- Row: Labels -->
                    <div class="room-card-checkout">
                        {{-- <div class="room-header-checkout">
                                    <h4>Phòng 01 giường đơn</h4>
                                    <span class="status-label-checkout">Đã đặt trước</span>


                                </div> --}}

                        <div class="room-details-checkout">

                        </div>

                    </div>
                    {{-- danh sách phòng đặt  --}}
                    {{-- @include('admin.booking.partials.table-booking') --}}

                    {{-- danh sách dịch vụ  --}}
                    {{-- <div class="row" id="product_room">
                        @include('admin.booking.partials.system-3')
                    </div> --}}

                    {{-- danh sách sản phẩm  --}}
                    {{-- <div class="row mt-2">
                        @include('admin.booking.partials.system-4')
                    </div> --}}

                    <div class="d-flex justify-content-between">
                        <div class="d-flex flex-column">
                            <span class="d-flex  flex-column group-booked-room">

                            </span>
                            <span class="d-flex note-booked-room">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 14 14">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M.5 13.5h11m-5-3.5l-3 .54L4 7.5L10.73.79a1 1 0 0 1 1.42 0l1.06 1.06a1 1 0 0 1 0 1.42Z" />
                                </svg>
                                <p class="note-booking">Nhập ghi chú...</p>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <div class="info-room">

                            </div>
                            <div class="d-flex align-items-center justify-content-around">
                                <button type="submit" class="w-10 sua_dat_phong">Sửa đặt phòng</button>
                                <div class="chose-btn-room">

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Xác Nhận -->
    <!-- Modal -->
    @include('admin.booking.partials.modal-check-in-room')
    @include('admin.booking.partials.modal-check-out-room')
    @include('admin.booking.partials.modal-check-in')
    @include('admin.booking.partials.modal-check-out')
    @include('admin.booking.partials.cancel-room')
    @include('admin.booking.partials.modal-note-booked')
    @include('admin.booking.partials.confirm-room')
    {{-- thêm dịch vụ  --}}
    @include('admin.booking.partials.system-2')
    {{-- thêm sản phẩm  --}}
    {{-- @include('admin.booking.partials.system-3') --}}

    @include('admin.booking.partials.system-5')

    @include('admin.booking.partials.clean_modal')

    @include('admin.booking.partials.table-service')

    @include('admin.booking.partials.modal-cancel-booking')
    </div>
    <div id="loading" style="display: none;">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24">
            <path fill="none" stroke="currentColor" stroke-dasharray="15" stroke-dashoffset="15"
                stroke-linecap="round" stroke-width="2" d="M12 3C16.9706 3 21 7.02944 21 12">
                <animate fill="freeze" attributeName="stroke-dashoffset" dur="0.3s" values="15;0" />
                <animateTransform attributeName="transform" dur="1.5s" repeatCount="indefinite" type="rotate"
                    values="0 12 12;360 12 12" />
            </path>
        </svg>
    </div>
@endsection
<?php $currentTime = date('Y-m-d H:i:s'); ?>
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/global/css/receptionist.css') }}">
@endpush
<script src="https://cdn.jsdelivr.net/npm/tesseract.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@push('script')
    <script>
        $(document).ready(function() {


            // định dạng tiền input
            function formatNumber(input) {
                var value = input.value;
                // Loại bỏ tất cả các ký tự không phải số và dấu phân cách
                var numericValue = value.replace(/[^0-9,]/g, '');
                // Loại bỏ tất cả dấu phân cách thừa (nếu có)
                var parts = numericValue.split(',');
                var integerPart = parts[0].replace(/\./g, ''); // Loại bỏ tất cả dấu phân cách ngàn
                // Định dạng số tiền theo định dạng tiền tệ của Việt Nam
                var formattedValue = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                // Nếu có phần thập phân, thêm vào sau số nguyên
                if (parts.length > 1) {
                    formattedValue += ',' + parts[1];
                }
                input.value = formattedValue;
            }

            $('.increase').on('click', function() {
                let target = $(this).data('target');
                let inputField = $('#' + target);
                let value = parseInt(inputField.val(), 10) || 0;
                inputField.val(value + 1);
            });

            // Xử lý giảm số lượng
            $('.reduce').on('click', function() {
                let target = $(this).data('target');
                let inputField = $('#' + target);
                let value = parseInt(inputField.val(), 10) || 0;
                inputField.val(Math.max(value - 1, 0)); // Không cho giảm xuống dưới 0
            });


            $(document).on('click', '.option-booked', function(e) {
                e.stopPropagation(); // Ngăn việc click lan sang các thành phần khác
                const dropdownMenu = $(this).siblings('.dropdown-menu');

                // Đóng tất cả dropdown đang mở
                $('.dropdown-menu').not(dropdownMenu).hide();

                // Hiển thị hoặc ẩn dropdown tương ứng
                dropdownMenu.toggle();
            });

            // Ẩn dropdown khi click bên ngoài
            $(document).on('click', function() {
                $('.dropdown-menu').hide();
            });

            const baseUrl = window.location.origin + '/';

            let data_service = [];
            let data_product = [];
            //  const today = new Date();
            // const yyyy = today.getFullYear();
            // const mm = String(today.getMonth() + 1).padStart(2, '0');
            // const dd = String(today.getDate()).padStart(2, '0');
            // const hourss = String(today.getHours()).padStart(2, '0'); // Giờ
            // const minutess = String(today.getMinutes()).padStart(2, '0'); // Phút
            // // Định dạng ngày: yyyy-MM-dd
            // // Định dạng thời gian: HH:mm
            // const formattedDate = `${yyyy}-${mm}-${dd}`;
            // const formattedTime = `${hourss}:${minutess}`;
            // // Gán giá trị vào input
            // $('#date-book-room').val(formattedDate);

            // $('#time-book-room').val(formattedTime);

            const selectCancelRoom = $('.select-cancel-room');
            const textareaCancelRoom = $('textarea[name="note-cancel-room"]');

            // Ẩn textarea khi chọn giá trị "Khách không đến"
            selectCancelRoom.change(function() {
                if ($(this).val() === '1') {
                    textareaCancelRoom.hide();
                } else {
                    textareaCancelRoom.show();
                }
            });

            $('.option-cancel-room').on('click', function() {
                $('#cancelroom').modal('show');
            })



            // $('.btn-check-in').on('click', function() {
            //     alert('Check');
            // });

            $('#all-check-box').change(function() {
                $('#list-room-booking input[type="checkbox"]').prop('checked', $(this).prop('checked'));
               
            });

            $('#all-check-boxs').change(function() {
                // Thay đổi trạng thái của tất cả các checkbox trong danh sách
                $('#list-room-booked input[type="checkbox"]').prop('checked', $(this).prop('checked'));
                
                // Kiểm tra nếu tất cả các checkbox đã được chọn
                if ($(this).prop('checked')) {
                    alert('Tất cả các phòng đã được chọn!');
                }
            });


            $(document).on('click', '.modal-checkout', function() {
                var booking_id = $('.booking_number').text();
                var nameroom = $('.name-room').text();

                $('#loading').show();
                $.ajax({
                    url: '{{ route('admin.booking.listRoomBooked') }}',
                    type: 'POST',
                    data: {
                        booking_id: booking_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            const todays = new Date();
                            const yyyys = todays.getFullYear();
                            const mms = String(todays.getMonth() + 1).padStart(2, '0');
                            const dds = String(todays.getDate()).padStart(2, '0');
                            const hoursss = String(todays.getHours()).padStart(2, '0'); // Giờ
                            const minutesss = String(todays.getMinutes()).padStart(2,
                                '0'); // Phút

                            const formattedDates = `${yyyys}-${mms}-${dds}`;
                            const formattedTimes = `${hoursss}:${minutesss}`;
                            $('#list-room-booked').empty();
                            let rowlistRoom = '';
                            let rowbtnCheckOut = '';
                            // console.log(response.data.length);
                            $('.btn-modalCheckOut').empty();
                            if(response.data.length === 1){
                                rowbtnCheckOut += `
                                 <button type="button" class=" nhan-phong modal-check-out-room" data-bs-toggle="modal" data-bs-target="#checkOutRoom">Trả phòng và thanh toán</button>
                                `;
                            }else{
                                rowbtnCheckOut += `
                                    <button type="button" class=" nhan-phong check-out-room-detail" >Trả phòng</button>
                                    <button type="button" class=" nhan-phong modal-check-out-room" data-bs-toggle="modal" data-bs-target="#checkOutRoom">Trả phòng và thanh toán</button>
                                `;
                            }

                            response.data.forEach(item => {
                                const dateTimeCurrent = item.checkins[0]['check_in_at'];
                                const parts = dateTimeCurrent.split(' ');
                                const formattedDatesCurrent = parts[0];
                                const formattedTimesCurrent = parts[1];

                                // item.booked[0]['id'] id của bảng booked_rooms
                                rowlistRoom += `
                                        <tr data-id="${item.checkins[0]['id']}" data-booking-id="${item.checkins[0]['booking_id']}">
                                            <td ><input type="checkbox" ${nameroom === item.room_number ? "checked" :""}></td>
                                            <td>${item.room_type['name']}</td>
                                            <td>${item.room_number}</td>
                                            <td>
                                                <div class="d-flex" style="gap: 10px;position: relative;left: 60px;margin-top:5px">
                                                    <input type="date" name="checkInDate" class="form-control date-book-room" style="width: 165px;height: 35px;" value="${formattedDatesCurrent}" disabled>
                                                    <input type="time" name="checkInTime" class="form-control time-book-room" style="width: 135px;height: 35px;" value="${formattedTimesCurrent.slice(0, 5)}" disabled>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex" style="gap: 10px; position: relative; left: 50px; margin-top:5px">
                                                    <input type="date" name="checkInDate" class="form-control date-book-room" id="date-book-room" style="width: 165px;height: 35px;" value="${formattedDates}">
                                                    <input type="time" name="checkInTime" class="form-control time-book-room" id="time-book-room" style="width: 135px;height: 35px;" value="${formattedTimes}">
                                                </div>
                                            </td>
                                        </tr>
                                    `;
                            })

                            $('#list-room-booked').append(rowlistRoom);
                            $('.btn-modalCheckOut').append(rowbtnCheckOut);
                            $('#loading').hide();
                            $('#modalCheckOut').modal('show');

                        }

                        // notify('success', response.success);
                        // $('.note-booking').html(note);
                        // $('#noteModal').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        $('#loading').hide();
                        // alert('Có lỗi xảy ra khi lưu ghi chú!');
                    }
                });
            });

            $(document).on('click', '.check-out-room-detail', function() {
          
                var selectedRooms = [];
                if ($('#list-room-booking input[type="checkbox"]:checked').length === 0) {

                    $('#list-room-booked input[type="checkbox"]:checked').each(function() {
                        var roomId = $(this).closest('tr').data('id');
                        selectedRooms.push(roomId);
                    });
                } else {

                    $('#list-room-booking input[type="checkbox"]:checked').each(function() {
                        var roomId = $(this).closest('tr').data('id');
                        selectedRooms.push(roomId);
                    });
                }
                console.log(selectedRooms);
                

            });

            $(document).on('click', '.modal-check-out-room', function() {
          
                var selectedRooms = [];

                var checkInDate = $('#list-room-booked input[type="date"]:last').val();
                var checkInTime = $('#list-room-booked input[type="time"]:last').val();
                // Chuyển đổi ngày sang định dạng dd/mm/yyyy
                var formattedDate = checkInDate.split('-').reverse().join('/');

                // Định dạng giờ và phút
                var formattedTime = checkInTime;

                // Kết hợp ngày và giờ thành định dạng cuối cùng
                var formattedDateTime = formattedDate + ' ' + formattedTime;



                if ($('#list-room-booking input[type="checkbox"]:checked').length === 0) {

                    $('#list-room-booked input[type="checkbox"]:checked').each(function() {
                        var roomId = $(this).closest('tr').data('id');
                        selectedRooms.push(roomId);
                    });
                } else {

                    $('#list-room-booking input[type="checkbox"]:checked').each(function() {
                        var roomId = $(this).closest('tr').data('id');
                        selectedRooms.push(roomId);
                    });
                }

                $.ajax({
                    url: '{{ route('admin.booking.getRoomCheckIn') }}',
                    type: 'POST',
                    data: {
                        selectedRooms: selectedRooms,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#loading').show();
                        console.log(response);
                        
                        if (response.status === 'success') {
                          
                            
                            var currentDate = new Date();
                            var formattedDate = currentDate.toLocaleDateString('vi-VN') + ' ' +
                                currentDate.toLocaleTimeString('vi-VN');
                            // $('#checkInRoom').modal('hide');
                            $('.main-room-booked').empty();
                         
                            
                            let row_booking = '';
                            row_booking = `
                                <div class="modal-body">
                                    <!-- Thông tin chung -->
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <strong>Khách hàng</strong><br>${response.users['username']} - ${response.users['mobile'] }
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Khách lưu trú</strong><br>${response.booking['total_people']  } người lớn
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Phòng nhận</strong><br> ${ response.rooms.length } phòng (
                                            ${ response.roomNumbers }
                                            )
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-2">
                                            <strong>Nhận phòng</strong><br>${response.roomCheckIn[0]['check_in_at']}
                                        </div>  
                                        <div class="col-md-6">
                                            <strong>Trả phòng</strong><br>${formattedDateTime}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Thời gian sử dụng</strong><br>${formattedDateTime}
                                        </div>
                                    </div>
                                
                                    <!-- Tab Menu -->
                                    <div class="row">
                                        <div class=" col-md-6 d-flex align-items-end">
                                            <label for="note" class="form-label"><strong style="white-space: nowrap;">Ghi chú</strong></label>
                                            <input type="text" data-id="${response.booking['id']}" value="${response.booking['note'] === null ? "" : response.booking['note'] }" class="form-control custom-input-note" id="note" placeholder="Nhập ghi chú đặt phòng">
                                        </div>
                                    </div>

                                    <div class="tab-content" id="myTabContent">
                                    
                                        
                                    
                                    </div>

                                    <!-- Thanh toán -->
                                    <div class="payment-box mt-2">
                                        <div class=" align-items-center">
                                            <div class="d-flex justify-content-between">
                                                <span><strong>Khách cần trả</strong></span>
                                                <span class="fw-bold">${formatCurrency(response.totalFare)}</span>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between">
                                                <span>Khách đã trả</span>
                                                <span class="fw-bold">${formatCurrency(response.booking['paid_amount'])}</span>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between">
                                                <span><strong>Còn cần trả</strong></span>
                                                <span class="fw-bold">${formatCurrency(response.due)}</span>
                                            </div>

                                            <div class=" d-flex justify-content-between align-items-center mt-1">
                                                <span>Khách thanh toán</span>
                                                <div class="d-flex align-items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"  class="cardRoom" width="20" height="20" viewBox="0 0 256 256"><path fill="currentColor" d="M224 48H32a16 16 0 0 0-16 16v128a16 16 0 0 0 16 16h192a16 16 0 0 0 16-16V64a16 16 0 0 0-16-16Zm-88 128h-16a8 8 0 0 1 0-16h16a8 8 0 0 1 0 16Zm64 0h-32a8 8 0 0 1 0-16h32a8 8 0 0 1 0 16ZM32 88V64h192v24Z"/></svg>
                                                <input type="text" class="  input-css-main" id="number-input" placeholder="0" oninput="this.value = this.value.slice(0, 16)">
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <input type="text" data-booking-id="${response.roomCheckIn[0]['booking_id']}" 
                                                            data-room-id="${response.roomCheckIn[0]['room_id']}" 
                                                            data-room-type-id="${response.roomCheckIn[0]['room_type_id']}"
                                                            data-array=""
                                                                class="form-control data-booked" hidden>
                                </div>
                            `
                            $('.main-room-booked').append(row_booking);


                            $('#number-input').on('input', function() {
                                formatNumber(this);
                            });

                            $('.custom-input-note').on('blur', function() {
                                const note = $(this).val();
                                const noteId = $(this).data('id');
                                noteUpdate(note, noteId)
                            });

                            $('#loading').hide();
                            $('#checkOutRoom').modal('show');
                        }

                    },
                    error: function(xhr, status, error) {
                        $('#loading').hide();
                        alert('Có lỗi xảy ra khi lưu ghi chú!');
                    }
                });
            });
            
            $('.modal-check-in-room').on('click', function() {
                var selectedRooms = [];

                if ($('#list-room-booking input[type="checkbox"]:checked').length === 0) {
                    $('#list-room-booked input[type="checkbox"]:checked').each(function() {
                        var roomId = $(this).closest('tr').data('id');
                        selectedRooms.push(roomId);
                    });
                } else {

                    $('#list-room-booking input[type="checkbox"]:checked').each(function() {
                        var roomId = $(this).closest('tr').data('id');
                        selectedRooms.push(roomId);
                    });
                }

                $.ajax({
                    url: '{{ route('admin.booking.getRoomCheckIn') }}',
                    type: 'POST',
                    data: {
                        selectedRooms: selectedRooms,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#loading').show();
                        if (response.status === 'success') {
                            var currentDate = new Date();
                            var formattedDate = currentDate.toLocaleDateString('vi-VN') + ' ' +
                                currentDate.toLocaleTimeString('vi-VN');
                            // $('#checkInRoom').modal('hide');
                            $('.main-room-booking').empty();
                          

                            let row_booking = '';
                            row_booking = `
                                    <div class="modal-body">
                                        <!-- Thông tin chung -->
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <strong>Khách hàng</strong><br>${response.users['username']} - ${response.users['mobile'] }
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Khách lưu trú</strong><br>${response.booking['total_people']  } người lớn
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Phòng nhận</strong><br> ${ response.rooms.length } phòng (
                                                ${ response.roomNumbers }
                                                )
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <strong>Nhận phòng</strong><br>${formattedDate}
                                            </div>
                                        </div>

                                        <!-- Tab Menu -->
                                        

                                        <div class="row">
                                            <div class=" col-md-6 d-flex align-items-end">
                                                <label for="note" class="form-label"><strong style="white-space: nowrap;">Ghi chú</strong></label>
                                                <input type="text" data-id="${response.booking['id']}" value="${response.booking['note'] === null ? "" : response.booking['note'] }" class="form-control custom-input-note" id="note" placeholder="Nhập ghi chú đặt phòng">
                                            </div>
                                        </div>

                                        <div class="tab-content" id="myTabContent">
                                        
                                            
                                          
                                        </div>

                                        <!-- Thanh toán -->
                                        <div class="payment-box mt-2">
                                            <div class=" align-items-center">
                                                <div class="d-flex justify-content-between">
                                                    <span><strong>Khách cần trả</strong></span>
                                                    <span class="fw-bold">${formatCurrency(response.totalFare)}</span>
                                                </div>
                                                
                                                <div class="d-flex justify-content-between">
                                                    <span>Khách đã trả</span>
                                                    <span class="fw-bold">${formatCurrency(response.booking['paid_amount'])}</span>
                                                </div>
                                                
                                                <div class="d-flex justify-content-between">
                                                    <span><strong>Còn cần trả</strong></span>
                                                    <span class="fw-bold">${formatCurrency(response.due)}</span>
                                                </div>

                                                <div class=" d-flex justify-content-between align-items-center mt-1">
                                                    <span>Khách thanh toán</span>
                                                    <div class="d-flex align-items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg"  class="cardRoom" width="20" height="20" viewBox="0 0 256 256"><path fill="currentColor" d="M224 48H32a16 16 0 0 0-16 16v128a16 16 0 0 0 16 16h192a16 16 0 0 0 16-16V64a16 16 0 0 0-16-16Zm-88 128h-16a8 8 0 0 1 0-16h16a8 8 0 0 1 0 16Zm64 0h-32a8 8 0 0 1 0-16h32a8 8 0 0 1 0 16ZM32 88V64h192v24Z"/></svg>
                                                    <input type="text" class="  input-css-main" id="number-input" placeholder="0" oninput="this.value = this.value.slice(0, 16)">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="text" data-booking-id="${response.roomCheckIn[0]['booking_id']}" 
                                                                data-room-id="${response.roomCheckIn[0]['room_id']}" 
                                                                data-room-type-id="${response.roomCheckIn[0]['room_type_id']}"  
                                                              id="arrCheckInInput"  data-arr="${JSON.stringify(response.ArrCheckIn)}"  class="form-control data-booked" hidden>
                                    </div>
                                    `
                            $('.main-room-booking').append(row_booking);


                            $('#number-input').on('input', function() {
                                formatNumber(this);
                            });

                            $('.custom-input-note').on('blur', function() {
                                const note = $(this).val();
                                const noteId = $(this).data('id');
                                noteUpdate(note, noteId)
                            });

                            $('#loading').hide();
                            $('#checkInRoom').modal('show');
                        }

                    },
                    error: function(xhr, status, error) {
                        $('#loading').hide();
                        alert('Có lỗi xảy ra khi lưu ghi chú!');
                    }
                });

            });
            $(document).on('click', '.modal-checkin', function() {
                var booking_id = $('.booking_number').text();
                var nameroom = $('.name-room').text();

                $('#loading').show();
                $.ajax({
                    url: '{{ route('admin.booking.listRoomBooking') }}',
                    type: 'POST',
                    data: {
                        booking_id: booking_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            const todays = new Date();
                            const yyyys = todays.getFullYear();
                            const mms = String(todays.getMonth() + 1).padStart(2, '0');
                            const dds = String(todays.getDate()).padStart(2, '0');
                            const hoursss = String(todays.getHours()).padStart(2, '0'); // Giờ
                            const minutesss = String(todays.getMinutes()).padStart(2,
                                '0'); // Phút
                            const formattedDates = `${yyyys}-${mms}-${dds}`;
                            const formattedTimes = `${hoursss}:${minutesss}`;
                            $('#list-room-booking').empty();
                            let rowlistRoom = '';
                            response.data.forEach(item => {
                                rowlistRoom += `
                                        <tr data-id="${item.booked[0]['id']}">
                                            <td ><input type="checkbox" ${nameroom === item.room_number ? "checked" :""}></td>
                                            <td>${item.room_type['name']}</td>
                                            <td>${item.room_number}</td>
                                            <td>${item.is_clean === 1 ? "Sạch" : "Chưa dọn"}</td>
                                            <td>
                                                <div class="d-flex" style="gap: 10px;position: relative;left: 60px;margin-top:5px">
                                                    <input type="date" name="checkInDate" class="form-control date-book-room" style="width: 165px;height: 35px;" value="${formattedDates}">
                                                    <input type="time" name="checkInTime" class="form-control time-book-room" style="width: 135px;height: 35px;" value="${formattedTimes}">
                                                </div>
                                            </td>
                                        </tr>
                                    `;
                            })

                            $('#list-room-booking').append(rowlistRoom);

                            $('#loading').hide();
                            $('#modalCheckIn').modal('show');

                        }

                        // notify('success', response.success);
                        // $('.note-booking').html(note);
                        // $('#noteModal').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        $('#loading').hide();
                        // alert('Có lỗi xảy ra khi lưu ghi chú!');
                    }
                });

            })

            // xóa phòng vừa add vào  roomNumber
            $('#myModal-booking').on('hidden.bs.modal', function() {
                if (!$('#myModal-booking').hasClass('show')) {
                    $('#roomNumber').empty(); // Xóa các phòng vừa thêm vào
                    $('#list-booking tr[data-room-id]').not('#specific-row').remove();
                }
            });

            $('#addRoomModal').on('hidden.bs.modal', function() {
                if (!$('#addRoomModal').hasClass('show')) {
                    $('#show-room tr').remove();
                    document.body.classList.remove('modal-open');
                    // xóa các phòng thêm vào damh sách booking
                }
            });

            $('.note-booked-room').on('click', function() {
                $('#noteModal').modal('show');
                //  console.log($('.note-booking').html());

                $('#note-input').val('');
                $('#note-input').val($('.note-booking').text());
            });

            $('.add-room-booking').on('click', function() {

                const roomIds = [];
                $('#list-booking tr').each(function() {
                    const roomId = $(this).attr('data-room-id');

                    if (roomId) {
                        roomIds.push(roomId);
                    }
                });
                showRoom(roomIds)
                $('#addRoomModal').modal('show'); // Hiển thị modal
                $('#addRoomModal').on('shown.bs.modal', function() {
                    document.body.classList.add('modal-open');
                });

            });

            $(document).on('click', '.add-book-room', function() {
                var roomId = $(this).data('id');
                var roomTypeId = $(this).data('room_type_id');
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

                            var tr = `
                                    <tr data-room-id="${response.room['id']}"  data-room-type-id="${response.room_type['id']}">
                                        <td>
                                            <p id="book_name" class="book_name">${response.room_type['name']}</p>
                                        </td>
                                        <td>
                                            <select name="" id="roomNumber">
                                                 <option value="${response.room_type['id']}">${response.room['room_number']}</option>
                                            </select>
                                        </td>
                                        <td style="display: flex; justify-content: center">
                                            <select id="bookingType" class="form-select" name="optionRoom" style="width: 110px;">
                                                 <option value="ngay">Ngày</option> 
                                                 <option value="gio">Giờ</option>
                                              
                                                
                                            </select>
                                        </td>
                                         <td>
                                            <div class="d-flex align-items-center justify-content-start" style="gap: 10px">
                                                <input type="date" name="checkInDate" class="form-control date-book-room" style="width: 140px;" value="${formattedDates}">

                                                <input type="time" name="checkInTime" class="form-control time-book-room" style="width: 135px;" value="${formattedTimes}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start" style="gap: 10px">
                                                <input type="date" name="checkOutDate" class="form-control date-book-room" style="width: 140px;" value="${nextDay}">

                                                <input type="time" name="checkOutTime" class="form-control time-book-room" style="width: 135px;" value="${formattedTimes}">
                                                <svg  class="icon-delete-room" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                            </div>
                                        </td>




                                    </tr>
                                `;

                            $('#loading').hide();
                            tbody.append(tr);
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

            // Sử dụng delegate event để đảm bảo sự kiện click hoạt động với các phần tử động
            $(document).on('click', '.icon-delete-room', function() {
                // Lấy data-room-id và data-room-type-id của dòng tr chứa icon
                const row = $(this).closest('tr'); // Lấy thẻ <tr> chứa icon
                const roomId = row.data('room-id'); // Lấy data-room-id
                const roomTypeId = row.data('room-type-id'); // Lấy data-room-type-id


                $('#confirmDeleteModal').modal('show');

                // Lưu thông tin phòng để xóa vào modal
                $('#confirmDeleteButton').off('click').on('click', function() {
                    // Xử lý xóa
                    row.remove(); // Xóa dòng tr khỏi bảng
                    $('#confirmDeleteModal').modal('hide'); // Ẩn modal
                    // console.log('Xóa phòng với roomId:', roomId, 'và roomTypeId:',
                    //     roomTypeId); // In ra thông tin xóa
                });
            });


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
                                        <td> ${ formatCurrency(item.room_price[0]['hourly_price']) } </td>
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

            // $('#model').on('change', function(event) {
            //     var model = $(this).val();
            //     var parent = $(this).closest('#myModal-booking');
            //     var updatedHours = parent.attr('data-hours');
            //     var updatedDay = parent.attr('data-day');
            //     var updatedNight = parent.attr('data-night');
            //     $('#bookingType').html('');
            //     if (model == 1 || model == null || model == '') {
            //         $('.inputTime').text(`1:00`);
            //         $('#customer-price-booking, #input-price-booking').val(parseInt(updatedHours, 10));
            //         $('#bookingType').append(`
        //             <option value="gio">Giờ</option>
        //             <option value="ngay">Ngày</option>
        //             <option value="dem">Đêm</option>
        //         `);
            //     } else {
            //         $('.inputTime').text(`24:00`);
            //         $('#customer-price-booking, #input-price-booking').val(parseInt(updatedDay, 10));
            //         $('#bookingType').append(`
        //             <option value="ngay">Ngày</option>
        //             <option value="dem">Đêm</option>
        //         `);

            //         var now = new Date();

            //         // Cộng thêm 1 ngày
            //         now.setDate(now.getDate() + 1);

            //         var year = now.getFullYear();
            //         var month = (now.getMonth() + 1).toString().padStart(2, '0');
            //         var day = now.getDate().toString().padStart(2, '0');
            //         var hours = now.getHours().toString().padStart(2, '0');
            //         var minutes = now.getMinutes().toString().padStart(2, '0');

            //         // Tạo chuỗi datetime-local
            //         var nextDayDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
            //         var checkOutTime = document.getElementById('checkOutTime');
            //         checkOutTime.value = nextDayDateTime;



            //     }
            // })

            $('input[name="dates"]').daterangepicker();
            // choose option  rooms
            var dirtyCount = $('.content-booking.mt-2.room-booking-status-dirty').length;
            var incomingCount = $('.content-booking.mt-2.room-booking-status-incoming').length;
            var occupiedCount = $('.content-booking.mt-2.room-booking-status-occupied').length;
            var lateCheckinCount = $('.content-booking.mt-2.room-booking-status-late-checkin').length;
            var checkOutCount = $('.content-booking.mt-2.room-booking-status-check-out').length;

            $('.status-available-line-count').text('Đang trống (' + dirtyCount + ')');
            $('.status-incoming-line-count').text('Sắp nhận (' + incomingCount + ')');
            $('.status-occupied-line-count').text('Đang sử dụng (' + occupiedCount + ')');
            $('.status-checkout-line-count').text('Nhận phòng muộn (' + lateCheckinCount + ')');
            $('.status-overdue-line-count').text('Quá giờ trả (' + checkOutCount + ')');

            $('.status-button').click(function() {
                $(this).toggleClass('active');

                var selectedStatuses = [];

                $('.status-button.active').each(function() {
                    selectedStatuses.push($(this).data('status'));
                });

                if (selectedStatuses.length === 0) {
                    $('.room-card').show();
                    $('.main-room-card').show();
                } else {
                    $('.room-card').hide();
                    selectedStatuses.forEach(function(status) {
                        $('.room-card').filter('.status-' + status).show();
                    });

                    $('.main-room-card').hide();
                    selectedStatuses.forEach(function(status) {
                        $('.main-room-card').filter('.card-status-' + status).show();
                    });
                }

            });

            function fetchlistserviceandproduct(id) {
                let services = '';
                let products = '';
                $('#list-service').empty();
                $('#list-product').empty();
                $('#services').empty();
                $('#products').empty();
                let url = `{{ route('admin.booking.serviceproduct', ['id' => ':id']) }}`.replace(':id', id);
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        response.service.forEach(function(item, index) {
                            // console.log(response.service);
                            const usedService = response.used_services.find(service => service
                                .premium_service_id === item.id);
                            const qty = usedService ? usedService.qty : 0;
                            services += `
                                <div style="display: flex" class='mb-2 item_service' data-id="${item.id}" data-name="${item.name}" data-price="${item.cost}">
                                    <div class='mb-2'>
                                        <p style="margin:0px">${item.name}</p>
                                        <p style="margin:0px">${formatCurrency(item.cost)}</p>
                                    </div>
                                </div>
                            `;
                            // listservice += `
                        //     <div class="row align-items-center mb-3">
                        //         <!-- Cột cho input tên dịch vụ -->
                        //         <div class="col-md-6 col-sm-12 mb-2 mb-md-0">
                        //             <div style="display: flex" >

                        //                <div>
                        //                 <p style="margin:0px">${item.name}</p>
                        //                 <p style="margin:0px">${formatCurrency(item.cost)}</p>
                        //                 </div>
                        //             </div>
                        //             <input type="hidden" class="form-control" placeholder="Tên dịch vụ" value="${item.name}" readonly>
                        //             <input type="hidden" class="form-control" name="services[]" placeholder="Tên sản phẩm" value="${item.id}">
                        //         </div>

                        //         <!-- Cột cho input số lượng -->
                        //         <div class="col-md-6 col-sm-12">
                        //             <div class="input-group quantity_new">
                        //                 <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(this, -1)">-</button>
                        //                 <input type="number" data-service_id="${item.id}" class="form-control text-center" name="qty[]" value="${qty}" min="0">
                        //                 <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(this, 1)">+</button>
                        //             </div>
                        //         </div>
                        //     </div>
                        // `;
                        });


                        $('#services').append(services);

                        response.product.forEach(function(item, index) {
                            // Tìm kiếm thông tin về dịch vụ đã sử dụng
                            const usedService = response.used_products.find(service => service
                                .product_id === item.id);
                            const qty = usedService ? usedService.qty : 0;
                            products += `
                                        <div style="display: flex" class='mb-2 item_product col-md-6' data-id="${item.id}" data-name="${item.name}" data-price="${item.import_price}" data-image="${baseUrl + 'storage/' + item.image_path}">
                                            <img src="${baseUrl + 'storage/' + item.image_path}" alt="${item.name}" class="img-fluid" style="width:50px;height:50px; margin-right: 20px">
                                           <div class='mb-2' >
                                                <p style="margin:0px">${item.name}</p>
                                                <p style="margin:0px">${formatCurrency(item.import_price)}</p>
                                            </div>
                                        </div>
                            `;
                            // listproduct += `
                        //     <div class="row align-items-center mb-3">
                        //         <!-- Cột cho input tên dịch vụ -->
                        //         <div class="col-md-6 col-sm-12 mb-2 mb-md-0">
                        //             <div style="display: flex" >
                        //                 <img src="${baseUrl + 'storage/' + item.image_path}" alt="${item.name}" class="img-fluid" style="width:50px;height:50px; margin-right: 20px">
                        //                <div>
                        //                     <p style="margin:0px">${item.name}</p>
                        //                     <p style="margin:0px">${formatCurrency(item.import_price)}</p>
                        //                 </div>
                        //             </div>
                        //             <input type="hidden" class="form-control" name="product[]" placeholder="Tên sản phẩm" value="${item.id}">
                        //             <input type="hidden" class="form-control" placeholder="Tên dịch vụ" value="${item.name}" >
                        //         </div>

                        //         <!-- Cột cho input số lượng -->
                        //         <div class="col-md-6 col-sm-12">
                        //             <div class="input-group quantity_new">
                        //                 <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(this, -1)">-</button>
                        //                 <input type="number" data-service_id="${item.id}" class="form-control text-center" name="qty[]" value="${qty}" min="0">
                        //                 <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(this, 1)">+</button>
                        //             </div>
                        //         </div>
                        //     </div>
                        // `;
                        });
                        // $('#list-product').empty();
                        // $('#list-product').append(listproduct);

                        $('#products').append(products);
                    }
                });
            }
            let listservice = '';
            let listproduct = '';
            $('#list-service').empty();
            $('#list-product').empty();
            data_service = [];
            data_product = [];

            $(document).on('click', '.item_service', function() {
                var service_id = $(this).data('id');
                var name = $(this).data('name');
                var price = $(this).data('price');

                // Kiểm tra nếu service_id đã tồn tại trong data_service
                if (data_service.includes(service_id)) {
                    // Tăng số lượng của dịch vụ nếu đã tồn tại
                    let quantityInput = $(`input[data-service_id="${service_id}"]`);
                    let currentQty = parseInt(quantityInput.val());
                    quantityInput.val(currentQty + 1); // Tăng giá trị số lượng
                } else {
                    // Thêm service_id vào mảng và tạo dòng mới
                    data_service.push(service_id);
                    $('#list-service').append(`
                        <div class="row align-items-center mb-3" data-service-row="${service_id}">
                            <!-- Cột cho input tên dịch vụ -->
                            <div class="col-md-5 col-sm-12 mb-2 mb-md-0">
                                <div style="display: flex">
                                    <div>
                                        <p style="margin:0px">${name}</p>
                                        <p style="margin:0px">${formatCurrency(price)}</p>
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" placeholder="Tên dịch vụ" value="${name}" readonly>
                                <input type="hidden" class="form-control" name="services[]" placeholder="Tên sản phẩm" value="${service_id}">
                            </div>

                            <!-- Cột cho input số lượng -->
                            <div class="col-md-5 col-sm-8">
                                <div class="input-group quantity_new">
                                    <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(this, -1)">-</button>
                                    <input type="number" data-service_id="${service_id}" class="form-control text-center" name="qty[]" value="1" min="1" oninput="this.value = this.value < 1 ? 1 : this.value">
                                    <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(this, 1)">+</button>
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-4">
                                <span  class="btn btn-danger btn-remove" data-id="${service_id}">x</span>
                            </div>
                        </div>
                    `);
                }
                //  console.log(data_service);
                $(document).on('click', '.btn-remove', function() {
                    let service_id = $(this).data('id');
                    let index = data_service.indexOf(service_id);

                    if (index !== -1) {
                        data_service.splice(index, 1);
                    }

                    $(this).closest('.row.align-items-center.mb-3').remove();
                });
            });




            $(document).on('click', '.item_product', function() {
                var product_id = $(this).data('id');
                var name = $(this).data('name');
                var price = $(this).data('price');
                var image = $(this).data('image');

                if (data_product.includes(product_id)) {
                    let quantityInput = $(`input[data-product_id="${product_id}"]`);
                    let currentQty = parseInt(quantityInput.val());
                    quantityInput.val(currentQty + 1);
                } else {
                    data_product.push(product_id);
                    $('#list-product').append(`
                                <div class="row align-items-center mb-3">
                                    <!-- Cột cho input tên dịch vụ -->
                                    <div class="col-md-5 col-sm-12 mb-2 mb-md-0">
                                        <div style="display: flex" >
                                            <img src="${image}" class="img-fluid" style="width:50px;height:50px; margin-right: 20px">
                                           <div>
                                                <p style="margin:0px">${name}</p>
                                                <p style="margin:0px">${formatCurrency(price)}</p>
                                            </div>
                                        </div>
                                        <input type="hidden" class="form-control" name="product[]" placeholder="Tên sản phẩm" value="${product_id}">
                                        <input type="hidden" class="form-control" placeholder="Tên dịch vụ" value="${name}" >
                                    </div>

                                    <!-- Cột cho input số lượng -->
                                    <div class="col-md-5 col-sm-8">
                                        <div class="input-group quantity_new">
                                            <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(this, -1)">-</button>
                                            <input type="number" data-product_id="${product_id}" class="form-control text-center" name="qty[]" value="1" min="1" oninput="this.value = this.value < 1 ? 1 : this.value">
                                            <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(this, 1)">+</button>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-4">
                                        <span  class="btn btn-danger btn-remove" data-id="${product_id}">x</span>
                                    </div>
                                </div>
                    `);
                }

                $(document).on('click', '.btn-remove', function() {

                    let product_id = $(this).data('id');
                    let index = data_product.indexOf(product_id);

                    if (index !== -1) {
                        data_product.splice(index, 1);
                    }

                    $(this).closest('.row.align-items-center.mb-3').remove();
                });
            });





            // add_premium_service
            $('.add_premium_service').on('click', function(event) {
                event.stopPropagation();
                $('#serviceModal').modal('dispose'); // Giải phóng modal hiện tại
                $('#serviceModal').modal();
                $('#serviceModal').modal('show');
                var roomId = $(this).data('id');
                data_service = [];
                fetchlistserviceandproduct(roomId)
            });

            // add_product_room
            $('.add_product_room').on('click', function(event) {
                event.stopPropagation();
                $('#productModal').modal('dispose'); // Giải phóng modal hiện tại
                $('#productModal').modal();
                $('#productModal').modal('show');
                var roomId = $(this).data('id');
                data_product = [];
                fetchlistserviceandproduct(roomId)
            });

            $('.addServiceBtn').on('click', function() {
                const content = `
                <div class="d-flex service-item position-relative mb-3 flex-wrap">
                    <div class="row w-100">
                        <div class="col-md-6">
                            <select class="custom-select no-right-radius w-100" name="services[]" required>

                            </select>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control no-left-radius w-100 h-40" name="qty[]" placeholder="@lang('Số lượng')" required type="text">
                            <button class="btn--danger removeServiceBtn border-0" type="button">
                                <i class="las la-times text--white"></i>
                            </button>
                        </div>


                    </div>
                </div>`;

                $('.service-wrapper').append(content);
                const select = $('.service-wrapper').find('select[name="services[]"]')
                    .last();


                $.ajax({
                    url: '{{ route('admin.services.booking.services') }}',
                    type: 'GET',
                    success: function(data) {
                        select.empty();

                        data.data.forEach(function(service) {
                            var option =
                                `<option value="${service.id}">${service.name} - ${formatCurrency(service.cost)}</option>`;
                            select.append(option);
                        });
                    },
                    error: function(error) {
                        console.log('Error:', error);
                    }
                });
            });

            $('.addProductBtn').on('click', function() {
                const content = `
                <div class="d-flex product-item position-relative mb-3 flex-wrap">
                    <div class="row w-100">
                        <div class="col-md-6">
                            <select class="custom-select no-right-radius w-100" name="product[]" required>

                            </select>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control no-left-radius w-100 h-40" name="qty[]" placeholder="@lang('Số lượng')" required type="text">
                            <button class="btn--danger removeProductBtn border-0" type="button">
                                <i class="las la-times text--white"></i>
                            </button>
                        </div>
                    </div>
                </div>`;

                $('.product-wrapper').append(content);
                const select = $('.product-wrapper').find('select[name="product[]"]')
                    .last();

                $.ajax({
                    url: '{{ route('admin.product.booking.product') }}',
                    type: 'GET',
                    success: function(data) {
                        select.empty();
                        data.data.forEach(function(product) {
                            var option =
                                `<option value="${product.id}">${product.name} - ${formatCurrency(product.selling_price)}</option>`;
                            select.append(option);
                        });
                    },
                    error: function(error) {
                        console.log('Error:', error);
                    }
                });
            });

            $('.service-wrapper').on('click', '.removeServiceBtn', function() {
                $(this).closest('.service-item').remove();
            });

            $('.product-wrapper').on('click', '.removeProductBtn', function() {
                $(this).closest('.product-item').remove();
            });
            $(document).on('click', '.btn-check-out', function(event) {
                alert('chưa làm xong');
                var bookingId = $('.data-booked').data('booking-id'); // mã booking
                var roomId = $('.data-booked').data('room-id'); // phòng
                var roomTypeId = $('.data-booked').data('room-type-id'); // loại phòng
                var inputValue = $('#number-input').val();
                var sanitizedValue = inputValue.replace(/\./g, ''); // giá
                var url = `{{ route('admin.booking.key.handover', ['id' => ':id']) }}`.replace(':id', bookingId); 

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        is_method: "receptionist",
                        room_id: roomId,
                        room_type_id: roomTypeId,
                        price_booked: sanitizedValue
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            window.location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Lỗi:', error);
                    }
                });
            });

            $(document).on('click', '.btn-check-in', function(event) {
                var bookingId = $('.data-booked').data('booking-id');
                var roomId = $('.data-booked').data('room-id');
                var roomTypeId = $('.data-booked').data('room-type-id');
                var bookedArr = $('.data-booked').attr('data-arr');
                var inputValue = $('#number-input').val();
                var sanitizedValue = inputValue.replace(/\./g, '');
                var url = `{{ route('admin.booking.key.handover', ['id' => ':id']) }}`.replace(':id',
                    bookingId);


                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        is_method: "receptionist",
                        room_id: roomId,
                        room_type_id: roomTypeId,
                        price_booked: sanitizedValue,
                        booked_arr: JSON.parse(bookedArr),
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            window.location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Lỗi:', error);
                    }
                });
            });

            $('.premium_service').on('click', function() {
                let data = $(this).data();

                var url = `{{ route('admin.booking.service.details', ['id' => ':id']) }}`.replace(':id',
                    data.id);

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        is_method: "receptionist"
                    },
                    success: function(response) {
                        if (response.status === "success") {
                            $('#table_service').empty();
                            if (response.services.data.length === 0) {

                                $('#table_service').append(`
                                            <tr>
                                                <td colspan="9" class="text-center">Không có dịch vụ nào!</td>
                                            </tr>
                                        `);
                            } else {


                                $.each(response.services.data, function(index, service) {
                                    var url =
                                        `{{ route('admin.premium.service.delete', ['id' => ':id']) }}`
                                        .replace(':id', service.id);
                                    var html = `
                                                        <tr>
                                                            <td>${index + 1}</td>
                                                            <td><span class="fw-bold">${ service.service_date } </span></td>
                                                            <td><span class="fw-bold">${service.room.room_number}</span></td>
                                                            <td>${service.premium_service.name}</td>
                                                            <td>${service.qty}</td>
                                                            <td>${formatCurrency(service.unit_price)}</td>
                                                            <td>${formatCurrency(service.total_amount)}</td>
                                                            <td><span class="fw-bold">${service.admin ? service.admin.name : ""}</span></td>
                                                            <td>
                                                                <form method = "post" action = "${url}">
                                                                    @csrf
                                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                                                    data-question="@lang('Bạn có chắc chắn muốn xóa dịch vụ này không?')">
                                                                        <i class="las la-trash-alt"></i>@lang('Delete')
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    `;
                                    $('#table_service').append(html);
                                });
                            }

                        }

                        // Show the modal after loading data
                        $('#premium_serviceModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('Lỗi:', error);
                    }
                });
            });

            function checkService(url, method, is_value) {

            }

            function cleanRoom() {
                $('.room-icon').on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    let roomData = $(this).attr('data-room');
                    let roomClean = $(this).attr('data-clean');
                    let textClean = '';

                    if (roomClean == 1) {
                        textClean = '<strong style="color: red;">Chưa dọn</strong>';
                    } else {
                        textClean = '<strong style="color: #28a745;">Sạch</strong>';
                    }

                    $('#dynamicModalLabel').html('Chuyển trạng thái buồng phòng ' + roomData +
                        ' thành ' +
                        textClean);
                    $('#modalRoomInfo').html('Chuyển trạng thái buồng phòng ' + roomData +
                        ' thành ' +
                        textClean);

                    var modal = new bootstrap.Modal($('#dynamicModal'));
                    $('.btn-clean').off('click').on('click', function(event) {
                        event.stopPropagation();
                        var url = `{{ route('admin.roomclean.booking.roomclean') }}`;
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                roomData: roomData,
                                roomClean: roomClean
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    modal.hide();
                                    window.location.reload();
                                }
                            },
                            error: function(xhr, status, error) {

                                console.error('Lỗi:', error);
                            }
                        });
                    });
                    modal.show();
                });
            }
            cleanRoom();

            function attachClickHandlersBooked() {
                $('.room-booking-status-dirty').on('click', function() {
                    $('#myModal-booking').modal('show');

                    const dataName = $(this).data('name');
                    const dataRoomType = $(this).data('room-type'); // loại room
                    const dataRoom = $(this).data('room');
                    const dataRoomNumber = $(this).data('roomnumber');
                    const $bookingRow = $('#list-booking tr');

                    // Gán data-* vào thẻ tr
                    $bookingRow.attr('data-room-id', dataRoom);
                    $bookingRow.attr('data-room-type-id', dataRoomType);

                    getRoomType(dataRoomType, dataRoomNumber); // hiển thị ra phòng theo loại


                    $('[name=room_type]').val(dataRoom);
                    $('#book_name').text(dataName);
                    $('#roomNumber').val(dataRoomNumber);
                    const today = new Date();
                    const yyyy = today.getFullYear();
                    const mm = String(today.getMonth() + 1).padStart(2, '0');
                    const dd = String(today.getDate()).padStart(2, '0');
                    const hourss = String(today.getHours()).padStart(2, '0'); // Giờ
                    const minutess = String(today.getMinutes()).padStart(2, '0'); // Phút
                    // Định dạng ngày: yyyy-MM-dd
                    // Định dạng thời gian: HH:mm
                    const formattedDate = `${yyyy}-${mm}-${dd}`;
                    const formattedTime = `${hourss}:${minutess}`;
                    let date = new Date(formattedDate);
                    date.setDate(date.getDate() + 1);
                    let yyyy2 = date.getFullYear();
                    let mm2 = String(date.getMonth() + 1).padStart(2, '0');
                    let dd2 = String(date.getDate()).padStart(2, '0');

                    const nextDay = `${yyyy2}-${mm2}-${dd2}`;
                    // Gán giá trị vào input
                    // check in
                    $('#date-book-room').val(formattedDate);

                    $('#time-book-room').val(formattedTime);
                    // check out
                    $('#date-book-room-out').val(nextDay);

                    $('#time-book-room-out').val(formattedTime);

                });
            }
            attachClickHandlersBooked();

            function getRoomType(id, room_number) {
                $('#loading').show();
                $.ajax({
                    url: '{{ route('admin.booking.getRoomType') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            const selectElement = document.getElementById('roomNumber');

                            response.data.forEach(room => {
                                const option = document.createElement('option');
                                // Tạo thẻ <option>

                                option.value = room.id;

                                option.textContent = room.room_number;
                                if (room.room_number === room_number) {
                                    option.selected = true;
                                }
                                selectElement.appendChild(option);

                            })
                        }
                        $('#loading').hide();
                        // $('#empty-rooms').html(response);
                        // $('#roomListContainer').empty();
                        // attachClickHandlersBooked();
                        // cleanRoom();
                    },
                    error: function(xhr) {
                        $('#loading').hide();
                        console.error('AJAX Error:', xhr.responseText);
                    }
                });
            }
            $('#search-rooms-dates').click(function(event) {
                event.preventDefault();
                $('#loading').show();
                const dates = $('input[name="dates"]').val();
                const codeRoom = $('#search-code-room').val();
                const roomType = $('#search-room-type').val();
                const customer = $('#search-custom').val();
                const [startDate, endDate] = dates.split(' - ');
                $.ajax({
                    url: '{{ route('admin.receptionist.booking.receptionist') }}',
                    type: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}',
                        startDate,
                        endDate,
                        customer,
                        codeRoom,
                        roomType
                    },
                    success: function(response) {
                        $('#loading').hide();
                        $('#empty-rooms').html(response);
                        $('#roomListContainer').empty();
                        attachClickHandlersBooked();
                        cleanRoom();
                    },
                    error: function(xhr) {
                        $('#loading').hide();
                        console.error('AJAX Error:', xhr.responseText);
                    }
                });

            })
            $(document).on('click', '.room-booking-status-occupied', function() {
                var id = $(this).data('id');
                var booking_id = $(this).data('room');
                // console.log(id);
                // console.log(booking_id);
                
                

                handleLateCheckinClick(id, booking_id);
            });

            $('.room-booking-status-incoming').on('click', function() {
                var id = $(this).data('id');
                var booking_id = $(this).data('room');
                handleLateCheckinClick(id, booking_id);
            });

            // $('.room-booking-status-late-checkin').on('click', function() {
            //     var id = $(this).data('id');
            //     var booking_id = $(this).data('booking');
            //     handleLateCheckinClick(id, booking_id);
            // });

            $(document).on('click', '.room-booking-status-late-checkin', function() {
                var id = $(this).data('id');
                var booking_id = $(this).data('room');
                handleLateCheckinClick(id, booking_id);
            });

            // function handleLateCheckinEvent() {
            //     $('.room-booking-status-late-checkin').on('click', function() {
            //         var id = $(this).data('id');
            //         var booking_id = $(this).data('booking');
            //         handleLateCheckinClick(id, booking_id);
            //     });
            // }
            // // Sử dụng hàm
            // handleLateCheckinEvent();


            $('.room-booking-status-check-out').on('click', function() {
                var id = $(this).data('id');
                var booking_id = $(this).data('booking');
                handleLateCheckinClick(id, booking_id);
            });


            function handleLateCheckinClick(id, booking_id) {

                var url = `{{ route('admin.booking.details', ['id' => ':id']) }}`.replace(':id', id);
                $('#booking_id').val(id);
                var dataToSend = {
                    is_method: 'receptionist',
                    room_id: booking_id,
                };
                fetchDataAndDisplayModal(url, dataToSend);
            }

            function fetchDataAndDisplayModal(url, dataToSend) {
                $('#loading').show();
                $.ajax({
                    url: url,
                    data: dataToSend,
                    type: 'GET',
                    success: function(response) {
                        $('#loading').hide();
                        if (response.status === 'success') {
                            // console.log(response.data['total_people']);
                            // console.log(response.room);
                            
                            var customer_type = response.data.user_id ?
                                "Khách hàng đã đăng ký" : " Khách hàng lưu trú"



                            $('.room-details-checkout').empty();
                            let row = '';
                            row += `
                                    <div class="detail-row-user">
                                        <div class="detail-item-checkout">
                                            <strong>Khách hàng</strong>
                                            <p class="user_info">${response.data.user_booking?.['username'] ?? response.data.guest_details['name']}-${response.data.user_booking?.['mobile'] ?? response.data.guest_details['mobile']}</p>
                                        </div>
                                        <div class="detail-item-checkout">
                                            <strong>Loại khách hàng</strong>
                                            <p class="customer_type">${customer_type}</p>
                                        </div>
                                          <div class="detail-item-checkout">
                                            <strong>Khách lưu trú</strong>
                                            <p class="customer_type">${response.data['total_people']} người</p>
                                        </div>
                                        <div class="detail-item-checkout">
                                            <strong>Mã đặt phòng</strong>
                                            <p class="booking_number">${response.data.booking_number}</p>
                                        </div>
                                            </div>

                                        <div class="detail-row-checkout">
                                        <div class="detail-item-checkout">
                                            <strong>Nhận phòng</strong>
                                            <p class="check_in">${response.room['room_status'][0]['check_in'] ?? ""}</p>
                                        </div>
                                         <div class="detail-item-checkout">
                                            <strong>Trả phòng</strong>
                                            <p class="check_in">${response.room['room_status'][0]['check_out'] ?? ""}</p>
                                        </div>

                                    `
                            $('.room-details-checkout').append(row);


                            $('.booking-no').empty();
                            let rowBooked = '';
                         
                            
                            rowBooked += ` ${response.room['room_number']}`;
                            $('.booking-no').append(rowBooked);


                            $('.note-booking').empty();
                            let rowNote = '';
                            rowNote += ` ${response.data.note ?? "Nhập ghi chú..."}`;
                            $('.note-booking').append(rowNote);

                            $('#note-input').attr('data-id', response.data.id);


                            $('.info-room').empty();
                            let rowPeople = '';

                            let rowGroupPrice = `<div class="border rounded p-2 mb-2" style="width: 250px;">
                                <div class="d-flex justify-content-between">
                                    <span class="fz-13 name-room">${response.room['room_number']}</span>
                                    <span>${formatCurrency(response.room.room_status[0]['fare'])}</span>
                                </div>`;
                            if (response.data.booked_rooms.length >= 2) {
                                let priceGroup = 0;
                                rowGroupPrice += ` <div class="d-flex justify-content-between mt-1">
                                        <span class="fz-13">Cả đoàn</span>`
                                response.data.booked_rooms.forEach(room => {
                                    let fareInt = parseInt(room.fare);
                                    priceGroup += fareInt;

                                });

                                rowGroupPrice +=
                                    `<span class="fw-bold">${formatCurrency(priceGroup)}</span>`
                                rowGroupPrice += `</div>`
                            } else {
                                $('.group-booked-room').empty();
                            }
                           
                            rowGroupPrice += ` <div class="d-flex justify-content-between mt-1">
                                    <span class="fz-13">Khách đã trả</span>
                                    <span class="fw-bold">${formatCurrency(response.data.paid_amount)}</span>
                                </div>`

                            rowGroupPrice += ` </div>`
                            $('.info-room').append(rowGroupPrice);

                            // các phòng trong đoàn
                            // console.log(response.data.booked_rooms);

                            if (response.data.booked_rooms.length >= 2) {
                                $('.group-booked-room').empty();
                                let rowGroup =
                                    '<span class="group-booked-room">Các phòng trong đoàn:</span><div class="d-flex" style="gap:10px">';

                                response.data.booked_rooms.forEach(room => {
                                    let classText = room.key_status === 0 ? 'text-origin' :
                                        'text-green';
                                    rowGroup +=
                                        `<p class="${classText}">${room.room['room_number']}</p>`;
                                });

                                rowGroup += '</div>';
                                $('.group-booked-room').append(rowGroup);
                            } else {
                                $('.group-booked-room').empty();
                            }

                            $('.chose-btn-room').empty();

                            if (response.room.room_status[0]['key_status'] == 1) {
                                let keyRoomActive = `
                                    <button type="submit" class="w-10 btn-primary-1 modal-checkout fz-13" style="height: 31px; ">Trả phòng</button>
                                `;
                                $('.chose-btn-room').append(keyRoomActive);
                            } else {
                                let keyRoomUnActive = `
                                    <button type="submit" class="w-10 btn-primary-1 modal-checkin fz-13" style="height: 31px;">Nhận phòng</button>
                                `;
                                $('.chose-btn-room').append(keyRoomUnActive);
                            }

                            $('#myModal-booking-status').modal('show');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#loading').hide();
                        console.error('Error:', error);

                    }
                });
            }
            $('#submitBtn').click(function(e) {
                e.preventDefault();

                var booking_id = $('#booking_id').val();
                var amount = $('#amount_payment').val();

                $('#amount_payment_errors').text('');
                if (amount > 0) {
                    var url = `{{ route('admin.booking.payment', ['id' => ':id']) }}`.replace(':id',
                        booking_id);

                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            _token: '{{ csrf_token() }}',
                            amount: amount,
                            is_method: "receptionist"
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                $('#amount_payment').val("");
                                handleLateCheckinClick(response.id, response.booking_id)
                                notify('success', `Đã nhận thanh toán thành công`);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            console.log(xhr.status);
                            alert("Đã có lỗi xảy ra. Vui lòng thử lại.");
                        }
                    });
                } else {
                    $('#amount_payment_errors').text('Vui lòng nhập số tiền!');
                }
            });

            
            $('#hour_current').on('click', function() {
               
                // Lấy ngày và giờ hiện tại
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0'); // Tháng bắt đầu từ 0
                const date = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');

                // Kết hợp thành giá trị datetime-local
                const currentDate = `${year}-${month}-${date}`; // Format YYYY-MM-DD
                const currentTime = `${hours}:${minutes}`;      // Format HH:mm

                // Gán giá trị vào các input
                $('#date-book-room').val(currentDate);
                $('#time-book-room').val(currentTime);
            });


            $('#hour_regulatory').on('click', function() {
                // var url = `{{ route('admin.checkhours') }}`;
                // $.ajax({
                //     url: url,
                //     type: "GET",
                //     data: {
                //         _token: '{{ csrf_token() }}',
                //     },
                //     success: function(response) {
                //         if (response.status === 'success') {
                //             const bookingType = $('#bookingType').val();

                //             var result = response.data;
                //             var checkin_time = result.checkin_time;
                //             var checkout_time = result.checkout_time;
                //             var checkin_time_night = result.checkin_time_night;
                //             var checkout_time_night = result.checkout_time_night;
                //             if (bookingType == 'ngay') {
                //                 var checkInTime = hous_mac_dinh(checkin_time);
                //                 var checkOutTime = hous_mac_dinh_dem(checkout_time);
                //             } else if (bookingType == 'dem') {
                //                 var checkInTime = hous_mac_dinh(checkin_time_night);
                //                 var checkOutTime = hous_mac_dinh_dem(checkout_time_night);
                //             } else {
                //                 return 0;
                //             }
                //             //  console.log(checkInTime);

                //             // Gán giá trị mới vào input
                //             $('#checkInTime').val(checkInTime);
                //             $('#checkOutTime').val(checkOutTime);
                //             //   calculateDuration();
                //         }
                //     },
                //     error: function(xhr, status, error) {
                //         console.error(xhr.responseText);
                //         console.log(xhr.status);
                //         alert("Đã có lỗi xảy ra. Vui lòng thử lại.");
                //     }
                // });
            });

            function hous_mac_dinh(time) {
                const [checkinHours, checkinMinutes] = time.split(':');
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const date = String(now.getDate()).padStart(2, '0');
                const updatedDateTime = `${year}-${month}-${date}T${checkinHours}:${checkinMinutes}`;
                return updatedDateTime;
            }

            function hous_mac_dinh_dem(time) {
                // Tách giờ và phút từ chuỗi time
                const [checkinHours, checkinMinutes] = time.split(':');

                // Lấy ngày hiện tại
                const now = new Date();

                // Tăng ngày hiện tại thêm 1
                now.setDate(now.getDate() + 1);

                // Lấy năm, tháng, ngày sau khi tăng
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const date = String(now.getDate()).padStart(2, '0');

                // Kết hợp thành định dạng datetime-local
                const updatedDateTime = `${year}-${month}-${date}T${checkinHours}:${checkinMinutes}`;
                return updatedDateTime;
            }


            function reserthoursNow() {
                var now = new Date();
                var year = now.getFullYear();
                var month = (now.getMonth() + 1).toString().padStart(2, '0');
                var day = now.getDate().toString().padStart(2, '0');
                var hours = now.getHours().toString().padStart(2, '0');
                var minutes = now.getMinutes().toString().padStart(2, '0');

                var currentDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
                // var checkInTime = document.getElementById('checkInTime');
                // checkInTime.value = currentDateTime;
            }


            // $('#checkInTime, #checkOutTime').on('change', calculateDuration);


            $('.customer-svg-icon-add').on('click', function() {
                $('#customer-popup').addClass('active');
            });

            $('#customer-closePopup, #customer-close').on('click', function() {
                $('#customer-popup').removeClass('active');
            });

            // loại khách hàng
            // $('#guest_type').on('change', function() {
            //     var selectedValue = $(this).val();

            //     if (selectedValue == '1') { // Khách đã đăng ký

            //         $('#name, #phone, #address').closest('label, input').hide();

            //         $('#email').closest('label, input').show();
            //     } else {

            //         $('#name, #email, #phone, #address').closest('label, input').show();
            //     }
            // });

            // validate nếu nhập không có tên khách hàng
            $('#customer-name').on('blur', function() {
                const inputValue = $(this).val().toLowerCase();
                const options = $('#customer-names option');
                let isValid = false;
                const values = options.map(function() {
                    return $(this).val()
                        .toLowerCase();
                }).get();
                if (values.includes(inputValue)) {
                    isValid = true;
                }

                if (inputValue !== '') {
                    if (isValid) {
                        $('#error-message').hide();
                    } else {
                        $('#error-message').show();
                    }
                }
            });
            // thông tin khách hàng
            $('#customer-name').on('input', function() {

                var inputValue = $(this).val().toLowerCase();
                var isValid = false;
                var matchedOption = null;

                $('#customer-names option').each(function() {
                    if ($(this).val().toLowerCase() === inputValue) {
                        matchedOption = $(this);
                        isValid = true;
                        return false; // Break the loop
                    }
                });

                if (isValid && matchedOption) {
                    // Update the user info with the data-* attributes
                    $('.username-user').text(matchedOption.val());
                    $('.clear-main').text("|");
                    $('.email-user').text(matchedOption.data('user'));


                    $('.email-user1').val(matchedOption.val());
                    $('.username-user1').val(matchedOption.data('user'));
                    $('.mobile-user').val(matchedOption.data('mobi'));
                    $('.address-user').val(matchedOption.data('address'));
                } else {
                    // Clear the user info if no match
                    $('.username-user').text('');
                    $('.email-user').text('');
                    $('.clear-main').text('');
                }
            });
            // btn booking
            $('.btn-confirm, .btn-book').on('click', function() {
                const dataRowValue = $(this).data('row');
                $('.booking-form').data('row', dataRowValue); // Thiết lập data-row cho form
                $('.booking-form').submit(); // Gửi form
            });


            function getDatesBetween(checkInDate, checkInTime, checkOutDate, checkOutTime, room, roomType, optionRoom) {
                let dates = [];
                let currentDate = new Date(checkInDate);
                let currentDateOut = new Date(checkOutDate);

                const [checkOutHours, checkOutMinutes] = checkOutTime.split(':').map(Number);
                const [checkInHours, checkInMinutes]   = checkInTime.split(':').map(Number);

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

            // 123123
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
                //   console.log(roomData);

                roomData.forEach(function(item) {
                    const roomDates = getDatesBetween(item['checkInDate'], item['checkInTime'], item['checkOutDate'], item['checkOutTime'], item['roomId'], item['roomTypeId'], item['optionRoom']);
                    roomDates.forEach(function(date, index) {
                        formData.push({
                            name: 'room[]',
                            value: date
                        });
                    });
                })

                const guest_type = $('.guest_type').val();

                if (guest_type === "") {
                    formData.push({
                        name: 'guest_type',
                        value: 1
                    })
                } else {
                    formData.push({
                        name: 'guest_type',
                        value: 0
                    })
                }
                formData.push({
                    name: 'total_people',
                    value: totalPeople
                })
                formData.push({
                    name: 'is_method',
                    value: 'receptionist',
                });
                formData.push({
                    name: 'check_btn',
                    value: dataRowValue,
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
            });


            $('.btn-user-info').on('click', function(event) {

                // Lấy giá trị của guest_type
                //    let guestType = $('#guest_type').val();
                event.stopPropagation();

                let name = $('#name').val();
                let email = $('#email').val();
                let phone = $('#phone').val();
                let address = $('#address').val();
                //    123123
                $('#customer-name').val(email);
                // {{-- <div class="user-info-customer">
                //                 <p class="email-user"></p>
                //                 <p class="ms-2 me-2 clear-main"> </p>
                //                 <p class="username-user"></p>
                //             </div> --}}
                // $('.email-user').text(email);
                // $('.clear-main').text("|");

                // $('.username-user').text(name);
                $('.guest_type').val(0);

                $('.username-user1').val(name);
                $('.email-user1').val(email);
                $('.mobile-user').val(phone);
                $('.address-user').val(address);
                $('#customer-popup').removeClass('active');


            });
            // cccd
            $('.camera-svg-icon-add').on('click', function() {

                $('#fileUpload').click();
            });

            // Xử lý sự kiện khi file được chọn
            $('#fileUpload').on('change', function(event) {
                const files = event.target.files;
                const file = files[0]; // Lấy file đầu tiên trong danh sách

                if (file) {

                    const formData = new FormData();
                    formData.append('image', file);
                    formData.append('_token', '{{ csrf_token() }}');

                    var url = '{{ route('admin.booking.writeCccd') }}';
                    $('#loading').show();
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData,
                        processData: false, // Để tránh xử lý dữ liệu
                        contentType: false, // Để tránh thiết lập header Content-Type
                        success: function(response) {
                            $('#loading').hide();

                            $('.username-user').text(response.data[0]['name']);

                            $('.customer-svg-icon-add').click();

                            $('#name').val(response.data[0]['name']);
                            $('#address').val(response.data[0]['address']);

                        },
                        error: function(xhr, status, error) {
                            $('#loading').hide();
                            console.error("Lỗi khi gửi yêu cầu API:", error);
                        }
                    });
                }
                $(this).val(null);
            });


            $('.extraChargeBtn').on('click', function() {
                let data = $(this).data();
                let id = $('.booking_extra').val();
                let modal = $('#extraChargeModal');
                modal.find('.modal-title').text($(this).text());
                if (data.type == 'add') {
                    modal.find('form').attr('action',
                        `{{ route('admin.booking.extra.charge.add', '') }}/${id}`);
                    modal.find('[name=type]').val('add');
                } else {
                    modal.find('form').attr('action',
                        `{{ route('admin.booking.extra.charge.subtract', '') }}/${id}`
                    );
                    modal.find('[name=type]').val('subtract');
                }
                modal.modal('show');
            });
            var langChoose = "{{ __('Chọn') }}";
            $.ajax({
                url: '{{ route('admin.services.booking.services') }}',
                type: 'GET',
                success: function(data) {
                    $('select[name="services[]"]').empty();
                    $('select[name="services[]"]').append('<option value="">' + langChoose +
                        '</option>');

                    data.data.forEach(function(service) {
                        var option =
                            `<option value="${service.id}">${service.name} - ${formatCurrency(service.cost)}</option>`;
                        $('select[name="services[]"]').append(option);
                    });
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
            $.ajax({
                url: '{{ route('admin.product.booking.product') }}',
                type: 'GET',
                success: function(data) {
                    $('select[name="product[]"]').empty();
                    $('select[name="product[]"]').append('<option value="">' + langChoose +
                        '</option>');

                    data.data.forEach(function(service) {
                        var option =
                            `<option value="${service.id}">${service.name} - ${formatCurrency(service.selling_price)}</option>`;
                        $('select[name="product[]"]').append(option);
                    });
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
            let serviceForm = $('.add-service-form');

            serviceForm.on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();
                //  console.log(formData);
                let url = $(this).attr('action');
                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            notify('success', response.success);
                            let firstItem = $('.first-service-wrapper .service-item');
                            // $(document).find('.service-wrapper').find('.service-item').not(firstItem).remove();

                            serviceForm.trigger("reset");
                            $('#serviceModal').hide();
                            $('#productModal').hide();

                            handleLateCheckinClick(response.data['booking_id'], response.data[
                                'booked_room_id']);
                        } else {
                            $.each(response.error, function(key, value) {
                                notify('error', value);
                            });
                        }
                    },
                });
            });

        });


        // định dạng tiền
        function formatCurrency(amount) {
            const parts = amount.toString().split('.');
            const integerPart = parts[0];
            const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            return formattedInteger + ' VND';
        }
        const showSwalMessage = (icon, title, timer = 2000) => {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: timer,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: icon,
                title: `<p>${title}</p>`
            });
        };



        $(document).on('click', '.cancelBookingBtn', function() {

            let modal = $('#cancelBookingModal');
            let data = $(this).data();
            let action;

            if (data.booked_for) {
                action = `{{ route('admin.booking.booked.day.cancel', '') }}/${data.id}`;
                modal.find('[name=booked_for]').val(data.booked_for);
            } else {
                action = `{{ route('admin.booking.booked.room.cancel', '') }}/${data.id}`;
            }

            modal.find('.modal-title').text(`@lang('Hủy đặt phòng')`);
            modal.find('form').attr('action', action);
            modal.find('.totalFare').text(data.fare);
            modal.find('.refundableAmount').text(data.should_refund);
            modal.modal('show');
        });

        function formatCurrency(amount) {
            amount = parseFloat(amount);

            return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' đ';
        }

        function bindFormattedInput(displaySelector, hiddenSelector) {
            $(document).on('input', displaySelector, function() {
                let displayInput = $(this);
                let hiddenInput = $(hiddenSelector);
                let rawValue = displayInput.val().replace(/,/g, ''); // Loại bỏ dấu phẩy cũ

                if ($.isNumeric(rawValue)) {
                    // Định dạng giá trị với dấu phẩy
                    let formattedValue = parseInt(rawValue).toLocaleString('en-US');
                    displayInput.val(formattedValue);

                    // Lưu giá trị không định dạng vào input hidden
                    hiddenInput.val(rawValue);
                } else {
                    // Xóa cả hai giá trị nếu nhập không hợp lệ
                    displayInput.val('');
                    hiddenInput.val('');
                }
            });
        }

        $(document).ready(function() {
            bindFormattedInput('#amount_payment', '#amount_payment_key');
            bindFormattedInput('#minus_fee', '#minus_fee_key');
        });

        $(document).on('click', '.close_model', function() {
            let parentModal = $(this).closest('.modal');
            parentModal.modal('hide');
        });
        $(document).ready(function() {
            // Đóng modal khi nhấn nút "Close"
            $('.modal-backdrop .btn-close').click(function() {
                $('.modal-backdrop').removeClass('show');
                $('.modal-backdrop').hide();
                data_service = [];
                data_product = [];
            });

            // Đóng modal khi nhấp ra ngoài
            $(document).click(function(event) {
                if (!$(event.target).closest('.modal-backdrop .modal-dialog').length) {
                    $('.modal-backdrop').removeClass('show');
                    $('.modal-backdrop').hide();
                    data_service = [];
                    data_product = [];
                }
            });
        });


        $('#save-note').on('click', function() {
            var note = $('#note-input').val();
            var noteid = $('#note-input').data('id');
            noteUpdate(note, noteid);
        });
        // Thay đ��i ghi chú
        function noteUpdate(note, noteid) {
            if (note.trim() !== '') {
                $.ajax({
                    url: '{{ route('admin.room.note') }}',
                    type: 'POST',
                    data: {
                        note: note,
                        id: noteid,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        notify('success', response.success);
                        $('.note-booking').html(note);
                        $('#noteModal').modal('hide');
                    },
                    error: function(xhr, status, error) {

                        alert('Có lỗi xảy ra khi lưu ghi chú!');
                    }
                });
            } else {
                $('#noteModal').modal('hide');
            }
        }
    </script>
@endpush

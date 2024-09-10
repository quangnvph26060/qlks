@extends('admin.layouts.app')
@section('panel')
    <div class="row">

    </div>
    <div class="row">
        <div class="col">
            <div class="status-buttons">
                <div class="status-button status-available-line">
                    <span class="status-dot"></span>
                    Đang trống (8)
                </div>
                <div class="status-button status-incoming-line">
                    <span class="status-dot"></span>
                    Sắp nhận (2)
                </div>
                <div class="status-button status-occupied-line">
                    <span class="status-dot"></span>
                    Đang sử dụng (4)
                </div>
                <div class="status-button status-checkout-line">
                    <span class="status-dot"></span>
                    Sắp trả (2)
                </div>
                <div class="status-button status-overdue-line">
                    <span class="status-dot"></span>
                    Quá giờ trả (1)
                </div>
            </div>
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
        <!-- Room P.201 -->
        @forelse($emptyRooms as $rooms)
            @php
                $class = 'status-dirty';
                if ($rooms->status == 1) {
                    $class = 'status-occupied'; // đang hoạt động; sắp tới
                }
            @endphp

            <div class="col-md-2">
                <div class="room-card  {{ $class }}">
                    <div class="d-flex  justify-content-between align-items-center">
                        <div id="badgeChuaDon" class="badge badge-danger">
                            <svg style="margin-right: 10px" xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                viewBox="0 0 48 48">
                                <g fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="4">
                                    <path stroke-linecap="round" d="M20 5.914h8v8h15v8H5v-8h15v-8Z" clip-rule="evenodd" />
                                    <path d="M8 40h32V22H8v18Z" />
                                    <path stroke-linecap="round" d="M16 39.898v-5.984m8 5.984v-6m8 6v-5.984M12 40h24" />
                                </g>
                            </svg>Chưa dọn
                        </div>

                        <div>
                            <svg style="color: #161515" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 21 21" class="room-icon" data-room="{{ $rooms->room_number }}"
                                style="cursor:pointer;" data-clean="1">
                                <g fill="currentColor" fill-rule="evenodd">
                                    <circle cx="10.5" cy="10.5" r="1" />
                                    <circle cx="10.5" cy="5.5" r="1" />
                                    <circle cx="10.5" cy="15.5" r="1" />
                                </g>
                            </svg>
                        </div>
                    </div>
                    <div class="content-booking mt-2 room-booking-{{ $class }}"
                        data-hours="{{ $rooms->roomType->hourly_rate }}"
                           data-day="{{ $rooms->roomType->fare }}"
                        data-night="{{ $rooms->roomType->fare }}"
                         data-name = "{{ $rooms->roomType->name }}"
                        data-roomNumber="{{ $rooms->room_number }}">
                        <h5>{{ $rooms->room_number }}</h5>
                        <p class="single-line">{{ $rooms->roomType->name }}</p>
                        <div class="room-info">
                            <p><i class="fas fa-clock icon"></i><span>{{ showAmount($rooms->roomType->hourly_rate) }}</span>
                            </p>
                            <p><i class="fas fa-sun icon"></i>{{ showAmount($rooms->roomType->fare) }}</p>
                            <p><i class="fas fa-moon icon"></i>{{ showAmount($rooms->roomType->fare) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center" colspan="100%">{{ __($emptyMessage) }}</p>
        @endforelse
        @forelse($bookings as $booking)
            @php
                $class = 'status-dirty';
                if (now() >= $booking->check_in && $booking->status == 1) {
                    $class = 'status-occupied'; // đang hoạt động;
                } elseif (now() < $booking->check_in && $booking->status == 1) {
                    $class = 'status-incoming'; // sắp tới
                }
            @endphp
            <div class="col-md-2">
                <div class="room-card {{ $class }}">
                    <div class="d-flex  justify-content-between align-items-center">
                        <div id="badgeChuaDon" class="badge badge-danger">
                            <svg style="margin-right: 10px" xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                viewBox="0 0 48 48">
                                <g fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="4">
                                    <path stroke-linecap="round" d="M20 5.914h8v8h15v8H5v-8h15v-8Z" clip-rule="evenodd" />
                                    <path d="M8 40h32V22H8v18Z" />
                                    <path stroke-linecap="round" d="M16 39.898v-5.984m8 5.984v-6m8 6v-5.984M12 40h24" />
                                </g>
                            </svg>Chưa dọn
                        </div>

                        <div>
                            <svg style="color: #161515" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 21 21" class="room-icon" data-room="{{ $rooms->room_number }}"
                                style="cursor:pointer;" data-clean="1">
                                style="cursor:pointer;">
                                <g fill="currentColor" fill-rule="evenodd">
                                    <circle cx="10.5" cy="10.5" r="1" />
                                    <circle cx="10.5" cy="5.5" r="1" />
                                    <circle cx="10.5" cy="15.5" r="1" />
                                </g>
                            </svg>

                        </div>
                    </div>

                    <div class="content-booking mt-2 room-booking-{{ $class }}"
                        data-hours="{{ $booking->bookedRooms[0]->roomType->hourly_rate }}"
                        data-day="{{ $booking->bookedRooms[0]->roomType->fare }}"
                        data-night="{{ $booking->bookedRooms[0]->roomType->fare }}"
                        data-name = "{{ $booking->bookedRooms[0]->roomType->name }} "
                        data-roomNumber="{{ $rooms->room_number }}">

                        <h3>{{ $booking->bookedRooms[0]->room->room_number }}</h3>
                        {!! $booking->checkGuest() !!}
                        @php

                            $currentTime = now();
                            $checkInTime = $booking->check_in;
                            $flag = true;
                            // So sánh thời gian hiện tại và thời gian check-in
                            if ($currentTime < $checkInTime) {
                                $flag = false;
                                // Tính khoảng cách thời gian giữa check-in và thời gian hiện tại
                                $diffInHours = $currentTime->diffInHours($checkInTime);
                                $diffInDays = $currentTime->diffInDays($checkInTime);
                                if ($diffInHours < 24) {
                                    $roundedHours = floor($diffInHours);
                                    $time_check_in = "Còn $roundedHours giờ nữa nhận phòng.";
                                } else {
                                    $roundedDays = floor($diffInDays);
                                    $time_check_in = "Còn $roundedDays ngày nữa nhận phòng.";
                                }
                            } else {
                                $flag = true;
                            }
                        @endphp

                        @if ($flag)
                            <p class="single-line">{{ $booking->bookedRooms[0]->roomType->name }}</p>
                            <div class="room-info">
                                <p> <i
                                        class="fas fa-clock icon"></i>{{ showAmount($booking->bookedRooms[0]->roomType->hourly_rate) }}
                                </p>
                                <p> <i
                                        class="fas fa-sun icon"></i>{{ showAmount($booking->bookedRooms[0]->roomType->fare) }}
                                </p>
                                <p> <i
                                        class="fas fa-moon icon"></i>{{ showAmount($booking->bookedRooms[0]->roomType->fare) }}
                                </p>
                            </div>
                        @else
                            @php
                                echo $time_check_in;
                            @endphp
                        @endif

                    </div>
                </div>
            </div>
        @empty
            <p class="text-center" colspan="100%">{{ __($emptyMessage) }}</p>
        @endforelse

        <!-- modal dặt hàng  -->
        <div class="modal fade" id="myModal-booking" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel-booking" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 1200px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel-booking">Đặt/Nhận phòng nhanh</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="bookingForm">
                            <!-- Row: Labels -->
                            <div class="table-responsive">
                                <table class="table mobi-table">
                                    <thead>
                                        <tr class="text-center fw-bold main-booking-modal">
                                            <th>Hạng phòng</th>
                                            <th>Phòng</th>
                                            <th>Hình thức</th>
                                            <th>Nhận</th>
                                            <th>Trả phòng</th>
                                            <th class="d-flex justify-content-between align-items-center">Dự kiến <span>Thành tiền</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><p id="book_name"></p></td>
                                            <td><input type="text" class="form-control" id="roomNumber" disabled></td>
                                            <td>
                                                <select id="bookingType" class="form-select">
                                                    <option value="gio">Giờ</option>
                                                    <option value="ngay">Ngày</option>
                                                    <option value="dem">Đêm</option>
                                                </select>
                                            </td>
                                            <td><input type="datetime-local" class="form-control" id="checkInTime"></td>
                                            <td><input type="datetime-local" class="form-control" id="checkOutTime"></td>
                                            <td>
                                                <p class="d-flex justify-content-between align-items-center">
                                                    <span class="inputTime">00:00</span>
                                                    <input type="text" class="custom-input " id="input-price-booking">
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- <div class="row mb-3">
                                <div class="col-md-3">
                                    <button class="btn btn-outline-secondary">Chọn thêm phòng</button>
                                </div>

                            </div> --}}

                            <hr>


                            <div class="card mb-3">
                                <div class="card-body">

                                    <div class="row mb-3 justify-content-between">
                                        <div class="col-md-9">

                                        </div>
                                        <div class="col-md-3 text-end" style="padding: 0px">
                                            <div class="form-group " style="background: #ddd;border-radius:8px ">
                                                <div class="col-12  mt-2 d-flex justify-content-around">

                                                    <label class="fw-bold">Khách cần trả</label>
                                                    <input type="number" value="2000" class="custom-input"
                                                        id="customer-price-booking"
                                                        style="border-bottom: 1px solid #a89191 ">
                                                </div>

                                                <div class="col-12 mt-2 mb-2 d-flex justify-content-around">
                                                    <label class="fw-bold">Khách thanh toán</label>
                                                    <input type="number" class="custom-input"
                                                        style="border-bottom: 1px solid #a89191 ">
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-primary">Đặt phòng</button>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- trả phòng  --}}
        <div class="modal fade" id="myModal-checkout" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel-booking" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 1200px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel-booking">Chi tiết P.303</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="bookingForm">
                            <!-- Row: Labels -->
                            <div class="room-card-checkout">
                                <div class="room-header-checkout">
                                    <h4>Phòng 01 giường đơn</h4>
                                    <span class="status-label-checkout">Đang sử dụng</span>
                                    <span class="icon-money-checkout">$</span>
                                </div>

                                <div class="room-details-checkout">
                                    <div class="detail-row-checkout">
                                        <div class="detail-item-checkout">
                                            <strong>Khách hàng</strong>
                                            <p>quang - 0382252561</p>
                                        </div>
                                        <div class="detail-item-checkout">
                                            <strong>Khách lưu trú</strong>
                                            <p>1 người lớn, 0 trẻ em, 0 giấy tờ</p>
                                        </div>
                                        <div class="detail-item-checkout">
                                            <strong>Mã đặt phòng</strong>
                                            <p>DP000020</p>
                                        </div>
                                    </div>

                                    <div class="detail-row-checkout">
                                        <div class="detail-item-checkout">
                                            <strong>Nhận phòng</strong>
                                            <p>09 thg 9, 16:20</p>
                                        </div>
                                        <div class="detail-item-checkout">
                                            <strong>Trả phòng</strong>
                                            <p>09 thg 9, 17:20</p>
                                        </div>
                                        <div class="detail-item-checkout">
                                            <strong>Thời gian lưu trú</strong>
                                            <p>1 giờ <span class="used-time-checkout">Đã sử dụng: 0 giờ</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row mb-3 justify-content-between">
                                        <div class="col-md-9">
                                        </div>
                                        <div class="col-md-3 text-end" style="padding: 0px">
                                            <div class="form-group " style="background: #ddd;border-radius:8px ">
                                                <div class="col-12  mt-2 d-flex justify-content-around">

                                                    <label class="fw-bold"> P.303 </label>

                                                    <p>150,000</p>
                                                </div>

                                                <div class="col-12 mt-2 mb-2 d-flex justify-content-around">
                                                    <label class="fw-bold">Khách đã trả</label>
                                                    <p>0</p>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-primary">Trả phòng</button>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- sắp tới  --}}
        <div class="modal fade" id="myModal-upcoming" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel-booking" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 1200px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel-booking">Chi tiết P.303</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="bookingForm">
                            <!-- Row: Labels -->
                            <div class="room-card-checkout">
                                <div class="room-header-checkout">
                                    <h4>Phòng 01 giường đơn</h4>
                                    <span class="status-label-checkout">Đã đặt trước</span>
                                    
                                   
                                </div>

                                <div class="room-details-checkout">
                                    <div class="detail-row-checkout">
                                        <div class="detail-item-checkout">
                                            <strong>Khách hàng</strong>
                                            <p>quang - 0382252561</p>
                                        </div>
                                        <div class="detail-item-checkout">
                                            <strong>Khách lưu trú</strong>
                                            <p>1 người lớn, 0 trẻ em, 0 giấy tờ</p>
                                        </div>
                                        <div class="detail-item-checkout">
                                            <strong>Mã đặt phòng</strong>
                                            <p>DP000020</p>
                                        </div>
                                    </div>

                                    <div class="detail-row-checkout">
                                        <div class="detail-item-checkout">
                                            <strong>Nhận phòng</strong>
                                            <p>09 thg 9, 16:20</p>
                                        </div>
                                        <div class="detail-item-checkout">
                                            <strong>Trả phòng</strong>
                                            <p>09 thg 9, 17:20</p>
                                        </div>
                                        <div class="detail-item-checkout">
                                            <strong>Thời gian lưu trú</strong>
                                            <p>1 giờ <span class="used-time-checkout">Đã sử dụng: 0 giờ</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row mb-3 justify-content-between">
                                        <div class="col-md-9">
                                        </div>
                                        <div class="col-md-3 text-end" style="padding: 0px">
                                            <div class="form-group " style="background: #ddd;border-radius:8px ">
                                                <div class="col-12  mt-2 d-flex justify-content-around">

                                                    <label class="fw-bold"> P.303 </label>

                                                    <p>150,000</p>
                                                </div>

                                                <div class="col-12 mt-2 mb-2 d-flex justify-content-around">
                                                    <label class="fw-bold">Khách đã trả</label>
                                                    <p>0</p>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-primary">Trả phòng</button>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal clean -->
        <div class="modal fade" id="dynamicModal" tabindex="-1" aria-labelledby="dynamicModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog1 ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="dynamicModalLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Nội dung modal sẽ được cập nhật bằng JS -->
                        <p id="modalRoomInfo">Đang tải dữ liệu...</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Đồng ý</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/global/css/receptionist.css') }}">
@endpush


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {

        const svgIcons = document.querySelectorAll('.room-icon');
        svgIcons.forEach(function(svg) {
            svg.addEventListener('click', function() {

                const roomData = this.getAttribute('data-room');
                const roomClean = this.getAttribute('data-clean');
                let textClean = '';

                if (roomClean == 1) {
                    textClean = '<strong style="color: #28a745;">Sạch</strong>';
                } else {
                    textClean = '<strong style="color: red;">Chưa dọn</strong>';
                }

                document.getElementById('dynamicModalLabel').innerText =
                    'Chuyển trạng thái buồng phòng';

                document.getElementById('modalRoomInfo').innerHTML =
                    'Chuyển trạng thái buồng phòng ' + roomData + ' thành ' + textClean;

                var modal = new bootstrap.Modal(document.getElementById('dynamicModal'));
                modal.show();
            });
        });
        // đặt phòng
        const roomBooking = document.querySelectorAll('.room-booking-status-dirty');
        roomBooking.forEach(function(booking) {
            booking.addEventListener('click', function() {
                // Hiển thị modal với ID mới
                var modal = new bootstrap.Modal(document.getElementById('myModal-booking'));
                modal.show();

                const dataHours = this.getAttribute('data-hours');
                const dataName = this.getAttribute('data-name');
                const dataroomNumber = this.getAttribute('data-roomNumber');
                const integerValue = parseInt(dataHours, 10);
                window.savedDataHours = this.getAttribute('data-hours');
                window.savedDataHours = window.savedDataHours.replace(',', '');

                window.savedDataDay = this.getAttribute('data-day');
                window.savedDataDay = window.savedDataDay.replace(',', '');

                window.savedDataNight = this.getAttribute('data-night');
                window.savedDataNight = window.savedDataNight.replace(',', '');

                document.getElementById('customer-price-booking').value = integerValue;
                document.getElementById('input-price-booking').value = integerValue;
                document.getElementById('book_name').innerText = dataName;
                document.getElementById('roomNumber').value = dataroomNumber;
            });
        });
        // trả phòng 
        const roomCheckOut = document.querySelectorAll('.room-booking-status-occupied');
        roomCheckOut.forEach(function(booking) {
            booking.addEventListener('click', function() {
                var modal = new bootstrap.Modal(document.getElementById('myModal-checkout'));
                modal.show();
            });
        });
        // sắp tới 
        const roomUpcoming = document.querySelectorAll('.room-booking-status-incoming');
        roomUpcoming.forEach(function(booking) {
            booking.addEventListener('click', function() {
                var modal = new bootstrap.Modal(document.getElementById('myModal-upcoming'));
                modal.show();
            });
        });
        var now = new Date();
        var year = now.getFullYear();
        var month = (now.getMonth() + 1).toString().padStart(2, '0');
        var day = now.getDate().toString().padStart(2, '0');
        var hours = now.getHours().toString().padStart(2, '0');
        var minutes = now.getMinutes().toString().padStart(2, '0');

        var currentDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
        var checkInTime = document.getElementById('checkInTime');
        checkInTime.value = currentDateTime;

        let bookingType;
        $('#bookingType').on('change', function() {
            bookingType = $(this).val();
            var now = new Date();

            var checkOutTime;
            if (bookingType === 'gio') {
                checkOutTime = new Date(now.getTime() + (1 * 60 * 60 * 1000)); // Cộng 1 giờ
            } else if (bookingType === 'ngay') {
                checkOutTime = new Date(now.getTime() + (1 * 24 * 60 * 60 * 1000)); // Cộng 1 ngày
            } else if (bookingType === 'dem') {
                checkOutTime = new Date(now.getTime() + (12 * 60 * 60 * 1000)); // Cộng 12 giờ
            }

            var checkOutYear = checkOutTime.getFullYear();
            var checkOutMonth = (checkOutTime.getMonth() + 1).toString().padStart(2, '0');
            var checkOutDay = checkOutTime.getDate().toString().padStart(2, '0');
            var checkOutHours = checkOutTime.getHours().toString().padStart(2, '0');
            var checkOutMinutes = checkOutTime.getMinutes().toString().padStart(2, '0');
            var checkOutDateTime =
                `${checkOutYear}-${checkOutMonth}-${checkOutDay}T${checkOutHours}:${checkOutMinutes}`;

            $('#checkOutTime').val(checkOutDateTime);
            calculateDuration();

        });

        function calculateDuration(event) {
           
            var checkInTime = $('#checkInTime').val();
            var checkOutTime = $('#checkOutTime').val();
            if (!checkInTime || !checkOutTime) {
                $('.inputTime').text('Vui lòng nhập cả thời gian check-in và check-out.');
                return;
            }

            var checkInDate = new Date(checkInTime);
            var checkOutDate = new Date(checkOutTime);

            var durationMs = checkOutDate - checkInDate;

            if (durationMs < 0) {
                $('.inputTime').text('Thời gian check-out phải sau thời gian check-in.');
                return;
            }


            var durationHours = Math.floor(durationMs / (1000 * 60 * 60));
            var durationMinutes = Math.floor((durationMs % (1000 * 60 * 60)) / (1000 * 60));


            var formattedHours = durationHours.toString().padStart(2, '0'); // giờ 
            var formattedMinutes = durationMinutes.toString().padStart(2, '0'); // phút
      
            let priceTime = 0;
            if(bookingType === 'gio'){
                priceTime = parseFloat(window.savedDataHours.replace(',', '')) || 0;
            }else if (bookingType === 'ngay') {
                priceTime = parseFloat(window.savedDataDay.replace(',', '')) || 0;
                updatedPrice = priceTime;
            } else if (bookingType === 'dem') {
                priceTime = parseFloat(window.savedDataNight.replace(',', '')) || 0;
                updatedPrice = priceTime;
            }
          
            
            if (bookingType !== 'dem' && bookingType !== 'ngay') {
                var updatedPrice = priceTime * formattedHours;
            }


            $('#input-price-booking').val(updatedPrice.toLocaleString());
            $('#customer-price-booking').val(updatedPrice.toLocaleString());

            $('.inputTime').text(formattedHours + ':' + formattedMinutes);
        }

        $('#checkInTime, #checkOutTime').on('change', calculateDuration);

    });
</script>

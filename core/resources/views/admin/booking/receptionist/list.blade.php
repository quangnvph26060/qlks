@extends('admin.layouts.app')
@section('panel')
    <div class="row">

    </div>
    <div class="row">
        <div class="col">
            <div class="status-buttons">
                <div class="status-button status-available-line" data-status="dirty">
                    <span class="status-dot"></span>
                    Đang trống (8)
                </div>
                <div class="status-button status-incoming-line" data-status="incoming">
                    <span class="status-dot"></span>
                    Sắp nhận (2)
                </div>
                <div class="status-button status-occupied-line" data-status="occupied">
                    <span class="status-dot"></span>
                    Đang sử dụng (4)
                </div>
                <div class="status-button status-checkout-line" data-status="late-checkin">
                    <span class="status-dot"></span>
                    Nhận phòng muộn (2)
                </div>
                <div class="status-button status-overdue-line" data-status="check-out">
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

            <div class="col-md-2 main-room-card  card-{{ $class }} ">
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
                        data-hours="{{ $rooms->roomType->hourly_rate }}" data-day="{{ $rooms->roomType->fare }}"
                        data-night="{{ $rooms->roomType->fare }}" data-name = "{{ $rooms->roomType->name }}"
                        data-roomNumber="{{ $rooms->room_number }}" data-room-type="{{ $rooms->room_type_id }}">
                        <h5>{{ $rooms->room_number }} </h5>
                        <p class="single-line">{{ $rooms->roomType->name }}</p>
                        <div class="room-info">
                            <p><i
                                    class="fas fa-clock icon"></i><span>{{ showAmount($rooms->roomType->hourly_rate) }}</span>
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

                $room = $booking->room;
                $classClean = $room->getCleanStatusClass();
                $classSvg = $room->getCleanStatusSvg();
                $cleanText = $room->getCleanStatusText();

                if (
                    now() > $booking->booking->check_in &&
                    now() <= $booking->booking->check_out &&
                    $booking->booking->status == 1 &&
                    $booking->booking->key_status == 1
                ) {
                    $class = 'status-occupied'; // đang hoạt động;
                } elseif (now() < $booking->booking->check_in && $booking->booking->status == 1) {
                    $class = 'status-incoming'; // sắp nhận
                } elseif ($booking->booking->check_out < now() && $booking->booking->key_status == 1) {
                    $class = 'status-check-out'; // trả phòng muộn
                } elseif (
                    now() >= $booking->booking->check_in &&
                    $booking->booking->status == 1 &&
                    $booking->booking->key_status == 0
                ) {
                    $class = 'status-late-checkin'; // nhận phòng muộn
                }
            @endphp
            <div class="col-md-2 main-room-card card-{{ $class }}">
                <div class="room-card {{ $class }}">
                    <div class="d-flex  justify-content-between align-items-center">
                        <div id="badgeChuaDon" class="badge {{ $classClean }}">

                            <img src="{{ asset('assets/svg/' . $classSvg . '.svg') }}" alt="Logo" />

                            <link rel="stylesheet" href="{{ asset('assets/svg/no_clean.svg') }}">
                            {{ $cleanText }}
                        </div>

                        <div>
                            <svg style="color: #161515" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 21 21" class="room-icon" data-room="{{ $booking->room_number }}"
                                style="cursor:pointer;" data-clean="1">
                                >
                                <g fill="currentColor" fill-rule="evenodd">
                                    <circle cx="10.5" cy="10.5" r="1" />
                                    <circle cx="10.5" cy="5.5" r="1" />
                                    <circle cx="10.5" cy="15.5" r="1" />
                                </g>
                            </svg>

                        </div>
                    </div>

                    <div class="content-booking mt-2 room-booking-{{ $class }}"
                        data-id="{{ $booking->booking_id }}" data-hours="{{ $booking->roomType->hourly_rate }}"
                        data-day="{{ $booking->roomType->fare }}" data-night="{{ $booking->roomType->fare }}"
                        data-name = "{{ $booking->roomType->name }} " data-roomNumber="{{ $booking->room_number }}"
                        data-room-type="{{ $booking->room_type_id }}" data-booking="{{ $booking->id }}">
                        <p> {{ $booking->booking->booking_number }}</p>
                        <p> {{ $booking->roomType->name }}</p>
                        <p> {{ $booking->booking->check_in }} - {{ $booking->booking->check_out }}</p>
                        <h3>{{ $booking->room->room_number }}</h3>
                        {!! $booking->booking->checkGuest() !!}

                        @php

                            $currentTime = now();
                            $checkInTime = $booking->booking->check_in;
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
                            <p class="single-line">{{ $booking->roomType->name }}</p>
                            <div class="room-info">
                                <p> <i class="fas fa-clock icon"></i>{{ showAmount($booking->roomType->hourly_rate) }}
                                </p>
                                <p> <i class="fas fa-sun icon"></i>{{ showAmount($booking->roomType->fare) }}
                                </p>
                                <p> <i class="fas fa-moon icon"></i>{{ showAmount($booking->roomType->fare) }}
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
                        <form>
                            <div class="d-flex ">
                                <div class="customer-input-container">
                                    <input id="customer-name" list="customer-names" type="text"
                                        class="customer-form-control" placeholder="Email khách hàng">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="customer-svg-icon" width="20"
                                        height="20" viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" d="M12 8v4m0 0v4m0-4h4m-4 0H8" />
                                            <circle cx="12" cy="12" r="10" />
                                        </g>
                                    </svg>

                                </div>
                                <div class="user-info-customer">
                                    <p class="email-user"></p>
                                    <p class="ms-2 me-2"> | </p>
                                    <p class="username-user"></p>
                                </div>
                            </div>
                            <datalist id="customer-names">
                                @forelse ($userList as $user)
                                    <option value="{{ $user->email }}" data-user="{{ $user->username }}"
                                        data-mobi="{{ $user->mobile }}" data-address="{{ $user->address }}">
                                    @empty
                                        <p>No items found.</p>
                                @endforelse
                            </datalist>
                            <p id="error-message" style="color: red; display: none;">Không tìm thấy email khách hàng phù
                                hợp
                            </p>
                        </form>

                        <!-- add customer  -->
                        <div id="customer-popup" class="customer-popup">
                            <div class="modal-header">
                                <h3>Thông tin khách hàng</h3>
                                <svg class="close-customer" id="customer-close" xmlns="http://www.w3.org/2000/svg"
                                    width="20" height="20" viewBox="0 0 24 24">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                        d="M20 20L4 4m16 0L4 20" />
                                </svg>
                            </div>
                            <form>
                                {{-- <label for="name">Loại khách hàng</label>
                                <select class="form-control" name="guest_type" id="guest_type">
                                    <option selected value="0">@lang('Khách lưu trú')</option>
                                    <option value="1">@lang('Khách đã đăng ký')</option>
                                </select> --}}

                                <label for="name">Tên khách hàng</label>
                                <input type="text" id="name" class="customer-input"
                                    placeholder="Nhập tên của bạn">

                                <label for="email">Email</label>
                                <input type="email" id="email" class="customer-input"
                                    placeholder="Nhập email của bạn">

                                <label for="phone">Số điện thoại</label>
                                <input type="text" id="phone" class="customer-input"
                                    placeholder="Nhập số điện thoại">

                                <label for="address">Địa chỉ</label>
                                <input type="text" id="address" class="customer-input"
                                    placeholder="Nhập địa chỉ của bạn">

                                <div class="btn-customer">
                                    <button type="button" id="customer-closePopup">Bỏ qua</button>
                                    <button type="button" class="btn btn-primary btn-user-info">Lưu</button>
                                </div>
                            </form>
                        </div>


                        <form id="bookingForm" action="{{ route('admin.room.book') }}" class="booking-form"
                            method="POST">

                            @csrf
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
                                            <th class="d-flex justify-content-between align-items-center">Dự kiến
                                                <span>Thành tiền</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <input name="room_type_id" class="room_type_id" type="text" hidden>
                                        <input type="text" class="username-user1" name="guest_name" hidden>
                                        <input type="text" class="email-user1" name="email" hidden>
                                        <input type="text" class="mobile-user" name="mobile" hidden>
                                        <input type="text" class="address-user" name="address" hidden>
                                        <input type="text" class="guest_type" name="guest_type" hidden>
                                        <tr>
                                            <td>
                                                <p id="book_name"></p>
                                            </td>
                                            <td><input type="text" class="form-control" id="roomNumber" disabled></td>
                                            <td>
                                                <select id="bookingType" class="form-select">
                                                    <option value="gio">Giờ</option>
                                                    <option value="ngay">Ngày</option>
                                                    <option value="dem">Đêm</option>
                                                </select>
                                            </td>
                                            <td><input type="datetime-local" class="form-control" name="checkInTime"
                                                    id="checkInTime"></td>
                                            <td><input type="datetime-local" class="form-control" name="checkOutTime"
                                                    id="checkOutTime"></td>
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
                                            <div class="form-group custom-box-price">
                                                <div class="col-12  mt-2 d-flex ">

                                                    <label class="fw-bold">Khách cần trả</label>
                                                    <input type="number" name="total_amount" class="custom-input"
                                                        id="customer-price-booking"
                                                        style="border-bottom: 1px solid #a89191 ; margin-left: 70px;">
                                                </div>

                                                <div class="col-12 mt-2 mb-2 d-flex ">
                                                    <label class="fw-bold payment-main">Khách thanh toán</label>
                                                    <input type="number" name="paid_amount" class="custom-input"
                                                        style="border-bottom: 1px solid #a89191 ; margin-left: 38px;">
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-primary btn-confirm">Đặt phòng</button>
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
        {{-- NHẬN PHÒNG MUỘN  --}}
        <div class="modal fade" id="myModal-late-checkin" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel-booking" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 1200px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel-booking">Chi tiết phòng <span class="booking-no"></span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="bookingForm">
                            <!-- Row: Labels -->
                            <div class="room-card-checkout">
                                {{-- <div class="room-header-checkout">
                                    <h4>Phòng 01 giường đơn</h4>
                                    <span class="status-label-checkout">Đã đặt trước</span>


                                </div> --}}

                                <div class="room-details-checkout">
                                    <div class="detail-row-checkout">
                                        <div class="detail-item-checkout">
                                            <strong>Khách hàng</strong>
                                            <p class="user_info"></p>
                                        </div>
                                        <div class="detail-item-checkout">
                                            <strong>Loại khách hàng</strong>
                                            <p class="customer_type"></p>
                                        </div>
                                        <div class="detail-item-checkout">
                                            <strong>Mã đặt phòng</strong>
                                            <p class="booking_number"></p>
                                        </div>
                                    </div>

                                    <div class="detail-row-checkout">
                                        <div class="detail-item-checkout">
                                            <strong>Nhận phòng</strong>
                                            <p class="check_in"></p>
                                        </div>
                                        <div class="detail-item-checkout">
                                            <strong>Trả phòng</strong>
                                            <p class="check_out"></p>
                                        </div>
                                        <div class="detail-item-checkout">
                                            <strong>Tổng phí</strong>
                                            <p class="booking_price"></p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="bookedRoomsHeading">
                                        <button aria-controls="bookedRooms" aria-expanded="true" class="accordion-button"
                                            data-bs-target="#bookedRooms" data-bs-toggle="collapse" type="button">
                                            @lang('Phòng đã đặt')
                                        </button>
                                    </h2>
                                    <div aria-labelledby="bookedRoomsHeading" class="accordion-collapse collapse show"
                                        data-bs-parent="#s" id="bookedRooms">
                                        <div class="accordion-body p-0">
                                            <div class="table-responsive--sm">
                                                <table class="custom--table table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">@lang('Đã đặt chỗ')</th>
                                                            <th>@lang('Loại phòng')</th>
                                                            <th>@lang('Phòng số')</th>
                                                            <th class="text-end">@lang('Giá') / @lang('Đêm')</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody id="bookings-table-body">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="premiumServiceHeading">
                                        <button aria-controls="premiumService" aria-expanded="false"
                                            class="accordion-button" data-bs-target="#premiumService"
                                            data-bs-toggle="collapse" type="button">
                                            @lang('Dịch vụ cao cấp')
                                        </button>
                                    </h2>
                                    <div aria-labelledby="premiumServiceHeading" class="accordion-collapse collapse show"
                                        data-bs-parent="#s" id="premiumService">
                                        <div class="accordion-body p-0">
                                            @if ($booking->usedPremiumService->count())
                                                <div class="table-responsive--sm">
                                                    <table class="custom--table head--base table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>@lang('Ngày')</th>
                                                                <th>@lang('Phòng số')</th>
                                                                <th>@lang('Dịch vụ')</th>
                                                                <th>@lang('Tổng')</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody id="user_services">
                                                           
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="text-center">
                                                    <h6 class="p-3">@lang('Không sử dụng dịch vụ bổ sung')</h6>
                                                </div>
                                            @endif
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
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Đồng ý 213</button>
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
        // choose option  rooms
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

                document.getElementById('dynamicModalLabel').innerHTML =
                    'Chuyển trạng thái buồng phòng ' + roomData + ' thành ' + textClean;

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
                $('[name=room_type_id]').val(this.getAttribute('data-room-type'));

                window.savedDataDay = this.getAttribute('data-day'); // giá ngày
                window.savedDataDay = window.savedDataDay.replace(',', '');

                window.savedDataNight = this.getAttribute('data-night'); // giá đêm
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
                const id = this.getAttribute('data-id');
            });
        });
        // nhận phòng muộn
        $('.room-booking-status-late-checkin').on('click', function() {

            var id = $(this).data('id');
            var booking_id = $(this).data('booking');
            var url = `{{ route('admin.booking.details', ['id' => ':id']) }}`.replace(':id', id);

            var dataToSend = {
                is_method: 'receptionist'
            };
            $.ajax({
                url: url,
                data: dataToSend,
                type: 'GET',
                success: function(response) {
                    console.log(
                        response.data
                    );


                    if (response.status === 'success') {
                        // booking_number
                        var customer_type = response.data.user_id ?
                            "Khách hàng đã đăng ký" : " Khách hàng lưu trú"

                        if (customer_type) {
                            var url_user_info =
                                `{{ can('admin.users.detail') ? route('admin.users.detail', ['id' => ':id']) : 'javascript:void(0)' }}`
                                .replace(':id', response.data.user_id);

                            $.ajax({
                                url: url_user_info,
                                data: dataToSend,
                                type: 'GET',
                                success: function(response) {
                                    if (response.status === 'success') {

                                        $('.user_info').text(
                                            `${response.data.username} - ${response.data.email}`
                                        );
                                    }

                                },
                                error: function(xhr, status, error) {

                                    $('.user_info').text('N/A');

                                    console.error('Error:', error);

                                }
                            });
                        }

                        $('#bookings-table-body').empty();
                        let rowsHtml = '';
                        response.data.booked_rooms.forEach(function(booked, index) {
                            if (booked.id === booking_id) {
                                $('.booking-no').text(booked.room.room_number);
                                rowsHtml += `
                                        <tr>
                                            ${index === 0 ? `<td class="bg--date text-center" rowspan="${response.data.booked_rooms.length}">
                                                ${booked.booked_for}
                                            </td>` : ''}
                                            <td class="text-center" data-label="@lang('Loại phòng')">
                                                ${booked.room.room_type.name}
                                            </td>
                                            <td data-label="@lang('Room No.')">
                                                ${booked.room.room_number}
                                                ${booked.status === 'canceled' ? `<span class="text--danger text-sm">(@lang('Đã hủy'))</span>` : ''}
                                            </td>
                                            <td class="text-end" data-label="@lang('Giá')">
                                                ${booked.fare}
                                            </td>
                                        </tr>
                                    `;
                            }

                        });
                        $('#user_services').empty();
                        let rowsHtml1 = '';
                        response.data.used_premium_service.forEach(function(booked, index) {
                           
                            //    $('.booking-no').text(booked.room.room_number);
                                rowsHtml1 += `
                                        <tr>
                                            ${index === 0 ? `<td class="bg--date text-center" rowspan="${response.data.used_premium_service.length}">
                                                ${booked.service_date}
                                            </td>` : ''}
                                            <td class="text-center" data-label="@lang('Loại phòng')">
                                                ${booked.room.room_type.name}
                                            </td>
                                            <td data-label="@lang('Room No.')">
                                                ${booked.room.room_number}
                                                ${booked.status === 'canceled' ? `<span class="text--danger text-sm">(@lang('Đã hủy'))</span>` : ''}
                                            </td>
                                            <td class="text-end" data-label="@lang('Giá')">
                                                ${booked.fare}
                                            </td>
                                        </tr>
                                    `;
                            

                        });
                        // Chèn nội dung mới vào tbody
                        $('#user_services').append(rowsHtml1);
                        $('#bookings-table-body').append(rowsHtml);
                        $('.customer_type').text(customer_type);
                        $('.booking_number').text(response.data.booking_number);
                        $('.check_in').text(response.data.check_in);
                        $('.check_out').text(response.data.check_out);
                        $('.booking_price').text(response.data.booked_rooms.fare);

                        $('#myModal-late-checkin').modal('show');

                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);

                }
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
                checkOutTime = new Date(now.getTime() + (1 * 24 * 60 * 60 *
                    1000)); // Cộng 1 ngày
            } else if (bookingType === 'dem') {
                checkOutTime = new Date(now.getTime() + (12 * 60 * 60 *
                    1000)); // Cộng 12 giờ
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
            console.log(checkInDate.toLocaleDateString() + " - " + checkOutDate
                .toLocaleDateString());
            console.log($('.room_type_id').val());


            // array (
            //     'room_type' => '2',
            //     'date' => '09/11/2024 - 09/25/2024',
            //     'rooms' => '3',
            //     ) 

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
            if (bookingType === 'gio') {
                priceTime = parseFloat(window.savedDataHours.replace(',', '')) || 0;
            } else if (bookingType === 'ngay') {
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


        $('.customer-svg-icon').on('click', function() {
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
        $('#customer-name').on('input', function() {
            const inputValue = $(this).val().toLowerCase();
            const options = $('#customer-names option');
            let isValid = false;


            options.each(function() {
                if ($(this).val().toLowerCase() === inputValue) {
                    isValid = true;

                    return false; // Break the loop
                }
            });

            if (isValid) {
                $('#error-message').hide();


            } else {
                $('#error-message').show();
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
                $('.email-user').text(matchedOption.data('user'));


                $('.email-user1').val(matchedOption.val());
                $('.username-user1').val(matchedOption.data('user'));
                $('.mobile-user').val(matchedOption.data('mobi'));
                $('.address-user').val(matchedOption.data('address'));
            } else {
                // Clear the user info if no match
                $('.username-user').text('');
                $('.email-user').text('');
            }
        });
        // btn booking
        $('.btn-confirm').on('click', function() {

            $('.booking-form').submit();
        });

        function getDatesBetween(startDate, endDate, roomType) {
            let dates = [];
            let currentDate = new Date(startDate);
            endDate = new Date(endDate);

            while (currentDate <= endDate) {

                let formattedDate =
                    `${currentDate.getMonth() + 1}/${String(currentDate.getDate()).padStart(2, '0')}/${currentDate.getFullYear()}`;
                dates.push(`${roomType}-${formattedDate}`);


                currentDate.setDate(currentDate.getDate() + 1);
            }

            return dates;
        }
        $('.booking-form').on('submit', function(e) {
            e.preventDefault();
            // Thu thập dữ liệu từ biểu mẫu
            let formData = $(this).serializeArray();

            // Chuyển đổi dữ liệu biểu mẫu thành đối tượng JavaScript
            let formObject = {};
            formData.forEach(function(field) {
                formObject[field.name] = field.value;
            });
            // Chuyển đổi đối tượng thành chuỗi truy vấn URL
            let queryString = $.param(formObject);



            const params = new URLSearchParams(queryString);


            const checkInTime = params.get('checkInTime');
            const checkOutTime = params.get('checkOutTime');


            if (!checkInTime || !checkOutTime) {
                let missingField = !checkInTime ? 'nhận phòng' : 'trả phòng';
                notify('error', `Vui lòng chọn thời gian ${missingField}`);
                return false;
            }


            const startDate = new Date(checkInTime);
            const endDate = new Date(checkOutTime);
            const roomDates = getDatesBetween(startDate, endDate, params.get(
                'room_type_id'));
            console.log(roomDates);

            roomDates.forEach(function(date, index) {
                formData.push({
                    name: 'room[]',
                    value: date
                });
            });
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

            let url = $(this).attr('action');
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
                    } else {
                        notify('error', response.error);
                    }
                },
            });
        });

        //
        $('.btn-user-info').on('click', function() {
            // Lấy giá trị của guest_type
            //    let guestType = $('#guest_type').val();



            let name = $('#name').val();
            let email = $('#email').val();
            let phone = $('#phone').val();
            let address = $('#address').val();


            $('.email-user').text(email);

            $('.username-user').text(name);
            $('.guest_type').val(0);

            $('.username-user1').val(name);
            $('.email-user1').val(email);
            $('.mobile-user').val(phone);
            $('.address-user').val(address);
            $('#customer-popup').removeClass('active');


        });
    });
</script>

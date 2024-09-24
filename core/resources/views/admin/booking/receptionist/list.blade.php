@extends('admin.layouts.app')
@section('panel')
    <div class="row">

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
        <!-- Room P.201 -->
        @forelse($emptyRooms as $rooms)
            @php
                $classClean = $rooms->getCleanStatusClass();
                $classSvg = $rooms->getCleanStatusSvg();
                $cleanText = $rooms->getCleanStatusText();
                $class = 'status-dirty';
                if ($rooms->status == 1) {
                    $class = 'status-occupied'; // đang hoạt động; sắp tới
                }
            @endphp

            <div class="col-md-2 main-room-card  card-{{ $class }} ">
                <div class="room-card  {{ $class }}">

                    <x-room-badge styleClass="" isClean="{{ $rooms->is_clean }}" classClean="{{ $classClean }}"
                        classSvg="{{ $classSvg }}" cleanText="{{ $cleanText }}"
                        roomNumber="{{ $rooms->room_number }}" />



                    <div class="content-booking mt-2 room-booking-{{ $class }}"
                        data-hours="{{ $rooms->roomType->hourly_rate }}" data-day="{{ $rooms->roomType->fare }}"
                        data-night="{{ $rooms->roomType->fare }}" data-name = "{{ $rooms->roomType->name }}"
                        data-roomNumber="{{ $rooms->room_number }}" data-room-type="{{ $rooms->room_type_id }}"
                        data-room="{{ $rooms->id }}">
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
                    $class = 'status-check-out'; // quá giờ trả
                } elseif (
                    now() >= $booking->booking->check_in &&
                    $booking->booking->status == 1 &&
                    $booking->booking->key_status == 0
                ) {
                    $class = 'status-late-checkin'; // nhận phòng muộn
                }
                $key_status = false;
                if (
                    now()->format('Y-m-d') >= $booking->booking->check_in &&
                    now()->format('Y-m-d') < $booking->booking->check_out &&
                    $booking->booking->key_status == Status::DISABLE
                ) {
                    $key_status = true;
                }
            @endphp
            <div class="col-md-2 main-room-card card-{{ $class }}">
                <div class="room-card {{ $class }}">

                    <x-room-badge styleClass="dropdown" isClean="{{ $booking->room->is_clean }}"
                        classClean="{{ $classClean }}" classSvg="{{ $classSvg }}" cleanText="{{ $cleanText }}"
                        roomNumber="{{ $booking->room->room_number }}" keyStatus="{{ $key_status }}"
                        key="{{ $booking->booking->key_status }}" bookingId="{{ $booking->booking_id }}" />

                    <div class="content-booking mt-2 room-booking-{{ $class }}"
                        data-id="{{ $booking->booking_id }}" data-hours="{{ $booking->roomType->hourly_rate }}"
                        data-day="{{ $booking->roomType->fare }}" data-night="{{ $booking->roomType->fare }}"
                        data-name = "{{ $booking->roomType->name }} " data-roomNumber="{{ $booking->room->room_number }}"
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
        <div class="modal fade" id="myModal-booking" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-booking"
            aria-hidden="true">
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
                        @include('admin.booking.partials.add_customer_booking')

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
                                        <input type="text" class="room_type_id" name="room_type_id"hidden>
                                        <input type="text" class="room_type" name="room_type"hidden>
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
                                            <button type="button" class=" btn-primary-2 btn-confirm">Đặt phòng</button>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.booking.partials.modal_extraChargeModal')
        {{-- NHẬN PHÒNG MUỘN  --}}
        <div class="modal fade" id="myModal-booking-status" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel-booking" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 1200px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel-booking">Chi tiết phòng <span class="booking-no"></span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

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
                                    {{-- <div class="detail-item-checkout">
                                            <strong>Tổng phí</strong>
                                            <p class="booking_price"></p>
                                        </div> --}}
                                </div>
                            </div>

                        </div>
                        @include('admin.booking.partials.table-booking')
                        <div class="row">
                            <div class="accordion-item">
                                <div class="d-flex justify-content-between mt-2 mb-2">
                                    <h2 class="accordion-header" id="premiumServiceHeading">
                                        <button aria-controls="premiumService" aria-expanded="false"
                                            class="accordion-button" data-bs-target="#premiumService"
                                            data-bs-toggle="collapse" type="button">
                                            @lang('Dịch vụ cao cấp ')
                                        </button>
                                    </h2>
                                    <div>
                                        <a href="javascript:void(0)" class=" btn-primary-service add_premium_service"> <i
                                                class="las la-plus-circle"></i> Thêm dịch vụ cao cấp</a>
                                    </div>
                                </div>
                                <div aria-labelledby="premiumServiceHeading" class="accordion-collapse collapse show"
                                    data-bs-parent="#s" id="premiumService">
                                    <div class="accordion-body p-0">

                                        <div class="table-responsive--sm">
                                            <table class="custom--table head--base table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('Ngày')</th>
                                                        <th>@lang('Phòng số')</th>
                                                        <th>@lang('Dịch vụ')</th>
                                                        <th>@lang('Số lượng')</th>
                                                        <th>@lang('Giá trị')</th>
                                                        <th>@lang('Tổng')</th>
                                                    </tr>
                                                </thead>

                                                <tbody id="user_services">

                                                </tbody>
                                            </table>
                                        </div>

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

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mt-3 mb-3">
                                                <h5 class="card-title">@lang('Tóm tắt thanh toán')</h5>
                                                <div>
                                                    {{-- data-id="{{ $booking->id }}" --}}
                                                    <button class="btn btn--success extraChargeBtn" data-type="add">
                                                        <i class="las la-plus-circle"></i>@lang('Thêm phí bổ sung')
                                                    </button>
                                                    {{-- data-id="{{ $booking->id }}" --}}
                                                    <button class="btn btn--danger extraChargeBtn" data-type="subtract"><i
                                                            class="las la-minus-circle"></i>@lang('Trừ Phí Thêm')</button>
                                                </div>
                                            </div>
                                            <div class="list">
                                                <div class="list-item">
                                                    <span>@lang('Thanh toán')</span>
                                                    <span class="total_fare"></span>
                                                </div>

                                                <div class="list-item">
                                                    <span>@lang('Đã nhận được thanh toán')</span>
                                                    <span class="total_received"> </span>
                                                </div>

                                                <div class="list-item">
                                                    <span>@lang('Đã hoàn tiền')</span>
                                                    <span class="total_refunded"></span>
                                                </div>

                                                <div class="list-item fw-bold">
                                                    <span id="dueMessage">@lang('Phải thu từ người dùng')</span>
                                                    <span id="customer_payment"></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <form method="post">

                                                    <h5 class="card-title" id="dueMessage1"></h5>
                                                    <div id="color_payment">
                                                        <span id="number_fare"></span> <span
                                                            id="customer_payment1"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>@lang('Nhập số tiền')</label>
                                                        <div class="input-group">

                                                            <input class="form-control input_fare_booking" min="0"
                                                                name="amount" id="amount_payment" required
                                                                step="any" type="number">
                                                            <span class="input-group-text">{{ __(gs()->cur_text) }}</span>
                                                        </div>
                                                        <div class="input-group">
                                                            <span id="amount_payment_errors" class="d-error"></span>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="booking_id" id="booking_id">

                                                    <button type="submit" class="btn btn-primary" id="submitBtn">Trả
                                                        phòng</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

        @include('admin.booking.partials.system-2')

        @include('admin.booking.partials.clean_modal')

        @include('admin.booking.partials.table-service')

        @include('admin.booking.partials.modal-cancel-booking')

    </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/global/css/receptionist.css') }}">
@endpush

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>
@endpush

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

        // $('.more_select').on('click', function() {

        // });
        // add_premium_service
        $('.add_premium_service').on('click', function(event) {
            event.stopPropagation();
            $('#serviceModal').modal('show');
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

        $('.service-wrapper').on('click', '.removeServiceBtn', function() {
            $(this).closest('.service-item').remove();
        });

        $('.handoverKeyBtn').on('click', function(event) {
            let data = $(this).data();

            event.stopPropagation();
            var url = `{{ route('admin.booking.key.handover', ['id' => ':id']) }}`.replace(':id', data
                .id);

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    is_method: "receptionist"
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

        $('.room-icon').on('click', function() {
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
                        }


                    },
                    error: function(xhr, status, error) {

                        console.error('Lỗi:', error);
                    }
                });
            });
            modal.show();
        });

        $('.room-booking-status-dirty').on('click', function() {
            const modal = new bootstrap.Modal($('#myModal-booking')[0]);
            modal.show();

            const dataHours = $(this).data('hours').replace(',', '');
            const dataName = $(this).data('name');
            const dataRoomType = $(this).data('room-type');
            const dataRoom = $(this).data('room');
            const dataRoomNumber = $(this).data('roomnumber');


            const dataDay = $(this).data('day').replace(',', '');
            const dataNight = $(this).data('night').replace(',', '');

            $('[name=room_type_id]').val(dataRoomType);
            $('[name=room_type]').val(dataRoom);
            $('#customer-price-booking, #input-price-booking').val(parseInt(dataHours, 10));
            $('#book_name').text(dataName);
            $('#roomNumber').val(dataRoomNumber);

            window.savedDataHours = dataHours;
            window.savedDataDay = dataDay;
            window.savedDataNight = dataNight;
        });

        $('.room-booking-status-occupied').on('click', function() {
            var id = $(this).data('id'); 
            var booking_id = $(this).data('booking');   
            handleLateCheckinClick(id, booking_id); 
        });

        $('.room-booking-status-incoming').on('click', function() {
            var id = $(this).data('id'); 
            var booking_id = $(this).data('booking');   
            handleLateCheckinClick(id, booking_id); 
        });

        $('.room-booking-status-late-checkin').on('click', function() {
            var id = $(this).data('id'); 
            var booking_id = $(this).data('booking');   
            handleLateCheckinClick(id, booking_id); 
        });

        $('.room-booking-status-check-out').on('click', function() {
            var id = $(this).data('id'); 
            var booking_id = $(this).data('booking');   
            handleLateCheckinClick(id, booking_id); 
        });

        function handleLateCheckinClick(id, booking_id) {
           
            var url = `{{ route('admin.booking.details', ['id' => ':id']) }}`.replace(':id', id);
            $('#booking_id').val(id);
            var dataToSend = {
                is_method: 'receptionist'
            };
            fetchDataAndDisplayModal(url, dataToSend);
        }

        function fetchDataAndDisplayModal(url, dataToSend) {
            $.ajax({
                url: url,
                data: dataToSend,
                type: 'GET',
                success: function(response) {
                    if (response.status === 'success') {

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

                                    $('.user_info').text(
                                        `${response.data.guest_details.name} - ${response.data.guest_details.email}`
                                    );

                                    // console.error('Error:', error);
                                }
                            });
                        }

                        $('#bookings-table-body').empty();
                        let rowsHtml = '';
                        let totalFare = 0;
                        let total_fare = 0;
                        let cancellation_fee, shouldRefund = 0;

                        var currentDate = '<?php echo now()->format('Y-m-d H:i:s'); ?>';

                        response.data.booked_rooms.forEach(function(booked, index) {
                            $('.booking-no').text(booked.room.room_number);
                            $('.room_serive').val(booked.room.room_number);
                            let is_flag = false;


                            if (booked.status === 1 && booked.booked_for >= currentDate) {
                                total_fare = booked.fare;
                                cancellation_fee = booked.cancellation_fee;
                            } else {
                                is_flag = true;
                            }
                            rowsHtml += `
                                    <tr>
                                        <td  class="text-center " data-label="@lang('Hành động')">
                                           <button
                                                ${is_flag ? "disabled" : ""}
                                                data-id="${booked.booking_id}"
                                                data-booked_for="${booked.booked_for}" 
                                                data-fare="${ formatCurrency(total_fare) }" 
                                                data-should_refund="${ formatCurrency(total_fare  - cancellation_fee) }" 
                                                class="btn btn--danger cancelBookingBtn" 
                                                type="button">
                                                @lang('Hủy đặt phòng')
                                            </button>
                                        </td>
                                        <td class="bg--date text-center" data-label="@lang('Đã đặt chỗ')">
                                            ${booked.booked_for}
                                        </td>
                                        <td class="text-center" data-label="@lang('Loại phòng')">
                                            ${booked.room.room_type.name}
                                        </td>
                                        <td data-label="@lang('Số phòng.')">
                                            ${booked.room.room_number}
                                            ${booked.status === 'canceled' ? `<span class="text--danger text-sm">(@lang('Đã hủy'))</span>` : ''}
                                        </td>
                                        <td class="text-end" data-label="@lang('Giá')">
                                            ${formatCurrency(booked.fare)}
                                        </td>

                                    </tr>
                                `;
                            totalFare += parseFloat(booked.fare);

                        });
                        rowsHtml += `
                            <tr>
                                <td class="text-end" colspan="3">
                                    <span class="fw-bold">@lang('Tổng giá')</span>
                                </td>

                                <td class="fw-bold text-end">
                                    ${ formatCurrency(totalFare) } 
                                </td>
                            </tr>
                        `;
                        $('#bookings-table-body').append(rowsHtml);

                        $('#user_services').empty();

                        let rowsHtml1 = '';
                        response.data.used_premium_service.forEach(function(booked,
                            index) {
                            rowsHtml1 += `
                                        <tr>
                                            <td class="bg--date text-center" data-label="@lang('Ngày')">
                                                ${booked.service_date}
                                            </td>
                                             <td data-label="@lang('Phòng số')">
                                                 ${booked.room.room_number}
                                            </td>
                                            <td class="text-center" data-label="@lang('Dịch vụ')">
                                                ${booked.premium_service.name}
                                            </td>
                                              <td class="text-center" data-label="@lang('Số lượng')">
                                                ${booked.qty}
                                            </td>
                                           
                                            <td class="text-end" data-label="@lang('Giá')">
                                                ${formatCurrency(booked.premium_service.cost)}
                                            </td>
                                            <td class="text-end" data-label="@lang('Tổng giá')">
                                                  ${formatCurrency( booked.qty * booked.premium_service.cost)}
                                            </td>
                                        </tr>
                                    `;


                        });
                        $('#user_services').append(rowsHtml1);

                        $('.customer_type').text(customer_type);
                        $('.booking_number').text(response.data.booking_number);
                        $('.check_in').text(response.data.check_in);
                        $('.check_out').text(response.data.check_out);
                        $('.booking_price').text(response.data.booked_rooms.fare);

                        const paid_amount = formatCurrency(response.data.paid_amount);
                        $('.total_received').text("-" + paid_amount);
                        $('.total_refunded').text(response.returnedPayments);
                        const total_amount = formatCurrency(response.total_amount)
                        $('.total_fare').text("+" + total_amount);
                        if (response.due > 0) {
                            $('#number_fare').text('Số tiền phải thu: ');

                            $('#color_payment').addClass('text--success');
                            $('#dueMessage1').text("@lang('Nhận thanh toán')");
                            $('#dueMessage').text("@lang('Phải thu từ người dùng')");

                            const due = formatCurrency(response.due);
                            $('#customer_payment, #customer_payment1').text(due);
                        } else {
                            $('#number_fare').text('Số tiền hoàn lại: ');

                            $('#dueMessage1').text("@lang('Số tiền hoàn lại')");
                            $('#dueMessage').text("Có thể hoàn trả");

                            const due = formatCurrency(response.due);
                            $('#customer_payment1').text(due);
                        }
                        $('.btn-primary').on('click', function() {
                            let inputFareBooking = $('.input_fare_booking')
                                .val();
                            if (inputFareBooking === "") {
                                showSwalMessage('error',
                                    'Vui lòng nhập số tiền');
                                return;
                            }
                        });

                        $('#myModal-booking-status').modal('show');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);

                }
            });
        }
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
                    `${currentDate.getMonth() + 1}/${String(currentDate.getDate()).padStart(2, '0')}/${currentDate.getFullYear()} ` +
                    `${String(currentDate.getHours()).padStart(2, '0')}:${String(currentDate.getMinutes()).padStart(2, '0')}:${String(currentDate.getSeconds()).padStart(2, '0')}`;

                dates.push(`${roomType}-${formattedDate}`);


                currentDate.setDate(currentDate.getDate() + 1);
            }

            return dates;
        }
        $('.booking-form').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serializeArray();


            let formObject = {};
            formData.forEach(function(field) {
                formObject[field.name] = field.value;
            });

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

            const roomDates = getDatesBetween(startDate, endDate, params.get('room_type'));

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
                        $('#myModal-booking').hide();
                        window.location.reload();
                    } else {
                        notify('error', response.error);
                    }
                },
            });
        });

      
        $('.btn-user-info').on('click', function(event) {
            // Lấy giá trị của guest_type
            //    let guestType = $('#guest_type').val();
            event.stopPropagation();


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
        $('.extraChargeBtn').on('click', function() {


            let data = $(this).data();
            let modal = $('#extraChargeModal');
            modal.find('.modal-title').text($(this).text());
            if (data.type == 'add') {
                modal.find('form').attr('action',
                    `{{ route('admin.booking.extra.charge.add', '') }}/${data.id}`);
                modal.find('[name=type]').val('add');
            } else {
                modal.find('form').attr('action',
                    `{{ route('admin.booking.extra.charge.subtract', '') }}/${data.id}`
                );
                modal.find('[name=type]').val('subtract');
            }
            modal.modal('show');
        });

    });



    function formatCurrency(amount) {
        const parts = amount.toString().split('.');
        const integerPart = parts[0];
        const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        return formattedInteger + ' ' + 'VND';
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
    // tất cả các dịch vụ cao cấp 
    $(document).ready(function() {
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
        let serviceForm = $('.add-service-form');

        serviceForm.on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();
            let url = $(this).attr('action');

            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                success: function(response) {
                    if (response.success) {
                        notify('success', response.success);
                        let firstItem = $(
                            '.first-service-wrapper .service-item');
                        $(document).find('.service-wrapper').find(
                            '.service-item').not(
                            firstItem).remove();
                        serviceForm.trigger("reset");
                        $('#serviceModal').hide();
                    } else {
                        $.each(response.error, function(key, value) {
                            notify('error', value);
                        });
                    }
                },
            });
        });
    });

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
    $(document).ready(function() {
        $('#submitBtn').click(function(e) {
            e.preventDefault();

            var booking_id = $('#booking_id').val();
            var amount = $('#amount_payment').val();

            $('#amount_payment_errors').text('');
            if (amount > 0) {
                var url = `{{ route('admin.booking.payment', ['id' => ':id']) }}`.replace(':id', booking_id);

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        amount: amount,
                        is_method: "receptionist"
                    },
                    success: function(response) {
                       if(response.status === 'success'){
                       console.log("booking id " + response.booking_id); 
                       console.log(" id " + response.id);
                       handleLateCheckinClick(response.id , response.booking_id)
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
    });
</script>

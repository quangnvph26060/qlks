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
        <div id="empty-rooms" class="row">
            @include('admin.booking.partials.empty_rooms', ['dataRooms' => $dataRooms ?? []])
        </div>
    </div>
    <div class="row" id="roomListContainer">

        @include('admin.booking.partials.empty_rooms', ['dataRooms' => $emptyRooms ?? []])
        {{-- 123 --}}
        {{-- @include('admin.booking.partials.booked_rooms', ['bookings' => $bookings ?? []]) --}}

    </div>
    <!-- modal dặt hàng  -->
    <div class="modal fade" id="myModal-booking" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-booking"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 1200px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel-booking">Đặt phòng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="d-flex justify-content-between">
                            <div class="customer-input-container">
                                <input id="customer-name" list="customer-names" type="text" class="customer-form-control"
                                    placeholder="Email khách hàng">

                                <div class="d-flex customer-svg-icon" style="gap: 5px">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="camera-svg-icon-add" width="20"
                                        height="20" viewBox="0 0 1024 1024">
                                        <path fill="currentColor"
                                            d="M928 224H780.816L704 96H320l-76.8 128H96c-32 0-96 32-96 95.008V832c0 53.008 48 96 89.328 96H930c42 0 94-44.992 94-94.992V320c0-32-32-96-96-96zm32 609.008c0 12.624-20.463 30.288-29.999 31.008H89.521c-7.408-.609-25.52-15.04-25.52-32.016V319.008c0-20.272 27.232-30.496 32-31.008h183.44l76.8-128h313.647l57.12 96.945l17.6 31.055H928c22.56 0 31.68 29.472 32 32v513.008zM512.001 320c-123.712 0-224 100.288-224 224s100.288 224 224 224s224-100.288 224-224s-100.288-224-224-224zm0 384c-88.224 0-160-71.776-160-160s71.776-160 160-160s160 71.776 160 160s-71.776 160-160 160z" />
                                    </svg>

                                    <input type="file" class="file-upload-input" id="fileUpload">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="customer-svg-icon-add" width="20"
                                        height="20" viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" d="M12 8v4m0 0v4m0-4h4m-4 0H8" />
                                            <circle cx="12" cy="12" r="10" />
                                        </g>
                                    </svg>
                                </div>




                            </div>
                            <div class="user-info-customer">
                                <p class="email-user"></p>
                                <p class="ms-2 me-2 clear-main"> </p>
                                <p class="username-user"></p>
                            </div>
                            {{-- <div>
                                <select name="" id="model">
                                    <option value="">Chọn mô hình</option>
                                    <option value="1">Khách sạn</option>
                                    <option value="2">Khu nghỉ dưỡng</option>
                                </select>
                            </div> --}}
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

                    <form id="bookingForm" action="{{ route('admin.room.book') }}" class="booking-form" method="POST">

                        @csrf
                        <!-- Row: Labels -->
                        <div class="table-responsive">
                            <table class="table mobi-table">
                                <thead>
                                    <tr class="text-center fw-bold main-booking-modal">
                                        <th>Hạng phòng</th>
                                        <th>Phòng</th>
                                        <th>Hình thức</th>
                                        <th class="d-flex gap-10">Nhận <span class="main-hour-out" id="hour_current">Hiện
                                                tại</span> <span class="main-hour-out" id="hour_regulatory">Quy
                                                định</span></th>
                                        {{-- <th>Trả phòng</th> --}}
                                        {{-- <th class="d-flex justify-content-between align-items-center">Dự kiến
                                            <span>Thành tiền</span>
                                        </th> --}}
                                    </tr>
                                </thead>
                                <input type="text" class="room_type_id" name="room_type_id"hidden>
                                <input type="text" class="room_type" name="room_type"hidden>
                                <input type="text" class="username-user1" name="guest_name" hidden>
                                <input type="text" class="email-user1" name="email" hidden>
                                <input type="text" class="mobile-user" name="mobile" hidden>
                                <input type="text" class="address-user" name="address" hidden>
                                <input type="text" class="guest_type" name="guest_type" hidden>
                                <tbody id="list-booking">

                                    <tr>
                                        <td>
                                            <p id="book_name" class="book_name"></p>
                                        </td>
                                        <td>
                                            <select name="" id="roomNumber">
                                            </select>
                                        </td>
                                        <td style="display: flex; justify-content: center">
                                            <select id="bookingType" class="form-select " name="optionRoom"
                                                style="width: 110px;">
                                                <option value="gio">Giờ</option>
                                                <option value="ngay">Ngày</option>
                                                <option value="dem">Đêm</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="d-flex" style="gap: 10px">
                                                <input type="date" name="checkInDate" class="form-control"
                                                    id="date-book-room" style="width: 165px;">
                                                <input type="time" name="checkInTime" class="form-control"
                                                    id="time-book-room" style="width: 135px;">
                                            </div>
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
                                        <div class="d-flex" style="flex-direction: column; gap:20px">
                                            <p class="add-room-booking">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24">
                                                    <g fill="currentColor" fill-rule="evenodd" clip-rule="evenodd">
                                                        <path
                                                            d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12Zm10-8a8 8 0 1 0 0 16a8 8 0 0 0 0-16Z" />
                                                        <path
                                                            d="M13 7a1 1 0 1 0-2 0v4H7a1 1 0 1 0 0 2h4v4a1 1 0 1 0 2 0v-4h4a1 1 0 1 0 0-2h-4V7Z" />
                                                    </g>
                                                </svg>
                                                Chọn thêm phòng
                                            </p>
                                            <div class="modal fade" id="addRoomModal" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Chọn Phòng</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
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
                                            <div class="d-flex justify-content-flex-start align-items-end mt-2"
                                                style="gap: 10px">
                                                <label>Ghi chú</label>
                                                <input name="ghichu" class="input-ghichu"
                                                    placeholder="Nhập ghi chú..."></input>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-end datphong">
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
                                    <div class="d-flex">
                                        <p class="check_out"></p>
                                        <p class="text-checkoutlate"></p>
                                    </div>
                                </div>
                                <div class="detail-item-checkout">
                                    <strong>Hình thức</strong>
                                    <p class="option">Giờ</p>
                                </div>
                                {{-- <div class="detail-item-checkout">
                                            <strong>Tổng phí</strong>
                                            <p class="booking_price"></p>
                                        </div> --}}
                            </div>
                        </div>

                    </div>
                    {{-- danh sách phòng đặt  --}}
                    @include('admin.booking.partials.table-booking')
                    {{-- danh sách dịch vụ  --}}
                    <div class="row" id="product_room">
                        @include('admin.booking.partials.system-3')
                    </div>

                    {{-- danh sách sản phẩm  --}}
                    <div class="row mt-2">
                        @include('admin.booking.partials.system-4')
                    </div>

                    <hr>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row mb-3 justify-content-between">
                                <div class="col-md-9">
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mt-3 mb-3 coloumn-mobi">
                                            <h5 class="card-title">@lang('Tóm tắt thanh toán')</h5>
                                            <div>
                                                {{-- data-id="{{ $booking->id }}" --}}
                                                <button class="btn btn--success extraChargeBtn" data-type="add">
                                                    <i class="las la-plus-circle"></i>@lang('Thêm phí bổ sung')
                                                </button>
                                                {{-- data-id="{{ $booking->id }}" --}}
                                                <button class="btn btn--danger extraChargeBtn" data-type="subtract">
                                                    <i class="las la-minus-circle"></i>@lang('Trừ Phí Thêm')
                                                </button>
                                                <input type="text" hidden class="booking_extra"> </input>
                                            </div>
                                        </div>
                                        <div class="list">
                                            <div class="list-item">
                                                <span>@lang('Trả phòng muộn') <span class="last_overtime"></span> giờ</span>
                                                <span class="total_last_overtime"> 0 VND</span>
                                            </div>
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
                                                    <span id="number_fare"></span> <span id="customer_payment1"></span>
                                                </div>
                                                <div class="form-group">
                                                    <label>@lang('Nhập số tiền')</label>
                                                    <div class="input-group">

                                                        <input class="form-control input_fare_booking" min="0"
                                                            name="amount" id="amount_payment" required step="any"
                                                            type="number">
                                                        <span class="input-group-text">{{ __(gs()->cur_text) }}</span>
                                                    </div>
                                                    <div class="input-group">
                                                        <span id="amount_payment_errors" class="d-error"></span>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="booking_id" id="booking_id">

                                                <button type="submit" class="btn btn-primary" id="submitBtn">Thanh
                                                    toán</button>

                                                <button type="submit" class="btn btn-dark"id="submitBtnCheckOut">Trả
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
            // Gán giá trị vào input
            $('#date-book-room').val(formattedDate);
            $('#time-book-room').val(formattedTime);







            // xóa phòng vừa add vào  roomNumber
            $('#myModal-booking').on('hidden.bs.modal', function() {
                if (!$('#myModal-booking').hasClass('show')) {
                    $('#roomNumber').empty(); // Xóa các phòng vừa thêm vào
                    $('#list-booking tr[data-room-id]').remove();
                    // xóa các phòng thêm vào damh sách booking
                }
            });

            // modal danh sách sản phẩm
            $('.add-room-booking').on('click', function() {
                showRoom()
                $('#addRoomModal').modal('show'); // Hiển thị modal
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
                                                <option value="gio">Giờ</option>
                                                <option value="ngay">Ngày</option>
                                                <option value="dem">Đêm</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="d-flex" style="gap: 10px">
                                                <input type="date" name="checkInDate" class="form-control date-book-room" style="width: 165px;" value="${formattedDates}">
                                                <input type="time" name="checkInTime" class="form-control time-book-room" style="width: 135px;" value="${formattedTimes}">
                                            </div>
                                        </td>
                                    </tr>
                                `;

                            $('#loading').hide();
                            $('#addRoomModal').modal('hide');

                            tbody.append(tr); // Thêm dòng vào bảng
                        }
                    },
                    error: function(error) {
                        $('#loading').hide();
                        console.log('Error:', error);
                    }
                });
            }

            function showRoom() {
                $.ajax({
                    url: '{{ route('admin.booking.showRoom') }}',
                    type: 'GET',
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
                        });
                    },
                    error: function(error) {
                        console.log('Error:', error);
                    }
                });
            }




            $('#model').on('change', function(event) {
                var model = $(this).val();
                var parent = $(this).closest('#myModal-booking');
                var updatedHours = parent.attr('data-hours');
                var updatedDay = parent.attr('data-day');
                var updatedNight = parent.attr('data-night');
                $('#bookingType').html('');
                if (model == 1 || model == null || model == '') {
                    $('.inputTime').text(`1:00`);
                    $('#customer-price-booking, #input-price-booking').val(parseInt(updatedHours, 10));
                    $('#bookingType').append(`
                        <option value="gio">Giờ</option>
                        <option value="ngay">Ngày</option>
                        <option value="dem">Đêm</option>
                    `);
                } else {
                    $('.inputTime').text(`24:00`);
                    $('#customer-price-booking, #input-price-booking').val(parseInt(updatedDay, 10));
                    $('#bookingType').append(`
                        <option value="ngay">Ngày</option>
                        <option value="dem">Đêm</option>
                    `);

                    var now = new Date();

                    // Cộng thêm 1 ngày
                    now.setDate(now.getDate() + 1);

                    var year = now.getFullYear();
                    var month = (now.getMonth() + 1).toString().padStart(2, '0');
                    var day = now.getDate().toString().padStart(2, '0');
                    var hours = now.getHours().toString().padStart(2, '0');
                    var minutes = now.getMinutes().toString().padStart(2, '0');

                    // Tạo chuỗi datetime-local
                    var nextDayDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
                    var checkOutTime = document.getElementById('checkOutTime');
                    checkOutTime.value = nextDayDateTime;



                }
            })

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

            // add_premium_service
            $('.add_premium_service').on('click', function(event) {
                event.stopPropagation();
                $('#serviceModal').modal('show');
            });

            // add_product_room
            $('.add_product_room').on('click', function(event) {
                event.stopPropagation();
                $('#productModal').modal('show');
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

            $(document).on('click', '.handoverKeyBtn', function(event) {
                let data = $(this).data();

                event.stopPropagation();
                var url = `{{ route('admin.booking.key.handover', ['id' => ':id']) }}`.replace(':id', data
                    .id);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        is_method: "receptionist",
                        // status: "receptionist"
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
                    const modalElement = document.getElementById('myModal-booking');

                    if (modalElement) {
                        $('#model').prop('selectedIndex', ''); // Chọn giá trị mặc định (index = 0)
                        $('#bookingType').prop('selectedIndex', 'gio');
                        var now = new Date();
                        var year = now.getFullYear();
                        var month = (now.getMonth() + 1).toString().padStart(2, '0');
                        var day = now.getDate().toString().padStart(2, '0');
                        var hours = now.getHours().toString().padStart(2, '0');
                        var minutes = now.getMinutes().toString().padStart(2, '0');

                        var currentDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
                        // var checkInTime = document.getElementById('checkInTime');
                        // checkInTime.value = currentDateTime;


                        now.setHours(now.getHours() + 1);
                        var checkoutYear = now.getFullYear();
                        var checkoutMonth = (now.getMonth() + 1).toString().padStart(2, '0');
                        var checkoutDay = now.getDate().toString().padStart(2, '0');
                        var checkoutHours = now.getHours().toString().padStart(2, '0');
                        var checkoutMinutes = now.getMinutes().toString().padStart(2, '0');

                        //   var checkoutDateTime =
                        //      `${checkoutYear}-${checkoutMonth}-${checkoutDay}T${checkoutHours}:${checkoutMinutes}`;
                        //   var checkOutTime = document.getElementById('checkOutTime');
                        //   checkOutTime.value = checkoutDateTime;
                        //   $('.inputTime').text(`1:00`);
                        const modal = new bootstrap.Modal(modalElement);
                        modal.show();
                    } else {
                        console.error("Modal element not found");
                    }

                    // const modal = new bootstrap.Modal($('#myModal-booking')[0]);
                    // modal.show();

                    const dataHours = $(this).data('hours').replace(',', '');
                    const dataName = $(this).data('name');
                    const dataRoomType = $(this).data('room-type'); // loại room
                    const dataRoom = $(this).data('room');
                    const dataRoomNumber = $(this).data('roomnumber');

                    getRoomType(dataRoomType, dataRoomNumber); // hiển thị ra phòng theo loại


                    const dataDay = $(this).data('day').replace(',', '');
                    const dataNight = $(this).data('night').replace(',', '');

                    $('[name=room_type_id]').val(dataRoomType);
                    $('[name=room_type]').val(dataRoom);
                    $('#customer-price-booking, #input-price-booking').val(parseInt(dataHours, 10));
                    $('#book_name').text(dataName);
                    $('#roomNumber').val(dataRoomNumber);

                    modalElement.setAttribute('data-hours', parseInt(dataHours, 10));
                    modalElement.setAttribute('data-day', parseInt(dataDay, 10));
                    modalElement.setAttribute('data-night', parseInt(dataNight, 10));

                    window.savedDataHours = dataHours;
                    window.savedDataDay = dataDay;
                    window.savedDataNight = dataNight;
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
                var booking_id = $(this).data('booking');


                handleLateCheckinClick(id, booking_id);
            });

            $('.room-booking-status-incoming').on('click', function() {
                var id = $(this).data('id');
                var booking_id = $(this).data('booking');
                handleLateCheckinClick(id, booking_id);
            });

            // $('.room-booking-status-late-checkin').on('click', function() {
            //     var id = $(this).data('id');
            //     var booking_id = $(this).data('booking');
            //     handleLateCheckinClick(id, booking_id);
            // });

            $(document).on('click', '.room-booking-status-late-checkin', function() {
                var id = $(this).data('id');
                var booking_id = $(this).data('booking');
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
                    is_method: 'receptionist'
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

                            var customer_type = response.data.user_id ?
                                "Khách hàng đã đăng ký" : " Khách hàng lưu trú"


                            if (customer_type) {
                                var url_user_info =
                                    `{{ can('admin.users.detail') ? route('admin.users.detail', ['id' => ':id']) : '' }}`
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
                            // các phòng  đã đặt 
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
                                           
                                        </td>

                                    </tr>
                                `;
                                // ${formatCurrency(booked.fare)}
                                // ${formatCurrency(booked.room.room_prices_active[0]['price'])} ${booked.room}
                                totalFare += parseFloat(booked.fare);

                            });
                            rowsHtml += `
                            <tr>
                                <td class="text-end" colspan="4">
                                    <span class="fw-bold">@lang('Tổng giá')</span>
                                </td>

                                <td class="fw-bold text-end">
                                    ${ formatCurrency(totalFare) }
                                </td>
                            </tr>
                            `;
                            $('#bookings-table-body').append(rowsHtml);

                            $('#user_services').empty();
                            // dịch vụ trong phòng
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

                            $('#user_product').empty();

                            let rowsHtmlProduct = '';

                            // chi tiêt sản phẩm trong phòng
                            response.data.used_product_room.forEach(function(booked, index) {
                                rowsHtmlProduct += `
                                        <tr>
                                            <td class="bg--date text-center" data-label="@lang('Ngày')">
                                                ${booked.product_date}
                                            </td>
                                            <td data-label="@lang('Phòng số')">
                                                ${booked.room.room_number}
                                            </td>
                                            <td class="text-center" data-label="@lang('Dịch vụ')">
                                                ${booked.product.name}
                                            </td>
                                            <td class="text-center" data-label="@lang('Số lượng')">
                                                ${booked.qty}
                                            </td>

                                            <td class="text-end" data-label="@lang('Giá')">
                                                ${formatCurrency(booked.unit_price)}
                                            </td>
                                            <td class="text-end" data-label="@lang('Tổng giá')">
                                                ${formatCurrency( booked.qty * booked.unit_price)}
                                            </td>
                                        </tr>
                                    `;
                            });
                            $('#user_product').append(rowsHtmlProduct);



                            //   console.log(response);
                            $('.booking_extra').val(response.data.id);
                            $('.last_overtime').text(response.data.last_overtime_calculated_at ?? 0);

                            $('.customer_type').text(customer_type);
                            $('.booking_number').text(response.data.booking_number);

                            let hinhthuc = '';
                            switch (response.data.option) {
                                case 'gio':
                                    hinhthuc = 'Giờ';
                                    break;
                                case 'dem':
                                    hinhthuc = 'Đêm';
                                    break;
                                case 'ngay':
                                    hinhthuc = 'Ngày';
                                    break;
                            }
                            $('.option').text(hinhthuc);
                            $('.check_in').text(response.data.check_in);
                            $('.check_out').text(response.data.check_out);
                            $('.booking_price').text(response.data.booked_rooms.fare);
                            const currentTimeStr = '<?php echo $currentTime; ?>';

                            const checkoutTimeStr = response.data.check_out;

                            // Chuyển đổi chuỗi thời gian thành đối tượng Date (thêm 'T' vào giữa ngày và giờ)
                            const currentTime = new Date(currentTimeStr.replace(' ', 'T'));
                            const checkoutTime = new Date(checkoutTimeStr.replace(' ', 'T'));

                            // So sánh thời gian hiện tại với thời gian checkout
                            if (currentTime > checkoutTime) {
                                $('.text-checkoutlate').text('(Quá giờ trả phòng)')
                            } else {
                                $('.text-checkoutlate').text('')
                            }

                            const paid_amount = formatCurrency(response.data.paid_amount);
                            $('.total_received').text("-" + paid_amount);
                            $('.total_refunded').text(response.returnedPayments);
                            const total_amount = formatCurrency(response.total_amount)
                            $('.total_fare').text("+" + total_amount);
                            let price_overtime = 0;
                            let due = 0;
                            if (response.data.option === 'gio') {

                                price_overtime = response.data.booked_rooms[0]['fare'] * response
                                    .data.last_overtime_calculated_at;
                                const last_over_time = formatCurrency(price_overtime);

                                $('.total_last_overtime').text("+" + last_over_time);

                                due = formatCurrency(response.total_amount - response.data.paid_amount)
                                $('#customer_payment, #customer_payment1').text("+" + due);

                            } else {
                                due = formatCurrency(response.due);
                            }

                            if (response.due > 0) {
                                $('#number_fare').text('Số tiền phải thu: ');
                                $('#color_payment').addClass('text--success');
                                $('#dueMessage1').text("@lang('Nhận thanh toán')");
                                $('#dueMessage').text("@lang('Phải thu từ người dùng')");
                                $('#submitBtnCheckOut').attr('disabled', true);

                                $('#customer_payment, #customer_payment1').text(due);
                            } else {
                                // thanh toán
                                $('#number_fare').text('Số tiền hoàn lại: ');
                                $('#dueMessage1').text("@lang('Số tiền hoàn lại')");
                                $('#dueMessage').text("Có thể hoàn trả");
                                $('#customer_payment1').text(due);

                                $('#submitBtnCheckOut').attr('disabled', false);
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

            $('#submitBtnCheckOut').click(function(e) {
                var booking_id = $('#booking_id').val();
                var url = `{{ route('admin.booking.checkout', ['id' => ':id']) }}`.replace(':id',
                    booking_id);

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        is_method: "receptionist"
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            handleLateCheckinClick(response.id, response.booking_id)
                            notify('success', `Đặt phòng đã được thanh toán thành công`);
                            window.location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        console.log(xhr.status);
                        alert("Đã có lỗi xảy ra. Vui lòng thử lại.");
                    }
                });
            })
            // var now = new Date();
            // var year = now.getFullYear();
            // var month = (now.getMonth() + 1).toString().padStart(2, '0');
            // var day = now.getDate().toString().padStart(2, '0');
            // var hours = now.getHours().toString().padStart(2, '0');
            // var minutes = now.getMinutes().toString().padStart(2, '0');

            // var currentDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
            // // var checkInTime = document.getElementById('checkInTime');
            // // checkInTime.value = currentDateTime;


            // now.setHours(now.getHours() + 1);
            // var checkoutYear = now.getFullYear();
            // var checkoutMonth = (now.getMonth() + 1).toString().padStart(2, '0');
            // var checkoutDay = now.getDate().toString().padStart(2, '0');
            // var checkoutHours = now.getHours().toString().padStart(2, '0');
            // var checkoutMinutes = now.getMinutes().toString().padStart(2, '0');

            // var checkoutDateTime =
            //     `${checkoutYear}-${checkoutMonth}-${checkoutDay}T${checkoutHours}:${checkoutMinutes}`;
            // var checkOutTime = document.getElementById('checkOutTime');
            // checkOutTime.value = checkoutDateTime;

            //     $('.inputTime').text(`1:00`);

            // let bookingType;
            // $('#bookingType').on('change', function() {
            //     bookingType = $(this).val();
            //     var now = new Date();

            //     var checkOutTime;
            //     if (bookingType === 'gio') {
            //         reserthoursNow();
            //         checkOutTime = new Date(now.getTime() + (1 * 60 * 60 * 1000)); // Cộng 1 giờ
            //     } else if (bookingType === 'ngay') {
            //         reserthoursNow();
            //         checkOutTime = new Date(now.getTime() + (1 * 24 * 60 * 60 * 1000)); // Cộng 1 ngày

            //     } else if (bookingType === 'dem') {
            //         var hours = '22'; // Đặt giờ là 10h tối (22:00)
            //         var minutes = '00'; // Đặt phút là 00
            //         // Kết hợp lại thành thời gian theo định dạng ISO
            //         var currentDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;

            //         // Gán giá trị cho trường 'checkInTime' (input trong HTML)
            //         // var checkInTime = document.getElementById('checkInTime');
            //         // checkInTime.value = currentDateTime;
            //         // checkOutTime = new Date(now.getTime() + (12 * 60 * 60 * 1000)); // Cộng 12 giờ
            //         // var checkOutTime = new Date(currentDateTime);
            //         // checkOutTime.setHours(checkOutTime.getHours() + 12);
            //     }

            //     // var checkOutYear = checkOutTime.getFullYear();
            //     // var checkOutMonth = (checkOutTime.getMonth() + 1).toString().padStart(2, '0');
            //     // var checkOutDay = checkOutTime.getDate().toString().padStart(2, '0');
            //     // var checkOutHours = checkOutTime.getHours().toString().padStart(2, '0');
            //     // var checkOutMinutes = checkOutTime.getMinutes().toString().padStart(2, '0');
            //     // var checkOutDateTime =
            //     //     `${checkOutYear}-${checkOutMonth}-${checkOutDay}T${checkOutHours}:${checkOutMinutes}`;

            //     // $('#checkOutTime').val(checkOutDateTime);
            //     calculateDuration();

            // });

            // function calculateDuration(event) {

            //     var checkInTime = $('#checkInTime').val();
            //     var checkOutTime = $('#checkOutTime').val();
            //     if (!checkInTime || !checkOutTime) {
            //         $('.inputTime').text('Vui lòng nhập cả thời gian check-in và check-out.');
            //         return;
            //     }

            //     var checkInDate = new Date(checkInTime);
            //     var checkOutDate = new Date(checkOutTime);
            //     var durationMs = checkOutDate - checkInDate;

            //     if (durationMs < 0) {
            //         $('.inputTime').text('Thời gian check-out phải sau thời gian check-in.');
            //         return;
            //     }


            //     var durationHours = Math.floor(durationMs / (1000 * 60 * 60));
            //     var durationMinutes = Math.floor((durationMs % (1000 * 60 * 60)) / (1000 * 60));


            //     var formattedHours = durationHours.toString().padStart(2, '0'); // giờ
            //     var formattedMinutes = durationMinutes.toString().padStart(2, '0'); // phút

            //     var parent = $(this).closest('#myModal-booking');
            //     var updatedHours = parseFloat(window.savedDataHours.replace(',', ''));
            //     var updatedDay = parseFloat(window.savedDataDay.replace(',', ''));
            //     var updatedNight = parseFloat(window.savedDataNight.replace(',', ''));
            //     let priceTime = 0;
            //     switch (bookingType) {
            //         case 'gio':
            //             priceTime = parseFloat(window.savedDataHours.replace(',', '')) || 0;
            //             break;
            //         case 'ngay':
            //             priceTime = parseFloat(window.savedDataDay.replace(',', '')) || 0;
            //             break;
            //         case 'dem':
            //             priceTime = parseFloat(window.savedDataNight.replace(',', '')) || 0;
            //             break;
            //         default:
            //             const model = $('#model').val();
            //             if (model == 1 || model == null || model == '') {
            //                 priceTime = parseFloat(window.savedDataHours.replace(',', '')) || 0;
            //             } else {
            //                 priceTime = parseFloat(window.savedDataDay.replace(',', '')) || 0;

            //             }
            //             break;
            //     }

            //     $('.inputTime').text(`${formattedHours}:${formattedMinutes}`);

            //     let updatedPrice = 0;

            //     // const dateTimeDate = Math.round(formattedHours / 24); // Số ngày
            //     // const dateTimeNight = Math.round(formattedHours / 12); // Số đêm

            //     // if (formattedHours >= 24) {
            //     //     alert('ngay');
            //     //     updatedPrice = dateTimeDate * priceTime; // Tính theo ngày
            //     // } else if (formattedHours >= 12) {
            //     //     alert('đêm');
            //     //     updatedPrice = dateTimeNight * priceTime; // Tính theo đêm
            //     // } else {
            //     //     alert('gio');
            //     //     updatedPrice = formattedHours * priceTime; // Tính theo giờ
            //     // }

            //     const totalHours = Math.round((checkOutDate - checkInDate) / (1000 * 60 * 60)); // Tổng số giờ

            //     // Tính số đêm (làm tròn lên nếu tổng giờ vượt quá 12)
            //     let dateTimeNight = Math.ceil(totalHours / 12);

            //     // Tính số ngày (làm tròn xuống để có số ngày đầy đủ)
            //     let dateTimeDate = Math.round(totalHours / 24);

            //     if (bookingType === 'ngay') {
            //         updatedPrice = dateTimeDate * priceTime;
            //     } else if (bookingType === 'dem') {
            //         updatedPrice += priceTime;
            //         if (dateTimeNight > 1) {
            //             console.log(priceTime + '---' + dateTimeDate);
            //             updatedPrice += updatedDay * (dateTimeDate - 1);
            //         }

            //     } else if (bookingType === 'gio') {
            //         updatedPrice = totalHours * priceTime;
            //     } else {
            //         const model = $('#model').val();
            //         if (model == 1 || model == null || model === '') {
            //             updatedPrice = totalHours * priceTime;
            //         } else {
            //             updatedPrice = dateTimeDate * priceTime;
            //         }
            //     }

            //     $('#input-price-booking').val(updatedPrice.toLocaleString());
            //     $('#customer-price-booking').val(updatedPrice.toLocaleString());

            // }
            $('#hour_current').on('click', function() {
                // Lấy ngày và giờ hiện tại
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0'); // Tháng bắt đầu từ 0
                const date = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');

                // Kết hợp thành giá trị datetime-local
                const updatedDateTime = `${year}-${month}-${date}T${hours}:${minutes}`;

                // Gán giá trị mới vào input
                $('#checkInTime').val(updatedDateTime);
                // calculateDuration();
            });


            $('#hour_regulatory').on('click', function() {
                var url = `{{ route('admin.checkhours') }}`;
                $.ajax({
                    url: url,
                    type: "GET",
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            const bookingType = $('#bookingType').val();

                            var result = response.data;
                            var checkin_time = result.checkin_time;
                            var checkout_time = result.checkout_time;
                            var checkin_time_night = result.checkin_time_night;
                            var checkout_time_night = result.checkout_time_night;
                            if (bookingType == 'ngay') {
                                var checkInTime = hous_mac_dinh(checkin_time);
                                var checkOutTime = hous_mac_dinh_dem(checkout_time);
                            } else if (bookingType == 'dem') {
                                var checkInTime = hous_mac_dinh(checkin_time_night);
                                var checkOutTime = hous_mac_dinh_dem(checkout_time_night);
                            } else {
                                return 0;
                            }
                            console.log(checkInTime);

                            // Gán giá trị mới vào input
                            $('#checkInTime').val(checkInTime);
                            $('#checkOutTime').val(checkOutTime);
                            //   calculateDuration();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        console.log(xhr.status);
                        alert("Đã có lỗi xảy ra. Vui lòng thử lại.");
                    }
                });
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
            $('.btn-confirm').on('click', function() {

                $('.booking-form').submit();
            });


            function getDatesBetween(startDate, checkInTime, roomType) {
                let dates = [];
                let currentDate = new Date(startDate);


                const [checkInHours, checkInMinutes] = checkInTime.split(':').map(Number);


                while (currentDate) {

                    currentDate.setHours(checkInHours);
                    currentDate.setMinutes(checkInMinutes);
                    currentDate.setSeconds(0); // Đặt giây về 0

                    let formattedDate =
                        `${currentDate.getMonth() + 1}/${String(currentDate.getDate()).padStart(2, '0')}/${currentDate.getFullYear()} ` +
                        `${String(currentDate.getHours()).padStart(2, '0')}:${String(currentDate.getMinutes()).padStart(2, '0')}:${String(currentDate.getSeconds()).padStart(2, '0')}`;

                    dates.push(`${roomType}-${formattedDate}`);

                    break;


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
                const checkInDate = params.get('checkInDate');
                const checkInTime = params.get('checkInTime');
                // const checkOutTime = params.get('checkOutTime');

                // if (!checkInTime || !checkOutTime) {
                //     let missingField = !checkInTime ? 'nhận phòng' : 'trả phòng';
                //     notify('error', `Vui lòng chọn thời gian ${missingField}`);
                //     return false;
                // }

                //const startDate = new Date(checkInTime);
                // const endDate = new Date(checkOutTime);


                const roomDates = getDatesBetween(checkInDate, checkInTime, params.get('room_type'));

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
                formData.push({
                    name: 'is_method',
                    value: 'receptionist',
                });
                // formData.push({
                //     name: '',
                //     value: 'receptionist',
                // });
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
                $('.clear-main').text("|");

                $('.username-user').text(name);
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
    </script>
@endpush

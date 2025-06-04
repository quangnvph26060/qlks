@extends('admin.layouts.master_iframe')
@section('panel')
    <div class="row d-flex align-items-center" style="padding: 10px">
        <div class="col-md-6 d-flex" style="justify-content: start;gap:10px;height: 38px;">
            <div class="view-toggle">
                <button id="listViewBtn" class="active" onclick="changeView('list')">
                    <span class="icon"> <i class="fa-solid fa-bars"></i></span> <span class="text">Danh Sách</span>
                </button>
                <button id="gridViewBtn" onclick="changeView('calendar')">
                    <span class="icon"><i class="fa-solid fa-sliders"></i></span> <span class="text"
                        style="display: none;">Lưới</span>
                </button>
                <button id="tableViewBtn" onclick="changeView('grid')">
                    <span class="icon"> <i class="fa-solid fa-th-large"></i></span> <span class="text"
                        style="display: none;">Sơ đồ</span>
                </button>
            </div>
            <div class="search-container">
                <!-- Dropdown (Bên trái) -->
                <div class="dropdown" style="position: relative; display: inline-block;">
                    <button class="btn btn-primary demo" type="button" id="dropdownMenuButton">
                        Mã kênh bán
                    </button>
                    <ul class="dropdown-menu custom-dropdown"
                        style="display: none; position: absolute; z-index: 999; top: 100%; left: 0;">
                        <li><a class="dropdown-item click-dropdown-item" href="#" data-type="room">Tên phòng</a></li>
                        <li><a class="dropdown-item click-dropdown-item" href="#" data-type="customer">Khách hàng</a>
                        </li>
                        <li><a class="dropdown-item click-dropdown-item" href="#" data-type="booking">Mã đặt phòng</a>
                        </li>
                        <li><a class="dropdown-item click-dropdown-item active" href="#" data-type="channel">Mã kênh
                                bán</a></li>
                    </ul>
                </div>


                <!-- Ô tìm kiếm (Bên phải) -->
                <div class="flex-grow-1">
                    <div class="input-group">
                        <input id="searchInputBooking" style="height: 38px" name="room" type="text"
                            class="form-control" placeholder="🔍 Tìm theo tên phòng" aria-label="Search">
                        <button class="btn btn-primary" id="searchBtn">Tìm kiếm</button>
                    </div>
                </div>
                <button class="filter-btn" id="filter-btn">
                    <i class="fas fa-filter"></i>
                    <span class="dot"></span>
                </button>
                <button class="reload-btn" id="reload-btn">
                    <i class="fas fa-sync-alt"></i>

                </button>
            </div>
        </div>
        <div class="col-md-6" id="booking-time">
            <div style="float: right; gap: 10px;height: 39px;" class="d-flex">
                <div id="date-input-booking" style="display: flex;gap: 10px;">
                </div>

                <p class="btn btn-primary change-room d-flex align-items-center add-book-room"
                    style="font-size:13px; gap: 5px;"><i class="la la-plus"></i> Đặt phòng</p>
            </div>
        </div>
    </div>
    <div class="row" style="padding: 10px">
        <div class="col">
            {{-- @include('admin.booking.partials.system-1') --}}
        </div>
    </div>
    <div class="row">
        <div id="listView" class="view">
            @include('admin/booking/receptionist/list')
        </div>
        <div id="gridView" class="view" style="display: none;">
            @include('admin/booking/receptionist/grid')
        </div>
        <div id="calendarView" class="view" style="display: none;">
            @include('admin/booking/receptionist/calendar')
        </div>

        <div id="loading-overlay" style="display: none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24">
                <path fill="currentColor" d="M12 2A10 10 0 1 0 22 12A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8A8 8 0 0 1 12 20Z"
                    opacity=".5"></path>
                <path fill="currentColor" d="M20 12h2A10 10 0 0 0 12 2V4A8 8 0 0 1 20 12Z">
                    <animateTransform attributeName="transform" dur="1s" from="0 12 12" repeatCount="indefinite"
                        to="360 12 12" type="rotate"></animateTransform>
                </path>
            </svg>
        </div>
    </div>
    @push('breadcrumb-plugins')
        <div class="modal fade" id="addRoomModal" tabindex="-1" aria-hidden="true" style="overflow: unset">
            <div class="modal-dialog modal-dialog-centered" style="top: 4px">
                <div class="modal-content" style="height: 100vh;">
                    <div class="modal-header">
                        <h5 class="modal-title">Chọn Phòng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class=" mt-2 d-flex mb-2" style="gap: 10px;justify-content: space-around;">
                        <div class="">
                            <label for="">Chọn loại phòng</label>
                            <select class="form-select" id="selected-hang-phong">


                            </select>
                        </div>
                        <div class="">
                            <label for="">Chọn tên phòng</label>
                            <select class="form-select" id="selected-name-phong">

                            </select>
                        </div>
                        <div class="">
                            <label for="">Từ ngày</label>
                            <input type="date" class="form-control " id="date-chon-phong-in" style="height: 38px">
                        </div>
                        <div class="">
                            <label for="">Đến ngày</label>
                            <input type="date" class="form-control" id="date-chon-phong-out" style="height: 38px">
                        </div>
                        <div class="">
                            <label for="">Trạng thái phòng</label>
                            <select class="form-select" id="status-room">
                                <option value="">Chọn trạng tên phòng</option>
                                <option value="Trống">Trống</option>
                                <option value="Đã đặt">Đã đặt</option>
                                <option value="Đã nhận">Đã nhận</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-body overflow-add-room">
                        <table class=" table--light style--two table">
                            <thead>
                                <tr>
                                    <th data-table="Hạng phòng">Hạng phòng</th>
                                    <th data-table="Phòng">Tên phòng</th>
                                    <th data-table="Ngày">Ngày</th>
                                    <th data-table="Trạng thái phòng">Trạng thái phòng</th>
                                    <th data-table="Giá">Giá</th>
                                    <th data-table="Thao tác">Thao tác</th>
                                </tr>
                            </thead>

                            <tbody id="show-room">

                            </tbody>

                        </table>
                    </div>
                    <div class="d-flex justify-content-end" style="gap: 10px;padding: 7px 31px">
                        <p type="button" data-row="booked" class=" btn-dat-truoc  add-room-list">Lưu
                        </p>
                        <p type="button" data-row="booked" class="alert-paragraph close_modal_booked_room">Hủy</p>
                    </div>
                </div>
            </div>
        </div>
        @include('admin.booking.partials.customer_booked')
        @include('admin.booking.partials.room_booking_edit')
        @include('admin.booking.partials.room_booking')
        @include('admin.booking.partials.change_room_booking')
        @include('admin.booking.partials.modal_service')
        <div class="modal fade" id="myModal-check-in-edit" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel-booking" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered" role="document" style="width: 100%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title pageModal" id="myModalLabel-booking">Sửa nhận phòng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body " style="padding: 5px 12px 12px 5px;">


                        <form id="bookingForm" action="" class="booking-form-pttt" method="POST">
                            @csrf
                            <div class="row">
                                <h5 class="modal-title" id="myModalLabel-booking">Thông tin khách hàng</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex flex-column">
                                            {{-- <div class="mb-3 mt-2">
                                            <label for="email" class="form-label required">Email</label>
                                        <select class="form-select" name="" id="">
                                            <option value="">21</option>
                                            <option value="">2</option>
                                        </select>
                                        </div> --}}
                                            <div class="customer-input-container">
                                                <div class="col-md-1" style="margin-left: 10px">
                                                    {{-- <p class="btn btn--primary "
                                                    style="white-space: nowrap; font-size: 13px" id="btn-search">Tìm
                                                    kiếm</p> --}}
                                                    <p class="btn btn--primary modal--search-customer"
                                                        style="white-space: nowrap; font-size: 13px;" id="btn-search">Tìm
                                                        khách</p>
                                                </div>
                                                <label for="phone" class="form-label required">Tên khách hàng</label>

                                                <div class="d-flex">
                                                    <div class="col-md-8">
                                                        <input type="text" name="name" id="name_edit"
                                                            class="form-control name-edit" placeholder="Tên khách hàng">
                                                        <span class="invalid-feedback d-block name_error_edit"
                                                            style="font-weight: 500" id="name_error_edit"></span>
                                                    </div>
                                                    <input type="hidden" name="id_room_booking" id="id_room_booking"
                                                        class="form-control id_room_booking">
                                                </div>

                                                {{-- <div class="d-flex customer-svg-icon" style="gap: 5px">
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
                                            </div> --}}
                                            </div>
                                            <div class="col-md-8 ">
                                                <label for="phone" class="form-label required">Số điện thoại</label>
                                                <input type="text" id="phone" name="phone"
                                                    class="form-control phone-edit" placeholder="Số điện thoại">
                                                <span class="mt-3 invalid-feedback d-block"
                                                    style="font-weight: 500"id="phone_error"></span>
                                            </div>

                                            {{-- <div class="mb-3">
                                            <label for="address" class="form-label required">Địa chỉ</label>
                                            <input type="text" id="address" class="form-control" placeholder="Địa chỉ">
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label required">Email</label>
                                            <input type="text" id="email" class="form-control" placeholder="Email">
                                        </div>

                                        <div class="md-3 d-flex flex-column mt-1 mb-3">
                                            <label for="note" class="form-label required">Ghi chú</label>

                                            <input type="text" name="ghichu" id="note" class="form-control"
                                                placeholder="Nhập ghi chú...">
                                        </div> --}}

                                            <div class="col-md-8  ">
                                                <input type="hidden" name="customer_code" id="customer_code">
                                                <label for="phone" class="form-label">Nguồn khách</label>
                                                {{-- <input type="text" id="phone" name="phone" class="form-control"
                                                placeholder="Số điện thoại"> --}}
                                                <select id="select-customer-source-edit-letan" name="customer_source"
                                                    class="form-control " style="width: 100%;">
                                                </select>
                                            </div>
                                            <div class="col-md-8 mb-3 mt-3 d-flex align-items-center result-add-customer"
                                                style="gap:10px">
                                                <input type="checkbox" name="insert_customer">
                                                <p style="font-size: 13px">Lưu thông tin khách</p>
                                            </div>
                                            <div class="d-flex justify-content-start" style="gap: 10px">
                                                <p class="add-room-booking-edit" style="width: 185px;">
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
                                                <p id="add-service-room-booking" class="add_product_service_payment"
                                                    style="width: 204px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24">
                                                        <g fill="currentColor" fill-rule="evenodd" clip-rule="evenodd">
                                                            <path
                                                                d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12Zm10-8a8 8 0 1 0 0 16a8 8 0 0 0 0-16Z" />
                                                            <path
                                                                d="M13 7a1 1 0 1 0-2 0v4H7a1 1 0 1 0 0 2h4v4a1 1 0 1 0 2 0v-4h4a1 1 0 1 0 0-2h-4V7Z" />
                                                        </g>
                                                    </svg>
                                                    Thêm dịch vụ sản phẩm
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex flex-column">

                                            <div class="customer-input-container mt-2">
                                                <label for="phone" class="form-label required">Ngày đặt</label>
                                                <div class="d-flex">
                                                    <div class="col-md-8">
                                                        <div class="d-flex align-items-center justify-content-start"
                                                            style="gap: 10px">
                                                            <input type="date" name="checkInDate"
                                                                id="date-book-room-booking-edit" class="form-control ">
                                                            <input type="time" name="checkInTime"
                                                                id="time-book-room-booking" class="form-control ">
                                                        </div>
                                                        {{-- <span class="invalid-feedback d-block"
                                                        style="font-weight: 500"id="name_error"></span> --}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8 mb-3 mt-2" style="display: none">
                                                <label for="phone" class="form-label required">Ngày trả</label>
                                                <div class="d-flex align-items-center justify-content-start"
                                                    style="gap: 10px">
                                                    <input type="date" name="checkOutDate" id="date-book-room-date"
                                                        class="form-control">
                                                    <input type="time" name="checkOutTime" id="time-book-room-date"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <label for="phone" class="form-label ">Nhân viên </label>
                                                <div class="d-flex align-items-center justify-content-start"
                                                    style="gap: 10px">
                                                    <select id="select-staff-edit-letan"name="name_staff"
                                                        class="form-control " style="width: 100%;">

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <datalist id="customer-names">
                                @forelse ($userList as $user)
                                    <option value="{{ $user->customer_code }}">
                                    @empty
                                        <p>No items found.</p>
                                @endforelse
                            </datalist> --}}
                                {{-- <p id="error-message" style="color: red; display: none;">Không tìm thấy email khách hàng phù
                                hợp
                            </p> --}}
                            </div>
                            <div class="row">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5 class="modal-title" id="myModalLabel-booking">Danh sách phòng</h5>
                                    <p class="delete-room-booking-edit-letan" style="width: 126px;height: 30px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 20 20">
                                            <path fill="currentColor"
                                                d="m9.129 0l1.974.005c.778.094 1.46.46 2.022 1.078c.459.504.7 1.09.714 1.728h5.475a.69.69 0 0 1 .686.693a.689.689 0 0 1-.686.692l-1.836-.001v11.627c0 2.543-.949 4.178-3.041 4.178H5.419c-2.092 0-3.026-1.626-3.026-4.178V4.195H.686A.689.689 0 0 1 0 3.505c0-.383.307-.692.686-.692h5.47c.014-.514.205-1.035.554-1.55C7.23.495 8.042.074 9.129 0Zm6.977 4.195H3.764v11.627c0 1.888.52 2.794 1.655 2.794h9.018c1.139 0 1.67-.914 1.67-2.794l-.001-11.627ZM6.716 6.34c.378 0 .685.31.685.692v8.05a.689.689 0 0 1-.686.692a.689.689 0 0 1-.685-.692v-8.05c0-.382.307-.692.685-.692Zm2.726 0c.38 0 .686.31.686.692v8.05a.689.689 0 0 1-.686.692a.689.689 0 0 1-.685-.692v-8.05c0-.382.307-.692.685-.692Zm2.728 0c.378 0 .685.31.685.692v8.05a.689.689 0 0 1-.685.692a.689.689 0 0 1-.686-.692v-8.05a.69.69 0 0 1 .686-.692ZM9.176 1.382c-.642.045-1.065.264-1.334.662c-.198.291-.297.543-.313.768l4.938-.001c-.014-.291-.129-.547-.352-.792c-.346-.38-.73-.586-1.093-.635l-1.846-.002Z" />
                                        </svg>
                                        Xóa phòng
                                    </p>
                                </div>
                                <!-- Row: Labels -->
                                <div class="table-responsive mt-2">
                                    <table class="table mobi-table" id="data-table">
                                        <thead>
                                            <tr class="text-center fw-bold main-booking-modal">
                                                {{-- <th>Hạng phòng</th> --}}
                                                <th></th>
                                                <th>Phòng</th>
                                                <th>Số lượng khách</th>
                                                <th>Hình thức</th>
                                                <th class="d-flex gap-10">Ngày nhận phòng
                                                    {{-- <span class="main-hour-out"
                                                    id="hour_current">Hiện tại</span> --}}
                                                </th>
                                                <th>Ngày trả phòng</th>
                                                <th>Tiền phòng</th>
                                                <th>Tiền cọc</th>
                                                <th>Giảm giá</th>
                                                <th>Ghi chú</th>
                                                {{-- <th class="d-flex justify-content-between align-items-center">Dự kiến
                                            <span>Thành tiền</span>
                                        </th> --}}
                                            </tr>
                                        </thead>
                                        {{-- <input type="text" class="room_type_id" name="room_type_id"hidden> --}}
                                        {{-- <input type="text" class="room_type" name="room_type"hidden>
                                    <input type="text" class="username-user1" name="guest_name" hidden>
                                    <input type="text" class="email-user1" name="email" hidden>
                                    <input type="text" class="mobile-user" name="mobile" hidden>
                                    <input type="text" class="address-user" name="address" hidden>
                                    <input type="text" class="guest_type" name="guest_type" hidden> --}}
                                        <tbody id="list-booking-edit-letan">

                                        </tbody>

                                    </table>
                                </div>
                                <div class="alert-danger message-error-edit" role="alert"></div>
                            </div>
                            <hr>
                            <div class="flex-column justify-content-end" style="gap: 10px;">
                                <div class=" d-flex justify-content-between mb-2">
                                    <div class="col-md-3">

                                        <select id="select-option-pttt-main" name="payment_pttt" class="form-control "
                                            style="width: 100%;">
                                            <option value="">Chọn phương thức thanh toán</option>
                                            <option value="Tiền mặt">Tiền mặt</option>
                                            <option value="Chuyển khoản ngân hàng">Chuyển khoản ngân hàng</option>
                                            <option value="Thẻ tín dụng">Thẻ tín dụng</option>
                                        </select>
                                        <span class="invalid-feedback d-block" id="select-option-pttt_error1"
                                            style="font-weight: 500"></span>


                                    </div>
                                    <ul class="financial-list">
                                        <li class="financial-item">
                                            <span>Tiền phòng</span>
                                            <span class="total_amount">0</span>
                                        </li>
                                        <li class="financial-item highlighted">
                                            <span>Giảm giá</span>
                                            <span class="total_discount">0</span>
                                            {{-- <input type="text" id="discountInput" class="custom-input-giam-gia"> --}}
                                        </li>
                                        <li class="financial-item">
                                            <span>Tiền cọc</span>
                                            <span class="total_deposit">0</span>
                                        </li>
                                        <li class="financial-item" id="total_payment_display">
                                            <span>Tổng tiền sản phẩm, dịch vụ</span>
                                            <span class="total_service_display">0</span>

                                        </li>
                                        <li class="financial-item" id="select-option-pttt">
                                            <span>Khách đã thanh toán</span>
                                            <span class="total_payment">0</span>

                                        </li>
                                        <li class="financial-item" id="select-option-pttt">
                                            <span>Khách thanh toán</span>
                                            <input type="text" name="input_pttt" class="money-input css-main-input-pttt"
                                                placeholder="0">

                                        </li>
                                        <span class="invalid-feedback d-block" id="input_pttt_error"
                                            style="font-weight: 500"></span>
                                        <li class="financial-item">
                                            <span>Còn lại</span>
                                            <span class="total_balance">0</span>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end" style="gap: 10px;">
                                 <button type="button"  id="checkout_room" class="btn btn-primary btn-checkout-room">Trả phòng</button>
                                  <button type="button" id="print_invoice" class="btn btn-success">In hoá đơn</button>
                                <button type="button" class="btn-dat-truoc btn-book-pttt">Lưu</button>
                                <p type="button" data-row="booked" class="alert-paragraph close_modal">Hủy</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- dọn phòng --}}
        @include('admin/booking/partials/clean_modal')
         @include('admin/booking/partials/room_fix_modal')
        <!-- Bộ lọc bên phải -->
        <div class="filter-sidebar" id="filter-sidebar">
            <div class="filter-header d-flex justify-content-between">
                <h3>Bộ lọc</h3>
                <button class="close-filter">
                    <i class="fas fa-times" style="font-size:20px"></i>
                </button>
            </div>
            <div class="filter-content">
                <div class="room-option mt-1">
                    <p>HẠNG PHÒNG</p>
                    <hr>
                    <div class="room-option-main-item">

                        <div class="row">
                            @foreach ($roomTypes as $roomType)
                                <span class=" col-md-6 d-flex " style="gap:5px">
                                    <input type="checkbox" name="room_type" data-id="{{ $roomType->id }}" />
                                    <p style="font-size:13px">{{ $roomType->name }}</p>
                                </span>
                            @endforeach
                        </div>

                    </div>
                </div>
                <div class="room-status_fill">
                    <p>TRẠNG THÁI PHÒNG</p>
                    <hr>
                    <div class="room-option-item">
                        <div class="row">
                            <span class=" col-md-6 d-flex " style="gap:5px">
                                <input type="checkbox" name="room_status" data-id="1" id="room_status">
                                <p style="font-size:13px">Đang trống</p>
                            </span>
                            <span class=" col-md-6 d-flex " style="gap:5px">
                                <input type="checkbox" name="room_status" data-id="2" id="room_status">
                                <p style="font-size:13px">Đã đặt</p>
                            </span>
                            <span class=" col-md-6 d-flex " style="gap:5px">
                                <input type="checkbox" name="room_status" data-id="3" id="room_status">
                                <p style="font-size:13px">Đã nhận</p>
                            </span>

                        </div>
                    </div>
                </div>
                <div class="room-clean">
                    <p>TÌNH TRẠNG PHÒNG</p>
                    <hr>
                    <div class="room-option-item">
                        <div class="row">
                            <span class="col-md-6 d-flex" style="gap:5px">
                                <input type="checkbox" name="room_clean" data-id = "1" />
                                <p style="font-size:13px">Sạch</p>
                            </span>
                            <span class="col-md-6 d-flex" style="gap:5px">
                                <input type="checkbox" name="room_clean" data-id = "0"
                                    style="accent-color: red; border: 1px solid red;">
                                <p style="font-size:13px">Chưa dọn</p>
                            </span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="d-flex justify-content-end action-buttons" style="gap:10px">
                <button class="btn btn-danger" id="btn-clear-fillter">Bỏ qua</button>
                <button class="btn btn-primary btn-fillter" id="btn-fillter">Áp dụng</button>
            </div>
        </div>

        <!-- Overlay làm mờ nền -->
        <div class="overlay" id="overlay"></div>
    @endpush
@endsection
@push('style-lib')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/global/css/system-1.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/view-toggle.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/grid_main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/pagination.css') }}">
@endpush
<script src="https://cdn.jsdelivr.net/npm/tesseract.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
@push('script-lib')
    <script src="{{ asset('assets/validator/validator.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
@endpush

<script>
    var checkInUpdateUrl = "{{ route('admin.check.in.update') }}";
    var deleteCheckin = "{{ route('admin.check.in.delete') }}";
    var deleteRoomEdit = "{{ route('admin.room.booking.delete') }}";
    var searchCustomerUrl = "{{ route('admin.search.customer') }}";
    var roomBoookingHistory = "{{ route('admin.booking.room-booking-history') }}";
    var showRoomUrl = "{{ route('admin.booking.showRoom') }}";
    var checkRoomBookingUrl = "{{ route('admin.booking.checkRoomBooking') }}";
    var getCustomerStaff = "{{ route('admin.get.customer.staff') }}";
    var findCustomerUrl = "{{ route('admin.find.customer') }}";
    var roomBookingEditUrl = "{{ route('admin.room.booking.edit', ['id' => ':id']) }}"; // sửa đặt phòng
    var checkInEditUrl = "{{ route('admin.check.in.edit', ['id' => ':id']) }}";
    var paymentRoomUrl = "{{ route('admin.booking.payment.room') }}";
    var checkOutRoomUrl = "{{ route('admin.booking.check.out.room') }}";
    var findRoomBookingIdUrl = '{{ route("admin.find.room.booking") }}';
    var CheckInUrl = "{{ route('admin.room.booked.check.in') }}";
    var roomBook = "{{ route('admin.room.book') }}";
    var cleanRoomUrl = "{{ route('admin.roomclean.booking.roomclean') }}"
    var changeRoomFixUrl = "{{ route('admin.roomfix.booking.roomfix') }}"
    var getAllService = "{{ route('admin.hotel.premium.service.get-all-service') }}";
    var storeService = "{{ route('admin.hotel.premium.service.store-service') }}";
    var deleteService = "{{ route('admin.hotel.premium.service.delete-service') }}";
    var showCurrency = "{{ format_currency() }}";
    const date_booking = new Date();
    const date_yyyy = date_booking.getFullYear();
    const date_mm = String(date_booking.getMonth() + 1).padStart(2, '0');
    const date_dd = String(date_booking.getDate()).padStart(2, '0');
    const date_hour = String(date_booking.getHours()).padStart(2, '0'); // Giờ
    const date_minutes = String(date_booking.getMinutes()).padStart(2, '0'); // Phút

    const formattedDates = `${date_yyyy}-${date_mm}-${date_dd}`;
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('money-input')) {
            let value = e.target.value.replace(/\D/g, ""); // Xóa ký tự không phải số
            value = Number(value).toLocaleString('vi-VN'); // Định dạng theo chuẩn Việt Nam
            e.target.value = value;
        }
    });
    $(document).ready(function() {
        // Toggle dropdown
        $(document).on("click", "#dropdownMenuButton", function(e) {
            e.stopPropagation(); // Không cho nổi bọt lên document
            $(".custom-dropdown").not($(this).siblings(".custom-dropdown"))
                .hide(); // ẩn cái khác nếu có
            $(this).siblings(".custom-dropdown").toggle(); // toggle dropdown này
        });

        // Đóng dropdown khi click ngoài
        $(document).on("click", function() {
            $(".custom-dropdown").hide();
        });

        // Khi chọn tiêu chí tìm kiếm
        $(document).on("click", ".click-dropdown-item", function(e) {
            e.preventDefault();
            $(".click-dropdown-item").removeClass("active");
            $(this).addClass("active");

            let selectedType = $(this).data("type");
            let selectedText = $(this).text().trim();

            let placeholderText = {
                customer: "🔍 Tìm theo khách hàng",
                booking: "🔍 Tìm theo mã đặt phòng",
                channel: "🔍 Tìm theo mã kênh bán",
                room: "🔍 Tìm theo tên phòng"
            };


            $("#dropdownMenuButton").html(selectedText);
            $("#searchInputBooking").attr("placeholder", placeholderText[selectedType]);
            $("#searchInputBooking").attr("name", selectedType);
        });

        // Khi bấm nút tìm kiếm
        $("#searchBtn").on("click", function() {
            let searchType = $("#searchInputBooking").attr("name");
            let searchValue = $("#searchInputBooking").val();
            const data = {
                searchType: searchType,
                searchValue: searchValue
            };
            initGridMain(data);
        });
        $("#btn-fillter").on("click", function() {
            const selectedValuesClean = [];
            const selectedValuesRoomType = [];
            const selectedValuesRoomStatus = [];
            $("input[name='room_clean']:checked").each(function() {
                selectedValuesClean.push($(this).data("id"));
            });
            $("input[name='room_type']:checked").each(function() {
                selectedValuesRoomType.push($(this).data("id"));
            });
            $("input[name='room_status']:checked").each(function() {
                selectedValuesRoomStatus.push($(this).data("id"));
            });
            const data = {
                date: formattedDates,
                room_clean: selectedValuesClean,
                room_type: selectedValuesRoomType,
                room_status: selectedValuesRoomStatus,
            };
            initGridMain(data);
            $('.filter-sidebar').removeClass('open');
            $('.overlay').removeClass('show');
        });
        $('#btn-clear-fillter').on("click", function() {
            $("input[name='room_clean'], input[name='room_type'], input[name='room_status']").prop(
                "checked", false);
        });

        function updateFilterDot() {
            const checkedCount = $("input[type='checkbox']:checked").length;
            if (checkedCount > 0) {
                $("#filter-btn .dot").show();
            } else {
                $("#filter-btn .dot").hide();
            }
        }

        // Gọi hàm khi người dùng click filter hoặc clear filter
        $("#btn-fillter, #btn-clear-fillter, input[type='checkbox']").on("click", function() {
            updateFilterDot();
        });

        // Gọi lần đầu để đảm bảo đúng trạng thái khi load trang
        updateFilterDot();

    });
    $(document).ready(function() {
        // let dirtyCount = 5; // Ví dụ giá trị
        // let incomingCount = 2;
        // let occupiedCount = 10;
        // let lateCheckinCount = 1;
        // let checkOutCount = 3;

        // $('.status-available-line-count').text('Đang trống (' + dirtyCount + ')');
        // $('.status-incoming-line-count').text('Sắp nhận (' + incomingCount + ')');
        // $('.status-occupied-line-count').text('Đang sử dụng (' + occupiedCount + ')');
        // $('.status-checkout-line-count').text('Nhận phòng muộn (' + lateCheckinCount + ')');
        // $('.status-overdue-line-count').text('Quá giờ trả (' + checkOutCount + ')');

        var validatorForm = {
            'name': {
                'element': document.getElementById('name'),
                'error': document.getElementById('name_error'),
                'validations': [{
                    'func': function(value) {
                        return checkRequired(value);
                    },
                    'message': generateErrorMessage('P001', 'Tên')
                }, ]
            },
        }
        const date_booking = new Date();
        const date_yyyy = date_booking.getFullYear();
        const date_mm = String(date_booking.getMonth() + 1).padStart(2, '0');
        const date_dd = String(date_booking.getDate()).padStart(2, '0');
        const date_hour = String(date_booking.getHours()).padStart(2, '0'); // Giờ
        const date_minutes = String(date_booking.getMinutes()).padStart(2, '0'); // Phút

        const formattedDates = `${date_yyyy}-${date_mm}-${date_dd}`;
        const formattedTimes = `${date_hour}:${date_minutes}`;

        $('[id="time-book-room-booking"]').val(formattedTimes);

        function calculateTotalPrice() {
            let totalPrice = 0;
            let totalDeposit = 0;
            let totalDiscount = 0;
            $('#list-booking, #list-booking-edit').find('p#price').each(function() {
                let priceString = $(this).attr('data-price');
                let price = parseFloat(priceString.replace(' VND', '').replace(',', '.'));
                totalPrice += price;
            });
            $('#list-booking, #list-booking-edit').find('input.deposit').each(function() {
                let priceString = $(this).val(); // Lấy giá trị nhập trong input
                let price = parseFloat(priceString.replace(/[,.]/g, '')); // Loại bỏ ký tự không phải số

                if (!isNaN(price)) {
                    totalDeposit += price;
                }
            });
            $('#list-booking, #list-booking-edit').find('input.discount').each(function() {
                let priceString = $(this).val(); // Lấy giá trị nhập trong input
                let price = parseFloat(priceString.replace(/[,.]/g, '')); // Loại bỏ ký tự không phải số
                if (!isNaN(price)) {
                    totalDiscount += price;
                }
            });


            // let pricediscount = 0;
            // let discountInputValue = $('#discountInput').val();

            // if (discountInputValue) {
            //     pricediscount = parseInt(discountInputValue.replace(/\./g, ''));
            //     pricediscount = isNaN(pricediscount) ? 0 : pricediscount;
            // }

            $('.total_balance').each(function() {
                $(this).text(formatCurrency(totalPrice - totalDeposit - totalDiscount)); // tiền còn lại
            });

            $('.total_amount').each(function() {
                $(this).text(formatCurrency(totalPrice));
            });
            // giảm giá

            $('.total_discount').each(function() {

                $(this).text(formatCurrency(totalDiscount));
            });

            $('.total_deposit').each(function() {
                $(this).text(formatCurrency(totalDeposit));
            });

            //  $('#total_balance').text(formatCurrency(totalPrice));
            // $('#total_deposit').text(formatCurrency(totalPrice));
            return totalPrice;
        }

        function formatDate(inputDate) {
            // Chia chuỗi ngày thành các phần tử: năm, tháng, ngày
            var parts = inputDate.split('-');
            // Định dạng lại chuỗi ngày
            var formattedDate = parts[2] + '/' + parts[1] + '/' + parts[0];
            return formattedDate;
        }

        function formatCurrency(amount) {
            if (!amount || isNaN(amount)) {
                return '0 VND'; // Nếu amount không hợp lệ, trả về 0 VND
            }

            const parts = parseFloat(amount).toFixed(2).toString().split('.');
            const integerPart = parts[0];
            const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            return formattedInteger + ' VND';
        }

        function getCurrentDate() {
            const currentDate = new Date();
            const year = currentDate.getFullYear();
            const month = String(currentDate.getMonth() + 1).padStart(2, '0'); // Thêm 1 vì tháng bắt đầu từ 0
            const day = String(currentDate.getDate()).padStart(2, '0');

            return `${year}-${month}-${day}`;
        }
        $('[id="date-book-room-booking"]').val(getCurrentDate());

        function countBookings() {
            let bookingList = document.getElementById("list-booking");
            let rows = bookingList.getElementsByTagName("tr");
            return rows.length; // Trả về số lượng hàng <tr>
        }

        // Kiểm tra nếu danh sách đặt phòng có ít nhất một hàng
        function hasBookings() {
            return countBookings() > 0;
        }
        $(document).on('click', '.add-book-room', function() {
            $('#list-booking-edit').empty();
            $('#myModal-booking').modal('show');
            $('[id="select-option-pttt"]').hide();
            // var roomId = $(this).data('id');
            // var roomTypeId = $(this).data('room_type_id');
            allStaffandCustomerSource()
            // $('#myModal-booking-edit').modal('hide');
            hasBookings() ? "" : ($('.total_balance').text(0), $('.total_amount').text(0), $(
                '.total_deposit').text(0)), $('.total_discount').text(0);

            // addRoomInBooking(roomId, roomTypeId);
            var selectedCheckboxes = [];

        });
        $('.modal--search-customer').on('click', function() {
            showCustomer("");
            $('#addCustomerModal').modal('show');
            $('#myModal-booking').modal('hide');
            $('#addCustomerModal').on('shown.bs.modal', function() {
                document.body.classList.add('modal-open');
                $('#addCustomerModal').addClass('z__index-mod');
            });
        });
        $('.add-customer-booked').on('click', function() {
            let selectedCustomer = $('#show-customer input[type="radio"]:checked');
            if (selectedCustomer.length === 0) {
                notify('error', 'Vui lòng chọn một khách hàng');
                return;
            }
            let Id = selectedCustomer.data('id');
            findCustomerById(Id)
        });

        $(document).on("dblclick", "#data-table tbody tr", function() {
            let customerId = $(this).find('input[type="radio"]').data("id");
            if (!customerId) {
                return;
            }
            findCustomerById(customerId);
        });

        function getDatesBetween(checkInDate, checkInTime, checkOutDate, checkOutTime, room, roomType, adult,
            note,
            deposit, discount, roomBookingId) {

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
                dates.push({
                    roomType: roomType,
                    room: room,
                    dateIn: formattedDate,
                    dateOut: formattedDateOut,
                    adult: adult,
                    note: note,
                    deposit: deposit,
                    discount: discount,
                    bookingId: roomBookingId
                });

                break;
            }
            return dates;
        }

        function findCustomerById(id) {
            $.ajax({
                url: findCustomerUrl,
                type: 'GET',
                data: {
                    id: id,
                },
                success: function(data) {
                    $('input[name="name"]').val(data.data.name);
                    $('input[name="phone"]').val(data.data.phone);
                    $('input[name="customer_code"]').val(data.data.customer_code);
                    $("#select-customer-source").val(data.data.group_code).change();
                    $("#select-customer-source-edit").val(data.data.group_code).change();
                    $('#addCustomerModal').modal('hide');
                    $('#myModal-booking').modal('show');
                    $('#loading').hide();
                },
                error: function(error) {
                    $('#loading').hide();
                    console.log('Error:', error);
                }
            });
        }
        $(document).on('click', '.close_modal', function() {
            $('#myModal-booking').modal('hide');
            $('#myModal-booking-edit').modal('hide');
            $("#myModal-check-in-edit").modal('hide');
        });
        $(document).on('click', '.close_modal_booked_room', function() {
            $('#addRoomModal').modal('hide');
            $('#addCustomerModal').modal('hide');
            $('#changeRoomModal').modal('hide');
        });
        $('.delete-room-booking').on('click', function() {
            $('#list-booking tr').each(function() {
                var checkbox = $(this).find('input[type="checkbox"]');
                if (checkbox.prop('checked')) {

                    $(this).remove();
                    let totalPrice = 0;
                    totalPrice = calculateTotalPrice();
                    $('#total_amount').text(formatCurrency(totalPrice));
                    // $('#total_amount').text(formatCurrency(totalPrice));
                    // $('#total_balance').text(formatCurrency(totalPrice));
                }
            });
        });
        // add
        $(document).off("click", ".btn-book").on("click", ".btn-book", function(e) {
            const dataRowValue = $(this).data('row');
            const method = $(this).attr('data-method');

            if (method == "check_in") {
                $('.booking-form').data('method', 'check_in');
                if (validateAllFields(validatorForm)) {
                    $('.booking-form').submit(); // Gửi form
                }
            } else {
                $('.booking-form').data('row', dataRowValue);
                $('.booking-form').data('method', 'booked_room');
                if (validateAllFields(validatorForm)) {
                    $('.booking-form').submit(); // Gửi form
                }
            }

        });

        function validatePhone(value) {
            const allErrors = document.querySelectorAll("[id='phone_error']");
            const phoneInputs = document.querySelectorAll("input[name='phone']");
            const trimmed = value.trim();

            let isValid = true;

            allErrors.forEach((errorSpan, index) => {
                const input = phoneInputs[index];

                if (trimmed === "") {
                    errorSpan.textContent = "Số điện thoại không để trống.";
                    input.classList.add("is-invalid");
                    isValid = false;
                } else if (!/^\d+$/.test(trimmed)) {
                    errorSpan.textContent = "Số điện thoại chỉ được chứa chữ số.";
                    input.classList.add("is-invalid");
                    isValid = false;
                } else {
                    errorSpan.textContent = "";
                    input.classList.remove("is-invalid");
                }
            });

            return isValid;
        }



        $('.booking-form').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serializeArray();
            let formObject = {};
            formData.forEach(function(field) {
                formObject[field.name] = field.value;
            });
            if (!validatePhone(formData[2]['value'])) {
                    return;
                }
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
                var discount = $(this).closest('tr').find('input[name="discount"]').val();
                // console.log(roomId, roomTypeId, checkInDate, checkInTime, checkOutDate, checkOutTime, adult, note);
                // const errorDiv = document.querySelector('.message-error');

                // if (new Date(checkOutDate) < new Date(checkInDate)) {

                //     errorDiv.textContent = `Ngày trả phòng phải lớn hơn ngày nhận phòng`;
                //     errorDiv.classList.add('alert', 'alert-danger');
                //     errorDiv.style.display = 'block';
                //     hasError = false;
                //     return false;
                // }
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
                    discount: discount,
                });

            });

            if (hasError) {
                roomData.forEach(function(item) {
                    const roomDates = getDatesBetween(item['checkInDate'], item['checkInTime'],
                        item['checkOutDate'], item['checkOutTime'], item['roomId'], item[
                            'roomTypeId'],
                        item['adult'], item['note'], item['deposit'], item['discount'], "");

                    roomDates.forEach(function(date, index) {
                        formData.push({
                            name: 'room[]',
                            value: JSON.stringify(date)
                        });
                    });
                })
                const methodValue = $(this).data('method') || 'default_value';
                formData.push({
                    name: 'method',
                    value: methodValue,
                });

                // formData.push({
                //     name: 'is_method',
                //     value: 'receptionist',
                // });
                let shouldSubmit = true;
                formData.some(function(item) {
                    if (item.name === 'room[]') {
                        const data = item.value;
                        let dataArray = JSON.parse(data);
                        const timeCheckIn = dataArray['dateIn'];
                        const timeCheckOut = dataArray['dateOut'];
                        // const resultData = validator(timeCheckIn, timeCheckOut, dataRowValue);
                        // if (!resultData) {
                        //     shouldSubmit = false;
                        //     return true;
                        // }
                    }

                });
               


                let url = $(this).attr('action');
                if (shouldSubmit) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: formData,
                        success: function(response) {
                            if (response.success) {
                                notify('success', response.success);
                                $('#myModal-booking').modal('hide');
                                $('#list-booking').empty();
                                $('#name, #phone, #name_book').val("");
                                //    loadRoomBookings();
                                let selectedDate = $('#startDate').val();
                                initGridMain('', selectedDate);
                                // window.location.reload();

                            } else {
                                notify('error', response.error);
                            }
                        },
                    });
                }
            }

        });

        function allStaffandCustomerSource() {
            $.ajax({
                url: getCustomerStaff,
                type: 'GET',
                success: function(data) {

                    var selected_customer_source = $('#select-customer-source');
                    selected_customer_source.empty();
                    let option = `<option value="">Chọn nguồn khách hàng</option>`;
                    data.customerSourse.forEach(function(item) {
                        if (item.id == data.option_customer_source) {
                            option +=
                                `<option value="${item.source_code}" selected>${item.source_name}</option>`;
                        } else {
                            option +=
                                `<option value="${item.source_code}">${item.source_name}</option>`;
                        }
                    });
                    selected_customer_source.append(option);
                    // nhân viên
                    var selected_select_staff = $('#select-staff');
                    selected_select_staff.empty();
                    let option_staff = `<option value="">Chọn nhân viên</option>`;
                    data.admin.forEach(function(item) {
                        if (item.id == data.option_customer_source) {
                            option_staff +=
                                `<option value="${item.id}" selected>${item.username}</option>`;
                        } else {
                            option_staff +=
                                `<option value="${item.id}">${item.username}</option>`;
                        }
                    });
                    selected_select_staff.append(option_staff);
                    $('#loading').hide();
                },
                error: function(error) {
                    $('#loading').hide();
                    console.log('Error:', error);
                }
            });
        }
        $('.add-room-list').on('click', function() {

            let dataListValue = $(this).attr('data-list');
            const selectedCheckboxes = [];
            $('#show-room input[type="checkbox"]:checked').each(function() {
                const checkboxData = {
                    room: $(this).data('id'),
                    room_type: $(this).data('room_type_id'),
                    date: $(this).data('date')
                };
                selectedCheckboxes.push(checkboxData);
            });
            addRoomInBooking(selectedCheckboxes, $(`#${dataListValue}`))
        });

        function addRoomInBooking(data, list) {
            $('#loading').show();
            $.ajax({
                url: checkRoomBookingUrl,
                type: 'POST',
                data: {
                    data: JSON.stringify(data)
                },
                success: function(response) {
                    var tbody = list;

                    let targetId = list[0]?.id;
                    targetId === 'list-booking-edit' ?
                        $('#list-booking').empty() :
                        targetId === 'list-booking' ?
                        $('#list-booking-edit').empty() :
                        null;
                    if (response.status === 'success') {
                        const seenRooms = new Set();
                        let totalPrice = 0;

                        response.data.forEach(item => {


                            let date = new Date(item.date);
                            date.setDate(date.getDate() + 1);
                            const roomId = item.room["id"];
                            const roomTypeId = item.room_type["id"];
                            const roomDate = item.date;
                            const key = `${roomId}-${roomTypeId}-${roomDate}`;

                            if (seenRooms.has(key)) {
                                return;
                            }
                            seenRooms.add(key);
                            // item.room['room_type']['room_type_price']['setup_pricing']['check_in_time']
                            // item.room['room_type']['room_type_price']['setup_pricing']['check_out_time']
                            var tr = `
                            <tr  data-status="0" data-room-id="${roomId}"  data-room-type-id="${roomTypeId}" data-date="${item.date}">
                                <td>
                                    <input type="checkbox">
                                </td>

                                <td>
                                    <p class="room__name"> ${item.room['room_number']}</p>
                                </td>
                                 <td>
                                     <input type="number" min="1" name="adult" class="form-control adult"  value="1"  >

                                </td>
                                <td >
                                    <select id="bookingType" class="form-select" name="optionRoom" style="width: 93px; font-size:15px">
                                         <option value="ngay">Ngày</option>
                                         <option value="gio">Giờ</option>
                                    </select>
                                </td>
                                 <td>
                                    <div class="d-flex align-items-center justify-content-start" style="gap: 3px">
                                        <input type="date" name="checkInDate" id="date-book-room" class="form-control date-book-room"  value="${item.date}" readonly>

                                        <input type="time" name="checkInTime" id="time-book-room" class="form-control time-book-room"   value="${item.checkin_datetime}">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-start" style="gap: 3px">
                                        <input type="date" name="checkOutDate"  class="form-control date-book-room" readonly  value="${date.toISOString().split('T')[0]}">

                                        <input type="time" name="checkOutTime" id="time-book-room" class="form-control time-book-room"  value="${item.checkin_datetime}">

                                    </div>
                                </td>
                                <td>
                                     <p id="price" data-price="${item.room['room_type']['room_type_price']['unit_price']}">${formatCurrency(item.room['room_type']['room_type_price']['unit_price'])}</p>
                                </td>
                                <td>
                                      <input type="text" class="form-control deposit number-input money-input"  name="deposit"  placeholder="0">
                                </td>
                                 <td>
                                      <input type="text" class="form-control discount number-input-discount money-input"  name="discount"  placeholder="0">
                                </td>
                                <td>
                                    <input type="text" name="note_room" class="form-control note_room" value="" id="note">
                                </td>
                            </tr>
                        `;

                            // tbody.append(tr); 
                            const tableSelector = targetId === 'list-booking-edit' ?
                                '#list-booking-edit' : '#list-booking';
                            let isDuplicate = $(`${tableSelector} tr`).filter(function() {
                                return $(this).attr('data-room-id') == roomId &&
                                    $(this).attr('data-room-type-id') ==
                                    roomTypeId &&
                                    $(this).attr('data-date') == item.date;
                            }).length > 0;

                            if (!isDuplicate) {
                                tbody.append(tr);
                            }
                        })
                        totalPrice = calculateTotalPrice();

                        $('#loading').hide();
                        let totalDeposit = 0;
                        let totalBalance = 0;
                        $('tr').find('input.deposit').on('blur', function() {
                            let rowTotal = 0;
                            $('tr').each(function() {
                                $(this).find('input.deposit').each(function() {
                                    let depositValue = $(this).val()
                                        .replace(/[,.]/g, '');
                                    let numericDeposit = parseInt(
                                        depositValue) || 0;
                                    rowTotal += numericDeposit;
                                });
                            });

                            $('.total_deposit').text(formatCurrency(rowTotal));
                            let priceString = $('.total_discount').text();
                            let price = parseInt(priceString.replace(/\./g, ""), 10);
                            price = isNaN(price) ? 0 : price;
                            totalBalance = totalPrice - rowTotal - price;
                            $('.total_balance').text(formatCurrency(totalBalance));
                        });
                        $('tr').find('input.discount').on('blur', function() {
                            let rowTotal = 0;
                            $('tr').each(function() {
                                $(this).find('input.discount').each(function() {
                                    let depositValue = $(this).val()
                                        .replace(/[,.]/g, '');
                                    let numericDeposit = parseInt(
                                        depositValue) || 0;
                                    rowTotal += numericDeposit;
                                });
                            });

                            $('.total_discount').text(formatCurrency(rowTotal));
                            let priceString = $('.total_deposit').text();
                            let price = parseInt(priceString.replace(/\./g, ""), 10);
                            price = isNaN(price) ? 0 : price;
                            totalBalance = totalPrice - rowTotal - price;
                            $('.total_balance').text(formatCurrency(totalBalance));
                        });

                        $('#addRoomModal').modal('hide');
                        $('#myModal-booking').modal('show');
                        document.body.classList.remove("modal-open");
                    } else if (response.status === 'error') {
                        $('#loading').hide();
                        var tr = ``;
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

        $('#selected-name-phong, #selected-hang-phong, #date-chon-phong-out, #date-chon-phong-in, #status-room')
            .on('change', function() {
                var selectedOptionHangPhong = $('#selected-hang-phong').val();
                var selectedOptionNamePhong = $('#selected-name-phong').val();
                var selectedOptionStatusPhong = $('#status-room').val();
                const roomIds = [];
                $('#list-booking tr').each(function() {
                    const roomId = $(this).attr('data-room-id');
                    const dateId = $(this).attr('data-date');
                    if (roomId && dateId) {
                        roomIds.push({
                            roomId: roomId,
                            dateId: dateId
                        });
                    }
                });
                const checkInDateValue = $('#date-chon-phong-in').val();
                const checkOutDateValue = $('#date-chon-phong-out').val();

                showRoom(roomIds, checkInDateValue, checkOutDateValue, selectedOptionHangPhong,
                    selectedOptionNamePhong,
                    selectedOptionStatusPhong)
            });

        $('.add-room-booking').on('click', function() {
            let roomIds = [];
            $('.add-room-list').attr('data-list', 'list-booking');
            $('#list-booking tr').each(function() {
                const roomId = $(this).attr('data-room-id');
                const dateId = $(this).attr('data-date');
                if (roomId && dateId) {
                    roomIds.push({
                        roomId: roomId,
                        dateId: dateId
                    });
                }


            });
            const checkInDateValue = $('#date-book-room-booking').val();

            // Lấy giá trị của input checkOutDate
            //   const checkOutDateValue = getCurrentDate();
            showRoom(roomIds, checkInDateValue, checkInDateValue, '', '')
            $('#addRoomModal').modal('show');
            $('#myModal-booking').modal('hide');
            $('#addRoomModal').on('shown.bs.modal', function() {
                document.body.classList.add('modal-open');
                $('#addRoomModal').addClass('z__index-mod');
            });

        });

        function showRoom(data = "", checkInDateValue = "", checkOutDateValue = "", selectedOptionHangPhong =
            "",
            selectedOptionNamePhong = "", selectedOptionStatusPhong = "") {
            $('#loading').show();
            $('[id="date-chon-phong-in"]').val(checkInDateValue);
            $('[id="date-chon-phong-out"]').val(checkOutDateValue);
            $.ajax({
                url: showRoomUrl,
                type: 'POST',
                data: {
                    roomIds: data,
                    checkInDate: checkInDateValue,
                    checkOutDate: checkOutDateValue,
                    optionHangPhong: selectedOptionHangPhong,
                    optionNamePhong: selectedOptionNamePhong,
                    optionStatusPhong: selectedOptionStatusPhong,
                },
                success: function(data) {
                    var tbody = $('#show-room');
                    const dataNew = data.data;
                    let seenRooms = new Set();
                    tbody.empty();


                    dataNew.forEach(function(item) {
                        let rowClass = '';
                        let isFirst = !seenRooms.has(item.room_number);
                        seenRooms.add(item.room_number);

                           if (item.room_fix == 1) {
                                item.check_booked = 'Phòng đang sửa';
                                rowClass =  'background-gray'; // bạn có thể chọn màu khác nếu muốn
                              
                            } else {
                                if (!isFirst) {
                                    if (item.check_booked === 'Đã nhận') {
                                        rowClass = "background-red";
                                    } else if (item.check_booked === 'Đã đặt') {
                                        rowClass = 'background-yellow';
                                    } else if (item.check_booked === 'Trống') {
                                        rowClass = "background-primary";
                                    }
                                } else {
                                    if (item.check_booked === 'Đã nhận') {
                                        rowClass = "background-red";
                                    } else if (item.check_booked === 'Đã đặt') {
                                        rowClass = 'background-yellow';
                                    } else if (item.check_booked === 'Trống') {
                                        rowClass = "background-primary";
                                    } else {
                                        rowClass = "background-white";
                                    }
                                }
                            }
                        
                        let firstRowClass = isFirst ? "first-row" : "";
                        var tr = `
                        <tr class="${firstRowClass}">
                            <td style="${isFirst ? 'font-weight: bold;' : ''}" class="text-left"> ${item.room_type['name']} </td>
                            <td style="${isFirst ? 'font-weight: bold;' : ''}"class="text-left"> ${item.room_number} </td>
                            <td style="${isFirst ? 'font-weight: bold;' : ''}"class="text-left"> ${formatDate(item.date)} </td>
                            <td style="${isFirst ? 'font-weight: bold;' : ''}"class="text-left ${rowClass}"> ${item.check_booked} </td>
                            <td style="${isFirst ? 'font-weight: bold;' : ''}"class="text-right"> ${formatCurrency(item.room_type.room_type_price['unit_price'])} </td>
                            <td>
                                <input type="checkbox" ${item.status == 1 ? 'disabled' : ''} ${item.checkbox !== undefined ? 'checked disabled' : ''} data-date="${item.date}" data-id="${item.id}" data-room_type_id="${item.room_type_id}" id="checkbox-${item.id}">
                            </td>
                        </tr>
                    `;
                        tbody.append(tr);

                    });
                    // hạng phòng
                    var selected_hang = $('#selected-hang-phong');
                    selected_hang.empty();
                    let option = `<option value="">Chọn loại phòng</option>`;
                    data.roomType.forEach(function(item) {
                        if (item.id == data.option_hang_phong) {
                            option +=
                                `<option value="${item.id}" selected>${item.name}</option>`;
                        } else {
                            option += `<option value="${item.id}">${item.name}</option>`;
                        }
                    });
                    selected_hang.append(option);

                    // tên phòng
                    var selected_name = $('#selected-name-phong');
                    selected_name.empty();
                    let options = `<option value="">Chọn tên phòng</option>`;


                    data.room.forEach(function(item) {
                        if (item.id == data.option_name_phong) {
                            options +=
                                `<option value="${item.id}" selected>${item.room_number}</option>`;
                        } else {
                            options +=
                                `<option value="${item.id}">${item.room_number}</option>`;
                        }
                    });

                    selected_name.append(options);
                    // trạng thái phòng
                    // var selected_status = $('#status-room');
                    // selected_status.empty();
                    // let status = ``;
                    // if(data.option_status_phong === null){
                    //      status += `<option value="">Chọn trạng thái phòng</option>`;
                    // }else{
                    //      status += `<option value="${data.option_status_phong}">${data.option_status_phong}</option>`;
                    // }
                    // selected_status.append(status);
                    $('#loading').hide();
                },
                error: function(error) {
                    $('#loading').hide();
                    console.log('Error:', error);
                }
            });
        }

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
                url: searchCustomerUrl,
                type: 'GET',
                data: {
                    name: customerName,
                },
                success: function(response) {
                    // notify('success', response.success);
                    // $('.note-booking').html(note);
                    // $('#noteModal').modal('hide');
                    // if (response.status == 'success') {
                    //     if (response.data !== null) {
                    //         $('#phone').val(response.data['phone']);
                    //     } else {
                    //         $('#phone').val('');
                    //     }

                    // }
                    let selectPhone = $('#selectphone');

                    if (response.status == 'success') {

                        selectPhone.empty();
                        // Nếu có số điện thoại, cập nhật giá trị vào select
                        if (response.data.length > 0) {
                            // selectPhone.append('<option value="">' + 'Chọn số điện thoại' + '</option>');
                            response.data.forEach(function(phoneInfo) {
                                selectPhone.append('<option value="' + phoneInfo.phone +
                                    '">' + phoneInfo.phone + '</option>');
                            });
                        } else {
                            // Nếu không có số điện thoại, thêm tùy chọn "Chưa có số điện thoại"
                            selectPhone.empty(); // Xóa tất cả các tùy chọn hiện tại trong select
                            selectPhone.append(
                                '<option value="">Chưa có số điện thoại</option>'
                            ); // Thêm tùy chọn mới
                            selectPhone.val('').trigger(
                                'change'
                            ); // Đặt giá trị mặc định là "Chưa có số điện thoại" và kích hoạt lại select2
                        }
                    }

                },
                error: function(xhr, status, error) {

                    // alert('Có lỗi xảy ra khi lưu ghi chú!');
                }
            });
        }

    });

    function showCustomer(value = "", option_customer_source = "") {
        $('#loading').show();
        $.ajax({
            url: searchCustomerUrl,
            type: 'GET',
            data: {
                name: value,
                option_customer_source: option_customer_source
            },
            success: function(data) {
                // <p data-id="${ item.id }" data-room_type_id="${ item.room_type_id }" class="add-book-room" id="add-book-room">Đặt phòng</p>
                var tbody = $('#show-customer');
                tbody.empty();
                data.data.forEach(function(item) {
                    var tr = `
                <tr class="customer-row">
                    <td class="text-left "> ${item.customer_code} </td>
                    <td class="text-left "> ${item.name} </td>
                    <td class="text-right"> ${item.phone} </td>
                    <td class="text-left "> ${item.group_code} </td>
                    <td class="text-center">
                        <input type="radio" name="customer_select" data-id="${item.id}">
                    </td>
                </tr>
            `;
                    tbody.append(tr);
                });
                // hạng phòng
                var selected_customer_source = $('#selected-customer-source');
                selected_customer_source.empty();
                let option = `<option value="">Chọn nguồn khách hàng</option>`;
                data.customerSourse.forEach(function(item) {
                    if (item.id == data.option_customer_source) {
                        option +=
                            `<option value="${item.source_code}" selected>${item.source_name}</option>`;
                    } else {
                        option +=
                            `<option value="${item.source_code}">${item.source_name}</option>`;
                    }
                });
                selected_customer_source.append(option);
                $('#loading').hide();
            },
            error: function(error) {
                $('#loading').hide();
                console.log('Error:', error);
            }
        });
    }

    function loadScript(view, callback) {

        let scriptId = 'view-script';
        let oldScript = document.getElementById(scriptId);
        if (oldScript) {
            oldScript.remove(); // Xóa script cũ trước khi thêm mới
        }
        let script = document.createElement('script');
        script.id = scriptId;
        // script.type = 'module';
        script.src = `{{ asset('assets/admin/js/${view}-main.js') }}?t=${new Date().getTime()}`;
        script.onload = function() {
            if (typeof initViewScript === "function") {
                initViewScript(); // Gọi lại hàm này sau khi script được tải
            }
            if (typeof initViewScriptGird === "function") {
                initViewScriptGird(); // Gọi lại hàm này sau khi script được tải
            }
            if (typeof callback === "function") {
                callback();
            }
        };

        document.body.appendChild(script);
    }
    $(document).ready(function() {
        $('.filter-btn').on('click', function() {
            $('.filter-sidebar').addClass('open');
            $('.overlay').addClass('show');
        });

        $('.close-filter').on('click', function() {
            $('.filter-sidebar').removeClass('open');
            $('.overlay').removeClass('show');
        });

        $('.overlay').on('click', function() {
            $('.filter-sidebar').removeClass('open');
            $(this).removeClass('show');
        });
        $('.reload-btn').on('click', function() {
            window.location.reload();
            let selectedDate = $('#startDate').val();
            initGridMain('', selectedDate);
        });
    });



    document.addEventListener("DOMContentLoaded", function() {
        const buttons = document.querySelectorAll(".view-toggle button");

        // Lấy trạng thái lưu trữ từ LocalStorage, mặc định là 'list'
        let savedView = localStorage.getItem('selectedView') || 'list';
        setActiveButton(savedView);

        buttons.forEach(button => {
            button.addEventListener("click", function() {
                let selectedView = this.getAttribute("onclick").match(/'([^']+)'/)[1];
                localStorage.setItem('selectedView', selectedView);
                setActiveButton(selectedView);
            });
        });

        function setActiveButton(view) {
            buttons.forEach(btn => {
                btn.classList.remove("active");
                btn.querySelector(".text").style.display = "none";
            });

            let activeButton = document.querySelector(`[onclick="changeView('${view}')"]`);
            if (activeButton) {
                activeButton.classList.add("active");
                activeButton.querySelector(".text").style.display = "inline";
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        let savedView = localStorage.getItem('selectedView') || 'list';

        changeView(savedView);
    });

    function changeView(view) {
        document.querySelectorAll('.view').forEach(el => el.style.display = 'none');
        document.getElementById(view + 'View').style.display = 'block';
        loadScript(view);
        localStorage.setItem('selectedView', view);


    }
</script>
<style scoped>
    .search-container {
        width: 50%;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-content {
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .modal-dialog {
        display: flex !important;
        align-items: center;
        justify-content: center;
        max-width: 97vw !important;
        max-height: 90vh;
        width: 100%;
        margin: auto !important;
    }

    .delete-room-booking,
    .delete-room-booking-edit,
    .delete-room-booking-edit-letan {
        border: 1px solid #f03514;
        border-radius: 8px;
        cursor: pointer;
        color: #f03514;
        padding: 0px 10px 0px 10px;
    }

    .financial-item {
        display: flex;
        justify-content: space-between;
        padding: 5px;
    }

    .table-responsive {
        display: block;
        height: 170px !important;
        overflow-y: scroll;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .date-book-room {
        width: 115px !important;
        padding: 2px 7px !important;
    }

    .adult {
        width: 60px !important;
        height: 30px;
        text-align: center;
    }

    .deposit,
    .discount {
        padding: 0px 3px !important;
        text-align: end;
        /* width: 135px !important; */
    }

    .table-responsive {
        overflow-y: scroll;
        height: auto;
        scrollbar-width: none;
    }

    #list-booking tr,
    #list-booking-edit tr {
        vertical-align: top;
    }

    #bookingForm .form-control,
    #bookingForm .form-select {
        height: 36px;
        border-radius: 4px;
    }

    .financial-list {
        width: 30%;
        list-style-type: none;
        padding: 0;
    }

    .search-container {
        display: flex;
        align-items: center;
        max-width: 600px;
        width: 100%;
    }

    /* Căn chỉnh dropdown */
    .dropdown-toggle {
        width: 160px;
        text-align: left;
        white-space: nowrap;
    }

    .css-main-input-pttt {
        text-align: right;
        padding: 1px 4px !important;
        border: none;
        outline: none;
        border-radius: 0 !important;
        border-bottom: 1px solid #000;
    }

    /* Ô input */
    .form-control {
        border-radius: 0 8px 8px 0;
    }

    /* Dropdown menu */
    .dropdown-menu {
        width: 160px;
    }

    /* Hiệu ứng khi chọn */
    .dropdown-item.active,
    .dropdown-item:hover {
        background: #0d6efd;
        color: white;
    }

    .main-booking-modal {
        background: #ddd;
        padding: 8px 10px;
        border-radius: 8px;
    }

    .add-room-booking,
    .add-room-booking-edit,
    .add_product_service_payment,
    .add-service-booking {
        padding: 4px 10px;
        border: 1px solid #337ab7;
        border-radius: 8px;
        cursor: pointer;
        color: #337ab7;
        white-space: nowrap;
    }

    .modal-content {
        max-height: 90vh;
        overflow-y: auto;
        scrollbar-width: none;
    }

    .alert-paragraph {
        display: inline-block;
        padding: 3px 20px;
        background-color: #f44336;
        color: white;
        border-radius: 5px;
        text-align: center;
        cursor: pointer;
    }

    .btn-dat-truoc {
        text-wrap: nowrap;
        color: #fff;
        background-color: #4634ff;
        border-color: #4634ff;
        border-radius: 5px;
        display: inline-block;
        padding: 3px 20px;
    }



    .overflow-add-room {
        overflow-y: scroll;
        height: 529px;
    }

    .filter-sidebar {
        position: fixed;
        top: 0;
        right: -400px;
        /* Ẩn khỏi màn hình */
        width: 400px;
        height: 100%;
        background: #fff;
        box-shadow: -2px 0 6px rgba(0, 0, 0, 0.2);
        transition: right 0.3s ease;
        z-index: 2000;
        padding: 20px;
    }

    hr {
        margin: 5px !important;
    }

    .filter-sidebar .action-buttons {
        position: absolute;
        bottom: 7rem;
        right: 1rem;
        left: 1rem;
        display: flex;
        justify-content: end;
        gap: 10px;
    }


    .filter-sidebar.open {
        right: 0;
    }

    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.4);
        z-index: 1500;
        display: none;
    }


    .filter-btn,
    .reload-btn {
        cursor: pointer;
        background: #fff;
        border: 1px solid #ccc;
        padding: 6px 13px;
        border-radius: 10px;
        transition: all 0.2s ease;
        height: 42px;
        position: relative;
    }

    .filter-btn .dot {
        position: absolute;
        position: absolute;
        top: 0px;
        right: -1px;
        width: 15px;
        height: 15px;
        background-color: red;
        border-radius: 50%;
        display: none;
    }

    .filter-btn:hover {
        background-color: #f5f5f5;
    }
</style>

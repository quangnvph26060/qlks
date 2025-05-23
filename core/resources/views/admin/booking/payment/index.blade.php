@extends('admin.layouts.master_iframe')
@section('panel')
    <div class="row">

        <div class="pagination-container"></div>

        <div class="card b-radius--10 mt-1 scroll-container-main mb-4">
            <div class="card-body p-0">
                <div class="table-responsive--md">
                    <table class="table--light style--two table" id="data-table">
                        <thead>
                            <tr>
                                <th>@lang('STT')</th>
                                <th>@lang('Hành động')</th>
                                <th>@lang('Mã thanh toán')</th>
                                <th>@lang('Mã nhận phòng')</th>
                                <th>@lang('Ngày chứng từ')</th>
                                <th>@lang('Tiền phòng')</th>
                                <th>@lang('Tiền dịch vụ')</th>
                                <th>@lang('Đặt cọc')</th>
                                <th>@lang('Giảm giá')</th>
                                <th>@lang('Khách đã trả')</th>
                                <th>@lang('Phương thức thanh toán')</th>
                                <th>@lang('Trạng thái')</th>
                                @can(['admin.hotel.room.type.edit', 'admin.hotel.room.type.status',
                                    'admin.hotel.room.type.destroy'])
                                @endcan
                            </tr>
                        </thead>
                        <tbody class="data-table">
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
        @include('admin.booking.partials.room_booking')
        @include('admin.booking.partials.room_booking_edit')
        @include('admin.booking.partials.confirm-room')
    </div>
@endsection

@can('admin.booking.all')
    @push('breadcrumb-plugins')
        {{-- <a class="btn btn-sm btn--primary" href="{{ route('admin.booking.all') }}">
            <i class="la la-list"></i>@lang('Tất cả các đặt phòng')
        </a> --}}
        <div class="d-flex">
            <div class="d-flex" style="gap: 10px">
                <a class="btn btn-sm btn--primary add-book-room" style="margin-left: 10px">
                    <i class="la la-plus"></i>
                </a>
                <a class="btn btn-sm btn--primary btn-submit-sync-book">
                    <i class="las la-sync"></i>
                </a>
            </div>

            <div class="form-group position-relative mb-0" style="display: flex;gap: 10px;">
                <input class="searchInput input-field-search-book" name="booking_code" placeholder="Mã thanh toán"
                    id="booking_code" style="width: 68% !important;">
                {{-- <input class="searchInput input-field-search-book" name="room_name" placeholder="Tên phòng" id="room_name"> --}}
                {{-- <div class="d-flex" style="gap: 10px">
                    <input type="date" class="form-control " id="date-chon-phong-in" style="height: 35px">
                    <input type="date" class="form-control " id="date-chon-phong-out" style="height: 35px">
                </div> --}}
                {{-- <input class="searchInput input-field-search-book" name="name" placeholder="Tên khách hàng" id="name_book"> --}}
                <button type="submit" class="btn btn-primary btn-submit-search-book">
                    <i class="las la-search"></i>
                </button>

            </div>
            {{-- <div style="  position: absolute;  right: 43px; display: flex;    gap: 10px;">
                <button onclick="toggleView('viewBox')" data-view="viewBox"
                    class="btn btn-primary btn-submit-search-book btn-toggle-view">
                    <svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="24" height="24">
                        <path fill="white"
                            d="M5.75 7.5h7.5a.75.75 0 0 1 0 1.5h-7.5a.75.75 0 0 1 0-1.5Zm0 5h7.5a.75.75 0 0 1 0 1.5h-7.5a.75.75 0 0 1 0-1.5Zm-4-10h6.5a.75.75 0 0 1 0 1.5h-6.5a.75.75 0 0 1 0-1.5ZM2 14a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-6a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm10.314-3.082L11.07 2.417A.25.25 0 0 1 11.256 2h4.488a.25.25 0 0 1 .186.417l-2.244 2.5a.25.25 0 0 1-.372 0Z">
                        </path>
                    </svg>
                </button>

                <button onclick="toggleView('viewModel')" data-view="viewModel"
                    class="btn btn-primary btn-submit-search-book btn-toggle-view">
                    <svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                        <path fill="white"
                            d="M1.75 2.5h10.5a.75.75 0 0 1 0 1.5H1.75a.75.75 0 0 1 0-1.5Zm4 5h8.5a.75.75 0 0 1 0 1.5h-8.5a.75.75 0 0 1 0-1.5Zm0 5h8.5a.75.75 0 0 1 0 1.5h-8.5a.75.75 0 0 1 0-1.5ZM2.5 7.75v6a.75.75 0 0 1-1.5 0v-6a.75.75 0 0 1 1.5 0Z">
                        </path>
                    </svg>
                </button>

            </div> --}}
        </div>
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
                        <table class=" table--light style--two table ">
                            <thead>
                                <tr>
                                    <th data-table="Hạng phòng" class="text-left">Hạng phòng</th>
                                    <th data-table="Phòng" class="text-left">Tên phòng</th>
                                    <th data-table="Ngày" class="text-left">Ngày</th>
                                    <th data-table="Trạng thái phòng" class="text-left">Trạng thái phòng</th>
                                    <th data-table="Giá" class="text-right">Giá</th>
                                    <th data-table="Thao tác">Thao tác</th>
                                </tr>
                            </thead>

                            <tbody id="show-room">

                            </tbody>

                        </table>
                    </div>
                    <div class="d-flex justify-content-end" style="gap: 10px;padding: 7px 31px">
                        <p data-row="booked" class=" btn-dat-truoc  add-room-list" style="cursor: pointer">Lưu
                        </p>
                        <p type="button" data-row="booked" class="alert-paragraph close_modal_booked_room">Hủy</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class="modal-dialog mt-3" style="max-width: 30%;">
                <div class="modal-content p-2">
                    <div class="modal-header">
                        <h5 class="modal-title">Thanh toán</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-unstyled " id="payment-info">
                          
                        </ul>
                      
                    </div>
                </div>
            </div>
        </div>
        
        @include('admin.booking.partials.change_room_booking')
        @include('admin.booking.partials.customer_booked')
    @endpush
@endcan

@push('script-lib')
    <script src="{{ asset('assets/admin/js/common.js') }}"></script>
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/payment.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/validator/validator.js') }}"></script>
    <script src="{{ asset('assets/admin/js/pagination.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/book.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/pagination.css') }}">
@endpush

@push('script')
    <script>
        var showRoomUrl = "{{ route('admin.booking.showRoom') }}";
        var checkRoomBookingUrl = '{{ route('admin.booking.checkRoomBooking') }}';
        var deleteRoomEdit = '{{ route('admin.room.booking.delete') }}';
        var searchCustomerUrl = '{{ route('admin.search.customer') }}';
        var paymentUrl = '{{ route('admin.booking.payment.list') }}';
        var paymentFind = '{{ route('admin.booking.payment.find') }}';
        var roomBookingEditUrl = "{{ route('admin.room.booking.edit', ['id' => ':id']) }}";
        var bookingDetailUrl = "{{ route('admin.booking.details', ['id' => ':id']) }}";
        var checkInUrl = "{{ route('admin.room.check.in', ['id' => ':id']) }}";
        var deleteBookedRoomUrl = "{{ route('admin.booking.delete-booked-room', ['id' => ':id']) }}";
        var checkBookedRoomUrl = "{{ route('admin.booking.check-booked-room-del', ['id' => ':id']) }}";
        var findCustomerUrl = '{{ route('admin.find.customer') }}';
        var getCustomerStaff = "{{ route('admin.get.customer.staff') }}";
        var changeRoomBooking = "{{ route('admin.booking.changeRoomBooking') }}";
    </script>

    <script>
        $(document).ready(function() {
            $("#date-chon-phong-in").on("change", function() {
                let checkInDate = new Date($(this).val());
                if (!isNaN(checkInDate.getTime())) {
                    checkInDate.setDate(checkInDate.getDate() + 1); // Thêm 1 ngày
                    let checkOutDate = checkInDate.toISOString().split('T')[0]; // Format YYYY-MM-DD
                    $("#date-chon-phong-out").val(checkOutDate);
                }
            });
        });
    </script>
@endpush
@push('style')
    <style scoped>
        .table .background-tr {
            height: 37px;
        }

        .data-table td {
            height: 30px !important;
            overflow: hidden;
            white-space: nowrap;
        }

        .dt-column-title {
            color: white !important;
        }

        .mt-10 {
            margin-top: -9px !important;
        }

        .text-left {
            text-align: left !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center;
        }

        #show-room tr td,
        #show-room-change tr td {
            padding: 6px 2px !important;
            line-height: 0;
            /* Giảm chiều cao dòng */
        }

        .table-responsive--md {
            overflow-x: auto;
            /* Enable horizontal scrolling if the table overflows */
        }

        /* Optional: Adjust the font size and padding for smaller screens */
        @media (max-width: 768px) {
            .table--light {
                font-size: 12px;
                /* Reduce font size on smaller screens */
            }

            .table td,
            .table th {
                padding: 5px;
                /* Reduce padding for more compact view */
            }
        }

        .booking-table td {
            white-space: unset;
        }

        .modal-open .select2-container {
            z-index: 9 !important;
        }




        /* Căn lề trái cho Mã khách hàng & Tên khách hàng */
        .customer-code,
        .customer-name,
        .customer-group {
            text-align: left !important;
            padding-left: 10px;
        }

        /* Căn lề phải cho Số điện thoại */
        .customer-phone {
            display: flex;
            justify-content: flex-end;
            /* Căn nội dung sang phải */
            align-items: center;
            /* Giữ nội dung ở giữa theo chiều dọc */
            text-align: right;
            padding-right: 10px;
        }


        /* Căn giữa cho cột Thao tác */
        .text-center {
            text-align: center !important;
        }

        .check_in_status {
            background: rgb(245, 243, 243);
            pointer-events: none;
            /* Ngăn mọi thao tác chuột */
            opacity: 0.6;
            /* Làm mờ để báo hiệu bị vô hiệu hóa */
        }

        /* Đảm bảo các input, checkbox, select trong hàng này bị vô hiệu hóa */
        .check_in_status input,
        .check_in_status select,
        .check_in_status textarea,
        .check_in_status button {
            pointer-events: none;
            background-color: #e9ecef;
            /* Màu nền giống disabled */
            cursor: not-allowed;
            /* Đổi con trỏ thành dấu cấm */
        }

        #data-table th {
            padding: 6px !important;
            line-height: 1 !important;
        }

        #dropdown-menu {
            left: 108px;
            position: fixed;
            z-index: 9999;
            background: white;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
@endpush

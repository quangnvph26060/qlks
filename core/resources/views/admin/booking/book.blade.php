@extends('admin.layouts.master_iframe')
@section('panel')
    <div class="row">
        {{-- <div class="col-lg-12" style="height: 10px">
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
        </div> --}}
        <div class="pagination-container"></div>
        <div class="card b-radius--10 mt-1">
            <div class="card-body p-0">
                <div class="table-responsive--md">
                    {{-- giữ --}}
                    <table class="table--light style--two table table-striped" id="data-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>@lang('Hành động')</th>
                                <th>@lang('STT')</th>
                                <th>@lang('Mã đặt hàng')</th>
                                <th>@lang('Số lượng phòng')</th>
                                <th>@lang('Ngày chứng từ')</th>
                                <th>@lang('Tên khách hàng')</th>
                                <th>@lang('Số điện thoại')</th>
                                <th>@lang('Số lượng người')</th>
                                <th>@lang('Tổng tiền')</th>
                                <th>@lang('Tổng đặt cọc')</th>
                                <th>@lang('Tổng giảm giá')</th>
                                @can(['admin.hotel.room.type.edit', 'admin.hotel.room.type.status',
                                    'admin.hotel.room.type.destroy'])
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
            @include('admin.booking.partials.room_booking')
            @include('admin.booking.partials.room_booking_edit')
            @include('admin.booking.partials.confirm-room')
        </div>
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
                <input class="searchInput input-field-search-book" name="booking_code" placeholder="Mã đặt phòng"
                    id="booking_code">
                <select class="searchInput input-field-search-book" name="room_code" id="select_room_number"></select>
                {{-- <div class="d-flex" style="gap: 10px">
            <input type="date" class="form-control " id="date-chon-phong-in" style="height: 35px">
            <input type="date" class="form-control " id="date-chon-phong-out" style="height: 35px">
          </div> --}}
                <input class="searchInput input-field-search-book" name="name" placeholder="Tên khách hàng" id="name_book">
                <button type="submit" class="btn btn-primary btn-submit-search-book">
                    <i class="las la-search"></i>
                </button>

            </div>
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
                            <label for="">Chọn hạng phòng</label>
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
        @include('admin.booking.partials.customer_booked')
    @endpush
@endcan

@push('script-lib')
<script src="{{ asset('assets/admin/js/common.js') }}"></script>
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/validator/validator.js') }}"></script>
    <script src="{{ asset('assets/admin/js/room_booking.js') }}"></script>
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
        var roomBookingUrl = '{{ route('admin.room.booking') }}';
        var roomBookingEditUrl = "{{ route('admin.room.booking.edit', ['id' => ':id']) }}";
        var bookingDetailUrl = "{{ route('admin.booking.details', ['id' => ':id']) }}";
        var checkInUrl = "{{ route('admin.room.check.in', ['id' => ':id']) }}";
        var deleteBookedRoomUrl = "{{ route('admin.booking.delete-booked-room', ['id' => ':id']) }}";
        var findCustomerUrl = '{{ route('admin.find.customer') }}';
        var getCustomerStaff = "{{ route('admin.get.customer.staff') }}";
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
        window.toggleRepresentatives = function(id, button) {
            const rows = document.querySelectorAll('[id="rep-' + id + '"]'); // Lấy tất cả các hàng có cùng ID
            rows.forEach(row => row.classList.toggle('show')); // Toggle từng hàng

            button.classList.toggle('collapsed'); // Toggle trạng thái button
        };
    </script>
@endpush
@push('style')
    <style scoped>
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

        #data-table tbody tr:hover {
            background-color: #f0f0f0;
            /* Màu nền khi hover */
            cursor: pointer;
            /* Con trỏ chuột đổi thành dạng pointer */
        }

        #show-room tr td {
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

        .pagination-container button:first-child {
            border-radius: 5px 0 0 5px;
        }

        .pagination-container button:last-child {
            border-radius: 0 5px 5px 0;
        }


        /* .background-yellow td,
            .background-red td,
            .background-primary td {
                color: white !important;
            } */

        #dropdown-menu {
            position: fixed; /* hoặc fixed */
            z-index: 9999; /* Số lớn để đè lên các phần khác */
            background: white; /* Đảm bảo nội dung rõ ràng */
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); /* Tạo hiệu ứng nổi bật */
        }


    
    </style>
@endpush

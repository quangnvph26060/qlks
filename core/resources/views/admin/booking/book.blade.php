@extends('admin.layouts.app')
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
        <div class="card b-radius--10 mt-1" >
            <div class="card-body p-0">  
                <div class="table-responsive--md">
                  
                    <table class="table--light style--two table table-striped" id="data-table">
                        <thead>
                            <tr>
                                <th>@lang('Hành động')</th>
                                <th>@lang('STT')</th>
                                <th>@lang('Mã đặt hàng')</th>
                                <th>@lang('Mã phòng')</th>
                                <th>@lang('Ngày chứng từ')</th>
                                <th>@lang('Ngày nhận')</th>
                                <th>@lang('Ngày trả')</th>
                                <th>@lang('Tên khách hàng')</th>
                                <th>@lang('Số điện thoại')</th>
                                <th>@lang('Số người')</th>
                                <th>@lang('Thành tiền')</th>
                                <th>@lang('Đặt cọc')</th>
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
        <a class="btn btn-sm btn--primary add-book-room">
            <i class="la la-plus"></i>@lang('Đặt phòng')
        </a>
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
                        <table class="table ">
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
                        <p data-row="booked" class=" btn-dat-truoc  add-room-list" style="cursor: pointer">Lưu
                        </p>
                        <p type="button" data-row="booked" class="alert-paragraph close_modal_booked_room">Hủy</p>
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
    <script  src="{{ asset('assets/admin/js/room_booking.js') }}"></script>
    <script  src="{{ asset('assets/admin/js/pagination.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/book.css') }}">
@endpush

@push('style')
    <style scoped>
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

        .pagination-container {
            display: flex;
            justify-content: center;
            /* margin-top: 20px; */
        }

        .pagination-container button {
            background-color: #4634ff;
            color: white;
            border: 1px solid #ddd;
            padding: 4px 10px;
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
        .body-wrapper {
    padding: 6px 25px 10px !important;
        }
        .pagination-container button.active {
            background-color: #4634ff;
            border-color: #4634ff;
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

        .background-primary {
            background: #0b138d;
        }

        .background-yellow {
            background-color: #eeddaa;
        }

        .background-white {
            color: #5b6e88;
            background-color: #f0f1f1;
        }

        .background-yellow td,
        .background-primary td {
            color: white !important;
        }
    #data-table {
        border-collapse: collapse; /* Gộp viền bảng */
    }
    #data-table td, #data-table th {
        line-height: 1 !important;
    }
#data-table td {
    height: 30px !important;
    overflow: hidden;
    white-space: nowrap; /* Ngăn xuống dòng để giảm chiều cao */
}


#data-table td, #data-table th {
    padding: 6px !important; /* Giảm padding để thu nhỏ chiều cao */
}


    </style>
@endpush

@push('script')
   <script>
        var showRoomUrl = "{{ route('admin.booking.showRoom') }}";
        var checkRoomBookingUrl =  '{{ route('admin.booking.checkRoomBooking') }}';
        var  searchCustomerUrl = '{{ route('admin.search.customer') }}';
        var roomBookingUrl =  '{{ route('admin.room.booking') }}';
        var roomBookingEditUrl = "{{ route('admin.room.booking.edit', ['id' => ':id']) }}";
        var bookingDetailUrl = "{{ route('admin.booking.details', ['id' => ':id']) }}";
        var checkInUrl = "{{ route('admin.room.check.in', ['id' => ':id']) }}";
        var deleteBookedRoomUrl = "{{ route('admin.booking.delete-booked-room', ['id' => ':id']) }}";
   </script>
@endpush

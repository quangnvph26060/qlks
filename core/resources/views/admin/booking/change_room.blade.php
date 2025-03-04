@extends('admin.layouts.master_iframe')
@section('panel')
<div class="pagination-container mb-1"></div>
    <div class="row">
        {{-- <div class="col-lg-12">
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
     
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--md">
                    <table class="table--light style--two table table-striped" id="data-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>@lang('Hành động')</th>
                                <th>@lang('STT')</th>
                                <th>@lang('Mã đổi phòng')</th>
                                <th>@lang('Số lượng phòng')</th>
                                <th>@lang('Ngày chứng từ')</th>
                                <th>@lang('Tên khách hàng')</th>
                                <th>@lang('Số điện thoại')</th>
                                <th>@lang('Số lượng người')</th>
                                <th>@lang('Tổng tiền')</th>
                                <th>@lang('Đặt cọc')</th>
                                <th>Giảm giá</th>

                                {{-- @can(['admin.hotel.room.type.edit', 'admin.hotel.room.type.status',
                                    'admin.hotel.room.type.destroy'])
                              
                                @endcan --}}
                            </tr>
                        </thead>
                        <tbody>
                            <thead class="data-table">

                            </thead>
                        </tbody>
                    </table>

                </div>
            </div>
          
        </div>
     
        {{-- @include('admin.booking.partials.room_booking') --}}
        @include('admin.booking.partials.confirm-room')
        @include('admin.booking.partials.check-in-room')
        @include('admin.booking.partials.check_in_room_edit')
        @include('admin.booking.partials.customer_booked')
        @include('admin.booking.partials.booked_room')
    </div>
    {{-- @include('admin.booking.partials.modals') --}}

    {{-- <x-confirmation-modal /> --}}
@endsection

@can('admin.book.room')
    @push('breadcrumb-plugins')
        {{-- <a class="btn btn-sm btn--primary" href="{{ route('admin.book.room') }}">
            <i class="la la-hand-o-right"></i>@lang('Thêm phòng mới')
        </a> --}}
        <a class="btn btn-sm btn--primary check-in-room"  style="margin-left: 10px">
            <i class="la la-plus"></i>
        </a>
        <a class="btn btn-sm btn--primary btn-submit-sync-book">
            <i class="las la-sync"></i>
        </a>
        <div class="form-group position-relative mb-0" style="display: flex;gap: 10px;">
            <input class="searchInput input-field-search-book" name="booking_code" placeholder="Mã nhận phòng" id="booking_code">
            <select class="searchInput input-field-search-book"  name="room_code" id="select_room_number"></select>
          {{-- <div class="d-flex" style="gap: 10px">
            <input type="date" class="form-control " id="date-chon-phong-in" style="height: 35px">
            <input type="date" class="form-control " id="date-chon-phong-out" style="height: 35px">
          </div> --}}
            <input class="searchInput input-field-search-book" name="name" placeholder="Tên khách hàng" id="name_book">
            <button type="submit" class="btn btn-primary btn-submit-search-book">
                <i class="las la-search"></i>
            </button>
          
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
    @endpush
@endcan

@push('script-lib')
    <script src="{{ asset('assets/admin/js/common.js') }}"></script>
    <script src="{{ asset('assets/validator/validator.js') }}"></script>
    <script  src="{{ asset('assets/admin/js/change_room.js') }}"></script>
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/book.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/pagination.css') }}">
@endpush

@push('script')
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
        function updateScroll() {
        const listBooking = document.getElementById("list-booking");
        const rows = listBooking.querySelectorAll("tr");

        if (rows.length > 3) {
            listBooking.style.display = "block";
            listBooking.style.height = "150px"; // Giới hạn chiều cao
            listBooking.style.overflowY = "auto"; // Hiển thị thanh cuộn
        } else {
            listBooking.style.height = "none";
            listBooking.style.overflowY = "visible"; // Không có thanh cuộn nếu ít hơn hoặc bằng 3 hàng
        }
    }

    updateScroll();
        window.toggleRepresentatives = function(id, button) {
            const rows = document.querySelectorAll('[id="rep-' + id + '"]'); // Lấy tất cả các hàng có cùng ID
            rows.forEach(row => row.classList.toggle('show')); // Toggle từng hàng

            button.classList.toggle('collapsed'); // Toggle trạng thái button
        };
        var allCheckInUrl =  "{{ route('admin.booking.all.change.room') }}";
        // var findCustomerUrl = '{{ route('admin.find.customer') }}';
        // var checkInDetailUrl = "{{ route('admin.booking.check.in.details', ['id' => ':id']) }}";
        // var allCheckInUrl =  "{{ route('admin.booking.all.check.in') }}";
        // var showRoomUrl = "{{ route('admin.booking.showRoom') }}";
        // var checkRoomBookingUrl =  '{{ route('admin.booking.checkRoomBooking') }}';
        // var  searchCustomerUrl = '{{ route('admin.search.customer') }}';
        // var roomBookingUrl =  '{{ route('admin.room.booking') }}';
        // var getRoomBookingUrl =  '{{ route('admin.get.room.booking') }}';
        // var checkInEditUrl = "{{ route('admin.check.in.edit', ['id' => ':id']) }}";
        // var bookingDetailUrl = "{{ route('admin.booking.details', ['id' => ':id']) }}";
        // var checkInUrl = "{{ route('admin.room.check.in', ['id' => ':id']) }}";
        // var deleteBookedRoomUrl = "{{ route('admin.booking.delete-booked-room', ['id' => ':id']) }}";
        // var getCustomerStaff = "{{ route('admin.get.customer.staff') }}";
        // var findRoomBookingIdUrl = '{{ route('admin.find.room.booking') }}';
        // var deleteRoomEdit = '{{ route('admin.room.booking.delete') }}';
        // var roomBook = "{{ route('admin.room.book') }}";
        // var CheckInUrl = "{{route('admin.room.booked.check.in')}}";
</script>
@endpush

@push('style')
    <style scoped>
          #data-table tbody tr:hover {
            background-color: #f0f0f0;
            cursor: pointer;
        }
        #show-room tr td {
            padding: 6px 2px !important;
            line-height: 0;   
        }
        #dropdown-menu {
            position: fixed; 
            z-index: 9999; 
            background: white; 
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
        .table-responsive--md {
            overflow-x: auto;
           
        }
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

        .delayed-checkout {
            background-color: #ffefd640;
        }



        .card {
            box-shadow: none;
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
    </style>
@endpush

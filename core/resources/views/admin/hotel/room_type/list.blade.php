@extends('admin.layouts.master_iframe')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive p-2">
                        <div class="pager-wrap  d-flex justify-content-center mb-1">
                            <div class="k-widget d-flex">
                                <div class="pagination-tb" style="font-size: 13px;margin: 0 auto">
                                    {{ $rooms->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                        <table class="table--light style--two table " id="data-table">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="" id="checkbox-room">
                                    </th>
                                    <th></th>
                                    <th>@lang('Hành động')</th>
                                    <th>@lang('STT')</th>

                                    <th>@lang('Loại phòng')</th>
                                    <th>@lang('Mã phòng')</th>
                                    <th>@lang('Tên phòng')</th>
                                    <th>@lang('Hướng phòng')</th>
                                    <th>@lang('Số người')</th>
                                    <th>@lang('Số giường')</th>
                                    {{-- <th>@lang('Hình ảnh')</th> --}}

                                    {{-- <th>@lang('Tiện nghi')</th>
                                    <th>@lang('Cở sở vật chất')</th>
                                    <th>@lang('Giá giờ')</th>
                                    <th>@lang('Giá ngày')</th>
                                    <th>@lang('Giá đêm')</th> --}}
                                    <th>@lang('Trạng thái')</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rooms as $id => $type)
                                    <tr data-id="{{ $type->id }}" class="{{ $id % 2 !== 0 ? 'bg-white' : 'bg-gray' }}">
                                        <td>
                                            <input type="checkbox" class="checkbox-item" data-id="{{ $type->id }}">
                                        </td>
                                        <td class="d-none-mobi">
                                            <button class="btn btn-link btn-toggle" type="button"
                                                onclick=" toggleRepresentatives('{{ $type->id }}', this)">
                                            </button>
                                        </td>

                                        <td style="width:20px" class="d-none-mobi">
                                            <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30"
                                                height="30" viewBox="0 0 21 21">
                                                <g fill="currentColor" fill-rule="evenodd">
                                                    <circle cx="10.5" cy="10.5" r="1" />
                                                    <circle cx="10.5" cy="5.5" r="1" />
                                                    <circle cx="10.5" cy="15.5" r="1" />
                                                </g>
                                            </svg>

                                            <div class="dropdown menu_dropdown_check_in" id="dropdown-menu"
                                                style="position:fixed">
                                                @can('admin.hotel.room.type.edit')
                                                    <div class="dropdown-item booked_room_edit">
                                                        <a href="{{ route('admin.hotel.room.type.edit', $type->id) }}"
                                                            style="color:black;padding:5px">
                                                            Sửa
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('admin.hotel.room.type.status')
                                                    <div class="dropdown-item booked_room">

                                                        @if ($type->status == 0)
                                                            <button class=" confirmationBtn"
                                                                data-action="{{ route('admin.hotel.room.type.status', $type->id) }}"
                                                                data-question="@lang('Bạn có chắc chắn muốn bật loại phòng này không?')">
                                                                Hoạt động
                                                            </button>
                                                        @else
                                                            <button class="confirmationBtn"
                                                                data-action="{{ route('admin.hotel.room.type.status', $type->id) }}"
                                                                data-question="@lang('Bạn có chắc chắn muốn vô hiệu hóa loại phòng này không?')">
                                                                Tắt hoạt động
                                                            </button>
                                                        @endif

                                                    </div>
                                                @endcan
                                                @can('admin.hotel.room.type.delete')
                                                    <div class="dropdown-item booked_room_detail"> <button
                                                            class=" btn-delete icon-delete-room" data-id="{{ $type->id }}"
                                                            data-modal_title="@lang('Xóa trạng thái')" type="button"
                                                            data-pro="0">Xóa</div>
                                                @endcan

                                            </div>

                    </div>
                </div>

                </td>

                <td data-label="STT" style="text-align:right">
                    @php
                        $stt = ($rooms->currentPage() - 1) * $rooms->perPage() + $id + 1;
                    @endphp
                    {{ $stt }}
                </td>

                <td data-label="Loại phòng" class="text-left">

                    {{ $type->roomType['name'] }}
                </td>
                <td data-label="Mã phòng" class="text-left">
                    {{ $type->code }}
                </td>
                <td data-label="Tên phòng" class="text-left">
                    {{ $type->room_number }}
                </td>
                <td data-label="Hướng phòng" class="text-left d-none-mobi">
                    {{ optional($type->direction)->name }}

                </td>
                <td data-label="Số người" style="width: 10px;" class=" text-right d-none-mobi">
                    {{ $type->total_adult }}
                </td>
                <td data-label="Số giường" style="width: 10px;" class=" text-right d-none-mobi">
                    {{ $type->beds }}
                </td>
                {{-- <td data-label="Hình ảnh">     
                                    @if (!empty($type->main_image))
                                            <i class="fa fa-check" style="color:green;text-align: center"></i>
                                                    @else
                                                <i class="fa fa-close" style="color:red;text-align: center"></i>
                                                    @endif
                                                        
                                </td> --}}
                {{-- <td data-label="Tiện nghi">
                                    @if ($type->amenities->count() > 0)
                                        <div class="float-inline-end">
                                            @foreach ($type->amenities->take(3) as $amenity)
                                                <span class="badge {{ getRandomColor() }} m-1 p-1 rounded-pill text-bg-primary">
                                                    {{ $amenity->title }}
                                                </span>
                                            @endforeach
                                            @if ($type->amenities->count() > 3)
                                            <span class="">
                                                ...
                                            </span>
                                        @endif
                                        </div>
                                    @else
                                        Chưa có tiện nghi nào
                                    @endif
                                </td> --}}
                {{-- <td data-label="Cơ sở vật chất">
                                    @if ($type->facilities->count() > 0)
                                        <div class="float-inline-end">
                                            @foreach ($type->facilities as $facility)
                                                <span class="badge {{ getRandomColor() }} m-1 p-1 rounded-pill text-bg-primary">
                                                    {{ $facility->title }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        Chưa có cơ sở vật chất
                                    @endif
                                </td> --}}

                {{-- <td data-label="Giá giờ">{{ showAmount($type->roomPriceNow()?->hourly_price) ?? '-----' }}</td>
                                <td data-label="Giá ngày">{{ showAmount($type->roomPriceNow()?->daily_price) ?? '-----' }}</td>
                                <td data-label="Giá đêm">{{ showAmount($type->roomPriceNow()?->overnight_price) ?? '-----' }}</td> --}}

                <td data-label="Trạng thái">
                    @if (!empty($type->status))
                        <i class="fa fa-check" style="color:green;text-align: center"></i>
                    @else
                        <i class="fa fa-close" style="color:red;text-align: center"></i>
                    @endif

                </td>
                <td class="d-block-mobi">
                    <div class="action-buttons">
                        @can('admin.hotel.room.type.edit')
                            <div class="dropdown-item booked_room_edit"
                                style="background-color: red;background-color: orange;
                                border-radius: 4px;  display: flex; justify-content: center;">
                                <a href="{{ route('admin.hotel.room.type.edit', $type->id) }}"
                                    style="color:black;padding:5px;background-color: orange; color: white;">
                                    Sửa
                                </a>
                            </div>
                        @endcan
                        @can('admin.hotel.room.type.status')
                            @if ($type->status == 0)
                                <div class="dropdown-item booked_room" style="background-color: #379037;border-radius: 4px">
                                    <button class=" confirmationBtn btn-success"
                                        style="background-color: #379037;border-radius: 4px;color:white"
                                        data-action="{{ route('admin.hotel.room.type.status', $type->id) }}"
                                        data-question="@lang('Bạn có chắc chắn muốn bật loại phòng này không?')">
                                        Hoạt động
                                    </button>
                                </div>
                            @else
                                <div class="dropdown-item booked_room " style="background-color: red;border-radius: 4px">
                                    <button class="confirmationBtn" style="background-color: red;border-radius: 4px;color:white"
                                        data-action="{{ route('admin.hotel.room.type.status', $type->id) }}"
                                        data-question="@lang('Bạn có chắc chắn muốn vô hiệu hóa loại phòng này không?')">
                                        Tắt hoạt động
                                    </button>
                                </div>
                            @endif
                        @endcan
                        @can('admin.hotel.room.type.delete')
                            <div class="dropdown-item booked_room_detail"
                                style="background-color: red;background-color: red;
                                border-radius: 4px;  display: flex; justify-content: center;">


                                <button style="background-color: red; color: white;"
                                    class="btn-danger btn-delete icon-delete-room" data-id="{{ $type->id }}"
                                    data-modal_title="@lang('Xóa trạng thái')" type="button" data-pro="0">Xóa
                            </div>
                        @endcan
                    </div>
                </td>


                </tr>

                <tr class="collapse" id="rep-{{ $type->id }}">
                    <td colspan="8">
                        {{-- @if ($type->products->count() > 0)
                                        <div class="representatives-container">
                                            <span class="representatives-label">Sản phẩm:</span>
                                            <span class="representatives-list">
                                                @foreach ($type->products as $product)
                                                    <span class="badge {{ getRandomColor() }} me-2 mb-1 cursor-pointer">
                                                        <small class="representative-name">
                                                            {{ $product->name }}</small>
                                                    </span>
                                                @endforeach
                                            </span>
                                        </div>
                                    @endif --}}
                        <div class="representatives-container">
                            <span class="representatives-label">Số lượng người:</span>
                            <span class="representatives-list">
                                {{ $type->total_adult }}
                            </span>
                        </div>
                        {{-- <div class="representatives-container">
                                        <span class="representatives-label">Trạng thái tính năng:</span>
                                        <span class="representatives-list">
                                            {!! $type->featureBadge !!}
                                        </span>
                                        
                                    </div> --}}
                        <div class="representatives-container">
                            <span class="representatives-label">Tiện nghi:</span>
                            <span class="representatives-list">
                                @if ($type->amenities->count() > 0)
                                    <div class="float-inline-end">
                                        @foreach ($type->amenities as $amenity)
                                            <span class="badge {{ getRandomColor() }} m-1 p-1 rounded-pill text-white"
                                                style="color:white !important">
                                                {{ $amenity->title }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    Chưa có tiện nghi nào
                                @endif
                            </span>

                        </div>
                        <div class="representatives-container">
                            <span class="representatives-label">Cơ sở vật chất</span>
                            <span class="representatives-list">
                                @if ($type->facilities->count() > 0)
                                    <div class="float-inline-end">
                                        @foreach ($type->facilities as $facility)
                                            <span
                                                class="badge {{ getRandomColor() }} m-1 p-1 rounded-pill text-bg-primary"
                                                style="color:white !important">
                                                {{ $facility->title }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    Chưa có cơ sở vật chất
                                @endif
                            </span>

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                </tr>
                @endforelse

                </tbody>
                </table>

            </div>
        </div>

    </div>
    </div>
    </div>
    @can('admin.hotel.room.type.status')
        <x-confirmation-modal />
    @endcan
@endsection

@push('breadcrumb-plugins')
    <div class="card-body mt-1">
        <div class="row">

            <div class="col-md-12 d-flex  stack-mobile">

                <div>
                    <a class="mr-1" href="{{ route('admin.hotel.room.type.all') }}">
                        <button class="btn btn--primary" data-modal_title="Làm mới">
                            <i class="fa fa-repeat p-1"></i>
                        </button>
                    </a>
                    @can('admin.hotel.room.type.create')
                        <a href="{{ route('admin.hotel.room.type.create') }}">
                            <button class="btn btn--primary" data-modal_title="Thêm mới phòng" type="button"
                                style="margin-left:10px">
                                <i class="las la-plus p-1"></i>
                            </button>
                        </a>
                    @endcan
                </div>
                <form role="form" enctype="multipart/form-data" action="{{ route('admin.hotel.room.type.search') }}">
                    @csrf
                    <div class="form-group mb-0 stack-mobile" style="display: flex;">
                        <input class="searchInput" name="code"
                            style="height: 35px;border: 1px solid rgb(121, 117, 117, 0.5);"
                            placeholder="Mã phòng/Tên phòng" value="{{ $code ?? '' }}">

                        <select name="room_type_id" class="form-control choose ml-1" id="tim-loai-phong"
                            style="width:250px;margin-left: 8px;height: 35px">
                            <option value="">--Chọn loại phòng--</option>
                            @foreach ($room_type as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>

                        <select name="status" class="form-control choose" id="tim-trang-thai"
                            style="width:250px;margin-left: 8px;height: 35px">
                            <option value="">--Chọn trạng thái--</option>

                            <option value="0">Không hoạt động</option>
                            <option value="1">Hoạt động</option>


                        </select>

                        <div>
                            <button type="submit" class="btn btn--primary" style="margin-left: 8px;">
                                <i class="las la-search p-1"></i>
                            </button>
                            <!-- Nút bấm -->
                            <button type="button" class="btn btn--primary gap-2 " data-bs-toggle="modal"
                                data-bs-target="#importRoomModal" style="margin-left: 8px;">
                                <i class="fa-solid fa-file-import"></i> Import
                            </button>

                            <a href="{{ route('admin.rooms.export') }}" class="btn btn--primary gap-2 btn-export-room"
                                style="margin-left: 8px;">
                                <i class="fa-solid fa-file-export"></i> Export
                            </a>

                            <!-- Nút thao tác -->
                            <div class="dropdown-hover d-none"
                                style="position: relative; display: inline-block; margin-left: 8px;">
                                <button type="button" class="btn btn--primary gap-2 ">
                                    <i class="fas fa-ellipsis-v"></i> Thao tác
                                </button>
                                <!-- Cách 1: button -->

                                <!-- Menu tùy chọn hiển thị khi hover -->
                                <div class="dropdown-menu-custom">
                                    <a href="#" class="dropdown-item change-room-status"
                                        id="btn-change-status-room">Chuyển trạng thái</a>
                                    <a href="#"
                                        class="dropdown-item change-room-direction"id="btn-change-direction-room">Chuyển
                                        hướng
                                        phòng</a>
                                    {{-- <a href="#" class="dropdown-item change-room-status">Chuyển loại phòng</a> --}}
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

            <!-- Modal import-->
            <div class="modal fade" id="importRoomModal" tabindex="-1" aria-labelledby="importRoomModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="importRoomModalLabel">Nhập phòng từ file Excel</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>

                        <div class="modal-body">
                            <p>
                                Xử lý dữ liệu (Tải về File mẫu: <a href="{{ asset('file/FileExcelMau.xlsx') }}"
                                    download>Excel File</a>):
                            </p>
                            {{-- <div class="alert alert-warning">
                                <strong><i class="fa-solid fa-triangle-exclamation"></i> Lưu ý</strong><br>
                                Hệ thống cho phép nhập tối đa <strong>1.000 phòng</strong> mỗi lần từ file
                            </div> --}}

                            <form action="{{ route('admin.hotel.import.room.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="import_file" class="form-label">Chọn file Excel:</label>
                                    <input class="form-control" type="file" id="import_file" name="file" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-import-room"
                                    style="float: right">Nhận</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
    <!-- Modal: Chọn hướng phòng -->
    <div class="modal fade" id="modal-room-direction" tabindex="-1" aria-labelledby="roomDirectionLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chọn hướng phòng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <form id="roomDirectionForm">
                        <div class="mb-3">
                            <label for="room_direction" class="form-label">Hướng phòng</label>
                            <select id="room_direction" name="direction" class="form-select">
                                <option value="">-- Chọn hướng phòng --</option>
                                @foreach ($roomDirections as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Thoát</button>
                    <button type="button" id="btn-save-direction" class="btn btn-primary">Lưu</button>
                </div>
            </div>
        </div>
    </div>


    {{--        <div class="dropdown col-md-1 col-8" style="display: flex; --}}
    {{--                justify-content: end"> --}}
    {{--            <a class="btn btn-outline-secondary dropdown-toggle d-flex justify-content-center --}}
    {{--                align-items-center" href="#" role="button" --}}
    {{--               data-bs-toggle="dropdown" aria-expanded="false"> --}}
    {{--                Thao tác --}}
    {{--            </a> --}}
    {{--            <ul class="dropdown-menu"> --}}
    {{--                <li><a class="dropdown-item" --}}
    {{--                       href="{{ route('admin.hotel.room.type.all.deleted') }}">Các phòng đã xóa</a> --}}
    {{--                </li> --}}
    {{--            </ul> --}}
    {{--        </div> --}}
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/modal.css') }}">
    {{-- <style>
        .pagination .page-item .page-link,
        .pagination .page-item span {
            width: 22px !important;
            height: auto !important;
            background-color: #4634ff !important;
            color: white !important;
        }

        .pagination .page-item.active .page-link {
            background-color: #071251 !important;
        }
    </style> --}}
@endpush
@push('script')
    <script src="{{ asset('assets/admin/js/highlighter22.js') }}"></script>
    <script src="{{ asset('assets/validator/validator.js') }}"></script>
    <script>
        toggleRepresentatives = function(id, button) {
            const row = document.getElementById('rep-' + id);
            row.classList.toggle('show');
            button.classList.toggle('collapsed');
        };
        $(document).on('input', 'input[name="code"]', function() {
            this.value = this.value.toUpperCase();
        });
        $(document).ready(function() {
            const apiUrl = '{{ route('admin.hotel.room.type.all') }}';
            // initDataFetch(apiUrl);




            //jquery for toggle sub menus
            $('.has-arrow').click(function() {
                $(this).next('.menu-side').slideToggle();
                $(this).find('.dropdown').toggleClass('rotate');
            });

            //jquery for expand and collapse the sidebar
            $('.menu-btn').click(function() {
                $('.side-bar').addClass('active');
                $('.menu-btn').css("visibility", "hidden");
            });

            $('.close-btn').click(function() {
                $('.side-bar').removeClass('active');
                $('.menu-btn').css("visibility", "visible");
            });
        });
        $(document).ready(function() {
            function toggleActionDropdown() {
                const anyChecked = $('.checkbox-item:checked').length > 0;

                if (anyChecked) {
                    $('.dropdown-hover').removeClass('d-none');
                } else {
                    $('.dropdown-hover').addClass('d-none');
                }
            }


            // Khi thay đổi checkbox tổng
            $('#checkbox-room').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('.checkbox-item').prop('checked', isChecked);
                toggleActionDropdown();
            });

            // Khi thay đổi bất kỳ checkbox con nào
            $(document).on('change', '.checkbox-item', function() {
                toggleActionDropdown();
            });
            $(document).on('click', '#btn-change-direction-room', function(e) {
                e.preventDefault();
                $('#modal-room-direction').modal('show');
            });

            $(document).on('click', '#btn-save-direction', function(e) {
                e.preventDefault();
                const selectedIds = $('.checkbox-item:checked')
                    .map(function() {
                        return $(this).data('id');
                    })
                    .get();

                if (selectedIds.length === 0) {
                    alert("Vui lòng chọn ít nhất một phòng để thay đổi trạng thái.");
                    return;
                }
                var room_direction = $('#room_direction').val();
                if (room_direction === "") {
                    notify('error', 'Chọn hướng phòng');
                    return;
                }
                $.ajax({
                    url: '{{ route('admin.hotel.room.type.changeDirections') }}', // Laravel route helper
                    method: 'POST',
                    data: {
                        ids: selectedIds,
                        direction_id: room_direction,
                    },
                    success: function(response) {
                        console.log("Thành công:", response);
                        // Ví dụ: reload lại trang hoặc cập nhật giao diện
                        location.reload();
                    },
                    error: function(xhr) {
                        console.error("Lỗi:", xhr.responseText);
                        alert("Có lỗi xảy ra khi cập nhật trạng thái.");
                    }
                });

            });
            $(document).on('click', '#btn-change-status-room', function(e) {
                e.preventDefault();
                const selectedIds = $('.checkbox-item:checked')
                    .map(function() {
                        return $(this).data('id');
                    })
                    .get();

                if (selectedIds.length === 0) {
                    alert("Vui lòng chọn ít nhất một phòng để thay đổi trạng thái.");
                    return;
                }

                $.ajax({
                    url: '{{ route('admin.hotel.room.type.statusAll') }}', // Laravel route helper
                    method: 'POST',
                    data: {
                        ids: selectedIds
                    },
                    success: function(response) {
                        console.log("Thành công:", response);
                        // Ví dụ: reload lại trang hoặc cập nhật giao diện
                        location.reload();
                    },
                    error: function(xhr) {
                        console.error("Lỗi:", xhr.responseText);
                        alert("Có lỗi xảy ra khi cập nhật trạng thái.");
                    }
                });

            });
            $('.btn-delete').on('click', function() {

                var dataId = $(this).data('id');
                var rowToDelete = $(`tr[data-id="${dataId}"]`);
                Swal.fire({
                    title: 'Xác nhận xóa phòng?',
                    text: 'Bạn có chắc chắn muốn xóa phòng này không?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy bỏ',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ajax
                        $.ajax({
                            url: `{{ route('admin.hotel.room.type.delete', '') }}/${dataId}`,
                            type: 'POST',
                            success: function(data) {
                                if (data.status === 'success') {
                                    rowToDelete.remove();
                                } else {
                                    notify('error', data.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log(xhr.responseText);
                            }
                        });


                    }
                });
            });

            $('.choose').change(function() {
                var status = $('#tim-trang-thai').val();
                var room_type_id = $('#tim-loai-phong').val();
                var url = "{{ route('admin.hotel.room.type.ajax') }}";
                $.ajax({
                    type: 'GET',
                    cache: false,
                    url: url,
                    data: {
                        status: status,
                        room_type_id: room_type_id,

                    },
                    success: function(response) {
                        if (response) {
                            $('#data').html(response)
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                })
            });
            $(document).on('click', '.svg-icon', function(e) {
                e.stopPropagation();
                const $dropdown = $(this).siblings('.menu_dropdown');
                $('.menu_dropdown').not($dropdown).removeClass('show');
                $dropdown.toggleClass('show');
            });
            $(document).on('click', function() {
                $('.menu_dropdown').removeClass('show');
            });
            $(document).on('click', '.svg_menu_check_in', function(e) {
                e.stopPropagation();
                const $dropdown = $(this).siblings('.menu_dropdown_check_in');
                $('.menu_dropdown_check_in').not($dropdown).removeClass('show');
                $dropdown.toggleClass('show');
            });
            $(document).on('click', function() {
                $('.menu_dropdown_check_in').removeClass('show');
            });
            $(document).on('click', function() {
                $('.menu_dropdown').removeClass('show');
            });

        });
    </script>
@endpush

@push('style')
    <style scoped>
        /* Desktop giữ nguyên */
        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .d-block-mobi {
            display: none;
        }

        /* Mobile: các nút nằm ngang trong 1 hàng */
        @media (max-width: 768px) {
            .d-block-mobi .action-buttons {
                display: flex;
                flex-wrap: nowrap;
                justify-content: space-between;
            }

            .d-block-mobi .action-buttons .btn {
                flex: 1;
                /* nút tự dàn đều */
                margin: 0 2px;
                /* khoảng cách nhỏ */
            }

            .d-block-mobi {
                display: block;
            }
        }

        .dropdown-hover .dropdown-menu-custom {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            border: 1px solid #ccc;
            min-width: 120px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            z-index: 999;
            padding: 5px 0;
            border-radius: 4px;
        }

        .dropdown-hover:hover .dropdown-menu-custom {
            display: block;
        }

        .dropdown-item {
            display: block;
            padding: 8px 12px;
            color: #333;
            text-decoration: none;
        }

        .dropdown-item:hover {
            background-color: #f0f0f0;
        }

        @media (max-width: 991px) {

            .table-responsive--md tr th,
            .table-responsive--md tr td {
                padding-left: 4% !important;
            }
        }

        th,
        td {
            text-align: center !important;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #fff;
        }



        .btn-toggle {
            border: 1px solid #007bff;
            background-color: #007bff;
            color: #fff;
            font-size: 1rem;
            padding: 1px 4px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
            text-align: center;
            line-height: 1;
            border-radius: 50%;
            font-family: 'Courier New', Courier, monospace;
        }

        .btn-toggle:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .pagination .page-item .page-link,
        .pagination .page-item span {
            font-size: 0.875rem;
            display: flex;
            width: 36px;
            height: 36px;
            margin: 0 3px;
            padding: 0;
            border-radius: 3px !important;
            align-items: center;
            justify-content: center;
            color: #5b6e88;
        }

        #data-table td {
            height: 37px !important;
        }

        .btn-toggle.collapsed {
            background-color: red;
            border-color: red;
        }

        .btn-toggle::after {
            content: '+';
            display: inline-block;
        }

        .btn-toggle.collapsed::after {
            content: '−';
        }

        .collapse {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.5s ease, opacity 0.5s ease;
            opacity: 0;
        }

        .collapse.show {
            max-height: 200px;
            /* Điều chỉnh theo nhu cầu */
            opacity: 1;
        }

        .representatives-container {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ddd;
            /* Border-bottom for separation */
            padding-bottom: 8px;
            /* Optional padding */
            margin-bottom: 8px;
            /* Optional margin */
        }

        .representatives-label {
            font-weight: bold;
            margin-right: 8px;
            /* Space between label and list */
        }

        .representatives-list {
            flex: 1;
            display: flex;
            flex-wrap: wrap;
        }

        .representatives-list::after {
            content: '';
            /* Clear floats if needed */
            display: block;
            width: 100%;
        }

        .searchInput {
            padding: 1px 3px !important;
            border: 1px solid rgb(121, 117, 117, 0.5);
            margin-left: 8px;
        }

        @media (max-width: 768px) {
            #perPage {
                width: 100% !important;
            }

            #data-table td {
                height: auto !important;
            }

            .dropdown {
                order: 2;
            }

            .search {
                order: 3;
                margin-top: 15px
            }

            .menu_dropdown_check_in {
                right: 40px !important;
            }

            .svg_menu_check_in {
                margin-top: -10px;
            }

        }

        @media (max-width: 768px) {
            .stack-mobile {
                flex-direction: column !important;
                gap: 8px;
                /* khoảng cách giữa các item */
            }

            .stack-mobile>* {
                width: 100% !important;
                /* mỗi item full chiều ngang */
            }
        }
    </style>
@endpush

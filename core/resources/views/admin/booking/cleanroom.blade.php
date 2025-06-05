@extends('admin.layouts.master_iframe')
@section('panel')
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Thêm mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="showEditRoomAmenity" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Cập nhật</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="roomsEditAmenityForm">
                        <input type="hidden" name="_method" id="method" value="POST">
                        <input type="hidden" name="id" id="recordId">
                        <div class="row">
                            <div class="form-group mb-3">
                                <label for="">Phòng</label>
                                <select name="room_id" id="room-choice" class="form-control">

                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group mb-3">
                                <label for="">Các tiện nghi <code>(Được chọn nhiều)</code></label>
                                <div id="checkbox-amenity" class="form-check-group mt-3">

                                </div>


                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            {{-- @if ($amenities->isNotEmpty())
                                <button type="submit" class="btn btn-primary">Thực hiện</button>
                            @endif --}}
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="#cleaning" data-bs-toggle="tab">Dọn phòng</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#maintenance" data-bs-toggle="tab">Sửa chữa</a>
        </li>
    </ul>

    <div class="tab-content mt-3">
        <div class="tab-pane fade show active" id="cleaning">
            <div class="row">
                <!-- Khối bên phải: Danh sách danh mục -->
                <div class="col-md-12">
                    <div class="col-md-2 mt-2 mb-2">
                        <input type="date" id="search-input-clean" class="form-control" value="{{ $today }}" />
                    </div>
                    <div class="border p-2">
                        <div class="card b-radius--10">
                            <div class="card-body p-0">
                                <div class="table-responsive--sm table-responsive">
                                    <table class="table--light style--two table" id="data-table">
                                        <thead>
                                            <tr>
                                                <th>@lang('STT')</th>
                                                <th>@lang('Tên phòng')</th>
                                                <th>@lang('Ngày tạo')</th>
                                                <th>@lang('Nhân viên')</th>
                                                @can()
                                                    <th>@lang('Hành động')</th>
                                                @endcan
                                            </tr>
                                        </thead>
                                        <tbody class="data-table-clean">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="maintenance">
            <div class="row">
                <!-- Khối bên phải: Danh sách danh mục -->
                <div class="col-md-12">
                    <div class="col-md-2 mt-2 mb-2">
                        <input type="date" id="search-input-maintenance" class="form-control"
                            value="{{ $today }}" />
                    </div>
                    <div class="border p-2">
                        <div class="card b-radius--10">
                            <div class="card-body p-0">


                                <div class="table-responsive--sm table-responsive">
                                    <table class="table--light style--two table" id="data-table">
                                        <thead>
                                            <tr>
                                                <th>@lang('STT')</th>
                                                <th>@lang('Tên phòng')</th>
                                                <th>@lang('Ngày tạo')</th>
                                                <th>@lang('Nhân viên')</th>
                                                @can()
                                                    <th>@lang('Hành động')</th>
                                                @endcan
                                            </tr>
                                        </thead>
                                        <tbody class="data-table-maintenance">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

@push('script')
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush
<script>
    $(document).ready(function() {
       

        fetchCleanRoomData();



        // Optional: bắt sự kiện tìm kiếm theo keyword
        $('#search-input-clean').on('change', function() {
            let keyword = $(this).val();
            fetchCleanRoomData(keyword);
        });

        fetchFixRoomData();



        $('#search-input-maintenance').on('change', function() {
            let keyword = $(this).val();
            fetchFixRoomData(keyword);
        });
    });
 function formatDatetime(datetimeStr) {
            const date = new Date(datetimeStr);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0'); // tháng bắt đầu từ 0
            const year = date.getFullYear();
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');

            return `${day}/${month}/${year} ${hours}:${minutes}`;
        }
        function fetchCleanRoomData(keyword = '') {
            $.ajax({
                url: "{{ route('admin.listUserCleanRoom.booking.listUserCleanRoom') }}",
                method: "GET",
                data: {
                    keyword: keyword
                },
                success: function(response) {
                    if (response.success) {
                        const tbody = $(".data-table-clean");
                        tbody.empty();

                        const data = response.data.data; // Dữ liệu phân trang nằm trong `data`

                        if (data.length === 0) {
                            tbody.append(
                                '<tr><td colspan="5" class="text-center">Không có dữ liệu</td></tr>'
                            );
                            return;
                        }

                        $.each(data, function(index, item) {
                            const roomName = item.room ? item.room.room_number :
                                '(Không có)';
                            const adminName = item.admin ? item.admin.name : '(Không có)';
                            const createdAt = item.clean_date ?? '-';

                            let actions = '';
                            @can('some_permission')
                                actions = `
                                        <button class="btn btn-sm btn-danger btn-delete-clean"
                                            onclick="confirmDeleteClean(${item.id})"
                                            data-id="${item.id}">
                                            Xoá
                                        </button>`;
                            @endcan


                            tbody.append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${roomName}</td>
                                    <td>${formatDatetime(createdAt)}</td>
                                    <td>${adminName}</td>
                                    <td>${actions}</td>
                                </tr>
                            `);
                        });
                    }
                },
                error: function() {
                    alert("Đã có lỗi xảy ra khi tải dữ liệu.");
                }
            });
        }

    function fetchFixRoomData(keyword = '') {
        $.ajax({
            url: "{{ route('admin.listUserFixRoom.booking.listUserFixRoom') }}",
            method: "GET",
            data: {
                keyword: keyword
            },
            success: function(response) {
                if (response.success) {
                    const tbody = $(".data-table-maintenance");
                    tbody.empty();

                    const data = response.data.data; // Dữ liệu phân trang nằm trong `data`

                    if (data.length === 0) {
                        tbody.append(
                            '<tr><td colspan="5" class="text-center">Không có dữ liệu</td></tr>'
                        );
                        return;
                    }

                    $.each(data, function(index, item) {
                        const roomName = item.room ? item.room.room_number :
                            '(Không có)';
                        const adminName = item.admin ? item.admin.name : '(Không có)';
                        const createdAt = item.fix_date ?? '-';

                        let actions = '';
                        @can('some_permission')
                            actions =
                                `<button class="btn btn-sm btn-danger btn-delete-maintenance"
                                      onclick="confirmDeleteMaintenance(${item.id})"
                                        data-id="${item.id}">
                                    Xoá</button>`;
                        @endcan


                        tbody.append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${roomName}</td>
                                <td>${formatDatetime(createdAt)}</td>
                                <td>${adminName}</td>
                                <td>${actions}</td>
                            </tr>
                        `);
                    });
                }
            },
            error: function() {
                alert("Đã có lỗi xảy ra khi tải dữ liệu.");
            }
        });
    }

    function confirmDeleteClean(id) {
        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: "Hành động này sẽ xóa dữ liệu dọn phòng!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xoá',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                let url = "{{ route('admin.delCleanRoom.booking.delCleanRoom', ['id' => ':id']) }}";
                url = url.replace(':id', id);

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {

                            fetchCleanRoomData();
                        } else {
                            Swal.fire('Lỗi!', 'Đã xảy ra lỗi khi xóa dữ liệu.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Lỗi!', 'Không thể gửi yêu cầu đến máy chủ.', 'error');
                    }
                });
            }
        });
    }

    function confirmDeleteMaintenance(id) {
        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: "Hành động này sẽ xóa dữ liệu sửa phòng!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xoá',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {

                let url = "{{ route('admin.delFixRoom.booking.delFixRoom', ['id' => ':id']) }}";
                url = url.replace(':id', id);

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {

                            fetchFixRoomData();
                        } else {
                            Swal.fire('Lỗi!', 'Đã xảy ra lỗi khi xóa dữ liệu.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Lỗi!', 'Không thể gửi yêu cầu đến máy chủ.', 'error');
                    }
                });
            }
        });
    }
</script>
@push('style')
    <style>
        .radio-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle {
            position: relative;
            display: inline-block;
            width: 52px;
            height: 29px;
        }

        .toggle input {
            display: none;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #4CAF50;
        }

        input:checked+.slider:before {
            transform: translateX(24px);
        }

        .label {
            margin-left: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        .status-input {
            margin-bottom: 20px;
        }

        .status-input label {
            font-weight: bold;
            margin-right: 10px;
        }

        .radio-group {
            display: flex;
            align-items: center;
        }

        .radio-group input[type="radio"] {
            margin-right: 5px;
            accent-color: #007bff;
        }

        .radio-group label {
            margin-right: 20px;
            font-size: 16px;
        }

        .form-check-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .form-check {
            margin-right: 15px;
        }

        .form-check-input {
            width: 25px;
            height: 25px;
            margin-right: 10px;
        }

        .form-check-label {
            font-size: 18px;
            line-height: 25px;
        }

        @media(max-width:768px) {
            .flex-nowrap {
                flex-wrap: nowrap !important;
            }
        }
    </style>
@endpush

@push('style-lib')
@endpush

@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <!-- Khối bên phải: Danh sách danh mục -->
        <div class="col-md-12">
            <div class="border p-2">
                <div class="d-flex justify-content-between mb-3">
                    {{-- <div class="dt-length">
                        <select name="example_length" id="perPage" style=" padding: 1px 3px; margin-right: 8px;"
                            aria-controls="example" class="perPage">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select><label for="perPage"> entries per page</label>
                    </div> --}}
                    <div class="search">
                        {{-- <label for="searchInput">Search:</label>
                        <input class="searchInput"
                            style="padding: 1px 3px; border: 1px solid rgb(121, 117, 117, 0.5); margin-left: 8px;"
                            type="search" placeholder="Tìm kiếm..."> --}}
                        <form method="GET" id="searchForm"
                            action="{{ route('admin.listUserCleanRoom.booking.listUserCleanRoom') }}">
                            @csrf
                            <div class="input-group flex-nowrap">
                                <input type="date" class="searchInput" name="keyword" id="searchInput"
                                    value="{{ request('keyword') }}" placeholder="Tìm kiếm ...">
                                <!-- Thay icon bằng bất kỳ icon nào bạn muốn -->
                                <!-- Nút tìm kiếm -->
                                <button type="button" id="clearDate"
                                    data-url="{{ route('admin.listUserCleanRoom.booking.listUserCleanRoom') }}"
                                    class="btn btn-primary">
                                    <i class="las la-sync-alt"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card b-radius--10">
                    <div class="card-body p-0">
                        <div class="table-responsive--sm table-responsive">
                            <table class="table--light style--two table" id="data-table">
                                <thead>
                                    <tr>
                                        <th>@lang('STT')</th>
                                        <th>@lang('Số phòng')</th>
                                        <th>@lang('Ngày tạo')</th>
                                        <th>@lang('Admin')</th>
                                        @can()
                                            <th>@lang('Hành động')</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($userCleanRoom as $index=>$item)
                                        <tr data-table="{{ $item->id }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->room->room_number }}</td>
                                            <td>{{ $item->clean_date }}</td>
                                            <td>{{ $item->admin->name }}</td>
                                            @php
                                                $disabled = authCleanRoom() ? 'disabled' : '';
                                            @endphp
                                            <td>
                                                <button class="btn btn-sm btn-outline--danger btn-delete"
                                                    onclick="confirmDelete('{{ $item->id }}')"
                                                    data-id="{{ $item->id }}"
                                                    data-modal_title="@lang('Xóa danh mục')"type="button" data-pro="0"
                                                    {{ $disabled }}>
                                                    <i class="fas fa-trash"></i>@lang('Xóa')
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <h3>Không có dữ liệu.</h3>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="pagination" class="mt-3">
                {{ $userCleanRoom->links() }}
            </div>
        </div>
    </div>


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
@endsection
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

@push('script')
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush
<script>
    $(document).ready(function() {
        $('#searchInput').change(function() {
            var name = $('#searchInput').val();
            $('#searchForm').submit();
        })

        var originalUrl = $('#clearDate').data('url');
        $('#clearDate').click(function() {
            $('#searchInput').val('');
            $('#searchForm').submit();
            window.location.href = originalUrl;
        });
    });

    function confirmDelete(id) {
        if (confirm('Bạn có chắc chắn muốn xóa không?')) {
            var dataTable = document.querySelector('tr[data-table="' + id + '"]');
            var url = "{{route('admin.delCleanRoom.booking.delCleanRoom',['id'=> ':id' ])}}";
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
                        console.log('Dữ liệu đã được xóa thành công!');
                        dataTable.remove(); // Xóa dòng trong bảng HTML
                    } else {
                        console.error('Đã xảy ra lỗi khi xóa dữ liệu.');
                    }
                },
                error: function() {
                    console.error('Đã xảy ra lỗi khi gửi yêu cầu.');
                }
            });
        }
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

@push('script-lib')
@endpush

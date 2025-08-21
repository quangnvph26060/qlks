@extends('admin.layouts.master_iframe')
@section('panel')
    <div id="pagination" class="mt-2 mb-2 d-flex  justify-content-center align-items-center">
    </div>
    <div class="row">
        <!-- Khối bên phải: Danh sách danh mục -->
        <div class="col-md-12">
            <div class="border">

                <div class="card b-radius--10">
                    <div class="card-body p-0">
                        <div class="table-responsive--sm table-responsive">
                            <table class="table--light style--two table" id="data-table">
                                <thead>
                                    <tr>
                                        @can('admin.hotel.room.amenities.all')
                                            <th>@lang('Hành động')</th>
                                        @endcan
                                        <th>@lang('STT')</th>
                                        <th>@lang('Mã phòng')</th>
                                        <th>@lang('Loại phòng')</th>
                                        <th>@lang('Tên phòng')</th>
                                        <th>@lang('Tiện nghi')</th>

                                    </tr>
                                </thead>
                                <tbody id="data">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @can('')
        @push('breadcrumb-plugins')
            <div class="card-body mt-1">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2">
                            <!-- Nút Làm mới & Thêm -->
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('admin.hotel.room.amenities.all') }}">
                                    <button type="button" class="btn btn--primary" data-modal_title="Làm mới">
                                        <i class="fa fa-repeat p-1"></i>
                                    </button>
                                </a>
                                @can('admin.hotel.room.amenities.store')
                                    <button type="button" class="btn btn--primary btn-add">
                                        <i class="las la-plus p-1"></i>
                                    </button>
                                @endcan
                            </div>

                            <!-- Form tìm kiếm -->
                            <form class="d-flex flex-wrap flex-lg-nowrap align-items-center gap-2 mb-0" role="form"
                                enctype="multipart/form-data" action="{{ route('admin.hotel.room.amenities.search') }}">
                                <input class="form-control searchInput" name="code" placeholder="Mã phòng/Tên phòng"
                                    value="{{ $code ?? '' }}">
                                <select name="room_type_id" class="form-control choose" id="tim-loai-phong">
                                    <option value="">--Chọn loại phòng--</option>
                                    @foreach ($room_type as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn--primary">
                                    <i class="las la-search p-1"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endpush
    @endcan

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Thêm tiện nghi vào phòng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="roomsAddAmenityForm" method="POST" action="">
                        @csrf
                        <input type="hidden" name="_method" id="method" value="POST">
                        <input type="hidden" name="id" id="recordId">

                        {{-- Danh sách phòng --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Danh sách phòng</label>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="checkAllRooms">
                                <label for="checkAllRooms" class="form-check-label">Chọn tất cả phòng</label>
                            </div>
                            <div class="border rounded p-3 scroll-box" style="max-height: 200px; overflow-y: auto;">
                                @foreach ($rooms as $room)
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input room-checkbox" name="room_ids[]"
                                            value="{{ $room->id }}" id="room-{{ $room->id }}">
                                        <label class="form-check-label"
                                            for="room-{{ $room->id }}">{{ $room->code }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Danh sách tiện nghi --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Các tiện nghi <code>(Được chọn nhiều)</code></label>
                            @if ($amenities->isNotEmpty())
                                <div class="border rounded p-3 scroll-box" style="max-height: 200px; overflow-y: auto;">
                                    @foreach ($amenities as $amenity)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="{{ $amenity->id }}"
                                                name="amenities_id[]" id="amenity-{{ $amenity->id }}">
                                            <label class="form-check-label" for="amenity-{{ $amenity->id }}">
                                                {{ $amenity->title }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p>Chưa có tiện nghi nào!</p>
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            @if ($amenities->isNotEmpty())
                                <button type="submit" class="btn btn-primary">Lưu</button>
                            @endif
                        </div>
                    </form>
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
                            @if ($amenities->isNotEmpty())
                                <button type="submit" class="btn btn-primary">Thực hiện</button>
                            @endif
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


@endsection
@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/modal.css') }}">
    <style>
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
    </style>
@endpush

@push('script')
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('assets/admin/js/dataTable.js') }}"></script>
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>

    <script>
        (function($) {
            "use strict"
            $(document).ready(function() {
                const apiUrl = '{{ route('admin.hotel.room.amenities.all') }}';
                initDataFetch(apiUrl);

                $("#roomsAddAmenityForm").on('submit', function(e) {
                    e.preventDefault();
                    const method = $('#method').val();
                    const url = method === 'PUT' ? "{{ route('admin.manage.price.update', ':id') }}"
                        .replace(
                            ':id', $(
                                '#recordId').val()) :
                        "{{ route('admin.hotel.room.amenities.store') }}";

                    $.ajax({
                        type: method,
                        url: url,
                        data: $(this).serializeArray(),
                        success: function(response) {
                            if (response.status) {
                                showSwalMessage('success', response.message);
                                $('#staticBackdrop').modal('hide');
                                initDataFetch(apiUrl);
                            } else {
                                $('input').removeClass('is-invalid');
                                $(`#${response.key}`).addClass('is-invalid');
                                showSwalMessage('error', response.message);
                            }

                        }
                    });
                })
                $('#checkAllRooms').on('change', function() {
                    $('.room-checkbox').prop('checked', this.checked);
                });

                // Nếu bỏ chọn 1 phòng => bỏ check all
                $('.room-checkbox').on('change', function() {
                    const allChecked = $('.room-checkbox').length === $('.room-checkbox:checked')
                        .length;
                    $('#checkAllRooms').prop('checked', allChecked);
                });
                $(document).on('click', '.btn-edit', function() {
                    let id = $(this).data('id');

                    $.ajax({
                        type: "GET",
                        url: "{{ route('admin.hotel.room.amenities.edit', ':id') }}".replace(
                            ':id',
                            id),
                        success: function(response) {
                            if (response.status) {
                                $('#showEditRoomAmenity').modal('show');
                                //auto selected room
                                let roomSelect = $('#room-choice');
                                roomSelect.empty();
                                response.rooms.forEach(room => {
                                    let selected = room.id === response.roomEdit
                                        .id ? 'selected' :
                                        '';
                                    let disabled = room.id !== response.roomEdit
                                        .id ? 'disabled class="text-muted"' : '';
                                    roomSelect.append(
                                        `<option value="${room.id}" ${selected}  ${disabled}>${room.code}</option>`
                                    );
                                });
                                roomSelect.trigger('change');
                                //
                                //auto selected amentity
                                let amenitesContainer = $('#checkbox-amenity');
                                amenitesContainer.empty();

                                response.amenities.forEach(amenity => {
                                    let checked = response.selectedAmenities
                                        .includes(amenity
                                            .id) ? 'checked' : '';
                                    amenitesContainer.append(`
                                    <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="${amenity.id}" ${checked} multiple
                                                name="amenities_id[]"  id="">
                                            <label class="form-check-label" for="">
                                                 ${amenity.title}
                                            </label> </div>`)
                                });
                                ///


                                $('#staticBackdropLabel').text('Cập nhật');
                                $('#method').val('PUT');
                                $('#recordId').val(id);

                            }
                        }
                    });
                });

                $(document).on('click', '.btn-add', function() {
                    $('#roomsAddAmenityForm')[0].reset();
                    $('#method').val('POST');
                    $('#staticBackdropLabel').text(
                        'Thêm mới'); // Đặt lại tiêu đề là "Thêm mới"
                    $('#staticBackdrop').modal('show');
                });
                $('#roomsEditAmenityForm').submit(function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: '{{ route('admin.hotel.room.amenities.update') }}',
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            if (response.status) {
                                showSwalMessage('success', response.message);
                                $('#showEditRoomAmenity').modal('hide');
                                initDataFetch(apiUrl);
                            }
                        },
                        error: function(error) {
                            alert('Có lỗi xảy ra khi cập nhật tiện nghi.');
                        }
                    });
                });
                $(document).on('click', '.icon-delete-room', function() {
                    var dataId = $(this).data('id');
                    var rowToDelete = $(`tr[data-id="${dataId}"]`);
                    Swal.fire({
                        title: 'Xác nhận xóa tiện nghi?',
                        text: 'Bạn có chắc chắn muốn xóa tiện nghi này không?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Đồng ý',
                        cancelButtonText: 'Hủy bỏ',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // ajax
                            $.ajax({
                                url: `{{ route('admin.hotel.room.amenities.delete', '') }}/${dataId}`,
                                type: 'POST',
                                success: function(data) {
                                    if (data.status === 'success') {
                                        rowToDelete.remove();


                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.log(xhr.responseText);
                                }
                            });


                        }
                    });
                });


            });
        })(jQuery);

        $('.choose').change(function() {
            var room_type_id = $('#tim-loai-phong').val();
            var url = "{{ route('admin.hotel.room.amenities.ajax') }}";
            $.ajax({
                type: 'GET',
                cache: false,
                url: url,
                data: {
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

        $(document).ready(function() {

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
    <style>
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

            .menu_dropdown_check_in {
                right: 10px;
            }
        }
    </style>
@endpush

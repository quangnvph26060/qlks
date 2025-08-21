@extends('admin.layouts.master_iframe')
@section('panel')
    <div id="pagination" class="mt-2 mb-2 d-flex  justify-content-center align-items-center">
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="border">
                <div class="card b-radius--10">
                    <div class="card-body p-0">
                        <div class="table-responsive--sm table-responsive">
                            <table class="table--light style--two table" id="data-table">
                                <thead>
                                    <tr>
                                        <th>@lang('Hành động')</th>
                                        <th>@lang('STT')</th>
                                        <th>@lang('Mã phòng')</th>
                                        <th>@lang('Loại phòng')</th>
                                        <th>@lang('Tên phòng')</th>
                                        <th>@lang('Cơ sở vật chất')</th>

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
    <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2">
        
        <!-- Nút Làm mới & Thêm -->
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.hotel.room.facilities.all') }}">
                <button type="button" class="btn btn--primary" data-modal_title="Làm mới">
                    <i class="fa fa-repeat p-1"></i>
                </button>
            </a>
            @can('admin.hotel.room.facilities.store')
                <button type="button" class="btn btn--primary btn-add">
                    <i class="las la-plus p-1"></i>
                </button>
            @endcan
        </div>

        <!-- Form tìm kiếm -->
        <form class="d-flex flex-wrap flex-lg-nowrap align-items-center gap-2 mb-0"
              role="form" enctype="multipart/form-data"
              action="{{ route('admin.hotel.room.facilities.search') }}">
            
            <!-- Ô nhập mã phòng -->
            <input type="text" class="form-control searchInput" name="code"
                   placeholder="Mã phòng/Tên phòng" value="{{ $code ?? '' }}">
            
            <!-- Chọn loại phòng -->
            <select name="room_type_id" class="form-control choose" id="tim-loai-phong">
                <option value="">--Chọn loại phòng--</option>
                @foreach ($room_type as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>

            <!-- Nút tìm kiếm -->
            <button type="submit" class="btn btn--primary">
                <i class="las la-search p-1"></i>
            </button>
        </form>
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
                    <form id="roomsAddFacilityForm" method="POST" action="">
                        @csrf
                        <input type="hidden" name="_method" id="method" value="POST">
                        <input type="hidden" name="id" id="recordId">

                        {{-- Danh sách mã phòng --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Mã Phòng</label>
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
                            @if ($facilities->isNotEmpty())
                                <div class="border rounded p-3 scroll-box" style="max-height: 200px; overflow-y: auto;">
                                    @foreach ($facilities as $facility)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="{{ $facility->id }}"
                                                name="facilities_id[]" id="facility-{{ $facility->id }}">
                                            <label class="form-check-label" for="facility-{{ $facility->id }}">
                                                {{ $facility->title }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p>Chưa có tiện nghi nào!</p>
                            @endif
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            @if ($facilities->isNotEmpty())
                                <button type="submit" class="btn btn-primary">Thực hiện</button>
                            @endif
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="showEditRoomFacility" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Cập nhật</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="roomsEditFacilityForm">
                        <input type="hidden" name="_method" id="method" value="POST">
                        <input type="hidden" name="id" id="recordId">
                        <div class="row">
                            <div class="form-group mb-3">
                                <label for="">Mã phòng</label>
                                <select name="room_id" id="room-choice" class="form-control">

                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group mb-3">
                                <label for="">Các tiện nghi <code>(Được chọn nhiều)</code></label>
                                <div id="checkbox-facility" class="form-check-group mt-3">

                                </div>


                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            @if ($facilities->isNotEmpty())
                                <button type="submit" class="btn btn-primary">Thực hiện</button>
                            @endif
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    </div>


@endsection
@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/modal.css') }}">
@endpush

@push('script')
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('assets/admin/js/dataTable.js') }}"></script>

    <script>
        (function($) {
            "use strict"
            $(document).ready(function() {
                const apiUrl = '{{ route('admin.hotel.room.facilities.all') }}';
                initDataFetch(apiUrl);

                $("#roomsAddFacilityForm").on('submit', function(e) {
                    e.preventDefault();
                    const method = $('#method').val();
                    const url = method === 'PUT' ? "{{ route('admin.manage.price.update', ':id') }}"
                        .replace(
                            ':id', $(
                                '#recordId').val()) :
                        "{{ route('admin.hotel.room.facilities.store') }}";

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
                        url: "{{ route('admin.hotel.room.facilities.edit', ':id') }}".replace(
                            ':id',
                            id),
                        success: function(response) {
                            if (response.status) {
                                $('#showEditRoomFacility').modal('show');
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
                                        `<option value="${room.id}" ${selected} ${disabled}>${room.code}</option>`
                                    );
                                });
                                roomSelect.trigger('change');
                                //
                                //auto selected amentity
                                let amenitesContainer = $('#checkbox-facility');
                                amenitesContainer.empty();

                                response.facilities.forEach(facility => {
                                    let checked = response.selectedfacilities
                                        .includes(facility
                                            .id) ? 'checked' : '';
                                    amenitesContainer.append(`
                                    <div class="form-check">
                 <input class="form-check-input" type="checkbox" value="${facility.id}" ${checked} multiple
                                                name="facilities_id[]"  id="">
                                            <label class="form-check-label" for="">
                                                 ${facility.title}
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
                    $('#roomsAddFacilityForm')[0].reset();
                    $('#method').val('POST');
                    $('#staticBackdropLabel').text(
                        'Thêm mới'); // Đặt lại tiêu đề là "Thêm mới"
                    $('#staticBackdrop').modal('show');
                });
                $('#roomsEditFacilityForm').submit(function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: '{{ route('admin.hotel.room.facilities.update') }}',
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            if (response.status) {
                                showSwalMessage('success', response.message);
                                $('#showEditRoomFacility').modal('hide');
                                initDataFetch(apiUrl);
                            }
                        },
                        error: function(error) {
                            alert('Có lỗi xảy ra khi cập nhật tiện nghi.');
                        }
                    });
                });
                $(document).on('click', '.btn-delete', function() {

                    var dataId = $(this).data('id');
                    var rowToDelete = $(`tr[data-id="${dataId}"]`);
                    Swal.fire({
                        title: 'Xác nhận xóa cơ sở vật chất?',
                        text: 'Bạn có chắc chắn muốn xóa cơ sở vật chất này không?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Đồng ý',
                        cancelButtonText: 'Hủy bỏ',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // ajax
                            $.ajax({
                                url: `{{ route('admin.hotel.room.facilities.delete', '') }}/${dataId}`,
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
        $('.choose').change(function() {
            var room_type_id = $('#tim-loai-phong').val();
            var url = "{{ route('admin.hotel.room.facilities.ajax') }}";
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

        @media(max-width:768px) {
            .flex-nowrap {
                flex-wrap: nowrap !important;
            }

            .menu_dropdown_check_in {
                right: 10px;
            }
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
    </style>
@endpush

@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <!-- Khối bên phải: Danh sách danh mục -->
        <div class="col-md-12">
            <div class="border">
{{--                <div class="d-flex justify-content-between mb-3">--}}
{{--                    <div class="dt-length">--}}
{{--                        <select name="example_length" id="perPage" style=" padding: 1px 3px; margin-right: 8px;"--}}
{{--                            aria-controls="example" class="perPage">--}}
{{--                            <option value="10">10</option>--}}
{{--                            <option value="25">25</option>--}}
{{--                            <option value="50">50</option>--}}
{{--                            <option value="100">100</option>--}}
{{--                        </select><label for="perPage"> entries per page</label>--}}
{{--                    </div>--}}
{{--                    <div class="search">--}}
{{--                        --}}{{-- <label for="searchInput">Search:</label>--}}
{{--                        <input class="searchInput"--}}
{{--                            style="padding: 1px 3px; border: 1px solid rgb(121, 117, 117, 0.5); margin-left: 8px;"--}}
{{--                            type="search" placeholder="Tìm kiếm..."> --}}

{{--                            <form method="GET" id="searchForm" >--}}
{{--                                <div class="input-group flex-nowrap">--}}
{{--                                    <input--}}
{{--                                        type="search"--}}
{{--                                        class="searchInput"--}}
{{--                                        name="keyword"--}}
{{--                                        id="searchInput"--}}
{{--                                        value="{{ request('keyword') }}"--}}
{{--                                        placeholder="Tìm kiếm ...">--}}
{{--                                    <!-- Nút tìm kiếm -->--}}
{{--                                    <button type="button" class="btn btn-primary">--}}
{{--                                        <i class="las la-search"></i>--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="card b-radius--10">
                    <div class="card-body p-0">
                        <div class="table-responsive--sm table-responsive">
                            <table class="table--light style--two table" id="data-table">
                                <thead>
                                    <tr>
                                        <th>@lang('STT')</th>
                                        <th>@lang('Mã phòng')</th>
                                        <th>@lang('Loại phòng')</th>
                                        <th>@lang('Số phòng')</th>
                                        <th>@lang('Cơ sở vật chất')</th>
                                        @can('admin.hotel.room.facilities.all')
                                            <th>@lang('Hành động')</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="pagination" class="mt-3">

            </div>
        </div>
    </div>
    @can('')
        @push('breadcrumb-plugins')
            <button type="button" class="btn btn-outline--primary mt-1 btn-add">
                <i class="las la-plus"></i>

            </button>
        @endpush
    @endcan

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Thêm mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="roomsAddFacilityForm" method="POST" action="">
                        <input type="hidden" name="_method" id="method" value="POST">
                        <input type="hidden" name="id" id="recordId">
                        <div class="row">
                            <div class="form-group mb-3">
                                <label for="">Mã Phòng</label>
                                <select name="room_id" id="room-multiple-choice" class="form-control">
                                    <option value="" selected>--Chọn mã phòng--</option>
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->id }}">{{ $room->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group mb-3">
                                <label for="">Các tiện nghi <code>(Được chọn nhiều)</code></label>
                                <div class="form-check-group mt-3">
                                    @if ($facilities->isNotEmpty())
                                        @foreach ($facilities as $facility)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="{{ $facility->id }}"
                                                    name="facilities_id[]" multiple id="checkbox-facility-add">
                                                <label class="form-check-label" for="checkbox-facility-add">
                                                    {{ $facility->title }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @else
                                        <p>Chưa có tiện ích nào!</p>
                                    @endif
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


@endsection

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
                                    roomSelect.append(
                                        `<option value="${room.id}" ${selected}>${room.code}</option>`
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



            });
        })(jQuery);
    </script>
@endpush

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

        @media(max-width:768px){
        .flex-nowrap{
            flex-wrap: nowrap !important;
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

@push('style-lib')
@endpush

@push('script-lib')
@endpush

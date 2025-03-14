@extends('admin.layouts.master_iframe')
@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="border">
                <div class="card b-radius--10">
                    <div class="card-body p-0">
                        <div class="table-responsive--sm table-responsive">
                            <table class="table--light style--two table" id="data-table">
                                <thead>
                                    <tr>
                                         @can('admin.hotel.room.facilities.all')
                                            <th>@lang('Hành động')</th>
                                        @endcan
                                        <th>@lang('STT')</th>
                                        <th>@lang('Mã phòng')</th>
                                        <th>@lang('Loại phòng')</th>
                                        <th>@lang('Tên phòng')</th>
                                        <th>@lang('Cơ sở vật chất')</th>
                                
                                    </tr>
                                </thead>
                                <tbody id="data">
                                @if ($rooms->isNotEmpty())
                                    @foreach ($rooms as $id => $room)
                                        @if ($room->facilities->count())
                                            <tr data-id="{{ $room->id }}">
                                            @can('admin.hotel.room.facilities.all')
                                                
                                                <td style="width:20px">
                                                    <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"/><circle cx="10.5" cy="5.5" r="1"/><circle cx="10.5" cy="15.5" r="1"/></g></svg>
                                                                    
                                                    <div class="dropdown menu_dropdown_check_in" id="dropdown-menu" style="position:fixed">
                                                        <div class="dropdown-item booked_room_edit">
                                                        <a class="btn btn-sm btn-outline--primary btn-edit" data-id="{{ $room->id }}"  style="color:black !important;border:none;padding:5px"
                                                                        data-modal_title="@lang('Cập nhật cơ sở vật chất')" type="button">
                                                                    @lang('Sửa cơ sở vật chất')
                                                        </a>
                                                        </div>
                                                    
                                                        <div class="dropdown-item booked_room_detail">
                                                        <button class=" btn-delete" data-id="{{ $room->id }}"
                                                            data-modal_title="@lang('Xóa')" type="button">
                                                                    Xóa cơ sở vật chất
                                                        </button>
                                                        </div>
                                            </div>

                                            </td>
                                            @endcan
                                                <td data-label="STT" style="text-align:right">
                                                @php
                                                $stt = $rooms->total() - ($rooms->currentPage() - 1) * $rooms->perPage() - $id;
                                                    @endphp
                                                {{ $stt }}
                                                </td>
                                                <td data-label="Mã phòng">{{ $room->code }}</td>
                                                <td data-label="Loại phòng">     @php
                                                        $type_name = \App\Models\RoomType::where('id',$room->room_type_id)->value('name');
                                                    @endphp
                                                    {{ $type_name }}
                                                </td>
                                                <td data-label="Số phòng">{{ $room->room_number }}</td>
                                                <td data-label="Cơ sở vật chất" >
                                                    @if ($room->facilities->count() > 0)
                                                        @foreach ($room->facilities as $item)
                                                            <span class="badge {{ getRandomColor() }}">{{ $item->title }}</span>
                                                        @endforeach
                                                    @else
                                                        <p>Chưa có cơ sở vật chất nào </p>
                                                    @endif

                                                </td>
                                        
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif


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
        
              <div class="col-md-12 d-flex">
           <a  href="{{ route('admin.hotel.room.facilities.all') }}">
           <button type="button" class="btn btn--primary"data-modal_title="Làm mới">
                <i class="fa fa-repeat p-1"></i>

            </button>
           </a>   
           <a>
           <button type="button" class="btn btn--primary btn-add " style="margin-left:8px">
                <i class="las la-plus p-1 "></i>

            </button>
           </a>     
           <form role="form" enctype="multipart/form-data" action="{{route('admin.hotel.room.facilities.search')}}">
                        <div class="form-group mb-0" style="display: flex;">
                            <input class="searchInput" name="code"
                                   style="height: 35px;border: 1px solid rgb(121, 117, 117, 0.5);margin-left:8px"
                                    placeholder="Mã phòng/Tên phòng" value="{{ $code ?? '' }}">
                
                            <select name="room_type_id" class="form-control choose ml-1" id="tim-loai-phong" style="width:250px;margin-left: 8px;height: 35px">
                                    <option value="">--Chọn loại phòng--</option>
                                    @foreach($room_type as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                            </select>
                            
                            <button type="submit" class="btn btn--primary" style="margin-left: 8px;">
                                <i class="las la-search p-1"></i>
                            </button>
                        </div>
                    </form> 
                    </div>
                    <div class="pager-wrap">
                            <div class="k-widget d-flex">
                                <div class="pagination-tb" style="font-size: 13px;margin: 0 auto">
                                    {{ $rooms->links('pagination::bootstrap-4') }}
                                </div>
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
                <div class="pager-wrap">
                            <div class="k-widget d-flex">
                                <div class="pagination-tb" style="font-size: 13px;margin: 0 auto">
                                    {{ $rooms->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>


@endsection
@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/modal.css') }}">
    
   <style>
          
          .pagination .page-item .page-link, .pagination .page-item span{
              width: 22px !important;
              height: auto !important;
              background-color: #4634ff !important;
              color: white !important;
          }
          .pagination .page-item.active .page-link{
              background-color: #071251 !important;
          }

  </style>
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
        $('.btn-delete').on('click', function() {
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
                                if (data.status ==='success') {
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
                $('.choose').change(function () {
            var room_type_id = $('#tim-loai-phong').val();
            var url = "{{ route('admin.hotel.room.facilities.ajax') }}";
            $.ajax({
                type: 'GET',
                cache: false,
                url: url,
                data: {
                    room_type_id: room_type_id,
        
                },
                success: function (response) {
                    if (response) {
                        $('#data').html(response)
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            })
        });
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

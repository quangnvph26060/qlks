@extends('admin.layouts.master_iframe')
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
{{--                                    <button type="button" class="btn btn--primary">--}}
{{--                                        <i class="las la-search p-1"></i>--}}
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
                                @if ($rooms->isNotEmpty())
                                    @foreach ($rooms as $id => $room)
                                        @if ($rooms->count() > 0)
                                            <tr data-id="{{ $room->id }}" class="{{$id % 2 !==0 ? 'bg-white' : 'bg-gray'}}">
                                            @can('admin.hotel.room.amenities.all')
                                                
                                                <td style="width:20px;">
                                                <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"/><circle cx="10.5" cy="5.5" r="1"/><circle cx="10.5" cy="15.5" r="1"/></g></svg>
                                                        
                                                    <div class="dropdown menu_dropdown_check_in" id="dropdown-menu" style="position:fixed">
                                                        <div class="dropdown-item">
                                                        <a class="btn btn-sm btn-outline--primary btn-edit" data-id="{{ $room->id }}"
                                                            data-modal_title="@lang('Cập nhật tiện nghi')" type="button" style="color:black !important;border:none;padding:5px">
                                                            Sửa tiện nghi
                                                        </a>
                                                    </div>    
                                                                    
                                                    <div class="dropdown-item booked_room_detail">
                                                        <button class=" btn-delete icon-delete-room" data-id="{{ $room->id }}" data-modal_title="@lang('Xóa tiện nghi')" type="button"data-pro="0">Xóa tiện nghi
                                                        </div>
                                                        
                                                    </div>
                                                </td>
                                            @endcan
                                                <td style="text-align:right">      
                                                    @php
                                                        $stt = $rooms->total() - ($rooms->currentPage() - 1) * $rooms->perPage() - $id;
                                                    @endphp
                                                    {{ $stt }}
                                                </td>
                                                <td>{{ $room->code }}</td>
                                                <td data-label="Loại phòng">
                                                    @php
                                                        $type_name = \App\Models\RoomType::where('id',$room->room_type_id)->value('name');
                                                    @endphp
                                                    {{ $type_name }}
                                                </td>                                                  
                                                <td>{{ $room->room_number }}</td>
                                                <td>
                                                    @if ($room->amenities->count() > 0)
                                                        @foreach ($room->amenities as $item)
                                                            <span class="badge {{ getRandomColor() }}">{{ $item->title }}</span>
                                                        @endforeach
                                                    @else
                                                        <p>Chưa có tiện nghi nào !</p>
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
            <div id="pagination" class="mt-3">

            </div>
        </div>
    </div>
    @can('')
 
        @push('breadcrumb-plugins')
        <div class="card-body mt-1">
            <div class="row">
        
              <div class="col-md-12 d-flex">
           <a  href="{{ route('admin.hotel.room.amenities.all') }}">
           <button type="button" class="btn btn--primary"data-modal_title="Làm mới">
                <i class="fa fa-repeat p-1"></i>

            </button>
           </a>   
           <a>
           <button type="button" class="btn btn--primary btn-add " style="margin-left:8px">
                <i class="las la-plus p-1 "></i>

            </button>
           </a>     
           <form role="form" enctype="multipart/form-data" action="{{route('admin.hotel.room.amenities.search')}}">
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
                    <form id="roomsAddAmenityForm" method="POST" action="">
                        <input type="hidden" name="_method" id="method" value="POST">
                        <input type="hidden" name="id" id="recordId">
                        <div class="row">
                            <div class="form-group mb-3">
                                <label for="">Phòng</label>
                                <select name="room_id" id="room-multiple-choice" class="form-control">
                                    <option value="" selected>--Chọn phòng--</option>
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
                                    @if ($amenities->isNotEmpty())
                                        @foreach ($amenities as $amenity)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="{{ $amenity->id }}"
                                                    name="amenities_id[]" multiple id="checkbox-amenity-add">
                                                <label class="form-check-label" for="checkbox-amenity-add">
                                                    {{ $amenity->title }}
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
                            @if ($amenities->isNotEmpty())
                                <button type="submit" class="btn btn-primary">Thực hiện</button>
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
                                    roomSelect.append(
                                        `<option value="${room.id}" ${selected}>${room.code}</option>`
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



            });
        })(jQuery);
 
        $('.choose').change(function () {
            var room_type_id = $('#tim-loai-phong').val();
            var url = "{{ route('admin.hotel.room.amenities.ajax') }}";
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
        $('.icon-delete-room').on('click', function() {
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
        @media(max-width:768px){
        .flex-nowrap{
            flex-wrap: nowrap !important;
        }
    }
    </style>
@endpush

@push('style-lib')
@endpush

@push('script-lib')
@endpush

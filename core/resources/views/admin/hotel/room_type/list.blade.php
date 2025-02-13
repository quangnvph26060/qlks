@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
{{--            <div class="d-flex justify-content-between mb-3 row order-1">--}}
{{--                <div class="dt-length col-md-6 col-4">--}}
{{--                    <select name="example_length" id="perPage" style=" padding: 1px 3px; margin-right: 8px;"--}}
{{--                        aria-controls="example" class="perPage">--}}
{{--                        <option value="10">10</option>--}}
{{--                        <option value="25">25</option>--}}
{{--                        <option value="50">50</option>--}}
{{--                        <option value="100">100</option>--}}
{{--                    </select><label for="perPage"> entries per page</label>--}}
{{--                </div>--}}

{{--                <div class="search col-md-4 col-12" style="text-align: end;">--}}
{{--                    --}}{{-- <label for="searchInput">Search:</label> --}}
{{--                    <div class="input-group" style="justify-content: end;">--}}
{{--                        <input class="searchInput"--}}

{{--                        type="search" placeholder="Tìm kiếm...">--}}
{{--                        <button type="submit" class="btn btn-primary">--}}
{{--                            <i class="las la-search"></i>--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--            </div>--}}
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table table-striped" id="data-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>@lang('STT')</th>
                                    @can(['admin.hotel.room.type.edit', 'admin.hotel.room.type.status',
                                        'admin.hotel.room.type.destroy'])
                                        <th>@lang('Hành động')</th>
                                    @endcan
                                    <th>@lang('Loại phòng')</th>
                                    <th>@lang('Mã phòng')</th>
                                    <th>@lang('Tên phòng')</th>
                                    <th>@lang('Số người')</th>
                                    <th>@lang('Số giường')</th>
                                    {{-- <th>@lang('Tiện nghi')</th>
                                    <th>@lang('Cở sở vật chất')</th>
                                    <th>@lang('Giá giờ')</th>
                                    <th>@lang('Giá ngày')</th>
                                    <th>@lang('Giá đêm')</th> --}}
                                    <th>@lang('Trạng thái')</th>
                                 
                                </tr>
                            </thead>
                            <tbody id="data" >

                            </tbody>
                        </table>

                    </div>
                </div>
                <div id="pagination" class="m-3">

                </div>
            </div>
        </div>
    </div>
    @can('admin.hotel.room.type.status')
        <x-confirmation-modal />
    @endcan
@endsection
@can('admin.hotel.room.type.create')
    @push('breadcrumb-plugins')
           <div class="card-body mt-1">
            <div class="row">
              <div class="col-md-12 d-flex">
                    <a class="btn btn-sm btn-outline--primary" href="{{ route('admin.hotel.room.type.create') }}"><i
                    class="las la-plus mt-1 p-1"></i></a>
                    <form role="form" enctype="multipart/form-data" action="{{route('admin.hotel.room.type.search')}}">
                        <div class="form-group mb-0" style="display: flex;">
                            <input class="searchInput" name="code"
                                   style="height: 35px;border: 1px solid rgb(121, 117, 117, 0.5);"
                                    placeholder="Mã phòng/Tên phòng">
                
                            <select name="room_type_id" class="form-control choose ml-1" id="tim-loai-phong" style="width:250px;margin-left: 8px;height: 35px">
                                    <option value="">--Chọn loại phòng--</option>
                                    @foreach($room_type as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                            </select>
                     
                            <select name="status"  class="form-control choose" id="tim-trang-thai"  style="width:250px;margin-left: 8px;height: 35px">
                                    <option value="">--Chọn trạng thái--</option>
                            
                                        <option value="0">Không hoạt động</option>
                                        <option value="1">Hoạt động</option>

            
                             </select>
                            
                            <button type="submit" class="btn btn-primary" style="margin-left: 8px;height: 35px">
                                <i class="las la-search p-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            
            </div>
        </div>

  
{{--        <div class="dropdown col-md-1 col-8" style="display: flex;--}}
{{--                justify-content: end">--}}
{{--            <a class="btn btn-outline-secondary dropdown-toggle d-flex justify-content-center--}}
{{--                align-items-center" href="#" role="button"--}}
{{--               data-bs-toggle="dropdown" aria-expanded="false">--}}
{{--                Thao tác--}}
{{--            </a>--}}
{{--            <ul class="dropdown-menu">--}}
{{--                <li><a class="dropdown-item"--}}
{{--                       href="{{ route('admin.hotel.room.type.all.deleted') }}">Các phòng đã xóa</a>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        </div>--}}
    @endpush
@endcan
@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/modal.css') }}">
@endpush
@push('script')
    <script src="{{ asset('assets/admin/js/dataTable.js') }}"></script>
    <script>
        toggleRepresentatives = function(id, button) {
            const row = document.getElementById('rep-' + id);
            row.classList.toggle('show');
            button.classList.toggle('collapsed');
        };
        $(document).ready(function() {
            const apiUrl = '{{ route('admin.hotel.room.type.all') }}';
            initDataFetch(apiUrl);


            $(document).on('click', '.btn-delete', function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Xóa phòng',
                    text: 'Bạn có chắc chắn không?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Huỷ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('admin.hotel.room.type.destroy', ':id') }}"
                                .replace(':id', id),
                            success: function(response) {
                                if (response.status) {
                                    showSwalMessage('success', response
                                        .message);
                                    initDataFetch(apiUrl);
                                } else {
                                    showSwalMessage('error', response
                                        .message);
                                }
                            }
                        });
                    }
                })
            })

              $(document).ready(function () {
        $('.choose').change(function () {
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
        //jquery for toggle sub menus
        $('.has-arrow').click(function () {
            $(this).next('.menu-side').slideToggle();
            $(this).find('.dropdown').toggleClass('rotate');
        });

        //jquery for expand and collapse the sidebar
        $('.menu-btn').click(function () {
            $('.side-bar').addClass('active');
            $('.menu-btn').css("visibility", "hidden");
        });

        $('.close-btn').click(function () {
            $('.side-bar').removeClass('active');
            $('.menu-btn').css("visibility", "visible");
        });
    });
        })
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
        @media (max-width: 991px) {

            .table-responsive--md tr th,
            .table-responsive--md tr td {
                padding-left: 4% !important;
            }
        }
        th, td {
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
            #perPage{
                width: 100% !important;
            }
            .dropdown{
                order: 2;
            }
            .search{
                order: 3;
                margin-top: 15px
            }

        }
        .menu_dropdown_check_in{
            right: auto !important;
        }
    </style>
@endpush

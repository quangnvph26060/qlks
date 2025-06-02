@extends('admin.layouts.master_iframe')
@section('panel')
    <div class="row">
        <div class="col-12">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 d-flex">
                        <a class="mr-1" href="{{ route('admin.hotel.setup.code.all') }}">
                            <button class="btn btn--primary" data-modal_title="Làm mới">
                                <i class="fa fa-repeat p-1"></i>
                            </button>
                        </a>
                        <a>
                            <button class="btn btn--primary" data-modal_title="Thêm mới mã mặc định" type="button"
                                data-bs-toggle="modal" data-bs-target="#modal-add-code" style="margin-left:10px">
                                <i class="las la-plus  p-1"></i>
                            </button>
                        </a>
                        <form role="form" enctype="multipart/form-data"
                            action="{{ route('admin.hotel.setup.code.search') }}">
                            <div class="form-group position-relative mb-0">
                                <input class="searchInput" name="code"
                                    style="height: 35px;border: 1px solid rgb(121, 117, 117, 0.5);margin-left: 8px;"
                                    placeholder="Mã mặc định">
                                <input class="searchInput" name="menu_name"
                                    style="height: 35px;border: 1px solid rgb(121, 117, 117, 0.5); margin-left: 8px;"
                                    placeholder="Tên menu">
                                <a>
                                    <button type="submit" class="btn btn--primary"
                                        style="padding-right:15px;padding-left:15px">
                                        <i class="las la-search p-1"></i>
                                    </button>
                                </a>

                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <div class="modal fade" id="modal-add-code" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" id="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Thêm mã mặc định
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <form id="addCode" method="POST" action="{{ route('admin.hotel.setup.code.store') }}">
                                {!! csrf_field() !!}
                                {{ method_field('POST') }}
                                <!-- Input 1 -->
                                <div class="mb-3">
                                    <label for="statusCode" class="form-label">Mã mặc định</label>
                                    <input type="text" class="form-control " name="code" id="add-code"
                                        placeholder="Nhập mã mặc định" value="">
                                    <span class="invalid-feedback d-block" style="font-weight: 500" id="code_error"></span>
                                </div>
                                <!-- Input 2 -->
                                <div class="mb-3">
                                    <label for="statusName" class="form-label">Chọn danh mục</label>
                                    @php
                                        $menuOptions = [
                                            'Danh mục loại phòng',
                                            'Danh mục phòng',
                                            'Danh mục dịch vụ',
                                            'Cài đặt tiện nghi',
                                            'Cài đặt tính giá',
                                            'Cài đặt cơ sở vật chất',
                                            'Cài đặt sản phẩm',
                                            'Danh mục người dùng',
                                            'Danh mục khách hàng',
                                            'Danh mục nguồn khách hàng',
                                            'Danh mục nhóm khách',
                                            'Danh mục trạng thái',
                                             
                                        ];

                                        // Lấy danh sách menu đã tồn tại cho đơn vị hiện tại
                                        $existingMenus = \App\Models\SetupCode::where('unit_code', unitCode())
                                            ->pluck('menu_name')
                                            ->toArray();
                                    @endphp

                                    <select class="form-control" name="menu_name" id="add-menu-name">
                                        @foreach ($menuOptions as $menu)
                                            <option value="{{ $menu }}"
                                                {{ in_array($menu, $existingMenus) ? 'disabled' : '' }}>
                                                {{ $menu }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>


                                <div class="modal-footer">
                                    <button type="sumit" class="btn btn-primary" id="click-btn-add-code">Lưu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal fade" id="status-code" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" id="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Chỉnh sửa mã mặc định
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editCode" method="POST" action="">
                                <input type="hidden" id="method" name="_method" value="">

                                {!! csrf_field() !!}
                                <!-- Input 1 -->
                                <div class="mb-3">
                                    <label for="statusCode" class="form-label">Mã mặc định</label>
                                    <input type="text" class="form-control " name="code" id="edit-code"
                                        placeholder="Nhập mã mặc định" value="">
                                    <span class="invalid-feedback d-block" style="font-weight: 500"
                                        id="edit_code_error"></span>
                                </div>
                                <!-- Input 2 -->
                                <div class="mb-3">
                                    <label for="statusName" class="form-label">Chọn danh mục</label>
                                    <select class="form-control" name="menu_name" id="edit-menu-name">
                                        @foreach ($menuOptions as $menu)
                                            <option value="{{ $menu }}"
                                                {{ in_array($menu, $existingMenus) ? 'disabled' : '' }}>
                                                {{ $menu }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary"
                                        id="click-btn-update-code">Lưu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row gy-4">
                <div class="col-12">
                    <div class="emptyArea"></div>
                </div>
                <div class="table-responsive--md table-responsive" style="overflow-x: auto;">
                    <table class="table--light style--two table">
                        <thead>
                            <tr>
                                <th>Hành động</th>
                                <th>STT</th>
                                <th>@lang('Tên danh mục')</th>
                                <th>@lang('Mã mặc định')</th>
                                <!-- <th>@lang('Ghi chú')</th> -->
                                <!-- <th>@lang('Trạng thái')</th> -->

                            </tr>
                        </thead>
                        <tbody id="main-table-hotel">
                            @forelse($setup_codes as $id => $item)
                                <tr data-id="{{ $item->id }}">
                                    <td style="width:20px;">
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
                                            <div class="dropdown-item"><a data-id="{{ $item->id }}"
                                                    class="btn-edit-status" data-bs-toggle="modal"
                                                    data-bs-target="#status-code" style="color:black">
                                                    Sửa
                                                </a>
                                            </div>

                                            <div class="dropdown-item booked_room_detail"> <button
                                                    class=" btn-delete icon-delete-room" data-id="{{ $item->id }}"
                                                    data-modal_title="@lang('Xóa trạng thái')" type="button"
                                                    data-pro="0">Xóa</div>

                                        </div>
                                    </td>
                                    <td style="width:20px;text-align:right">
                                        @php
                                            $stt =
                                                $setup_codes->total() -
                                                ($setup_codes->currentPage() - 1) * $setup_codes->perPage() +
                                                $id;
                                        @endphp
                                        {{ $stt }}
                                    </td>

                                    <td>
                                        {{ $item->menu_name }}
                                    </td>
                                    <td>
                                        {{ $item->code }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/admin/js/highlighter22.js') }}"></script>
    <script src="{{ asset('assets/validator/validator.js') }}"></script>
@endpush
@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/modal.css') }}">

    <style>
        .navbar__right {
            display: none;
        }

        #navbar-wrapper {
            padding: 0px 30px 20px;
        }
    </style>
@endpush
@push('script')
    <script>
        $(document).ready(function() {
            var formEconomyEdit = {
                'code': {
                    'element': document.getElementById('add-code'),
                    'error': document.getElementById('code_error'),
                    'validations': [{
                            'func': function(value) {
                                return checkRequired(value); // check trống
                            },
                            'message': generateErrorMessage('MS001')
                        },
                        {
                            'func': function(value) {
                                return checkKey(value);
                            },
                            'message': generateErrorMessage('KT001')
                        },
                        {
                            'func': function(value) {
                                return isAllUpperCase(value);
                            },
                            'message': generateErrorMessage('INHOA','Mã code')
                        },
                    ]
                },


            }
            $(document).on('click', '#click-btn-add-code', function() {
                if (validateAllFields(formEconomyEdit)) {
                    document.getElementById('addCode').submit(); // là id trong form
                } else {
                    event.preventDefault();

                }
            });
            // $(document).on('click', '#click-btn-status-update', function() {
            //     if (validateAllFields(formEconomyEdit)) {
            //         document.getElementById('btn-submit-status-update').submit(); // là id trong form
            //     }
            // });
            // add

            // sửa
            $('.btn-edit-status').on('click', function() {
                var dataId = $(this).data('id');
                // ajax request
                $.ajax({
                    url: `{{ route('admin.hotel.setup.code.edit', '') }}/${dataId}`,
                    type: 'GET',
                    success: function(data) {
                        if (data) {
                            $('#edit-code').val(data.code);
                            $('#edit-menu-name').val(data.menu_name).change();
                            // $('#edit-unit-code').val(data.unit_code).change();
                            $('#method').attr('value', 'PUT');
                            $('#editCode').attr('action',
                                '{{ route('admin.hotel.setup.code.update', '') }}/' +
                                dataId + '')

                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });

            });
            // xóa
            $('.icon-delete-room').on('click', function() {
                var dataId = $(this).data('id');
                var rowToDelete = $(`tr[data-id="${dataId}"]`);
                Swal.fire({
                    title: 'Xác nhận xóa trạng thái?',
                    text: 'Bạn có chắc chắn muốn xóa mã này không?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy bỏ',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ajax
                        $.ajax({
                            url: `{{ route('admin.hotel.setup.code.delete', '') }}/${dataId}`,
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
            // chỉnh sửa trạng thái


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
        .system-search-icon {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            aspect-ratio: 1;
            padding: 5px;
            display: grid;
            place-items: center;
            color: #888;
        }

        .system-search-icon~.form-control {
            padding-left: 45px;
        }

        .widget-seven .widget-seven__content-amount {
            font-size: 22px;
        }

        .widget-seven .widget-seven__content-subheading {
            font-weight: normal;
        }
        #add-code {
  text-transform: uppercase;
}

        .empty-search img {
            width: 120px;
            margin-bottom: 15px;
        }

        a.item-link:focus,
        a.item-link:hover {
            background: #4634ff38;
        }
    </style>
@endpush

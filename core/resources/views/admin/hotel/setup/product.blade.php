@extends('admin.layouts.master_iframe')
@section('panel')
    @include('admin.messages')
    @if ($categories->hasPages())
        <div class="pager-wrap d-flex     justify-content-center mt-2 mb-2">
            <div class="k-widget d-flex">
                <div class="pagination-tb" style="font-size: 13px;margin: 0 auto">
                    {{ $categories->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <!-- Khối bên phải: Danh sách danh mục -->
        <div class="col-md-12">
            <div>
                <div class="d-flex justify-content-between">
                    {{--                    <div class="dt-length"> --}}
                    {{--                        <select name="example_length" id="perPage" style=" padding: 1px 3px; margin-right: 8px;" --}}
                    {{--                            aria-controls="example" class="perPage"> --}}
                    {{--                            <option value="10">10</option> --}}
                    {{--                            <option value="25">25</option> --}}
                    {{--                            <option value="50">50</option> --}}
                    {{--                            <option value="100">100</option> --}}
                    {{--                        </select><label for="perPage"> entries per page</label> --}}
                    {{--                    </div> --}}
                    {{--                    <div class="search"> --}}
                    {{--                        <label for="searchInput">Search:</label> --}}
                    {{--                        <input class="searchInput" --}}
                    {{--                            style="padding: 1px 3px; border: 1px solid rgb(121, 117, 117, 0.5); margin-left: 8px;" --}}
                    {{--                            type="search" placeholder="Tìm kiếm..."> --}}
                    {{--                    </div> --}}
                </div>
                <div class="card b-radius--10">
                    <div class="card-body p-0">
                        <div class="table-responsive--sm table-responsive">
                            <table class="table--light style--two table" id="data-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>@lang('Hành động')</th>
                                        <th style="width:20px">STT</th>
                                        <th>@lang('Mã sản phẩm')</th>
                                        {{-- <th style="width:90px">@lang('Ảnh')</th> --}}
                                        <th>@lang('Tên sản phẩm')</th>
                                        {{-- <th>@lang('Giá nhập')</th> --}}
                                        <th>@lang('Giá bán')</th>
                                        {{-- <th>@lang('Tồn kho')</th> --}}
                                        <th>@lang('Trạng thái')</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $id => $product)
                                        <tr data-id="{{ $product->id }}" class={{ $id % 2 !== 0 ? 'bg-white' : 'bg-gray' }}>


                                            <td class="d-none-mobi" style="width:20px;">
                                                <button class="btn btn-link btn-toggle" type="button"
                                                    onclick=" toggleRepresentatives('{{ $product->id }}', this)"></button>
                                            </td>
                                            <td style="width:20px;" class="d-none-mobi">
                                                <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg"
                                                    width="30" height="30" viewBox="0 0 21 21">
                                                    <g fill="currentColor" fill-rule="evenodd">
                                                        <circle cx="10.5" cy="10.5" r="1" />
                                                        <circle cx="10.5" cy="5.5" r="1" />
                                                        <circle cx="10.5" cy="15.5" r="1" />
                                                    </g>
                                                </svg>
                                                <div class="dropdown menu_dropdown_check_in" id="dropdown-menu"
                                                    style="position:fixed">
                                                    <div class="dropdown-item"><a
                                                            href="{{ route('admin.product.edit', $product->id) }}"
                                                            style="color:black">
                                                            Sửa
                                                        </a>
                                                    </div>

                                                    <div class="dropdown-item booked_room_detail"> <button
                                                            class=" btn-delete icon-delete-room"
                                                            data-id="{{ $product->id }}"
                                                            data-modal_title="@lang('Xóa trạng thái')" type="button"
                                                            data-pro="0"> Xoá</div>

                                                </div>
                                            </td>
                                            <td data-label="STT" style="text-align:right">
                                                @php
                                                    $stt =
                                                        $categories->total() +
                                                        ($categories->currentPage() - 1) * $categories->perPage() +
                                                        $id;
                                                @endphp
                                                {{ $stt }}
                                            </td>
                                            <td data-label="@lang('Mã')">{{ $product->sku }}</td>
                                            {{-- <td data-label="@lang('Ảnh')" style="text-align:center">
                                                @if ($product->image_path)
                                                    <i class="fa fa-check" style="color:green;text-align: center"></i>
                                                @else
                                                    <i class="fa fa-close" style="color:red;text-align: center"></i>
                                                @endif
                                            </td> --}}
                                            <td data-label="@lang('Tên sản phẩm')">
                                                <p id="ellipsis">{{ $product->name ?? '' }}</p>
                                            </td>
                                            {{-- <td data-label="@lang('Giá nhập')" style="text-align:right">
                                                {{ showAmount($product->import_price) }}
                                            </td> --}}
                                            <td data-label="@lang('Giá bán')" style="text-align:right">
                                                {{ showAmount($product->selling_price) }}
                                            </td>
                                            {{-- <td style="text-align:right" data-label="@lang('Tồn kho')">
                                                {{ $product->stock ?? 0 }}</td> --}}
                                            <td data-label="@lang('Ảnh')" style="text-align:center;width:100px">
                                                @if ($product->is_published == 1)
                                                    <i class="fa fa-check" style="color:green;text-align: center"></i>
                                                @else
                                                    <i class="fa fa-close" style="color:red;text-align: center"></i>
                                                @endif
                                            </td>
                                            <td class="d-block-mobi mt-1">
                                                <div class="action-buttons gap-2">
                                                    <div class="dropdown-item d-flex justify-content-center" style=" background: orange;color: white !important;border-radius: 4px;border: none"><a
                                                            href="{{ route('admin.product.edit', $product->id) }}"
                                                            style=" background: orange;color: white !important;border-radius: 4px;border: none">
                                                            Sửa
                                                        </a>
                                                    </div>

                                                    <div class="dropdown-item booked_room_detail d-flex justify-content-center"  style=" background: red;color: white !important;border-radius: 4px;border: none"> <button
                                                            class=" btn-delete icon-delete-room"
                                                            data-id="{{ $product->id }}"
                                                             style=" background: red;color: white !important;border-radius: 4px;border: none"
                                                            data-modal_title="@lang('Xóa trạng thái')" type="button"
                                                            data-pro="0"> Xoá</div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="collapse" id="rep-{{ $product->id }}">
                                            <td colspan="8">
                                                <div class="representatives-container">
                                                    <span class="representatives-label">Danh mục:</span>
                                                    <span class="representatives-list">
                                                        <span class="badge bg-warning me-2 cursor-pointer">
                                                            <small class="representative-name">
                                                                {{ $product->category->name }}</small>
                                                        </span>
                                                    </span>
                                                </div>
                                                <div class="representatives-container">
                                                    <span class="representatives-label">Xuất bản:</span>
                                                    <span class="representatives-list">
                                                        <div class="form-check form-switch m-0">
                                                            <input class="form-check-input update-status" type="checkbox"
                                                                id="is_published" @checked($product->is_published)>
                                                        </div>
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

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
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 d-flex flex-column flex-md-row gap-1">

                        <div class="">
                            <a class="mr-1" href="{{ route('admin.hotel.setup.product.all') }}">
                                <button class="btn btn--primary" data-modal_title="Làm mới">
                                    <i class="fa fa-repeat p-1"></i>
                                </button>
                            </a>
                            <a href="{{ route('admin.product.create') }}">
                                <button class="btn btn--primary" style="margin-left:10px">
                                    <i class="las la-plus  p-1"></i>
                                </button>
                            </a>
                        </div>
                        <div class="">
                            <form role="form" enctype="multipart/form-data"
                                action="{{ route('admin.hotel.setup.product.search') }}">
                                <div class="form-group position-relative">
                                    <input class="searchInput mb-2" name="source_code"
                                        style="height: 35px;border: 1px solid rgb(121, 117, 117, 0.5);" placeholder="Mã sản phẩm"
                                        value="{{ $sku ?? '' }}">
                                    <input class="searchInput" name="source_name"
                                        style="height: 35px;border: 1px solid rgb(121, 117, 117, 0.5);" placeholder="Tên sản phẩm"
                                        value="{{ $name ?? '' }}">
                                    <a>
                                        <button type="submit" class="btn btn--primary">
                                            <i class="las la-search p-1"></i>
                                        </button>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        @endpush
    @endcan
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

        .pagination .page-item .page-link,
        .pagination .page-item span {
            /* width: 22px !important;
                            height: auto !important;
                            background-color: #4634ff !important;
                            color: white !important; */
        }

        #data-table td,
        #data-table th {
            padding-top: 0.5px !important;
            padding-bottom: 0.5px !important;
            line-height: 1.2 !important;
            height: 35px !important;
            vertical-align: middle;
        }

        .pagination .page-item.active .page-link {
            background-color: #071251 !important;
        }
    </style>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict"

            window.toggleRepresentatives = function(id, button) {
                const row = document.getElementById('rep-' + id);
                row.classList.toggle('show');
                button.classList.toggle('collapsed');
            };

            $(document).ready(function() {
                const apiUrl = '{{ route('admin.product.index') }}';
                initDataFetch(apiUrl);




            });

            $(document).on('change', '.update-status', function() {
                const isChecked = this.checked;

                var row = $(this).closest('tr');

                var rowId = row.attr('id');

                var productId = rowId.split('-')[1];

                const $checkbox = $(this);

                Swal.fire({
                    title: "@lang('Cập nhật trạng thái')",
                    text: "@lang('Bạn có chắc chắn muốn cập nhật không?')",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "@lang('Xác nhận')"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('admin.product.status', ':id') }}'.replace(':id',
                                productId),
                            method: "PUT",
                            success: function(response) {
                                if (response.status) {
                                    showSwalMessage('success', response
                                        .message);
                                    console.log(productId);
                                }
                            }
                        });
                    } else {
                        $checkbox.prop('checked', !
                            isChecked);
                    }
                });
            });
        })(jQuery);
        $(document).ready(function() {
            $(document).on('click', '.btn-delete', function() {
                var row = $(this).closest('tr');

                var productId = row.data('id');

                Swal.fire({
                    title: 'Xóa sản phẩm',
                    text: 'Bạn muốn xóa sản phẩm này ?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Huỷ'

                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'DELETE',
                            url: '{{ route('admin.product.destroy', ':id') }}'
                                .replace(':id', productId),
                            success: function(response) {

                                if (response.status) {
                                    row.remove();
                                    $(`#rep-${productId}`).remove();
                                    notData();
                                    showSwalMessage('success', response
                                        .message);
                                } else {
                                    showSwalMessage('error', response.message);
                                }
                            },

                        })
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
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>

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

        @media (max-width: 767px) {

            .table-responsive--sm tr th,
            .table-responsive--sm tr td {
                display: block;
                padding-left: 5% !important;
                text-align: right !important;
            }
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

        /* Hiệu ứng mở rộng và thu gọn */
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

        @media (max-width: 767.98px) {
            .searchInput {
                width: 100% !important;
            }
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

        #ellipsis {
            max-width: 250px;
            /* Chiều rộng tối đa của phần tử */
            white-space: nowrap;
            /* Không cho văn bản xuống dòng */
            overflow: hidden;
            /* Ẩn phần văn bản bị tràn */
            text-overflow: ellipsis;/
        }

        @media (max-width: 767.98px) {
            #ellipsis {
                max-width: none !important;
            }
        }
    </style>
@endpush

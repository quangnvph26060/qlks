@extends('admin.layouts.app')
@section('panel')
    @include('admin.messages')
    <div class="row">
        <!-- Khối bên phải: Danh sách danh mục -->
        <div class="col-md-12">
            <div class="scrollable-table border p-2">
                <div class="d-flex justify-content-between mb-3">
                    <div class="dt-length">
                        <select name="example_length" id="perPage" style=" padding: 1px 3px; margin-right: 8px;"
                            aria-controls="example" class="perPage">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select><label for="perPage"> entries per page</label>
                    </div>
                    <div class="search">
                        <label for="searchInput">Search:</label>
                        <input class="searchInput"
                            style="padding: 1px 3px; border: 1px solid rgb(121, 117, 117, 0.5); margin-left: 8px;"
                            type="search" placeholder="Tìm kiếm...">
                    </div>
                </div>
                <div class="card b-radius--10">
                    <div class="card-body p-0">
                        <div class="table-responsive--sm table-responsive">
                            <table class="table--light style--two table" id="data-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>@lang('Mã hàng')</th>
                                        <th>@lang('Ảnh')</th>
                                        <th>@lang('Tên sản phẩm')</th>
                                        <th>@lang('Giá bán')</th>
                                        <th>@lang('Giá nhập')</th>
                                        <th>@lang('Tồn kho')</th>
                                        @can([])
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
            <a class="btn btn-sm btn-outline--primary" href="{{ route('admin.product.create') }}"><i
                    class="las la-plus"></i>@lang('Thêm mới')</a>
        @endpush
    @endcan
@endsection


@push('script')
    <script src="{{ asset('assets/admin/js/dataTable.js') }}"></script>
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
                                    row.remove();
                                    $(`#rep-${productId}`).remove();
                                    notData();
                                    showSwalMessage('success', response.message);
                                },
                                error: function(xhr) {
                                    showSwalMessage('error', xhr.responseJSON
                                        .message);
                                }
                            })
                        }
                    })

                });
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
    </script>
@endpush

@push('style')
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>

    <style>
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
    </style>
@endpush

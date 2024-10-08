@extends('admin.layouts.app')
@section('panel')
    @include('admin.messages')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive p-2">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="dt-length">
                                <select name="example_length" style=" padding: 1px 3px; margin-right: 8px;"
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
                        <table class="table--light style--two table table-hover" id="data-table">
                            <thead>
                                <tr>
                                    <th>Mã phiếu</th>
                                    <th>Nhà Cung Cấp</th>
                                    <th>Số điện thoại</th>
                                    <th>Ngày tạo</th>
                                    <th>Trạng thái</th>
                                    <th>Tổng tiền</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('')
        @push('breadcrumb-plugins')
            <a class="btn btn-sm btn-outline--primary" href="{{ route('admin.warehouse.create') }}"><i
                    class="las la-plus"></i>@lang('Thêm mới')</a>
        @endpush
    @endcan
@endsection


@push('script')
    <script src="{{ asset('assets/admin/js/dataTable.js') }}"></script>
    <script>
        (function($) {
            "use strict"

            $(document).ready(function() {
                const apiUrl = '{{ route('admin.warehouse.index') }}';
                initDataFetch(apiUrl);

            });


        })(jQuery);
    </script>
@endpush

@push('style')
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>

    <style>
        @media (min-width: 768px) {
            .form-switch .form-check-input {
                float: right
            }
        }

        @media (min-width: 992px) {
            .form-switch .form-check-input {
                margin-left: 50%;
                transform: translateX(-50%);
                float: none;
            }
        }

        .tooltip1 {
            position: relative;
            display: inline-block;
        }

        .tooltip1 .tooltiptext {
            font-size: 8px;
            visibility: hidden;
            width: 150px;
            background-color: black;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            padding: 5px;
            position: absolute;
            z-index: 100;
            bottom: 125%;
            /* Vị trí tooltip */
            left: 50%;
            /* margin-left: -75px; */
            /* Để căn giữa */
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip1:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
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

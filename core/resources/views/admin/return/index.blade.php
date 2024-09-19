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
                                        <th>@lang('Phiếu trả hàng')</th>
                                        <th>@lang('Mã đơn hàng')</th>
                                        <th>@lang('Số sản phẩm bị hoàn trả')</th>
                                        <th>@lang('Tổng số lượng hoàn trả')</th>
                                        <th>@lang('Tổng tiền')</th>
                                        <th>@lang('Thời gian thực hiện')</th>
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
            <a class="btn btn-sm btn-outline--primary" href="{{ route('admin.return.create') }}"><i
                    class="las la-plus"></i>@lang('Thao tác')</a>
        @endpush
    @endcan
@endsection


@push('script')
    <script src="{{ asset('assets/admin/js/dataTable.js') }}"></script>
    <script>
        (function($) {
            "use strict"

            $(document).ready(function() {
                const apiUrl = '{{ route('admin.return.index') }}';
                initDataFetch(apiUrl);




            });


        })(jQuery);
    </script>
@endpush

@push('style')
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>


@endpush

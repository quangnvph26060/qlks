@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between mb-3 row">
                <div class="dt-length col-md-6">
                    <select name="example_length" id="perPage" style=" padding: 1px 3px; margin-right: 8px;"
                        aria-controls="example" class="perPage">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select><label for="perPage"> entries per page</label>
                </div>
                <div class="search col-md-4" style="text-align: end;">
                    <label for="searchInput">Search:</label>
                    <input class="searchInput"
                       
                        type="search" placeholder="Tìm kiếm...">
                </div>
                <div class="dropdown col-md-2" style="display: flex;
                justify-content: end">
                    <a class="btn btn-outline-secondary dropdown-toggle d-flex justify-content-center
    align-items-center" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Thao tác
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item"
                                href="{{ route('admin.hotel.room.type.all.deleted') }}">Các phòng đã xóa</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table table-striped" id="data-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    {{-- <th>@lang('STT')</th> --}}
                                    <th>@lang('Loại phòng')</th>
                                    <th>@lang('Tên phòng')</th>
                                    <th>@lang('Mã phòng')</th>
                                    <th>@lang('Tiện nghi')</th>
                                    <th>@lang('Cở sở vật chất')</th>
                                    <th>@lang('Giá giờ')</th>
                                    <th>@lang('Giá ngày')</th>
                                    <th>@lang('Giá đêm')</th>
                                    <th>@lang('Trạng thái')</th>
                                    @can(['admin.hotel.room.type.edit', 'admin.hotel.room.type.status',
                                        'admin.hotel.room.type.destroy'])
                                        <th>@lang('Hành động')</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>


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
        <a class="btn btn-sm btn-outline--primary" href="{{ route('admin.hotel.room.type.create') }}"><i
                class="las la-plus"></i>@lang('Thêm mới')</a>
    @endpush
@endcan

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
        })
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

        .table-striped tbody tr:nth-child(odd) {
            background-color: #fff;
        }

        .table th,
        td {
            text-align: unset !important;
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
        .searchInput{
            padding: 1px 3px;
            border: 1px solid rgb(121, 117, 117, 0.5);
            margin-left: 8px;
        }
    </style>
@endpush

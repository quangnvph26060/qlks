@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <!-- Khối bên phải: Danh sách danh mục -->
        <div class="col-md-12">
            <div class="border p-2">
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
                                        <th>@lang('STT')</th>
                                        <th>@lang('Mã giá')</th>
                                        <th>@lang('Tên loại giá')</th>
                                        <th>@lang('Giá trị')</th>
                                        <th>@lang('Thời gian bắt đầu')</th>
                                        <th>@lang('Thời gian kết thúc')</th>
                                        <th>@lang('Trạng thái')</th>
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
            <button type="button" class="btn btn-outline--primary btn-add">
                Thêm mới
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
                    <form id="priceForm" method="POST" action="">
                        <input type="hidden" name="_method" id="method" value="POST">
                        <input type="hidden" name="id" id="recordId">
                        <div class="row">
                            <div class="form-group mb-3 col-lg-6">
                                <label for="">Mã giá</label>
                                <input type="text" class="form-control" id="code" name="code"
                                    placeholder="Mã giá">
                            </div>
                            <div class="form-group mb-3 col-lg-6">
                                <label for="">Tên loại giá</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Nhập loại giá">
                            </div>

                            <div class="form-group mb-3 col-lg-6">
                                <label for="">Ngày bắt đầu</label>

                                <input type="date" name="start_date" class="form-control"

                                    placeholder="Chọn ngày và giờ" />
                            </div>

                            <div class="form-group mb-3 col-lg-6">
                                <label for="">Ngày kết thúc</label>

                                <input type="date" name="end_date" class="form-control"
                                    placeholder="Chọn ngày và giờ" />
                            </div>
                            <div class="form-group mb-3 col-lg-6">
                                <label for="">Thời gian bắt đầu</label>
                                <input type="time" name="start_time" class="form-control"
                                    placeholder="Chọn ngày và giờ" />
                            </div>

                            <div class="form-group mb-3 col-lg-6">
                                <label for="">Thời gian kết thúc</label>
                                <input type="time" name="end_time" class="form-control"
                                    placeholder="Chọn ngày và giờ" />
                            </div>
                            <div class="form-group mb-3 col-lg-6">
                                <label for="">Ngày đặc biệt</label>
                                <input type="date" name="specific_date" class="form-control"

                                    placeholder="Chọn ngày và giờ" />
                            </div>
                            <div class="form-group mb-3 col-lg-12">
                                <label for="">Giá trị</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="price" name="price"
                                        placeholder="Nhập giá">
                                    <span class="input-group-text">{{ __(gs()->cur_text) }}</span>
                                </div>
                            </div>

                            <div class="form-group mb-3 col-lg-12">
                                <div class="radio-container" style="justify-content: left; gap: 10px">
                                    <label for="">Trạng thái</label>
                                    <label class="toggle">
                                        <input type="checkbox" name="status" id="status" checked>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Thực hiện</button>
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

                // function formatFee(input) {
                //     let value = input.value.replace(/[^0-9]/g, '');
                //     value = new Intl.NumberFormat('vi-VN').format(value);
                //     input.value = value;
                // }

                // document.getElementById('price').addEventListener('input', function() {
                //     formatFee(this);
                // });

                const apiUrl = '{{ route('admin.manage.price.all') }}';
                initDataFetch(apiUrl);

                $("#priceForm").on('submit', function(e) {
                    e.preventDefault();



                    const method = $('#method').val();
                    const url = method === 'PUT' ? "{{ route('admin.manage.price.update', ':id') }}"
                        .replace(
                            ':id', $(
                                '#recordId').val()) : "{{ route('admin.manage.price.store') }}";

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
                        url: "{{ route('admin.manage.price.edit', ':id') }}".replace(':id', id),
                        success: function(response) {

                            if (response.status) {
                                $('#staticBackdropLabel').text('Cập nhật');
                                $('#code').val(response.data.code);
                                $('#name').val(response.data.name);

                                // Thêm giờ vào ngày
                                let startDate = response.data.start_date +
                                'T00:00'; // hoặc giờ thực tế
                                let endDate = response.data.end_date +
                                'T23:59'; // hoặc giờ thực tế

                                $('#start_date').val(startDate);
                                $('#end_date').val(endDate);

                                $('#price').val(response.data.price);
                                $('#status').prop('checked', response.data.status ==
                                    'active');
                                $('#method').val('PUT');
                                $('#recordId').val(id);
                                $('#staticBackdrop').modal('show');
                            }
                        }
                    });
                });

                $(document).on('click', '.btn-add', function() {
                    $('#priceForm')[0].reset();
                    $('#method').val('POST'); // Đặt phương thức thành POST
                    $('#staticBackdropLabel').text(
                        'Thêm mới'); // Đặt lại tiêu đề là "Thêm mới"
                    $('#staticBackdrop').modal('show');
                });


                $(document).on('click', '.btn-delete', function() {
                    let id = $(this).data('id');

                    Swal.fire({
                        title: 'Xóa vĩnh viễn',
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
                                url: "{{ route('admin.manage.price.destroy', ':id') }}"
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

                $(document).on('change', '.status-change', function() {
                    let checkbox = $(this);
                    let id = checkbox.data('id');


                    let isChecked = checkbox.is(':checked');

                    Swal.fire({
                        title: 'Thay đổi trạng thái',
                        text: 'Bạn có chắc chắn thay đổi trạng thái?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Đồng ý',
                        cancelButtonText: 'Huỷ'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "PUT",
                                url: "{{ route('admin.manage.price.updateStatus', ':id') }}"
                                    .replace(':id', id),
                                success: function(response) {
                                    if (response.status) {
                                        showSwalMessage('success', response
                                            .message);
                                        initDataFetch(apiUrl);
                                    } else {
                                        checkbox.prop('checked', !
                                            isChecked);
                                        showSwalMessage('error', response
                                            .message);
                                    }
                                }
                            });
                        } else {
                            checkbox.prop('checked', !
                                isChecked);
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
            /* Màu xanh cho Hoạt động */
        }

        .radio-group label {
            margin-right: 20px;
            font-size: 16px;
        }
    </style>
@endpush

@push('style-lib')
@endpush

@push('script-lib')
@endpush

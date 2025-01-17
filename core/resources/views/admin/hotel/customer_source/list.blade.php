@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-12">

            <div class="row gy-4">
                <div class="col-12">
                    <div class="emptyArea"></div>
                </div>
                <div class="table-responsive--md table-responsive">
                    <table class="table--light style--two table">
                        <thead>
                        <tr>
                            <th>@lang('Mã nguồn')</th>
                            <th>@lang('Tên nguồn')</th>
                            <th>@lang('Mã đơn vị')</th>
                            <th>Hành động</th>
                        </tr>
                        </thead>
                        <tbody id="main-table-hotel">
                        @forelse($customer_sources as $key => $item)
                            <tr data-id="{{ $item->id }}">
                                <td>
                                    {{ $item->source_code }}
                                </td>

                                <td>
                                    {{ $item->source_name }}
                                </td>
                                <td>
                                    {{ $item->unit_code }}
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-outline--primary btn-edit-source"
                                       data-id="{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#edit-customer-source">
                                        <i class="la la-pencil"></i>@lang('Sửa')
                                    </a>
                                    <button class="btn btn-sm btn-outline--danger btn-delete icon-delete-room"
                                            data-id="{{ $item->id }}" data-modal_title="@lang('Xóa nguồn khách hàng')"type="button"
                                            data-pro="0">
                                        <i class="fas fa-trash"></i>@lang('Xóa')
                                    </button>
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
    @can('')
        @push('breadcrumb-plugins')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group position-relative mb-0" style="float: inline-end;" id="btn-add-source">
                            <button class="btn btn-sm btn-outline--primary" data-modal_title="Thêm mới nguồn khách hàng" type="button"
                                    data-bs-toggle="modal" data-bs-target="#customer-source">
                                <i class="las la-plus"></i>Thêm mới
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endpush
    @endcan

    <div class="modal fade" id="customer-source" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Thêm mới nguồn khách hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addSource" method="POST" action="{{ route('admin.hotel.customer.source.store') }}">
                        {!! csrf_field() !!}
                        {{ method_field('POST') }}
                        <div class="row">
                            <div class="mb-3">
                                <label for="statusCode" class="form-label">Mã nguồn</label>
                                <input type="text" class="form-control " name="source_code" id="source_code"
                                       placeholder="Nhập mã nguồn">
                                <span class="invalid-feedback d-block" style="font-weight: 500"
                                      id="source_code_error"></span>
                            </div>
                            <!-- Input 2 -->
                            <div class="mb-3">
                                <label for="statusName" class="form-label">Tên nguồn</label>
                                <input type="text" class="form-control " name="source_name" id="source_name"
                                       placeholder="Nhập tên nguồn">
                                <span class="invalid-feedback d-block" style="font-weight: 500"
                                      id="source_name_error"></span>
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Mã đơn vị </label>
                                <select name="unit_code" id="unit-code-multiple-choice" class="form-control">
                                    <option value="" selected>--Chọn mã đơn vị--</option>
                                    @foreach($unit_codes as $item)
                                        <option value="{{ $item->ma_coso }}">{{ $item->ma_coso }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                <button type="submit" id="btn-add-source" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="edit-customer-source" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Cập nhật</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editSource" method="POST" action="">
                        {!! csrf_field() !!}
                        <input type="hidden" id="method" name="_method" value="">
                        <div class="row">
                            <div class="mb-3">
                                <label for="statusCode" class="form-label">Mã nguồn</label>
                                <input type="text" class="form-control " name="source_code" id="edit-source-code"
                                       placeholder="Nhập mã nguồn">
                                <span class="invalid-feedback d-block" style="font-weight: 500"
                                      id="source_code_error"></span>
                            </div>
                            <!-- Input 2 -->
                            <div class="mb-3">
                                <label for="statusName" class="form-label">Tên nguồn</label>
                                <input type="text" class="form-control " name="source_name" id="edit-source-name"
                                       placeholder="Nhập tên nguồn">
                                <span class="invalid-feedback d-block" style="font-weight: 500"
                                      id="source_name_error"></span>
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Mã đơn vị </label>
                                <select name="unit_code" id="edit-unit-code" class="form-control">
                                    <option value="" selected>--Chọn mã đơn vị--</option>
                                    @foreach($unit_codes as $item)
                                        <option value="{{ $item->ma_coso }}">{{ $item->ma_coso }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


@endsection

@push('script')
    <script src="{{ asset('assets/admin/js/highlighter22.js') }}"></script>
    <script src="{{ asset('assets/validator/validator.js') }}"></script>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            var formEconomyEdit = {
                'source_code': {
                    'element': document.getElementById('source_code'),
                    'error': document.getElementById('source_code_error'),
                    'validations': [{
                        'func': function(value) {
                            return checkRequired(value); // check trống
                        },
                        'message': generateErrorMessage('MN001')
                    },
                    ]
                },
                'source_name': {
                    'element': document.getElementById('source_name'), // id trong input đó
                    'error': document.getElementById('source_name_error'), // thẻ hiển thị lỗi
                    'validations': [{
                        'func': function(value) {
                            return checkRequired(value);
                        },
                        'message': generateErrorMessage('TN001')
                    },
                    ]
                },
            }
            $(document).on('click', '#click-btn-customer-source', function() {
                if (validateAllFields(formEconomyEdit)) {
                    document.getElementById('btn-submit-source').submit(); // là id trong form
                }
            });
            $(document).on('click', '#click-btn-source-update', function() {
                if (validateAllFields(formEconomyEdit)) {
                    document.getElementById('btn-submit-source-update').submit(); // là id trong form
                }
            });
            $('#btn-add-source').on('click', function() {
                formEconomyEdit.source_code.element = document.getElementById('source_code');
                formEconomyEdit.source_code.error = document.getElementById('source_code_error');
                formEconomyEdit.source_name.element = document.getElementById('source_name');
                formEconomyEdit.source_name.error = document.getElementById('source_name_error');
            });
            $('.btn-edit-source').on('click', function() {
                var dataId = $(this).data('id');
                $.ajax({
                    url: `{{ route('admin.hotel.customer.source.edit', '') }}/${dataId}`,
                    type: 'GET',
                    success: function(data) {
                            $('#edit-source-code').val(data.source_code);
                            $('#edit-source-name').val(data.source_name);
                            $('#edit-unit-code').val(data.unit_code).change();
                            $('#method').attr('value', 'PUT');
                            $('#editSource').attr('action', '{{ route('admin.hotel.customer.source.update', '') }}/' + dataId + '')
                            formEconomyEdit.source_code.element = document.getElementById('source_code');
                            formEconomyEdit.source_code.error = document.getElementById('source_code_error');
                            formEconomyEdit.source_name.element = document.getElementById('source_name');
                            formEconomyEdit.source_name.error = document.getElementById('source_name_error');
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
                    title: 'Xác nhận xóa nguồn khách hàng?',
                    text: 'Bạn có chắc chắn muốn xóa nguồn khách hàng này không?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy bỏ',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ajax
                        $.ajax({
                            url: `{{ route('admin.hotel.customer.source.delete', '') }}/${dataId}`,
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

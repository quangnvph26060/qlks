@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-12">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group position-relative mb-0" id="btn-add-group">
                            <button class="btn btn-sm btn-outline--primary" data-modal_title="Thêm mới nhóm khách hàng" type="button"
                                    data-bs-toggle="modal" data-bs-target="#group-code">
                                <i class="las la-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="group-code" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" id="modal-dialog">
                </div>
            </div>
            <div class="modal fade" id="edit-customer-group" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Cập nhật khách hàng</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editCustomer" method="POST" action="">
                                {!! csrf_field() !!}
                                <input type="hidden" id="method" name="_method" value="">
                                <div class="row">
                                    <div style="display: grid; grid-template-columns: 50% 50%">
                                        <div class="mb-3">
                                            <label for="statusCode" class="form-label">Mã khách hàng</label>
                                            <input type="text" class="form-control " name="customer_code" id="edit-customer-code"
                                                placeholder="Nhập mã khách hàng" style="width:98%">
                                            <span class="invalid-feedback d-block" style="font-weight: 500"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label for="statusName" class="form-label">Tên khách hàng</label>
                                            <input type="text" class="form-control " name="name" id="edit-name"
                                                placeholder="Nhập tên khách hàng" s>
                                            <span class="invalid-feedback d-block" style="font-weight: 500"></span>
                                        </div>
                                    </div>
                                   
                                
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                        <button type="submit" id="btn-edit-customer" class="btn btn-primary">Lưu</button>
                                    </div>
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
                <div class="table-responsive--md table-responsive">
                    <table class="table--light style--two table">
                        <thead>
                        <tr>
                            <th>@lang('Mã nhóm KH')</th>
                            <th>@lang('Tên nhóm KH')</th>
                            <!-- <th>@lang('Ghi chú')</th>
                            <th>@lang('Trạng thái')</th> -->
                            <th>Hành động</th>
                        </tr>
                        </thead>
                        <tbody id="main-table-hotel">
                        @forelse($customer_groups as $key => $item)
                            <tr data-id="{{ $item->id }}">
                                <td>
                                    {{ $item->group_code }}
                                </td>
                                <td>
                                    {{ $item->group_name }}
                                </td>
                        
                                <td>
                                    {{-- href="{{ route('admin.setting.setup.edit.hotel', $item->id) }}" --}}
                                    <a class="btn btn-sm btn-outline--primary btn-edit-group"
                                       data-id="{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#edit-customer-group">
                                        <i class="la la-pencil"></i>
                                    </a>
                        
                                    <button class="btn btn-sm btn-outline--danger btn-delete icon-delete-room"
                                            data-id="{{ $item->id }}" data-modal_title="@lang('Xóa nhóm KH')"type="button"
                                            data-pro="0">
                                        <i class="fas fa-trash"></i>
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
@endsection

@push('script')
    <script src="{{ asset('assets/admin/js/highlighter22.js') }}"></script>
    <script src="{{ asset('assets/validator/validator.js') }}"></script>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            var formEconomyEdit = {
                'group_code': {
                    'element': document.getElementById('group_code'),
                    'error': document.getElementById('group_code_error'),
                    'validations': [{
                        'func': function(value) {
                            return checkRequired(value); // check trống
                        },
                        'message': generateErrorMessage('MNH001')
                    },
                    ]
                },
                'group_name': {
                    'element': document.getElementById('group_name'), // id trong input đó
                    'error': document.getElementById('group_name_error'), // thẻ hiển thị lỗi
                    'validations': [{
                        'func': function(value) {
                            return checkRequired(value);
                        },
                        'message': generateErrorMessage('TNH001')
                    },
                    ]
                },
            }
            $(document).on('click', '#click-btn-customer-group', function() {
                if (validateAllFields(formEconomyEdit)) {
                    document.getElementById('btn-submit-group').submit(); // là id trong form
                }
            });
            $(document).on('click', '#click-btn-group-update', function() {
                if (validateAllFields(formEconomyEdit)) {
                    document.getElementById('btn-submit-group-update').submit(); // là id trong form
                }
            });
            // add
            $('#btn-add-group').on('click', function() {
                $('#modal-dialog').empty();
                let row = '';
                row += `
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Thêm mới nhóm khách hàng</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="btn-submit-group" action="{{ route('admin.hotel.customer.group.store') }}"
                                    method="POST">
                                    @csrf
                <!-- Input 1 -->
                <div class="mb-3">
                    <label for="groupCode" class="form-label">Mã nhóm</label>
                    <input type="text" class="form-control" name="group_code" id="group_code"
                        placeholder="Nhập mã nhóm">
                    <span class="invalid-feedback d-block" style="font-weight: 500"
                        id="group_code_error"></span>
                </div>
                <!-- Input 2 -->
                <div class="mb-3">
                    <label for="groupName" class="form-label">Tên nhóm</label>
                    <input type="text" class="form-control " name="group_name" id="group_name"
                        placeholder="Nhập tên trạng thái">
                    <span class="invalid-feedback d-block" style="font-weight: 500"
                        id="group_name_error"></span>
                </div>
        
           
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="click-btn-customer-group">Lưu</button>
                </div>
            </form>
        </div>
    </div>
`;
                $('#modal-dialog').append(row);

                formEconomyEdit.group_code.element = document.getElementById('group_code');
                formEconomyEdit.group_code.error = document.getElementById('group_code_error');
                formEconomyEdit.group_name.element = document.getElementById('group_name');
                formEconomyEdit.group_name.error = document.getElementById('group_name_error');
            });
            // sửa
            $('.btn-edit-group').on('click', function() {
                var dataId = $(this).data('id');
=                // ajax request
                $.ajax({
                    url: `{{ route('admin.hotel.customer.group.edit', '') }}/${dataId}`,
                    type: 'GET',
                    success: function(data) {
                        if (data.group === 'success') {
                            let rowEdit = '';
                            $('#modal-dialog').empty();
                            rowEdit += `
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Chỉnh sửa nhóm khách hàng
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="btn-submit-group-update" action="{{ route('admin.hotel.customer.group.update', '') }}/${data.data['id']}"
                                        method="POST">
                                        @csrf
                            <!-- Input 1 -->
                            <div class="mb-3">
                                <label for="groupCode" class="form-label">Mã nhóm</label>
                                <input type="text" class="form-control " name="group_code" id="group_code"
                                    placeholder="Nhập mã nhóm" value="${data.data['group_code']}">
                                            <span class="invalid-feedback d-block" style="font-weight: 500"
                                                id="group_code_error"></span>
                                        </div>
                                        <!-- Input 2 -->
                                        <div class="mb-3">
                                            <label for="groupName" class="form-label">Tên nhóm</label>
                                            <input type="text" class="form-control " name="group_name" id="group_name"
                                                placeholder="Nhập tên nhóm" value="${data.data['group_name']}">
                                            <span class="invalid-feedback d-block" style="font-weight: 500"
                                                id="group_name_error"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label for="note" class="form-label">Ghi chú</label>
                                            <textarea type="text" class="form-control " name="note" >${data.data['note']}</textarea>

                                        </div>
                                

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" id="click-btn-group-update">Lưu</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            `;
                            $('#modal-dialog').append(rowEdit);
                            formEconomyEdit.group_code.element = document.getElementById('group_code');
                            formEconomyEdit.group_code.error = document.getElementById('group_code_error');
                            formEconomyEdit.group_name.element = document.getElementById('group_name');
                            formEconomyEdit.group_name.error = document.getElementById('group_name_error');
                        }
                    },
                    error: function(xhr, group, error) {
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
                    text: 'Bạn có chắc chắn muốn xóa trạng thái này không?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy bỏ',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ajax
                        $.ajax({
                            url: `{{ route('admin.hotel.customer.group.delete', '') }}/${dataId}`,
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
            // chỉnh sửa trạng thái
            $('.confirmationBtn').on('click', function(){
                var action =  $(this).data('action');
                var dataId = $(this).data('id');
                // ajax request
                $.ajax({
                    url: action,
                    type: 'POST',
                    success: function(data) {
                        if (data.status ==='success') {
                            let statusCell = $(`tr[data-id="${dataId}"] .group-hotel`);
                            statusCell.html(data.status_html);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });

            });
            $('input[name="hotelGroup"]').on('change', function() {
                const selectedStatus = $('input[name="hotelGroup"]:checked').val();
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

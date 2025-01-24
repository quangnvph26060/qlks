@extends('admin.layouts.master_iframe')
@section('panel')
    <div class="row">
        <div class="col-12">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group position-relative mb-0" id="btn-add-status">
                            <button class="btn btn-sm btn-outline--primary" data-modal_title="Thêm mới trạng thái chức năng" type="button"
                                    data-bs-toggle="modal" data-bs-target="#status-code">
                                <i class="las la-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="status-code" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" id="modal-dialog">
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
                            <th>@lang('Mã trạng thái')</th>
                            <th>@lang('Tên trạng thái')</th>
                            <th>@lang('Ghi chú')</th>
                            <th>@lang('Trạng thái')</th>
                            <th>Hành động</th>
                        </tr>
                        </thead>
                        <tbody id="main-table-hotel">
                        @forelse($status_codes as $key => $item)
                            <tr data-id="{{ $item->id }}">
                                <td>
                                    {{ $item->status_code }}
                                </td>
                                <td>
                                    {{ $item->status_name }}
                                </td>
                                <td>
                                    {{ $item->note }}
                                </td>
                                <td class="status-hotel">
                                    {!! $item->styleStatus() !!}
                                </td>
                                <td>
                                    {{-- href="{{ route('admin.setting.setup.edit.hotel', $item->id) }}" --}}
                                    <a class="btn btn-sm btn-outline--primary btn-edit-status"
                                       data-id="{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#status-code">
                                        <i class="la la-pencil"></i>
                                    </a>
                                    @if($item->status_status == 1)

                                        <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                                data-action="{{ route('admin.hotel.status.code.status', $item->id) }}"   data-id="{{ $item->id }}"
                                                data-question="@lang('Bạn có chắc chắn muốn tắt trạng thái này không?')" type="button">
                                            <i class="la la-eye-slash"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-outline--success confirmationBtn"
                                                data-action="{{ route('admin.hotel.status.code.status', $item->id) }}"   data-id="{{ $item->id }}"
                                                data-question="@lang('Bạn có chắc chắn muốn tắt trạng thái này không?')" type="button">
                                            <i class="la la-eye-slash"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-sm btn-outline--danger btn-delete icon-delete-room"
                                            data-id="{{ $item->id }}" data-modal_title="@lang('Xóa trạng thái')"type="button"
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
                'status_code': {
                    'element': document.getElementById('status_code'),
                    'error': document.getElementById('status_code_error'),
                    'validations': [{
                        'func': function(value) {
                            return checkRequired(value); // check trống
                        },
                        'message': generateErrorMessage('MS001')
                    },
                    ]
                },
                'status_name': {
                    'element': document.getElementById('status_name'), // id trong input đó
                    'error': document.getElementById('status_name_error'), // thẻ hiển thị lỗi
                    'validations': [{
                        'func': function(value) {
                            return checkRequired(value);
                        },
                        'message': generateErrorMessage('TTT001')
                    },
                    ]
                },
            }
            $(document).on('click', '#click-btn-status-code', function() {
                if (validateAllFields(formEconomyEdit)) {
                    document.getElementById('btn-submit-status').submit(); // là id trong form
                }
            });
            $(document).on('click', '#click-btn-status-update', function() {
                if (validateAllFields(formEconomyEdit)) {
                    document.getElementById('btn-submit-status-update').submit(); // là id trong form
                }
            });
            // add
            $('#btn-add-status').on('click', function() {
                $('#modal-dialog').empty();
                let row = '';
                row += `
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Thêm mới trạng thái chức năng</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="btn-submit-status" action="{{ route('admin.hotel.status.code.store') }}"
                                    method="POST">
                                    @csrf
                <!-- Input 1 -->
                <div class="mb-3">
                    <label for="statusCode" class="form-label">Mã trạng thái</label>
                    <input type="text" class="form-control " name="status_code" id="status_code"
                        placeholder="Nhập mã trạng thái">
                    <span class="invalid-feedback d-block" style="font-weight: 500"
                        id="status_code_error"></span>
                </div>
                <!-- Input 2 -->
                <div class="mb-3">
                    <label for="statusName" class="form-label">Tên trạng thái</label>
                    <input type="text" class="form-control " name="status_name" id="status_name"
                        placeholder="Nhập tên trạng thái">
                    <span class="invalid-feedback d-block" style="font-weight: 500"
                        id="status_name_error"></span>
                </div>
                     <div class="mb-3">
                    <label for="note" class="form-label">Ghi chú</label>
                    <textarea type="text" class="form-control " name="note"></textarea>

                </div>
                <div class="mb-3">
                    <label for="statusStatus" class="form-label">Trạng thái</label><br>
                    <input type="radio" name="status_status" value="1" id="statusActive">Hoạt động
                    <input type="radio" name="status_status" value="0" id="statusInactive" checked>
                    Không hoạt động
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="click-btn-status-code">Lưu</button>
                </div>
            </form>
        </div>
    </div>
`;
                $('#modal-dialog').append(row);

                formEconomyEdit.status_code.element = document.getElementById('status_code');
                formEconomyEdit.status_code.error = document.getElementById('status_code_error');
                formEconomyEdit.status_name.element = document.getElementById('status_name');
                formEconomyEdit.status_name.error = document.getElementById('status_name_error');
            });
            // sửa
            $('.btn-edit-status').on('click', function() {
                var dataId = $(this).data('id');
                // ajax request
                $.ajax({
                    url: `{{ route('admin.hotel.status.code.edit', '') }}/${dataId}`,
                    type: 'GET',
                    success: function(data) {
                        if (data.status === 'success') {
                            let rowEdit = '';
                            $('#modal-dialog').empty();
                            rowEdit += `
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Chỉnh sửa trạng thái chức năng
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="btn-submit-status-update" action="{{ route('admin.hotel.status.code.update', '') }}/${data.data['id']}"
                                        method="POST">
                                        @csrf
                            <!-- Input 1 -->
                            <div class="mb-3">
                                <label for="statusCode" class="form-label">Mã trạng thái</label>
                                <input type="text" class="form-control " name="status_code" id="status_code"
                                    placeholder="Nhập mã trạng thái" value="${data.data['status_code']}">
                                            <span class="invalid-feedback d-block" style="font-weight: 500"
                                                id="status_code_error"></span>
                                        </div>
                                        <!-- Input 2 -->
                                        <div class="mb-3">
                                            <label for="statusName" class="form-label">Tên trạng thái</label>
                                            <input type="text" class="form-control " name="status_name" id="status_name"
                                                placeholder="Nhập tên trạng thái" value="${data.data['status_name']}">
                                            <span class="invalid-feedback d-block" style="font-weight: 500"
                                                id="status_name_error"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label for="note" class="form-label">Ghi chú</label>
                                            <textarea type="text" class="form-control " name="note" >${data.data['note']}</textarea>

                                        </div>
                                        <div class="mb-3">
                                            <label for="hotelStatus" class="form-label">Trạng thái</label><br>
                                            <input type="radio" name="status_status" value="1" id="statusActive" ${data.data['status_status'] == 1 ? 'checked' : ''}> Hoạt động
                                            <input type="radio" name="status_status" value="0" id="statusInactive" ${data.data['status_status'] == 0 ? 'checked' : ''}> Không hoạt động
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" id="click-btn-status-update">Lưu</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            `;
                            $('#modal-dialog').append(rowEdit);
                            formEconomyEdit.status_code.element = document.getElementById('status_code');
                            formEconomyEdit.status_code.error = document.getElementById('status_code_error');
                            formEconomyEdit.status_name.element = document.getElementById('status_name');
                            formEconomyEdit.status_name.error = document.getElementById('status_name_error');
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
                            url: `{{ route('admin.hotel.status.code.delete', '') }}/${dataId}`,
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
                            let statusCell = $(`tr[data-id="${dataId}"] .status-hotel`);
                            statusCell.html(data.status_html);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });

            });
            $('input[name="hotelStatus"]').on('change', function() {
                const selectedStatus = $('input[name="hotelStatus"]:checked').val();
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

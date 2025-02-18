@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-12">

            <div class="card-body">
                <div class="row">
                <div class="col-md-12 col-sm-12 d-flex">
                        <a class="mr-1" href="{{route('admin.hotel.status.code.all')}}">
                        <button class="btn btn-sm btn-outline--primary" data-modal_title="Làm mới">
                            <i class="fa fa-repeat p-2"></i>
                        </button>
                         </a>
                            <button class="btn btn-sm btn-outline--primary"  data-modal_title="Thêm mới trạng thái chức năng"  type="button"
                                    data-bs-toggle="modal" data-bs-target="##status-code"  style="margin-left:10px">
                                <i class="las la-plus  p-2"></i>
                            </button>
                            <form role="form" enctype="multipart/form-data" action="{{route('admin.hotel.status.code.search')}}">
                                <div class="form-group position-relative mb-0">
                                    <input class="searchInput" name="status_code"
                                        style="height: 35px;border: 1px solid rgb(121, 117, 117, 0.5);margin-left: 8px;"
                                            placeholder="Mã trạng thái">
                                    <input class="searchInput" name="status_name"
                                        style="height: 35px;border: 1px solid rgb(121, 117, 117, 0.5); margin-left: 8px;"
                                        placeholder="Tên trạng thái">
                                    <button type="submit" class="btn btn-primary" style="padding-right:15px;padding-left:15px">
                                        <i class="las la-search"></i>
                                    </button>

                                </div>
                            </form>
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
                <div class="table-responsive--md table-responsive" style="overflow-x: visible;">
                    <table class="table--light style--two table">
                        <thead>
                        <tr>
                            <th>Hành động</th>
                            <th>STT</th>
                            <th>@lang('Mã trạng thái')</th>
                            <th>@lang('Tên trạng thái')</th>
                            <th>@lang('Ghi chú')</th>
                            <th>@lang('Trạng thái')</th>
                          
                        </tr>
                        </thead>
                        <tbody id="main-table-hotel">
                        @forelse($status_codes as $key => $item)
                            <tr data-id="{{ $item->id }}">
                            <td style="width:20px;">
                                    <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"/><circle cx="10.5" cy="5.5" r="1"/><circle cx="10.5" cy="15.5" r="1"/></g></svg>
                                        <div class="dropdown menu_dropdown_check_in" id="dropdown-menu">
                                            <div class="dropdown-item"><a
                                            data-id="{{ $item->id }}" class="btn-edit-status" data-bs-toggle="modal" data-bs-target="#status-code" style="color:black">
                                                Sửa trạng thái
                                            </a>
                                        </div>
                                          
                                         <div class="dropdown-item booked_room_detail"> <button class=" btn-delete icon-delete-room"
                                                data-id="{{ $item->id }}" data-modal_title="@lang('Xóa trạng thái')" type="button"
                                                data-pro="0">Xóa trạng thái</div>
                              
                                        </div>
                                </td>
                                <td style="width:20px;text-align:right">{{ $loop->iteration }}</td>
                                <td>
                                    {{ $item->status_code }}
                                </td>
                                <td>
                                    {{ $item->status_name }}
                                </td>
                                <td>
                                    {{ $item->note }}
                                </td>
                             
                                <td style="width:50px;text-align: center" class="status-hotel">
                                        @if($item->status_status == 1)
                                            <i class="fa fa-check" style="color:green;text-align: center"></i>
                                        @else
                                            <i class="fa fa-close" style="color:red;text-align: center"></i>
                                        @endif
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
            .navbar__right{
                display: none;
            }
            #navbar-wrapper{
                padding: 0px 30px 20px;
            }
    </style>
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

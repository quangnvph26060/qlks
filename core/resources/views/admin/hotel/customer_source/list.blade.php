@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="emptyArea"></div>
                </div>
                <div class="table-responsive--md table-responsive">
                    <table class="table--light style--two table">
                        <thead>
                        <tr>
                            <th>Hành động</th>
                            <th>@lang('STT')</th>
                            <th>@lang('Mã nguồn')</th>
                            <th>@lang('Tên nguồn')</th>
                            <!-- <th>@lang('Mã đơn vị')</th> -->
                        </tr>
                        </thead>
                        <tbody id="main-table-hotel">
                        @forelse($customer_sources as $key => $item)
                            <tr data-id="{{ $item->id }}">
                            <td style="width:20px;">
                                    <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"/><circle cx="10.5" cy="5.5" r="1"/><circle cx="10.5" cy="15.5" r="1"/></g></svg>
                            
                                    <div class="dropdown menu_dropdown_check_in" id="dropdown-menu">
                                            <div class="dropdown-item"><a
                                            data-id="{{ $item->id }}" class="btn-edit-source" data-bs-toggle="modal" data-bs-target="#edit-customer-source" style="color:black">
                                                Sửa khách hàng
                                            </a>
                                    </div>
                                          
                                    <div class="dropdown-item booked_room_detail"> <button class=" btn-delete icon-delete-room"
                                                data-id="{{ $item->id }}" data-modal_title="@lang('Xóa nguồn khách')" type="button"
                                                data-pro="0">Xóa khách hàng</div>
                              
                                    </div>
                                </td>
                            <td style="width:20px;text-align:right">{{ $loop->iteration }}</td>
                          
                            <td>
                                    {{ $item->source_code }}
                             </td>

                             <td>
                                    {{ $item->source_name }}
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
                    <div class="col-md-12 col-sm-12 d-flex">
                        <a class="mr-1" href="{{route('admin.hotel.customer.source.all')}}">
                        <button class="btn btn-sm btn-outline--primary" data-modal_title="Làm mới">
                            <i class="fa fa-repeat p-2"></i>
                        </button>
                         </a>
                            <button class="btn btn-sm btn-outline--primary" data-modal_title="Thêm mới nguồn khách hàng" type="button"
                                    data-bs-toggle="modal" data-bs-target="#customer-source"  style="margin-left:10px">
                                <i class="las la-plus  p-2"></i>
                            </button>
                            <form role="form" enctype="multipart/form-data" action="{{route('admin.hotel.customer.source.search')}}">
                                <div class="form-group position-relative mb-0">
                                    <input class="searchInput" name="source_code"
                                        style="height: 35px;border: 1px solid rgb(121, 117, 117, 0.5);margin-left: 8px;"
                                            placeholder="Mã nguồn khách">
                                    <input class="searchInput" name="source_name"
                                        style="height: 35px;border: 1px solid rgb(121, 117, 117, 0.5); margin-left: 8px;"
                                        placeholder="Tên nguồn khách">
                                    
                                    <button type="submit" class="btn btn-primary" style="padding-right:15px;padding-left:15px">
                                        <i class="las la-search"></i>
                                    </button>

                                </div>
                            </form>
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
                                <input type="text" class="form-control " name="source_code" id="add_source_code"
                                       placeholder="Nhập mã nguồn">
                                <span class="invalid-feedback d-block" style="font-weight: 500"
                                      id="source_code_error"></span>
                            </div>
                            <!-- Input 2 -->
                            <div class="mb-3">
                                <label for="statusName" class="form-label">Tên nguồn</label>
                                <input type="text" class="form-control " name="source_name" id="add_source_name"
                                       placeholder="Nhập tên nguồn">
                                <span class="invalid-feedback d-block" style="font-weight: 500"
                                      id="source_name_error"></span>
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
                                      id="edit_source_code_error"></span>
                            </div>
                            <!-- Input 2 -->
                            <div class="mb-3">
                                <label for="statusName" class="form-label">Tên nguồn</label>
                                <input type="text" class="form-control " name="source_name" id="edit-source-name"
                                       placeholder="Nhập tên nguồn">
                                <span class="invalid-feedback d-block" style="font-weight: 500"
                                      id="edit_source_name_error"></span>
                            </div>
                 
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" id="btn-edit-source" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


@endsection
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
    <script src="{{ asset('assets/admin/js/highlighter22.js') }}"></script>
    <script src="{{ asset('assets/validator/validator.js') }}"></script>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            var formEconomyEdit = {
                'source_code': {
                    'element': document.getElementById('add_source_code'),
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
                    'element': document.getElementById('add_source_name'), // id trong input đó
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
            var formEconomyEditedit = {
                'source_code': {
                    'element': document.getElementById('edit-source-code'),
                    'error': document.getElementById('edit_source_code_error'),
                    'validations': [{
                        'func': function(value) {
                            return checkRequired(value); // check trống
                        },
                        'message': generateErrorMessage('MN001')
                    },
                    ]
                },
                'source_name': {
                    'element': document.getElementById('edit-source-name'), // id trong input đó
                    'error': document.getElementById('edit_source_name_error'), // thẻ hiển thị lỗi
                    'validations': [{
                        'func': function(value) {
                            return checkRequired(value);
                        },
                        'message': generateErrorMessage('TN001')
                    },
                    ]
                },
            }
        
         
            $(document).on('click', '#btn-add-source', function(event) {
                if (validateAllFields(formEconomyEdit)) {
                    document.getElementById('addSource').submit(); // là id trong form
                }

                else
                {
                    event.preventDefault();
                
                }  
            });
            $(document).on('click', '#btn-edit-source', function(event) {
                if (validateAllFields(formEconomyEditedit)) {
                    document.getElementById('editSource').submit(); // là id trong form
                }

                else
                {
                    event.preventDefault();
                
                }  
            });
            $('.btn-edit-source').on('click', function() {
                var dataId = $(this).data('id');
                $.ajax({
                    url: `{{ route('admin.hotel.customer.source.edit', '') }}/${dataId}`,
                    type: 'GET',
                    success: function(data) {
                            $('#edit-source-code').val(data.source_code);
                            $('#edit-source-name').val(data.source_name);
                            // $('#edit-unit-code').val(data.unit_code).change();
                            $('#method').attr('value', 'PUT');
                            $('#editSource').attr('action', '{{ route('admin.hotel.customer.source.update', '') }}/' + dataId + '')
                 
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

@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-12">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 d-flex">
                            <a class="mr-1" href="{{route('admin.hotel.customer.group.all')}}">
                            <button class="btn btn-sm btn-outline--primary" data-modal_title="Làm mới">
                                <i class="fa fa-repeat p-2"></i>
                            </button>
                        </a>
                            <button class="btn btn-sm btn-outline--primary" data-modal_title="Thêm mới nhóm khách hàng" type="button"
                                                data-bs-toggle="modal" data-bs-target="#group-code" style="padding-right:15px;padding-left:15px;margin-left:10px">
                                <i class="las la-plus"></i>
                            </button>
                            <form role="form" enctype="multipart/form-data" action="{{route('admin.hotel.customer.group.search')}}">
                                <div class="form-group position-relative mb-0">
                                    <input class="searchInput" name="group_code"
                                        style="height: 35px;border: 1px solid rgb(121, 117, 117, 0.5);margin-left: 8px;"
                                            placeholder="Mã nhóm khách">
                                    <input class="searchInput" name="group_name"
                                        style="height: 35px;border: 1px solid rgb(121, 117, 117, 0.5); margin-left: 8px;"
                                        placeholder="Tên nhóm khách">
                                 
                                    <button type="submit" class="btn btn-primary" style="padding-right:15px;padding-left:15px">
                                        <i class="las la-search"></i>
                                    </button>

                                </div>
                            </form> 
                    </div>
                </div>
            </div>
            <div class="modal fade" id="group-code" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" id="modal-dialog">
                    <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Thêm mới nhóm khách hàng</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="addGroup" action="{{ route('admin.hotel.customer.group.store') }}"
                                        method="POST">
                                        @csrf
                            <!-- Input 1 -->
                                <div class="mb-3">
                                    <label for="groupCode" class="form-label">Mã nhóm</label>
                                    <input type="text" class="form-control" name="group_code" id="add_group_code"
                                        placeholder="Nhập mã nhóm">
                                    <span class="invalid-feedback d-block" style="font-weight: 500"
                                        id="group_code_error"></span>
                                </div>
                                <!-- Input 2 -->
                                <div class="mb-3">
                                    <label for="groupName" class="form-label">Tên nhóm</label>
                                    <input type="text" class="form-control " name="group_name" id="add_group_name"
                                        placeholder="Nhập tên nhóm khách hàng">
                                    <span class="invalid-feedback d-block" style="font-weight: 500"
                                        id="group_name_error"></span>
                                </div>
                        
                        
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary" id="btn-add-customer-group">Lưu</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal fade" id="edit-customer-group" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog" id="modal-dialog">
                <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Cập nhật nhóm khách hàng</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editGroup" method="POST" action="">
                                {!! csrf_field() !!}
                                    <input type="hidden" id="method" name="_method" value="">

                        <!-- Input 1 -->
                            <div class="mb-3">
                                <label for="groupCode" class="form-label">Mã nhóm</label>
                                <input type="text" class="form-control" name="group_code" id="edit_group_code"
                                    placeholder="Nhập mã nhóm">
                                <span class="invalid-feedback d-block" style="font-weight: 500"
                                    id="edit_group_code_error"></span>
                            </div>
                            <!-- Input 2 -->
                            <div class="mb-3">
                                <label for="groupName" class="form-label">Tên nhóm</label>
                                <input type="text" class="form-control " name="group_name" id="edit_group_name"
                                    placeholder="Nhập tên nhóm khách hàng">
                                <span class="invalid-feedback d-block" style="font-weight: 500"
                                    id="edit_group_name_error"></span>
                            </div>
                    
                    
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" id="btn-edit-customer-group">Lưu</button>
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
                <div class="table-responsive--md  table-responsive" style="overflow-x: visible;">
                                        <table class="table--light style--two table">
                        <thead>
                        <tr>
                            <th>@lang('Hành động')</th>

                            <th>@lang('STT')</th>
                            <th>@lang('Mã nhóm KH')</th>
                            <th>@lang('Tên nhóm KH')</th>
                            <!-- <th>@lang('Ghi chú')</th>
                            <th>@lang('Trạng thái')</th> -->
                        </tr>
                        </thead>
                        <tbody id="main-table-hotel">
                        @forelse($customer_groups as $key => $item)
                            <tr data-id="{{ $item->id }}">
                            <td style="width:20px;">
                                        <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"/><circle cx="10.5" cy="5.5" r="1"/><circle cx="10.5" cy="15.5" r="1"/></g></svg>
                            
                                        <div class="dropdown menu_dropdown_check_in" id="dropdown-menu">
                                            <div class="dropdown-item"><a
                                            data-id="{{ $item->id }}" class="btn-edit-customer-group btn-edit-group" data-bs-toggle="modal" data-bs-target="#edit-customer-group" style="color:black">
                                                Sửa nhóm khách hàng
                                            </a></div>
                                        
                                              <div class="dropdown-item booked_room_detail"> <button class=" btn-delete icon-delete-room"
                                                data-id="{{ $item->id }}" data-modal_title="@lang('Xóa nhóm khách hàng')" type="button"
                                                data-pro="0">Xóa nhóm khách hàng</div>
                              
                                        </div>
                                    </td>
                                <td style="width:20px;text-align:right">{{ $loop->iteration }}</td>

                                <td>
                                    {{ $item->group_code }}
                                </td>
                                <td>
                                    {{ $item->group_name }}
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

    <script>
        $(document).ready(function() {
            var formEconomyEdit = {
                'group_code': {
                    'element': document.getElementById('add_group_code'),
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
                    'element': document.getElementById('add_group_name'), // id trong input đó
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
            var formEconomyEditedit = {
                'group_code': {
                    'element': document.getElementById('edit_group_code'),
                    'error': document.getElementById('edit_group_code_error'),
                    'validations': [{
                        'func': function(value) {
                            return checkRequired(value); // check trống
                        },
                        'message': generateErrorMessage('MNH001')
                    },
                    ]
                },
                'group_name': {
                    'element': document.getElementById('edit_group_name'), // id trong input đó
                    'error': document.getElementById('edit_group_name_error'), // thẻ hiển thị lỗi
                    'validations': [{
                        'func': function(value) {
                            return checkRequired(value);
                        },
                        'message': generateErrorMessage('TNH001')
                    },
                    ]   
                },
            }
            $(document).on('click', '#btn-add-customer-group', function(event) {
                if (validateAllFields(formEconomyEdit)) {
                    document.getElementById('addGroup').submit(); // là id trong form
                }

                else
                {
                    event.preventDefault();
                }
                
            });
            $(document).on('click', '#btn-edit-customer-group', function() {
                if (validateAllFields(formEconomyEditedit)) {
                    document.getElementById('editGroup').submit(); // là id trong form
                }
                else
                {
                    event.preventDefault();
                }
            });
            // add
         
            // sửa
            $('.btn-edit-group').on('click', function() {
                var dataId = $(this).data('id');
               // ajax request
                $.ajax({
                    url: `{{ route('admin.hotel.customer.group.edit', '') }}/${dataId}`,
                    type: 'GET',
                    success: function(data) {
                        $('#edit_group_code').val(data.group_code);
                        $('#edit_group_name').val(data.group_name);
                        $('#method').attr('value', 'PUT');
                        $('#editGroup').attr('action', '{{ route('admin.hotel.customer.group.update', '') }}/' + dataId + '')
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
                    title: 'Xác nhận xóa nhóm khách hàng?',
                    text: 'Bạn có chắc chắn muốn xóa nhóm khách hàng này không?',
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
            // chỉnh sửa trạng thái
        
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

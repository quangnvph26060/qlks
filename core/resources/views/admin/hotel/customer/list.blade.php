@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive" style="overflow-x:auto">
                        <div>
                            <table class="table--light style--two table">
                                <thead>
                                <tr>
                                    <th>@lang('Thao tác')</th>
                                    <th>@lang('STT')</th>
                                    <th style="width:8%">@lang('Mã KH')</th>
                                    <th>@lang('Tên')</th>
                                    <th style="width:110px">@lang('Số điện thoại')</th>
                                    <th style="width:15%">@lang('Email')</th>
                                    <th style="width:10%">@lang('Địa chỉ')</th>
                                    <th>@lang('Nhóm KH')</th>
                                    <th>@lang('Nguồn KH')</th>
                                    <th>@lang('Ngày tạo')</th>
                                    <th>@lang('Tr.thái')</th>
                                
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($customers as $id => $customer)
                                    <tr data-id="{{ $customer->id }}">
                                    <td style="width:20px;">
                                            <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"/><circle cx="10.5" cy="5.5" r="1"/><circle cx="10.5" cy="15.5" r="1"/></g></svg>
                                
                                            <div class="dropdown menu_dropdown_check_in" id="dropdown-menu" style="position:fixed">
                                                <div class="dropdown-item"><a
                                                data-id="{{ $customer->id }}" class="btn-edit-customer" data-bs-toggle="modal" data-bs-target="#edit-customer" style="color:black">
                                                    Sửa khách hàng
                                                </a>
                                                </div>
                                                <div class="dropdown-item booked_room_detail"> <button class=" btn-delete icon-delete-room"
                                                        data-id="{{ $customer->id }}" data-modal_title="@lang('Xóa khách hàng')" type="button"
                                                        data-pro="0">Xóa khách hàng
                                                </div>
                                
                                            </div>
                                    </td>
                                    <td style="width:20px;text-align:right">{{ $customers->count() - ($loop->iteration - 1) }}</td>
            
                        
                                    <td>
                                            <span class="fw-bold code">{{ $customer->customer_code }}</span>
                                    </td>
                                    <td>
                                            <p class="fw-bold">{{ $customer->name }}</p>
                                    </td>
                                    <td style="text-align:right">
                                            <p>{{ $customer->phone }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $customer->email }}</p>
                                    </td>
                                    <td>
                                            <p>{{ $customer->address }}</p>
                                    </td>
                                    <td>
                                            @php
                                                $group = \App\Models\CustomerGroup::where('group_code','=',$customer->group_code)->value('group_name');
                                            @endphp
                                            {{ $group}}
                                    </td>
                                    <td>
                                            @php
                                                $source = \App\Models\CustomerSource::where('source_code','=',$customer->source_code)->value('source_name');
                                            @endphp
                                            {{ $source}}
                                    </td>
                                    <td style="text-align:right;width:90px">
                                        {{ (new DateTime($customer->created_at))->format('d/m/Y')  }}
                                    </td>
                                    <td style="width:50px;text-align: center" class="status-hotel">
                                            @if($customer->status == 1)
                                                <i class="fa fa-check" style="color:green;text-align: center"></i>
                                            @else
                                            <i class="fa fa-close" style="color:red;text-align: center"></i>
                                            @endif
                                    </td>
                            
                                
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                                </tbody>
                            </table><!-- table end -->
                        </div>
                    </div>
                </div>
            
            </div>
        </div>

    </div>
    @push('breadcrumb-plugins')
        <div class="card-body mt-1">
            <div class="row">
                <div class="col-md-12 col-sm-12 d-flex">
                    <a class="mr-1" href="{{route('admin.hotel.customer.all')}}">
                        <button class="btn btn-sm btn-outline--primary" data-modal_title="Làm mới">
                            <i class="fa fa-repeat p-2"></i>
                        </button>
                    </a>
                     <button class="btn btn-sm btn-outline--primary" data-modal_title="Thêm mới khách hàng" type="button"
                                                data-bs-toggle="modal" data-bs-target="#customer" style="padding-right:15px;padding-left:15px;margin-left:10px">
                        <i class="las la-plus"></i>
                    </button>
                
                    <form role="form" enctype="multipart/form-data" action="{{route('admin.hotel.customer.search')}}">
                        <div class="form-group position-relative mb-0">
                            <input class="searchInput" name="customer_code"
                                   style="height: 35px;width:20%;border: 1px solid rgb(121, 117, 117, 0.5);margin-left: 8px;"
                                    placeholder="Mã khách hàng" value="{{ $customer_code ?? '' }}">
                            <input class="searchInput" name="name"
                                   style="height: 35px;width:20%;border: 1px solid rgb(121, 117, 117, 0.5); margin-left: 8px;"
                                   placeholder="Tên khách hàng" value="{{ $name ?? '' }}">
                            <input class="searchInput" name="phone"
                                   style="height: 35px;width:20%;border: 1px solid rgb(121, 117, 117, 0.5); margin-left: 8px;"
                                   placeholder="Điện thoại" value="{{ $phone ?? '' }}">
                            <input class="searchInput" name="address"
                                   style="height: 35px;width:20%;border: 1px solid rgb(121, 117, 117, 0.5); margin-left: 8px;"
                                 placeholder="Địa chỉ" value="{{ $address ?? '' }}">
            
                            <button type="submit" class="btn btn-primary" style="padding-right:15px;padding-left:15px">
                                <i class="las la-search"></i>
                            </button>

                        </div>
                    </form>
                </div>
                @if ($customers->hasPages())
                    <div >
                        {{ paginateLinks($customers) }}
                    </div>
                @endif
            </div>
        </div>
    @endpush
    <div class="modal fade" id="customer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Thêm mới khách hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addCustomer" method="POST" action="{{ route('admin.hotel.customer.store') }}">
                        {!! csrf_field() !!}
                        {{ method_field('POST') }}
                        <div class="row">
                            <div style="display: grid; grid-template-columns: 50% 50%">
                                <div class="mb-3">
                                    <label for="statusCode" class="form-label">Mã khách hàng</label>
                                    <input type="text" class="form-control " name="customer_code" id="add_customer_code"
                                           placeholder="Nhập mã khách hàng" style="width:98%">
                                    <span class="invalid-feedback d-block" style="font-weight: 500"
                                          id="customer_code_error"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="statusName" class="form-label">Tên khách hàng</label>
                                    <input type="text" class="form-control " name="name" id="add_name"
                                           placeholder="Nhập tên khách hàng">
                                    <span class="invalid-feedback d-block" style="font-weight: 500"
                                          id="name_error"></span>
                                </div>
                            </div>
                            <div style="display: grid; grid-template-columns: 50% 50%">
                                <div class="mb-3">
                                    <label for="statusCode" class="form-label">Điện thoại</label>
                                    <input type="number" class="form-control " name="phone" id="add_phone"
                                           placeholder="Nhập số điện thoại" style="width:98%">
                                    <span class="invalid-feedback d-block" style="font-weight: 500"
                                          id="phone_error"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="statusCode" class="form-label">Email</label>
                                    <input type="email" class="form-control " name="email" id="add_email"
                                           placeholder="Nhập email">
                                    <span class="invalid-feedback d-block" style="font-weight: 500"
                                          id="email_error"></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" name="address" id="add_address"
                                       placeholder="Nhập địa chỉ">
                                <span class="invalid-feedback d-block"  id="address_error" style="font-weight: 500"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea type="text" class="form-control " name="note" rows="1"></textarea>

                            </div>
                            <div style="display: grid; grid-template-columns: 50% 50%">
                            <div class="mb-3">
                                    <label>@lang('Nhóm khách')</label>
                                    <select class="form-control" name="group_code" style="width:98%">
                                        <option value="">@lang('Chọn nhóm khách hàng')</option>
                                        @php
                                            $customer_group = \App\Models\CustomerGroup::where('unit_code',unitCode())->get();
                                        @endphp
                                        @foreach ($customer_group as $group)
                                            <option value="{{ $group->group_code }}">
                                                {{ $group->group_name }}</option>
                                        @endforeach
                                    </select>
                            </div>
                            <div class="mb-3">
                                    <label>@lang('Nguồn khách')</label>
                                    <select class="form-control" name="source_code">
                                        <option value="">@lang('Chọn nguồn khách hàng')</option>
                                        @php
                                            $customer_source = \App\Models\CustomerSource::where('unit_code',unitCode())->get();
                                        @endphp
                                        @foreach ($customer_source as $source)
                                            <option value="{{ $source->source_code }}">
                                                {{ $source->source_name }}</option>
                                        @endforeach
                                    </select>
                            </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label><br>
                                <input type="radio" name="status" value="1" id="statusActive" checked>Hoạt động
                                <input type="radio" name="status" value="0" id="statusInactive">
                                Không hoạt động
                            </div>
                       
            
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                <button type="submit" id="btn-add-customer" class="btn btn-primary">Lưu</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit-customer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
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
                                           <input id="ma-kh-cu" hidden>
                                    <span class="invalid-feedback d-block" id="edit_customer_code_error" style="font-weight: 500"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="statusName" class="form-label">Tên khách hàng</label>
                                    <input type="text" class="form-control " name="name" id="edit-name"
                                           placeholder="Nhập tên khách hàng">
                                    <span class="invalid-feedback d-block" id="edit_name_error" style="font-weight: 500"></span>
                                </div>
                            </div>
                            <div style="display: grid; grid-template-columns: 50% 50%">
                                <div class="mb-3">
                                    <label for="statusCode" class="form-label">Điện thoại</label>
                                    <input type="text" class="form-control " name="phone" id="edit-phone"
                                           placeholder="Nhập số điện thoại" style="width:98%">
                                    <span class="invalid-feedback d-block" id="edit_phone_error" style="font-weight: 500"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" id="edit-email"
                                           placeholder="Nhập email">
                                    <span class="invalid-feedback d-block" id="edit_email_error" style="font-weight: 500" ></span>
                                </div>
                            </div>
                            <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control " name="address" id="edit-address"
                                   placeholder="Nhập địa chỉ">
                                 <span class="invalid-feedback d-block" id="edit_address_error" style="font-weight: 500"></span>
                             </div>
                            <div class="mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea type="text" class="form-control" id="edit-note" name="note" rows="1"></textarea>

                            </div>
                            <div style="display: grid; grid-template-columns: 50% 50%">
                            <div class="mb-3">
                                    <label>@lang('Nhóm khách')</label>
                                    <select class="form-control" name="group_code" id="edit-group-code" style="width:98%">
                                        <option value="">@lang('Chọn nhóm khách hàng')</option>
                                        @php
                                            $customer_group = \App\Models\CustomerGroup::where('unit_code',unitCode())->get();
                                        @endphp
                                        @foreach ($customer_group as $group)
                                            <option value="{{ $group->group_code }}">
                                                {{ $group->group_name }}</option>
                                        @endforeach
                                    </select>
                            </div>
                            <div class="mb-3">
                                    <label>@lang('Nguồn khách')</label>
                                    <select class="form-control" name="source_code" id="edit-source-code">
                                        <option value="">@lang('Chọn nguồn khách hàng')</option>
                                        @php
                                            $customer_source = \App\Models\CustomerSource::where('unit_code',unitCode())->get();
                                        @endphp
                                        @foreach ($customer_source as $source)
                                            <option value="{{ $source->source_code }}">
                                                {{ $source->source_name }}</option>
                                        @endforeach
                                    </select>
                            </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label><br>
                                <input type="radio" class="edit-status" name="status" value="1">Hoạt động
                                <input type="radio" class="edit-status" name="status" value="0">
                                Không hoạt động
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
                'customer_code': {
                    'element': document.getElementById('add_customer_code'),
                    'error': document.getElementById('customer_code_error'),
                
                    'validations': [{
                        'func': function(value) {
                            return checkRequired(value); // check trống
                        },
                        'message': generateErrorMessage('MKH001')
                    },
                    ]
                },

                'name': {
                    'element': document.getElementById('add_name'), // id trong input đó
                    'error': document.getElementById('name_error'), // thẻ hiển thị lỗi
                    'validations': [{
                        'func': function(value) {
                            return checkRequired(value);
                        },
                        'message': generateErrorMessage('TKH001')
                    },
                    ]
                },
            //     'phone': {
            //         'element': document.getElementById('add_phone'), // id trong input đó
            //         'error': document.getElementById('phone_error'), // thẻ hiển thị lỗi
            //         'validations': [{
            //             'func': function(value) {
            //                 return checkRequired(value);
            //             },
            //             'message': generateErrorMessage('SDT001')
            //         },
            //         ]
            //     },
            
            //     'email': {
            //         'element': document.getElementById('add_email'), // id trong input đó
            //         'error': document.getElementById('email_error'), // thẻ hiển thị lỗi
            //         'validations': [{
            //             'func': function(value) {
            //                 return checkRequired(value);
            //             },
            //             'message': generateErrorMessage('Email001') 
            //         },
            //         ]
                
            //     },
            //     'email': {
            //         'element': document.getElementById('add_email'), // id trong input đó
            //         'error': document.getElementById('email_error'), // thẻ hiển thị lỗi
            //         'validations': [{
            //             'func': function(value) {
            //                 return checkEmail(value);
            //             },
            //             'message': generateErrorMessage('Email002') 
            //         },
            //         ]
            //     },
            //     'address': {
            //         'element': document.getElementById('add_address'), // id trong input đó
            //         'error': document.getElementById('address_error'), // thẻ hiển thị lỗi
            //         'validations': [{
            //             'func': function(value) {
            //                 return checkRequired(value);
            //             },
            //             'message': generateErrorMessage('DiaChi001')
            //         },
            //         ]
            //     },
            }
            var formEconomyEdit_edit = {
                'customer_code': {
                    'element': document.getElementById('edit-customer-code'),
                    'error': document.getElementById('edit_customer_code_error'),
                
                    'validations': [{
                        'func': function(value) {
                            return checkRequired(value); // check trống
                        },
                        'message': generateErrorMessage('MKH001')
                    },
                    ]
                },

                'name': {
                    'element': document.getElementById('edit-name'), // id trong input đó
                    'error': document.getElementById('edit_name_error'), // thẻ hiển thị lỗi
                    'validations': [{
                        'func': function(value) {
                            return checkRequired(value);
                        },
                        'message': generateErrorMessage('TKH001')
                    },
                    ]
                },
                // 'phone': {
                //     'element': document.getElementById('edit-phone'), // id trong input đó
                //     'error': document.getElementById('edit_phone_error'), // thẻ hiển thị lỗi
                //     'validations': [{
                //         'func': function(value) {
                //             return checkRequired(value);
                //         },
                //         'message': generateErrorMessage('SDT001')
                //     },
                //     ]
                // },
            
                // 'email': {
                //     'element': document.getElementById('edit-email'), // id trong input đó
                //     'error': document.getElementById('edit_email_error'), // thẻ hiển thị lỗi
                //     'validations': [{
                //         'func': function(value) {
                //             return checkRequired(value);
                //         },
                //         'message': generateErrorMessage('Email001') 
                //     },
                //     ]
                
                // },
                // 'email': {
                //     'element': document.getElementById('edit-email'), // id trong input đó
                //     'error': document.getElementById('edit_email_error'), // thẻ hiển thị lỗi
                //     'validations': [{
                //         'func': function(value) {
                //             return checkEmail(value);
                //         },
                //         'message': generateErrorMessage('Email002') 
                //     },
                //     ]
                // },
                // 'address': {
                //     'element': document.getElementById('edit-address'), // id trong input đó
                //     'error': document.getElementById('edit_address_error'), // thẻ hiển thị lỗi
                //     'validations': [{
                //         'func': function(value) {
                //             return checkRequired(value);
                //         },
                //         'message': generateErrorMessage('DiaChi001')
                //     },
                //     ]
                // },
            }
            $(document).on('click', '#btn-add-customer', function(event) {
                
                if (validateAllFields(formEconomyEdit)) {
                    document.getElementById('addCustomer').submit(); // là id trong form
                }

                else
                {
                    event.preventDefault();
                
                }
                
            });
            $(document).on('keyup', '#add_customer_code', function(event) {
                    var code = $('#add_customer_code').val();
                    $.ajax({
                        url: `{{ route('admin.hotel.customer.check') }}`,
                        type: 'GET',
                        cache: false,
                        data: {
                            'customer_code': code,
                    },
                    success: function(response) {
                            if(response == 1)
                            {
                                $('#customer_code_error').html('Mã khách hàng đã tồn tại');
                                document.getElementById('btn-add-customer').disabled = 'disabled';

                            }
                            else
                            {
                                $('#customer_code_error').html('');
                                document.getElementById('btn-add-customer').disabled = false;

                            }
            
                     },
                     error: function(error) {
                        console.log(error);
                
                     }
                });
            });
            $(document).on('keyup', '#edit-customer-code', function(event) {
                    var code = $('#edit-customer-code').val();
                    $.ajax({
                        url: `{{ route('admin.hotel.customer.check') }}`,
                        type: 'GET',
                        cache: false,
                        data: {
                            'customer_code': code,
                    },
                    success: function(response) {
                            if(response == 1)
                            {
                                $('#edit_customer_code_error').html('Mã khách hàng đã tồn tại');
                                document.getElementById('btn-edit-customer').disabled = 'disabled';
                                var old_code = $('#ma-kh-cu').val();
                                var allVals = [];
                                $(".code").each(function () {
                                if ($(this).text() != old_code) {
                                        allVals.push($(this).text());
                                    }
                                });
                               
                                if (allVals.indexOf($('#edit-customer-code').val()) > -1) {
                                    $('#edit_customer_code_error').html('Mã khách hàng đã tồn tại');
                                    document.getElementById('btn-edit-customer').disabled = 'disabled';
                                }
                                else
                                {
                                    $('#edit_customer_code_error').html('');
                                    document.getElementById('btn-edit-customer').disabled = false;
                                }

                            }
                            else
                            {
                                $('#edit_customer_code_error').html('');
                                document.getElementById('btn-edit-customer').disabled = false;

                            }
            
                     },
                     error: function(error) {
                        console.log(error);
                
                     }
                });
            });
            $(document).on('click', '#btn-edit-customer', function() {
                if (validateAllFields(formEconomyEdit_edit)) {
                    document.getElementById('editCustomer').submit(); // là id trong form
                }
                else
                {
                    event.preventDefault();

                }
            });
           
        $('.btn-edit-customer').on('click', function() {
            var dataId = $(this).data('id');
            $.ajax({
                url: `{{ route('admin.hotel.customer.edit', '') }}/${dataId}`,
                type: 'GET',
                success: function(data) {
                    $('#ma-kh-cu').val(data.customer_code);
                    $('#edit-customer-code').val(data.customer_code);
                    $('#edit-name').val(data.name);
                    $('#edit-phone').val(data.phone);
                    $('#edit-email').val(data.email);
                    $('#edit-address').val(data.address);
                    $('#edit-note').val(data.note);
                    $('#edit-group-code').val(data.group_code).change();
                    $('#edit-source-code').val(data.source_code).change();
                    $('input[name^="status"][class^="edit-status"][value="' + data.status + '"').prop('checked', true);
                    $('#method').attr('value', 'PUT');
                    $('#editCustomer').attr('action', '{{ route('admin.hotel.customer.update', '') }}/' + dataId + '')

                  
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });

        });
        $('.icon-delete-room').on('click', function() {
            var dataId = $(this).data('id');
            var rowToDelete = $(`tr[data-id="${dataId}"]`);
            $.ajax({
                        url: `{{ route('admin.hotel.customer.get', '') }}/${dataId}`,
                        type: 'GET',
                        success: function(data) {
                           if(data == 1)
                           {
                            alert(1);
                            Swal.fire({
                                title: 'Khách hàng đã có đơn hàng',
                                text: 'Bạn không thể xóa khách hàng này',
                                icon: 'warning',
                                showCancelButton: true,
                                // confirmButtonText: 'Đồng ý',
                                cancelButtonText: 'Hủy bỏ',
                                reverseButtons: true
                            })
                           }
                           else
                           {
                            Swal.fire({
                                title: 'Xác nhận xóa khách hàng?',
                                text: 'Bạn có chắc chắn muốn xóa khách hàng này không?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Đồng ý',
                                cancelButtonText: 'Hủy bỏ',
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // ajax
                                    $.ajax({
                                        url: `{{ route('admin.hotel.customer.delete', '') }}/${dataId}`,
                                        type: 'POST',
                                        success: function(data) {
                                            if (data.status ==='success') {
                                                rowToDelete.remove();
                                                    
                                            }
                                            else
                                            {
                                                alert('Khách hàng đã có đơn hàng, không thể xóa');
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            console.log(xhr.responseText);
                                        }
                                    });


                                }
                            });
                           }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                        }
                    });
          
        });

    });
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
<script type="text/javascript">
    $('.iziToast-title').html('<p>Thông báo</p>'); 
</script>
@endpush

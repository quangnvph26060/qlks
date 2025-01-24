@extends('admin.layouts.master_iframe')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('Mã KH')</th>
                                <th>@lang('Tên')</th>
                                <th>@lang('Số điện thoại')</th>
{{--                                <th>@lang('Email')</th>--}}
                                <th>@lang('Địa chỉ')</th>
                                <th>@lang('Ngày tạo')</th>
{{--                                <th>@lang('Ghi chú')</th>--}}
                                <th>@lang('Trạng thái')</th>
                                <th>@lang('Mã đơn vị')</th>
                                <th>@lang('Hành động')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($customers as $customer)
                                <tr data-id="{{ $customer->id }}">
                                    <td>
                                        <span class="fw-bold">{{ $customer->customer_code }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $customer->name }}</span>
                                    </td>
                                    <td>
                                        {{ $customer->phone }}
                                    </td>
{{--                                    <td>--}}
{{--                                        {{ $customer->email }}--}}
{{--                                    </td>--}}
                                    <td>
                                        {{ $customer->address }}
                                    </td>
                                    <td>
                                        {{ showDateTime($customer->created_at) }} <br> {{ diffForHumans($customer->created_at) }}
                                    </td>

{{--                                    <td>--}}
{{--                                        {{ $customer->note }}--}}
{{--                                    </td>--}}
                                    <td class="status-hotel">
                                        {!! $customer->styleStatus() !!}
                                    </td>
                                    <td>
                                        {{ $customer->unit_code }}
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-outline--primary btn-edit-customer"
                                           data-id="{{ $customer->id }}" data-bs-toggle="modal" data-bs-target="#edit-customer">
                                            <i class="la la-pencil"></i>
                                        </a>
                                        @if($customer->status == 1)
                                            <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="{{ route('admin.hotel.customer.status', $customer->id) }}"   data-id="{{ $customer->id }}"
                                                    data-question="@lang('Bạn có chắc chắn muốn tắt trạng thái này không?')" type="button">
                                                <i class="la la-eye-slash"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-outline--success confirmationBtn"
                                                    data-action="{{ route('admin.hotel.customer.status', $customer->id) }}"   data-id="{{ $customer->id }}"
                                                    data-question="@lang('Bạn có chắc chắn muốn tắt trạng thái này không?')" type="button">
                                                <i class="la la-eye-slash"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-sm btn-outline--danger btn-delete icon-delete-room"
                                                data-id="{{ $customer->id }}" data-modal_title="@lang('Xóa khách hàng')" type="button"
                                                data-pro="0">
                                            <i class="fas fa-trash"></i>
                                        </button>
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
                @if ($customers->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($customers) }}
                    </div>
                @endif
            </div>
        </div>

    </div>
    @push('breadcrumb-plugins')
        <div class="card-body mt-1">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <form role="form" enctype="multipart/form-data" action="{{route('admin.hotel.customer.search')}}">
                        <div class="form-group position-relative mb-0" id="btn-add-customer">
                            <input class="searchInput" name="customer_code"
                                   style="height: 35px;width:18%;border: 1px solid rgb(121, 117, 117, 0.5);"
                                    placeholder="Mã khách hàng">
                            <input class="searchInput" name="name"
                                   style="height: 35px;width:18%;border: 1px solid rgb(121, 117, 117, 0.5); margin-left: 8px;"
                                   placeholder="Tên khách hàng">
                            <input class="searchInput" name="phone"
                                   style="height: 35px;width:18%;border: 1px solid rgb(121, 117, 117, 0.5); margin-left: 8px;"
                                   placeholder="Điện thoại">
                            <input class="searchInput" name="address"
                                   style="height: 35px;width:18%;border: 1px solid rgb(121, 117, 117, 0.5); margin-left: 8px;"
                                   type="search" placeholder="Địa chỉ">
                            <input class="searchInput" name="unit_code"
                                   style="height: 35px;width:18%;border: 1px solid rgb(121, 117, 117, 0.5); margin-left: 8px;"
                                   type="search" placeholder="Mã đơn vị">
                            <button type="submit" class="btn btn-primary" style="padding:10px">
                                <i class="las la-search"></i>
                            </button>

                        </div>
                    </form>
                </div>
                <div class="col-md-2">
                    <button class="btn mt-1 btn-sm btn-outline--primary" data-modal_title="Thêm mới khách hàng" type="button"
                                                data-bs-toggle="modal" data-bs-target="#customer">
                        <i class="las la-plus"></i>
                    </button>
                </div>
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
                                    <input type="text" class="form-control " name="customer_code" id="customer_code"
                                           placeholder="Nhập mã khách hàng" style="width:98%">
                                    <span class="invalid-feedback d-block" style="font-weight: 500"
                                          id="customer_code_error"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="statusName" class="form-label">Tên khách hàng</label>
                                    <input type="text" class="form-control " name="name" id="name"
                                           placeholder="Nhập tên khách hàng">
                                    <span class="invalid-feedback d-block" style="font-weight: 500"
                                          id="name_error"></span>
                                </div>
                            </div>
                            <div style="display: grid; grid-template-columns: 50% 50%">
                                <div class="mb-3">
                                    <label for="statusCode" class="form-label">Điện thoại</label>
                                    <input type="text" class="form-control " name="phone" id="phone"
                                           placeholder="Nhập số điện thoại" style="width:98%">
                                    <span class="invalid-feedback d-block" style="font-weight: 500"
                                          id="phone_error"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="statusCode" class="form-label">Email</label>
                                    <input type="email" class="form-control " name="email" id="email"
                                           placeholder="Nhập email">
                                    <span class="invalid-feedback d-block" style="font-weight: 500"
                                          id="email_error"></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" name="address"
                                       placeholder="Nhập địa chỉ">
                                <span class="invalid-feedback d-block" style="font-weight: 500"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea type="text" class="form-control " name="note" rows="1"></textarea>

                            </div>
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label><br>
                                <input type="radio" name="status" value="1" id="statusActive">Hoạt động
                                <input type="radio" name="status" value="0" id="statusInactive" checked>
                                Không hoạt động
                            </div>
                            <div class=" mb-3">
                                <label for="">Mã đơn vị </label>
                                <select name="unit_code" id="unit-code-multiple-choice" class="form-control">
                                    <option value="" selected>--Chọn mã đơn vị--</option>
                                    @foreach($unit_codes as $item)
                                        <option value="{{ $item->ma_coso }}">{{ $item->ma_coso }}</option>
                                    @endforeach
                                </select>
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
                                    <span class="invalid-feedback d-block" style="font-weight: 500"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="statusName" class="form-label">Tên khách hàng</label>
                                    <input type="text" class="form-control " name="name" id="edit-name"
                                           placeholder="Nhập tên khách hàng" s>
                                    <span class="invalid-feedback d-block" style="font-weight: 500"></span>
                                </div>
                            </div>
                            <div style="display: grid; grid-template-columns: 50% 50%">
                                <div class="mb-3">
                                    <label for="statusCode" class="form-label">Điện thoại</label>
                                    <input type="text" class="form-control " name="phone" id="edit-phone"
                                           placeholder="Nhập số điện thoại" style="width:98%">
                                    <span class="invalid-feedback d-block" style="font-weight: 500"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" id="edit-email"
                                           placeholder="Nhập email">
                                    <span class="invalid-feedback d-block" style="font-weight: 500" ></span>
                                </div>
                            </div>
                            <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control " name="address" id="edit-address"
                                   placeholder="Nhập địa chỉ">
                                 <span class="invalid-feedback d-block" style="font-weight: 500"></span>
                             </div>
                            <div class="mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea type="text" class="form-control" id="edit-note" name="note" rows="1"></textarea>

                            </div>
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label><br>
                                <input type="radio" name="status" value="1">Hoạt động
                                <input type="radio" name="status" value="0">
                                Không hoạt động
                            </div>
                            <div class=" mb-3">
                                <label for="">Mã đơn vị </label>
                                <select name="unit_code" id="edit-unit-code" class="form-control">
                                    <option value="" selected>--Chọn mã đơn vị--</option>
                                    @foreach($unit_codes as $item)
                                        <option value="{{ $item->ma_coso }}">{{ $item->ma_coso }}</option>
                                    @endforeach
                                </select>
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
@push('script')

<script>
    $(document).ready(function() {
        $('.btn-edit-customer').on('click', function() {
            var dataId = $(this).data('id');
            $.ajax({
                url: `{{ route('admin.hotel.customer.edit', '') }}/${dataId}`,
                type: 'GET',
                success: function(data) {
                    $('#edit-customer-code').val(data.customer_code);
                    $('#edit-name').val(data.name);
                    $('#edit-phone').val(data.phone);
                    $('#edit-email').val(data.email);
                    $('#edit-address').val(data.address);
                    $('#edit-note').val(data.note);
                    $('#edit-unit-code').val(data.unit_code).change();
                    $('input[name^="status"][value="' + data.status + '"').prop('checked', true);
                    $('#method').attr('value', 'PUT');
                    $('#editCustomer').attr('action', '{{ route('admin.hotel.customer.update', '') }}/' + dataId + '')

                    // formEconomyEdit.source_code.element = document.getElementById('source_code');
                    // formEconomyEdit.source_code.error = document.getElementById('source_code_error');
                    // formEconomyEdit.source_name.element = document.getElementById('source_name');
                    // formEconomyEdit.source_name.error = document.getElementById('source_name_error');
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });

        });
        $('.icon-delete-room').on('click', function() {
            var dataId = $(this).data('id');
            var rowToDelete = $(`tr[data-id="${dataId}"]`);
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
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                        }
                    });


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

</script>
@endpush

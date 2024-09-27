@extends('admin.layouts.app')
@section('panel')
    <form action="" method="POST" id="supplierForm">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thông tin nhà cung cấp</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12">
                                <label for="supplier_id" class="control-label ">@lang('Mã nhà cung cấp')</label>
                                <input value="{{$supplier->supplier_id ?? 'Chưa có mã nhà cung cấp'}}" type="text" name="supplier_id" id="supplier_id"
                                    class="form-control" name="supplier_id" placeholder="Nhập tên nhà cung cấp">
                                <small></small>
                            </div>
                            <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12">
                                <label for="name" class="control-label required">@lang('Tên nhà cung cấp')</label>
                                <input value="{{$supplier->name}}" type="text" name="name" id="name" class="form-control"
                                    placeholder="Nhập tên nhà cung cấp">
                                <small></small>
                            </div>
                            <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12">
                                <label for="email" class="control-label required">@lang('Địa chỉ email')</label>
                                <input value="{{$supplier->email}}" type="email" name="email" id="email" class="form-control"
                                    placeholder="Nhập địa chỉ email">
                                <small></small>
                            </div>
                            <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12">
                                <label for="address" class="control-label required">@lang('Địa chỉ')</label>
                                <input value="{{$supplier->address}}" type="text" name="address" id="address" class="form-control"
                                    placeholder="Nhập địa chỉ email">
                                <small></small>
                            </div>
                            <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12">
                                <label for="phone" class="control-label required">@lang('Số điện thoại')</label>
                                <input value="{{$supplier->phone}}" type="text" name="phone" id="phone" class="form-control"
                                    placeholder="Nhập số điện thoại">
                                <small></small>
                            </div>
                            <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12">
                                <label for="account_number"
                                    class="control-label required">@lang('Số tài khoản')</label>
                                <input value="{{$supplier->account_number}}" type="text" name="account_number" id="account_number"
                                    class="form-control" placeholder="Nhập số tài khoản ngân hàng">
                                <small></small>
                            </div>
                            <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12">
                                <label for="tax_code" class="control-label required">@lang('Mã số thuế')</label>
                                <input value="{{$supplier->tax_code}}" type="text" name="tax_code" id="tax_code"
                                    class="form-control" placeholder="Nhập số tài khoản ngân hàng">
                                <small></small>
                            </div>
                            <div class="form-group mb-3 col-12">
                                <label for="bank_id" class="control-label required">@lang('Ngân hàng')</label>
                                <select name="bank_id" id="bank_id" class="form-select">
                                    <option disabled selected>--- Chọn ngân hàng ---</option>
                                    @foreach ($banks as $id => $name)
                                        <option @selected($name) value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <small></small>
                            </div>
                            <div class="form-group mb-3 col-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                    <label class="form-check-label" for="is_active">Hoạt động</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group col-6 mt-3">
                <button class="btn btn-primary btn-sm">@lang('Cập nhật')</button>
                <a href="javascript:void(0)" class="btn btn-outline-secondary btn-sm btn-reset">@lang('Đặt lại')</a>
            </div>
        </div>
    </form>
@endsection


@push('breadcrumb-plugins')
    <a href="{{ route('admin.supplier.index') }}" class="btn btn-primary"><i class="las la-list"></i>
        @lang('Danh sách nhà cung cấp')</a>
@endpush


@push('script')
    <script>
        $(document).ready(function() {
            $('input[name="supplier_id"]').on('input', function(){
                this.value = this.value.toUpperCase();
            });

            $('#suppliers\\.bank_id').select2({
                placeholder: '--- Chọn ngân hàng ---',
                allowClear: true
            });

            $('.btn-reset').on('click', function() {
                $('#supplierForm').trigger('reset');
                $('input, select').removeClass('is-invalid');
                $('small').removeClass('invalid-feedback').html('');
            });

            $('#supplierForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('admin.supplier.update', $supplier->id) }}",
                    type: "PUT",
                    data: $(this).serializeArray(),
                    success: function(response) {
                        if (response.status) {
                            window.location.href = "{{ route('admin.supplier.index') }}";
                        } else {
                            $('small').removeClass('invalid-feedback').html('');
                            $('input, select').removeClass('is-invalid');

                            $.each(response.errors, function(index, message) {
                                $(`input[id="${index}"], select[id="${index}"]`)
                                    .addClass('is-invalid')
                                    .siblings('small')
                                    .addClass('invalid-feedback').html(message);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr);
                    }
                })
            })
        })
    </script>
@endpush

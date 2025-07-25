 <div class="modal fade" id="supplierModal" tabindex="-1" role="dialog" aria-hidden="true">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header"
                 style=" display: flex;
                    justify-content: end;
                    align-items: end;">
                 <button type="button" class="close" data-bs-dismiss="modal" aria-label="Đóng">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body" id="supplierModalContent">

                 <form action="" method="POST" id="supplierForm">
                     @csrf
                     <div class="row">
                         <div class="col-lg-12 col-md-12 col-sm-12">
                             <div class="card">
                                 <div class="card-header">
                                     <h5 class="card-title mb-0">Thông tin nhà cung cấp</h5>
                                 </div>
                                 <div class="card-body">
                                     <div class="row">
                                         <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12">
                                             <label for="suppliers.supplier_id"
                                                 class="control-label ">@lang('Mã nhà cung cấp')</label>
                                             <input type="text" name="suppliers[supplier_id]"
                                                 id="suppliers.supplier_id" class="form-control"
                                                 value="{{ $code }}" placeholder="Nhập tên nhà cung cấp">
                                             <small></small>
                                         </div>
                                         <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12">
                                             <label for="suppliers.name"
                                                 class="control-label required">@lang('Tên nhà cung cấp')</label>
                                             <input type="text" name="suppliers[name]" id="suppliers.name"
                                                 class="form-control" placeholder="Nhập tên nhà cung cấp">
                                             <small></small>
                                         </div>
                                         <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12">
                                             <label for="suppliers.address"
                                                 class="control-label required">@lang('Địa chỉ')</label>
                                             <input type="text" name="suppliers[address]" id="suppliers.address"
                                                 class="form-control" placeholder="Nhập địa chỉ">
                                             <small></small>
                                         </div>
                                         <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12">
                                             <label for="suppliers.email"
                                                 class="control-label ">@lang('Địa chỉ email')</label>
                                             <input type="email" name="suppliers[email]" id="suppliers.email"
                                                 class="form-control" placeholder="Nhập địa chỉ email">
                                             <small></small>
                                         </div>

                                         <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12">
                                             <label for="suppliers.phone"
                                                 class="control-label required">@lang('Số điện thoại')</label>
                                             <input type="text" name="suppliers[phone]" id="suppliers.phone"
                                                 class="form-control" placeholder="Nhập số điện thoại">
                                             <small></small>
                                         </div>
                                         {{-- <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12">
                                             <label for="suppliers.account_number"
                                                 class="control-label ">@lang('Số tài khoản')</label>
                                             <input type="text" name="suppliers[account_number]"
                                                 id="suppliers.account_number" class="form-control"
                                                 placeholder="Nhập số tài khoản ngân hàng">
                                             <small></small>
                                         </div> --}}
                                         <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12">
                                             <label for="suppliers.tax_code"
                                                 class="control-label">@lang('Mã số thuế')</label>
                                             <input type="text" name="suppliers[tax_code]" id="suppliers.tax_code"
                                                 class="form-control" placeholder="Nhập số tài khoản ngân hàng">
                                             <small></small>
                                         </div>
                                         {{-- <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12">
                                             <label for="suppliers.bank_id"
                                                 class="control-label">@lang('Ngân hàng')</label>
                                             <select name="suppliers[bank_id]" id="suppliers.bank_id"
                                                 class="form-select">
                                                 <option disabled selected>--- Chọn ngân hàng ---</option>
                                                 @foreach ($banks as $id => $name)
                                                     <option value="{{ $id }}">{{ $name }}</option>
                                                 @endforeach
                                             </select>
                                             <small></small>
                                         </div> --}}
                                         <div class="form-group mb-3 col-6">
                                             <div class="form-check form-switch">
                                                 <input class="form-check-input" type="checkbox" name="is_active"
                                                     id="is_active" checked>
                                                 <label class="form-check-label" for="is_active">Hoạt động</label>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         {{-- <div class="col-lg-4 col-md-12 col-sm-12 mt-3 mt-sm-0">
                             <div class="card">
                                 <div class="card-header">
                                     <h5 class="card-title mb-0">@lang('Thông tin người đại diện')</h5>
                                 </div>
                                 <div class="card-body">
                                     <div class="form-group mb-3">
                                         <label for="representatives.name"
                                             class="control-label required">@lang('Tên người đại diện')</label>
                                         <input type="text" name="representatives[name]" id="representatives.name"
                                             class="form-control" placeholder="Nhập tên">
                                         <small></small>
                                     </div>

                                     <div class="form-group mb-3">
                                         <label for="representatives.phone"
                                             class="control-label required">@lang('Số điện thoại')</label>
                                         <input type="text" name="representatives[phone]"
                                             id="representatives.phone" class="form-control"
                                             placeholder="Nhập tên">
                                         <small></small>
                                     </div>
                                     <div class="form-group mb-3">
                                         <label for="representatives.email"
                                             class="control-label ">@lang('Email')</label>
                                         <input type="text" name="representatives[email]"
                                             id="representatives.email" class="form-control"
                                             placeholder="Nhập tên">
                                         <small></small>
                                     </div>
                                     <div class="form-group mb-3">
                                         <label for="representatives.position"
                                             class="control-label">@lang('Chức vụ')</label>
                                         <input type="text" name="representatives[position]"
                                             id="representatives.position" class="form-control"
                                             placeholder="Nhập tên">
                                         <small></small>
                                     </div>
                                 </div>
                             </div>
                         </div> --}}

                         <div class="form-group col-12 mt-3 d-flex gap-2"
                             style="    display: flex
;
    justify-content: end;
    align-items: end;">
                             <button class="btn btn-primary btn-sm">@lang('Thêm mới')</button>
                             <a href="javascript:void(0)"
                                 class="btn btn-outline-secondary btn-sm btn-reset">@lang('Đặt lại')</a>
                             <button type="button" class="btn btn-danger btn-sm"
                                 data-bs-dismiss="modal">@lang('Huỷ')</button>
                         </div>

                     </div>
                 </form>

             </div>
         </div>
     </div>
 </div>

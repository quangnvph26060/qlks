<div class="modal fade productModal" id="productModal" tabindex="-1" aria-labelledby="productModalLabel"
aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content add-serve-mobi">
        <div class="modal-header">
            <h5 class="modal-title" id="productModalLabel">Thêm sản phẩm </h5>
            <button type="button" class="close close_model" data-dismiss="modal" aria-label="Close" >
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.premium.service.save-product') }}" class="add-service-form"
                method="POST">
                @csrf
                <div class="row append-service">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="service_date">Ngày phục vụ</label>
                            <input type="text" class="form-control" name="product_date"
                                value="{{ todaysDate() }}" required readonly>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="room_number">Số phòng</label>
                            <input type="text" class="form-control room_serive" name="room_number"
                                required readonly>
                        </div>
                    </div>
                </div>


                    {{-- <div class="form-group d-flex justify-content-end">
                        <button type="button" class="btn btn-success addProductBtn"><i
                                class="las la-plus"></i> Thêm</button>
                    </div> --}}
                    <label for="services">Danh sách sản phẩm</label>
                    <div class="row product-wrapper">
                        <div class="first-product-wrapper row">
                            <div id="products" class="col-md-5 mb-3 row">

                            </div>
                            <div class="service-item col-md-7 position-relative mb-3 flex-wrap-mobi" id="list-product">
                                {{-- <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                      <div class="form-group">
                                            <select class="w-260px custom-select no-right-radius" name="product[]"
                                            required>
                                            </select>
                                      </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                        <input class="form-control no-left-radius w-260px h-40" name="qty[]"
                                            placeholder="@lang('Số lượng')" required type="text">
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>



                    <div class="form-group" style="float: inline-end">
                        <button type="submit" class=" btn-primary-1 w-10">Xác nhận</button>
                    </div>

            </form>
        </div>

    </div>
</div>
</div>

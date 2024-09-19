<div class="modal fade serviceModal" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel"
aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content" style="width: 50%;">
        <div class="modal-header">
            <h5 class="modal-title" id="serviceModalLabel">Thêm dịch vụ cao cấp</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.premium.service.save') }}" class="add-service-form"
                method="POST">
                @csrf
                <div class="row append-service">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="service_date">Ngày phục vụ</label>
                            <input type="text" class="form-control" name="service_date"
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

                <div class="form-group">
                    <div class="form-group d-flex justify-content-end">
                        <button type="button" class="btn btn-success addServiceBtn"><i
                                class="las la-plus"></i> Thêm</button>
                    </div>
                    <label for="services">Dịch vụ</label>
                    <div class="service-wrapper">
                        <div class="first-service-wrapper">
                            <div class="d-flex service-item position-relative mb-3 flex-wrap">
                                <div class="row w-100">
                                    <div class="col-md-6">
                                        <select class="custom-select no-right-radius w-100" name="services[]"
                                            required>


                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control no-left-radius w-100 h-40" name="qty[]"
                                            placeholder="@lang('Số lượng')" required type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                    <div class="form-group">
                        <button type="submit" class=" btn-primary-1 w-100">Xác nhận</button>
                    </div>
                
            </form>
        </div>

    </div>
</div>
</div>
<div class="modal-content" style="width: 1200px;">
    <div class="modal-header">
        <h5 class="modal-title" id="pricingModalLabel">Thêm cài đặt tính giá</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form id="btn-setupPrice-submit" action="{{ route('admin.manage.addPriceRoomType') }}" method="POST">
            @csrf
            <div class="row">
                <div class="row-box col-6">
                    <div class="box">
                        <label for="priceCode" class="form-label">Mã bảng giá</label>
                        <input type="text"  class="form-control" id="priceCode" placeholder="Nhập mã bảng giá"
                            name="price_code">
                        <span class="invalid-feedback d-block" style="font-weight: 500" id="priceCode_error"></span>
                    </div>

                    <div class="box">
                        <label for="priceName" class="form-label">Tên bảng giá</label>
                        <input type="text" class="form-control" id="priceName" name="price_name"
                            placeholder="Nhập tên bảng giá">
                        <span class="invalid-feedback d-block" style="font-weight: 500" id="priceName_error"></span>
                    </div>

                    <div class="box">
                        <label for="priceNote" class="form-label">Ghi chú</label>
                        <input type="text" class="form-control" id="priceNote" name="description"
                            placeholder="Nhập tên bảng giá">
                        <span class="invalid-feedback d-block" style="font-weight: 500" id="priceNote_error"></span>
                    </div>
                    {{-- <div class="box">
                        <label for="dayType" class="form-label">Chi nhánh</label>
                        <select class="form-select" id="hotel">
                            <option value="normal" selected>Ngày thường</option>
                            <option value="weekend">Ngày cuối tuần</option>
                            <option value="holiday">Ngày lễ</option>
                        </select>
                    </div> --}}
                </div>
                <div class="row-box col-6">
                    {{-- <div class="row">
                        <div class="col-lg-6">
                            <label for="startDate" class="form-label">Hiệu lực từ</label>
                            <input type="date" class="form-control" id="startDate" name="effective_start_date">
                            <span class="invalid-feedback d-block" style="font-weight: 500" id="startDate_error"></span>
                        </div>
                        <div class="col-lg-6">
                            <label for="endDate" class="form-label">Đến</label>
                            <input type="date" class="form-control" id="endDate" name="effective_end_date">
                            <span class="invalid-feedback d-block" style="font-weight: 500" id="endDate_error"></span>
                        </div>
                    </div> --}}

                    <div class="row">
                        <div class="col-lg-6">
                            <label for="startDate" class="form-label">Thời gian nhận</label>
                            <input type="time" class="form-control" id="checkInTime" name="check_in_time">
                            <span class="invalid-feedback d-block" style="font-weight: 500"
                                id="checkInTime_error"></span>
                        </div>
                        <div class="col-lg-6">
                            <label for="endDate" class="form-label">Thời gian trả</label>
                            <input type="time" class="form-control" id="checkOutTime" name="check_out_time">
                            <span class="invalid-feedback d-block" style="font-weight: 500"
                                id="checkOutTime_error"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="box">
                            <label for="roundTime" class="form-label">Làm tròn thời gian</label>
                            <input type="text" class="form-control" id="roundTime" name="round_time"
                                placeholder="Nhập số phút làm tròn">
                            <span class="invalid-feedback d-block" style="font-weight: 500"
                                id="roundTime_error"></span>
                        </div>
                    </div>


                    <div class="box">
                        <label for="dayType" class="form-label">Hình thức</label>
                        <select class="form-select" id="dayType">
                            <option value="">Chọn hình thức</option>
                            <option value="rank">Thứ</option>
                            <option value="holiday">Ngày</option>
                            <option value="time">Giờ</option>
                        </select>
                        <span class="invalid-feedback d-block" style="font-weight: 500" id="dayType_error"></span>
                    </div>
                    <div class="box mt-3">
                        <div id="checkboxContainer" class="checkbox-list">


                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-success btn-setupPrice">Lưu</button>
    </div>
</div>

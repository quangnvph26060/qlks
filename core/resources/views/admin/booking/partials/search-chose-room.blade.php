<div class="modal-header">
    <h5 class="modal-title">Chọn Phòng</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class=" mt-2 d-flex mb-2 response-mobi" style="gap: 10px;justify-content: space-around;">
    <div class="">
        <label for="">Chọn loại phòng</label>
        <select class="form-select" id="selected-hang-phong">


        </select>
    </div>
    <div class="">
        <label for="">Chọn tên phòng</label>
        <select class="form-select" id="selected-name-phong">

        </select>
    </div>
    <div class="">
        <label for="">Từ ngày</label>
        <input type="date" class="form-control " id="date-chon-phong-in" style="height: 38px">
    </div>
    <div class="">
        <label for="">Đến ngày</label>
        <input type="date" class="form-control" id="date-chon-phong-out" style="height: 38px">
    </div>
    <div class="">
        <label for="">Trạng thái phòng</label>
        <select class="form-select" id="status-room">
            <option value="">Chọn trạng tên phòng</option>
            <option value="Trống">Trống</option>
            <option value="Đã đặt">Đã đặt</option>
            <option value="Đã nhận">Đã nhận</option>
        </select>
    </div>
</div>
<style>
    @media (max-width: 768px) {
    .response-mobi {
        flex-direction: column;
    }
}
</style>
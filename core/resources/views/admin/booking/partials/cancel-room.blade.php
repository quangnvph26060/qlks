<div class="modal fade" id="cancelroom" tabindex="-1" aria-labelledby="cancelroomLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="width: 500px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelroomLabel">Hủy đặt phòng</h5>
                
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p style="font-size: 13px">Bạn có chắc chắn muốn hủy bỏ đơn đặt phòng này của khách hàng?</p>
                <div class="row">
                    <div class="col-md-3">
                            <p style=" text-align: right; margin-top: 7px">Lý do hủy:</p>
                    </div>  
                    <div class="col-md-9" style="gap: 10px">
                            <select class="form-select select-cancel-room" name="" id="">
                                <option value="0">Khác</option>
                                <option value="1">Khách không đến</option>
                            </select>
                            <textarea name="note-cancel-room" id="" cols="10" rows="2" class="mt-2" placeholder="Nhập lý do..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bỏ qua</button>
                <button type="button" class="btn btn-success">Đồng ý</button>
            </div>
        </div>
    </div>
</div>
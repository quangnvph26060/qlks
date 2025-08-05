<div class="modal fade" id="roomStatusModal" tabindex="-1" aria-labelledby="roomStatusModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow modal-content-room-clean" style="width: 35%;">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="roomStatusModalLabel">Chuyển trạng thái buồng phòng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0 fs-5">
                            Chuyển trạng thái buồng phòng <strong></strong> thành
                            <span class="text-danger fw-bold status-text"></span>?
                        </p>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Bỏ qua</button>
                        <button type="button" class="btn btn-primary change_clean_room_btn">Đồng ý</button>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .modal-content-room-clean{
                height: 25vh !important;
            }
        </style>
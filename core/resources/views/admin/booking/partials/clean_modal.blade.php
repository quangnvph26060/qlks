<div class="modal fade" id="dynamicModal" tabindex="-1" aria-labelledby="dynamicModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog1 ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dynamicModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Nội dung modal sẽ được cập nhật bằng JS -->
                <p id="modalRoomInfo">Đang tải dữ liệu...</p>
            </div>
            <div class="modal-footer">
                <a   href="javascript:void(0)"  class="btn btn-primary btn-clean" >Đồng ý</a>
                {{-- <button type="button" class="btn btn-primary btn-clean" data-dismiss="modal">Đồng ý</button> --}}
            </div>
        </div>
    </div>
</div>

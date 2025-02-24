<div class="modal fade" id="addBookedRoom" tabindex="-1" aria-hidden="true" style="overflow: unset">
    <div class="modal-dialog modal-dialog-centered" style="top: 4px">
        <div class="modal-content" style="height: 100vh;">
            <div class="modal-header">
                <h5 class="modal-title">Chọn đặt phòng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class=" mt-3" style="    padding: 0px 15px">
                <div class="search-container">
                    {{-- <select class="form-select" id="selected-customer-source">

                    </select> --}}
                    <input type="text" class="form-control" id="search_input_booked" placeholder="Tìm kiếm">

                </div>
            </div>
            <div class="modal-body overflow-add-room">
                <table class="table mt-10" id="data-table">
                    <thead>
                        <tr>
                            <th data-table="Mã đặt phòng" class="text-left">Mã đặt phòng</th>
                            <th data-table="Số lượng phòng" class="text-right">Số lượng phòng</th>
                            <th data-table="Ngày chứng từ" class="text-right">Ngày chứng từ</th>
                            <th data-table="Tên khách hàng" class="text-left">Tên khách hàng</th>
                            <th data-table="Số điện thoại" class="text-right">Số điện thoại</th>
                            <th data-table="Số lượng người" class="text-right">Số lượng người</th>
                            <th data-table="Tổng tiền" class="text-right">Tổng tiền</th>
                            <th data-table="Tổng đặt cọc" class="text-right">Tổng đặt cọc</th>
                            <th data-table="Thao tác">Thao tác</th>
                        </tr>
                    </thead>

                    <tbody id="show-booked-room">

                    </tbody>

                </table>
            </div>
            <div class="d-flex justify-content-end" style="gap: 10px;padding: 7px 31px">
                <p data-row="booked" class=" btn-dat-truoc  add-booked-room-form" style="cursor: pointer">Lưu
                </p>
                <p type="button" data-row="booked" class="alert-paragraph close_modal_booked_room">Hủy</p>
            </div>
        </div>
    </div>
</div>
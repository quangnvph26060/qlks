<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-hidden="true" style="overflow: unset">
    <div class="modal-dialog modal-dialog-centered" style="top: 4px">
        <div class="modal-content" style="height: 100vh;">
            <div class="modal-header">
                <h5 class="modal-title">Chọn khách hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class=" mt-3" style="    padding: 0px 15px">
                <div class="search-container">
                    <select class="form-select" id="selected-customer-source">

                    </select>
                    <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm">

                </div>
            </div>
            <div class="modal-body overflow-add-room">
                <table class="table--light style--two  table mt-10" id="data-table">
                    <thead>
                        <tr>
                            <th data-table="Mã khách hàng" class="text-left">Mã khách hàng</th>
                            <th data-table="Tên khách hàng" class="text-left">Tên khách hàng</th>
                            <th data-table="Số điện thoại" class="text-right">Số điện thoại</th>
                            <th data-table="Nguồn khách hàng" class="text-left">Nguồn khách hàng</th>
                            <th data-table="Thao tác">Thao tác</th>
                        </tr>
                    </thead>

                    <tbody id="show-customer">

                    </tbody>

                </table>
            </div>
            <div class="d-flex justify-content-end" style="gap: 10px;padding: 7px 31px">
                <p data-row="booked" class=" btn-dat-truoc  add-customer-booked" style="cursor: pointer">Lưu
                </p>
                <p type="button" data-row="booked" class="alert-paragraph close_modal_booked_room">Hủy</p>
            </div>
        </div>
    </div>
</div>
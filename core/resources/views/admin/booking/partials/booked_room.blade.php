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
                    <div style="  position: absolute;  right: 43px; display: flex;    gap: 10px;">
                        <button onclick="toggleView('viewBox')" data-view="viewBox" class="btn btn-primary btn-submit-search-book btn-toggle-view">
                            <svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="24" height="24">
                                <path fill="white" d="M5.75 7.5h7.5a.75.75 0 0 1 0 1.5h-7.5a.75.75 0 0 1 0-1.5Zm0 5h7.5a.75.75 0 0 1 0 1.5h-7.5a.75.75 0 0 1 0-1.5Zm-4-10h6.5a.75.75 0 0 1 0 1.5h-6.5a.75.75 0 0 1 0-1.5ZM2 14a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-6a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm10.314-3.082L11.07 2.417A.25.25 0 0 1 11.256 2h4.488a.25.25 0 0 1 .186.417l-2.244 2.5a.25.25 0 0 1-.372 0Z"></path>
                            </svg>
                        </button>
                        
                        <button onclick="toggleView('viewModel')" data-view="viewModel" class="btn btn-primary btn-submit-search-book btn-toggle-view">
                            <svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                <path fill="white" d="M1.75 2.5h10.5a.75.75 0 0 1 0 1.5H1.75a.75.75 0 0 1 0-1.5Zm4 5h8.5a.75.75 0 0 1 0 1.5h-8.5a.75.75 0 0 1 0-1.5Zm0 5h8.5a.75.75 0 0 1 0 1.5h-8.5a.75.75 0 0 1 0-1.5ZM2.5 7.75v6a.75.75 0 0 1-1.5 0v-6a.75.75 0 0 1 1.5 0Z"></path>
                            </svg>
                        </button>
                        
                    </div>
                </div>
            </div>
            
            <div class="modal-body overflow-add-room">
                <table class="table--light style--two table mt-10" id="data-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th data-table="Mã đặt phòng" class="text-left">Mã đặt phòng</th>
                            <th data-table="Số lượng phòng" class="text-right">Số lượng phòng</th>
                            <th data-table="Ngày chứng từ" class="text-right">Ngày chứng từ</th>
                            <th data-table="Tên khách hàng" class="text-left">Tên khách hàng</th>
                            <th data-table="Số điện thoại" class="text-right">Số điện thoại</th>
                            <th data-table="Số lượng người" class="text-right">Số lượng người</th>
                            <th data-table="Tổng tiền" class="text-right">Tổng tiền</th>
                            <th data-table="Tổng đặt cọc" class="text-right">Tổng đặt cọc</th>
                            <th data-table="Tổng đặt cọc" class="text-right">Tổng giảm giá</th>
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
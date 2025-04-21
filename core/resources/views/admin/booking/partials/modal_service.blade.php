<div class="modal fade" id="serviceModal" tabindex="-1" style="z-index: 100000">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="width: 60%;">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title">Thêm sản phẩm, dịch vụ</h5>
                    <small class="text-muted service-customer"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="service-modal-body py-2 px-2">
                <div class="row">
                    <!-- Left: Danh sách sản phẩm -->
                    <div class="col-md-4">
                        <input type="text" class="form-control mb-3" placeholder="Tìm kiếm..." id="searchServiceInput">
                        <input type="hidden" class="form-control mb-3" placeholder="Tìm kiếm..." id="check_in_id_service">
                        <input type="hidden" class="form-control mb-3" placeholder="Tìm kiếm..." id="room_code_service">
                        <div class="btn-group mb-3" role="group">
                            <button class="btn btn-outline-primary active" onclick="filterItems('Tất cả')">Tất cả</button>
                            <button class="btn btn-outline-primary" onclick="filterItems('premium_service')">Dịch vụ</button>
                            <button class="btn btn-outline-primary" onclick="filterItems('product')">Đồ ăn, đồ uống</button>
                        </div>

                        <div class="row row-cols-2 g-2 scroll-service" id="productList">
                            <!-- Render product here -->
                        </div>
                    </div>

                    <!-- Right: Danh sách đã chọn -->
                    <div class="col-md-8">
                        <h6><strong class="service-room-number"></strong></h6>
                        <div class="service-selected-list px-2 py-2" id="selectedItems">
                            <div class="text-muted">Chưa có dịch vụ, sản phẩm</div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 py-2 ">
                                 <p class="fw-bold text-dark">Tổng cộng: </p>
                            </div>
                            <div class="col-md-7 py-2">
                             <p class="total_product_service_display">  </p>
                        </div>
                    </div>
                </div>
              
            </div>
          
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Bỏ qua</button>
                <button class="btn btn-primary btn-add-service">Lưu</button>
            </div>
        </div>
    </div>
</div>

<style scoped>
    .scroll-service{
        max-height: 39vh;
        overflow-y: auto;
    }
    .service-modal-body {
        /* max-height: 70vh; */
        /* overflow-y: auto; */
    }

    .service-product-item {
        cursor: pointer;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        text-align: center;
        padding: 10px;
        transition: 0.2s;
    }

    .service-product-item:hover {
        background-color: #f1f1f1;
    }

    .service-product-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        margin-bottom: 5px;
        border-radius: 6px;
    }

    .service-selected-list {
        max-height: 300px;
        overflow-y: auto;
        height: 300px;
    }

    .service-selected-list input[type="number"] {
        padding: 2px 6px;
    }
</style>

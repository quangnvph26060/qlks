@foreach ($roomType as $type)
    <div class="card p-3" style="max-width: 100%;">
        <div class="row g-0">
            <div class="col-custom-md-4 position-relative">
                <img src="core/public/storage/roomTypeImage/172741170266f635f6128d1.jpg" class="responsive-image rounded"
                    alt="Hotel Image">
                <button class="btn btn-light position-absolute top-0 end-0 m-2 rounded-circle"
                    style="padding:0.375rem 0.75rem">
                    <i class="far fa-heart"></i>
                </button>
            </div>
            <div class="col-md-6">
                <div class="card-body py-1">
                    <h5 class="card-title text-primary fw-bold">
                        BIDV Central Da Lat Hotel
                        <span class="text-warning ms-2">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </span>
                    </h5>
                    <p class="text-muted mb-1">
                        <a href="#" class="text-custom">Đà Lạt</a> • <a href="#" class="text-custom">Xem
                            trên bản đồ</a> •
                        Cách trung tâm 0,7km
                    </p>
                    <p class="card-text amenities">
                        <label for="" class="text-muted me-1 fw-bold">Tiện nghi: </label><span
                            class="badge bg-secondary ms-2">Thịt
                            chó</span><span class="badge bg-secondary ms-1">Mắm tôm</span><span
                            class="badge bg-secondary ms-1">Lá mơ</span>
                    </p>
                    <p class="card-text utilities">
                        <label for="" class="text-muted me-1 fw-bold">Tiện ích: </label><span
                            class="badge bg-secondary ms-2">Bia</span><span
                            class="badge bg-secondary ms-1">Rượu</span><span class="badge bg-secondary ms-2">Cafe</span>
                    </p>
                    <p class="card-text utilities">
                        <label for="" class="text-muted me-1 fw-bold">Số lượng người(7): </label> <span>5 người
                            lớn | 2 trẻ em</span>
                    </p>
                    <p class="card-text utilities">
                        <span> <label for="" class="text-muted me-1 fw-bold mb-0">Mô tả ngắn: </label>Lorem
                            ipsum dolor, sit amet consectetur
                            adipisicing elit. Alias magni quis soluta nemo at tempore! Autem voluptate rerum qui
                            molestiae. Ullam voluptates eius facilis, neque aliquid vitae numquam minima tempora.
                        </span>
                    </p>

                </div>

            </div>
            <div class="col-custom-md-2 ps-3 border-start d-flex flex-column justify-content-center">
                <!-- Giá phòng -->
                <div class="price-info my-3 ">
                    <h6 class="text-danger fw-bold">2.500.000 VND</h6>
                </div>

                <!-- Chính sách -->
                <div class="policy-info mb-3">
                    <p class="small mb-1 text-muted">
                        <i class="fas fa-check-circle text-success me-1"></i>Miễn phí hủy
                    </p>
                    <p class="small text-muted">
                        <i class="fas fa-utensils me-1"></i>Bao gồm bữa sáng
                    </p>
                </div>

                <!-- Nút đặt phòng -->
                <a class="btn btn-sm btn--base" href="{{ route('room.type.details', $type->slug) }}">
                    <i class="la la-desktop me-2"></i>@lang('Đặt ngay')
                </a>

                <!-- Nút yêu thích -->
                <button class="btn btn-outline-danger btn-sm mt-2">
                    <i class="far fa-heart me-1"></i>Thêm vào yêu thích
                </button>
            </div>

        </div>
    </div>
@endforeach

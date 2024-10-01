
@foreach ($data ?? [] as $key => $room)
    <div class="card p-3 room-item" style="max-width: 100%;">
        <div class="row g-0">
            <div class="col-custom-md-4 col-sm-5 col-custom-ssm-5 col-custom-sssm-5 position-relative">
                <img src="{{ \Storage::url($room->main_image) }}" class="responsive-image rounded"
                    alt="Hotel Image">
                <button data-id="{{ $room->id }}"
                    class="cancelWishlistBtn btn btn-light position-absolute top-0 end-0 m-2 rounded-circle {{ Auth::check() && $room->wishlist ? 'text-white bg-danger' : '' }}"
                    id="show-wishlist-{{ $room->id }}" style="padding:0.375rem 0.75rem">
                    <i class="far fa-heart"></i>
                </button>
            </div>
            <div class="col-md-8 col-lg-6 col-sm-7 col-custom-ssm-7 col-custom-sssm-7">
                <div class="card-body  py-1">
                    <h3 class=" text-primary fw-bold ">
                        {{ $room->room_number }}
                        <span class="text-warning ms-2 rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </span>
                    </h3>
                    <span class="text-muted room-type">({{ $room->roomType->name }})</span>
                    <p class="card-text amenities">
                        <label for="" class="text-muted me-1 fw-bold">Tiện nghi: </label>
                        <span class="badge-container">
                            @if ($room->amenities)
                                @foreach ($room->amenities as $amenity)
                                    <span class="badge bg-secondary ms-1">{{ $amenity->title }}</span>
                                @endforeach
                            @else
                                <span class="badge bg-secondary ms-1">Đang cập nhật</span>
                            @endif
                        </span>
                    </p>
                    <p class="card-text utilities">
                        <label for="" class="text-muted me-1 fw-bold">Tiện ích:</label>
                        <span class="badge-container">
                            @if ($room->facilities)
                                @foreach ($room->facilities as $utility)
                                    <span class="badge bg-secondary ms-1">{{ $utility->title }}</span>
                                @endforeach
                            @else
                                <span class="badge bg-secondary ms-1">Đang cập nhật</span>
                            @endif
                        </span>
                    </p>
                    <p class="card-text quality">
                        <label for="" class="text-muted me-1 fw-bold">Số lượng
                            người({{ $room->total_adult + $room->total_children }}): </label>
                        <span>{{ $room->total_adult }} người
                            lớn | {{ $room->total_children ?? 0 }} trẻ em</span>
                    </p>
                    <p class="card-text sort-description">
                        <span> <label for="" class="text-muted me-1 fw-bold mb-0">Mô tả ngắn: </label>
                            {!! $room->description ?? 'Đang cập nhật...' !!}
                        </span>
                    </p>

                    <div class="border-start d-flex flex-column justify-content-center d-lg-custom-none">
                        <!-- Giá phòng -->
                        <div class="price-info mb-1">
                            <h6 class="text-danger fw-bold">2.500.000 VND</h6>
                        </div>

                        <!-- Chính sách -->
                        <div class="policy-info mb-3  d-md-custom-none">
                            <p class="small mb-1 text-muted">
                                <i class="fas fa-check-circle text-success me-1"></i>Miễn phí hủy
                            </p>
                            <p class="small text-muted">
                                <i class="fas fa-utensils me-1"></i>Bao gồm bữa sáng
                            </p>
                        </div>

                        <!-- Nút đặt phòng -->
                        <a class="btn btn-sm btn--base" href="{{ route('room.type.details', $room->roomType->slug) }}">
                            <i class="la la-desktop me-2"></i>@lang('Đặt ngay')
                        </a>
                    </div>

                </div>

            </div>
            <div class="col-custom-md-2 ps-3 border-start d-flex flex-column justify-content-center d-custom-none">
                <!-- Giá phòng -->
                <div class="price-info my-3 ">
                    <h6 class="text-danger fw-bold">{{ showAmount($room->roomPricesActive->first()->price) }}</h6>
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
                <a class="btn btn-sm btn--base" href="{{ route('room.type.details', $room->room_number) }}">
                    <i class="la la-desktop me-2"></i>@lang('Đặt ngay')
                </a>

                <!-- Nút yêu thích -->
                <button class="btn btn-outline-danger btn-sm mt-2 addWishlistBtn" data-id="{{ $room->id }}">
                    <i class="far fa-heart me-1"></i>Yêu thích
                </button>
            </div>

        </div>
    </div>
@endforeach

@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="section">
        <div class="container" style="max-width:1100px">
            <div class="row">
                <div class="col-lg-3 col-xl-3 d-custom-md-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title m-0">@lang('Chọn lọc theo:')</h5>
                        </div>
                        <div class="card-body border-bottom">
                            <h6 class="card-text mb-2">Ngân sách của bạn (mỗi đêm)</h6>
                            <div class="range-value mb-2">
                                <span id="priceMin">VNĐ 50.000</span> - <span id="priceMax">VNĐ 2.000.000+</span>
                            </div>
                            <input type="text" id="priceSlider" name="price" value="" />
                        </div>
                        <div class="card-body">
                            <h6 class="card-text mb-2">Tiện nghi</h6>
                            <div id="amenities">
                                <div class="form-check">
                                    <div class="d-flex justify-content-between alion-items-center">
                                        <div>
                                            <input class="form-check-input" type="checkbox" id="parking" />
                                            <label class="form-check-label" for="parking">Chỗ đỗ xe</label>
                                        </div>
                                        <div>
                                            <span class="badge bg-secondary">1015</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <input class="form-check-input" type="checkbox" id="restaurant" />
                                            <label class="form-check-label" for="restaurant">Nhà hàng</label>
                                        </div>
                                        <div>
                                            <span class="badge bg-secondary">162</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <input class="form-check-input" type="checkbox" id="petFriendly" />
                                            <label class="form-check-label" for="petFriendly">Cho phép mang theo vật
                                                nuôi</label>
                                        </div>
                                        <div>
                                            <span class="badge bg-secondary">309</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <input class="form-check-input" type="checkbox" id="roomService" />
                                            <label class="form-check-label" for="roomService">Dịch vụ phòng</label>
                                        </div>
                                        <div>
                                            <span class="badge bg-secondary">657</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <input class="form-check-input" type="checkbox" id="24HourService" />
                                            <label class="form-check-label" for="24HourService">Lễ tân 24 giờ</label>
                                        </div>
                                        <div>
                                            <span class="badge bg-secondary">667</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-link mt-3 p-0" id="toggleAmenities">Hiển thị tất cả 15 loại ▼</button>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-12 col-md-12">
                    <div class="row gy-4 justify-content-center">
                        @if ($roomTypes->count())
                            @include($activeTemplate . 'partials.room_cards', [
                                'roomType' => $roomTypes,
                                'class' => 'col-xl-4 col-md-6 col-xs-10',
                            ])
                        @else
                            <div class="col-md-9">
                                <div class="card custom--card border-0">
                                    <div class="card-body empty-message">
                                        <i class="la la-lg la-10x la-frown text--warning"></i>
                                        <span class="text--muted mt-3">{{ __($emptyMessage) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.0/js/ion.rangeSlider.min.js"></script>

    <script>
        $(document).ready(function() {
            let amenitiesVisible = false;

            $('#toggleAmenities').on('click', function() {
                if (!amenitiesVisible) {
                    // Thêm 15 tiện nghi mới
                    const newAmenities = [{
                            id: 'wifi',
                            label: 'Wifi miễn phí',
                            count: 1200
                        },
                        {
                            id: 'pool',
                            label: 'Hồ bơi',
                            count: 450
                        },
                        {
                            id: 'gym',
                            label: 'Phòng tập gym',
                            count: 300
                        },
                        {
                            id: 'spa',
                            label: 'Dịch vụ spa',
                            count: 200
                        },
                        {
                            id: 'breakfast',
                            label: 'Bữa sáng miễn phí',
                            count: 800
                        },
                        {
                            id: 'bar',
                            label: 'Quầy bar',
                            count: 150
                        },
                        {
                            id: 'shuttle',
                            label: 'Dịch vụ đưa đón',
                            count: 90
                        },
                        {
                            id: 'kitchen',
                            label: 'Bếp chung',
                            count: 75
                        },
                        {
                            id: 'luggage',
                            label: 'Giữ hành lý',
                            count: 50
                        },
                        {
                            id: 'laundry',
                            label: 'Dịch vụ giặt ủi',
                            count: 30
                        },
                        {
                            id: 'conference',
                            label: 'Phòng hội nghị',
                            count: 20
                        },
                        {
                            id: 'nonSmoking',
                            label: 'Phòng không hút thuốc',
                            count: 10
                        },
                        {
                            id: 'fireplace',
                            label: 'Lò sưởi',
                            count: 5
                        },
                        {
                            id: 'view',
                            label: 'Cảnh đẹp',
                            count: 15
                        },
                        {
                            id: 'security',
                            label: 'An ninh 24/7',
                            count: 100
                        }
                    ];

                    newAmenities.forEach(amenity => {
                        const div = `
                        <div class="form-check">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <input class="form-check-input" type="checkbox" id="${amenity.id}" />
                                    <label class="form-check-label" for="${amenity.id}">${amenity.label}</label>
                                </div>
                                <div>
                                    <span class="badge bg-secondary">${amenity.count}</span>
                                </div>
                            </div>
                        </div>
                    `;
                        $('#amenities').append(div);
                    });

                    $(this).text('Thu gọn ▲');
                } else {
                    // Xóa các tiện nghi mới
                    $('#amenities .form-check:gt(4)').remove(); // Xóa từ tiện nghi thứ 5 trở đi

                    $(this).text('Hiển thị tất cả 15 loại ▼');
                }

                amenitiesVisible = !amenitiesVisible;
            });
        });

        function formatCurrency(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        $("#priceSlider").ionRangeSlider({
            min: 50000,
            max: 2000000,
            from: 50000,
            to: 2000000,
            type: 'double',
            // prefix: "VNĐ ",
            grid: true,
            onUpdate: function(data) {
                // Cập nhật giá trị hiển thị khi kéo thanh trượt
                $('#priceMin').text("VNĐ " + formatCurrency(data.from));
                $('#priceMax').text("VNĐ " + formatCurrency(data.to) + "+");
            },
            onFinish: function(data) {
                // Cập nhật giá trị hiển thị khi chọn xong
                $('#priceMin').text("VNĐ " + formatCurrency(data.from));
                $('#priceMax').text("VNĐ " + formatCurrency(data.to) + "+");
            }
        });
    </script>
@endpush

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.0/css/ion.rangeSlider.min.css">
    <style>
        .responsive-image {
            max-width: 100%;
            height: auto;
        }

        @media (max-width: 768px) {

            .price-info {
                margin-bottom: 15px !important;
            }

            .utilities,
            .amenities {
                display: flex;
                /* Sử dụng flexbox để sắp xếp */
                align-items: center;
                /* Căn giữa theo chiều dọc */
            }

            .badge-container {
                display: flex;
                /* Sắp xếp các badge theo chiều ngang */
                overflow-x: auto;
                /* Cho phép cuộn ngang */
                overflow-y: hidden;
                /* Ẩn cuộn dọc */
                white-space: nowrap;
                /* Ngăn không cho văn bản xuống dòng */
                max-width: 100%;
                /* Chiều rộng tối đa */
                scrollbar-width: none;
                /* Ẩn thanh cuộn trên Firefox */
            }

            .badge-container::-webkit-scrollbar,
            .rating {
                display: none;
                /* Ẩn thanh cuộn trên Chrome, Safari, và Edge */
            }



            .text-muted {
                white-space: nowrap;
                /* Ngăn không cho chữ 'Tiện ích' xuống dòng */
            }



            .d-custom-none {
                display: none !important;
            }

            .sort-description {
                display: none;
            }

            .border-start {
                border-left: none !important;
            }
        }

        /* Khi màn hình nhỏ hơn 576px (mobile) */
        @media (max-width: 576px) {

            .card-body {
                padding-right: 0 !important;
            }

            .price-info {
                margin-bottom: 14px !important;
            }

            .quality,
            .policy-info {
                display: none !important;
            }

            .col-custom-ssm-5 {
                flex: 0 0 auto;
                width: 30.33333333% !important;
            }

            .col-custom-ssm-7 {
                flex: 0 0 auto;
                width: 69.33333333% !important;
            }


        }

        @media (max-width:550px) {
            .btn.btn-sm.btn--base {
                margin-top: -5px;
            }

            .rating {
                margin: 5px 0 0px !important;
            }

            .col-custom-sssm-5 {
                flex: 0 0 auto;
                width: 37.33333333% !important;
            }

            .col-custom-sssm-7 {
                flex: 0 0 auto;
                width: 62.33333333% !important;
            }

            .card {
                border-top: none !important;
                border-left: none !important;
                border-right: none !important;
                border-bottom: 1px solid #dee2e6 !important;
                margin: 0;
            }

            .d-custom-sssm-none {
                display: none !important;
            }


        }

        .section {
            padding-top: clamp(50px, 4vw, 100px);
        }

        .card {
            border: 1px solid #dee2e6;
        }

        label,
        #toggleAmenities {
            font-size: .75rem;
        }


        .form-check span {
            font-size: .6rem !important;
        }

        .card-title {
            font-size: 16px;
        }

        .card-text,
        .text-muted.mb-1 {
            font-size: 12px;
        }

        .end-0 {
            right: 1px !important;
        }

        .fa-star {
            font-size: 14px
        }

        .text-custom {
            text-decoration: underline;
            color: #007bff;
            font-weight: bold;
        }

        .d-block.text-muted {
            font-size: 12px;
        }

        .card-body.border-bottom {
            font-size: 12px;
        }

        @media (max-width: 1200px) {
            .d-custom-md-none {
                display: none;
            }
        }

        @media (min-width: 990px) {

            .d-lg-custom-none {
                display: none !important;
            }

            .col-custom-md-4 {
                flex: 0 0 auto !important;
                width: 30.33333333% !important;
            }

            .col-custom-md-2 {
                flex: 0 0 auto !important;
                width: 19.33333333% !important;
            }
        }

        @media (min-width: 768px) {
            .col-custom-md-4 {
                flex: 0 0 auto;
                width: 25.33333333%;
            }

            .col-custom-md-2 {
                flex: 0 0 auto;
                width: 24.33333333%;
            }

            .d-md-custom-none {
                display: none !important;
            }
        }
    </style>
@endpush

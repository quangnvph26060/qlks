@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">@lang('Chọn lọc theo:')</h5>
                        </div>
                        <div class="card-body border-bottom">
                            <h6 class="card-text mb-2" style="font-size: 14px;">Ngân sách của bạn (mỗi đêm)</h6>
                            <div class="range-value mb-2">
                                <span id="priceMin">VNĐ 50.000</span> - <span id="priceMax">VNĐ 2.000.000+</span>
                            </div>
                            <input type="text" id="priceSlider" name="price" value="" />
                        </div>
                        <div class="card-body">
                            <h6 class="card-text mb-2" style="font-size: 14px;">Tiện nghi</h6>
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
                <div class="col-md-8">
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
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1110px;
            margin: 0 auto;
        }

        .item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .item img {
            max-width: 40%;
            height: auto;
            display: block;
            width: 240px;
            height: 240px;
        }

        .info {
            margin-left: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
            /* justify-content: space-between; */
        }

        .info h2 {
            margin: 0 0 10px;
            color: #333;
        }

        .info p {
            margin: 5px 0;
            line-height: 1.5;
            color: #666;
        }

        .price {
            font-weight: bold;
            color: #e67e22;
        }

        @media (max-width: 600px) {
            .item {
                flex-direction: column;
            }

            .item img {
                max-width: 100%;
            }

            .info {
                padding: 10px;
            }
        }

        .empty-message {
            text-align: center;
        }

        .empty-message span {
            font-size: 25px;
            display: block;
        }
    </style>
@endpush

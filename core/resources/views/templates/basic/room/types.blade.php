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
                        <div class="card-body border-bottom">
                            <h6 class="card-text mb-2">Tiện nghi</h6>
                            <div id="amenities">
                                <div class="form-check amenity">
                                    @foreach ($amenities as $amenity)
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <input class="form-check-input amenity-checkbox" type="checkbox"
                                                    id="amenity-{{ $amenity->id }}" value="{{ $amenity->id }}" />
                                                <label class="form-check-label"
                                                    for="amenity-{{ $amenity->id }}">{{ $amenity->title }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <h6 class="card-text mb-2">Tiện ích</h6>
                            <div id="facilities">
                                <div class="form-check facility">
                                    @foreach ($facilities as $facility)
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <input class="form-check-input facility-checkbox" type="checkbox"
                                                    id="facility-{{ $facility->id }}" value="{{ $facility->id }}" />
                                                <label class="form-check-label"
                                                    for="facility-{{ $facility->id }}">{{ $facility->title }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-xl-9 col-lg-12 col-md-12">
                    <div class="loader-overlay" style="display: none;">
                        <div class="loader"></div>
                    </div>
                    <div class="row gy-4 justify-content-center" id="results-container">
                        @if ($rooms->count())
                            @include($activeTemplate . 'partials.room_cards', [
                                'room' => $rooms,
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <script>
        $(document).ready(function() {


            function incrementBadge() {
                // Get the current value of the badge
                var currentCount = parseInt($(".notification-badge").html());
                // Increment the count
                $(".notification-badge").html(currentCount + 1);
            }

            function decrementBadge() {

                var currentCount = parseInt($(".notification-badge").html());
                $(".notification-badge").html(currentCount - 1);
            }

            function showMessage(name, message) {
                showMessageToast({
                    name: name, // Truyền trực tiếp giá trị của biến 'name'
                }, message); // Truyền thông điệp vào
            }

            // function formatCurrency(amount) {
            //     // Convert the number to a string and replace commas with dots
            //     return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' VND';
            // }

            // $(document).on('click', '.room-checkbox', function() {
            //     console.log(123);

            //     let id = $(this).data('id');

            //     $.ajax({
            //         url: "{{ route('user.handle.publish', ':id') }}".replace(':id', id),
            //         type: "POST",
            //         success: function(response) {
            //             if (response.status === 'success') {
            //                 $('.show-total').html(formatCurrency(response.total));
            //             }
            //         }
            //     })

            // })

            // var publish = 0;
            // // Nếu tất cả checkbox con đều được chọn
            // if ($('.room-checkbox:checked').length === $('.room-checkbox').length) {

            //     $('#select-all').prop('checked', true); // Đánh dấu checkbox "select-all"
            // } else {

            //     $('#select-all').prop('checked', false); // Bỏ chọn checkbox "select-all"
            // }


            // $('#select-all').on('change', function() {
            //     publish = $(this).prop('checked') ? 1 : 0;
            //     $('.room-checkbox').prop('checked', $(this).prop('checked'));

            //     $.ajax({
            //         url: "{{ route('user.handle.publish.all') }}" + '?publish=' + publish,
            //         type: "POST",
            //         success: function(response) {
            //             if (response.status === 'success') {
            //                 $('.show-total').html(formatCurrency(response.total));
            //             }
            //         }
            //     })
            // })

            $(document).on('click', '.addWishlistBtn', function() {
                let id = $(this).data('id');

                $.ajax({
                    url: "{{ route('user.toggle.wishlist', ':id') }}".replace(':id', id),
                    type: "POST",
                    success: function(response) {

                        if (response.status === 'success') {
                            $('.show-total').html(formatCurrency(response.total));
                            let wishlistButton = $(`#show-wishlist-${id}`);
                            let roomElement = $(`.room-${id}`);

                            if (wishlistButton.hasClass('text-white bg-danger')) {
                                roomElement.remove(); // Xóa phần tử
                                checkNotRoomWishlist();

                                wishlistButton.removeClass(
                                    'text-white bg-danger'); // Cập nhật nút

                                decrementBadge();
                                showMessage('error', 'Đã xóa phòng khỏi danh sách yêu thích');

                                // Kiểm tra xem còn phòng nào không
                            } else {
                                appendToWishlist(response.data);
                                wishlistButton.addClass(
                                    'text-white bg-danger'); // Thêm vào danh sách yêu thích
                                incrementBadge();
                                showMessage('success', 'Đã thêm phòng vào danh sách yêu thích');

                                $('#wishlist-message').hide(); // Ẩn thông báo

                            }

                            updateBookingButton();

                        } else {
                            showMessageToast({
                                name: 'error'
                            }, response.message);
                        }
                    },
                    error: function(response) {
                        console.log(response);

                        showMessageToast({
                            name: 'error',
                            icon: 'las la-exclamation-circle'
                        }, response.responseJSON.message);
                    }
                });
            });


            let amenitiesVisible = false;

            function appendToWishlist(roomData) {

                const image_path = "{{ \Storage::url('') }}" + roomData.main_image;
                const wishlistContainer = $('.append-child');
                const checked = roomData.wish_list.publish == true ? 'checked' : '';


                const newRoomHtml = /*html*/ `
                    <div class="border rounded p-2 d-flex align-items-center mb-3 rooms room-${roomData.id}">
                        <input ${checked} type="checkbox" class="form-check-input me-2 room-checkbox" data-price="1500000" id="room-${roomData.id}">
                        <img src="${image_path}" alt="Room Image" class="rounded" style="max-width: 20%; object-fit: cover;">
                        <div class="ms-3">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-1">${roomData.room_number}</h5>
                                <p class="mb-1 ms-1 text-muted">(${roomData.room_type.name})</p>
                            </div>
                            <p class="mb-0 text-muted">Giá: ${roomData.room_prices_active[0].price}</p>
                        </div>
                    </div>
                    `;

                wishlistContainer.prepend(newRoomHtml);
            }

            const amenities = $('#amenities .amenity');
            const hiddenAmenities = $('#amenities .amenity:gt(7)'); // Ẩn các tiện nghi từ thứ 8 trở đi

            // Kiểm tra số lượng tiện nghi
            if (amenities.length > 8) {
                $('#toggleAmenities').removeClass('hidden');
            }

            hiddenAmenities.hide();

            $('#toggleAmenities').on('click', function() {
                hiddenAmenities.toggle();
                $(this).text($(this).text().includes('▼') ? 'Ẩn đi ▲' : 'Hiển thị tất cả ▼');
            });

            // Xử lý tiện ích
            const facilities = $('#facilities .amenity');
            const hiddenFacilities = $('#facilities .amenity:gt(7)'); // Ẩn các tiện ích từ thứ 8 trở đi

            // Kiểm tra số lượng tiện ích
            if (facilities.length > 8) {
                $('#toggleFacilities').removeClass('hidden');
            }

            hiddenFacilities.hide();

            $('#toggleFacilities').on('click', function() {
                hiddenFacilities.toggle();
                $(this).text($(this).text().includes('▼') ? 'Ẩn đi ▲' : 'Hiển thị tất cả ▼');
            });
        });

        let timeout = null;

        function filterRoom() {
            $('.loader-overlay').show();

            let priceMin = $('#priceMin').text().replace('VNĐ ', '').replace('.', '');
            let priceMax = $('#priceMax').text().replace('VNĐ ', '').replace('.', '');

            // Lấy các tiện ích đã được chọn
            let facilities = $('.facility-checkbox:checked').map(function() {
                return $(this).val(); // Lấy giá trị (ID) của checkbox đã chọn
            }).get();

            // Lấy các tiện nghi đã được chọn
            let amenities = $('.amenity-checkbox:checked').map(function() {
                return $(this).val(); // Lấy giá trị (ID) của checkbox đã chọn
            }).get();

            let data = {
                priceMin: priceMin,
                priceMax: priceMax,
                facilities: facilities,
                amenities: amenities
            };

            clearTimeout(timeout);
            timeout = setTimeout(function() {
                // Hiển thị loader

                $.ajax({
                    type: "GET",
                    url: "{{ route('basic.room.filter') }}",
                    data: data,
                    success: function(data) {
                        $('#results-container').empty();
                        $('#results-container').html(data.data);
                    },
                    complete: function() {
                        // Ẩn loader khi hoàn tất
                        $('.loader-overlay').hide();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText); // Xử lý lỗi
                        $('.loader-overlay').hide(); // Ẩn loader trong trường hợp lỗi
                    }
                });
            }, 500);
        }

        $(document).on('click', '.facility-checkbox', function() {

            filterRoom();

        })


        $(document).on('click', '.amenity-checkbox', function() {

            filterRoom();

        })



        function checkNotRoomWishlist() {

            if ($('.list-group div.rooms').length == 0) {
                console.log("no data");

                $('#wishlist-message').show();
            } else {
                console.log("has data");

                $('#wishlist-message').hide();
            }

        }

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

                filterRoom();

            }
        });
    </script>
@endpush

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.0/css/ion.rangeSlider.min.css">
    <style>
        /* Fullscreen overlay */
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(128, 128, 128, 0.5);
            /* Gray background with 50% opacity */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        /* Loader styles */
        .loader {
            border: 8px solid rgba(255, 255, 255, 0.3);
            /* Light border */
            border-top: 8px solid #ffffff;
            /* White border for rotating effect */
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        /* Spin animation */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }


        .hidden {
            display: none;
        }

        .room-type {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

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

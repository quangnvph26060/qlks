@extends('admin.layouts.app')
@section('panel')
    <div class="row">

    </div>
    <div class="row">
        <div class="col">
            <div class="status-buttons">
                <div class="status-button status-available-line">
                    <span class="status-dot"></span>
                    Đang trống (8)
                </div>
                <div class="status-button status-incoming-line">
                    <span class="status-dot"></span>
                    Sắp nhận (2)
                </div>
                <div class="status-button status-occupied-line">
                    <span class="status-dot"></span>
                    Đang sử dụng (4)
                </div>
                <div class="status-button status-checkout-line">
                    <span class="status-dot"></span>
                    Sắp trả (2)
                </div>
                <div class="status-button status-overdue-line">
                    <span class="status-dot"></span>
                    Quá giờ trả (1)
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="header-row">
                <h3>Chưa xác định (12)</h3>
                <span class="header-line"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Room P.201 -->

        @forelse($bookings as $booking)
            @php
                $class =  "status-dirty"; 
                if( $booking->status  == 1 ){
                    $class =  "status-occupied"; // đang hoạt động; sắp tới 
                }
            @endphp
            <div class="col-md-2">
                <div class="room-card  {{ $class }}" data-toggle="modal" data-target="#roomModal" data-room="P.201"
                    data-status="Chưa dọn" data-info="Phòng 01 giường đôi cho 2 người" data-price-day="180,000"
                    data-price-night="720,000">
                    <span id="badgeChuaDon" class="badge badge-danger"> <svg style="margin-right: 10px"
                            xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 48 48">
                            <g fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="4">
                                <path stroke-linecap="round" d="M20 5.914h8v8h15v8H5v-8h15v-8Z" clip-rule="evenodd" />
                                <path d="M8 40h32V22H8v18Z" />
                                <path stroke-linecap="round" d="M16 39.898v-5.984m8 5.984v-6m8 6v-5.984M12 40h24" />
                            </g>
                        </svg>Chưa dọn</span>
                    <h5>{{ $booking->bookedRooms[0]->room->room_number }}</h5>

                    <p class="single-line">{{ $booking->bookedRooms[0]->roomType->description }}</p>
                    <div class="room-info">
                        <p><i class="fas fa-clock icon"></i>{{ showAmount($booking->bookedRooms[0]->roomType->hourly_rate) }}</p>
                        <p><i class="fas fa-sun icon"></i>{{ showAmount($booking->bookedRooms[0]->roomType->fare) }}</p>
                        <p><i class="fas fa-moon icon"></i>{{ showAmount($booking->bookedRooms[0]->roomType->fare) }}</p>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center" colspan="100%">{{ __($emptyMessage) }}</p>
        @endforelse
        <h1>1quang</h1>
        <div class="col-md-2">
            <div class="room-card status-dirty" data-toggle="modal" data-target="#roomModal" data-room="P.201"
                data-status="Chưa dọn" data-info="Phòng 01 giường đôi cho 2 người" data-price-day="180,000"
                data-price-night="720,000">
                <span id="badgeChuaDon" class="badge badge-danger"> <svg style="margin-right: 10px"
                        xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 48 48">
                        <g fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="4">
                            <path stroke-linecap="round" d="M20 5.914h8v8h15v8H5v-8h15v-8Z" clip-rule="evenodd" />
                            <path d="M8 40h32V22H8v18Z" />
                            <path stroke-linecap="round" d="M16 39.898v-5.984m8 5.984v-6m8 6v-5.984M12 40h24" />
                        </g>
                    </svg>Chưa dọn</span>
                <h5>P.201</h5>

                <p>Phòng 01 giường đôi cho 2 người</p>
                <div class="room-info">
                    <p><i class="fas fa-sun icon"></i>180,000</p>
                    <p><i class="fas fa-moon icon"></i>720,000</p>
                </div>
            </div>
        </div>

        <!-- Room P.202 -->
        <div class="col-md-2">
            <div class="room-card status-occupied" data-toggle="modal" data-target="#roomModal" data-room="P.202"
                data-status="Sạch" data-info="Khách lẻ" data-duration="7 giờ 40 phút / 12 giờ">
                <span class="badge badge-info">
                    <svg style="margin-right: 10px;" xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                        viewBox="0 0 24 24">
                        <path stroke="black" fill="none" stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="1.5"
                            d="M13 21.57A8.132 8.132 0 0 1 6.25 7.75l5.326-5.326a.6.6 0 0 1 .848 0L17.75 7.75A8.131 8.131 0 0 1 19.74 16M16 20l2 2l4-4" />
                    </svg>
                    Sạch</span>
                <h5>P.202</h5>
                <p>Khách lẻ</p>
                <div class="room-info">
                    <p><i class="fas fa-clock icon"></i>7 giờ 40 phút / 12 giờ</p>
                </div>
            </div>
        </div>

        <!-- Room P.203 -->
        <div class="col-md-2">
            <div class="room-card status-dirty" data-toggle="modal" data-target="#roomModal" data-room="P.203"
                data-status="Chưa dọn" data-info="Phòng 01 giường đôi cho 2 người" data-price-day="180,000"
                data-price-night="720,000">
                <span class="badge badge-danger">Chưa dọn</span>
                <h5>P.203</h5>
                <p>Phòng 01 giường đôi cho 2 người</p>
                <div class="room-info">
                    <p><i class="fas fa-clock icon"></i>150,000</p>

                    <p><i class="fas fa-sun icon"></i>180,000</p>
                    <p><i class="fas fa-moon icon"></i>720,000</p>
                </div>
            </div>
        </div>

        <!-- Room P.301 -->
        <div class="col-md-2">
            <div class="room-card status-occupied" data-toggle="modal" data-target="#roomModal" data-room="P.301"
                data-status="Sạch" data-info="Khách lẻ" data-duration="3 giờ 34 phút / 1 giờ">
                <span class="badge badge-info">Sạch</span>
                <h5>P.301</h5>
                <p>Khách lẻ</p>
                <div class="room-info">
                    <p><i class="fas fa-clock icon"></i>3 giờ 34 phút / 1 giờ</p>
                </div>
            </div>
        </div>

        <!-- Room P.302 -->
        <div class="col-md-2">
            <div class="room-card status-occupied" data-toggle="modal" data-target="#roomModal" data-room="P.302"
                data-status="Sạch" data-info="Phòng 01 giường đơn" data-price-day="150,000" data-price-night="600,000">
                <span class="badge badge-info">Sạch</span>
                <h5>P.302</h5>
                <p>Phòng 01 giường đơn</p>
                <div class="room-info">
                    <p><i class="fas fa-sun icon"></i>150,000</p>
                    <p><i class="fas fa-sun icon"></i>150,000</p>
                    <p><i class="fas fa-moon icon"></i>600,000</p>
                </div>
            </div>
        </div>

        <!-- Room P.303 -->
        <div class="col-md-2">
            <div class="room-card status-available" data-toggle="modal" data-target="#roomModal" data-room="P.303"
                data-status="Sạch" data-info="Khách lẻ" data-duration="2 ngày 3 giờ / 4 ngày">
                <span class="badge badge-info">Sạch</span>
                <h5>P.303</h5>
                <p>Khách lẻ</p>
                <div class="room-info">
                    <p><i class="fas fa-clock icon"></i>2 ngày 3 giờ / 4 ngày</p>
                </div>
            </div>
        </div>

        <!-- Room P.403 -->
        <div class="col-md-2">
            <div class="room-card status-available" data-toggle="modal" data-target="#roomModal" data-room="P.403"
                data-status="Sạch" data-info="Khách lẻ" data-duration="1 tháng / 1 tháng">
                <span class="badge badge-info">Sạch</span>
                <h5>P.403</h5>
                <p>Khách lẻ</p>
                <div class="room-info">
                    <p><i class="fas fa-calendar icon"></i>1 tháng / 1 tháng</p>
                </div>
            </div>
        </div>

        <!-- Room P.401 -->
        <div class="col-md-2">
            <div class="room-card status-clean" data-toggle="modal" data-target="#roomModal" data-room="P.401"
                data-status="Sạch" data-info="Phòng 01 giường đôi và 1 giường đơn" data-price-day="250,000"
                data-price-night="1,000,000">
                <span class="badge badge-info">Sạch</span>
                <h5>P.401</h5>
                <p>Phòng 01 giường đôi và 1 giường đơn</p>
                <div class="room-info">
                    <p><i class="fas fa-sun icon"></i>250,000</p>
                    <p><i class="fas fa-moon icon"></i>1,000,000</p>
                </div>
            </div>
        </div>

        <!-- Room P.402 -->
        <div class="col-md-2">
            <div class="room-card status-dirty" data-toggle="modal" data-target="#roomModal" data-room="P.402"
                data-status="Chưa dọn" data-info="Phòng 01 giường đôi và 1 giường đơn" data-price-day="250,000"
                data-price-night="1,000,000">
                <span class="badge badge-danger">Chưa dọn</span>
                <h5>P.402</h5>
                <p>Phòng 01 giường đôi và 1 giường đơn</p>
                <div class="room-info">
                    <p><i class="fas fa-sun icon"></i>250,000</p>
                    <p><i class="fas fa-moon icon"></i>1,000,000</p>
                </div>
            </div>
        </div>

        <!-- Room Sắp nhận -->
        <div class="col-md-2">
            <div class="room-card status-incoming" data-toggle="modal" data-target="#roomModal" data-room="P.404"
                data-status="Sắp nhận" data-info="Phòng đang chờ khách mới">
                <span class="badge badge-warning">Sắp nhận</span>
                <h5>P.404</h5>
                <p>Phòng đang chờ khách mới</p>
            </div>
        </div>

    </div>
    </div>

    <!-- Room Modal -->
    <div class="modal fade" id="roomModal" tabindex="-1" role="dialog" aria-labelledby="roomModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="roomModalLabel">Đặt/Nhận phòng nhanh</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Room:</strong> <span id="modalRoom"></span></p>
                    <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                    <p><strong>Information:</strong> <span id="modalInfo"></span></p>
                    <p><strong>Day Price:</strong> <span id="modalPriceDay"></span></p>
                    <p><strong>Night Price:</strong> <span id="modalPriceNight"></span></p>
                    <p><strong>Duration:</strong> <span id="modalDuration"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        <!-- Modal clean -->

        <div class="modal fade" id="badgeModal" tabindex="-1" role="dialog" aria-labelledby="badgeModalLabel"
            aria-hidden="true">

        </div>
    @endsection

    <style scoped>
        .single-line {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;  
            overflow: hidden;
        }

        .header-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .header-row h3 {
            margin: 0;
            white-space: nowrap;
        }

        .header-line {
            flex-grow: 1;
            height: 1px;
            background-color: #dddada;
        }

        .room-card {
            border-radius: 8px;
            padding: 10px;
            margin: 10px;
            color: white;
            position: relative;
            height: 180px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .status-clean {
            background-color: #17a2b8;
        }

        /*  phòng trống */
        .status-dirty {

            background-color: #fff;
        }

        /* đang sử dụng */
        .status-available {
            background-color: #279656;
        }

        .badge-danger {
            background: #eeb0ac;
            color: #d22e22 !important;
            padding: 8px !important;
            border-radius: 30px !important;
        }

        .badge-info {
            background: #fcfbfb;
            color: #161515 !important;
            padding: 8px !important;
            border-radius: 30px !important;
        }

        /* sắp trả */
        .status-occupied {
            background-color: #006769;
        }

        /* Dark */
        .status-incoming {
            background-color: #ffc107;
        }

        /* Yellow */
        .room-card .badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        h5 {
            font-weight: 500;
        }

        .status-dirty:hover,
        .status-occupied:hover,
        .status-available:hover,
        .status-incoming:hover,
        .status-clean:hover {
            border: 1px solid gray;
            border-radius: 8px;
        }

        .status-occupied *:not(svg),
        .status-available *:not(svg),
        .status-incoming *:not(svg) {
            color: #fff;
        }



        .room-info {
            font-size: 14px;
        }

        .icon {
            margin-right: 5px;
        }

        .modal-body p {
            margin-bottom: 0.5rem;
        }


        .status-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            padding: 10px 0;
        }

        .status-button {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 20px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #343a40;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .status-button:hover {
            border: 1px solid;
            background-color: #e2e6ea;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 6px;
        }


        .status-available-line .status-dot {
            background-color: #17a2b8;
        }

        /* Blue */
        .status-incoming-line .status-dot {
            background-color: #ffc107;
        }

        /* Yellow */
        .status-occupied-line .status-dot {
            background-color: #28a745;
        }

        /* Green */
        .status-checkout-line .status-dot {
            background-color: #6c757d;
        }

        /* Gray */
        .status-overdue-line .status-dot {
            background-color: #20c997;
        }

        /* Teal */
    </style>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $('#roomModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var room = button.data('room');
            var status = button.data('status');
            var info = button.data('info');
            var priceDay = button.data('price-day') || 'N/A';
            var priceNight = button.data('price-night') || 'N/A';
            var duration = button.data('duration') || 'N/A';

            var modal = $(this);
            modal.find('#modalRoom').text(room);
            modal.find('#modalStatus').text(status);
            modal.find('#modalInfo').text(info);
            modal.find('#modalPriceDay').text(priceDay);
            modal.find('#modalPriceNight').text(priceNight);
            modal.find('#modalDuration').text(duration);
        });
    </script>

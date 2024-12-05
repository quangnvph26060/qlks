@forelse($bookings as $booking)
    @php
        $class = 'status-dirty';
        $room = $booking->room;
    
     
        $bookingId = $room->bookedRooms()[0]->booking_id; 
        $totalPrice = App\Models\Booking::find($bookingId);
        $booking_fare = App\Models\Booking::find($bookingId)->booking_fare;
        $bookingPrice = ($booking_fare  + $totalPrice->product_cost + $totalPrice->service_cost + $totalPrice->extra_charge) - $totalPrice->paid_amount;
        $classClean = $room->getCleanStatusClass();
        $classSvg = $room->getCleanStatusSvg();
        $cleanText = $room->getCleanStatusText();
        // $price = $room->roomPricesActive[0]['price'];

        // test
        // if($booking->booking->status === 3){
        //     $class = 'demo-abc';
        // }
        if (
            now() > $booking->booking->check_in &&
            now() <= $booking->booking->check_out &&
            $booking->booking->status == 1 &&
            $booking->booking->key_status == 1
        ) {
            $class = 'status-occupied'; // đang hoạt động;
        } elseif (now() < $booking->booking->check_in && $booking->booking->status == 1) {
            $class = 'status-incoming'; // sắp nhận
        } elseif ($booking->booking->check_out < now() && $booking->booking->key_status == 1) {
            $class = 'status-check-out'; // quá giờ trả
        } elseif (
            now() >= $booking->booking->check_in &&
            $booking->booking->status == 1 &&
            $booking->booking->key_status == 0
        ) {
            $class = 'status-late-checkin'; // nhận phòng muộn
        }
        $key_status = false;
        if (
            now()->format('Y-m-d H:i:s') >= $booking->booking->check_in &&
            now()->format('Y-m-d H:i:s') < $booking->booking->check_out &&
            $booking->booking->key_status == Status::DISABLE
        ) {
            $key_status = true;
        }
    @endphp
    <div class="col-md-2 main-room-card card-{{ $class }}">
        <div class="room-card {{ $class }}">

            <x-room-badge styleClass="dropdown" isClean="{{ $booking->room->is_clean }}" classClean="{{ $classClean }}"
                classSvg="{{ $classSvg }}" cleanText="{{ $cleanText }}"
                roomNumber="{{ $booking->room->room_number }}" keyStatus="{{ $key_status }}"
                key="{{ $booking->booking->key_status }}" bookingId="{{ $booking->booking_id }}" />

            <div class="content-booking mt-2 room-booking-{{ $class }}" data-id="{{ $booking->booking_id }}"
                data-hours="{{ $bookingPrice }}" data-day="{{ $bookingPrice }}" data-night="{{ $bookingPrice }}"
                data-name = "{{ $booking->roomType->name }} " data-roomNumber="{{ $booking->room->room_number }}"
                data-room-type="{{ $booking->room_type_id }}" data-booking="{{ $booking->id }}">
                {{-- <p> {{ $booking->booking->booking_number }}</p> --}}
                <h1>{{ $booking->room->room_number }}</h1>
                <h3> {{ $booking->roomType->name }}</h3>
                {{-- <p> {{ $booking->booking->check_in }} - {{ $booking->booking->check_out }}</p> --}}

                <p> {!! $booking->booking->checkGuest() !!} </p>

                @php

                    $currentTime = now();
                    $checkInTime = $booking->booking->check_in;
                    $flag = true;
                    // So sánh thời gian hiện tại và thời gian check-in
                    if ($currentTime < $checkInTime) {
                        $flag = false;
                        // Tính khoảng cách thời gian giữa check-in và thời gian hiện tại
                        $diffInHours = $currentTime->diffInHours($checkInTime);
                        $diffInDays = $currentTime->diffInDays($checkInTime);
                        if ($diffInHours < 24) {  
                            $diffMinutes =  $currentTime->diffInMinutes($checkInTime);
                            $roundedMinutes = floor($diffMinutes);
                            $roundedHours = floor($diffInHours);
                            if($roundedHours < 1){
                                $time_check_in = "Còn $roundedMinutes phút nữa nhận phòng.";
                            }else{
                                $time_check_in = "Còn $roundedHours giờ nữa nhận phòng.";
                            }
                            
                        } else {
                            $roundedDays = floor($diffInDays);
                            $time_check_in = "Còn $roundedDays ngày nữa nhận phòng.";
                        }
                    } else {
                        $flag = true;
                    }
                @endphp

                @if ($flag)
                    <p class="single-line">{{ $booking->roomType->name }}</p>
                    <div class="room-info">
                        {{-- <p> <i class="fas fa-clock icon"></i>{{ showAmount($booking->roomType->hourly_rate) }}
                    </p>
                    <p> <i class="fas fa-sun icon"></i>{{ showAmount($booking->roomType->fare) }}
                    </p>
                    <p> <i class="fas fa-moon icon"></i>{{ showAmount($booking->roomType->fare) }}
                    </p> --}}
                        <p class="price-booked">
                            <i class="fas fa-dollar-sign icon"></i>{{ showAmount($bookingPrice) }}
                        </p>
                    </div>
                @else
                    @php
                        echo $time_check_in;
                    @endphp
                @endif

            </div>
        </div>
    </div>
@empty
    <p class="text-center" colspan="100%">{{ __($emptyMessage = "") }}</p>
@endforelse

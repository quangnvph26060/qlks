@forelse($dataRooms as $rooms)
    @php
        //  echo $rooms;
         $key_status = true;
        $class = 'status-dirty';
        $classClean = $rooms->getCleanStatusClass();
        $classSvg = $rooms->getCleanStatusSvg();
        $cleanText = $rooms->getCleanStatusText();
        $price_hours = showAmount($rooms->roomPriceNow()->hourly_price);
        $price_day = showAmount($rooms->roomPriceNow()->daily_price);
        $price_night = showAmount($rooms->roomPriceNow()->overnight_price);
        if (isset($rooms->booked) && $rooms->booked->isNotEmpty()) {
            foreach ($rooms->booked as $item) {
                if (
                    now() > $item->check_in &&
                    now() <= $item->check_out &&
                    $item->status == 1 &&
                    $item->key_status == 1
                ) {
                    $class = 'status-occupied'; // đang hoạt động;
                } elseif (now() < $item->check_in && $item->status == 1) {
                    $class = 'status-incoming'; // sắp nhận
                } elseif ($item->check_out < now() && $item->key_status == 1) {
                    $class = 'status-check-out'; // quá giờ trả
                } elseif (now() >= $item->check_in && $item->status == 1 && $item->key_status == 0) {
                    $class = 'status-late-checkin'; // nhận phòng muộn
                }
               
                // if (
                //     now()->format('Y-m-d H:i:s') >= $item->check_in &&
                //     now()->format('Y-m-d H:i:s') < $item->check_out &&
                //     $item->key_status == Status::DISABLE
                // ) {
                //     $key_status = true;
                // }
            }
        }

    @endphp

    <div class="col-md-2 main-room-card  card-{{ $class }}">
        <div class="room-card demodemo {{ $class }}">

            <x-room-badge styleClass="dropdown" isClean="{{ $rooms->is_clean }}" classClean="{{ $classClean }}"
                classSvg="{{ $classSvg }}" cleanText="{{ $cleanText }}" roomNumber="{{ $rooms->room_number }}"
                />

            <div class="content-booking mt-2 room-booking-{{ $class }}  " data-hours="{{ $price_hours }}"
                data-day="{{ $price_day }}" data-night="{{  $price_night }}"
                data-id="{{ $rooms->booked[0]->booking_id ?? '' }}" data-name="{{ $rooms->roomType->name }}"
                data-roomNumber="{{ $rooms->room_number }}" data-room-type="{{ $rooms->room_type_id }}"
                data-room="{{ $rooms->id }}">
                
                <h2>{{ $rooms->room_number }} </h2>
                <p class="single-line">{{ $rooms->roomType->name }}</p>
                <div class="room-info">
                    <p class="hourly_price">
                        <i class="fas fa-clock icon"></i> <span
                            class="price-color">{{ showAmount($rooms->roomPriceNow()->hourly_price) }}</span>
                    </p>
                    <p class="daily_price">
                        <i class="fas fa-sun icon-gray"></i> {{ showAmount($rooms->roomPriceNow()->daily_price) }}
                    </p>
                    <p class="overnight_price">
                        <i class="fas fa-moon icon-moon"></i> {{ showAmount($rooms->roomPriceNow()->overnight_price) }}
                    </p>
                </div>
            </div>
        </div>
    </div>

@empty
    <p class="text-center" colspan="100%">{{ __($emptyMessage = '') }}</p>
@endforelse
{{-- @if ($is_result)
    @include('admin.booking.partials.booked_rooms', ['bookings' => $bookings ?? []])
@endif --}}


@forelse($dataRooms as $rooms)
    @php
        $classClean = $rooms->getCleanStatusClass();
        $classSvg = $rooms->getCleanStatusSvg();
        $cleanText = $rooms->getCleanStatusText();
        $class = 'status-dirty';
        if ($rooms->status == 1) {
            $class = 'status-occupied'; // đang hoạt động; sắp tới
        }
        $price = $rooms->roomPricesActive[0]['price'];
        $price_hours = showAmount($rooms->roomPriceNow()->hourly_price);
        $price_day = showAmount($rooms->roomPriceNow()->daily_price);
        $price_night = showAmount($rooms->roomPriceNow()->overnight_price);
    @endphp

    <div class="col-md-2 main-room-card  card-{{ $class }}">
        <div class="room-card  {{ $class }}">

            <x-room-badge styleClass="" isClean="{{ $rooms->is_clean }}" classClean="{{ $classClean }}"
                classSvg="{{ $classSvg }}" cleanText="{{ $cleanText }}"
                roomNumber="{{ $rooms->room_number }}" />

            <div class="content-booking mt-2 room-booking-{{ $class }}  " data-hours="{{ $price_hours }}"
                data-day="{{ $price_day }}" data-night="{{ $price_night }}"
                data-name="{{ $rooms->roomType->name }}" data-roomNumber="{{ $rooms->room_number }}"
                data-room-type="{{ $rooms->room_type_id }}" data-room="{{ $rooms->id }}">
                <h2>{{ $rooms->room_number }} </h2>
                <p class="single-line">{{ $rooms->roomType->name }}</p>
                <div class="room-info">
                    <p class="hourly_price">
                        <i class="fas fa-clock icon"></i> {{  showAmount($rooms->roomPriceNow()->hourly_price) }}
                    </p>
                    <p class="daily_price">
                        <i class="fas fa-sun icon-gray"></i> {{ showAmount($rooms->roomPriceNow()->daily_price)  }}
                    </p>
                    <p class="overnight_price">
                        <i class="fas fa-moon icon-moon"></i> {{showAmount($rooms->roomPriceNow()->overnight_price) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@empty
    <p class="text-center" colspan="100%">{{ __($emptyMessage) }}</p>
@endforelse
@if($is_result)
    @include('admin.booking.partials.booked_rooms', ['bookings' => $bookings ?? []])
@endif

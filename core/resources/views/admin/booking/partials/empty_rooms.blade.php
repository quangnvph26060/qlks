
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
    @endphp

    <div class="col-md-2 main-room-card  card-{{ $class }}">
        <div class="room-card  {{ $class }}">

            <x-room-badge styleClass="" isClean="{{ $rooms->is_clean }}" classClean="{{ $classClean }}"
                classSvg="{{ $classSvg }}" cleanText="{{ $cleanText }}"
                roomNumber="{{ $rooms->room_number }}" />

            <div class="content-booking mt-2 room-booking-{{ $class }}" data-hours="{{ $price }}"
                data-day="{{ $price }}" data-night="{{ $price }}"
                data-name="{{ $rooms->roomType->name }}" data-roomNumber="{{ $rooms->room_number }}"
                data-room-type="{{ $rooms->room_type_id }}" data-room="{{ $rooms->id }}">
                <h5>{{ $rooms->room_number }} </h5>
                <p class="single-line">{{ $rooms->roomType->name }}</p>
                <div class="room-info">
                    <p>
                        <i class="fas fa-dollar-sign icon"></i>{{ showAmount($price) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@empty
    <p class="text-center" colspan="100%">{{ __($emptyMessage) }}</p>
@endforelse

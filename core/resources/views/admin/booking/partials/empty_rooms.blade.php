@forelse($dataRooms as $rooms)
    @php

        $key_status = true;
        $class = 'status-dirty';
        $classClean = $rooms->getCleanStatusClass();
        $classSvg = $rooms->getCleanStatusSvg();
        $cleanText = $rooms->getCleanStatusText();
        // $price_hours = showAmount($rooms->roomPriceNow()->hourly_price);
        // $price_day = showAmount($rooms->roomPriceNow()->daily_price);
        // $price_night = showAmount($rooms->roomPriceNow()->overnight_price);
        $flag = false;
        $id_booked = 0;
        $i = 0;
        if($rooms->roomCheckIn->isNotEmpty()){
          $class = 'status-occupied';
          $data = json_decode($rooms->roomCheckIn, true);
          $id_booked = $data[0]['id'];  
           $action = 'admin.booking.check.in.details';
        
        }
        if($rooms->roomBooking->isNotEmpty()){
            $class = 'status-incoming';
            $data = json_decode($rooms->roomBooking, true);
            $id_booked = $data[0]['id'];
            $action = 'admin.booking.details';
          
        
        }  
        // if ($rooms->checkroom()->isNotEmpty()) {
        //     foreach ($rooms->checkroom() as $item) {
               
        //         if (
        //             now() > $item->check_in &&
        //             now() <= $item->check_out &&
        //             $item->status == 1 && $item->key_status == 1  && $item->check_in_at !== "" 
        //         ) {
        //             $class = 'status-occupied'; // đang hoạt động;
        //         } elseif (  now() <  $item->check_in && $item->status == 1) {
        //             $class = 'status-incoming'; // sắp nhận
        //         } elseif ( $item->check_out < now() && $item->key_status == 1) {
        //             $class = 'status-check-out'; // quá giờ trả
        //         } elseif (now() >=  $item->check_in && $item->status == 1 && $item->key_status == 0) {
        //             $class = 'status-late-checkin'; // nhận phòng muộn
        //         }

              
        //     }
        // }
       
    @endphp

        <div class="col-md-2 main-room-card  card-{{ $class }}">
            <div class="room-card demodemo {{ $class }}">

                <x-room-badge styleClass="dropdown" isClean="{{ $rooms->is_clean }}" classClean="{{ $classClean }}"
                    classSvg="{{ $classSvg }}" cleanText="{{ $cleanText }}"
                    roomNumber="{{ $rooms->room_number }}" />
                {{-- <div class="content-booking mt-2 room-booking-{{ $class }}  " 
                    data-day="{{ $rooms->roomType->roomTypePrice->unit_price }}"
                    data-id="{{$rooms->checkroom()[0]->booking_id ?? ""}}" data-name="{{ $rooms->roomType->name }}"
                    data-roomNumber="{{ $rooms->room_number }}" data-room-type="{{ $rooms->room_type_id }}"
                    data-room="{{ $rooms->id }}"> --}}
                <div class="content-booking mt-2 room-booking-{{ $class }}  " data-action="{{$action}}" data-id="{{ $id_booked ?? ""}}">
                  
                    

                    <h2>{{ $rooms->room_number }}  
                        <div class="group-people">
                        </div>
                    </h2>
                    <p class="single-line">{{ $rooms->roomType->name }}</p>
                    <div class="room-info">
                        <p class="hourly_price">
                            {{-- <i class="fas fa-clock icon"></i> --}}
                            <i class="fas fa-sun icon-gray"></i>
                             <span
                                class="price-color">{{ showAmount($rooms->roomType->roomTypePrice->unit_price) }}</span>
                        </p>
                        {{-- <p class="daily_price">
                            <i class="fas fa-sun icon-gray"></i> {{ showAmount($rooms->roomPriceNow()->daily_price) }}
                        </p>
                        <p class="overnight_price">
                            <i class="fas fa-moon icon-moon"></i>
                            {{ showAmount($rooms->roomPriceNow()->overnight_price) }}
                        </p> --}}
                    </div>
                </div>
            </div>
        </div>
    {{-- @endif --}}


@empty
    <p class="text-center" colspan="100%">{{ __($emptyMessage = '') }}</p>
@endforelse
{{-- @if ($is_result)
    @include('admin.booking.partials.booked_rooms', ['bookings' => $bookings ?? []])
@endif --}}

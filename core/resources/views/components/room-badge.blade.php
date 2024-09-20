

<div class="d-flex  justify-content-between align-items-center">
    <div data-room="{{ $roomNumber }}" data-clean="{{$isClean}}" id="badgeChuaDon" class="room-icon badge {{ $classClean }}">
        <img src="{{ asset('assets/svg/' . $classSvg . '.svg') }}" alt="Logo" />
        {{ $cleanText }} 
    </div>
   
  
            @if($key)
                <div>
                    <i class="las la-key f-size--24"></i>
                </div> 
            @endif
   
    <div>
        <svg style="color: #161515" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
            viewBox="0 0 21 21" data-bs-toggle="{{$styleClass}}"
            style="cursor:pointer;" >
            >
            <g fill="currentColor" fill-rule="evenodd">
                <circle cx="10.5" cy="10.5" r="1" />
                <circle cx="10.5" cy="5.5" r="1" />
                <circle cx="10.5" cy="15.5" r="1" />
            </g>
        </svg>
        <div class="dropdown-menu" id="more_select">
            @if($keyStatus)
                <a class="dropdown-item handoverKeyBtn"  data-id="{{ $bookingId }}"  href="javascript:void(0)">
                    <i class="las la-key"></i> @lang('Bàn giao chìa khóa')
                </a>
            @endif
            {{-- data-booked_rooms="{{ $booking->activeBookedRooms->unique('room_id') }}" data-id="{{ $booking->id }}" --}}
            <a class="dropdown-item premium_service" data-id="{{ $bookingId }}"  href="javascript:void(0)">
                <i class="las la-server"></i> @lang('Dịch vụ cao cấp')
            </a>

            <a class="dropdown-item payment_booking"  href="javascript:void(0)">
                <i class="la la-money-bill"></i> @lang('Thanh toán')
            </a> 
            
            <a class="dropdown-item checkout"  href="javascript:void(0)">
                <i class="la la-sign-out"></i> @lang('Trả phòng')
            </a>
        </div>
    </div>
</div>

<div class="d-flex  justify-content-between align-items-center">
    <div id="badgeChuaDon" class="badge {{ $classClean }}">
        <img src="{{ asset('assets/svg/' . $classSvg . '.svg') }}" alt="Logo" />
        {{ $cleanText }}
    </div>

    <div>
        <svg style="color: #161515" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
            viewBox="0 0 21 21" class="room-icon" data-room="{{ $roomNumber }}"
            style="cursor:pointer;" data-clean="{{$isClean}}">
            >
            <g fill="currentColor" fill-rule="evenodd">
                <circle cx="10.5" cy="10.5" r="1" />
                <circle cx="10.5" cy="5.5" r="1" />
                <circle cx="10.5" cy="15.5" r="1" />
            </g>
        </svg>

    </div>
</div>

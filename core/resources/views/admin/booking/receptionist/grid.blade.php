{{-- <div class="floor-header" onclick="toggleFloor('floor1')">Tầng 1 <span><i class="fas fa-chevron-down"></i></span></div>
<div id="floor1" class="room-grid">
    <div class="room-card clean">
        <div class="room-name">P.101</div>
        <div class="room-info">Khách lẻ</div>
    </div>
    <div class="room-card dirty">
        <div class="room-name">P.102</div>
        <div class="room-info">Chưa dọn</div>
    </div>
</div>

<div class="floor-header" onclick="toggleFloor('floor2')">Tầng 2 <span><i class="fas fa-chevron-down"></i></span></div>
<div id="floor2" class="room-grid">
    <div class="room-card clean">
        <div class="room-name">P.201</div>
        <div class="room-info">Khách lẻ</div>
    </div>
    <div class="room-card clean">
        <div class="room-name">P.201</div>
        <div class="room-info">Khách lẻ</div>
    </div>
    <div class="room-card clean">
        <div class="room-name">P.201</div>
        <div class="room-info">Khách lẻ</div>
    </div>
    <div class="room-card clean">
        <div class="room-name">P.201</div>
        <div class="room-info">Khách lẻ</div>
    </div>
    <div class="room-card clean">
        <div class="room-name">P.201</div>
        <div class="room-info">Khách lẻ</div>
    </div>
    <div class="room-card clean">
        <div class="room-name">P.201</div>
        <div class="room-info">Khách lẻ</div>
    </div>
    <div class="room-card clean">
        <div class="room-name">P.201</div>
        <div class="room-info">Khách lẻ</div>
    </div>
    <div class="room-card dirty">
        <div class="room-name">P.202</div>
        <div class="room-info">Chưa dọn</div>
    </div>
</div> --}}


@push('scripts-book')
    <script src="{{ asset('assets/admin/js/grid.js') }}"></script>
    <script>
        var roomBoookingHistory = "{{ route('admin.booking.room-booking-history') }}"
    </script>
@endpush


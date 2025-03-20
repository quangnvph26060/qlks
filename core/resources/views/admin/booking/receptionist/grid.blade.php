<div class="floor-header" onclick="toggleFloor('floor1')">Tầng 1 <span><i class="fas fa-chevron-down"></i></span></div>
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
</div>


@push('scripts-book')
    <script src="{{ asset('assets/admin/js/grid.js') }}"></script>
@endpush
<style scoped>
    .floor-header {
        font-size: 20px;
        font-weight: bold;
        cursor: pointer;
        padding: 10px;
        background: #ddd;
        margin-bottom: 5px;
        border-radius: 5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .room-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, 200px); /* Cố định mỗi phòng 200px */
        justify-content: start; /* Canh trái thay vì trải rộng */
        gap: 15px;
        margin-bottom: 20px;
    }

    .room-card {
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        position: relative;
        transition: 0.3s;
        min-width: 200px;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .room-status {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 5px 10px;
        font-size: 12px;
        border-radius: 15px;
        font-weight: bold;
    }

    .clean {
        background-color: #198754;
        color: white;
    }

    .dirty {
        background-color: #dc3545;
        color: white;
    }

    .room-name {
        font-size: 20px;
        font-weight: bold;
        margin-top: 10px;
    }

    .room-info {
        font-size: 14px;
        color: #666;
        margin-top: 5px;
    }

    .price {
        font-size: 14px;
        font-weight: bold;
        margin-top: 10px;
    }

    .time-info {
        font-size: 12px;
        background: #eee;
        padding: 5px 10px;
        border-radius: 5px;
        display: inline-block;
        margin-top: 5px;
    }

    @media (max-width: 768px) {
        .room-grid {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }
    }
</style>

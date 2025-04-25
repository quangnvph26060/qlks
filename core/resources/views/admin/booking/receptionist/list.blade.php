<div class="card b-radius--10 scroll-container-main">
    <table class="table--light style--two table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Mã đặt phòng</th>

                <th>Phòng</th>
                <th>Khách hàng</th>
                <th>Giờ nhận</th>
                <th>Giờ trả</th>
                <th>Tổng cộng</th>
                <th>Khách đã trả</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="booking-table">


        </tbody>
    </table>
</div>

@push('scripts-book')
    <script src="{{ asset('assets/admin/js/list-main.js') }}"></script>
@endpush
<style scoped>
    .room-action-menu .dropdown-item {
        padding: 8px 12px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
    }

    .room-action-menu .dropdown-item:last-child {
        border-bottom: none;
    }

    .room-action-menu .dropdown-item:hover {
        background-color: #f5f5f5;
    }

    .menu-toggle-btn {
        line-height: 1;
        font-size: 30px;
        padding: 0px 0px 0px;
        margin-left: 4px;
    }

    .menu-main-list {
        display: none;
        position: absolute;
        right: 59px;
        background: white;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        border-radius: 4px;
        z-index: 100;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #e3f2d6;
        font-weight: bold;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    .badge {
        padding: 5px 10px;
        border-radius: 10px;
        color: #fff;
        font-weight: bold;
    }

    .gray {
        background-color: gray;
    }

    .green {
        background-color: green;
    }

    .brown {
        background-color: brown;
    }

    .button {
        padding: 6px 12px;
        border-radius: 5px;
        color: #fff;
        text-decoration: none;
        font-weight: bold;
    }

    .btn-blue {
        background-color: blue;
    }

    .btn-green {
        background-color: green;
    }

    .btn-orange {
        background-color: orange;
    }
</style>

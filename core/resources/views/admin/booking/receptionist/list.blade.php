<div>
    <table>
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
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            @php
            $bookings = [
                ['id' => 1, 'code' => 'DP00012', 'date' => '17/03/2025 10:20', 'room' => 'P.201', 'room_class' => 'gray', 'customer' => 'Khách lẻ', 'checkin' => '13 Thg 03, 00:00', 'checkout' => '17 Thg 03, 10:20', 'total' => '19,080,000', 'paid' => '19,080,000', 'status' => 'Đã hoàn thành', 'btn_class' => 'btn-blue'],
                ['id' => 2, 'code' => 'DP00013', 'date' => '17/03/2025 11:00', 'room' => 'P.301', 'room_class' => 'green', 'customer' => 'Khách lẻ', 'checkin' => '14 Thg 03, 12:00', 'checkout' => '18 Thg 03, 14:00', 'total' => '15,000,000', 'paid' => '0', 'status' => 'Trả phòng', 'btn_class' => 'btn-blue'],
                ['id' => 3, 'code' => 'DP00014', 'date' => '17/03/2025 12:30', 'room' => 'P.401', 'room_class' => 'brown', 'customer' => 'Khách lẻ', 'checkin' => '15 Thg 03, 14:00', 'checkout' => '19 Thg 03, 12:00', 'total' => '20,000,000', 'paid' => '5,000,000', 'status' => 'Nhận phòng', 'btn_class' => 'btn-green'],
                ['id' => 4, 'code' => 'DP00015', 'date' => '17/03/2025 14:00', 'room' => 'P.502', 'room_class' => 'gray', 'customer' => 'Khách lẻ', 'checkin' => '12 Thg 03, 10:00', 'checkout' => '16 Thg 03, 09:00', 'total' => '18,500,000', 'paid' => '18,500,000', 'status' => 'Đã hoàn thành', 'btn_class' => 'btn-blue'],
                ['id' => 5, 'code' => 'DP00016', 'date' => '17/03/2025 15:30', 'room' => 'P.303', 'room_class' => 'green', 'customer' => 'Khách lẻ', 'checkin' => '13 Thg 03, 00:00', 'checkout' => '17 Thg 03, 11:00', 'total' => '12,000,000', 'paid' => '0', 'status' => 'Trả phòng', 'btn_class' => 'btn-blue'],
            ];
        @endphp
        
        @foreach ($bookings as $booking)
        <tr>
            <td>{{ $booking['id'] }}.</td>
            <td><a href="#">{{ $booking['code'] }}</a> <br> {{ $booking['date'] }}</td>
            <td><span class="badge {{ $booking['room_class'] }}">{{ $booking['room'] }}</span></td>
            <td>{{ $booking['customer'] }}</td>
            <td>{{ $booking['checkin'] }}</td>
            <td>{{ $booking['checkout'] }}</td>
            <td>{{ $booking['total'] }}</td>
            <td>{{ $booking['paid'] }}</td>
            <td><span class="button {{ $booking['btn_class'] }}">{{ $booking['status'] }}</span></td>
        </tr>
        @endforeach
        
          
        </tbody>
    </table>
</div>

@push('scripts-book')
    <script src="{{ asset('assets/admin/js/list-main.js') }}"></script>
@endpush
<style scoped>
  table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
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
        .gray { background-color: gray; }
        .green { background-color: green; }
        .brown { background-color: brown; }
        .button {
            padding: 6px 12px;
            border-radius: 5px;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }
        .btn-blue { background-color: blue; }
        .btn-green { background-color: green; }
        .btn-orange { background-color: orange; }
</style>
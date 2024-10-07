<!DOCTYPE html>
<html>

<head>
    <title>Thông báo hủy phòng</title>
</head>

<body>
    <h1>Xin chào {{ $user->last_name . ' ' . $user->first_name }},</h1>
    <p>Chúng tôi rất tiếc phải thông báo rằng các phòng sau đây trong đơn đặt phòng của bạn đã bị hủy:</p>
    <ul>
        @foreach ($canceledRooms as $item)
            <li>Phòng: {{ $item->room->room_number }} hiện tại đã bị hủy, do phòng hiện tại đang được hoạt động.</li>
        @endforeach
    </ul>
    <p>Chúng tôi xin lỗi vì sự bất tiện này và hy vọng sẽ tiếp tục được phục vụ bạn trong tương lai.</p>
</body>

</html>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Hóa đơn thanh toán</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 14px;
            color: #000;
            padding: 20px;
        }

        h3,
        h4 {
            text-align: center;
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border-bottom: 1px dotted #000;
            padding: 6px 8px;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .section-title {
            font-weight: bold;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-style: italic;
        }

        .no-border td {
            border: none;
        }
    </style>
</head>

<body onload="window.print(); window.close();">

    <h4>Phần mềm quản lý khách sạn Homestay - Fasthotel</h4>
    <h3>Fasthotel - Giao dịch - Hóa đơn</h3>

    <p><strong>Tên cơ sở:</strong> {{ $hotel->hotel_name }}</p>
    <p><strong>Điện thoại:</strong> {{ $hotel->phone }}</p>
    <p><strong>Ngày xuất HĐ:</strong>{{ $currentDateTime }}</p>

    <center>
        <div class="text-center mb-2">
            <strong>HÓA ĐƠN DỊCH VỤ</strong><br>
            <strong>{{ $booking->payment_id }}</strong>
        </div>
    </center>

    <p><strong>Khách hàng:</strong> {{ $booking->customer }}</p>
    <p><strong>Mã đặt phòng:</strong> {{ $booking->checkin_id ?? $booking->booking_id }}</p>
    <p><strong>Thu ngân:</strong> {{ $booking->creator_name ?? '' }}</p>
    <div class="section-title">Danh sách phòng</div>
    <table>
        <thead>
            <tr>
                <th>Mã đặt phòng</th>
                <th>Tên phòng</th>
                <th>Đơn giá</th>
                <th>Giảm giá</th>
                <th>Đặt cọc</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($booked as $item)
                <tr>
                    <td>{{ $item->booking_id ?? $item->check_in_id }}</td>
                    <td>{{ $item->room->room_number }}</td>
                    <td>{{ number_format($item->total_amount) }}VNĐ</td>
                    <td>{{ number_format($item->discount) }} VNĐ</td>
                    <td>{{ number_format($item->deposit_amount) }} VNĐ</td>
                </tr>
            @endforeach

        </tbody>
    </table>
   @if (isset($booking->service_booking) && $booking->service_booking->isNotEmpty())
        <div class="section-title">Danh sách sản phẩm</div>
        <table>
            <thead>
                <tr>
                    <th>Mã hàng hóa</th>
                    <th>Tên sản phẩm</th>
                    <th>Đơn giá</th>
                    <th>SL</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
              @foreach ($booking->service_booking as $item)
                   <tr>
                    <td>{{$item->service?->code ?? $item->product?->sku}}</td>
                    <td>{{$item->service?->name ?? $item->product?->name}}</td>
                    <td>{{$item->quantity}}</td>
                    <td>{{number_format($item->price)}} VNĐ</td>
                       <td>{{number_format($item->total_payment)}} VNĐ</td>
                   </tr>
               @endforeach
            </tbody>
        </table>
    @endif




    <table class="no-border">
        <tr>
            <td class="text-right" colspan="4"><strong>Tổng tiền:</strong></td>
            <td class="text-right">{{ number_format($booking->room_price) }}VNĐ</td>
        </tr>
        <tr>
            <td class="text-right" colspan="4">Đặt cọc:</td>
            <td class="text-right">{{ number_format($booking->deposit_total) }}VNĐ</td>
        </tr>
        <tr>
            <td class="text-right" colspan="4">Giảm giá:</td>
            <td class="text-right">{{ number_format($booking->discount_total) }}VNĐ</td>
        </tr>
        <tr>
            <td class="text-right" colspan="4">Khách đã trả:</td>
            <td class="text-right">{{ number_format($booking->paid_customer) }}VNĐ</td>
        </tr>
        <tr>
            <td class="text-right" colspan="4"><strong>Khách cần trả:</strong></td>
            <td class="text-right"><strong>{{ number_format($booking->customer_needs_to_pay) }}VNĐ</strong></td>
        </tr>
    </table>

    <div class="footer">
        Cảm ơn và hẹn gặp lại<br>
        <strong>Powered by Fasthotel</strong>
    </div>
</body>

</html>

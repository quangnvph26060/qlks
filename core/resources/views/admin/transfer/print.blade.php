<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>In phiếu điều chuyển</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            padding: 20px;
            color: #000;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        table th {
            background-color: #f0f0f0;
        }

        .total {
            margin-top: 15px;
            text-align: right;
            font-weight: bold;
            font-size: 16px;
        }

        .note {
            margin-top: 20px;
            font-style: italic;
        }
    </style>
</head>

<body>
    <h2>Phiếu điều chuyển #{{ $warehouse->reference_code }}</h2>

    <div class="info">
        <p><strong>Ngày:</strong> {{ \Carbon\Carbon::parse($warehouse->transfer_date)->format('d/m/Y H:i') }}</p>
        <p><strong>Người tạo:</strong> {{ $warehouse->admin->name ?? '---' }}</p>
        @php
            $pttt = [
                1 => 'Thanh toán khi nhận hàng',
                2 => 'Thanh toán chuyển khoản',
            ];
        @endphp

        {{-- <p>
            <strong>Phương thức thanh toán:</strong>
            {{ $pttt[$warehouse->payment_method_id] ?? '---' }}
        </p> --}}

        <p><strong>Ghi chú:</strong> {{ $warehouse->note ?? '---' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
                <th>Từ kho</th>
                <th>Đến kho</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach ($warehouse->entry->entries as $item)
                @php
                    $amount = $item->quantity * $item->price;
                    $total += $amount;
                @endphp
                <tr>
                    <td>{{ $item->product->name ?? 'Mã: ' . $item->product_id }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 0, ',', '.') }} </td>
                    <td>{{ number_format($amount, 0, ',', '.') }}</td>
                    <td>{{ $warehouse->fromWarehouse->name }}</td>
                    <td>{{ $warehouse->toWarehouse->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">Tổng cộng: {{ number_format($total, 0, ',', '.') }} VND</div>

    <div class="note">Cảm ơn quý khách đã sử dụng hệ thống!</div>
</body>

</html>

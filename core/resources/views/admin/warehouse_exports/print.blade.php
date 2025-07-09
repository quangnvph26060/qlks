<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>In phiếu xuất kho</title>
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

        table th, table td {
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
    <h2>Phiếu xuất kho #{{ $warehouse->reference_code }}</h2>

    <div class="info">
        <p><strong>Ngày:</strong> {{ \Carbon\Carbon::parse($warehouse->created_time)->format('d/m/Y H:i') }}</p>
        <p><strong>Người tạo:</strong> {{ $warehouse->admin->username ?? '---' }}</p>
        <p><strong>Ghi chú:</strong> {{ $warehouse->note ?? '---' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach ($warehouse->entriesexport as $item)
                @php
                    $amount = $item->quantity * $item->price;
                    $total += $amount;
                @endphp
                <tr>
                    <td>{{ $item->product->name ?? 'Mã: ' . $item->product_id }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 0, ',', '.') }} VND</td>
                    <td>{{ number_format($amount, 0, ',', '.') }} VND</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">Tổng cộng: {{ number_format($total, 0, ',', '.') }} VND</div>

    <div class="note">Cảm ơn quý khách đã sử dụng hệ thống!</div>
</body>
</html>

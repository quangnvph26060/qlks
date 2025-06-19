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

        h3, h4 {
            text-align: center;
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
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

    <p><strong>Tên cơ sở:</strong> Khách sạn QuangDev</p>
    <p><strong>Điện thoại:</strong> 0382252551</p>
    <p><strong>Ngày xuất HĐ:</strong> 10:30 19/06/2025</p>

    <h3>HÓA ĐƠN BÁN HÀNG</h3>
    <p><strong>Số HĐ:</strong> HD001</p>
    <p><strong>Khách hàng:</strong> Nguyễn Văn A</p>
    <p><strong>Mã đặt phòng:</strong> DP123</p>
    <p><strong>Thu ngân:</strong> Lê Văn B</p>

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
            <tr>
                <td>SP001</td>
                <td>Nước suối</td>
                <td>10.000 VNĐ</td>
                <td>2</td>
                <td>20.000 VNĐ</td>
            </tr>
            <tr>
                <td>SP002</td>
                <td>Bánh mì</td>
                <td>15.000 VNĐ</td>
                <td>1</td>
                <td>15.000 VNĐ</td>
            </tr>
        </tbody>
    </table>

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
            <tr>
                <td>DP123</td>
                <td>Phòng 101</td>
                <td>300.000 VNĐ</td>
                <td>20.000 VNĐ</td>
                <td>50.000 VNĐ</td>
            </tr>
        </tbody>
    </table>

    <table class="no-border">
        <tr>
            <td class="text-right" colspan="4"><strong>Tổng tiền:</strong></td>
            <td class="text-right">335.000 VNĐ</td>
        </tr>
        <tr>
            <td class="text-right" colspan="4">Đặt cọc:</td>
            <td class="text-right">50.000 VNĐ</td>
        </tr>
        <tr>
            <td class="text-right" colspan="4">Giảm giá:</td>
            <td class="text-right">20.000 VNĐ</td>
        </tr>
        <tr>
            <td class="text-right" colspan="4">Khách đã trả:</td>
            <td class="text-right">265.000 VNĐ</td>
        </tr>
        <tr>
            <td class="text-right" colspan="4"><strong>Khách cần trả:</strong></td>
            <td class="text-right"><strong>265.000 VNĐ</strong></td>
        </tr>
    </table>

    <div class="footer">
        Cảm ơn và hẹn gặp lại<br>
        <strong>Powered by Fasthotel</strong>
    </div>
</body>
</html>

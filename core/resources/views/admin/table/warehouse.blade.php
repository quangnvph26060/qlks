@if ($response->isNotEmpty())
    @foreach ($response as $warehouse)
        @php
            $total = $warehouse->return ? $warehouse->total - $warehouse->return->total : $warehouse->total;
            $statusIcon =
                $warehouse->return && $warehouse->return->warehouse_entry_id == $warehouse->id
                    ? '<i class="fa fa-exclamation-circle"></i>'
                    : ($warehouse->status
                        ? '<i class="fas fa-check-circle" style="color: #63E6BE;"></i>'
                        : '<i class="fas fa-hourglass-half" style="color: #f6c23e;"></i>');
        @endphp
        <tr data-id="{{ $warehouse->id }}">
            <td data-label="Mã phiếu">
                <div class="tooltip1">
                    {!! $statusIcon !!}
                    @if ($warehouse->return && $warehouse->return->warehouse_entry_id == $warehouse->id)
                        <span class="tooltiptext">Đơn hàng có sản phẩm bị hoàn trả!</span>
                    @endif
                </div>
                <a href="{{ route('admin.warehouse.show', $warehouse->id) }}">{{ $warehouse->reference_code }}</a>
            </td>
            <td data-label="Nhà Cung Cấp">{{ $warehouse->supplier->name ?? '' }}</td>
            <td data-label="Số điện thoại">{{ $warehouse->supplier->phone ?? '' }}</td>
            <td data-label="Ngày tạo">{{ \Carbon\Carbon::parse($warehouse->created_at)->format('d/m/Y H:i') }}</td>
            <td data-label="Trạng thái">
                <span class="badge {{ $warehouse->status ? 'badge--success' : 'badge--warning' }}">
                    {{ $warehouse->status ? 'Hoàn thành' : 'Đang xử lý' }}
                </span>
            </td>
            <td data-label="Tổng tiền">{{ showAmount($total) }}</td>
        </tr>
    @endforeach
@endif

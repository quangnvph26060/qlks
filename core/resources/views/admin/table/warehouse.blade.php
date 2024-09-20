@if ($response->isNotEmpty())
    @foreach ($response as $warehouse)
        <tr data-id="{{ $warehouse->id }}"> 
            <td data-label="Mã phiếu">
                @if ($warehouse->return && $warehouse->return->warehouse_entry_id == $warehouse->id)
                    {{-- @if ($warehouse->status) --}}
                    <div class="tooltip1">
                        <i class="fa fa-exclamation-circle"></i>
                        <span class="tooltiptext">Đơn hàng có sản phẩm bị hoàn trả!</span>
                    </div>
                    {{-- @else
                        <i class="fas fa-exclamation-triangle" style="color: #fd3a3a;"></i> --}}
                    {{-- @endif --}}
                @elseif($warehouse->status)
                    <i class="fas fa-check-circle" style="color: #63E6BE;"></i>
                @else
                    <i class="fas fa-hourglass-half" style="color: #f6c23e;"></i>
                @endif
                <a href="{{ route('admin.warehouse.show', $warehouse->id) }}">{{ $warehouse->reference_code }} </a>
            </td>
            <td data-label="Nhà Cung Cấp">{{ $warehouse->supplier->name }}</td>
            <td data-label="Số điện thoại">{{ $warehouse->supplier->phone }}</td>
            <td data-label="Ngày tạo">{{ \Carbon\Carbon::parse($warehouse->created_at)->format('d/m/Y H:i') }}</td>
            <td data-label="Trạng thái">
                @if ($warehouse->status)
                    <span class="badge badge--success">Hoàn thành</span>
                @else
                    <span class="badge badge--warning">Đang xử lý</span>
                @endif
            </td>
            <td data-label="Tổng tiền">{{ showAmount($warehouse->total) }}</td>
        </tr>
    @endforeach
@endif

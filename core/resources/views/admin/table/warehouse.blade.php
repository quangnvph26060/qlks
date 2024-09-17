@if ($response->isNotEmpty())
    @foreach ($response as $warehouse)
        <tr data-id="{{ $warehouse->id }}">
            <td data-label="Mã phiếu"> <a
                    href="{{ route('admin.warehouse.show', $warehouse->id) }}">{{ $warehouse->reference_code }} </a></td>
            <td data-label="Nhà Cung Cấp">{{ $warehouse->supplier->name }}</td>
            <td data-label="Số điện thoại">{{ $warehouse->supplier->phone }}</td>
            <td data-label="Ngày tạo">{{ \Carbon\Carbon::parse($warehouse->created_at)->format('d/m/Y H:i') }}</td>
            <td data-label="Trạng thái">
                <div class="form-check form-switch p-0">
                    <input class="form-check-input" type="checkbox" id="status" style="padding: 10px 20px;" @checked($warehouse->status)>
                </div>
            </td>
            <td data-label="Tổng tiền">{{ showAmount($warehouse->total) }}</td>

        </tr>
    @endforeach
@endif

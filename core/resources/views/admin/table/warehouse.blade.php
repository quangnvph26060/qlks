@if ($response->isNotEmpty())
    @foreach ($response as $warehouse)
        <tr data-id="{{ $warehouse->id }}">
            <td data-label="Mã phiếu"> <a
                    href="{{ route('admin.warehouse.show', $warehouse->id) }}">{{ $warehouse->reference_code }} </a></td>
            <td data-label="Nhà Cung Cấp">{{ $warehouse->supplier->name }}</td>
            <td data-label="Số điện thoại">{{ $warehouse->supplier->phone }}</td>
            <td data-label="Địa Chỉ">{{ $warehouse->supplier->address }}</td>
            <td data-label="Email">{{ $warehouse->supplier->email }}</td>
            <td data-label="Tổng">{{ showAmount($warehouse->total) }}</td>

        </tr>
    @endforeach
@endif

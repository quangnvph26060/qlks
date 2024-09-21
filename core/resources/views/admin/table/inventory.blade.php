@if ($response->isNotEmpty())
    @foreach ($response as $inventory)
        <tr>
            <td>{{$inventory->warehouse->reference_code}}</td>
            <td>{{$inventory->product->sku}}</td>
            <td>{{$inventory->quantity}}</td>
            <td>{{showAmount($inventory->product->import_price)}}</td>
            <td>{{showAmount($inventory->product->selling_price)}}</td>
            <td>{{\Carbon\Carbon::parse($inventory->entry_date)->diffForHumans()}}</td>
            <td>
                {!! $inventory->status == 'in' ? '<p class="badge badge--success">Nhập kho</p>' : '<p class="badge badge--warning">Xuất kho</p>' !!}
            </td>
        </tr>
    @endforeach
@endif

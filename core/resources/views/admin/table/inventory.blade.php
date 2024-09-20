@if ($response->isNotEmpty())
    @foreach ($response as $inventory)
        <tr>
            <td>{{$inventory->warehouse->reference_code}}</td>
            <td>{{$inventory->product->sku}}</td>
            <td>{{$inventory->quantity}}</td>
            <td>{{showAmount($inventory->product->import_price)}}</td>
            <td>{{\Carbon\Carbon::parse($inventory->entry_date)->diffForHumans()}}</td>
        </tr>
    @endforeach
@endif

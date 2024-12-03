@foreach ($response as $return)
    <tr data-id="{{ $return->id }}">
        <td><a href="{{ route('admin.return.show', $return->id) }}">{{ $return->reference_code }}</a></td>
        <td><a
                href="{{ route('admin.warehouse.show', $return->warehouse_entry_id) }}">{{ $return->warehouse_entry->reference_code ?? ""}}</a>
        </td>
        <td>{{ $return->return_items->count() }} sản phẩm</td>
        <td>
            @php($sum = 0)
            @foreach ($return->return_items as $item)
                @php($sum += $item->pivot->quantity)
            @endforeach
            {{ $sum }}
        </td>
        <td>{{ \Carbon\Carbon::parse($return->created_at)->diffForHumans() }}</td>
    </tr>
@endforeach

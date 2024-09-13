@foreach ($response as $return)
    <tr data-id="{{ $return->id }}">
        {{-- <td>{{ $loop->iteration }}</td>
        <td>{{ $return->order->reference_code }}</td>
        <td>{{ $return->order->customer->name }}</td>
        <td>{{ $return->order->customer->phone }}</td>
        <td>{{ $return->order->customer->email }}</td>
        <td>{{ $return->order->customer->address }}</td>
        <td>{{ $return->created_at }}</td>
        <td>
            <div class="dropdown">
                <button class="btn btn-sm btn--primary btn--icon btn-action" data-bs-toggle="dropdown">
                    <i class="la la-ellipsis-v"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('admin.table.return.details', $return->id) }}">
                        <i class="la la-desktop"></i> @lang('Chi tiết')
                    </a>
                </div>
            </div>
        </td> --}}
    </tr>
@endforeach

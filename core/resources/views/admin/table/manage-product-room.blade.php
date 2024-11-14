@if ($response->isNotEmpty())
    @foreach ($response as $room)
        @if ($room->products->count())
            <tr data-id="{{ $room->id }}">
                <th>{{ $loop->iteration }}</th>
                <td>{{ $room->code }}</td>
                <td>{{ $room->roomType->name }}</td>
                <td>{{ $room->room_number }}</td>
                <td>
                    @if ($room->products->count() > 0)
                        @foreach ($room->products as $item)
                            <span class="badge {{ getRandomColor() }}">{{ $item->name }}</span>
                        @endforeach
                    @else
                        <p>Chưa có sản phẩm nào !</p>
                    @endif

                </td>
                @can('admin.hotel.room.product.all')
                    <td>
                        <div class="button--group">
                            <button class="btn btn-sm btn-outline--primary btn-edit" data-id="{{ $room->id }}"
                                data-modal_title="@lang('Cập nhật danh mục')" type="button">
                                <i class="fas fa-edit"></i>@lang('Sửa')
                            </button>
                        </div>
                    </td>
                @endcan
            </tr>
        @endif
    @endforeach
@endif

@if ($response->isNotEmpty())
    @foreach ($response as $room)
        @if ($room->products->count())
            <tr data-id="{{ $room->id }}">
                <th data-label="STT">{{ $loop->iteration }}</th>
                <td data-label="Mã phòng">{{ $room->code }}</td>
                <td data-label="Loại phòng">{{ $room->roomType->name }}</td>
                <td data-label="Số phòng">{{ $room->room_number }}</td>
                <td data-label="Sản phẩm">
                    @if ($room->products->count() > 0)
                        @foreach ($room->products as $item)
                            <span class="badge {{ getRandomColor() }} limitname">{{ $item->name }}</span>
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

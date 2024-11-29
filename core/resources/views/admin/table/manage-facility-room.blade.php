@if ($response->isNotEmpty())
    @foreach ($response as $room)
        @if ($room->facilities->count())
            <tr data-id="{{ $room->id }}">
                <th data-label="STT">{{ $loop->iteration }}</th>
                <td data-label="Mã phòng">{{ $room->code }}</td>
                <td data-label="Loại phòng">{{ $room->roomType->name }}</td>
                <td data-label="Số phòng">{{ $room->room_number }}</td>
                <th data-label="Cơ sở vật chất" >
                    @if ($room->facilities->count() > 0)
                        @foreach ($room->facilities as $item)
                            <span class="badge {{ getRandomColor() }}">{{ $item->title }}</span>
                        @endforeach
                    @else
                        <p>Chưa có cơ sở vật chất nào </p>
                    @endif

                </th>
                @can('admin.hotel.room.facilities.all')
                    <td data-label="Hành động">
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

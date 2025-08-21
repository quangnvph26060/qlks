@if ($response->isNotEmpty())
    @foreach ($response as $id => $room)
        <tr data-id="{{ $room->id }}">
            <td data-label="Hành động" style="width:20px;" class="d-none-mobi">
                <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                    viewBox="0 0 21 21">
                    <g fill="currentColor" fill-rule="evenodd">
                        <circle cx="10.5" cy="10.5" r="1" />
                        <circle cx="10.5" cy="5.5" r="1" />
                        <circle cx="10.5" cy="15.5" r="1" />
                    </g>
                </svg>

                <div class="dropdown menu_dropdown_check_in" id="dropdown-menu">
                    @can('admin.hotel.room.amenities.edit')
                        <div class="dropdown-item">
                            <a class="btn btn-sm btn-outline--primary btn-edit" data-id="{{ $room->id }}"
                                data-modal_title="@lang('Cập nhật tiện nghi')" type="button"
                                style="color:black !important;border:none;padding:5px">
                                Sửa
                            </a>
                        </div>
                    @endcan
                    @can('admin.hotel.room.amenities.delete')
                        <div class="dropdown-item booked_room_detail">
                            <button class=" btn-delete icon-delete-room" data-id="{{ $room->id }}"
                                data-modal_title="@lang('Xóa tiện nghi')" type="button"data-pro="0">Xóa
                        </div>
                    @endcan
                </div>
            </td>

            <td data-label="STT" style="text-align:right">
                @php
                    $stt = $response->total() - ($response->currentPage() - 1) * $response->perPage() + $id;
                @endphp
                {{ $stt }}
            </td>
            <td data-label="Mã phòng">{{ $room->code }}</td>
            <td data-label="Loại phòng">{{ $room->roomType->name }}</td>
            <td data-label="Tên phòng">{{ $room->room_number }}</td>
            <td data-label="Tiện nghi">
                @if ($room->amenities->count() > 0)
                    @foreach ($room->amenities as $item)
                        <span class="badge {{ getRandomColor() }}"
                            style="color:white !important">{{ $item->title }}</span>
                    @endforeach
                @else
                    <p>Chưa có tiện nghi nào !</p>
                @endif

            </td>
            <td class="d-block-mobi">
                <div class="action-buttons gap-2">
                    @can('admin.hotel.room.amenities.edit')
                        <div class="dropdown-item"  style=" background: orange;    padding: 2px;color: white !important;border-radius: 4px;border: none">
                            <a class="btn btn-sm btn-outline--primary btn-edit d-flex justify-content-center" data-id="{{ $room->id }}"
                                data-modal_title="@lang('Cập nhật tiện nghi')" type="button"
                                 style=" background: orange;color: white !important;border-radius: 4px;border: none">
                                Sửa
                            </a>
                        </div>
                    @endcan
                    @can('admin.hotel.room.amenities.delete')
                        <div class="dropdown-item booked_room_detail d-flex justify-content-center"style=" background: red;    padding: 2px;color: white !important;border-radius: 4px;border: none">
                            <button class=" btn-delete icon-delete-room" data-id="{{ $room->id }}"
                                 style=" background: red;color: white !important;border-radius: 4px;border: none"
                                data-modal_title="@lang('Xóa tiện nghi')" type="button"data-pro="0">Xóa
                        </div>
                    @endcan
                </div>
            </td>

        </tr>
    @endforeach
@endif

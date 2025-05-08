@if ($response->isNotEmpty())
    @foreach ($response as $id => $room)
        @if ($room->facilities->count())
            <tr data-id="{{ $room->id }}">
            @can('admin.hotel.room.facilities.all')
                
                <td style="width:20px">
                    <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"/><circle cx="10.5" cy="5.5" r="1"/><circle cx="10.5" cy="15.5" r="1"/></g></svg>
                                    
                    <div class="dropdown menu_dropdown_check_in" id="dropdown-menu">
                        <div class="dropdown-item booked_room_edit">
                        <a class="btn btn-sm btn-outline--primary btn-edit" data-id="{{ $room->id }}"  style="color:black !important;border:none;padding:5px"
                                        data-modal_title="@lang('Cập nhật cơ sở vật chất')" type="button">
                                      @lang('Sửa')
                        </a>
                        </div>
                    
                        <div class="dropdown-item booked_room_detail">
                        <button class=" btn-delete" data-id="{{ $room->id }}"
                            data-modal_title="@lang('Xóa')" type="button">
                                    Xóa
                        </button>
                        </div>
             </div>

            </td>
            @endcan
                <th data-label="STT">
                @php
                $stt = $response->total() - ($response->currentPage() - 1) * $response->perPage() - $id;
                    @endphp
                {{ $stt }}
                </th>
                <td data-label="Mã phòng">{{ $room->code }}</td>
                <td data-label="Loại phòng">{{ $room->roomType->name }}</td>
                <td data-label="Số phòng">{{ $room->room_number }}</td>
                <th data-label="Cơ sở vật chất" >
                    @if ($room->facilities->count() > 0)
                        @foreach ($room->facilities as $item)
                            <span class="badge {{ getRandomColor() }}" style="color:white !important">{{ $item->title }}</span>
                        @endforeach
                    @else
                        <p>Chưa có cơ sở vật chất nào </p>
                    @endif

                </th>
        
            </tr>
        @endif
    @endforeach
@endif

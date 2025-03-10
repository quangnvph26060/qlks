@foreach ($rooms as $id => $room)
                                     
                                            <tr data-id="{{ $room->id }}">
                                            @can('admin.hotel.room.amenities.all')
                                                
                                                <td style="width:20px;">
                                                <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"/><circle cx="10.5" cy="5.5" r="1"/><circle cx="10.5" cy="15.5" r="1"/></g></svg>
                                                        
                                                    <div class="dropdown menu_dropdown_check_in" id="dropdown-menu" style="position:fixed">
                                                        <div class="dropdown-item">
                                                        <a class="btn btn-sm btn-outline--primary btn-edit" data-id="{{ $room->id }}"
                                                            data-modal_title="@lang('Cập nhật tiện nghi')" type="button" style="color:black !important;border:none;padding:5px">
                                                            Sửa tiện nghi
                                                        </a>
                                                    </div>    
                                                                    
                                                    <div class="dropdown-item booked_room_detail">
                                                        <button class=" btn-delete icon-delete-room" data-id="{{ $room->id }}" data-modal_title="@lang('Xóa tiện nghi')" type="button"data-pro="0">Xóa tiện nghi
                                                        </div>
                                                        
                                                    </div>
                                                </td>
                                            @endcan
                                                <td style="text-align:right">      
                                                    @php
                                                        $stt = $rooms->total() - ($rooms->currentPage() - 1) * $rooms->perPage() - $id;
                                                    @endphp
                                                    {{ $stt }}
                                                </td>
                                                <td>{{ $room->code }}</td>
                                                <td data-label="Loại phòng">
                                                    @php
                                                        $type_name = \App\Models\RoomType::where('id',$room->room_type_id)->value('name');
                                                    @endphp
                                                    {{ $type_name }}
                                                </td>                                                  
                                                <td>{{ $room->room_number }}</td>
                                                <td>
                                                    @if ($room->amenities->count() > 0)
                                                        @foreach ($room->amenities as $item)
                                                            <span class="badge {{ getRandomColor() }}">{{ $item->title }}</span>
                                                        @endforeach
                                                    @else
                                                        <p>Chưa có tiện nghi nào !</p>
                                                    @endif

                                                </td>
                                            
                                            </tr>
                                    @endforeach
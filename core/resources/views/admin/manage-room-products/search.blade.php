@foreach ($rooms as $id => $room)
                                    @if ($room->products->count())
                                        <tr data-id="{{ $room->id }}">
                                        @can('admin.hotel.room.product.all')
                                            <td style="width:20px">
                                                <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"/><circle cx="10.5" cy="5.5" r="1"/><circle cx="10.5" cy="15.5" r="1"/></g></svg>
                                                                
                                                <div class="dropdown menu_dropdown_check_in" id="dropdown-menu" style="position:fixed">
                                                    <div class="dropdown-item booked_room_edit">
                                                    <a class="btn btn-sm btn-outline--primary btn-edit" data-id="{{ $room->id }}"  style="color:black !important;border:none;padding:5px"
                                                                    data-modal_title="@lang('Cập nhật sản phẩm')" type="button">
                                                                @lang('Sửa sản phẩm')
                                                    </a>
                                                    </div>
                                                
                                                    <div class="dropdown-item booked_room_detail">
                                                    <button class=" btn-delete" data-id="{{ $room->id }}"
                                                        data-modal_title="@lang('Xóa')" type="button">
                                                                Xóa sản phẩm
                                                    </div>
                                                </div>

                                            </td>
                                            @endcan
                                            <td data-label="STT" style="text-align:right">      
                                                @php
                                            $stt = $rooms->total() - ($rooms->currentPage() - 1) * $rooms->perPage() - $id;
                                                @endphp
                                            {{ $stt }}</td>
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
                                        
                                        </tr>
                                    @endif
                                @endforeach
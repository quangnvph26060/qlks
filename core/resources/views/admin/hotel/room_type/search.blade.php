@foreach($room_type as $type)
    <tr>
        <td>
            <button class="btn btn-link btn-toggle" type="button"
                onclick=" toggleRepresentatives('{{ $type->id }}', this)"></button>
        </td>
        @can(['admin.hotel.room.type.edit', 'admin.hotel.room.type.status', 'admin.hotel.room.type.destroy'])
            <td>
            <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"/><circle cx="10.5" cy="5.5" r="1"/><circle cx="10.5" cy="15.5" r="1"/></g></svg>
                            
            <div class="dropdown menu_dropdown_check_in" id="dropdown-menu">
                <div class="dropdown-item booked_room_edit">
                <a href="{{ route('admin.hotel.room.type.edit', $type->id) }}" style="color:black;padding:5px">
                     Sửa phòng
                 </a>
                </div>
                <div class="dropdown-item booked_room">
                  @if ($type->status == 0)
               
                <button class=" confirmationBtn"
                         data-action="{{ route('admin.hotel.room.type.status', $type->id) }}"
                         data-question="@lang('Bạn có chắc chắn muốn bật loại phòng này không?')">
                       Hoạt động
                </button>
                  @else
                 <button class="confirmationBtn"
                        data-action="{{ route('admin.hotel.room.type.status', $type->id) }}"
                         data-question="@lang('Bạn có chắc chắn muốn vô hiệu hóa loại phòng này không?')">
                      Tắt hoạt động
                </button>
                     @endif
                </div>
                <div class="dropdown-item booked_room_detail">
                <button class=" btn-delete" data-id="{{ $type->id }}"
                       data-modal_title="@lang('Xóa')" type="button">
                             Xóa phòng
                 </button>
                </div>
                 </div>
            </td>
        @endcan
        <td data-label="STT">{{ $loop->iteration }}</td>
       
        <td data-label="Loại phòng">
            @php
                $name = \App\Models\RoomType::where('id','=',$type->room_type_id)->value('name');
            @endphp
            {{ $name }}
        </td>  
        <td data-label="Mã phòng">
            {{ $type->code }}
        </td>
        <td data-label="Tên phòng">
            {{ $type->room_number }}
        </td>
        <td data-label="Số người">
            {{ $type->total_adult }}
        </td>
        <td data-label="Số giường">
            {{ $type->beds }}
        </td>
        {{-- <td data-label="Tiện nghi">
           <!--  @if ($type->amenities->count() > 0)
                <div class="float-inline-end">
                    @foreach ($type->amenities->take(3) as $amenity)
                        <span class="badge {{ getRandomColor() }} m-1 p-1 rounded-pill text-bg-primary">
                            {{ $amenity->title }}
                        </span>
                    @endforeach
                    @if ($type->amenities->count() > 3)
                    <span class="">
                        ...
                    </span>
                @endif
                </div>
            @else
                Chưa có tiện nghi nào
            @endif -->
        </td> --}}
        {{-- <td data-label="Cơ sở vật chất">
            @if ($type->facilities->count() > 0)
            <!--     <div class="float-inline-end">
                    @foreach ($type->facilities as $facility)
                        <span class="badge {{ getRandomColor() }} m-1 p-1 rounded-pill text-bg-primary">
                            {{ $facility->title }}
                        </span>
                    @endforeach
                </div>
            @else
                Chưa có cơ sở vật chất
            @endif -->
        </td> --}}

        {{-- <td data-label="Giá giờ">{{ showAmount($type->roomPriceNow()?->hourly_price) ?? '-----' }}</td>
        <td data-label="Giá ngày">{{ showAmount($type->roomPriceNow()?->daily_price) ?? '-----' }}</td>
        <td data-label="Giá đêm">{{ showAmount($type->roomPriceNow()?->overnight_price) ?? '-----' }}</td> --}}
        
        <td data-label="Trạng thái">     
            @if(!empty($type->status))
                      <i class="fa fa-check" style="color:green;text-align: center"></i>
                             @else
                         <i class="fa fa-close" style="color:red;text-align: center"></i>
                             @endif
                                   
        </td>
  

    </tr>

    <tr class="collapse" id="rep-{{ $type->id }}">
        <td colspan="8">
            {{-- @if ($type->products->count() > 0)
                <div class="representatives-container">
                    <span class="representatives-label">Sản phẩm:</span>
                    <span class="representatives-list">
                        @foreach ($type->products as $product)
                            <span class="badge {{ getRandomColor() }} me-2 mb-1 cursor-pointer">
                                <small class="representative-name">
                                    {{ $product->name }}</small>
                            </span>
                        @endforeach
                    </span>
                </div>
            @endif --}}
            <div class="representatives-container">
                <span class="representatives-label">Số lượng người:</span>
                <span class="representatives-list">
                    {{ $type->total_adult }}
                </span>
            </div>
            {{-- <div class="representatives-container">
                <span class="representatives-label">Trạng thái tính năng:</span>
                <span class="representatives-list">
                    {!! $type->featureBadge !!}
                </span>
                
            </div> --}}
            <div class="representatives-container">
                <span class="representatives-label">Tiện nghi:</span>
                <span class="representatives-list">
                    @if ($type->amenities->count() > 0)
                <div class="float-inline-end">
                    @foreach ($type->amenities as $amenity)
                        <span class="badge {{ getRandomColor() }} m-1 p-1 rounded-pill text-bg-primary">
                            {{ $amenity->title }}
                        </span>
                    @endforeach
                </div>
            @else
                Chưa có tiện nghi nào
            @endif
                </span>
                
            </div>
            <div class="representatives-container">
                <span class="representatives-label">Cơ sở vật chất</span>
                <span class="representatives-list">
                    @if ($type->facilities->count() > 0)
                        <div class="float-inline-end">
                            @foreach ($type->facilities as $facility)
                                <span class="badge {{ getRandomColor() }} m-1 p-1 rounded-pill text-bg-primary">
                                    {{ $facility->title }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        Chưa có cơ sở vật chất
                    @endif
                </span>
                
            </div>
        </td>
    </tr>

@endforeach

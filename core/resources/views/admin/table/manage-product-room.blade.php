@if ($response->isNotEmpty())
    @foreach ($response as $id => $room)
        @if ($room->products->count())
            <tr data-id="{{ $room->id }}">
                <td data-label="" class="d-none-mobi">
                    <button class="btn btn-link btn-toggle" type="button"
                        onclick=" toggleRepresentatives('{{ $room->id }}', this)"></button>
                </td>
                @can('admin.hotel.room.product.all')
                    <td style="width:20px" data-label="Hành động">
                        <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                            viewBox="0 0 21 21">
                            <g fill="currentColor" fill-rule="evenodd">
                                <circle cx="10.5" cy="10.5" r="1" />
                                <circle cx="10.5" cy="5.5" r="1" />
                                <circle cx="10.5" cy="15.5" r="1" />
                            </g>
                        </svg>

                        <div class="dropdown menu_dropdown_check_in" id="dropdown-menu">
                            @can(['admin.hotel.room.product.edit', 'admin.hotel.room.product.update'])
                                <div class="dropdown-item booked_room_edit">
                                    <a class="btn btn-sm btn-outline--primary btn-edit" data-id="{{ $room->id }}"
                                        style="color:black !important;border:none;padding:5px"
                                        data-modal_title="@lang('Cập nhật sản phẩm')" type="button">
                                        @lang('Sửa')
                                    </a>
                                </div>
                            @endcan
                            @can('admin.hotel.room.product.delete')
                                <div class="dropdown-item booked_room_detail">
                                    <button class=" btn-delete" data-id="{{ $room->id }}"
                                        data-modal_title="@lang('Hoàn kho')" type="button">
                                        Hoàn kho
                                </div>
                            @endcan
                        </div>

                    </td>
                @endcan
                <th data-label="STT" style="text-align:right">
                    @php
                        $stt = $response->total() - ($response->currentPage() - 1) * $response->perPage() + $id;
                    @endphp
                    {{ $stt }}</th>
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
           <tr class="collapse bg-light" id="rep-{{ $room->id }}">
    <td colspan="8">
        @if ($room->products->count() > 0)
            <div class="p-3 border rounded">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tên sản phẩm</th>
                                <th style="width: 20%">Số lượng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($room->products as $index => $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->pivot->quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="text-muted p-3">Không có sản phẩm nào trong phòng này.</div>
        @endif
    </td>
</tr>

        @endif
    @endforeach
@endif
<style>
      .btn-toggle {
            border: 1px solid #007bff;
            background-color: #007bff;
            color: #fff;
            font-size: 1rem;
            padding: 1px 4px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
            text-align: center;
            line-height: 1;
            border-radius: 50%;
            font-family: 'Courier New', Courier, monospace;
        }

        .btn-toggle:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        #data-table td {
            height: 37px !important;
        }

        .btn-toggle.collapsed {
            background-color: red;
            border-color: red;
        }

        .btn-toggle::after {
            content: '+';
            display: inline-block;
        }

        .btn-toggle.collapsed::after {
            content: '−';
        }
</style>
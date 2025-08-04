@if ($response->isNotEmpty())
    @foreach ($response as $warehouse)
        @php
            $total = $warehouse->return ? $warehouse->total - $warehouse->return->sum('total') : $warehouse->total;

            $statusIcon =
                $warehouse->return &&
                $warehouse->return->first() &&
                $warehouse->return->first()->warehouse_entry_id == $warehouse->id
                    ? '<i class="fa fa-exclamation-circle"></i>'
                    : ($warehouse->status
                        ? '<i class="fas fa-check-circle" style="color: #63E6BE;"></i>'
                        : '<i class="fas fa-hourglass-half" style="color: #f6c23e;"></i>');
        @endphp

        <tr data-id="{{ $warehouse->id }}">
            <td style="width:20px" data-label="Hành động">
                <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                    viewBox="0 0 21 21">
                    <g fill="currentColor" fill-rule="evenodd">
                        <circle cx="10.5" cy="10.5" r="1"></circle>
                        <circle cx="10.5" cy="5.5" r="1"></circle>
                        <circle cx="10.5" cy="15.5" r="1"></circle>
                    </g>
                </svg>
                <div class="dropdown menu_dropdown_check_in" id="dropdown-menu" style="position:fixed">
                    <div class="dropdown-item booked_room_edit">
                        <a href="javascript:void(0);" type="button"
                            class="btn btn-sm btn-primary open-warehouse-modal"
                             data-id="{{ $warehouse->id }}"
                            data-url="{{ route('admin.warehouse.show', $warehouse->id) }}">
                            Sửa
                        </a>
                    </div>
                    <div class="dropdown-item hotel_delete">
                        <form method="POST" action="{{ route('admin.warehouse.destroy', $warehouse->id) }}" class="delete-warehouse-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Xoá</button>
                        </form>
                    </div>
                     <div class="dropdown-item booked_room_edit" >
                        <a href="javascript:void(0);" type="button" style="padding: 5px 22px;  margin-top: -13px;"
                            class="btn btn-sm btn-secondary open-warehouse-modal-print"
                            data-id="{{ $warehouse->id }}"
                            data-url="{{ route('admin.warehouse.print', $warehouse->id) }}">
                            In
                        </a>
                    </div>
                </div>

            </td>
            <td data-label="Mã phiếu">
                <div class="tooltip1">
                    {!! $statusIcon !!}
                    @if (
                        $warehouse->return &&
                            $warehouse->return->first() &&
                            $warehouse->return->first()->warehouse_entry_id == $warehouse->id)
                        <span class="tooltiptext">Đơn hàng có sản phẩm bị hoàn trả!</span>
                    @endif
                </div>
                {{ $warehouse->reference_code }}
            </td>
            <td data-label="Nhà Cung Cấp">{{ $warehouse->supplier->name ?? '' }}</td>
            {{-- <td data-label="Số điện thoại">{{ $warehouse->supplier->phone ?? '' }}</td>
            <td data-label="Ngày tạo">{{ \Carbon\Carbon::parse($warehouse->created_at)->format('d/m/Y H:i') }}</td> --}}
            <td data-label="Trạng thái">
                @php
                    switch ($warehouse->status) {
                        case 1:
                            $badgeClass = 'badge--success';
                            $statusText = 'Hoàn thành';
                            break;
                        case 2:
                            $badgeClass = 'badge--danger';
                            $statusText = 'Đã hủy';
                            break;
                        default:
                            $badgeClass = 'badge--warning';
                            $statusText = 'Chờ xử lý';
                    }
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
            </td>

            <td data-label="Tổng tiền" class="text-right">{{ showAmount($total) }}</td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="6" class="text-center">Không có dữ liệu phiếu nhập nào.</td>
    </tr>
@endif

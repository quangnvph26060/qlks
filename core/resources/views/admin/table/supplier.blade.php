@foreach ($response as $item)
    <tr style="border-bottom: 1px solid #dee2e6">
        <td data-label="STT">
            {{ $loop->iteration }}
            {{-- <button class="btn btn-link btn-toggle" type="button"
                onclick=" toggleRepresentatives('{{ $item->id }}', this)"></button> --}}
        </td>
        <td  data-label="Hành động" style="position: relative;" class="d-none-mobi">
            <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                viewBox="0 0 21 21" onclick="toggleDropdown(event, {{ $item->id }})" style="cursor: pointer;">
                <g fill="currentColor" fill-rule="evenodd">
                    <circle cx="10.5" cy="10.5" r="1" />
                    <circle cx="10.5" cy="5.5" r="1" />
                    <circle cx="10.5" cy="15.5" r="1" />
                </g>
            </svg>

            <div class="dropdown menu_dropdown_check_in" id="dropdown-menu-{{ $item->id }}"
                style="display: none; position: absolute; z-index: 99999999999999; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.15); border-radius: 5px;">
                <div class="dropdown-item">
                    {{-- <a class="btn btn-sm btn-outline--primary cuModalBtn edit_supplier"
                        href="{{ route('admin.supplier.edit', $item->id) }}" data-id="{{ $item->id }}"
                        data-modal_title="@lang('Cập nhật danh mục')" type="button"
                        style="color: black !important; border: none;">Sửa</a> --}}
                    <a href="javascript:void(0)" class="btn btn-sm btn-outline--primary edit_supplier"
                        data-id="{{ $item->id }}" data-route="{{ route('admin.supplier.edit', ['id' => ':id']) }}"
                        data-modal_title="Cập nhật nhà cung cấp">
                        Sửa
                    </a>


                </div>
                <div class="dropdown-item booked_room_detail">
                    <button class="btn-delete icon-delete-room" data-id="{{ $item->id }}"
                        data-modal_title="@lang('Xóa khách hàng')" type="button"
                        data-pro="{{ $item->products->count() }}">Xóa</button>
                </div>
            </div>
        </td>

        <td  data-label="Mã nhà cung cấp">{{ $item->supplier_id ?? 'Chưa có mã nhà cung cấp' }}</td>
        <td  data-label="Tên nhà cung cấp">{{ $item->name }}</td>
        <td  data-label="Email">{{ $item->email }}</td>
        <td  data-label="Số điện thoại">{{ $item->phone }}</td>
        <td  data-label="Địa chỉ">{{ $item->address }}</td>
        <td class="d-block-mobi">
            <div class="action-buttons gap-2">
                 <div class="dropdown-item" style=" background: orange;color: white !important;border-radius: 4px;border: none">
                    {{-- <a class="btn btn-sm btn-outline--primary cuModalBtn edit_supplier"
                        href="{{ route('admin.supplier.edit', $item->id) }}" data-id="{{ $item->id }}"
                        data-modal_title="@lang('Cập nhật danh mục')" type="button"
                        style="color: black !important; border: none;">Sửa</a> --}}
                    <a href="javascript:void(0)"  style=" background: orange;color: white !important;border-radius: 4px;border: none"class="btn btn-sm btn-outline--primary edit_supplier d-flex justify-content-center"
                        data-id="{{ $item->id }}" data-route="{{ route('admin.supplier.edit', ['id' => ':id']) }}"
                        data-modal_title="Cập nhật nhà cung cấp">
                        Sửa
                    </a>


                </div>
                <div class="dropdown-item booked_room_detail d-flex justify-content-center" style=" background: red;color: white !important;border-radius: 4px;border: none">
                    <button class="btn-delete icon-delete-room"
                    style=" background: red;color: white !important;border-radius: 4px;border: none" data-id="{{ $item->id }}"
                        data-modal_title="@lang('Xóa khách hàng')" type="button"
                        data-pro="{{ $item->products->count() }}">Xóa</button>
                </div>
            </div>
        </td>



    </tr>
    <tr class="collapse" id="rep-{{ $item->id }}">
        <td colspan="6" class="p-sm-0">
            <div class="representatives-container">
                <span class="representatives-label">Người đại diện:</span>
                <span class="representatives-list">
                    @foreach ($item->supplier_representatives as $rep)
                        <span class="badge bg-info me-2 position-relative edit-representative cursor-pointer"
                            data-id="{{ $rep->id }}">
                            <small class="representative-name"> {{ $rep->name }}</small>
                            <small class="bg-danger rounded-circle position-absolute delete-representative"
                                style="cursor: pointer; top: -7px !important; right: -5px !important; padding: 1px 4px !important">x</small>
                        </span>
                    @endforeach
                    <span class="badge bg-primary cursor-pointer show-modal" data-id="{{ $item->id }}">Thêm
                        (+)</span>
                </span>
            </div>
        </td>
    </tr>
@endforeach
<script>
    function toggleDropdown(event, id) {
        event.stopPropagation(); // Ngăn sự kiện lan ra ngoài
        const dropdown = document.getElementById(`dropdown-menu-${id}`);
        const allDropdowns = document.querySelectorAll('.menu_dropdown_check_in');

        allDropdowns.forEach(el => {
            if (el !== dropdown) el.style.display = 'none';
        });

        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    // Ẩn khi click ra ngoài
    document.addEventListener('click', function() {
        document.querySelectorAll('.menu_dropdown_check_in').forEach(el => el.style.display = 'none');
    });
</script>

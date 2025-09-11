@extends('admin.layouts.master_iframe')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="modal fade" id="otaModal" tabindex="-1" aria-labelledby="otaModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <form action="{{ route('admin.ota.save') }}" method="post">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="otaModalLabel">Cấu hình OTA</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                            </div>
                            <div class="modal-body">
                                <div class="vstack gap-1">
                                    <input type="hidden" value="{{ $hotels->id }}" name="hotel_id">
                                    <input type="hidden" name="id" id="editId">

                                    <div class="mb-3">
                                        <label for="otaSelect" class="form-label fw-semibold" style="color: #000000">Chọn
                                            OTA</label>
                                        <select class="form-select" name="ota_id" id="otaSelect">
                                            <option value="">-- Chọn một OTA --</option>
                                            @foreach ($otas as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ isset($ota->ota_id) && $ota->ota_id == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name ?? 'OTA #' . $item->id }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="discountCode" class="form-label fw-semibold required"
                                            style="color: #000000">Mã giảm giá</label>
                                        <input type="number" min="0" step="1" class="form-control"
                                            name="discount_code" id="discountCode" placeholder="Nhập mã giảm giá"
                                            value="{{ old('discount_code', $ota->discount_code ?? '') }}">
                                    </div>


                                    <!-- Case 1 -->
                                    <div class="p-3 bg-light rounded-3">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                {{ isset($ota) && $ota->allow_all_rooms == 1 ? 'checked' : '' }}
                                                type="radio" name="ota_case" id="case1" value="1" checked>
                                            <label class="form-check-label fs-6 fw-semibold" for="case1"
                                                style="color: #000000">
                                                <i class="bi bi-building"></i> Cho phép lấy tất cả phòng của cơ
                                                sở
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Case 2 -->
                                    <div class="p-3 bg-light rounded-3">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input"
                                                {{ isset($ota) && is_array($ota->allowed_room_types) && !empty($ota->allowed_room_types) ? 'checked' : '' }}
                                                type="radio" name="ota_case" id="case2" value="2">
                                            <label class="form-check-label fs-6 fw-semibold" for="case2"
                                                style="color: #000000">
                                                <i class="bi bi-layers"></i> Chọn loại phòng được phép lấy
                                            </label>
                                        </div>
                                        <div class="room-types-checkboxes ms-4 mt-2 row row-cols-1 row-cols-md-3">
                                            @foreach ($room_type as $item)
                                                <div class="form-check col">
                                                    <input class="form-check-input" type="checkbox" name="room_types[]"
                                                        value="{{ $item->id }}" id="{{ $item->name }}"
                                                        @if (isset($ota) && is_array($ota->allowed_room_types) && in_array((string) $item->id, $ota->allowed_room_types)) checked @endif>
                                                    <label class="form-check-label" style="color:#000000"
                                                        for="roomTypeStandard">{{ $item->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Case 3 -->
                                    <div class="p-3 bg-light rounded-3">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio"
                                                {{ isset($ota) && is_array($ota->allowed_rooms) && !empty($ota->allowed_rooms) ? 'checked' : '' }}
                                                name="ota_case" id="case3" value="3">
                                            <label class="form-check-label fs-6 fw-semibold" for="case3"
                                                style="color: #000000">
                                                <i class="bi bi-door-closed"></i> Chọn từng phòng cụ thể
                                            </label>
                                        </div>
                                        <div class="room-list-by-type ms-4 mt-2">
                                            @foreach ($rooms as $item)
                                                <div class="mb-3">
                                                    <h6 class="fw-bold" style="color: #000000">
                                                        {{ $item->name }}</h6>
                                                    <div class="form-check d-flex gap-5 ">
                                                        @foreach ($item->rooms as $room)
                                                            <div class="d-flex justify-content-center gap-1">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="rooms[]" value="{{ $room->id }}"
                                                                    id="room{{ $room->id }}"
                                                                    @if (isset($ota) && is_array($ota->allowed_rooms) && in_array((string) $room->id, $ota->allowed_rooms)) checked @endif>
                                                                <label class="form-check-label" for="room"
                                                                    title="{{ $room->room_number }}"
                                                                    style="color: #000000">
                                                                    {{ \Illuminate\Support\Str::limit($room->room_number, 30) }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="statusSelect" class="form-label fw-semibold"
                                            style="color: #000000">Trạng thái OTA</label>
                                        <select class="form-select" name="status" id="statusSelect">
                                            <option value="1"
                                                {{ isset($ota) && $ota->status == 1 ? 'selected' : '' }}>Kích
                                                hoạt</option>
                                            <option value="0"
                                                {{ isset($ota) && $ota->status == 0 ? 'selected' : '' }}>Không
                                                kích hoạt</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary w-100" type="submit">Lưu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive--md table-responsive" style="overflow: visible !important;">
                            <div>
                                <table class="table--light style--two table">
                                    <thead>
                                        <tr>
                                            <th>@lang('Hành động')</th>
                                            <th>@lang('STT')</th>
                                            <th>@lang('OTA')</th>
                                            <th>@lang('Giảm giá')</th>

                                            <th>@lang('Tr.thái')</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($data as $index => $item)
                                            <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray' }}">
                                                <td style="width:20px" data-label="Hành động"
                                                    class="position-relative d-none-mobi">
                                                    <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg"
                                                        width="30" height="30" viewBox="0 0 21 21"
                                                        style="cursor:pointer"
                                                        data-toggle-id="dropdown-{{ $item->id }}">
                                                        <g fill="currentColor" fill-rule="evenodd">
                                                            <circle cx="10.5" cy="10.5" r="1"></circle>
                                                            <circle cx="10.5" cy="5.5" r="1"></circle>
                                                            <circle cx="10.5" cy="15.5" r="1"></circle>
                                                        </g>
                                                    </svg>

                                                    <div class="dropdown menu_dropdown_check_in d-none"
                                                        id="dropdown-{{ $item->id }}"
                                                        style="position:absolute; z-index:999; background:#fff; border:1px solid #ccc; border-radius:5px; padding:5px;">
                                                        <div class="dropdown-item booked_room_edit">
                                                            <a class="btn btn-sm btn-outline--primary btn-edit-ota"
                                                                data-id="{{ $item->id }}"
                                                                data-ota-id="{{ $item->ota_id }}"
                                                                data-discount="{{ $item->discount_code }}"
                                                                data-allowed-room-types='@json($item->allowed_room_types ?? [])'
                                                                data-allowed-rooms='@json($item->allowed_rooms ?? [])'
                                                                data-allow-all-rooms="{{ $item->allow_all_rooms }}"
                                                                data-status="{{ $item->status }}" data-bs-toggle="modal"
                                                                data-bs-target="#otaModal"
                                                                style="color:black !important;border:none;padding:5px"
                                                                type="button">
                                                                Sửa
                                                            </a>

                                                        </div>
                                                        <div class="dropdown-item hotel_delete">
                                                            <button class="btn-delete-ota icon-delete-room"
                                                                data-id="{{ $item->id }}" data-modal_title="Xóa"
                                                                type="button">
                                                                Xóa
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td data-label="STT">{{ $index + 1 }}</td>
                                                <td data-label="OTA">{{ $item->ota['name'] }}</td>
                                                <td data-label="Giảm giá">{{ $item->discount_code }}%</td>
                                                <td data-label="Tr.thái">{!! $item->status_badge !!}</td>
                                                <td class="d-block-mobi">
                                                    <div class="action-buttons gap-2">
                                                        <div style=" background: orange;color: white !important;border-radius: 4px;border: none;padding: 4px;"
                                                            class="dropdown-item booked_room_edit d-flex justify-content-center">
                                                            <a class="btn btn-sm btn-outline--primary btn-edit-ota"
                                                                style=" background: orange;color: white !important;border-radius: 4px;border: none;padding: 4px;"
                                                                data-id="{{ $item->id }}"
                                                                data-ota-id="{{ $item->ota_id }}"
                                                                data-discount="{{ $item->discount_code }}"
                                                                data-allowed-room-types='@json($item->allowed_room_types ?? [])'
                                                                data-allowed-rooms='@json($item->allowed_rooms ?? [])'
                                                                data-allow-all-rooms="{{ $item->allow_all_rooms }}"
                                                                data-status="{{ $item->status }}" data-bs-toggle="modal"
                                                                data-bs-target="#otaModal"
                                                                style="color:black !important;border:none;padding:5px"
                                                                type="button">
                                                                Sửa
                                                            </a>

                                                        </div>
                                                        <div style=" background: red;color: white !important;border-radius: 4px;border: none;padding: 4px;"
                                                            class="dropdown-item hotel_delete d-flex justify-content-center">
                                                            <button class="btn-delete-ota icon-delete-room"
                                                                style=" background: red;color: white !important;border-radius: 4px;border: none;padding: 4px;"
                                                                data-id="{{ $item->id }}" data-modal_title="Xóa"
                                                                type="button">
                                                                Xóa
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table><!-- table end -->
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection


@push('breadcrumb-plugins')
    <div class="d-flex" style="gap:5px">
        <div class="col-md-12 col-sm-12 d-flex">
            <a class="btn d-flex  align-item-center btn-sm btn--primary btn-submit-sync-roles">
                <button class="btn d-flex  align-item-center btn-sm btn--primary">
                    <i class="las la-sync"></i>
                </button>
            </a>

            <a>
                {{-- <button class="btn btn--primary" type="button" data-bs-toggle="modal" data-bs-target="#otaModal"
                    style="margin-left:10px">
                    <i class="las la-plus p-1"></i>
                </button> --}}
                <button class="btn btn--primary btn-add-ota" type="button" data-bs-toggle="modal"
                    data-bs-target="#otaModal" style="margin-left:10px">
                    <i class="las la-plus p-1"></i>
                </button>

            </a>
        </div>
    </div>
@endpush
@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.svg_menu_check_in').forEach(svg => {
                svg.addEventListener('click', function(e) {
                    e.stopPropagation();

                    // Ẩn tất cả dropdowns trước
                    document.querySelectorAll('.menu_dropdown_check_in').forEach(dropdown => {
                        dropdown.classList.add('d-none');
                    });

                    // Hiện dropdown tương ứng
                    const id = this.getAttribute('data-toggle-id');
                    const dropdown = document.getElementById(id);
                    if (dropdown) {
                        dropdown.classList.toggle('d-none');
                    }
                });
            });

            // Click ra ngoài thì ẩn dropdown
            document.addEventListener('click', function() {
                document.querySelectorAll('.menu_dropdown_check_in').forEach(dropdown => {
                    dropdown.classList.add('d-none');
                });
            });
        });
    </script>

    <script>
        function toggleValue(el) {
            el.value = el.checked ? "1" : "0";
        }
        $(document).ready(function() {
            "use strict";
            $('.btn-submit-sync-roles').on('click', function() {
                location.reload();
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $('input[name="ota_case"]').on('change', function() {
                const val = $(this).val();

                // Hiển thị theo giá trị radio được chọn
                $('.room-types-checkboxes').css('display', val == '2' ? 'flex' : 'none');
                $('.room-list-by-type').css('display', val == '3' ? 'block' : 'none');

                // Bỏ tích checkbox không liên quan
                if (val != '2') {
                    $('.room-types-checkboxes input[type="checkbox"]').prop('checked', false);
                }

                if (val != '3') {
                    $('.room-list-by-type input[type="checkbox"]').prop('checked', false);
                }
            });

            // Khi trang vừa load
            const checked = $('input[name="ota_case"]:checked').val();
            $('.room-types-checkboxes').css('display', checked == '2' ? 'flex' : 'none');
            $('.room-list-by-type').css('display', checked == '3' ? 'block' : 'none');


            // Khi chọn chi nhánh (branch)
            $('#branch').on('change', function() {
                const selectedText = $(this).find('option:selected').text();
                $('#branch-name').text(selectedText);
            });

        });
    </script>
    <script>
        $(document).ready(function() {
            const $modal = $('#otaModal');
            const $form = $modal.find('form');

            // Nút "Thêm"
            $('.btn-add-ota').on('click', function() {
                const form = $('#otaModal form')[0];
                form.reset();
                $('#otaModalLabel').text('Thêm OTA');
                $('#editId').val('');
                $('#otaSelect').val('');
                $('#discountCode').val('');
                $('#statusSelect').val('1');
                $('#case1').prop('checked', true);

                // Reset các checkbox
                $('input[type=checkbox]').prop('checked', false);
            });

            $('.btn-edit-ota').on('click', function() {
                const form = $('#otaModal form')[0];
                form.reset();
                $('#otaModalLabel').text('Cập nhật OTA');

                $('#editId').val($(this).data('id'));
                $('#otaSelect').val($(this).data('ota-id'));
                $('#discountCode').val($(this).data('discount'));
                $('#statusSelect').val($(this).data('status'));

                // Reset các checkbox
                $('input[type=checkbox]').prop('checked', false);

                const allowAll = $(this).data('allow-all-rooms');
                const allowedRoomTypes = $(this).data('allowed-room-types') || [];
                const allowedRooms = $(this).data('allowed-rooms') || [];

                if (allowAll == 1) {
                    $('#case1').prop('checked', true);
                } else if (allowedRoomTypes.length > 0) {
                    $('#case2').prop('checked', true);
                    allowedRoomTypes.forEach(id => {
                        $('input[name="room_types[]"][value="' + id + '"]').prop('checked', true);
                    });
                } else if (allowedRooms.length > 0) {
                    $('#case3').prop('checked', true);
                    allowedRooms.forEach(id => {
                        $('input[name="rooms[]"][value="' + id + '"]').prop('checked', true);
                    });
                } else {
                    $('#case1').prop('checked', true);
                }
            });
        });
    </script>
@endpush
<style scoped>
    .d-block-mobi {
        display: none;
    }

    .modal-dialog {
        max-width: 95%;
        /* modal không bao giờ vượt quá 95% chiều ngang */
        width: auto;
        /* tự động co giãn theo nội dung */
    }

    .modal-content {
        max-height: 80vh !important;
        /* không vượt quá 90% chiều cao màn hình */
        overflow-y: auto !important;
        /* thêm scroll khi nội dung quá dài */
    }

    /* Mobile: các nút nằm ngang trong 1 hàng */
    @media (max-width: 768px) {
        .d-block-mobi .action-buttons {
            display: flex;
            flex-wrap: nowrap;
            justify-content: space-between;
        }

        .d-block-mobi .action-buttons .btn {
            flex: 1;
            /* nút tự dàn đều */
            margin: 0 2px;
            /* khoảng cách nhỏ */
        }

        .d-block-mobi {
            display: block;
        }
    }

    switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        height: 25px;
        position: absolute;
        cursor: pointer;
        background-color: #ccc;
        border-radius: 34px;
        top: 39px;
        left: 5px;
        right: 18px;
        bottom: 32px;
        transition: .4s;
        width: 50px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        border-radius: 50%;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:checked+.slider:before {
        transform: translateX(24px);
    }
</style>

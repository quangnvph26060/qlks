@extends('admin.layouts.master_iframe')
@section('panel')
    <div id="pagination" class="mt-2 mb-2 d-flex  justify-content-center align-items-center">
    </div>
    <div class="row">
        <!-- Khối bên phải: Danh sách danh mục -->

        <div class="col-md-12">
            <div class="border">
                <div class="card b-radius--10">
                    <div class="card-body p-0">
                        <div class="table-responsive--sm table-responsive">
                            <table class="table--light style--two table" id="data-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        @can('admin.hotel.room.product.all')
                                            <th>@lang('Hành động')</th>
                                        @endcan
                                        <th style="width:50px">@lang('STT')</th>
                                        <th>@lang('Mã phòng')</th>
                                        <th>@lang('Loại phòng')</th>
                                        <th>@lang('Số phòng')</th>
                                        <th>@lang('Sản phẩm')</th>

                                    </tr>
                                </thead>
                                <tbody id="data">


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @can('')
        @push('breadcrumb-plugins')
            @push('breadcrumb-plugins')
                <div class="card-body mt-1">
                    <div class="row">

                        <div class="col-md-12 d-flex">
                            <a href="{{ route('admin.hotel.room.product.all') }}">
                                <button type="button" class="btn btn--primary"data-modal_title="Làm mới">
                                    <i class="fa fa-repeat p-1"></i>

                                </button>
                            </a>
                            @can('admin.hotel.room.product.store')
                                <a>
                                    <button type="button" class="btn btn--primary btn-add " style="margin-left:8px">
                                        <i class="las la-plus p-1 "></i>

                                    </button>
                                </a>
                            @endcan
                            <form role="form" enctype="multipart/form-data" action="{{ route('admin.hotel.room.product.search') }}">
                                <div class="form-group mb-0" style="display: flex;">
                                    <input class="searchInput" name="code"
                                        style="height: 35px;border: 1px solid rgb(121, 117, 117, 0.5);margin-left:8px"
                                        placeholder="Mã phòng/Tên phòng" value="{{ $code ?? '' }}">

                                    <select name="room_type_id" class="form-control choose ml-1" id="tim-loai-phong"
                                        style="width:250px;margin-left: 8px;height: 35px">
                                        <option value="">--Chọn loại phòng--</option>
                                        @foreach ($room_type as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>

                                    <button type="submit" class="btn btn--primary" style="margin-left: 8px;">
                                        <i class="las la-search p-1"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                </div>
            @endpush
        @endpush
    @endcan
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Thêm sản phẩm vào phòng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="roomsAddFacilityForm" method="GET" action="">
                        @csrf

                        {{-- Danh sách phòng --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Mã phòng</label>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="checkAllRooms">
                                <label class="form-check-label" for="checkAllRooms">Chọn tất cả phòng</label>
                            </div>
                            <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                @foreach ($rooms as $room)
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input room-checkbox" name="room_ids[]"
                                            value="{{ $room->id }}" id="room-{{ $room->id }}">
                                        <label class="form-check-label" for="room-{{ $room->id }}">
                                            {{ $room->code }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Danh sách sản phẩm --}}
                        <div class="mb-4">
                            {{-- <select name="warehouse_id" id="" class="form-control select-warehouse">
                                @foreach ($warehouse as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select> --}}
                            <label class="form-label fw-semibold mt-1">Các sản phẩm <code>(Chọn và nhập số
                                    lượng)</code></label>


                            <div id="product-list" class="border rounded p-3" style="max-height: 250px; overflow-y: auto;">
                                {{-- @foreach ($products as $product)
                                        <div class="mb-3 d-flex align-items-center justify-content-between">
                                            <div class="form-check flex-grow-1">
                                                <input class="form-check-input product-checkbox" type="checkbox"
                                                    value="{{ $product->id }}" name="product_ids[]"
                                                    id="product-{{ $product->id }}">
                                                <label class="form-check-label" for="product-{{ $product->id }}">
                                                    {{ $product->name }}
                                                </label>
                                            </div>
                                            <div style="width: 90px;">
                                                <input type="number" name="stock[{{ $product->id }}]"
                                                    class="form-control form-control-sm" id="stock-{{ $product->id }}"
                                                    max="{{ $product->stock }}" min="1" disabled
                                                    oninput="validateInput(this)">
                                            </div>
                                        </div>
                                    @endforeach --}}
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            @if ($products->isNotEmpty())
                                <button type="submit" class="btn btn-primary">Thực hiện</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="showEditRoomFacility" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Cập nhật</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="roomsEditFacilityForm" method="POST">
                        @csrf
                        {{-- <input type="hidden" name="_method" id="method" value="POST"> --}}
                        <input type="hidden" name="id" id="recordId">
                        <div class="row">
                            <div class=" mb-3">
                                <label for="">Mã phòng</label>
                                <select name="room_id" id="room-choice" class="form-control">

                                </select>
                            </div>
                        </div>
                        <div class="row p-2">
                            {{-- <select name="warehouse_id" id="" class="form-control select-warehouse">
                                @foreach ($warehouse as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select> --}}
                            <div class="form-group mb-3 mt-1">
                                <label for="">Các sản phẩm <code>(Được chọn nhiều)</code></label>
                                <div id="checkbox-facility" class="form-check-group mt-3 row"
                                    style="max-height: 250px; overflow-y: auto;">
                                    <!-- Các checkbox sẽ được thêm vào đây -->
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            @if ($products->isNotEmpty())
                                <button type="submit" class="btn btn-primary">Thực hiện</button>
                            @endif
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


@endsection
@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/modal.css') }}">

    <style>
        .pagination .page-item .page-link,
        .pagination .page-item span {
            width: 22px !important;
            height: auto !important;
            background-color: #4634ff !important;
            color: white !important;
        }

        .pagination .page-item.active .page-link {
            background-color: #071251 !important;
        }
    </style>
@endpush
@push('script')
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('assets/admin/js/dataTable.js') }}"></script>

    <script>
        (function($) {
            "use strict"
            $(document).ready(function() {
                const apiUrl = '{{ route('admin.hotel.room.product.all') }}';
                initDataFetch(apiUrl);
                window.toggleRepresentatives = function(id, button) {
                    const row = document.getElementById('rep-' + id);
                    row.classList.toggle('show');
                    button.classList.toggle('collapsed');
                };
                $("#roomsAddFacilityForm").on('submit', function(e) {
                    e.preventDefault();
                    const url =
                        "{{ route('admin.hotel.room.product.store') }}";
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: $(this).serializeArray(),
                        success: function(response) {
                            if (response.status) {
                                showSwalMessage('success', response.message);
                                $('#staticBackdrop').modal('hide');
                                $('#roomsAddFacilityForm')[0].reset(); // Reset form
                                // Reset lại các input với class "input_number" nếu có
                                $('#staticBackdrop').find('.input_number').val('').prop(
                                    'disabled', true);
                                // Reset lại các checkbox nếu có
                                $('#staticBackdrop').find('.form-check-input').prop(
                                    'checked', false);

                                initDataFetch(apiUrl);
                            } else {
                                $('input').removeClass('is-invalid');
                                $(`#${response.key}`).addClass('is-invalid');

                                showSwalMessage('error', response.message);
                            }

                        }
                    });
                })
                $('#checkAllRooms').on('change', function() {
                    $('.room-checkbox').prop('checked', this.checked);
                });

                // Nếu bỏ chọn 1 phòng => bỏ check all
                $('.room-checkbox').on('change', function() {
                    const allChecked = $('.room-checkbox').length === $('.room-checkbox:checked')
                        .length;
                    $('#checkAllRooms').prop('checked', allChecked);
                });

                function buildWarehouseOptions(selectedId) {
                    return warehouses.map(item => {
                        let selected = item.id == selectedId ? 'selected' : '';
                        return `<option value="${item.id}" ${selected}>${item.name}</option>`;
                    }).join('');
                }
                $(document).on('click', '.btn-edit', function() {
                    let id = $(this).data('id');

                    $.ajax({
                        type: "GET",
                        url: "{{ route('admin.hotel.room.product.edit', ':id') }}".replace(
                            ':id', id),
                        success: function(response) {
                            if (response.status) {
                                $('#showEditRoomFacility').modal('show');

                                // Tạo danh sách phòng
                                let roomSelect = $('#room-choice');
                                roomSelect.empty();
                                response.rooms.forEach(room => {
                                    let selected = room.id === response.roomEdit
                                        .id ? 'selected' : '';
                                    let disabled = room.id !== response.roomEdit
                                        .id ? 'disabled class="text-muted"' : '';
                                    roomSelect.append(
                                        `<option value="${room.id}" ${selected} ${disabled}>${room.code}</option>`
                                    );
                                });
                                roomSelect.trigger('change');

                                // Tạo danh sách tiện ích (amenities)
                                let amenitesContainer = $('#checkbox-facility');
                                amenitesContainer.empty();
                                response.products.forEach(facility => {

                                    let checked = response.selectedproducts
                                        .hasOwnProperty(facility.id) ? 'checked' :
                                        '';
                                    let disable = !checked ? 'disabled' : '';
                                    let quantity = checked ? response
                                        .selectedproducts[facility.id].quantity :
                                        '';
                                    let warehouse_id = response.selectedproducts[
                                        facility.id]?.warehouse_id ?? '';


                                    amenitesContainer.append(`
                                    <div class=" mb-4 edit-checkbox" style="display: flex">
                                        <div class="form-check" style="flex: 70%" >
                                            <input class="form-check-input product-checkbox-edit" type="checkbox" value="${facility.id}" ${checked}
                                                name="product_id[]" id="checkbox-facility-${facility.id}">
                                            <label class="form-check-label limit_name" for="checkbox-facility-${facility.id}">
                                                ${facility.name}  (Tồn: ${facility.stock})
                                            </label>
                                        </div>
                                         <div style="width: 120px;">
                                        <select name="warehouse_id[${facility.id}]" data-id="${facility.id}" class="form-control form-control-sm select-warehouse-edit" style="height:33px">
                                         ${buildWarehouseOptions(warehouse_id)}
                                        </select>
                                    </div>
                                        <div style="flex: 30%">
                                            <input type="number" name="stock[]" data-stock="${quantity}" class="input_number form-control form-control-sm" style="max-width: 80px" ${disable} value="${quantity}"
                                                id="stock-${facility.id}" min="1" max="${facility.stock}" oninput="validateInput(this)">
                                        </div>
                                    </div>


                                `);
                                });

                                // Lắng nghe sự kiện khi click vào checkbox
                                $(document).on('click', '.product-checkbox-edit',
                                    function() {
                                        let checkbox = $(this);

                                        let inputNumber = checkbox.closest(
                                            '.edit-checkbox').find('.input_number');

                                        let savedQuantity = inputNumber.data('stock');
                                        if (checkbox.is(':checked')) {

                                            inputNumber.removeAttr('disabled');
                                            inputNumber.val(1);
                                        } else {

                                            inputNumber.val('');
                                            inputNumber.attr('disabled', 'disabled');
                                        }
                                    });


                                $('#staticBackdropLabel').text('Cập nhật');
                                $('#method').val('PUT');
                                $('#recordId').val(id);
                            }
                        }
                    });
                });
                const warehouses = @json($warehouse);

                function handleWarehouseChange(selectElement) {

                    const $select = $(selectElement);
                    const warehouseId = $select.val() ?? "";
                    const productId = $select.data('id') ?? ""; // ✅ Lấy từ data-id

                    $.ajax({
                        url: "{{ route('admin.hotel.room.product.admin.warehouse.get-products-by-warehouse') }}", // ✅ Route bạn định nghĩa trong web.php
                        method: 'POST',
                        data: {
                            warehouse_id: warehouseId,
                            product_id: productId,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.status) {
                                const container = $('#product-list');
                                if (!productId) {
                                    container.empty(); // Xóa tất cả trước khi render lại
                                    response.products.forEach(product => {
                                        renderProduct(product);
                                    });
                                } else {
                                    // ✅ Trường hợp chỉ update dòng đang đổi select
                                    const product = response.products[0]; // Vì chỉ trả về 1
                                    const $row = $select.closest('.d-flex');

                                    $row.find('label.form-check-label')
                                        .html(`${product.name} (Tồn: ${product.quantity})`);
                                    $row.find('input[type="number"]')
                                        .attr('max', product.quantity);
                                }
                            } else {
                                alert('Không tìm thấy sản phẩm trong kho.');
                            }
                        },
                        error: function() {
                            alert('Lỗi khi truy xuất dữ liệu từ kho.');
                        }
                    });
                }

                function renderProduct(product) {
                    const container = $('#product-list');
                    const html = `
                                <div class="d-flex align-items-center justify-content-between mb-2 gap-2">
                                    <div class="form-check flex-grow-1">
                                        <input type="checkbox" class="form-check-input room-checkbox"
                                            name="product_ids[]" value="${product.product_id}" id="product-${product.product_id}">
                                        <label class="form-check-label" for="product-${product.product_id}">
                                            ${product.name} (Tồn: ${product.quantity})
                                        </label>
                                    </div>
                                    <div style="width: 120px;">
                                        <select name="warehouse_id[${product.product_id}]" data-id="${product.product_id}" class="form-control form-control-sm select-warehouse" style="height:33px">
                                            @foreach ($warehouse as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div style="width: 120px;">
                                        <input type="number"
                                            name="stock[${product.product_id}]"
                                            class="form-control form-control-sm"
                                            id="stock-${product.product_id}"
                                            max="${product.quantity}" min="1" 
                                            oninput="validateInput(this)">
                                    </div>

                                
                                </div>
                                        `;

                    container.append(html);
                }

                $(document).on('change', '.select-warehouse', function() {
                    handleWarehouseChange(this);
                });

                $(document).on('change', '.select-warehouse-edit', function() {
                    const $select = $(this);
                    const warehouseId = $select.val(); // ✅ ID kho vừa chọn
                    const productId = $select.data('id'); // ✅ ID sản phẩm

                    // Gửi Ajax để lấy tồn kho của sản phẩm đó trong kho đó
                    $.ajax({
                        url: "{{ route('admin.hotel.room.product.admin.warehouse.get-products-by-warehouse') }}",
                        method: 'POST',
                        data: {
                            warehouse_id: warehouseId,
                            product_id: productId,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.status && response.products.length) {
                                const product = response.products[0];


                                const $row = $select.closest('.edit-checkbox');
                                $row.find('label.form-check-label').html(
                                    `${product.name} (Tồn: ${product.quantity})`);

                                // ✅ Cập nhật max trong ô input số lượng
                                $row.find('input[type="number"]').attr('max', product
                                    .quantity);
                            } else {
                                alert('Không tìm thấy tồn kho sản phẩm!');
                            }
                        },
                        error: function() {
                            alert('Lỗi khi truy vấn tồn kho!');
                        }
                    });
                });

                $(document).ready(function() {
                    const selects = $('.select-warehouse');

                    if (selects.length > 0) {
                        selects.each(function() {
                            handleWarehouseChange(this);
                        });
                    } else {
                        // ✅ Nếu chưa có select nào, vẫn chạy 1 lần handleWarehouseChange với null hoặc mặc định
                        handleWarehouseChange(null);
                    }
                });




                $(document).on('click', '.btn-add', function() {
                    $('#roomsAddFacilityForm')[0].reset();
                    $('#method').val('POST');
                    $('#staticBackdropLabel').text(
                        'Thêm mới'); // Đặt lại tiêu đề là "Thêm mới"
                    $('#staticBackdrop').modal('show');
                });
                $('#roomsEditFacilityForm').submit(function(e) {
                    e.preventDefault();
                    console.log($(this).serialize());
                    $.ajax({
                        url: '{{ route('admin.hotel.room.product.update') }}',
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            if (response.status) {
                                showSwalMessage('success', response.message);
                                $('#showEditRoomFacility').modal('hide');
                                initDataFetch(apiUrl);
                            }
                        },
                        error: function(error) {
                            alert('Có lỗi xảy ra khi cập nhật sản phẩm.');
                        }
                    });
                });
                $(document).on('click', '.btn-delete', function() {
                    var dataId = $(this).data('id');
                    var rowToDelete = $(`tr[data-id="${dataId}"]`);
                    Swal.fire({
                        title: 'Xác nhận xóa sản phẩm?',
                        text: 'Bạn có chắc chắn muốn xóa sản phẩm này không?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Đồng ý',
                        cancelButtonText: 'Hủy bỏ',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // ajax

                            $.ajax({
                                url: `{{ route('admin.hotel.room.product.delete', '') }}/${dataId}`,
                                type: 'POST',
                                success: function(data) {
                                    if (data.status === 'success') {
                                        rowToDelete.remove();


                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.log(xhr.responseText);
                                }
                            });


                        }
                    });
                });


            });


        })(jQuery);

        $(document).ready(function() {

            $(document).on('click', '.svg-icon', function(e) {
                e.stopPropagation();
                const $dropdown = $(this).siblings('.menu_dropdown');
                $('.menu_dropdown').not($dropdown).removeClass('show');
                $dropdown.toggleClass('show');
            });
            $(document).on('click', function() {
                $('.menu_dropdown').removeClass('show');
            });
            $(document).on('click', '.svg_menu_check_in', function(e) {
                e.stopPropagation();
                const $dropdown = $(this).siblings('.menu_dropdown_check_in');
                $('.menu_dropdown_check_in').not($dropdown).removeClass('show');
                $dropdown.toggleClass('show');
            });
            $(document).on('click', function() {
                $('.menu_dropdown_check_in').removeClass('show');
            });
            $(document).on('click', function() {
                $('.menu_dropdown').removeClass('show');
            });

        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const checkboxes = document.querySelectorAll(".product-checkbox");

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener("change", function() {

                    const productId = this.value;

                    const stockInput = document.getElementById("stock-" + productId);

                    if (this.checked) {
                        stockInput.disabled = false;
                        stockInput.value = 1;
                    } else {
                        stockInput.disabled = true;
                        stockInput.value = "";
                    }
                });
            });
        });

        function validateInput(input) {
            const max = parseInt(input.max, 10); // Lấy giá trị max từ thuộc tính
            const min = parseInt(input.min, 10); // Lấy giá trị min từ thuộc tính
            const value = parseInt(input.value, 10);

            if (value > max) {
                input.value = max; // Đặt lại giá trị nếu vượt quá max
            } else if (value < min) {
                input.value = min; // Đặt lại giá trị nếu nhỏ hơn min
            }
        }
        $('.choose').change(function() {
            var room_type_id = $('#tim-loai-phong').val();
            var url = "{{ route('admin.hotel.room.product.ajax') }}";
            $.ajax({
                type: 'GET',
                cache: false,
                url: url,
                data: {
                    room_type_id: room_type_id,

                },
                success: function(response) {
                    if (response) {
                        $('#data').html(response)
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            })
        });
    </script>
@endpush
@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/modal.css') }}">
@endpush
@push('style')
    <style>
        .no-input {
            pointer-events: none;
            background-color: #f0f0f0;
            cursor: not-allowed;
        }

        .input_number {
            width: 55%;
            padding: 5px 10px !important;
            border-radius: 5px;
            margin-left: 10px
        }

        .radio-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle {
            position: relative;
            display: inline-block;
            width: 52px;
            height: 29px;
        }

        .toggle input {
            display: none;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #4CAF50;
        }

        input:checked+.slider:before {
            transform: translateX(24px);
        }

        .label {
            margin-left: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        .status-input {
            margin-bottom: 20px;
        }

        .status-input label {
            font-weight: bold;
            margin-right: 10px;
        }

        .radio-group {
            display: flex;
            align-items: center;
        }

        .radio-group input[type="radio"] {
            margin-right: 5px;
            accent-color: #007bff;
        }

        .radio-group label {
            margin-right: 20px;
            font-size: 16px;
        }

        @media(max-width:768px) {
            .flex-nowrap {
                flex-wrap: nowrap !important;
            }

            .mobi-page {
                align-content: center !important;
            }
        }

        /* .form-check-group {
                                                                                display: flex;
                                                                                flex-wrap: wrap;
                                                                                gap: 10px;
                                                                            } */

        .form-check {
            margin-right: 15px;
        }

        .form-check-input {
            width: 15px;
            height: 15px;
            margin-right: 10px;
        }

        .form-check-label {
            /* font-size: 18px; */
            line-height: 25px;
        }

        .limit_name {
            display: inline-block;
            max-width: 400px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .limitname {
            color: white !important;
            display: inline-block;
            max-width: 120px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endpush

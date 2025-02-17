@extends('admin.layouts.app')
@section('panel')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<div class="row">
    <!-- Khối bên phải: Danh sách danh mục -->
    <div class="col-md-12">
        <div class="border">
            <div class="d-flex justify-content-between  mobi-page">
{{--                <div class="dt-length">--}}
{{--                    <select name="example_length" id="perPage" style=" padding: 1px 3px; margin-right: 8px;"--}}
{{--                        aria-controls="example" class="perPage">--}}
{{--                        <option value="10">10</option>--}}
{{--                        <option value="25">25</option>--}}
{{--                        <option value="50">50</option>--}}
{{--                        <option value="100">100</option>--}}
{{--                    </select><label for="perPage"> entries per page</label>--}}
{{--                </div>--}}
{{--                <div class="search">--}}
{{--                    --}}{{-- <label for="searchInput">Search:</label>--}}
{{--                    <input class="searchInput"--}}
{{--                        style="padding: 1px 3px; border: 1px solid rgb(121, 117, 117, 0.5); margin-left: 8px;"--}}
{{--                        type="search" placeholder="Tìm kiếm..."> --}}
{{--                        <form method="GET" id="searchForm">--}}
{{--                            <div class="input-group flex-nowrap">--}}
{{--                                <input--}}
{{--                                    type="search"--}}
{{--                                    class="searchInput"--}}
{{--                                    name="keyword"--}}
{{--                                    id="searchInput"--}}
{{--                                    value="{{ request('keyword') }}"--}}
{{--                                    placeholder="Tìm kiếm ...">--}}
{{--                                <!-- Nút tìm kiếm -->--}}
{{--                                <button type="button" class="btn btn-primary">--}}
{{--                                    <i class="las la-search"></i>--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                </div>--}}
            </div>
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table--light style--two table" id="data-table">
                            <thead>
                                <tr>
                                    <th>@lang('STT')</th>
                                    <th>@lang('Mã phòng')</th>
                                    <th>@lang('Loại phòng')</th>
                                    <th>@lang('Số phòng')</th>
                                    <th>@lang('Sản phẩm')</th>
                                    @can('admin.hotel.room.product.all')
                                    <th>@lang('Hành động')</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="pagination" class="mt-3">

        </div>
    </div>
</div>
@can('')
@push('breadcrumb-plugins')
<button type="button" class="btn btn-outline--primary btn-add mt-1">
    <i class="las la-plus"></i>

</button>
@endpush
@endcan

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Thêm mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="roomsAddFacilityForm" method="POST" action="">
                    <input type="hidden" name="_method" id="method" value="POST">
                    <input type="hidden" name="id" id="recordId">
                    <div class="row">
                        <div class="form-group mb-3">
                            <label for="">Mã Phòng</label>
                            <select name="room_id" id="room-multiple-choice" class="form-control">
                                <option value="" selected>--Chọn mã phòng--</option>
                                @foreach ($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->code }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group mb-3">
                            <label for="">Các sản phẩm <code>(Được chọn nhiều)</code></label>

                            <div class="form-check-group mt-3 row" style="max-height: 250px; overflow-y: auto;">
                                @if ($products->isNotEmpty())
                                @foreach ($products as $product)
                                <div class=" mb-4" style="display: flex">
                                    <!-- Sử dụng col-12 để đảm bảo mỗi item chiếm toàn bộ chiều rộng -->
                                    <div class="form-check" style="flex: 70%">
                                        <input class="form-check-input product-checkbox" type="checkbox"
                                            value="{{ $product->id }}" name="product_id[]"
                                            id="checkbox-facility-add-{{ $product->id }}">
                                        <label class="form-check-label limit_name"
                                            for="checkbox-facility-add-{{ $product->id }}">
                                            {{ $product->name }}
                                        </label>
                                    </div>
                                    <div style="flex: 30%">
                                        <input type="number" name="stock[]"
                                            class="input_number form-control form-control-sm" disabled
                                            style="max-width: 80px" id="stock-{{ $product->id }}"
                                            max="{{ $product->stock }}" min="1" oninput="validateInput(this)">
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <p>Chưa có sản phẩm nào!</p>
                                @endif
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
<div class="modal fade" id="showEditRoomFacility" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Cập nhật</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="roomsEditFacilityForm">
                    <input type="hidden" name="_method" id="method" value="POST">
                    <input type="hidden" name="id" id="recordId">
                    <div class="row">
                        <div class="form-group mb-3">
                            <label for="">Mã phòng</label>
                            <select name="room_id" id="room-choice" class="form-control">

                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group mb-3">
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

@push('script')
<script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>
<script src="{{ asset('assets/admin/js/dataTable.js') }}"></script>

<script>
    (function($) {
            "use strict"
            $(document).ready(function() {
                const apiUrl = '{{ route('admin.hotel.room.product.all') }}';
                initDataFetch(apiUrl);

                $("#roomsAddFacilityForm").on('submit', function(e) {
                    e.preventDefault();
                    const method = $('#method').val();
                    const url = method === 'PUT' ? "{{ route('admin.manage.price.update', ':id') }}"
                        .replace(
                            ':id', $(
                                '#recordId').val()) :
                        "{{ route('admin.hotel.room.product.store') }}";

                    $.ajax({
                        type: method,
                        url: url,
                        data: $(this).serializeArray(),
                        success: function(response) {
                            if (response.status) {
                                showSwalMessage('success', response.message);
                                $('#staticBackdrop').modal('hide');
                                $('#roomsAddFacilityForm')[0].reset(); // Reset form
                                // Reset lại các input với class "input_number" nếu có
                                $('#staticBackdrop').find('.input_number').val('').prop('disabled', true);
                                // Reset lại các checkbox nếu có
                                $('#staticBackdrop').find('.form-check-input').prop('checked', false);

                                initDataFetch(apiUrl);
                            } else {
                                $('input').removeClass('is-invalid');
                                $(`#${response.key}`).addClass('is-invalid');

                                showSwalMessage('error', response.message);
                            }

                        }
                    });
                })

                $(document).on('click', '.btn-edit', function() {
                    let id = $(this).data('id');

                    $.ajax({
                        type: "GET",
                        url: "{{ route('admin.hotel.room.product.edit', ':id') }}".replace(':id', id),
                        success: function(response) {
                            if (response.status) {
                                $('#showEditRoomFacility').modal('show');

                                // Tạo danh sách phòng
                                let roomSelect = $('#room-choice');
                                roomSelect.empty();
                                response.rooms.forEach(room => {
                                    let selected = room.id === response.roomEdit.id ? 'selected' : '';
                                    roomSelect.append(`<option value="${room.id}" ${selected}>${room.code}</option>`);
                                });
                                roomSelect.trigger('change');

                                // Tạo danh sách tiện ích (amenities)
                                let amenitesContainer = $('#checkbox-facility');
                                amenitesContainer.empty();
                                console.log(response.products);
                                response.products.forEach(facility => {
                                let checked = response.selectedproducts.hasOwnProperty(facility.id) ? 'checked' : '';
                                let disable = !checked ? 'disabled' : '';
                                let quantity = checked ? response.selectedproducts[facility.id] : '';

                                amenitesContainer.append(`
                                    <div class=" mb-4 edit-checkbox" style="display: flex">
                                        <div class="form-check" style="flex: 70%" >
                                            <input class="form-check-input product-checkbox-edit" type="checkbox" value="${facility.id}" ${checked}
                                                name="product_id[]" id="checkbox-facility-${facility.id}">
                                            <label class="form-check-label limit_name" for="checkbox-facility-${facility.id}">
                                                ${facility.name}
                                            </label>
                                        </div>
                                        <div style="flex: 30%">
                                            <input type="number" name="stock[]" data-stock="${quantity}" class="input_number form-control form-control-sm" style="max-width: 80px" ${disable} value="${quantity}"
                                                id="stock-${facility.id}" min="1" max="${facility.stock}" oninput="validateInput(this)">
                                        </div>
                                    </div>


                                `);
                            });

                            // Lắng nghe sự kiện khi click vào checkbox
                            $(document).on('click', '.product-checkbox-edit', function() {
                                let checkbox = $(this);

                                let inputNumber = checkbox.closest('.edit-checkbox').find('.input_number');

                                let savedQuantity = inputNumber.data('stock') ;
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



            });


        })(jQuery);
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

</script>

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
    @media(max-width:768px){
        .flex-nowrap{
            flex-wrap: nowrap !important;
        }
        .mobi-page{
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
    .limitname{
        display: inline-block;
        max-width: 120px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush

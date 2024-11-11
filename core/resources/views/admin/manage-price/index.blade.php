@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <!-- Khối bên phải: Danh sách danh mục -->
        <div class="col-md-12">
            <div class="border p-2">
                <div class="d-flex justify-content-between mb-3">
                    <div class="dt-length">
                        <select name="example_length" id="perPage" style=" padding: 1px 3px; margin-right: 8px;"
                            aria-controls="example" class="perPage">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select><label for="perPage"> entries per page</label>
                    </div>
                    <div class="search">
                        <label for="searchInput">Search:</label>
                        <input class="searchInput"
                            style="padding: 1px 3px; border: 1px solid rgb(121, 117, 117, 0.5); margin-left: 8px;"
                            type="search" placeholder="Tìm kiếm...">
                    </div>
                </div>
                <div class="card b-radius--10">
                    <div class="card-body p-0">
                        <div class="table-responsive--sm table-responsive">
                            <table class="table--light style--two table roomPricesTable" id="data-table">
                                <thead>
                                    <tr>
                                        <th>@lang('Tên loại giá')</th>
                                        <th>@lang('Loại giá')</th>
                                        <th>@lang('Giá mặc định')</th>
                                        @can('admin.manage.price.all')
                                            <th>@lang('Thứ,ngày')</th>
                                            <th>@lang('Ngày đặc biệt')</th>
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


    <div class="container">

        {{-- <h2>Thêm Giá Phòng</h2>

        <table class="table table-bordered" id="roomPricesTable">

            <thead>

                <tr>

                    <th>Tên Phòng</th>

                    <th>Loại Giá</th>

                    <th>Giá</th>

                </tr>

            </thead>

            <tbody> 
                <tr>
                     <td rowspan="4">Phòng 1</td>
                </tr>

                <tr>

                    

                    <td>Giá Giờ</td>

                    <td><input type="number" step="1000" class="form-control" id="pricePerHour1"></td>

                </tr>

                <tr>

                    <td>Giá Cả Ngày</td>

                    <td><input type="number" step="1000" class="form-control" id="fullDayPrice1" ></td>

                </tr>

                <tr>

                    <td>Giá Qua Đêm</td>

                    <td><input type="number" step="1000" class="form-control" id="overnightPrice1"
                            ></td>
                </tr>

             

            </tbody>

        </table> --}}

        <button class="btn btn-primary mt-2" onclick="addRoomPrices()">Thêm Giá</button>

        <button class="btn btn-success mt-2" id="addColumnBtn" data-toggle="modal" data-target="#addDayModal">Thêm
            Cột</button>

    </div>


    <!-- Modal -->
    <div class="modal fade" id="addDayModal" tabindex="-1" role="dialog" aria-labelledby="addDayModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDayModalLabel">Chọn Ngày</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="dateSelectionForm">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Monday" id="dayMonday"
                                name="selectedDays[]">
                            <label class="form-check-label" for="dayMonday">Thứ Hai</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Tuesday" id="dayTuesday"
                                name="selectedDays[]">
                            <label class="form-check-label" for="dayTuesday">Thứ Ba</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Wednesday" id="dayWednesday"
                                name="selectedDays[]">
                            <label class="form-check-label" for="dayWednesday">Thứ Tư</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Thursday" id="dayThursday"
                                name="selectedDays[]">
                            <label class="form-check-label" for="dayThursday">Thứ Năm</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Friday" id="dayFriday"
                                name="selectedDays[]">
                            <label class="form-check-label" for="dayFriday">Thứ Sáu</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" onclick="addDayColumn()">Thêm Cột Ngày</button>
                </div>
            </div>
        </div>
    </div>




    @can('')
        @push('breadcrumb-plugins')
            <button type="button" class="btn btn-outline--primary btn-add">
                Thêm mới
            </button>
        @endpush
    @endcan

    {{-- modal  --}}
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Thêm mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="priceForm" method="POST" action="">
                        <input type="hidden" name="_method" id="method" value="POST">
                        <input type="hidden" name="id" id="recordId">
                        <div class="row">
                            <div class="form-group mb-3 col-lg-6">
                                <label for="">Phòng</label>
                                <select name="room_id" id="room_id" class="form-control">
                                    <option value="">Chọn mã phòng (Tên phòng)</option>
                                    @foreach ($rooms as $id => $room)
                                        <option @selected(old('room_id', $room->id) == $id) value="{{ $room->id }}">
                                            {{ $room->code }} ({{ $room->room_number }})</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- @php
                                if(empty($selectedTypePrice)){
                                    $selectedTypePrice = [];
                                }
                            @endphp
                            <div class="form-group mb-3 col-lg-6">
                                <label for="price_type_id">Loại Giá</label>
                                <select name="price_type_id[]" id="price_type_id" class="select2-multi-select" multiple>
                                    @foreach ($priceTypes as $id => $priceType)
                                        <option value="{{ $priceType->id }}"   @if (in_array($id, $selectedTypePrice)) selected @endif>{{ $priceType->name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="form-group mb-3 col-lg-6">
                                <label for="price_type_id">Loại Giá</label>
                                <select name="price_type_id" id="price_type_id" class="form-control">
                                    <option value="">Hãy chọn loại giá</option>
                                    @foreach ($priceTypes as $id => $priceType)
                                        <option value="{{ $priceType->id }}">{{ $priceType->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="day_of_week_id">Ngày trong tuần</label>
                                <select name="day_of_week_id" class="form-control">
                                    <option value="">Chọn nếu không phải ngày lễ</option>
                                    @foreach ($daysOfWeek as $day)
                                        <option value="{{ $day->id }}">{{ $day->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3 col-lg-6">
                                <label for="holiday_date">Ngày lễ (nếu có)</label>
                                <input type="date" name="holiday_date" class="form-control">
                            </div>


                            <div class="form-group mb-3 col-lg-6">
                                <label for="">Giá giờ đầu tiên</label>
                                <input type="text" name="first_hour" id="first_hour" placeholder="Giá giờ đầu tiên"
                                    class="form-control">
                            </div>

                            <div class="form-group mb-3 col-lg-6">
                                <label for="">Giá giờ tiếp theo</label>
                                <input type="text" name="additional_hour" id="additional_hour"
                                    placeholder="Giá giờ tiếp theo" class="form-control">
                            </div>

                            <div class="form-group mb-3 col-lg-6">
                                <label for="">Giá qua đêm</label>
                                <input type="text" name="overnight" id="overnight" placeholder="Giá qua đêm"
                                    class="form-control">
                            </div>
                            <div class="form-group mb-3 col-lg-6">
                                <label for="">Giá cả ngày</label>
                                <input type="text" name="full_day" id="full_day" placeholder="Giá cả ngày"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Thực hiện</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <!-- Modal Giá -->
    <div class="modal fade" id="priceModal" tabindex="-1" role="dialog" aria-labelledby="priceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="priceModalLabel">Nhập Giá</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" onclick="closePriceModal()">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="number" class="form-control" placeholder="Nhập giá tại đây...">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closePriceModal()"
                        data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary">Lưu</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('assets/admin/js/dataTable.js') }}"></script>
    <script src="{{ asset('assets/admin/js/fontawesome-iconpicker.js') }}"></script>

    <script>
        // Biến để lưu trữ danh sách các cột đã được thêm
        let addedColumns = [];
        let columnCounter = 1;

        function addDayColumn() {
            const table = document.querySelector(".roomPricesTable");
            const tableRows = table.getElementsByTagName('tr');
            const selectedDays = Array.from(document.querySelectorAll('input[name="selectedDays[]"]:checked'))
                .map(day => day.value);

            selectedDays.forEach(day => {
                if (!addedColumns.includes(day)) {
                    const headerRow = tableRows[0];
                    const headerCell = document.createElement('th');
                    headerCell.textContent = day;

                    // Thêm nút xóa vào tiêu đề cột
                    const deleteButton = document.createElement('button');
                    deleteButton.className = "btn btn-danger btn-sm ms-2";
                    deleteButton.textContent = "Xóa";
                    deleteButton.onclick = function() {
                        removeDayColumn(day);
                    };
                    headerCell.appendChild(deleteButton);

                    headerCell.dataset.day = day;
                    headerRow.appendChild(headerCell);

                    for (let i = 1; i < tableRows.length; i++) {
                        const row = tableRows[i];
                        const inputCell = document.createElement('td');
                        const input = document.createElement('input');
                        input.type = "number";
                        input.step = "1000";
                        input.className = "form-control";
                        input.placeholder = "Nhập giá";
                        input.id = `input-${day}-${i}-${columnCounter}`;
                        inputCell.dataset.day = day;
                        // Gán sự kiện click để mở modal
                        input.addEventListener("click", () => openPriceModal(day, i));
                        inputCell.appendChild(input);
                        row.appendChild(inputCell);
                    }

                    addedColumns.push(day);
                    columnCounter++;
                }
            });
            $('#addDayModal').modal('hide');
            document.getElementById("dateSelectionForm").reset();
        }

        function removeDayColumn(day) {
            const table = document.querySelector(".roomPricesTable");
            const tableRows = table.getElementsByTagName('tr');

            // Xóa cột trong hàng tiêu đề
            const headerRow = tableRows[0];
            for (let i = 0; i < headerRow.cells.length; i++) {
                if (headerRow.cells[i].dataset.day === day) {
                    headerRow.deleteCell(i);
                    break;
                }
            }

            // Xóa cột trong các hàng dữ liệu
            for (let i = 1; i < tableRows.length; i++) {
                const row = tableRows[i];
                for (let j = 0; j < row.cells.length; j++) {
                    if (row.cells[j].dataset.day === day) {
                        row.deleteCell(j);
                        break;
                    }
                }
            }

            // Xóa ngày đó khỏi danh sách đã thêm
            addedColumns = addedColumns.filter(column => column !== day);
        }

        function openPriceModal(day, rowIndex) {
            document.getElementById("priceModalLabel").textContent = `Nhập giá cho ${day} - Dòng ${rowIndex}`;
            $('#priceModal').modal('show');
        }

        function closePriceModal() {
            $('#priceModal').modal('hide');
        }
        initSelect2MultiSelect();
        initSelect2AutoTokenize();

        var dropdownParent;

        function initSelect2MultiSelect(selector = null) {

            if (selector) {
                dropdownParent = $(selector).parents('.form-group');

                $(selector).select2({
                    dropdownParent: dropdownParent
                });
            } else {
                $.each($('.select2-multi-select'), function(indexInArray, selector) {
                    dropdownParent = $(selector).parents('.form-group');
                    $(selector).select2({
                        dropdownParent: dropdownParent
                    });
                });
            }
        }

        function initSelect2AutoTokenize(selector = null) {

            if (selector) {
                dropdownParent = $(selector).parents('.form-group');

                $(selector).select2({
                    dropdownParent: dropdownParent
                });
            } else {
                $.each($('.select2-auto-tokenize'), function(indexInArray, selector) {
                    dropdownParent = $(selector).parents('.form-group');
                    $(selector).select2({
                        dropdownParent: dropdownParent,
                        tags: true,
                        tokenSeparators: [',']
                    });
                });
            }
        }




        // Sự kiện khi checkbox không được chọn nữa

        document.querySelectorAll('input[name="selectedDays[]"]:checked]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const day = this.value;
                const table = document.getElementById("roomPricesTable");

                // Xóa tất cả các cột trong thead và tbody có data-day tương ứng
                if (!this.checked) {
                    const headerRow = table.getElementsByTagName('thead')[0].getElementsByTagName('tr')[0];
                    const headerCells = headerRow.getElementsByTagName('th');

                    // Xóa cột trong thead
                    for (let i = 0; i < headerCells.length; i++) {
                        const cell = headerCells[i];
                        if (cell.getAttribute('data-day') === day) {
                            cell.remove();
                        }
                    }

                    // Xóa cột trong tbody
                    const tbodyRows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

                    for (let i = 0; i < tbodyRows.length; i++) {
                        const cells = tbodyRows[i].getElementsByTagName('td');
                        for (let j = 0; j < cells.length; j++) {
                            const cell = cells[j];
                            if (cell.getAttribute('data-day') === day) {
                                cell.remove();
                            }
                        }
                    }
                }
            });
        });
    </script>
    <script>
        (function($) {
            "use strict"
            $(document).ready(function() {

                // function formatFee(input) {
                //     let value = input.value.replace(/[^0-9]/g, '');
                //     value = new Intl.NumberFormat('vi-VN').format(value);
                //     input.value = value;
                // }

                // document.getElementById('price').addEventListener('input', function() {
                //     formatFee(this);
                // });

                const apiUrl = '{{ route('admin.manage.price.all') }}';
                initDataFetch(apiUrl);

                $("#priceForm").on('submit', function(e) {
                    e.preventDefault();



                    const method = $('#method').val();
                    const url = method === 'PUT' ? "{{ route('admin.manage.price.update', ':id') }}"
                        .replace(
                            ':id', $(
                                '#recordId').val()) : "{{ route('admin.manage.price.store') }}";

                    $.ajax({
                        type: method,
                        url: url,
                        data: $(this).serializeArray(),
                        success: function(response) {
                            if (response.status) {
                                showSwalMessage('success', response.message);
                                $('#staticBackdrop').modal('hide');
                                initDataFetch(apiUrl);
                                window.location.href = window.location.href;
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
                        url: "{{ route('admin.manage.price.edit', ':id') }}".replace(':id',
                            id),
                        success: function(response) {

                            if (response.status) {
                                $('#staticBackdropLabel').text('Cập nhật');
                                $('#room_id').val(response.data.room_id);
                                $('#first_hour').val(response.data.first_hour);
                                $('#additional_hour').val(response.data.additional_hour);
                                $('#full_day').val(response.data.full_day);
                                $('#overnight').val(response.data.overnight);
                                $('#method').val('PUT');
                                $('#recordId').val(id);
                                $('#staticBackdrop').modal('show');
                            }
                        }
                    });
                });

                $(document).on('click', '.btn-add', function() {
                    $('#priceForm')[0].reset();
                    $('#method').val('POST'); // Đặt phương thức thành POST
                    $('#staticBackdropLabel').text(
                        'Thêm mới'); // Đặt lại tiêu đề là "Thêm mới"
                    $('#staticBackdrop').modal('show');
                });


                $(document).on('click', '.btn-delete', function() {
                    let id = $(this).data('id');

                    Swal.fire({
                        title: 'Xóa vĩnh viễn',
                        text: 'Bạn có chắc chắn không?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Đồng ý',
                        cancelButtonText: 'Huỷ'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "DELETE",
                                url: "{{ route('admin.manage.price.destroy', ':id') }}"
                                    .replace(':id', id),
                                success: function(response) {
                                    if (response.status) {
                                        showSwalMessage('success', response
                                            .message);
                                        initDataFetch(apiUrl);
                                    } else {
                                        showSwalMessage('error', response
                                            .message);
                                    }
                                }
                            });
                        }
                    })
                })

                $(document).on('change', '.status-change', function() {
                    let checkbox = $(this);
                    let id = checkbox.data('id');


                    let isChecked = checkbox.is(':checked');

                    Swal.fire({
                        title: 'Thay đổi trạng thái',
                        text: 'Bạn có chắc chắn thay đổi trạng thái?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Đồng ý',
                        cancelButtonText: 'Huỷ'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "PUT",
                                url: "{{ route('admin.manage.price.updateStatus', ':id') }}"
                                    .replace(':id', id),
                                success: function(response) {
                                    if (response.status) {
                                        showSwalMessage('success', response
                                            .message);
                                        initDataFetch(apiUrl);
                                    } else {
                                        checkbox.prop('checked', !
                                            isChecked);
                                        showSwalMessage('error', response
                                            .message);
                                    }
                                }
                            });
                        } else {
                            checkbox.prop('checked', !
                                isChecked);
                        }
                    });
                });

            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        input[type="number"] {

            width: 80px;

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
            /* Màu xanh cho Hoạt động */
        }

        .radio-group label {
            margin-right: 20px;
            font-size: 16px;
        }


        .table-bordered {
            border: 1px solid #000;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000;
        }
    </style>
@endpush

@push('style-lib')
@endpush

@push('script-lib')
@endpush

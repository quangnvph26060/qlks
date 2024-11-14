@extends('admin.layouts.app')
@section('panel')
    {{-- <div class="row">
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
                            <table class="table--light style--two table" id="data-table">
                                <thead>
                                    <tr>
                                        <th>@lang('STT')</th>
                                        <th>@lang('Mã giá')</th>
                                        <th>@lang('Tên loại giá')</th>
                                        <th>@lang('Giá trị')</th>
                                        <th>@lang('Thời gian bắt đầu')</th>
                                        <th>@lang('Thời gian kết thúc')</th>
                                        <th>@lang('Trạng thái')</th>
                                        @can('admin.manage.price.all')
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
    </div> --}}
    <div class="bodywrapper__inner">

        <div class="d-flex justify-content-between">
            <h2>Thêm Giá Phòng</h2>
            <button type="button" class="btn btn-primary main-add-day" id="addColumnBtn" data-toggle="modal"
                data-target="#addDayModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M13 4v7h7v2h-7v7h-2v-7H4v-2h7V4h2Z" />
                </svg>
                Thứ,Ngày
            </button>
        </div>

        <div class="table-responsive--md table-responsive">
            <table class="table table-bordered table--light style--two table table-striped" id="data-table">
                <thead>
                    <tr>
                        <th>Tên Phòng</th>
                        <th class="small-column">Loại Giá</th>
                        <th>Giá</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($rooms as $item)
                        @php
                            $regularRoom = $item->regularRoom();
                        @endphp
                        <tr>
                            <td rowspan="3"> {{ $item->room_number }} </td>

                            <td class="small-column">Giá Giờ</td>

                            <td>
                                <div class="d-flex price-hour price-input" id="priceContainerHour">
                                    <p class="d-flex text-end">Từ giờ đầu tiên: &nbsp;<br>Mỗi giờ:</p>
                                    <input type="number" step="1000" class="form-control" data-id="{{ $item->id }}"
                                        id="pricePerHour" value="{{ $regularRoom ? $regularRoom->hourly_price : '' }}"
                                        placeholder="Nhập giá">
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="small-column">Giá Cả Ngày</td>
                            <td><input type="number" step="1000"class="form-control mf-table fullDayPrice"
                                    data-id="{{ $item->id }}"
                                    value="{{ $regularRoom ? $regularRoom->daily_price : '' }}" placeholder="Nhập giá"></td>
                        </tr>

                        <tr>

                            <td class="small-column">Giá Qua Đêm</td>

                            <td><input type="number" step="1000" class="form-control mf-table overnightPrice"
                                    data-id="{{ $item->id }}"
                                    value="{{ $regularRoom ? $regularRoom->overnight_price : '' }}" placeholder="Nhập giá">
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- <button class="btn btn-primary" onclick="addRoomPrices()">Thêm Giá</button> --}}

        {{-- <button class="btn btn-success" id="addColumnBtn" data-toggle="modal" data-target="#addDayModal">Thêm
            Cột</button> --}}

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
                    <form id="dateSelectionForm" class="d-flex flex-wrap">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="Monday" id="dayMonday">
                            <label class="form-check-label" for="dayMonday">Thứ Hai</label>
                        </div>
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="Tuesday" id="dayTuesday">
                            <label class="form-check-label" for="dayTuesday">Thứ Ba</label>
                        </div>
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="Wednesday" id="dayWednesday">
                            <label class="form-check-label" for="dayWednesday">Thứ Tư</label>
                        </div>
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="Thursday" id="dayThursday">
                            <label class="form-check-label" for="dayThursday">Thứ Năm</label>
                        </div>
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="Friday" id="dayFriday">
                            <label class="form-check-label" for="dayFriday">Thứ Sáu</label>
                        </div>
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="Saturday" id="daySaturday">
                            <label class="form-check-label" for="daySaturday">Thứ Bảy</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Sunday" id="daySunday">
                            <label class="form-check-label" for="daySunday">Chủ Nhật</label>
                        </div>

                    </form>
                    <div>
                        <p>Ngày</p> <input type="text" id="selectedDates" class="form-group" placeholder="Chọn ngày"
                            style="width: 100%;">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="addDayColumn">Thêm Cột Ngày</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="priceModal" tabindex="-1" aria-labelledby="priceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="priceModalLabel">Cập nhật giá</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class=" d-flex justify-content-start align-items-center gap-4 mb-3">
                        <label for="firstHour" class="form-label mt-2">Từ giờ đầu tiên:</label>
                        <input type="number" class="form-control" id="firstHour" placeholder="Nhập giá giờ đầu tiên">
                        <svg style="cursor: pointer;" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                            viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="square" stroke-linejoin="round"
                                stroke-width="2" d="M12 6v12m6-6H6" />
                        </svg>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="saveButton">Lưu</button>
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
    {{-- <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
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
                                <label for="">Mã giá</label>
                                <input type="text" class="form-control" id="code" name="code"
                                    placeholder="Mã giá">
                            </div>
                            <div class="form-group mb-3 col-lg-6">
                                <label for="">Tên loại giá</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Nhập loại giá">
                            </div>

                            <div class="form-group mb-3 col-lg-6">
                                <label for="">Ngày bắt đầu</label>

                                <input type="date" name="start_date" class="form-control"
                                    placeholder="Chọn ngày và giờ" />
                            </div>

                            <div class="form-group mb-3 col-lg-6">
                                <label for="">Ngày kết thúc</label>

                                <input type="date" name="end_date" class="form-control"
                                    placeholder="Chọn ngày và giờ" />
                            </div>
                            <div class="form-group mb-3 col-lg-6">
                                <label for="">Thời gian bắt đầu</label>
                                <input type="time" name="start_time" class="form-control"
                                    placeholder="Chọn ngày và giờ" />
                            </div>

                            <div class="form-group mb-3 col-lg-6">
                                <label for="">Thời gian kết thúc</label>
                                <input type="time" name="end_time" class="form-control"
                                    placeholder="Chọn ngày và giờ" />
                            </div>
                            <div class="form-group mb-3 col-lg-6">
                                <label for="">Ngày đặc biệt</label>
                                <input type="date" name="specific_date" class="form-control"
                                    placeholder="Chọn ngày và giờ" />
                            </div>
                            <div class="form-group mb-3 col-lg-12">
                                <label for="">Giá trị</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="price" name="price"
                                        placeholder="Nhập giá">
                                    <span class="input-group-text">{{ __(gs()->cur_text) }}</span>
                                </div>
                            </div>

                            <div class="form-group mb-3 col-lg-12">
                                <div class="radio-container" style="justify-content: left; gap: 10px">
                                    <label for="">Trạng thái</label>
                                    <label class="toggle">
                                        <input type="checkbox" name="status" id="status" checked>
                                        <span class="slider"></span>
                                    </label>
                                </div>
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
    </div> --}}
@endsection
@push('style-lib')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush
@push('script')


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('assets/admin/js/dataTable.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#selectedDates", {
            mode: "multiple",
            dateFormat: "Y-m-d",
        });



    </script>






    <script>
        $('body').on('click', '#priceContainerHour', function() {
            var dataId = $(this).find('#pricePerHour').data('id');
            var dataDate = $(this).find('#pricePerHour').data('date');
            var price = $(this).find('#pricePerHour').val();

            $('#priceModal').find('#firstHour').attr('data-date', dataDate);
            $('#priceModal').find('#firstHour').attr('data-id', dataId);
            $('#priceModal').find('#firstHour').val(price);

            $('#priceModal').modal('show');
        });
        $('#priceModal').on('hidden.bs.modal', function(e) {
            $('#priceModal').find('#firstHour').attr('data-id', '');
            $('#priceModal').find('#firstHour').attr('data-date', '');
        });
        // giá giờ tiếp theo    
        $(document).ready(function() {
            $('#priceModal').on('click', 'svg', function() {
                var newHtml =
                    '<div class="d-flex justify-content-start align-items-center gap-4 mb-3 added-content">' +
                    '<label for="secondHour" class="form-label mt-2">Từ giờ thứ ' + ($('.added-content')
                        .length + 1) + ':</label>' +
                    '<input type="number" class="form-control" id="secondHour-' + ($('.added-content')
                        .length + 1) + '" placeholder="Nhập giá giờ thứ hai">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" class="svg-close"  width="20" height="20" viewBox="0 0 48 48">' +
                    '<g fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="4"><path d="M8 8L40 40"/><path d="M8 40L40 8"/></g>' +
                    '</svg>' +
                    '</div>';

                $('#priceModal .modal-body').append(newHtml);
                console.log('123');

            });

            $('#priceModal').on('click', '.svg-close', function(event) {
                event.stopPropagation(); // Ngăn chặn sự kiện click từ lan rộng đến các phần tử cha
                $(this).closest('.added-content').remove();
            });
        });

        $(document).ready(function() {
            function sendAjaxRequest(price, dataId, dataDate, method) {
                var url = '{{ route('admin.price.switchPrice') }}';

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        price: price,
                        room_id: dataId,
                        method: method,
                        date: dataDate ?? "",
                    },
                    success: function(response) {
                        if (response.status === "success") {
                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });

                        }
                        // Xử lý dữ liệu trả về từ server nếu cần
                    },
                    error: function(xhr, status, error) {
                        console.error('Đã xảy ra lỗi trong quá trình gửi dữ liệu: ' + error);
                    }
                });
            }

            // Giá cả ngày
            $(document).on('blur', '.fullDayPrice', function() {
                var price = $(this).val();
                var dataId = $(this).data('id');
                var dataDate = $(this).data('date');

                let method = dataDate !== undefined && dataDate !== "" ? 'method_fulldaydate' :
                    'method_fullday';
                if (price !== "") {
                    sendAjaxRequest(price, dataId, dataDate, method);
                }
            });

            // Giá qua đêm
            $(document).on('blur', '.overnightPrice', function() {
                var price = $(this).val();
                var dataId = $(this).data('id');
                var dataDate = $(this).data('date');

                let method = dataDate !== undefined && dataDate !== "" ? 'method_overnightPricedate' :
                    'method_overnightPrice';
                if (price !== "") {
                    sendAjaxRequest(price, dataId, dataDate, method);
                }

            });

            // Giá giờ
            $('#saveButton').on('click', function() {
                var price = $('#firstHour').val();
                var dataId = $('#firstHour').data('id');
                var dataDate = $('#firstHour').data('date');
                let method = dataDate !== undefined && dataDate !== "" ? 'method_hourlydate' :
                    'method_hourly';
                if (price !== "") {
                    sendAjaxRequest(price, dataId, dataDate, method);
                }
            });
        });



        // Biến để lưu trữ danh sách các cột đã được thêm
        $(document).ready(function() {
            let addedColumns = [];

            $('#addDayColumn').on('click', function() {

                const table = document.getElementById("data-table");
                const tableRows = table.getElementsByTagName('tr');
                const selectedDate = $('#selectedDates').val();
                //  const selectedDate = '2024-11-15';

                const selectedDays = Array.from(document.querySelectorAll('input[type=checkbox]:checked'))
                    .map(day => day.value);


                let columnsAdded = false;
                let dateColumnAdded = false;
                if (selectedDate) {
                    console.log('123');
                    // Kiểm tra xem cột cho ngày đã tồn tại trong bảng chưa
                    let dateColumnExists = false;
                    const headerRow = tableRows[0];


                    for (let i = 0; i < headerRow.children.length; i++) {
                        if (headerRow.children[i].dataset.date === selectedDate) {
                            dateColumnExists = true;
                            break;
                        }
                    }

                    if (!dateColumnExists) {
                        const headerCell = document.createElement('th');
                        headerCell.textContent = selectedDate;
                        headerCell.dataset.date = selectedDate;
                        headerRow.appendChild(headerCell);

                        let elseCount = 0;
                        for (let i = 1; i < tableRows.length; i++) {
                            const row = tableRows[i];
                            const inputCell = document.createElement('td');
                            inputCell.dataset.date = selectedDate;
                            const input = row.querySelector('input');
                            // Lấy giá trị data-id từ input
                            const dataId = input.dataset.id;

                            if (i % 3 === 1) { // Row for "Giá Giờ"

                                const div = document.createElement('div');
                                div.className = "d-flex price-hour";
                                div.id = "priceContainerHour";

                                const p = document.createElement('p');
                                p.className = "d-flex text-end";
                                p.innerHTML = "Từ giờ đầu tiên: &nbsp;<br>Mỗi giờ:";

                                const input = document.createElement('input');

                                input.type = "number";
                                input.step = "1000";
                                input.className = "form-control";
                                input.placeholder = "Nhập giá";
                                // input.id = "input-" + selectedDate + "-pricePerHour";
                                input.id = "pricePerHour";
                                input.dataset.id = dataId;
                                input.dataset.date = selectedDate;
                                div.appendChild(p);
                                div.appendChild(input);
                                inputCell.appendChild(div);
                            } else {
                                const input = document.createElement('input');
                                input.type = "number";
                                input.step = "1000";
                                input.className = "form-control mf-table";
                                input.placeholder = "Nhập giá";

                                if (elseCount % 2 === 0) {
                                    input.classList.add("fullDayPrice");
                                } else {
                                    input.classList.add("overnightPrice");
                                }
                                input.dataset.id = dataId;
                                input.dataset.date = selectedDate;
                                inputCell.appendChild(input);
                                elseCount++;

                            }

                            row.appendChild(inputCell);
                        }

                        dateColumnAdded = true;
                    } else {
                        console.log('Cột cho ngày đã tồn tại trong bảng.');
                    }
                }
                if (dateColumnAdded) {
                    $('#addDayModal').modal('hide');
                }

                selectedDays.forEach(day => {
                    if (!addedColumns.includes(day)) {
                        let dataDay = "";
                        switch (day) {
                            case "Monday":
                                dataDay = "Thứ Hai";
                                break;
                            case "Tuesday":
                                dataDay = "Thứ Ba";
                                break;
                            case "Wednesday":
                                dataDay = "Thứ Tư";
                                break;
                            case "Thursday":
                                dataDay = "Thứ Năm";
                                break;
                            case "Friday":
                                dataDay = "Thứ Sáu";
                                break;
                            case "Saturday":
                                dataDay = "Thứ Bảy";
                                break;
                            case "Sunday":
                                dataDay = "Chủ Nhật";
                                break;
                        }

                        const headerRow = tableRows[0];
                        const headerCell = document.createElement('th');
                        headerCell.textContent = dataDay;
                        headerCell.dataset.label = day;
                        headerRow.appendChild(headerCell);
                        let count = 0;
                        for (let i = 1; i < tableRows.length; i++) {
                            const row = tableRows[i];

                            const input = row.querySelector('input');

                            // Lấy giá trị data-id từ input
                            const dataId = input.dataset.id;

                            const inputCell = document.createElement('td');
                            inputCell.dataset.day = day;

                            if (i % 3 === 1) { // Row for "Giá Giờ"
                                const div = document.createElement('div');
                                div.className = "d-flex price-hour";
                                div.id = "priceContainerHour";

                                const p = document.createElement('p');
                                p.className = "d-flex text-end price-input";
                                p.innerHTML = "Từ giờ đầu tiên: &nbsp;<br>Mỗi giờ:";

                                // var dataId = document.querySelector('#pricePerHour').dataset.id;
                                // console.log(dataId);

                                const input = document.createElement('input');
                                input.type = "number";
                                input.step = "1000";
                                input.className = "form-control";
                                input.placeholder = "Nhập giá";
                                // input.id = "input-" + day + "-pricePerHour";
                                input.id = "pricePerHour";
                                input.dataset.id = dataId;
                                input.dataset.date = day;
                                div.appendChild(p);
                                div.appendChild(input);

                                inputCell.appendChild(div);
                            } else {
                                const input = document.createElement('input');
                                input.type = "number";
                                input.step = "1000";
                                input.className = "form-control mf-table";
                                input.placeholder = "Nhập giá";

                                if (count % 2 === 0) {
                                    input.classList.add("fullDayPrice");
                                } else {
                                    input.classList.add("overnightPrice");
                                }
                                input.dataset.id = dataId;
                                input.dataset.date = day;
                                inputCell.appendChild(input);
                                count++;
                            }

                            row.appendChild(inputCell);

                        }

                        addedColumns.push(day);
                        columnsAdded = true;
                    }
                });

                if (columnsAdded) {
                    $('#addDayModal').modal('hide');
                }
            });

            function addDayColumn(selectedDate) {
                const table = document.getElementById("data-table");
                const tableRows = table.getElementsByTagName('tr');
                if (selectedDate) {
                    console.log('123');
                    
                    // Kiểm tra xem cột cho ngày đã tồn tại trong bảng chưa
                    let dateColumnExists = false;
                    const headerRow = tableRows[0];


                    for (let i = 0; i < headerRow.children.length; i++) {
                        if (headerRow.children[i].dataset.date === selectedDate) {
                            dateColumnExists = true;
                            break;
                        }
                    }

                    if (!dateColumnExists) {
                        const headerCell = document.createElement('th');
                        headerCell.textContent = selectedDate;
                        headerCell.dataset.date = selectedDate;
                        headerRow.appendChild(headerCell);

                        let elseCount = 0;
                        for (let i = 1; i < tableRows.length; i++) {
                            const row = tableRows[i];
                            const inputCell = document.createElement('td');
                            inputCell.dataset.date = selectedDate;
                            const input = row.querySelector('input');
                            // Lấy giá trị data-id từ input
                            const dataId = input.dataset.id;

                            if (i % 3 === 1) { // Row for "Giá Giờ"

                                const div = document.createElement('div');
                                div.className = "d-flex price-hour";
                                div.id = "priceContainerHour";

                                const p = document.createElement('p');
                                p.className = "d-flex text-end";
                                p.innerHTML = "Từ giờ đầu tiên: &nbsp;<br>Mỗi giờ:";

                                const input = document.createElement('input');

                                input.type = "number";
                                input.step = "1000";
                                input.className = "form-control";
                                input.placeholder = "Nhập giá";
                                // input.id = "input-" + selectedDate + "-pricePerHour";
                                input.id = "pricePerHour";
                                input.dataset.id = dataId;
                                input.dataset.date = selectedDate;
                                div.appendChild(p);
                                div.appendChild(input);
                                inputCell.appendChild(div);
                            } else {
                                const input = document.createElement('input');
                                input.type = "number";
                                input.step = "1000";
                                input.className = "form-control mf-table";
                                input.placeholder = "Nhập giá";

                                if (elseCount % 2 === 0) {
                                    input.classList.add("fullDayPrice");
                                } else {
                                    input.classList.add("overnightPrice");
                                }
                                input.dataset.id = dataId;
                                input.dataset.date = selectedDate;
                                inputCell.appendChild(input);
                                elseCount++;

                            }

                            row.appendChild(inputCell);
                        }

                        dateColumnAdded = true;
                    } else {
                        console.log('Cột cho ngày đã tồn tại trong bảng.');
                    }
                }
            }
            $(document).ready(function() {
                addDayColumn('2024-11-15');
            });
            document.querySelectorAll('input[type=checkbox]').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const day = this.value;
                    const table = document.getElementById("data-table");

                    if (!this.checked) {
                        const headerRow = table.getElementsByTagName('thead')[0]
                            .getElementsByTagName('tr')[0];
                        const headerCells = headerRow.getElementsByTagName('th');

                        for (let i = 0; i < headerCells.length; i++) {
                            const cell = headerCells[i];
                            if (cell.getAttribute('data-day') === day) {
                                cell.remove();
                                const index = addedColumns.indexOf(day);
                                if (index > -1) {
                                    addedColumns.splice(index, 1);
                                }
                                break;
                            }
                        }

                        const tbodyRows = table.getElementsByTagName('tbody')[0]
                            .getElementsByTagName('tr');

                        for (let i = 0; i < tbodyRows.length; i++) {
                            const cells = tbodyRows[i].getElementsByTagName('td');
                            for (let j = 0; j < cells.length; j++) {
                                const cell = cells[j];
                                if (cell.getAttribute('data-day') === day) {
                                    cell.remove();
                                    break;
                                }
                            }
                        }
                    }
                });
            });

            $('#saveButton').on('click', function() {
                var firstHour = $('#firstHour').val();
                var perHour = $('#perHour').val();
                var selectValue = $('#selectOption').val();
                var customPrice = $('#customPrice').val();

                console.log('Từ giờ đầu tiên: ' + firstHour);
                $('#priceModal').modal('hide');
            });


            // $('#')




        });
    </script>
@endpush

@push('style')
    <style>
        input[type="number"] {

            width: 180px;

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

        .mf-table {
            margin-left: 112px;
        }

        .small-column {
            width: 100px;
            white-space: nowrap;
        }


        .text-center {
            text-align: center;
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

        .swal2-popup {
            font-size: 0.8rem;
            max-width: 500px;
        }

        .radio-group input[type="radio"] {
            margin-right: 5px;
            accent-color: #007bff;
        }

        .table-striped>tbody>tr:nth-of-type(odd)>* {
            --bs-table-accent-bg: rgb(255 254 254 / 5%) !important;
        }

        .radio-group label {
            margin-right: 20px;
            font-size: 16px;
        }

        @media(max-width:768px) {
            .small-column {
                width: 100%;
            }

            .mf-table {
                margin-left: 0px;
            }

            .price-hour {
                white-space: nowrap;
                margin-left: -107px
            }
        }
    </style>
@endpush

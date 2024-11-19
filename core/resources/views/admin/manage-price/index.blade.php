@extends('admin.layouts.app')
@section('panel')
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
    <!-- Modal -->
    <div class="modal fade" id="myModalDate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Thông tin ngày đã chọn</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="updateDate">



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary btnUpdateDate" data-dismiss="modal">Lưu</button>
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
                        <svg style="cursor: pointer;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" id="add_hours"
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
    {{-- loading --}}
    <div id="loading" style="display: none;">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24">
            <path fill="none" stroke="currentColor" stroke-dasharray="15" stroke-dashoffset="15"
                stroke-linecap="round" stroke-width="2" d="M12 3C16.9706 3 21 7.02944 21 12">
                <animate fill="freeze" attributeName="stroke-dashoffset" dur="0.3s" values="15;0" />
                <animateTransform attributeName="transform" dur="1.5s" repeatCount="indefinite" type="rotate"
                    values="0 12 12;360 12 12" />
            </path>
        </svg>
    </div>
@endsection
@push('style-lib')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/global/css/manage-price.css') }}">
@endpush
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('assets/admin/js/dataTable.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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
            $('#priceModal .modal-body').html(`<div class=" d-flex justify-content-start align-items-center gap-4 mb-3">
                        <label for="firstHour" class="form-label mt-2">Từ giờ đầu tiên:</label>
                        <input type="number" class="form-control" id="firstHour" placeholder="Nhập giá giờ đầu tiên">
                        <svg style="cursor: pointer;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" id="add_hours"
                            viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="square" stroke-linejoin="round"
                                stroke-width="2" d="M12 6v12m6-6H6" />
                        </svg>
                    </div>`);
        });
        // giá giờ tiếp theo
        $(document).ready(function() {
            $('#priceModal').on('click', '#add_hours', function() {
                var newHtml =
                    '<div class="d-flex justify-content-start align-items-center gap-4 mb-3 added-content">' +
                    '<label for="secondHour" class="form-label mt-2">Từ giờ thứ ' + ($('.added-content')
                        .length + 2) + ':</label>' +
                    '<input type="number" class="form-control" id="secondHour-' + ($('.added-content')
                        .length + 2) + '" placeholder="Nhập giá giờ thứ ' + ($('.added-content')
                        .length + 2) + '" >' +
                    '<svg xmlns="http://www.w3.org/2000/svg" class="svg-close"  width="20" height="20" viewBox="0 0 48 48">' +
                    '<g fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="4"><path d="M8 8L40 40"/><path d="M8 40L40 8"/></g>' +
                    '</svg>' +
                    '</div>';

                $('#priceModal .modal-body').append(newHtml);

            });

            $('#priceModal').on('click', '.svg-close', function(event) {
                event.stopPropagation(); // Ngăn chặn sự kiện click từ lan rộng đến các phần tử cha
                $(this).closest('.added-content').remove();
                $('.added-content').each(function(index) {
                    $(this).find('label').text('Từ giờ thứ ' + (index + 2) + ':');
                    $(this).find('input').attr('id', 'secondHour-' + (index + 2));
                });
            });
        });

        $(document).ready(function() {
            function sendAjaxRequest(price, dataId, dataDate, method, prices) {
                var url = '{{ route('admin.price.switchPrice') }}';

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        price: price,
                        room_id: dataId,
                        method: method,
                        date: dataDate ?? "",
                        // pricehours : prices
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
                var prices = [];
                $('input[id^="secondHour-"]').each(function() {
                    var hour = $(this).attr('id').split('-')[1];
                    var pricehour = $(this).val();

                    if (pricehour !== "") {
                        prices.push([hour, pricehour]);
                    }
                });
                console.log('id = ' + dataDate);
                console.log(prices);

                let method = dataDate !== undefined && dataDate !== "" ? 'method_hourlydate' :
                    'method_hourly';
                if (price !== "") {
                    sendAjaxRequest(price, dataId, dataDate, method, prices);
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
                const selectedDates = selectedDate.split(',').map(date => date.trim());

                //  console.log(selectedDates);
                //  const selectedDate = '2024-11-15';

                const selectedDays = Array.from(document.querySelectorAll('input[type=checkbox]:checked'))
                    .map(day => day.value);


                let columnsAdded = false;
                let dateColumnAdded = false;
                if (selectedDates.length > 0) {

                    // Kiểm tra xem cột cho ngày đã tồn tại trong bảng chưa

                    const headerRow = tableRows[0];
                    selectedDates.forEach(selectedDate => {
                        let dateColumnExists = false;


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
                        }
                    });
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
                        headerCell.dataset.date = day;
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


            $(document).on('click', '.selectedDate_th', function() {
                var dateValue = $(this).data('date');


                if (!/\d/.test(dateValue)) {
                    $('#myModalDate').modal('show');
                    const htmlToAppend = `
                        <div id="dateSelectionForm" data-date="${dateValue}" class="d-flex flex-wrap">
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" value="Monday"    id="dayMonday" ${dateValue === "Monday" ? "checked" : ""}>
                                <label class="form-check-label" for="dayMonday">Thứ Hai</label>
                            </div>
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" value="Tuesday" id="dayTuesday" ${dateValue === "Tuesday" ? "checked" : ""}> 
                                <label class="form-check-label" for="dayTuesday">Thứ Ba</label>
                            </div>
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" value="Wednesday" id="dayWednesday" ${dateValue === "Wednesday" ? "checked" : ""}>
                                <label class="form-check-label" for="dayWednesday">Thứ Tư</label>
                            </div>
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" value="Thursday" id="dayThursday" ${dateValue === "Thursday" ? "checked" : ""}>
                                <label class="form-check-label" for="dayThursday">Thứ Năm</label>
                            </div>
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" value="Friday" id="dayFriday" ${dateValue === "Friday" ? "checked" : ""}>
                                <label class="form-check-label" for="dayFriday">Thứ Sáu</label>
                            </div>
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" value="Saturday" id="daySaturday" ${dateValue === "Saturday" ? "checked" : ""}>
                                <label class="form-check-label" for="daySaturday">Thứ Bảy</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Sunday" id="daySunday" ${dateValue === "Sunday" ? "checked" : ""}>
                                <label class="form-check-label" for="daySunday">Chủ Nhật</label>
                            </div>
                        </div>
                    `;

                    const modalBody = document.getElementById('updateDate');
                    $('.btnUpdateDate').attr('data-date', dateValue);
                    modalBody.insertAdjacentHTML('beforeend', htmlToAppend);


                } else {
                    $('#myModalDate').modal('show');
                    const inputElement = document.createElement('input');
                    inputElement.type = 'text';
                    inputElement.id = 'modalDateValue';
                    inputElement.className = 'form-group';
                    inputElement.placeholder = 'Chọn ngày';
                    inputElement.style.width = '100%';
                    const modalBody = document.getElementById('updateDate');
                    $('.btnUpdateDate').removeAttr('data-date');
                    modalBody.appendChild(inputElement);

                    $('#modalDateValue').val(dateValue);
                    $('#modalDateValue').attr('data-date', dateValue);

                    flatpickr("#modalDateValue", {
                        mode: "multiple",
                        dateFormat: "Y-m-d",
                    });
                }
                // Xóa modal khi được ẩn
                $('#myModalDate').on('hidden.bs.modal', function() {
                    const modalBody = document.getElementById('updateDate');
                    const inputElement = modalBody.querySelector('#modalDateValue');
                    const inputElement1 = modalBody.querySelector('#dateSelectionForm');
                    if (inputElement) {
                        inputElement.remove();
                    }
                    if (inputElement1) {
                        inputElement1.remove();
                    }
                });

            });

            $(document).on('click', '.btnUpdateDate', function() {
                var dataDayValue = $('.btnUpdateDate').data('date');
                var dateValue = $('#modalDateValue').val();
                var dataDateValue = $('#modalDateValue').data('date');
                var dateCurent = dataDateValue ?? dataDayValue;
                let method = "";
                if (!/\d/.test(dateValue)) {
                    method = "day";
                } else {
                    method = "date";
                }
                console.log(dateCurent);

                var checkedValues = [];
                $('#dateSelectionForm input[type="checkbox"]:checked').each(function() {
                    var value = $(this).val();
                    if (!checkedValues.includes(value)) {
                        checkedValues.push(value);
                    }
                });
                var url = '{{ route('admin.price.updatePriceDate') }}';
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        method: method,
                        dateCurent: dateCurent,
                        dataDateValue: dateValue,
                        checkedValues: checkedValues
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

            });

            function addDayColumn(selectedDate) {
                const table = document.getElementById("data-table");
                const tableRows = table.getElementsByTagName('tr');

                if (selectedDate) {
                    // Kiểm tra xem cột cho ngày đã tồn tại trong bảng chưa
                    let dateColumnExists = false;
                    const headerRow = tableRows[0];
                    let date = selectedDate.day_of_week ?? selectedDate.date;



                    for (let i = 0; i < headerRow.children.length; i++) {
                        if (headerRow.children[i].dataset.date === date) {
                            dateColumnExists = true;
                            break;
                        }
                    }

                    if (!dateColumnExists) {
                        const headerCell = document.createElement('th');
                        let dataDay = "";
                        switch (date) {
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
                            default:
                                dataDay = date;
                                break;
                        }
                        headerCell.textContent = dataDay;
                        headerCell.dataset.date = date;
                        headerCell.className = "selectedDate_th";
                        headerRow.appendChild(headerCell);
                        let elseCount = 0;
                        for (let i = 1; i < tableRows.length; i++) {
                            const row = tableRows[i];
                            const inputCell = document.createElement('td');
                            inputCell.dataset.date = date;
                            //  console.log(selectedDate);
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
                                // console.log(dataId);
                                // console.log( 'room_id: '. selectedDate);

                                input.dataset.date = date;


                                if (dataId == selectedDate.room_price_id) {
                                    input.value = selectedDate.hourly_price;
                                }
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
                                    if (dataId == selectedDate.room_price_id) {
                                        input.value = selectedDate.daily_price;
                                    }
                                    input.classList.add("fullDayPrice");
                                } else {
                                    input.classList.add("overnightPrice");
                                    if (dataId == selectedDate.room_price_id) {
                                        input.value = selectedDate.overnight_price;
                                    }
                                }
                                input.dataset.id = dataId;

                                input.dataset.date = date;
                                inputCell.appendChild(input);
                                elseCount++;

                            }

                            row.appendChild(inputCell);
                        }

                        dateColumnAdded = true;
                    } else {
                        const table = document.getElementById("data-table");
                        const tableRows = table.getElementsByTagName('tr');

                        for (let i = 1; i < tableRows.length; i++) {
                            const row = tableRows[i];
                            const inputs = row.getElementsByTagName('input');

                            for (let j = 0; j < inputs.length; j++) {
                                const input = inputs[j];
                                const dataId = input.dataset.id;
                                const dataDate = input.dataset.date;

                                if (dataId == selectedDate.room_price_id && dataDate == date) {
                                    if (input.id === "pricePerHour") {
                                        input.value = selectedDate.hourly_price;
                                    }
                                    if (input.classList.contains("fullDayPrice")) {
                                        input.value = selectedDate.daily_price;
                                    }
                                    if (input.classList.contains("overnightPrice")) {
                                        input.value = selectedDate.overnight_price;
                                    }

                                }
                            }
                        }
                    }
                }
            }
            $(document).ready(function() {

                var url = '{{ route('admin.price.roomPricePerDay') }}';
                $('#loading').show();
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        if (response.status === "success") {
                            // console.log(response.data);
                            let defaultDates = [];
                            if (typeof addDayColumn === 'function') {
                                $('#loading').hide();
                                // console.log(response.data);
                                response.data.forEach(function(item) {
                                    addDayColumn(item);
                                    const input = document.getElementById(
                                        'selectedDates');
                                    defaultDates.push(item.date);
                                    input.value = defaultDates.join(', ');
                                    flatpickr("#selectedDates", {
                                        mode: "multiple",
                                        dateFormat: "Y-m-d",
                                    });
                                });
                            }
                            // else {
                            //     console.error("Hàm addDayColumn chưa được định nghĩa!");
                            // }
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#loading').hide();
                        console.error('Đã xảy ra lỗi trong quá trình gửi dữ liệu: ' + error);
                    }
                });
                var url = '{{ route('admin.price.roomPricePerDayOfWeek') }}';
                $('#loading').show();
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        if (response.status === "success") {
                            // console.log(response.data);
                            let defaultDates = [];
                            $('#loading').hide();
                            if (typeof addDayColumn === 'function') {

                                response.data.forEach(function(item) {

                                    addDayColumn(item);
                                    // const input = document.getElementById(
                                    //     'selectedDates');
                                    // defaultDates.push(item.date);
                                    // input.value = defaultDates.join(', ');
                                    // flatpickr("#selectedDates", {
                                    //     mode: "multiple",
                                    //     dateFormat: "Y-m-d",
                                    // });
                                });
                            }
                            //  else {
                            //     console.error("Hàm addDayColumn chưa được định nghĩa!");
                            // }
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#loading').hide();
                        console.error('Đã xảy ra lỗi trong quá trình gửi dữ liệu: ' + error);
                    }
                });

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
                            if (cell.getAttribute('data-date') === day) {
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

                $('#priceModal').modal('hide');
            });
        });
    </script>
    <script>
        flatpickr("#selectedDates", {
            mode: "multiple",
            dateFormat: "Y-m-d",
        });
        flatpickr("#modalDateValue", {
            mode: "multiple",
            dateFormat: "Y-m-d",
        });
    </script>
@endpush



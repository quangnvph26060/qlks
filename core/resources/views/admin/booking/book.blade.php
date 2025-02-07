@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between mb-3 row order-1">
                <div class="dt-length col-md-6 col-4">
                    <select name="example_length" id="perPage" style=" padding: 1px 3px; margin-right: 8px;"
                        aria-controls="example" class="perPage">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select><label for="perPage"> entries per page</label>
                </div>
            </div>
        </div>
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--md">
                    <table class="table--light style--two table table-striped" id="data-table">
                        <thead>
                            <tr>

                                <th>@lang('STT')</th>
                                <th>@lang('Mã đặt hàng')</th>
                                <th>@lang('Mã phòng')</th>
                                <th>@lang('Ngày chứng từ')</th>
                                <th>@lang('Ngày nhận')</th>
                                <th>@lang('Ngày trả')</th>
                                <th>@lang('Tên khách hàng')</th>
                                <th>@lang('Số điện thoại')</th>
                                <th>@lang('Số người')</th>
                                <th>@lang('Thành tiền')</th>
                                <th>@lang('Đặt cọc')</th>
                                @can(['admin.hotel.room.type.edit', 'admin.hotel.room.type.status',
                                    'admin.hotel.room.type.destroy'])
                                    <th>@lang('Hành động')</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            <thead class="data-table">

                            </thead>
                        </tbody>
                    </table>

                </div>
            </div>
            <div id="pagination" class="m-3">

            </div>
        </div>
        <div class="pagination-container"></div>
    </div>

    @include('admin.booking.partials.room_booking')

    @include('admin.booking.partials.confirm-room')
@endsection

@can('admin.booking.all')
    @push('breadcrumb-plugins')
        {{-- <a class="btn btn-sm btn--primary" href="{{ route('admin.booking.all') }}">
            <i class="la la-list"></i>@lang('Tất cả các đặt phòng')
        </a> --}}
        <a class="btn btn-sm btn--primary add-book-room">
            <i class="la la-plus"></i>@lang('Đặt phòng')
        </a>
        <div class="modal fade" id="addRoomModal" tabindex="-1" aria-hidden="true" style="overflow: unset">
            <div class="modal-dialog modal-dialog-centered" style="top: 4px">
                <div class="modal-content" style="height: 100vh;">
                    <div class="modal-header">
                        <h5 class="modal-title">Chọn Phòng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class=" mt-2 d-flex mb-2" style="gap: 10px;justify-content: space-around;">
                        <div class="">
                            <label for="">Chọn hạng phòng</label>
                            <select class="form-select" id="selected-hang-phong">


                            </select>
                        </div>
                        <div class="">
                            <label for="">Chọn tên phòng</label>
                            <select class="form-select" id="selected-name-phong">

                            </select>
                        </div>
                        <div class="">
                            <label for="">Từ ngày</label>
                            <input type="date" class="form-control " id="date-chon-phong-in" style="height: 38px">
                        </div>
                        <div class="">
                            <label for="">Đến ngày</label>
                            <input type="date" class="form-control" id="date-chon-phong-out" style="height: 38px">
                        </div>
                        <div class="">
                            <label for="">Trạng thái phòng</label>
                            <select class="form-select" id="status-room">
                                <option value="">Chọn trạng tên phòng</option>
                                <option value="Trống">Trống</option>
                                <option value="Đã đặt">Đã đặt</option>
                                <option value="Đã nhận">Đã nhận</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-body overflow-add-room">
                        <table class="table ">
                            <thead>
                                <tr>
                                    <th data-table="Hạng phòng">Hạng phòng</th>
                                    <th data-table="Phòng">Tên phòng</th>
                                    <th data-table="Ngày">Ngày</th>
                                    <th data-table="Trạng thái phòng">Trạng thái phòng</th>
                                    <th data-table="Giá">Giá</th>
                                    <th data-table="Thao tác">Thao tác</th>
                                </tr>
                            </thead>

                            <tbody id="show-room">

                            </tbody>

                        </table>
                    </div>
                    <div class="d-flex justify-content-end" style="gap: 10px;padding: 7px 31px">
                        <p data-row="booked" class=" btn-dat-truoc  add-room-list" style="cursor: pointer">Lưu
                        </p>
                        <p type="button" data-row="booked" class="alert-paragraph close_modal_booked_room">Hủy</p>
                    </div>
                </div>
            </div>
        </div>
    @endpush
@endcan

@push('script-lib')
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/validator/validator.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/book.css') }}">
@endpush

@push('style')
    <style scoped>
        .table-responsive--md {
            overflow-x: auto;
            /* Enable horizontal scrolling if the table overflows */
        }

        /* Optional: Adjust the font size and padding for smaller screens */
        @media (max-width: 768px) {
            .table--light {
                font-size: 12px;
                /* Reduce font size on smaller screens */
            }

            .table td,
            .table th {
                padding: 5px;
                /* Reduce padding for more compact view */
            }
        }

        .booking-table td {
            white-space: unset;
        }

        .modal-open .select2-container {
            z-index: 9 !important;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination-container button {
            background-color: #4634ff;
            color: white;
            border: 1px solid #ddd;
            padding: 10px 15px;
            margin: 0 5px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .pagination-container button:hover {
            background-color: #4634ff;
            transform: scale(1.05);
        }

        .pagination-container button:disabled {
            background-color: #ddd;
            cursor: not-allowed;
        }

        .pagination-container button.active {
            background-color: #4634ff;
            border-color: #4634ff;
        }

        .pagination-container button:first-child {
            border-radius: 5px 0 0 5px;
        }

        .pagination-container button:last-child {
            border-radius: 0 5px 5px 0;
        }

        .background-primary {
            background: #0b138d;
        }

        .background-yellow {
            background-color: #eeddaa;
        }

        .background-white {
            color: #5b6e88;
            background-color: #f0f1f1;
        }

        .background-yellow td,
        .background-primary td {
            color: white !important;
        }
    </style>
@endpush

@push('script')
    <script>
        "use strict";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const start = moment();
        const end = moment().add(1, 'days');

        const date_booking = new Date();
        const date_yyyy = date_booking.getFullYear();
        const date_mm = String(date_booking.getMonth() + 1).padStart(2, '0');
        const date_dd = String(date_booking.getDate()).padStart(2, '0');
        const date_hour = String(date_booking.getHours()).padStart(2, '0'); // Giờ
        const date_minutes = String(date_booking.getMinutes()).padStart(2, '0'); // Phút

        const formattedDates = `${date_yyyy}-${date_mm}-${date_dd}`;
        const formattedTimes = `${date_hour}:${date_minutes}`;

        $('[id="date-book-room-booking"]').val(formattedDates);
        $('[id="time-book-room-booking"]').val(formattedTimes);

        // Tạo một đối tượng Date mới và cộng thêm 1 ngày
        const nextDay = new Date(date_booking);
        nextDay.setDate(date_booking.getDate() + 1);

        const nextDay_yyyy = nextDay.getFullYear();
        const nextDay_mm = String(nextDay.getMonth() + 1).padStart(2, '0');
        const nextDay_dd = String(nextDay.getDate()).padStart(2, '0');

        const formattedNextDay = `${nextDay_yyyy}-${nextDay_mm}-${nextDay_dd}`;
        $('[id="date-book-room-date"]').val(formattedNextDay);
        $('[id="time-book-room-date"]').val(formattedTimes);

        function formatCurrency(amount) {
            const parts = amount.toString().split('.');
            const integerPart = parts[0];
            const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            return formattedInteger + ' VND';
        }

        function formatNumber(input) {
            var value = input.value;
            var numericValue = value.replace(/[^0-9,]/g, '');
            var parts = numericValue.split(',');
            var integerPart = parts[0].replace(/\./g, '');
            var formattedValue = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            if (parts.length > 1) {
                formattedValue += ',' + parts[1];
            }
            input.value = formattedValue;
        }
        const dateRangeOptions = {
            minDate: start,
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment().add(1, 'days')],
                'Tomorrow': [moment().add(1, 'days'), moment().add(2, 'days')],
                'Next 3 Days': [moment(), moment().add(2, 'days')],
                'Next 7 Days': [moment(), moment().add(6, 'days')],
                'Next 15 Days': [moment(), moment().add(14, 'days')],
                'Next 30 Days': [moment(), moment().add(30, 'days')]
            }
        }
        var formEconomyEdit = {
            'name': { // passwword thì nên đặt là name trong input đó 
                'element': document.getElementById('name'), // id trong input đó 
                'error': document.getElementById('name_error'), // thẻ hiển thị lỗi 
                'validations': [{
                        'func': function(value) {
                            return checkRequired(value); // check trống
                        },
                        'message': generateErrorMessage('P001', 'Tên')
                    }, // viết tiếp điều kiện validate vào đây (validations)
                ]
            },

        }

        const changeDatePickerText = (element, startDate, endDate) => {
            $(element).val(startDate.format('L') + ' - ' + endDate.format('L'));
        }

        $('.bookingDatePicker').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('.bookingDatePicker',
            start, end));
        $('.bookingDatePicker').on('apply.daterangepicker', (event, picker) => changeDatePickerText(event.target, picker
            .startDate, picker.endDate));



        $('[name=guest_type]').on('change', function() {
            if ($(this).val() == 1) {
                $('.guestInputDiv').addClass('d-none');
                $('.forGuest').attr("required", false);
            } else {
                $('.guestInputDiv').removeClass('d-none');
                $('.forGuest').attr("required", true);
            }
        });


        $('.formRoomSearch').on('submit', function(e) {
            e.preventDefault();

            let searchDate = $('[name=date]').val();
            if (searchDate.split(" - ").length < 2) {
                notify('error', `@lang('Ngày nhận phòng và ngày trả phòng phải được cung cấp khi đặt phòng.')`);
                return false;
            }

            resetDOM();
            let formData = $(this).serialize();
            let url = $(this).attr('action');

            $.ajax({
                type: "get",
                url: url,
                data: formData,
                success: function(response) {
                    $('.bookingInfo').html('');
                    $('.booking-wrapper').addClass('d-none');
                    if (response.error) {
                        notify('error', response.error);
                    } else if (response.html.error) {
                        notify('error', response.html.error);
                    } else {
                        $('.bookingInfo').html(response.html);
                        let roomTypeId = $('[name=room_type]').val();
                        $('[name=room_type_id]').val(roomTypeId);


                        $('.booking-wrapper').removeClass('d-none');
                    }
                },
                processData: false,
                contentType: false,
            });
        });

        function resetDOM() {
            $(document).find('.orderListItem').remove();
            $('.totalFare').data('amount', 0);
            $('.totalFare').text(`0 {{ __(gs()->cur_text) }}`);
            $('.taxCharge').text('0');
            $('[name=tax_charge]').val('0');
            $('.grandTotalFare').text(`0 {{ __(gs()->cur_text) }}`);
            $('[name=total_amount]').val('0');
            $('[name=paid_amount]').val('');
            $('[name=room_type_id]').val('');
        }

        $(document).on('click', '.confirmBookingBtn', function() {
            var modal = $('#confirmBookingModal');
            modal.modal('show');
        });

        // $('.btn-confirm').on('click', function() {
        //     $('#confirmBookingModal').modal('hide');
        //     $('.booking-form').submit();
        // });

        // $('.booking-form').on('submit', function(e) {
        //     e.preventDefault();
        //     let formData = $(this).serialize();
        //     let url = $(this).attr('action');
        //     $.ajax({
        //         type: "POST",
        //         url: url,
        //         data: formData,
        //         success: function(response) {
        //             if (response.success) {
        //                 notify('success', response.success);
        //                 $('.bookingInfo').html('');
        //                 $('.booking-wrapper').addClass('d-none');
        //                 $(document).find('.orderListItem').remove();
        //                 $('.orderList').addClass('d-none');
        //                 $('.formRoomSearch').trigger('reset');
        //             } else {
        //                 notify('error', response.error);
        //             }
        //         },
        //     });
        // });
        $('.select2-basic').select2({
            dropdownParent: $('.select2-parent')
        });


        function showRoom(data = "", checkInDateValue = "", checkOutDateValue = "", selectedOptionHangPhong = "",
            selectedOptionNamePhong = "", selectedOptionStatusPhong = "") {
            $('#loading').show();
            $('[id="date-chon-phong-in"]').val(checkInDateValue);
            $('[id="date-chon-phong-out"]').val(checkOutDateValue);
            $.ajax({
                url: '{{ route('admin.booking.showRoom') }}',
                type: 'POST',
                data: {
                    roomIds: data,
                    checkInDate: checkInDateValue,
                    checkOutDate: checkOutDateValue,
                    optionHangPhong: selectedOptionHangPhong,
                    optionNamePhong: selectedOptionNamePhong,
                    optionStatusPhong: selectedOptionStatusPhong,
                },
                success: function(data) {
                    // <p data-id="${ item.id }" data-room_type_id="${ item.room_type_id }" class="add-book-room" id="add-book-room">Đặt phòng</p>
                    var tbody = $('#show-room');

                    const dataNew = Object.values(data.data);
                    let seenRooms = new Set();
                    tbody.empty();
                    dataNew.forEach(function(item) {
                        let rowClass = '';
                        let isFirst = !seenRooms.has(item.room_number);
                        seenRooms.add(item.room_number);
                        // Nếu không phải bản ghi đầu tiên, đặt class theo trạng thái
                        if (!isFirst) {
                            if (item.check_booked === 'Đã nhận') {
                                rowClass = "background-primary";
                            } else if (item.check_booked === 'Đã đặt') {
                                rowClass = 'background-yellow';
                            }
                        } else {
                            if (item.check_booked === 'Đã nhận') {
                                rowClass = "background-primary";
                            } else if (item.check_booked === 'Đã đặt') {
                                rowClass = 'background-yellow';
                            }
                            else {
                                rowClass = "background-white"; 
                            }
                        }

                        var tr = `
                                <tr class="${rowClass}">
                                    <td style="${isFirst ? 'font-weight: bold;' : ''}"> ${ item.room_type['name'] } </td>
                                    <td style="${isFirst ? 'font-weight: bold;' : ''}"> ${ item.room_number } </td>
                                    <td style="${isFirst ? 'font-weight: bold;' : ''}"> ${ formatDate(item.date) } </td>
                                    <td style="${isFirst ? 'font-weight: bold;' : ''}"> ${ item.check_booked } </td>
                                    <td style="${isFirst ? 'font-weight: bold;' : ''}"> ${ formatCurrency(item.room_type.room_type_price['unit_price']) } </td>
                                    <td> 
                                        <input type="checkbox" ${item.status == 1 ? 'disabled' : ''} data-date="${ item.date }" data-id="${ item.id }" data-room_type_id="${ item.room_type_id }" id="checkbox-${item.id}">
                                    </td>
                                </tr>
                            `;
                        tbody.append(tr);

                    });
                    // hạng phòng 
                    var selected_hang = $('#selected-hang-phong');
                    selected_hang.empty();
                    let option = `<option value="">Chọn hạng phòng</option>`;
                    data.roomType.forEach(function(item) {
                        if (item.id == data.option_hang_phong) {
                            option += `<option value="${item.id}" selected>${item.name}</option>`;
                        } else {
                            option += `<option value="${item.id}">${item.name}</option>`;
                        }
                    });
                    selected_hang.append(option);

                    // tên phòng    
                    var selected_name = $('#selected-name-phong');
                    selected_name.empty();
                    let options = `<option value="">Chọn tên phòng</option>`;


                    data.room.forEach(function(item) {
                        if (item.id == data.option_name_phong) {
                            options +=
                                `<option value="${item.id}" selected>${item.room_number}</option>`;
                        } else {
                            options += `<option value="${item.id}">${item.room_number}</option>`;
                        }
                    });

                    selected_name.append(options);
                    // trạng thái phòng
                    // var selected_status = $('#status-room');
                    // selected_status.empty();
                    // let status = ``;
                    // if(data.option_status_phong === null){
                    //      status += `<option value="">Chọn trạng thái phòng</option>`;
                    // }else{
                    //      status += `<option value="${data.option_status_phong}">${data.option_status_phong}</option>`;
                    // }
                    // selected_status.append(status);
                    $('#loading').hide();
                },
                error: function(error) {
                    $('#loading').hide();
                    console.log('Error:', error);
                }
            });
        }
        // 123
        $('#selected-name-phong, #selected-hang-phong, #date-chon-phong-out, #date-chon-phong-in, #status-room').on(
            'change',
            function() {
                var selectedOptionHangPhong = $('#selected-hang-phong').val();
                var selectedOptionNamePhong = $('#selected-name-phong').val();
                var selectedOptionStatusPhong = $('#status-room').val();
                const roomIds = [];
                $('#list-booking tr').each(function() {
                    const roomId = $(this).attr('data-room-id');

                    if (roomId) {
                        roomIds.push(roomId);
                    }
                });
                const checkInDateValue = $('#date-chon-phong-in').val();
                const checkOutDateValue = $('#date-chon-phong-out').val();
                showRoom(roomIds, checkInDateValue, checkOutDateValue, selectedOptionHangPhong, selectedOptionNamePhong,
                    selectedOptionStatusPhong)
            });
        // xóa phòng
        $('.delete-room-booking').on('click', function() {
            $('#list-booking tr').each(function() {
                var checkbox = $(this).find('input[type="checkbox"]');
                if (checkbox.prop('checked')) {

                    $(this).remove();
                    let totalPrice = 0;
                    totalPrice = calculateTotalPrice();
                    $('#total_amount').text(formatCurrency(totalPrice));
                    // $('#total_amount').text(formatCurrency(totalPrice));
                    // $('#total_balance').text(formatCurrency(totalPrice));
                }
            });
        });
        // add phòng vào booked
        $('.add-room-list').on('click', function() {
            const selectedCheckboxes = [];
            $('#show-room input[type="checkbox"]:checked').each(function() {
                const checkboxData = {
                    room: $(this).data('id'),
                    room_type: $(this).data('room_type_id'),
                    date: $(this).data('date')
                };
                selectedCheckboxes.push(checkboxData);
            });
            addRoomInBooking(selectedCheckboxes)
        });

        $('.add-room-booking').on('click', function() {

            const roomIds = [];
            $('#list-booking tr').each(function() {
                const roomId = $(this).attr('data-room-id');

                if (roomId) {
                    roomIds.push(roomId);
                }
            });
            const checkInDateValue = $('#date-book-room-booking').val();

            // Lấy giá trị của input checkOutDate
            const checkOutDateValue = $('#date-book-room-date').val();
            showRoom(roomIds, checkInDateValue, checkOutDateValue, '', '')
            $('#addRoomModal').modal('show');

            $('#addRoomModal').on('shown.bs.modal', function() {
                document.body.classList.add('modal-open');
                $('#addRoomModal').addClass('z__index-mod');
            });

        });
        $('#addRoomModal').on('hidden.bs.modal', function() {
            if (!$('#addRoomModal').hasClass('show')) {
                $('#show-room tr').remove();
                document.body.classList.remove('modal-open');
                $('#addRoomModal').removeClass('z__index-mod');
                // xóa các phòng thêm vào damh sách booking
            }
        });

        $(document).on('click', '.add-book-room', function() {
            var roomId = $(this).data('id');
            var roomTypeId = $(this).data('room_type_id');
            $('#myModal-booking').modal('show');


            // addRoomInBooking(roomId, roomTypeId);
            var selectedCheckboxes = [];

        });

        function formatDate(inputDate) {
            // Chia chuỗi ngày thành các phần tử: năm, tháng, ngày
            var parts = inputDate.split('-');

            // Định dạng lại chuỗi ngày
            var formattedDate = parts[2] + '/' + parts[1] + '/' + parts[0];

            return formattedDate;
        }

        function formatDateTime(inputDateTime) {
            // Chia chuỗi ngày và giờ thành các phần tử
            var dateTimeParts = inputDateTime.split(' ');

            // Chia phần ngày thành các phần tử: năm, tháng, ngày
            var dateParts = dateTimeParts[0].split('-');

            // Định dạng lại chuỗi ngày
            var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];

            // Nối lại chuỗi ngày và giờ
            var formattedDateTime = formattedDate + ' ' + dateTimeParts[1];

            return formattedDateTime;
        }
        $(document).on('click', '.close_modal', function() {
            $('#myModal-booking').modal('hide');
        });
        $(document).on('click', '.close_modal_booked_room', function() {
            $('#addRoomModal').modal('hide');
        });

        function calculateTotalPrice() {
            let totalPrice = 0;

            $('#list-booking').find('p#price').each(function() {
                let priceString = $(this).attr('data-price');
                let price = parseFloat(priceString.replace(' VND', '').replace(',', '.'));


                totalPrice += price;
            });
            let pricediscount = 0;
            let discountInputValue = $('#discountInput').val();

            if (discountInputValue) {
                pricediscount = parseInt(discountInputValue.replace(/\./g, ''));
                pricediscount = isNaN(pricediscount) ? 0 : pricediscount;
            }
            $('#total_balance').text(formatCurrency(totalPrice - pricediscount));
            // $('#total_deposit').text(formatCurrency(totalPrice)); 
            return totalPrice;
        }

        function addRoomInBooking(data) {
            $('#loading').show();
            $.ajax({
                url: '{{ route('admin.booking.checkRoomBooking') }}',
                type: 'POST',
                data: {
                    data: JSON.stringify(data)
                },
                success: function(response) {
                    var tbody = $('#list-booking');
                    if (response.status === 'success') {
                        //   getRoomType(response.room_type['id'], response.room['room_number'])
                        const todays = new Date();
                        const yyyys = todays.getFullYear();
                        const mms = String(todays.getMonth() + 1).padStart(2, '0');
                        const dds = String(todays.getDate()).padStart(2, '0');
                        const hoursss = String(todays.getHours()).padStart(2, '0'); // Giờ
                        const minutesss = String(todays.getMinutes()).padStart(2, '0'); // Phút

                        const formattedDates = `${yyyys}-${mms}-${dds}`;
                        const formattedTimes = `${hoursss}:${minutesss}`;
                        // let date = new Date(formattedDates);
                        // date.setDate(date.getDate() + 1);
                        // let yyyy2 = date.getFullYear();
                        // let mm2 = String(date.getMonth() + 1).padStart(2, '0');
                        // let dd2 = String(date.getDate()).padStart(2, '0');

                        // const nextDay = `${yyyy2}-${mm2}-${dd2}`;  
                        let totalPrice = 0;
                        // nextDay đang là time lớn hơn formattedDates 1 ngày
                        response.data.forEach(item => {
                            let date = new Date(item.date);
                            date.setDate(date.getDate() + 1);
                            var tr = `
                                    <tr data-room-id="${item.room['id']}"  data-room-type-id="${item.room_type['id']}">
                                        <td>
                                            <input type="checkbox"> 
                                        </td>

                                        <td>
                                            <p class="room__name"> ${item.room['room_number']}</p>
                                        </td>
                                         <td>
                                             <input type="number" min="1" name="adult" class="form-control adult"  value="1"  style="margin-left: 16px;">
                                             
                                        </td>
                                        <td style="display: flex; justify-content: center">
                                            <select id="bookingType" class="form-select" name="optionRoom" style="width: 93px; font-size:15px">
                                                 <option value="ngay">Ngày</option> 
                                                 <option value="gio">Giờ</option>
                                              
                                                
                                            </select>
                                        </td>
                                         <td>
                                            <div class="d-flex align-items-center justify-content-start" style="gap: 10px">
                                                <input type="date" name="checkInDate" id="date-book-room" class="form-control date-book-room"  value="${item.date}">

                                                <input type="time" name="checkInTime" id="time-book-room" class="form-control time-book-room"  value="${item.room['room_type']['room_type_price']['setup_pricing']['check_in_time']}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start" style="gap: 10px">
                                                <input type="date" name="checkOutDate"  class="form-control date-book-room"  value="${date.toISOString().split('T')[0]}">

                                                <input type="time" name="checkOutTime" id="time-book-room" class="form-control time-book-room"  value="${item.room['room_type']['room_type_price']['setup_pricing']['check_out_time']}">
                                               
                                            </div>
                                        </td>
                                        <td>
                                             <p id="price" data-price="${item.room['room_type']['room_type_price']['unit_price']}">${formatCurrency(item.room['room_type']['room_type_price']['unit_price'])}</p>
                                        </td>
                                        <td>
                                              <input type="text" class="form-control deposit number-input" oninput="this.value = this.value.slice(0, 16)"  name="deposit"  placeholder="0">
                                           
                                        </td>
                                        <td>
                                            <input type="text" name="note_room" class="form-control note_room" value="" id="note">
                                        </td>


                                    </tr>
                                `;
                            tbody.append(tr);
                        })
                        // $('#list-booking').find('p#price').each(function() {
                        //     let priceString = $(this).attr('data-price');
                        //     let price = parseFloat(priceString.replace(' VND', '').replace(',', '.'));
                        //     totalPrice += price;
                        // });
                        totalPrice = calculateTotalPrice();
                        $('#total_amount').text(formatCurrency(totalPrice));
                        $('#total_balance').text(formatCurrency(totalPrice));
                        $('#loading').hide();
                        let totalDeposit = 0;
                        let totalBalance = 0;
                        $('tr').find('input.deposit').on('blur', function() {
                            let rowTotal = 0;
                            $('tr').each(function() {
                                $(this).find('input.deposit').each(function() {
                                    let depositValue = $(this).val().replace(/[,.]/g,
                                        '');
                                    let numericDeposit = parseInt(depositValue) || 0;
                                    rowTotal += numericDeposit;
                                });
                            });
                            $('#total_deposit').text(formatCurrency(rowTotal));
                            let priceString = $('#discountInput').val();
                            let price = parseInt(priceString.replace(/\./g, ''));
                            price = isNaN(price) ? 0 : price;
                            totalBalance = totalPrice - rowTotal - price;
                            $('#total_balance').text(formatCurrency(totalBalance));
                        });

                        $('.number-input').on('blur', function() {
                            formatNumber(this);
                        });

                        $('.custom-input-giam-gia').on('blur', function() {
                            // Lấy giá trị từ trường nhập liệu
                            let discountValue = $(this).val();
                            let number = parseInt(discountValue.replace('.', ''));
                            number = isNaN(number) ? 0 : number;
                            let priceString = $('#total_amount').text();
                            let price = parseFloat(priceString.replace(/\./g, '').replace(' VND', ''));

                            let pricedeposit = $('#total_deposit').text();
                            let deposit = parseFloat(pricedeposit.replace(/\./g, '').replace(' VND',
                                ''));
                            $('#total_balance').text(formatCurrency(price - deposit - number));
                            formatNumber(this);
                        });

                        $('#addRoomModal').modal('hide');
                        document.body.classList.remove("modal-open");
                    } else if (response.status === 'error') {
                        $('#loading').hide();
                        var tr = ``;
                        tbody.append(tr);
                        $('#addRoomModal').modal('hide');
                        document.body.classList.remove("modal-open");
                    }
                },
                error: function(error) {
                    $('#loading').hide();
                    console.log('Error:', error);
                }
            });
        }
        $(document).on('click', '[id="hour_current"]', function() {
            // Lấy ngày và giờ hiện tại
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0'); // Tháng bắt đầu từ 0
            const date = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');

            // Kết hợp thành giá trị datetime-local
            const currentDate = `${year}-${month}-${date}`; // Format YYYY-MM-DD
            const currentTime = `${hours}:${minutes}`; // Format HH:mm

            // Gán giá trị vào các input
            $('[id="date-book-room"]').val(currentDate);
            $('[id="time-book-room"]').val(currentTime);
            // Gán giá trị vào các input
            $('[id="date-book-room"]').val(currentDate);
            $('[id="time-book-room"]').val(currentTime);
        });
        // xóa phòng
        $(document).on('click', '.delete-booked-room', function() {
            var roomId = $(this).data('room-id');


            if (confirm("Bạn có chắc chắn muốn xóa đặt phòng này?")) {
                var url = `{{ route('admin.booking.delete-booked-room', ['id' => ':id']) }}`.replace(':id', roomId);
                $.ajax({
                    url: url,
                    type: 'POST',
                    success: function(response) {
                        if (response.status == 'success') {
                            notify('success', response.success);
                            loadRoomBookings();
                        } else {
                            notify('error', response.success);
                        }
                    },
                    error: function(error) {
                        console.log('Error:', error);
                    }
                });
            }
        });
        // chi tiết 
        $(document).on('click', '.booked_room_detail', function() {
            var roomId = $(this).data('room-id');
            var url = `{{ route('admin.booking.details', ['id' => ':id']) }}`.replace(':id',
                roomId);
            window.location.href = url;
        });
        // sửa phòng 
        $(document).on('click', '.booked_room_edit', function() {
            var roomId = $(this).data('room-id');
            // ajax request
            var url = `{{ route('admin.room.booking.edit', ['id' => ':id']) }}`.replace(':id',
                roomId);
            $.ajax({
                url: url,
                type: 'POST',
                success: function(response) {
                    if (response.status == 'success') {
                        console.log(response.data);

                        // notify('success', response.success);
                        // loadRoomBookings();
                        $('#myModal-booking').modal('show');
                    } else {
                        notify('error', response.success);
                    }
                },
                error: function(error) {
                    // notify('error', error.responseJSON.message);
                    console.log('Error:', error);
                }
            });

        });

        // nhận phòng
        $(document).on('click', '.booked_room', function() {
            var roomId = $(this).data('room-id');

            // ajax request
            var url = `{{ route('admin.room.check.in', ['id' => ':id']) }}`.replace(':id',
                roomId);
            $.ajax({
                url: url,
                type: 'POST',
                success: function(response) {
                    if (response.status == 'success') {
                        notify('success', response.success);
                        loadRoomBookings();
                    } else {
                        notify('error', response.success);
                    }
                },
                error: function(error) {
                    // notify('error', error.responseJSON.message);
                    console.log('Error:', error);
                }
            });
        });
        $(document).on('click', '.icon-delete-room', function() {
            const row = $(this).closest('tr');
            const roomId = row.data('room-id');
            const roomTypeId = row.data('room-type-id');


            $('#confirmDeleteModal').modal('show');

            // Lưu thông tin phòng để xóa vào modal
            $('#confirmDeleteButton').off('click').on('click', function() {
                // Xử lý xóa
                row.remove();
                $('#confirmDeleteModal').modal('hide');
                // console.log('Xóa phòng với roomId:', roomId, 'và roomTypeId:',
                //     roomTypeId); // In ra thông tin xóa
            });
        });

        $(document).on('click', '#btn-search', function() {
            var customerName = $('#name').val();
            let flag = true;
            if (customerName == '') {
                flag = false;
            }
            if (flag) {
                // ajax request
                $.ajax({
                    url: '{{ route('admin.search.customer') }}',
                    type: 'GET',
                    data: {
                        name: customerName,
                    },
                    success: function(response) {
                        // notify('success', response.success);
                        // $('.note-booking').html(note);
                        // $('#noteModal').modal('hide');
                        if (response.status == 'success') {
                            if (response.data !== null) {
                                $('#phone').val(response.data['phone']);
                            } else {
                                $('#phone').val('');
                            }

                        }
                    },
                    error: function(xhr, status, error) {

                        // alert('Có lỗi xảy ra khi lưu ghi chú!');
                    }
                });
            }

        });

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





        function getDatesBetween(checkInDate, checkInTime, checkOutDate, checkOutTime, room, roomType, adult, note,
            deposit) {

            let dates = [];
            let currentDate = new Date(checkInDate);
            let currentDateOut = new Date(checkOutDate);

            const [checkOutHours, checkOutMinutes] = checkOutTime.split(':').map(Number);
            const [checkInHours, checkInMinutes] = checkInTime.split(':').map(Number);

            while (currentDate && currentDateOut) {
                currentDate.setHours(checkInHours);
                currentDate.setMinutes(checkInMinutes);
                currentDate.setSeconds(0); // Đặt giây về 0

                currentDateOut.setHours(checkOutHours);
                currentDateOut.setMinutes(checkOutMinutes);
                currentDateOut.setSeconds(0); // Đặt giây về 0

                let formattedDate =
                    `${currentDate.getMonth() + 1}/${String(currentDate.getDate()).padStart(2, '0')}/${currentDate.getFullYear()} ` +
                    `${String(currentDate.getHours()).padStart(2, '0')}:${String(currentDate.getMinutes()).padStart(2, '0')}:${String(currentDate.getSeconds()).padStart(2, '0')}`;

                let formattedDateOut =
                    `${currentDateOut.getMonth() + 1}/${String(currentDateOut.getDate()).padStart(2, '0')}/${currentDateOut.getFullYear()} ` +
                    `${String(currentDateOut.getHours()).padStart(2, '0')}:${String(currentDateOut.getMinutes()).padStart(2, '0')}:${String(currentDateOut.getSeconds()).padStart(2, '0')}`;
                //  dates.push(`${roomType}-${room}-${formattedDate}-${formattedDateOut}`);
                dates.push({
                    roomType: roomType,
                    room: room,
                    dateIn: formattedDate,
                    dateOut: formattedDateOut,
                    adult: adult,
                    note: note,
                    deposit: deposit
                });

                break;
                currentDate.setDate(currentDate.getDate() + 1);


            }
            return dates;
        }

        function validator(checkInDate, checkOutDate, dataRowValue) {
            const checkInDateTimeString = `${checkInDate}`;
            const checkInDateTimeStringOut = `${checkOutDate}`;
            // Chuyển đổi chuỗi ngày và giờ thành đối tượng Date
            const checkInDateTimeObj = new Date(checkInDateTimeString);
            const checkInDateTimeObjOut = new Date(checkInDateTimeStringOut);
            // Lấy thời gian hiện tại
            const currentDateTime = new Date();
            currentDateTime.setSeconds(0);

            const errorDiv = document.querySelector('.message-error');
            const checkInTimeInt = Math.floor(checkInDateTimeObj.getTime() / 1000);
            const checkInTimeIntOut = Math.floor(checkInDateTimeObjOut.getTime() / 1000);
            const currentTimeInt = Math.floor(currentDateTime.getTime() / 1000);
            // So sánh thời gian check-in với thời gian hiện tại
            if (checkInTimeInt > currentTimeInt && dataRowValue === 'checkin') {
                errorDiv.textContent =
                    `Không thể nhận phòng trong tương lai. Bạn có thể Đặt trước hoặc cập nhật giờ nhận về giờ hiện tại để nhận phòng.`;
                errorDiv.classList.add('alert', 'alert-danger');
                errorDiv.style.display = 'block';
                return false;
            } else if (checkInTimeInt > currentTimeInt && dataRowValue === 'booked') {
                errorDiv.style.display = 'none'; // Ẩn div thông báo lỗi
                return true;
            } else if (checkInTimeInt < currentTimeInt) {
                errorDiv.textContent =
                    `Không thể nhận phòng trong quá khứ. Bạn có thể Đặt trước hoặc cập nhật giờ nhận về giờ hiện tại để nhận phòng.`;
                errorDiv.classList.add('alert', 'alert-danger');
                errorDiv.style.display = 'block';
                return false;
            } else {
                errorDiv.style.display = 'none'; // Ẩn div thông báo lỗi
                return true;
            }
        }
        $('.btn-confirm, .btn-book').on('click', function() {
            const dataRowValue = $(this).data('row');
            $('.booking-form').data('row', dataRowValue);
            if (validateAllFields(formEconomyEdit)) {
                $('.booking-form').submit(); // Gửi form
            }

        });

        $('.booking-form').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serializeArray();
            // var adultsValue = parseInt($('#adults').val(), 10) || 0;
            // var childrenValue = parseInt($('#children').val(), 10) || 0;

            // var totalPeople = adultsValue + childrenValue;

            let formObject = {};
            formData.forEach(function(field) {
                formObject[field.name] = field.value;
            });

            let queryString = $.param(formObject);

            const params = new URLSearchParams(queryString);
            const checkInDate = params.get('checkInDate');
            const checkInTime = params.get('checkInTime');
            var roomData = []; // Mảng để chứa thông tin các phòng
            const dataRowValue = $(this).data('row'); // Lấy giá trị data-row đã thiết lập trước đó



            let hasError = true;
            // Duyệt qua từng dòng trong bảng
            $('#list-booking tr').each(function() {
                var roomId = $(this).data('room-id');
                var roomTypeId = $(this).data('room-type-id');
                var checkInDate = $(this).find('input[name="checkInDate"]').val();
                var checkInTime = $(this).find('input[name="checkInTime"]').val();
                var checkOutDate = $(this).find('input[name="checkOutDate"]').val();
                var checkOutTime = $(this).find('input[name="checkOutTime"]').val();
                var adult = $(this).find('input[name="adult"]').val();
                var note = $(this).closest('tr').find('input[name="note_room"]').val();
                var deposit = $(this).closest('tr').find('input[name="deposit"]').val();
                // console.log(roomId, roomTypeId, checkInDate, checkInTime, checkOutDate, checkOutTime, adult, note);
                const errorDiv = document.querySelector('.message-error');

                if (new Date(checkOutDate) < new Date(checkInDate)) {

                    errorDiv.textContent = `Ngày trả phòng phải lớn hơn ngày nhận phòng`;
                    errorDiv.classList.add('alert', 'alert-danger');
                    errorDiv.style.display = 'block';
                    hasError = false;
                    return false;
                }
                // Thêm thông tin của phòng vào mảng
                roomData.push({
                    roomId: roomId,
                    roomTypeId: roomTypeId,
                    checkInDate: checkInDate,
                    checkInTime: checkInTime,
                    checkOutDate: checkOutDate,
                    checkOutTime: checkOutTime,
                    adult: adult,
                    note: note,
                    deposit: deposit,
                });
            });

            if (hasError) {
                roomData.forEach(function(item) {
                    const roomDates = getDatesBetween(item['checkInDate'], item['checkInTime'],
                        item['checkOutDate'], item['checkOutTime'], item['roomId'], item['roomTypeId'],
                        item['adult'], item['note'], item['deposit']);

                    roomDates.forEach(function(date, index) {
                        formData.push({
                            name: 'room[]',
                            value: JSON.stringify(date)
                        });
                    });
                })
                formData.push({
                    name: 'method',
                    value: 'booked_room',
                });

                // formData.push({
                //     name: 'is_method',
                //     value: 'receptionist',
                // });
                let shouldSubmit = true;
                formData.some(function(item) {
                    if (item.name === 'room[]') {
                        const data = item.value;
                        let dataArray = JSON.parse(data);
                        const timeCheckIn = dataArray['dateIn'];
                        const timeCheckOut = dataArray['dateOut'];
                        // const resultData = validator(timeCheckIn, timeCheckOut, dataRowValue);
                        // if (!resultData) {
                        //     shouldSubmit = false;
                        //     return true;
                        // }
                    }

                });
                // Kiểm tra th��i gian check-in với th��i gian hiện tại

                let url = $(this).attr('action');
                if (shouldSubmit) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: formData,
                        success: function(response) {
                            if (response.success) {
                                notify('success', response.success);
                                $('.bookingInfo').html('');
                                $('.booking-wrapper').addClass('d-none');
                                $(document).find('.orderListItem').remove();
                                $('.orderList').addClass('d-none');
                                $('.formRoomSearch').trigger('reset');
                                $('#myModal-booking').hide();
                                window.location.reload();
                            } else {
                                notify('error', response.error);
                            }
                        },
                    });
                }
            }

        });
        $(document).on('click', '.note-booked-room', function() {
            var roomId = $(this).data('room-id');
            var note = $(this).closest('tr').find('input[name="note"]').val();
            $('#noteModal').modal('show');
            $('#note-input').val(note);
            $('#note-input').data('room-id', roomId);
        });

        $(document).on('click', '.save-note', function() {
            var newNote = $('#note-input').val();
            var roomId = $('#note-input').data('room-id');

            $('#list-booking tr[data-room-id="' + roomId + '"]')
                .find('input[name="note"]')
                .val(newNote);

            $('#list-booking tr[data-room-id="' + roomId + '"]')
                .find('input[name="note_room"]')
                .val(newNote);

            $('#noteModal').modal('hide');
        });
        $(document).ready(function() {
            loadRoomBookings(); // Function to load the room bookings
        });

        function formatCurrency(amount) {
            const parts = amount.toString().split('.');
            const integerPart = parts[0];
            const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            return formattedInteger + ' VND';
        }

        function formatDateTime(isoDateString) {
            if (!isoDateString) return '';

            const date = new Date(isoDateString);

            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear(); // Năm

            const hours = String(date.getHours()).padStart(2, '0'); // Giờ (2 chữ số)
            const minutes = String(date.getMinutes()).padStart(2, '0'); // Phút (2 chữ số)

            // Định dạng DD/MM/YYYY HH:MM
            return `${day}/${month}/${year} ${hours}:${minutes}`;
        }

        function loadRoomBookings(page = 1) {
            $.ajax({
                url: '{{ route('admin.room.booking') }}', // Adjust this to your route
                type: 'GET',
                data: {
                    page: page
                },
                success: function(response) {

                    if (response.status === 'success') {
                        var data = response.data;
                        var pagination = response.pagination;

                        $('.data-table').html('');
                        // <td>${data['admin']['name']}</td> user tạo
                        data.forEach(function(data, index) {
                            var html = `
                                <tr data-id="${data['id']}">
                                     <td>${index + 1  }</td>
                                    <td>${data['booking_id']}</td>
                                    <td>${data['room']['room_number']}</td>
                                    <td>${formatDateTime(data['document_date'])}</td>
                                    <td>${formatDateTime(data['checkin_date'])}</td>
                                    <td>${formatDateTime(data['checkout_date'])}</td>
                                    <td>${data['customer_name'] ? data['customer_name'] : 'N/A'}</td>
                                    <td>${data['phone_number'] ? data['phone_number'] : 'N/A'}</td>
                                    <td>${data['guest_count']}</td>
                                    <td>${ formatCurrency( data['total_amount'])}</td>
                                    <td>${formatCurrency(data['deposit_amount'])}</td>
                                 
                                  
                                    <td>
                                        <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"/><circle cx="10.5" cy="5.5" r="1"/><circle cx="10.5" cy="15.5" r="1"/></g></svg>    
                                        <div class="dropdown menu_dropdown_check_in" id="dropdown-menu">
                                            <div class="dropdown-item booked_room_edit" data-room-id="${data['booking_id']}">Sửa phòng</div>
                                            <div class="dropdown-item booked_room" data-room-id="${data['id']}">Nhận phòng</div>
                                            <div class="dropdown-item booked_room" data-room-id="${data['id']}">Đổi phòng</div>
                                              <div class="dropdown-item booked_room_detail" data-room-id="${data['id']}">Chi tiết</div>
                                            <div class="dropdown-item delete-booked-room"  data-room-id="${data['id']}" >Xóa phòng</div>
                                           
                                        </div>
                                    </td>
                                </tr>
                            `
                            $('.data-table').append(html);
                        })
                        updatePagination(pagination);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request failed: " + error);
                }
            });
        }

        function updatePagination(pagination) {
            var paginationHtml = '';
            if (pagination.current_page > 1) {
                paginationHtml += `<button onclick="loadRoomBookings(${pagination.current_page - 1})">Trước</button>`;
            }
            for (var i = 1; i <= pagination.last_page; i++) {
                paginationHtml += `
            <button onclick="loadRoomBookings(${i})">${i}</button>
            `;
            }
            if (pagination.current_page < pagination.last_page) {
                paginationHtml += `<button onclick="loadRoomBookings(${pagination.current_page + 1})">Tiếp theo</button>`;
            }
            $('.pagination-container').html(paginationHtml);
        }
    </script>
@endpush

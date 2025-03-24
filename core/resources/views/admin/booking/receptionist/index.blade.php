@extends('admin.layouts.master_iframe')
@section('panel')
    <div class="row d-flex align-items-center">
        <div class="col-md-3">
            <div class="view-toggle">
                <button id="listViewBtn" class="active" onclick="changeView('list')">
                    <span class="icon"> <i class="fa-solid fa-bars"></i></span> <span class="text">Danh Sách</span>
                </button>
                <button id="gridViewBtn" onclick="changeView('calendar')">
                    <span class="icon"><i class="fa-solid fa-sliders"></i></span> <span class="text"
                        style="display: none;">Lưới</span>
                </button>
                <button id="tableViewBtn" onclick="changeView('grid')">
                    <span class="icon"> <i class="fa-solid fa-th-large"></i></span> <span class="text"
                        style="display: none;">Sơ đồ</span>
                </button>
            </div>
        </div>
        <div class="col-md-9" id="booking-time">
            <div style="float: right; gap: 10px;height: 40px;" class="d-flex">
                <div class="date-input-booking" style="display: flex;gap: 10px;">
                    <input type="date" id="startDate" class="form-control w-auto" style="height: 40px"
                        placeholder="Từ ngày">
                    <input type="date" id="endDate" class="form-control w-auto" style="height: 40px"
                        placeholder="Đến ngày">
                </div>
                <p class="btn btn-primary change-room d-flex align-items-center add-book-room"
                    style="font-size:13px; gap: 5px;"><i class="la la-plus"></i> Đặt phòng</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            @include('admin.booking.partials.system-1')
        </div>
    </div>
    <div class="row">
        <div id="listView" class="view">
            @include('admin/booking/receptionist/list')
        </div>
        <div id="gridView" class="view" style="display: none;">
            @include('admin/booking/receptionist/grid')
        </div>
        <div id="calendarView" class="view" style="display: none;">
            @include('admin/booking/receptionist/calendar')
        </div>

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
                        <table class=" table--light style--two table">
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
                        <p type="button" data-row="booked" class=" btn-dat-truoc  add-room-list">Lưu
                        </p>
                        <p type="button" data-row="booked" class="alert-paragraph close_modal_booked_room">Hủy</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @include('admin.booking.partials.customer_booked')
    @include('admin.booking.partials.room_booking')
@endsection
@push('style-lib')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/global/css/system-1.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/view-toggle.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/grid_main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/pagination.css') }}">
@endpush
<script src="https://cdn.jsdelivr.net/npm/tesseract.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
@push('script-lib')
    <script src="{{ asset('assets/validator/validator.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
@endpush

<script>
    var searchCustomerUrl = '{{ route('admin.search.customer') }}';
    var roomBoookingHistory = "{{ route('admin.booking.room-booking-history') }}";
    var showRoomUrl = "{{ route('admin.booking.showRoom') }}";
    var checkRoomBookingUrl = "{{ route('admin.booking.checkRoomBooking') }}";
    var getCustomerStaff = "{{ route('admin.get.customer.staff') }}";
    var findCustomerUrl = "{{ route('admin.find.customer') }}";
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('money-input')) {
            let value = e.target.value.replace(/\D/g, ""); // Xóa ký tự không phải số
            value = Number(value).toLocaleString('vi-VN'); // Định dạng theo chuẩn Việt Nam
            e.target.value = value;
        }
    });
    $(document).ready(function() {
        let dirtyCount = 5; // Ví dụ giá trị
        let incomingCount = 2;
        let occupiedCount = 10;
        let lateCheckinCount = 1;
        let checkOutCount = 3;

        $('.status-available-line-count').text('Đang trống (' + dirtyCount + ')');
        $('.status-incoming-line-count').text('Sắp nhận (' + incomingCount + ')');
        $('.status-occupied-line-count').text('Đang sử dụng (' + occupiedCount + ')');
        $('.status-checkout-line-count').text('Nhận phòng muộn (' + lateCheckinCount + ')');
        $('.status-overdue-line-count').text('Quá giờ trả (' + checkOutCount + ')');

        var validatorForm = {
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
        const date_booking = new Date();
        const date_yyyy = date_booking.getFullYear();
        const date_mm = String(date_booking.getMonth() + 1).padStart(2, '0');
        const date_dd = String(date_booking.getDate()).padStart(2, '0');
        const date_hour = String(date_booking.getHours()).padStart(2, '0'); // Giờ
        const date_minutes = String(date_booking.getMinutes()).padStart(2, '0'); // Phút

        const formattedDates = `${date_yyyy}-${date_mm}-${date_dd}`;
        const formattedTimes = `${date_hour}:${date_minutes}`;

        $('[id="time-book-room-booking"]').val(formattedTimes);

        function calculateTotalPrice() {
            let totalPrice = 0;
            let totalDeposit = 0;
            let totalDiscount = 0;
            $('#list-booking, #list-booking-edit').find('p#price').each(function() {
                let priceString = $(this).attr('data-price');
                let price = parseFloat(priceString.replace(' VND', '').replace(',', '.'));
                totalPrice += price;
            });
            $('#list-booking, #list-booking-edit').find('input.deposit').each(function() {
                let priceString = $(this).val(); // Lấy giá trị nhập trong input
                let price = parseFloat(priceString.replace(/[,.]/g, '')); // Loại bỏ ký tự không phải số

                if (!isNaN(price)) {
                    totalDeposit += price;
                }
            });
            $('#list-booking, #list-booking-edit').find('input.discount').each(function() {
                let priceString = $(this).val(); // Lấy giá trị nhập trong input
                let price = parseFloat(priceString.replace(/[,.]/g, '')); // Loại bỏ ký tự không phải số
                if (!isNaN(price)) {
                    totalDiscount += price;
                }
            });


            // let pricediscount = 0;
            // let discountInputValue = $('#discountInput').val();

            // if (discountInputValue) {
            //     pricediscount = parseInt(discountInputValue.replace(/\./g, ''));
            //     pricediscount = isNaN(pricediscount) ? 0 : pricediscount;
            // }

            $('.total_balance').each(function() {
                $(this).text(formatCurrency(totalPrice - totalDeposit - totalDiscount)); // tiền còn lại
            });

            $('.total_amount').each(function() {
                $(this).text(formatCurrency(totalPrice));
            });
            // giảm giá

            $('.total_discount').each(function() {

                $(this).text(formatCurrency(totalDiscount));
            });

            $('.total_deposit').each(function() {
                $(this).text(formatCurrency(totalDeposit));
            });

            //  $('#total_balance').text(formatCurrency(totalPrice));
            // $('#total_deposit').text(formatCurrency(totalPrice));
            return totalPrice;
        }

        function formatDate(inputDate) {
            // Chia chuỗi ngày thành các phần tử: năm, tháng, ngày
            var parts = inputDate.split('-');
            // Định dạng lại chuỗi ngày
            var formattedDate = parts[2] + '/' + parts[1] + '/' + parts[0];
            return formattedDate;
        }

        function formatCurrency(amount) {
            if (!amount || isNaN(amount)) {
                return '0 VND'; // Nếu amount không hợp lệ, trả về 0 VND
            }

            const parts = parseFloat(amount).toFixed(2).toString().split('.');
            const integerPart = parts[0];
            const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            return formattedInteger + ' VND';
        }

        function getCurrentDate() {
            const currentDate = new Date();
            const year = currentDate.getFullYear();
            const month = String(currentDate.getMonth() + 1).padStart(2, '0'); // Thêm 1 vì tháng bắt đầu từ 0
            const day = String(currentDate.getDate()).padStart(2, '0');

            return `${year}-${month}-${day}`;
        }
        $('[id="date-book-room-booking"]').val(getCurrentDate());

        function countBookings() {
            let bookingList = document.getElementById("list-booking");
            let rows = bookingList.getElementsByTagName("tr");
            return rows.length; // Trả về số lượng hàng <tr>
        }

        // Kiểm tra nếu danh sách đặt phòng có ít nhất một hàng
        function hasBookings() {
            return countBookings() > 0;
        }
        $(document).on('click', '.add-book-room', function() {
            $('#list-booking-edit').empty();
            $('#myModal-booking').modal('show');
            // var roomId = $(this).data('id');
            // var roomTypeId = $(this).data('room_type_id');
            allStaffandCustomerSource()
            // $('#myModal-booking-edit').modal('hide');
            hasBookings() ? "" : ($('.total_balance').text(0), $('.total_amount').text(0), $(
                '.total_deposit').text(0)), $('.total_discount').text(0);

            // addRoomInBooking(roomId, roomTypeId);
            var selectedCheckboxes = [];

        });
        $('.modal--search-customer').on('click', function() {
            showCustomer("");
            $('#addCustomerModal').modal('show');
            $('#myModal-booking').modal('hide');
            $('#addCustomerModal').on('shown.bs.modal', function() {
                document.body.classList.add('modal-open');
                $('#addCustomerModal').addClass('z__index-mod');
            });
        });
        $('.add-customer-booked').on('click', function() {
            let selectedCustomer = $('#show-customer input[type="radio"]:checked');
            if (selectedCustomer.length === 0) {
                notify('error', 'Vui lòng chọn một khách hàng');
                return;
            }
            let Id = selectedCustomer.data('id');
            findCustomerById(Id)
        });

        $(document).on("dblclick", "#data-table tbody tr", function() {
            let customerId = $(this).find('input[type="radio"]').data("id");
            if (!customerId) {
                return;
            }
            findCustomerById(customerId);
        });

        function getDatesBetween(checkInDate, checkInTime, checkOutDate, checkOutTime, room, roomType, adult,
            note,
            deposit, discount, roomBookingId) {

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
                dates.push({
                    roomType: roomType,
                    room: room,
                    dateIn: formattedDate,
                    dateOut: formattedDateOut,
                    adult: adult,
                    note: note,
                    deposit: deposit,
                    discount: discount,
                    bookingId: roomBookingId
                });

                break;
            }
            return dates;
        }

        function findCustomerById(id) {
            $.ajax({
                url: findCustomerUrl,
                type: 'GET',
                data: {
                    id: id,
                },
                success: function(data) {
                    $('input[name="name"]').val(data.data.name);
                    $('input[name="phone"]').val(data.data.phone);
                    $('input[name="customer_code"]').val(data.data.customer_code);
                    $("#select-customer-source").val(data.data.group_code).change();
                    $("#select-customer-source-edit").val(data.data.group_code).change();
                    $('#addCustomerModal').modal('hide');
                    $('#myModal-booking').modal('show');
                    $('#loading').hide();
                },
                error: function(error) {
                    $('#loading').hide();
                    console.log('Error:', error);
                }
            });
        }
        $(document).on('click', '.close_modal', function() {
            $('#myModal-booking').modal('hide');
            $('#myModal-booking-edit').modal('hide');
            $("#myModal-check-in-edit").modal('hide');
        });
        $(document).on('click', '.close_modal_booked_room', function() {
            $('#addRoomModal').modal('hide');
            $('#addCustomerModal').modal('hide');
            $('#changeRoomModal').modal('hide');
        });
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
        // add
        $('.btn-book').on('click', function() {
            const dataRowValue = $(this).data('row');
            $('.booking-form').data('row', dataRowValue);
            if (validateAllFields(validatorForm)) {
                $('.booking-form').submit(); // Gửi form
            }
        });
        $('.booking-form').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serializeArray();
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
                var discount = $(this).closest('tr').find('input[name="discount"]').val();
                // console.log(roomId, roomTypeId, checkInDate, checkInTime, checkOutDate, checkOutTime, adult, note);
                const errorDiv = document.querySelector('.message-error');

                if (new Date(checkOutDate) < new Date(checkInDate)) {

                    errorDiv.textContent = `Ngày trả phòng phải lớn hơn ngày nhận phòng`;
                    errorDiv.classList.add('alert', 'alert-danger');
                    errorDiv.style.display = 'block';
                    hasError = false;
                    return false;
                }
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
                    discount: discount,
                });

            });

            if (hasError) {
                roomData.forEach(function(item) {
                    const roomDates = getDatesBetween(item['checkInDate'], item['checkInTime'],
                        item['checkOutDate'], item['checkOutTime'], item['roomId'], item[
                            'roomTypeId'],
                        item['adult'], item['note'], item['deposit'], item['discount'], "");

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
                                $('#myModal-booking').modal('hide');
                                $('#list-booking').empty();
                                $('#name, #phone, #name_book').val("");
                                //    loadRoomBookings();

                                window.location.reload();

                            } else {
                                notify('error', response.error);
                            }
                        },
                    });
                }
            }

        });

        function allStaffandCustomerSource() {
            $.ajax({
                url: getCustomerStaff,
                type: 'GET',
                success: function(data) {

                    var selected_customer_source = $('#select-customer-source');
                    selected_customer_source.empty();
                    let option = `<option value="">Chọn nguồn khách hàng</option>`;
                    data.customerSourse.forEach(function(item) {
                        if (item.id == data.option_customer_source) {
                            option +=
                                `<option value="${item.source_code}" selected>${item.source_name}</option>`;
                        } else {
                            option +=
                                `<option value="${item.source_code}">${item.source_name}</option>`;
                        }
                    });
                    selected_customer_source.append(option);
                    // nhân viên
                    var selected_select_staff = $('#select-staff');
                    selected_select_staff.empty();
                    let option_staff = `<option value="">Chọn nhân viên</option>`;
                    data.admin.forEach(function(item) {
                        if (item.id == data.option_customer_source) {
                            option_staff +=
                                `<option value="${item.id}" selected>${item.username}</option>`;
                        } else {
                            option_staff +=
                                `<option value="${item.id}">${item.username}</option>`;
                        }
                    });
                    selected_select_staff.append(option_staff);
                    $('#loading').hide();
                },
                error: function(error) {
                    $('#loading').hide();
                    console.log('Error:', error);
                }
            });
        }
        $('.add-room-list').on('click', function() {

            let dataListValue = $(this).attr('data-list');
            const selectedCheckboxes = [];
            $('#show-room input[type="checkbox"]:checked').each(function() {
                const checkboxData = {
                    room: $(this).data('id'),
                    room_type: $(this).data('room_type_id'),
                    date: $(this).data('date')
                };
                selectedCheckboxes.push(checkboxData);
            });
            addRoomInBooking(selectedCheckboxes, $(`#${dataListValue}`))
        });

        function addRoomInBooking(data, list) {
            $('#loading').show();
            $.ajax({
                url: checkRoomBookingUrl,
                type: 'POST',
                data: {
                    data: JSON.stringify(data)
                },
                success: function(response) {
                    var tbody = list;

                    let targetId = list[0]?.id;
                    targetId === 'list-booking-edit' ?
                        $('#list-booking').empty() :
                        targetId === 'list-booking' ?
                        $('#list-booking-edit').empty() :
                        null;
                    if (response.status === 'success') {
                        const seenRooms = new Set();
                        let totalPrice = 0;

                        response.data.forEach(item => {


                            let date = new Date(item.date);
                            date.setDate(date.getDate() + 1);
                            const roomId = item.room["id"];
                            const roomTypeId = item.room_type["id"];
                            const roomDate = item.date;
                            const key = `${roomId}-${roomTypeId}-${roomDate}`;

                            if (seenRooms.has(key)) {
                                return;
                            }
                            seenRooms.add(key);

                            var tr = `
                            <tr  data-status="0" data-room-id="${roomId}"  data-room-type-id="${roomTypeId}" data-date="${item.date}">
                                <td>
                                    <input type="checkbox">
                                </td>

                                <td>
                                    <p class="room__name"> ${item.room['room_number']}</p>
                                </td>
                                 <td>
                                     <input type="number" min="1" name="adult" class="form-control adult"  value="1"  style="margin-left: 16px;">

                                </td>
                                <td >
                                    <select id="bookingType" class="form-select" name="optionRoom" style="width: 93px; font-size:15px">
                                         <option value="ngay">Ngày</option>
                                         <option value="gio">Giờ</option>
                                    </select>
                                </td>
                                 <td>
                                    <div class="d-flex align-items-center justify-content-start" style="gap: 3px">
                                        <input type="date" name="checkInDate" id="date-book-room" class="form-control date-book-room"  value="${item.date}" readonly>

                                        <input type="time" name="checkInTime" id="time-book-room" class="form-control time-book-room"   value="${item.room['room_type']['room_type_price']['setup_pricing']['check_in_time']}">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-start" style="gap: 3px">
                                        <input type="date" name="checkOutDate"  class="form-control date-book-room" readonly  value="${date.toISOString().split('T')[0]}">

                                        <input type="time" name="checkOutTime" id="time-book-room" class="form-control time-book-room"  value="${item.room['room_type']['room_type_price']['setup_pricing']['check_out_time']}">

                                    </div>
                                </td>
                                <td>
                                     <p id="price" data-price="${item.room['room_type']['room_type_price']['unit_price']}">${formatCurrency(item.room['room_type']['room_type_price']['unit_price'])}</p>
                                </td>
                                <td>
                                      <input type="text" class="form-control deposit number-input money-input"  name="deposit"  placeholder="0">
                                </td>
                                 <td>
                                      <input type="text" class="form-control discount number-input-discount money-input"  name="discount"  placeholder="0">
                                </td>
                                <td>
                                    <input type="text" name="note_room" class="form-control note_room" value="" id="note">
                                </td>
                            </tr>
                        `;

                            // tbody.append(tr); 
                            const tableSelector = targetId === 'list-booking-edit' ?
                                '#list-booking-edit' : '#list-booking';
                            let isDuplicate = $(`${tableSelector} tr`).filter(function() {
                                return $(this).attr('data-room-id') == roomId &&
                                    $(this).attr('data-room-type-id') ==
                                    roomTypeId &&
                                    $(this).attr('data-date') == item.date;
                            }).length > 0;

                            if (!isDuplicate) {
                                tbody.append(tr);
                            }
                        })
                        totalPrice = calculateTotalPrice();

                        $('#loading').hide();
                        let totalDeposit = 0;
                        let totalBalance = 0;
                        $('tr').find('input.deposit').on('blur', function() {
                            let rowTotal = 0;
                            $('tr').each(function() {
                                $(this).find('input.deposit').each(function() {
                                    let depositValue = $(this).val()
                                        .replace(/[,.]/g, '');
                                    let numericDeposit = parseInt(
                                        depositValue) || 0;
                                    rowTotal += numericDeposit;
                                });
                            });

                            $('.total_deposit').text(formatCurrency(rowTotal));
                            let priceString = $('.total_discount').text();
                            let price = parseInt(priceString.replace(/\./g, ""), 10);
                            price = isNaN(price) ? 0 : price;
                            totalBalance = totalPrice - rowTotal - price;
                            $('.total_balance').text(formatCurrency(totalBalance));
                        });
                        $('tr').find('input.discount').on('blur', function() {
                            let rowTotal = 0;
                            $('tr').each(function() {
                                $(this).find('input.discount').each(function() {
                                    let depositValue = $(this).val()
                                        .replace(/[,.]/g, '');
                                    let numericDeposit = parseInt(
                                        depositValue) || 0;
                                    rowTotal += numericDeposit;
                                });
                            });

                            $('.total_discount').text(formatCurrency(rowTotal));
                            let priceString = $('.total_deposit').text();
                            let price = parseInt(priceString.replace(/\./g, ""), 10);
                            price = isNaN(price) ? 0 : price;
                            totalBalance = totalPrice - rowTotal - price;
                            $('.total_balance').text(formatCurrency(totalBalance));
                        });

                        $('#addRoomModal').modal('hide');
                        $('#myModal-booking').modal('show');
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

        $('#selected-name-phong, #selected-hang-phong, #date-chon-phong-out, #date-chon-phong-in, #status-room')
            .on('change', function() {
                var selectedOptionHangPhong = $('#selected-hang-phong').val();
                var selectedOptionNamePhong = $('#selected-name-phong').val();
                var selectedOptionStatusPhong = $('#status-room').val();
                const roomIds = [];
                $('#list-booking tr').each(function() {
                    const roomId = $(this).attr('data-room-id');
                    const dateId = $(this).attr('data-date');
                    if (roomId && dateId) {
                        roomIds.push({
                            roomId: roomId,
                            dateId: dateId
                        });
                    }
                });
                const checkInDateValue = $('#date-chon-phong-in').val();
                const checkOutDateValue = $('#date-chon-phong-out').val();

                showRoom(roomIds, checkInDateValue, checkOutDateValue, selectedOptionHangPhong,
                    selectedOptionNamePhong,
                    selectedOptionStatusPhong)
            });

        $('.add-room-booking').on('click', function() {
            let roomIds = [];
            $('.add-room-list').attr('data-list', 'list-booking');
            $('#list-booking tr').each(function() {
                const roomId = $(this).attr('data-room-id');
                const dateId = $(this).attr('data-date');
                if (roomId && dateId) {
                    roomIds.push({
                        roomId: roomId,
                        dateId: dateId
                    });
                }


            });
            const checkInDateValue = $('#date-book-room-booking').val();

            // Lấy giá trị của input checkOutDate
            //   const checkOutDateValue = getCurrentDate();
            showRoom(roomIds, checkInDateValue, checkInDateValue, '', '')
            $('#addRoomModal').modal('show');
            $('#myModal-booking').modal('hide');
            $('#addRoomModal').on('shown.bs.modal', function() {
                document.body.classList.add('modal-open');
                $('#addRoomModal').addClass('z__index-mod');
            });

        });

        function showRoom(data = "", checkInDateValue = "", checkOutDateValue = "", selectedOptionHangPhong =
            "",
            selectedOptionNamePhong = "", selectedOptionStatusPhong = "") {
            $('#loading').show();
            $('[id="date-chon-phong-in"]').val(checkInDateValue);
            $('[id="date-chon-phong-out"]').val(checkOutDateValue);
            $.ajax({
                url: showRoomUrl,
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
                    var tbody = $('#show-room');
                    const dataNew = data.data;
                    let seenRooms = new Set();
                    tbody.empty();


                    dataNew.forEach(function(item) {
                        let rowClass = '';
                        let isFirst = !seenRooms.has(item.room_number);
                        seenRooms.add(item.room_number);
                        if (!isFirst) {

                            if (item.check_booked === 'Đã nhận') {
                                rowClass = "background-red";
                            } else if (item.check_booked === 'Đã đặt') {
                                rowClass = 'background-yellow';
                            } else if (item.check_booked === 'Trống') {
                                rowClass = "background-primary";
                            }
                        } else {
                            if (item.check_booked === 'Đã nhận') {
                                rowClass = "background-red";
                            } else if (item.check_booked === 'Đã đặt') {
                                rowClass = 'background-yellow';
                            } else if (item.check_booked === 'Trống') {
                                rowClass = "background-primary";
                            } else {
                                rowClass = "background-white";
                            }
                        }
                        let firstRowClass = isFirst ? "first-row" : "";
                        var tr = `
                        <tr class="${firstRowClass}">
                            <td style="${isFirst ? 'font-weight: bold;' : ''}" class="text-left"> ${item.room_type['name']} </td>
                            <td style="${isFirst ? 'font-weight: bold;' : ''}"class="text-left"> ${item.room_number} </td>
                            <td style="${isFirst ? 'font-weight: bold;' : ''}"class="text-left"> ${formatDate(item.date)} </td>
                            <td style="${isFirst ? 'font-weight: bold;' : ''}"class="text-left ${rowClass}"> ${item.check_booked} </td>
                            <td style="${isFirst ? 'font-weight: bold;' : ''}"class="text-right"> ${formatCurrency(item.room_type.room_type_price['unit_price'])} </td>
                            <td>
                                <input type="checkbox" ${item.status == 1 ? 'disabled' : ''} ${item.checkbox !== undefined ? 'checked disabled' : ''} data-date="${item.date}" data-id="${item.id}" data-room_type_id="${item.room_type_id}" id="checkbox-${item.id}">
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
                            option +=
                                `<option value="${item.id}" selected>${item.name}</option>`;
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
                            options +=
                                `<option value="${item.id}">${item.room_number}</option>`;
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
                url: searchCustomerUrl,
                type: 'GET',
                data: {
                    name: customerName,
                },
                success: function(response) {
                    // notify('success', response.success);
                    // $('.note-booking').html(note);
                    // $('#noteModal').modal('hide');
                    // if (response.status == 'success') {
                    //     if (response.data !== null) {
                    //         $('#phone').val(response.data['phone']);
                    //     } else {
                    //         $('#phone').val('');
                    //     }

                    // }
                    let selectPhone = $('#selectphone');

                    if (response.status == 'success') {

                        selectPhone.empty();
                        // Nếu có số điện thoại, cập nhật giá trị vào select
                        if (response.data.length > 0) {
                            // selectPhone.append('<option value="">' + 'Chọn số điện thoại' + '</option>');
                            response.data.forEach(function(phoneInfo) {
                                selectPhone.append('<option value="' + phoneInfo.phone +
                                    '">' + phoneInfo.phone + '</option>');
                            });
                        } else {
                            // Nếu không có số điện thoại, thêm tùy chọn "Chưa có số điện thoại"
                            selectPhone.empty(); // Xóa tất cả các tùy chọn hiện tại trong select
                            selectPhone.append(
                                '<option value="">Chưa có số điện thoại</option>'
                            ); // Thêm tùy chọn mới
                            selectPhone.val('').trigger(
                                'change'
                            ); // Đặt giá trị mặc định là "Chưa có số điện thoại" và kích hoạt lại select2
                        }
                    }

                },
                error: function(xhr, status, error) {

                    // alert('Có lỗi xảy ra khi lưu ghi chú!');
                }
            });
        }

    });

    function showCustomer(value = "", option_customer_source = "") {
        $('#loading').show();
        $.ajax({
            url: searchCustomerUrl,
            type: 'GET',
            data: {
                name: value,
                option_customer_source: option_customer_source
            },
            success: function(data) {
                // <p data-id="${ item.id }" data-room_type_id="${ item.room_type_id }" class="add-book-room" id="add-book-room">Đặt phòng</p>
                var tbody = $('#show-customer');
                tbody.empty();
                data.data.forEach(function(item) {
                    var tr = `
                <tr class="customer-row">
                    <td class="text-left "> ${item.customer_code} </td>
                    <td class="text-left "> ${item.name} </td>
                    <td class="text-right"> ${item.phone} </td>
                    <td class="text-left "> ${item.group_code} </td>
                    <td class="text-center">
                        <input type="radio" name="customer_select" data-id="${item.id}">
                    </td>
                </tr>
            `;
                    tbody.append(tr);
                });
                // hạng phòng
                var selected_customer_source = $('#selected-customer-source');
                selected_customer_source.empty();
                let option = `<option value="">Chọn nguồn khách hàng</option>`;
                data.customerSourse.forEach(function(item) {
                    if (item.id == data.option_customer_source) {
                        option +=
                            `<option value="${item.source_code}" selected>${item.source_name}</option>`;
                    } else {
                        option +=
                            `<option value="${item.source_code}">${item.source_name}</option>`;
                    }
                });
                selected_customer_source.append(option);
                $('#loading').hide();
            },
            error: function(error) {
                $('#loading').hide();
                console.log('Error:', error);
            }
        });
    }

    function loadScript(view) {
        let scriptId = 'view-script';
        let oldScript = document.getElementById(scriptId);
        if (oldScript) {
            oldScript.remove();
        }
        let script = document.createElement('script');
        script.id = scriptId;
        script.src = `{{ asset('assets/admin/js/${view}-main.js') }}`;
        script.onload = function() {};
        document.body.appendChild(script);
    }
    document.addEventListener("DOMContentLoaded", function() {
        const buttons = document.querySelectorAll(".view-toggle button");

        // Lấy trạng thái lưu trữ từ LocalStorage, mặc định là 'list'
        let savedView = localStorage.getItem('selectedView') || 'list';
        setActiveButton(savedView);

        buttons.forEach(button => {
            button.addEventListener("click", function() {
                let selectedView = this.getAttribute("onclick").match(/'([^']+)'/)[1];
                localStorage.setItem('selectedView', selectedView);
                setActiveButton(selectedView);
            });
        });

        function setActiveButton(view) {
            buttons.forEach(btn => {
                btn.classList.remove("active");
                btn.querySelector(".text").style.display = "none";
            });

            let activeButton = document.querySelector(`[onclick="changeView('${view}')"]`);
            if (activeButton) {
                activeButton.classList.add("active");
                activeButton.querySelector(".text").style.display = "inline";
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        let savedView = localStorage.getItem('selectedView') || 'list';

        changeView(savedView);
    });

    function changeView(view) {
        document.querySelectorAll('.view').forEach(el => el.style.display = 'none');
        document.getElementById(view + 'View').style.display = 'block';
        loadScript(view);
        localStorage.setItem('selectedView', view);
        // let html  = ""
        // if(view == 'calendar'){
        //         html += `
        //             <input type="date" id="startDate" class="form-control w-auto" style="height: 40px" placeholder="Từ ngày">
        //                 <input type="date" id="endDate" class="form-control w-auto" style="height: 40px"
        //                     placeholder="Đến ngày">
        //         `;
        // }
        // document.querySelectorAll('.date-input-booking').forEach(el => {    el.innerHTML = html;});



    }
</script>
<style scoped>
    .search-container {
        width: 50%;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-content {
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .modal-dialog {
        display: flex;
        align-items: center;
        justify-content: center;
        max-width: 80vw !important;
        max-height: 90vh;
        width: 100%;
        margin: auto;
    }

    .delete-room-booking,
    .delete-room-booking-edit {
        border: 1px solid #f03514;
        border-radius: 8px;
        cursor: pointer;
        color: #f03514;
        padding: 0px 10px 0px 10px;
    }

    .financial-item {
        display: flex;
        justify-content: space-between;
        padding: 5px;
    }

    .table-responsive {
        display: block;
        height: 170px !important;
        overflow-y: scroll;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    #bookingForm .form-control,
    #bookingForm .form-select {
        height: 36px;
        border-radius: 4px;
    }

    .adult {
        width: 60px !important;
        height: 30px;
        text-align: center;
    }

    .deposit,
    .discount {
        padding: 0px 3px !important;
        text-align: end;
        /* width: 135px !important; */
    }

    .table-responsive {
        overflow-y: scroll;
        height: auto;
        scrollbar-width: none;
    }

    #list-booking tr,
    #list-booking-edit tr {
        vertical-align: top;
    }

    #bookingForm .form-control,
    #bookingForm .form-select {
        height: 36px;
        border-radius: 4px;
    }

    .financial-list {
        width: 30%;
        list-style-type: none;
        padding: 0;
    }

    .main-booking-modal {
        background: #ddd;
        padding: 8px 10px;
        border-radius: 8px;
    }

    .add-room-booking,
    .add-room-booking-edit {
        padding: 4px 10px;
        border: 1px solid #337ab7;
        border-radius: 8px;
        cursor: pointer;
        color: #337ab7;
    }

    .modal-content {
        max-height: 90vh;
        overflow-y: auto;
        scrollbar-width: none;
    }

    .alert-paragraph {
        display: inline-block;
        padding: 3px 20px;
        background-color: #f44336;
        color: white;
        border-radius: 5px;
        text-align: center;
        cursor: pointer;
    }

    .btn-dat-truoc {
        text-wrap: nowrap;
        color: #fff;
        background-color: #4634ff;
        border-color: #4634ff;
        border-radius: 5px;
        display: inline-block;
        padding: 3px 20px;
    }

    .modal-content {
        max-height: 90vh;
        overflow-y: auto;
        scrollbar-width: none;
    }

    .overflow-add-room {
        overflow-y: scroll;
        height: 529px;
    }
</style>

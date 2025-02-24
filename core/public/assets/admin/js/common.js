document.addEventListener('input', function (e) {
    if (e.target.classList.contains('money-input')) {
        let value = e.target.value.replace(/\D/g, ""); // Xóa ký tự không phải số
        value = Number(value).toLocaleString('vi-VN'); // Định dạng theo chuẩn Việt Nam
        e.target.value = value;
    }
});



function formatCurrency(amount) {
    if (!amount || isNaN(amount)) {
        return '0 VND'; // Nếu amount không hợp lệ, trả về 0 VND
    }

    const parts = parseFloat(amount).toFixed(2).toString().split('.');
    const integerPart = parts[0];
    const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    return formattedInteger + ' VND';
}
function showCustomer(value = "", option_customer_source = "") {
    $('#loading').show();
    $.ajax({
        url: searchCustomerUrl,
        type: 'GET',
        data: {
            name: value,
            option_customer_source: option_customer_source
        },
        success: function (data) {
            // <p data-id="${ item.id }" data-room_type_id="${ item.room_type_id }" class="add-book-room" id="add-book-room">Đặt phòng</p>
            var tbody = $('#show-customer');
            tbody.empty();
            data.data.forEach(function (item) {
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
            data.customerSourse.forEach(function (item) {
                if (item.id == data.option_customer_source) {
                    option += `<option value="${item.source_code}" selected>${item.source_name}</option>`;
                } else {
                    option += `<option value="${item.source_code}">${item.source_name}</option>`;
                }
            });
            selected_customer_source.append(option);
            $('#loading').hide();
        },
        error: function (error) {
            $('#loading').hide();
            console.log('Error:', error);
        }
    });
}
function findCustomerById(id) {
    $.ajax({
        url: findCustomerUrl,
        type: 'GET',
        data: {
            id: id,
        },
        success: function (data) {
            $('input[name="name"]').val(data.data.name);
            $('input[name="phone"]').val(data.data.phone);
            $('input[name="customer_code"]').val(data.data.customer_code);
            $("#select-customer-source").val(data.data.group_code).change();
            $("#select-customer-source-edit").val(data.data.group_code).change();
            $('#addCustomerModal').modal('hide');
            $('#loading').hide();
        },
        error: function (error) {
            $('#loading').hide();
            console.log('Error:', error);
        }
    });
}
function formatMoneyInput(input) {
    let value = input.value.replace(/\D/g, ""); // Loại bỏ ký tự không phải số
    if (value) {
        input.value = new Intl.NumberFormat('vi-VN').format(value);
    } else {
        input.value = "";
    }
}

function calculateTotalPrice() {
    let totalPrice = 0;
    let totalDeposit = 0;
    let totalDiscount = 0;
    $('#list-booking, #list-booking-edit').find('p#price').each(function () {
        let priceString = $(this).attr('data-price');
        let price = parseFloat(priceString.replace(' VND', '').replace(',', '.'));
        totalPrice += price;
    });
    $('#list-booking, #list-booking-edit').find('input.deposit').each(function () {
        let priceString = $(this).val(); // Lấy giá trị nhập trong input
        let price = parseFloat(priceString.replace(/[,.]/g, '')); // Loại bỏ ký tự không phải số

        if (!isNaN(price)) {
            totalDeposit += price;
        }
    });
    $('#list-booking, #list-booking-edit').find('input.discount').each(function () {
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
    $('.total_balance').each(function () {
        $(this).text(formatCurrency(totalPrice - totalDeposit - totalDiscount));
    });

    $('.total_amount').each(function () {
        $(this).text(formatCurrency(totalPrice));
    });
    // giảm giá

    $('.total_discount').each(function () {
       
        $(this).text(formatCurrency(totalDiscount));
    });

    $('.total_deposit').each(function () {
        $(this).text(formatCurrency(totalDeposit));
    });

    //  $('#total_balance').text(formatCurrency(totalPrice));
    // $('#total_deposit').text(formatCurrency(totalPrice));
    return totalPrice;
}
function countBookings() {
    let bookingList = document.getElementById("list-booking");
    let rows = bookingList.getElementsByTagName("tr");
    return rows.length; // Trả về số lượng hàng <tr>
}

// Kiểm tra nếu danh sách đặt phòng có ít nhất một hàng
function hasBookings() {
    return countBookings() > 0;
}
function formatCurrencyEdit(amount) {
    const parts = amount.toString().split('.');
    const integerPart = parts[0];
    const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    return formattedInteger;
}
$(document).on('click', '.close_modal', function () {
    $('#myModal-booking').modal('hide');
    $('#myModal-booking-edit').modal('hide');
    $("#myModal-check-in-edit").modal('hide');
});

$(document).on('click', '.close_modal_booked_room', function () {
    $('#addRoomModal').modal('hide');
    $('#addCustomerModal').modal('hide');
});
$('.add-room-booking-edit').on('click', function () {
    const roomIds = [];
    $('.add-room-list').attr('data-list', 'list-booking-edit');
    $('#list-booking tr').each(function () {
        const roomId = $(this).attr('data-room-id');
        if (roomId) {
            roomIds.push(roomId);
        }
    });
    const checkInDateValue = $('#date-book-room-booking-edit').val();

    // Lấy giá trị của input checkOutDate
    const checkOutDateValue = $('#date-book-room-date').val();
    showRoom(roomIds, checkInDateValue, checkOutDateValue, '', '')
    $('#addRoomModal').modal('show');

    $('#addRoomModal').on('shown.bs.modal', function () {
        document.body.classList.add('modal-open');
        $('#addRoomModal').addClass('z__index-mod');
    });
});
$('.add-room-list').on('click', function () {
    let dataListValue = $(this).attr('data-list');
    const selectedCheckboxes = [];
    $('#show-room input[type="checkbox"]:checked').each(function () {
        const checkboxData = {
            room: $(this).data('id'),
            room_type: $(this).data('room_type_id'),
            date: $(this).data('date')
        };
        selectedCheckboxes.push(checkboxData);
    });
    addRoomInBooking(selectedCheckboxes, $(`#${dataListValue}`))
});
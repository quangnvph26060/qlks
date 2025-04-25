function getCurrentDate() {
    const currentDate = new Date();
    const year = currentDate.getFullYear();
    const month = String(currentDate.getMonth() + 1).padStart(2, '0'); // Thêm 1 vì tháng bắt đầu từ 0
    const day = String(currentDate.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
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
function formatDateTime(dateTimeStr) {
    const date = new Date(dateTimeStr);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0'); // tháng bắt đầu từ 0
    const year = date.getFullYear();

    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');

    return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
}
function formatDate(date) {
    return date.toISOString().split('T')[0];
}

$(document).ready(function () {
    const savedView = localStorage.getItem('selectedView') || 'list';
    let htmlrow = '';
    if (savedView === "calendar") {
        htmlrow = `
            <input type="date" id="startDate" class="form-control w-auto" style="height: 40px" placeholder="Từ ngày">
            <input type="date" id="endDate" class="form-control w-auto" style="height: 40px" placeholder="Đến ngày">
        `;
    } else {
        htmlrow = `<input type="date" id="startDate" class="form-control w-auto" style="height: 40px">`;
    }

    $('#date-input-booking').html(htmlrow);

    let today = new Date();
    let futureDate = new Date();
    futureDate.setDate(today.getDate() + 5);

    // Hàm định dạng ngày yyyy-mm-dd
    function formatDate(date) {
        let d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }

    $('#startDate').val(formatDate(today));
    $('#endDate').val(formatDate(futureDate)); // Chỉ set nếu có endDate
});
function initGridMain(data,date) {
    $.ajax({
        type: "GET",
        url: roomBoookingHistory,
        data: {
            date: date,
            data: data,
            method: 'list-room-booking',
        },
        success: function (response) {
            if (response.status === 'success') {
                let roomHTML = '';
                let stt = 1;

                Object.entries(response.data).forEach(([bookingId, items]) => {
                    let firstItem = items[0];
                    let roomCount = items.length;
                    let totalAmount = items.reduce((sum, item) => sum + parseFloat(item.total_amount), 0);
                   // let totalWithExtras = parseFloat(firstItem.discount || 0) + parseFloat(firstItem.deposit_amount || 0);
                    let totalWithExtras =items.reduce((sum, item) => sum + parseFloat(item.deposit_amount) + parseFloat(item.discount), 0);
                    // dòng chính
                    roomHTML += `
                      <tr class="main-row" data-booking="${bookingId}">
                        <td>${stt++}</td>
                        <td>${bookingId}</td>
                        <td>
                        ${roomCount > 1
                        ? `<a href="#" class="toggle-rooms" data-booking="${bookingId}">${roomCount} phòng ⮟</a>`
                        : `<span>${firstItem.room.room_number}</span>`}
                        </td>
                        <td>${firstItem.customer_name} <br> ${firstItem.phone_number}</td>
                        <td class="w-10">${formatDateTime(firstItem.checkin_date)}</td>
                        <td class="w-10">${formatDateTime(firstItem.checkout_date)}</td>
                        <td class="text-right">${formatCurrency(totalAmount)}</td>
                        <td class="text-right">${totalWithExtras.toLocaleString()} VND</td>

                        <td class="w-20" style="position: relative;">
                            <div class="d-flex" style="gap:10px;align-items: anchor-center;">
                                    <button  style="height:37px" class="btn ${firstItem.paid_amount > 0 ? 'btn-success' : 'btn-primary'} ">
                                        ${firstItem.paid_amount > 0 ? 'Thanh toán' : 'Trả phòng'}
                                    </button>
                                    <button class="menu-toggle-btn"  data-room-id="${items.room_code}" data-booking="${bookingId}" style="">⋮</button>
                            </div>
                            <div class="room-action-menu menu-main-list" data-booking="${bookingId}" data-room-id="${items.room_code}">
                                <div class="dropdown-item">Thêm sản phẩm, dịch vụ</div>
                                <div class="dropdown-item">Sửa đặt phòng</div>
                                <div class="dropdown-item">Hủy đặt phòng</div>
                            </div>
                        </td>

                      </tr>
                    `;

                    // dòng con
                    if (roomCount > 1) {
                        items.forEach(item => {
                            roomHTML += `
                              <tr class="room-detail-row" data-parent="${bookingId}" style="display: none;">
                                  <td></td>
                                   <td></td>
                                   
                                <td><span>${item.room.room_number}</span></td>
                                 <td>${item.customer_name} <br> ${item.phone_number}</td>
                                <td class="w-10">${formatDateTime(item.checkin_date)}</td>
                                <td class="w-10">${formatDateTime(item.checkout_date)}</td>
                                <td class="text-right">${formatCurrency(item.total_amount)}</td>
                                <td class="text-right">${formatCurrency( parseFloat(item.discount || 0) + parseFloat(item.deposit_amount || 0))}</td>
                                <td class="w-20" style="position: relative;">
                                    <div class="d-flex" style="gap:10px;align-items: anchor-center;">
                                            <button style="height:37px" class="btn ${firstItem.paid_amount > 0 ? 'btn-success' : 'btn-primary'}">
                                                ${firstItem.paid_amount > 0 ? 'Thanh toán' : 'Trả phòng'}
                                            </button>
                                            <button class="menu-toggle-btn" data-booking="${bookingId}">⋮</button>
                                    </div>
                                    <div class="room-action-menu menu-main-list" data-booking="${bookingId}" >
                                        <div class="dropdown-item">Thêm sản phẩm, dịch vụ</div>
                                        <div class="dropdown-item">Sửa đặt phòng</div>
                                        <div class="dropdown-item">Hủy đặt phòng</div>
                                    </div>
                                </td>

                              </tr>
                            `;
                        });
                    }
                });

                // Gắn vào DOM
                $("#booking-table").html(roomHTML);

                // Toggle dòng con
                $(".toggle-rooms").on("click", function (e) {
                    e.preventDefault();
                    const bookingId = $(this).data("booking");
                    $(`.room-detail-row[data-parent="${bookingId}"]`).toggle();
                });

            } else {
                reject("Không lấy được dữ liệu");
            }
        },
        error: function (error) {
            reject(error);
        }
    });

}
// Toggle menu dropdown
$(document).on("click", ".menu-toggle-btn", function (e) {
    e.stopPropagation();
    const bookingId = $(this).data("booking");
    $(".room-action-menu").hide(); // ẩn hết menu cũ
    $(`.room-action-menu[data-booking="${bookingId}"]`).toggle(); // toggle menu của booking đang chọn
});

// Ẩn menu khi click ra ngoài
$(document).on("click", function () {
    $(".room-action-menu").hide();
});

$(document).ready(function () {
    let selectedDate = $('#startDate').val(); 
    initGridMain('', selectedDate); 
});
$(document).on('change', '#startDate', function () {
    let selectedDate = $(this).val();
    initGridMain('', selectedDate);
});
document.addEventListener("DOMContentLoaded", function () {
    console.log("Danh sách JS đã chạy!");

    const buttons = document.querySelectorAll(".view-toggle button");

    buttons.forEach(button => {
        button.addEventListener("click", function () {
            buttons.forEach(btn => {
                btn.classList.remove("active");
                btn.querySelector(".text").style.display = "none";
            });

            this.classList.add("active");
            this.querySelector(".text").style.display = "inline";
        });
    });
});

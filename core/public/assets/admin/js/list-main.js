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
    $('#loading-overlay').css('display', 'flex');
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
                response.data.forEach(item=>{

                    let dem = stt++;
                    item.room_booking_history.forEach(booked => {
                            
                        if(booked['status_code'] == 3 && booked['check_in_data'].length > 0){
                            
                            booked['check_in_data'].forEach(checkIn =>{
                                if(booked['room_id']  === checkIn['room_code']){
                                           roomHTML += `
                                              <tr class="main-row" data-booking="${checkIn['check_in_id']}">
                                                <td>${dem}</td>
                                                  <td class="w-20" style="position: relative;">
                                                    <div class="d-flex align-items-center justify-content-center" style="gap:10px;">
                                                            <button class="menu-toggle-btn" data-id="${checkIn['id']}" data-booking="${checkIn['check_in_id']}">
                                                                <svg class="" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"></circle><circle cx="10.5" cy="5.5" r="1"></circle><circle cx="10.5" cy="15.5" r="1"></circle></g></svg>
                                                            </button>
                                                    </div>
                                                    <div class="room-action-menu menu-main-list" data-id="${checkIn['id']}" data-booking="${checkIn['check_in_id']}">
                                                        <div class="dropdown-item room_clean"  data-id="${item.id}"> ${item['is_clean'] == 1 ? 'Chưa dọn' : 'Làm sạch' }</div>
                                                        <div class="dropdown-item">Thêm sản phẩm, dịch vụ</div>
                                                        <div class="dropdown-item">Đổi phòng</div>
                                                        <div class="dropdown-item">Thanh toán</div>
                                                    </div>
                                                </td>
                                                <td>${checkIn['check_in_id']}</td>
                                                <td>
                                                    <span>${item['room_number']}</span>
                                                <br>  <span class="status-clean">${item['is_clean'] == 1 ? "✨ Sạch" : "🚨 Chưa dọn"}</span></td>
                                                
                                                
                                                <td>${checkIn['customer_name']} <br> ${checkIn['phone_number'] ?? 'N/A'}</td>
                                                <td class="w-10">${formatDateTime(checkIn['checkin_date'])}</td>
                                                <td class="w-10">${formatDateTime(checkIn['checkout_date'])}</td>
                                                <td class="text-right">
                                                    ${formatCurrency(
                                                        Number(checkIn['total_amount']) + Number(checkIn['check_in_service_products'])
                                                    )}
                                                </td>

                                                <td class="text-right">${formatCurrency(checkIn['check_in_payment'])}</td>
                                              

                                              </tr>
                                            `;
                                   
                                    
                                }   
                            })
                            
                        }else if(booked['status_code'] == 2 && booked['booking_data'].length > 0){
                            booked['booking_data'].forEach(checkIn =>{
                                if(booked['room_id']  === checkIn['room_code']){
                                           roomHTML += `
                                              <tr class="main-row" data-booking="${checkIn['booking_id']}">
                                                <td>${dem}</td>
                                                 <td class="w-20" style="position: relative;">
                                                    <div class="d-flex align-items-center justify-content-center" style="gap:10px;">
                                                          
                                                            <button class="menu-toggle-btn" data-id="${checkIn['id']}"  data-booking="${checkIn['booking_id']}">
                                                            <svg class="" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"></circle><circle cx="10.5" cy="5.5" r="1"></circle><circle cx="10.5" cy="15.5" r="1"></circle></g></svg>
                                                            </button>
                                                    </div>
                                                    <div class="room-action-menu menu-main-list" data-id="${checkIn['id']}" data-booking="${checkIn['booking_id']}">
                                                        <div class="dropdown-item room_clean"  data-id="${item.id}" > ${item['is_clean'] == 1 ? 'Chưa dọn' : 'Làm sạch' }</div>
                                                        <div class="dropdown-item">Nhận phòng</div>
                                                        <div class="dropdown-item">Hủy phòng</div>
                                                    </div>
                                                </td>
                                                <td>${checkIn['booking_id']}</td>
                                                <td>${item['room_number']} <br>  <span class="status-clean">${item['is_clean'] == 1 ? "✨ Sạch" : "🚨 Chưa dọn"}</span></td>
                                                <td>${checkIn['customer_name']} <br> ${checkIn['phone_number'] ?? 'N/A'}</td>
                                                <td class="w-10">${formatDateTime(checkIn['checkin_date'])}</td>
                                                <td class="w-10">${formatDateTime(checkIn['checkout_date'])}</td>
                                                <td class="text-right">${formatCurrency(checkIn['total_amount'])}</td>
                                                    <td class="text-right">${formatCurrency(checkIn['check_in_payment'])}</td>
                                               
                                              

                                              </tr>
                                            `;
                                   
                                    
                                }   
                            })
                        }
                    });
                    
                    
                })
                // Gắn vào DOM
                $("#booking-table").html(roomHTML);

                // Toggle dòng con
                $(".toggle-rooms").on("click", function (e) {
                    e.preventDefault();
                    const bookingId = $(this).data("booking");
                    $(`.room-detail-row[data-parent="${bookingId}"]`).toggle();
                });
                setTimeout(function () {
                    $('#loading-overlay').css('display', 'none');
                }, 1000);
            } else {
                reject("Không lấy được dữ liệu");
            }
        },
        error: function (error) {
            reject(error);
        }
    });

}
$(document).off("click", ".room_clean").on("click", ".room_clean", function (e) {
    e.stopPropagation();

    // Lấy thông tin từ div được bấm
    const roomName = $(this).data("name");
    const roomId = $(this).data("id");
    const statusText = $(this).text().trim();

    // Gán vào modal
    $("#roomStatusModal .modal-body strong").text(roomName);
    $("#roomStatusModal .modal-body .status-text").text(statusText);
    $('.change_clean_room_btn').attr('data-id', roomId);
    // Mở modal
    const modal = new bootstrap.Modal(document.getElementById("roomStatusModal"));
    modal.show();
});
$(document).off("click", ".change_clean_room_btn").on("click", ".change_clean_room_btn", function (e) {
    const id = $(this).attr("data-id");
    $.ajax({
        url: cleanRoomUrl,
        type: 'POST',
        data: {
            id: id,
        },
        success: function (data) {
            if (data.status === 'success') {
                notify('success', data.success);
                const modal = bootstrap.Modal.getInstance(document.getElementById("roomStatusModal"));
                if (modal) modal.hide();

                let selectedDate = $('#startDate').val();
                initGridMain('', selectedDate);
            }

        },
        error: function (error) {
            $('#loading').hide();
            console.log('Error:', error);
        }
    });

});
// Toggle menu dropdown
$(document).on("click", ".menu-toggle-btn", function (e) {
    e.stopPropagation();
    const bookingId = $(this).data("booking");
    const roomId = $(this).data("room-id");

    // Ẩn tất cả menu cũ
    $(".room-action-menu").hide();

    // Hiện menu tương ứng với cả bookingId và roomId
    $(`.room-action-menu[data-booking="${bookingId}"][data-room-id="${roomId}"]`).toggle();
});
$(document).on("click", ".menu-toggle-btn", function (e) {
    e.stopPropagation();
    const bookingId = $(this).data("booking");

    $(".room-action-menu").hide();

    $(`.room-action-menu[data-booking="${bookingId}"]`).toggle();
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

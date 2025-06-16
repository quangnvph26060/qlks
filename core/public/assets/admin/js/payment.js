"use strict";

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function () {
    loadRoomBookings(); // Function to load the room bookings
    $(document).on('click', '.svg_menu_check_in', function (e) {
        e.stopPropagation();
        const $dropdown = $(this).siblings('.menu_dropdown_check_in');
        $('.menu_dropdown_check_in').not($dropdown).removeClass('show');
        $dropdown.toggleClass('show');
    });
    $(document).on('click', '.btn-submit-search-book', function () {
        let bookingCode = $('#booking_code').val();

        let data = {
            bookingCode: bookingCode,
        }

        loadRoomBookings(1, data);
    });
    $(document).on('click', '.btn-submit-sync-book', function () {
        $('#booking_code').val('');
        $('#room_name').val('');
        $('#name_book').val('');
        loadRoomBookings();
    });




    $(document).on('click', '.click_modal_payment', function () {
        let status = $(this).data('status');
        let dataId = $(this).data('id');

        // Dọn nội dung cũ
        $('#payment-info').html('<p>Đang tải...</p>');

        $.ajax({
            url: paymentFind,
            type: 'GET',
            data: { id: dataId },
            success: function (response) {
                if (response.status === 'success') {
                    $('#paymentModal').modal('show');

                    const data = response.data;

                    const html = `
                        <li class="d-flex justify-content-between mb-2">
                            <span>Ngày chứng từ</span><strong>${formatDateTime(data['created_date'])}</strong>
                        </li>
                        <li class="d-flex justify-content-between mb-2">
                            <span>Mã thanh toán</span><strong>${data['payment_id'] ?? ""}</strong>
                        </li>
                        <li class="d-flex justify-content-between mb-2">
                            <span>Họ tên</span>
                            <strong class="text-primary">
                                ${data['check_in']?.['customer_name'] ?? data['room_booking']['customer_name']}
                            </strong>
                        </li>
                        <li class="d-flex justify-content-between mb-2">
                            <span>Phương thức thanh toán</span><strong>${data['payment_method'] ?? "N/A"}</strong>
                        </li>
                        <li class="d-flex justify-content-between mb-2">
                            <span>Tiền thanh toán</span><strong>${formatCurrency(data['total_payment'])}</strong>
                        </li>
                        <li class="d-flex justify-content-between mb-2">
                            <span>Tiền đặt cọc</span><strong>${formatCurrency(data['deposit_amount'])}</strong>
                        </li>
                        <li class="d-flex justify-content-between mb-2">
                            <span>Tiền dịch vụ</span><strong>${formatCurrency(data['service_fee'])}</strong>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <span>Trạng thái</span>${data['status_badge']}
                        </li>
                        <hr>
                        <button class="btn btn-primary mt-2 btn-confirm-payment" data-id="${data['id']}" style="float: right">Lưu</button>
                    `;

                    // Cập nhật nội dung
                    $('#payment-info').html(html);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX request failed: " + error);
                $('#payment-info').html('<p class="text-danger">Không thể tải dữ liệu thanh toán.</p>');
            }
        });
    });

    $(document).on('click', '.btn-confirm-payment', function () {
        var id = $(this).data('id');
        $.ajax({
            url: paymentFind, // Adjust this to your route
            type: 'GET',
            data: {
                id: id,
                method: 'payment'
            },
            success: function (response) {
                if (response.status === 'success') {
                    notify('success', response.msg);
                    $('#paymentModal').modal('hide');
                    loadRoomBookings();
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX request failed: " + error);
            }
        });

    })
    $(document).on('click', '.save-payment-btn', function () {
        const fullFormId = $(this).data('form-id');
        const paymentId = fullFormId.split('-').pop();
        $.ajax({
            url: changeCashierge,
            method: 'POST',
            data: {
                payment_id: paymentId,
                creator: $(`#${fullFormId} select[name="creator"]`).val()
            },
            success: function (response) {
                if (response.status == 'success') {
                    notify('success', response.message);
                    //  loadRoomBookings();
                }
            },
            error: function (xhr, status, error) {
                console.error('Error updating:', error);
            }
        });
    });
    $(document).on('click', '.table-row-payment', function () {
        try {
            const encodedItem = $(this).attr('data-item');
            const datainvoice = $(this).attr('data-invoice');
            const dataCustomer = $(this).attr('data-customer');
            const item = JSON.parse(decodeURIComponent(encodedItem));

            $('#modalContent').html(`
        <div class="row mb-2">
            <div class="col-md-6 d-flex align-items-center">
                <label class="form-label fw-bold me-2 mb-0" style="min-width: 120px;">Mã phiếu thu:</label>
                <div class="form-control-plaintext payment-code">${item.payment_code}</div>
            </div>
            <div class="col-md-6 d-flex align-items-center">
                <label class="form-label fw-bold me-2 mb-0" style="min-width: 100px;">Thời gian:</label>
                <div class="form-control-plaintext">${formatDateTime(item.paid_at)}</div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-6 d-flex align-items-center">
                <label class="form-label fw-bold me-2 mb-0" style="min-width: 120px;">Thu ngân:</label>
                <div class="form-control-plaintext">${item.creator?.name || 'Không xác định'}</div>
            </div>
          <div class="col-md-6 d-flex align-items-center">
    <label class="form-label fw-bold me-2 mb-0" style="min-width: 100px;">Phương thức:</label>
    <select class="form-select payment-method">
        <option value="Tiền mặt" ${item.payment_method === 'Tiền mặt' ? 'selected' : ''}>Tiền mặt</option>
        <option value="Thẻ tín dụng" ${item.payment_method === 'Thẻ tín dụng' ? 'selected' : ''}>Thẻ tín dụng</option>
        <option value="Chuyển khoản ngân hàng" ${item.payment_method === 'Chuyển khoản ngân hàng' ? 'selected' : ''}>Chuyển khoản ngân hàng</option>
    </select>
</div>


        </div>

        <div class="row mb-2">
            <div class="col-md-6 d-flex align-items-center">
                <label class="form-label fw-bold me-2 mb-0" style="min-width: 120px;">Khách hàng:</label>
                <div class="form-control-plaintext">${dataCustomer || "-"}</div>
            </div>
        </div>

            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Mã phiếu</th>
                        <th>Mã Thanh toán</th>
                        <th>Thời gian</th>
                        <th>Giá trị phiếu</th>
                        <th>Tiền thu</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>${item.payment_code}</td>
                        <td>${datainvoice || '-'}</td>
                        <td>${formatDateTime(item.paid_at)}</td>
                        <td>${formatCurrency(item.amount)}</td>
                     
                        <td>${formatCurrency(item.amount)}</td>
                        <td>${item.status_badge}</td>
                    </tr>
                </tbody>
            </table>
            <div class="text-end fw-bold mt-2">
                Tổng tiền thu: ${formatCurrency(item.amount)}
            </div>
            <div class="mt-4 d-flex justify-content-end gap-2">
                <button class="btn btn-success btn-history-transaction">Lưu</button>
                <button class="btn btn-secondary">In</button>
                <button class="btn btn-danger" data-bs-dismiss="modal">Hủy bỏ</button>
            </div>
        `);

            $('#transactionModal').modal('show');
        } catch (error) {
            console.error("Lỗi parse JSON:", error);
            alert("Dữ liệu không hợp lệ.");
        }
    });
    $(document).on('click', '.btn-history-transaction', function () {
        const paymentCode = $('.payment-code').text().trim();
        const paymentMethod = $('.payment-method').val();
        $.ajax({
            url: updatePaymentMethod, // Thay bằng route thực tế
            method: 'POST',
            data: {
                payment_code: paymentCode,
                payment_method: paymentMethod,

            },
            success: function (response) {
                if (response.status == 'success') {
                    notify('success', response.message);
                    $('#transactionModal').modal('hide');
                    loadRoomBookings();
                }

            },
            error: function (xhr) {
                alert('Có lỗi xảy ra khi lưu!');
            }
        });
    });

});
function loadRoomBookings(page = 1, data) {
    $('#loading-overlay').css('display', 'flex');
    $.ajax({
        url: paymentUrl, // Adjust this to your route
        type: 'GET',
        data: {
            page: page,
            data: data,
        },
        success: function (response) {

            if (response.status === 'success') {
                var data = response.data;
                var staff = response.staff;
                var pagination = response.pagination;
                const currentView = localStorage.getItem('viewMode');
                $('.data-table').html('');
                var html = '';
                var total = '';
                let totalPayment = 0, totalDiscount = 0, totaldeposit = 0, paymentTotal = 0;
                window.allInvoiceData = data;
                data.forEach(function (record, idx) {
                    totalPayment += parseFloat(record['room_price']) || 0;
                    totalDiscount += parseFloat(record['discount_total']) || 0;
                    totaldeposit += parseFloat(record['deposit_total']) || 0;
                    paymentTotal += parseFloat(record['payment_total']) || 0;
                    const source = (record.check_in && record.check_in.length > 0) ? record.check_in : record.room_booking;
                                        // <td class="text-center w-10" >
                                        //     <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"/><circle cx="10.5" cy="5.5" r="1"/><circle cx="10.5" cy="15.5" r="1"/></g></svg>
                                        //     <div class="dropdown menu_dropdown_check_in" id="dropdown-menu">
                                        //         <div class="dropdown-item click_modal_payment" data-status="${record['status']}" data-id="${record['id']}">Sửa</div>
                                        //         <div class="dropdown-item delete-booked-room" data-room-id="${record['id']}">Xóa </div>
                                        //     </div>
                                        // </td>
                    html += `<tr data-id="${record['id']}" id="invoice-${record.payment_id}" class="table-row">
                                        <td>
                                            <button class="btn btn-link btn-toggle" type="button" onclick="toggleRepresentatives(${record['id']}, this)">
                                            </button>
                                        </td>
                                       <td class="text-right w-10">${idx + 1}</td>
                                       
                                        <td class="text-left w-10">${record['payment_id']}</td>

                                        <td class="text-left">${formatDateTime(record['created_date'])}</td>
                                        <td class="text-right">${formatCurrency(record['room_price'])}</td>
                                         <td class="text-right">${formatCurrency(record['deposit_total'])}</td>
                                          <td class="text-right">${formatCurrency(record['discount_total'])}</td>
                                        <td class="text-right">${formatCurrency(record['payment_total'])}</td>
                                        <td class="text-left">${record['status_badge']}</td>
                                    </tr>`

                    html += `<tr id="rep-${record['id']}" class="child-table-row" style="display:none;">
                            <td colspan="10" style="padding:0px !important">
                            <div>
                        <ul class="nav nav-tabs" role="tablist">
                        
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#other-info-${record.id}" role="tab">Thông tin</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#payment-history-${record.id}" role="tab">Lịch sử thanh toán</a>
                            </li>
                        </ul>
                        <div class="tab-content" style="padding: 10px; background: #f9f9f9;">
                            <!-- Tab 1 -->
                            <div class="tab-pane fade show active" id="payment-history-${record.id}" role="tabpanel">
                                <table class="table--light style--two table table-bordered-thanh-toan" style="width:100%; background: #f9f9f9;">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>Mã phiếu</th>
                                            <th>Thời gian</th>
                                            <th>Thu ngân</th>
                                            <th>Phương thức</th>
                                            <th>Tiền thu</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       ${(record.payment_transactions && record.payment_transactions.length > 0) ?
                            record.payment_transactions.map(item => `
                                                <tr class="table-row table-row-payment"
                                                data-invoice="${record['payment_id']}"
                                                data-customer="${record['customer']}"
                                                 data-item="${encodeURIComponent(JSON.stringify(item))}">
                                                    <td class="text-left">${item.payment_code}</td>
                                                    <td>${formatDateTime(item.paid_at)}</td>
                                                    <td>${item.creator['name']}</td>
                                                    <td class="text-left">${item.payment_method}</td>
                                                    <td class="text-right">${formatCurrency(item.amount)}</td>
                                                    <td>${item.status_badge}</td>
                                                </tr>
                                            `).join('')
                            : `
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">Không có dữ liệu thanh toán</td>
                                                </tr> `
                        }

                                    </tbody>
                                </table>
                            </div>
                            <!-- Tab 2 -->
                            <div class="tab-pane fade" id="other-info-${record.id}" role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-md-6 text-start d-flex gap-2">
                                        <div>
                                            <form action="" method="POST" id="btn-history-payment-${record.payment_id}">
                                                <strong>Mã hóa đơn:</strong> ${record['payment_id']} <br>
                                                <strong>Mã đặt phòng:</strong> ${record['checkin_id'] || record['booking_id']}<br>
                                                <strong>Thời gian:</strong> ${formatDateTime(record['created_date'])}<br>
                                                <strong>Khách hàng:</strong> ${record['customer']}<br>
                                                <label for="creator" class="mt-2"><strong>Thu ngân:</strong></label>
                                                <select name="creator">
                                                    ${staff.map(
                            staff => `
                                                                <option value="${staff.id}" ${record.creator == staff.id ? 'selected' : ''}>
                                                                ${staff.name}
                                                                </option>
                                                            `
                        ).join('')}
                                                </select><br>
                                                <strong class="mt-2">Trạng thái:</strong> ${record['status_badge']}<br>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                              
                                 <h4 class="text-left">Danh sách phòng</h4> 
                                <table class="table--light style--two table table-bordered-thanh-toan"  style="width:100%; background: #f9f9f9;">
                                            <thead class="bg-light">
                                                <tr>
                                                <th>Mã đặt phòng</th>
                                                <th>Tên phòng</th>
                                                <th>Giá</th>
                                                <th>Giảm giá</th>
                                                <th>Đặt cọc</th>
                                                </tr>
                                            </thead>
                                    <tbody>
                                        <tr>
                                            ${source.map(item => `
                                                    <tr class="table-row">
                                                        <td class="text-left">${item.booking_id ?? item.check_in_id}</td>
                                                    <td class="text-left">${item.room['room_number']}</td>
                                                    
                                                    <td class="text-left">${formatCurrency(item.total_amount)}</td>
                                                    <td class="text-left">${formatCurrency(item.discount)}</td>
                                                        <td class="text-left">${formatCurrency(item.deposit_amount)}</td>
                                                    </tr>
                                                `).join('')
                        }
                                            
                                        </tr>
                                    </tbody>
                                </table>
                                 <h4 class="text-left mt-2">Danh sách sản phẩm</h4>
                                <table class="table--light style--two table table-bordered-thanh-toan" style="width:100%; background: #f9f9f9;">
                                    <thead class="bg-light">
                                       <tr>
                                            <th>Mã hàng hóa</th>
                                            <th>Tên hàng</th>
                                            <th>Số lượng</th>
                                            <th>Đơn giá</th>
                                            <th>Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      ${(record.service_booking && record.service_booking.length > 0) ?
                            record.service_booking.map(item =>
                                `
                                                    <tr>
                                                        <td class="text-left">${item.service?.code ?? item.product?.sku}</td>
                                                         <td>${item.service?.name ?? item.product?.name}</td>
                                                       <td>${item.quantity}</td>
                                                        <td>${formatCurrency(item.price)}</td>
                                                      <td>${formatCurrency(item.total_payment)}</td>
                                                    
                                                    </tr>
                                                `
                            ).join('')
                            : `
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">Không có dữ liệu!</td>
                                                </tr>
                                            `
                        }
                                       
                                    </tbody>
                                </table>
                                <div class="">
                                    <table class="no-border">
                                        <tr>
                                            <td class="text-right"><strong>Tổng tiền:</strong></td>
                                            <td class="text-right w-10">${formatCurrency(record.room_price)}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><strong>Đặt cọc:</strong></td>
                                            <td class="text-right w-10">${formatCurrency(record.deposit_total)}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><strong>Giảm giá:</strong></td>
                                            <td class="text-right w-10">${formatCurrency(record.discount_total)}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><strong>Khách đã trả:</strong></td>
                                            <td class="text-right w-10">${formatCurrency(record.paid_customer)}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><strong>Khách cần trả:</strong></td>
                                            <td class="text-right w-10">${formatCurrency(record.customer_needs_to_pay)}</td>
                                        </tr>
                                    </table>
                                </div>



                               <div class="mt-2 d-flex justify-content-end">
                                    <button class="btn custom-btn btn-success me-2 save-payment-btn " data-form-id="btn-history-payment-${record.payment_id}">
                                        <i class="fas fa-save"></i> Lưu
                                    </button>
                                        <button class="btn custom-btn btn-secondary me-2"
                                            onclick="printInvoice('${idx}')">
                                                <i class="fas fa-print"></i> In
                                        </button>
                                    <button class="btn custom-btn btn-secondary me-2">
                                        <i class="fas fa-file-export"></i> Xuất file
                                    </button>
                                    <button class="btn custom-btn btn-danger">
                                        <i class="fas fa-times"></i> Hủy bỏ
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                </td>
            </tr>`;


                });
                total = `
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th class="text-right">${formatCurrency(totalPayment)}</th>
                    <th class="text-right">${formatCurrency(totaldeposit)}</th>
                    <th class="text-right">${formatCurrency(totalDiscount)}</th>
                    <th class="text-right">${formatCurrency(paymentTotal)}</th>
                    <th></th>
                `
                $('.data-table').append(html);
                $('.total_payment').html(total);

                var selected_select = $('#select_room_number');
                selected_select.empty();
                // let option = `<option value="">Chọn mã phòng</option>`;
                // response.rooms.forEach(function (item) {
                //     if (item.id == response.option_selected) {
                //         option += `<option value="${item.id}" selected>${item.room_number}</option>`;
                //     } else {
                //         option += `<option value="${item.id}">${item.room_number}</option>`;
                //     }
                // });
                // selected_select.append(option);
                highlightOddRows();
                updatePagination(pagination, 'loadRoomBookings');
                setTimeout(function () {
                    $('#loading-overlay').css('display', 'none');
                }, 1000);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX request failed: " + error);
        }

    });
}
function formatDateTimeVN(date) {
    const d = new Date(date);
    const pad = n => n.toString().padStart(2, '0');
    return `${pad(d.getDate())}/${pad(d.getMonth() + 1)}/${d.getFullYear()} ${pad(d.getHours())}:${pad(d.getMinutes())}`;
}

function printInvoice(idx) {
    const formattedDate = formatDateTimeVN(new Date());
    const record = window.allInvoiceData[idx];
    if (!record) {
        console.error("Không tìm thấy hóa đơn tại vị trí:", idx);
        return;
    }
    const source = (record.check_in && record.check_in.length > 0) ? record.check_in : record.room_booking;
    const content = `
        <div class="mb-2">09:58 11/06/2025</div>
        <div class="text-center mb-2">Fasthotel - Giao dịch - Hóa đơn</div>

        <div class="mb-1">Tên cở sở: <span>${hotel['hotel_name'] || "-"}</span></div>

        <div class="mb-2">Điện thoại: <span>${hotel['phone'] || "-"}</span></div>

        
        <div class="mb-2">Ngày xuất HD: <span>${formattedDate}</span></div>

        <div class="text-center mb-2">
            <strong>HÓA ĐƠN BÁN HÀNG</strong><br>
            <strong>${record['payment_id']}</strong>
        </div>

        <div class="mb-1">Khách hàng: <span>${record['customer'] || "-"} </span></div>
        <div class="mb-1">Mã đặt phòng: <span>${record['booking_id'] || "-"}</span></div>
        <div class="mb-2">Thu ngân: <span>${record['creator_name'] || "-"}</span></div>
  
      ${record['service_booking'] && record['service_booking'].length > 0 ?
            `
   <h4>Danh sách sản phẩm</h4>
   <table class="mb-2 table-print">
       <thead>
           <tr>
              <th class="price-cell">Mã hàng hóa</th>
               <th class="price-cell">Tên sản phẩm</th>
               <th class="price-cell">Đơn giá</th>
               <th class="price-cell"> SL</th>
               <th class="price-cell">Thành tiền</th>
           </tr>
       </thead>
       <tbody>
           ${record['service_booking'].map(item => `
              <tr>
                  <td class="text-left price-cell">${item.service?.code ?? item.product?.sku}</td>
                    <td class="price-cell">${item.service?.name ?? item.product?.name}</td>
                <td class="price-cell">${item.quantity}</td>
                <td class="price-cell">${formatCurrency(item.price)}</td>
                <td  class="price-cell">${formatCurrency(item.total_payment)}</td>
              </tr>
           `).join('')}
       </tbody>
   </table>
   ` : ""
        }

  
        <h4>Danh sách phòng</h4>
        <table class="mb-2 table-print">
            <thead>
            <tr>
                <th class="price-cell">Mã đặt phòng</th>
                <th class="price-cell">Tên phòng</th>
                <th class="price-cell">Đơn giá</th>
               <th class="price-cell">Giảm giá</th>
                <th class="price-cell">Đặt cọc</th>
            </tr>
            </thead>
            <tbody>
           ${source.map(item => `
                <tr class="table-row">
                    <td class="text-left price-cell">${item.booking_id ?? item.check_in_id}</td>
                    <td class="text-left price-cell">${item.room['room_number']}</td>
                    <td class="text-left price-cell ">${formatCurrency(item.total_amount)}</td>
                    <td class="text-left price-cell">${formatCurrency(item.discount)}</td>
                    <td class="text-left price-cell">${formatCurrency(item.deposit_amount)}</td>
                </tr>
            `).join('')
        }
            </tbody>
        </table>

        <table class="no-border">
        
            <tr>
                <td class="text-right">Tổng tiền:</td>
                <td class="text-right">${formatCurrency(record['room_price'])}</td>
            </tr>
               <tr>
                <td class="text-right">Đặt cọc:</td>
                <td class="text-right">${formatCurrency(record['deposit_total'])}</td>
            </tr>
            <tr>
                <td class="text-right">Giảm giá:</td>
                <td class="text-right">${formatCurrency(record['discount_total'])}</td>
            </tr>
             <tr>
                <td class="text-right">Khách đã tả:</td>
                <td class="text-right">${formatCurrency(record['paid_customer'])}</td>
            </tr>
              <tr>
                <td class="text-right">Khách cần trả:</td>
                <td class="text-right">${formatCurrency(record['customer_needs_to_pay'])}</td>
            </tr>
        </table>

        <div class="text-center mt-3">
            <em>Cảm ơn và hẹn gặp lại<br>Powered by Fasthotel</em>
        </div>
    `;

    // Gắn vào vùng in
    const printContainer = document.getElementById("print-container");
    printContainer.innerHTML = content;
    printContainer.style.display = "block";

    // In vùng này
    const originalContent = document.body.innerHTML;
    document.body.innerHTML = printContainer.innerHTML;
    window.print();
    document.body.innerHTML = originalContent;
}


function toggleRepresentatives(id, button) {
    const row = document.getElementById('rep-' + id);
    if (row) {
        row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
        // button.textContent = row.style.display === 'none' ? '+' : '-';
    }
}


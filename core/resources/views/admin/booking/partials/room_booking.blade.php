<div class="modal fade" id="addRoomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chọn Phòng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

        </div>
        <div class="modal-body overflow-add-room">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th data-table="Hạng phòng">Hạng phòng</th>
                        <th data-table="Phòng">Phòng</th>
                        <th data-table="Giá">Giá</th>
                        <th data-table="Thao tác">Thao tác</th>

                    </tr>
                </thead>

                <tbody id="show-room">

                </tbody>

            </table>
        </div>

    </div>
    <div class="modal-body overflow-add-room">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th data-table="Hạng phòng">Hạng phòng</th>
                    <th data-table="Phòng">Phòng</th>
                    <th data-table="Giá">Giá</th>
                    <th data-table="Thao tác">Thao tác</th>

                </tr>
            </thead>

            <tbody id="show-room">

            </tbody>

        </table>
    </div>

</div>



<div class="modal fade" id="myModal-booking" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-booking"
    aria-hidden="true">
    {{-- padding: 115px 10px 0px; --}}
    <div class="modal-dialog modal-dialog-centered" role="document" style="width: 100%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel-booking">Đặt phòng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body " style="padding: 5px 5px 12px 5px;">


                <form id="bookingForm" action="{{ route('admin.room.book') }}" class="booking-form" method="POST">
                    @csrf

                    <div class="row">
                        <h5 class="modal-title" id="myModalLabel-booking">Thông tin khách hàng</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex flex-column" style="line-height: 0px">
                                    {{-- <div class="mb-3 mt-2">
                                        <label for="email" class="form-label required">Email</label>
                                       <select class="form-select" name="" id="">
                                        <option value="">21</option>
                                        <option value="">2</option>
                                       </select>
                                    </div> --}}
                                    <div class="customer-input-container">
                                        <div class="col-md-1" style="margin-left: 10px">
                                            {{-- <p class="btn btn--primary "
                                                style="white-space: nowrap; font-size: 13px" id="btn-search">Tìm
                                                kiếm</p> --}}
                                            <p class="btn btn--primary modal--search-customer"
                                                style="white-space: nowrap; font-size: 13px;" id="btn-search">Tìm
                                                khách</p>
                                        </div>
                                        <label for="phone" class="form-label required mt-3">Tên khách hàng</label>
                                        <div class="d-flex">
                                            <div class="col-md-8">
                                                <input type="text" name="name" id="name" class="form-control"
                                                    placeholder="Tên khách hàng">
                                                <span class="invalid-feedback d-block"
                                                    style="font-weight: 500"id="name_error"></span>
                                            </div>


                                        </div>

                                        {{-- <div class="d-flex customer-svg-icon" style="gap: 5px">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="camera-svg-icon-add" width="20"
                                            height="20" viewBox="0 0 1024 1024">
                                            <path fill="currentColor"
                                                d="M928 224H780.816L704 96H320l-76.8 128H96c-32 0-96 32-96 95.008V832c0 53.008 48 96 89.328 96H930c42 0 94-44.992 94-94.992V320c0-32-32-96-96-96zm32 609.008c0 12.624-20.463 30.288-29.999 31.008H89.521c-7.408-.609-25.52-15.04-25.52-32.016V319.008c0-20.272 27.232-30.496 32-31.008h183.44l76.8-128h313.647l57.12 96.945l17.6 31.055H928c22.56 0 31.68 29.472 32 32v513.008zM512.001 320c-123.712 0-224 100.288-224 224s100.288 224 224 224s224-100.288 224-224s-100.288-224-224-224zm0 384c-88.224 0-160-71.776-160-160s71.776-160 160-160s160 71.776 160 160s-71.776 160-160 160z" />
                                        </svg>

                                        <input type="file" class="file-upload-input" id="fileUpload">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="customer-svg-icon-add" width="20"
                                            height="20" viewBox="0 0 24 24">
                                            <g fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" d="M12 8v4m0 0v4m0-4h4m-4 0H8" />
                                                <circle cx="12" cy="12" r="10" />
                                            </g>
                                        </svg>
                                        </div> --}}
                                    </div>
                                    <div class="col-md-8 mt-3">
                                        <label for="phone" class="form-label">Số điện thoại</label>
                                        <input type="text" id="phone" name="phone" class="form-control"
                                            placeholder="Số điện thoại">
                                        {{-- <select id="selectphone" name="phone" class="form-control select2" style="width: 100%;">
                                                <option value="">Chọn số điện thoại</option>

                                                <!-- Thêm các số điện thoại khác ở đây -->
                                            </select> --}}
                                    </div>
                                    <div class="col-md-8  mt-3">
                                        <input type="hidden" name="customer_code" id="customer_code">
                                        <label for="phone" class="form-label">Nguồn khách</label>
                                        {{-- <input type="text" id="phone" name="phone" class="form-control"
                                            placeholder="Số điện thoại"> --}}
                                            <select id="select-customer-source" name="customer_source" class="form-control " style="width: 100%;">
                                            </select>
                                    </div>
                                    <div class="col-md-8 mb-3 mt-3 d-flex align-items-center result-add-customer" style="gap:10px">
                                        <input type="checkbox" name="insert_customer"> <p style="font-size: 13px">Lưu thông tin khách</p>
                                    </div>
                                    <div class="">
                                        <p class="add-room-booking" style="width: 185px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24">
                                                <g fill="currentColor" fill-rule="evenodd" clip-rule="evenodd">
                                                    <path
                                                        d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12Zm10-8a8 8 0 1 0 0 16a8 8 0 0 0 0-16Z" />
                                                    <path
                                                        d="M13 7a1 1 0 1 0-2 0v4H7a1 1 0 1 0 0 2h4v4a1 1 0 1 0 2 0v-4h4a1 1 0 1 0 0-2h-4V7Z" />
                                                </g>
                                            </svg>
                                            Chọn thêm phòng
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex flex-column" style="line-height: 0px;">

                                    <div class="customer-input-container">
                                        <label for="phone" class="form-label required">Ngày đặt</label>
                                        <div class="d-flex">
                                            <div class="col-md-8">
                                                <div class="d-flex align-items-center justify-content-start"
                                                    style="gap: 10px">
                                                    <input type="date" name="checkInDate"
                                                        id="date-book-room-booking" class="form-control ">
                                                    <input type="time" name="checkInTime"
                                                        id="time-book-room-booking" class="form-control ">
                                                </div>
                                                {{-- <span class="invalid-feedback d-block"
                                                    style="font-weight: 500"id="name_error"></span> --}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8 mt-3" style="display: none">
                                        <label for="phone" class="form-label required">Ngày trả</label>
                                        <div class="d-flex align-items-center justify-content-start"
                                            style="gap: 10px">
                                            <input type="date" name="checkOutDate" id="date-book-room-date"
                                                class="form-control">
                                            <input type="time" name="checkOutTime" id="time-book-room-date"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-8 mt-3">
                                        <label for="phone" class="form-label ">Nhân viên </label>
                                        <div class="d-flex align-items-center justify-content-start"
                                            style="gap: 10px">
                                            <select id="select-staff"name="name_staff"class="form-control " style="width: 100%;">
                                             
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <datalist id="customer-names">
                            @forelse ($userList as $user)
                                <option value="{{ $user->customer_code }}">
                                @empty
                                    <p>No items found.</p>
                            @endforelse
                        </datalist> --}}
                        {{-- <p id="error-message" style="color: red; display: none;">Không tìm thấy email khách hàng phù
                            hợp
                        </p> --}}
                    </div>
                    <div class="row">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="modal-title" id="myModalLabel-booking">Danh sách phòng</h5>
                            <p class="delete-room-booking" style="width: 126px;height: 30px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 20 20">
                                    <path fill="currentColor"
                                        d="m9.129 0l1.974.005c.778.094 1.46.46 2.022 1.078c.459.504.7 1.09.714 1.728h5.475a.69.69 0 0 1 .686.693a.689.689 0 0 1-.686.692l-1.836-.001v11.627c0 2.543-.949 4.178-3.041 4.178H5.419c-2.092 0-3.026-1.626-3.026-4.178V4.195H.686A.689.689 0 0 1 0 3.505c0-.383.307-.692.686-.692h5.47c.014-.514.205-1.035.554-1.55C7.23.495 8.042.074 9.129 0Zm6.977 4.195H3.764v11.627c0 1.888.52 2.794 1.655 2.794h9.018c1.139 0 1.67-.914 1.67-2.794l-.001-11.627ZM6.716 6.34c.378 0 .685.31.685.692v8.05a.689.689 0 0 1-.686.692a.689.689 0 0 1-.685-.692v-8.05c0-.382.307-.692.685-.692Zm2.726 0c.38 0 .686.31.686.692v8.05a.689.689 0 0 1-.686.692a.689.689 0 0 1-.685-.692v-8.05c0-.382.307-.692.685-.692Zm2.728 0c.378 0 .685.31.685.692v8.05a.689.689 0 0 1-.685.692a.689.689 0 0 1-.686-.692v-8.05a.69.69 0 0 1 .686-.692ZM9.176 1.382c-.642.045-1.065.264-1.334.662c-.198.291-.297.543-.313.768l4.938-.001c-.014-.291-.129-.547-.352-.792c-.346-.38-.73-.586-1.093-.635l-1.846-.002Z" />
                                </svg>
                                Xóa phòng
                            </p>
                        </div>
                        <!-- Row: Labels -->
                        <div class="table-responsive mt-2">
                            <table class="table mobi-table" id="data-table">
                                <thead>
                                    <tr class="text-center fw-bold main-booking-modal">
                                        {{-- <th>Hạng phòng</th> --}}
                                        <th></th>
                                        <th>Phòng</th>
                                        <th>Số lượng khách</th>
                                        <th>Hình thức</th>
                                        <th class="d-flex gap-10">Ngày nhận phòng
                                            {{-- <span class="main-hour-out"
                                                id="hour_current">Hiện tại</span> --}}
                                        </th>
                                        <th>Ngày trả phòng</th>
                                        <th>Tiền phòng</th>
                                        <th>Tiền cọc</th>
                                        <th>Giảm giá</th>
                                        <th>Ghi chú</th>
                                        {{-- <th class="d-flex justify-content-between align-items-center">Dự kiến
                                        <span>Thành tiền</span>
                                    </th> --}}
                                    </tr>
                                </thead>
                                {{-- <input type="text" class="room_type_id" name="room_type_id"hidden> --}}
                                {{-- <input type="text" class="room_type" name="room_type"hidden>
                                <input type="text" class="username-user1" name="guest_name" hidden>
                                <input type="text" class="email-user1" name="email" hidden>
                                <input type="text" class="mobile-user" name="mobile" hidden>
                                <input type="text" class="address-user" name="address" hidden>
                                <input type="text" class="guest_type" name="guest_type" hidden> --}}
                                <tbody id="list-booking">

                                </tbody>

                            </table>
                        </div>
                        <div class="alert-danger message-error" role="alert">

                        </div>



                    </div>
                    <hr>
                    <div class="flex-column justify-content-end" style="gap: 10px;">
                        <div class=" d-flex justify-content-end mb-2">
                            <ul class="financial-list">
                                <li class="financial-item">
                                    <span>Tiền phòng</span>
                                    <span id="total_amount">0</span>
                                </li>
                                <li class="financial-item highlighted">
                                    <span>Giảm giá</span>
                                    <span id="total_discount">0</span>
                                    {{-- <input type="text" id="discountInput" class="custom-input-giam-gia"> --}}
                                </li>
                                <li class="financial-item">
                                    <span>Tiền cọc</span>
                                    <span id="total_deposit">0</span>
                                </li>
                                <li class="financial-item">
                                    <span>Còn lại</span>
                                    <span id="total_balance">0</span>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end" style="gap: 10px;">
                        <button type="button" data-row="booked" class=" btn-dat-truoc btn-book">Lưu</button>
                        <p type="button" data-row="booked" class="alert-paragraph close_modal">Hủy</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style scoped>
    .table td {
        padding: 15px 5px !important;
    }
</style>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<link rel="stylesheet" href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
<script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
<script>
   
    document.getElementById("date-book-room-booking").addEventListener("change", function() {
        let checkInDate = new Date(this.value);
        if (!isNaN(checkInDate.getTime())) {
            checkInDate.setDate(checkInDate.getDate() + 1); // Thêm 1 ngày
            let checkOutDate = checkInDate.toISOString().split('T')[0]; // Định dạng YYYY-MM-DD
            document.getElementById("date-book-room-date").value = checkOutDate;
        }
    });
    document.addEventListener("DOMContentLoaded", function () {
    function updateScroll() {
        const listBooking = document.getElementById("list-booking");
        const rows = listBooking.querySelectorAll("tr");

        if (rows.length > 3) {
            listBooking.style.display = "block";
            listBooking.style.height = "150px"; // Giới hạn chiều cao
            listBooking.style.overflowY = "auto"; // Hiển thị thanh cuộn
        } else {
            listBooking.style.height = "none";
            listBooking.style.overflowY = "visible"; // Không có thanh cuộn nếu ít hơn hoặc bằng 3 hàng
        }
    }

    updateScroll();
});

</script>

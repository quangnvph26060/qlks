{{-- <div class="modal fade" id="addRoomModal" tabindex="-1" aria-hidden="true">
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

</div> --}}



<div class="modal fade" id="myModal-booking" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-booking"
    aria-hidden="true" style="padding-bottom: 0px">
    {{-- padding: 115px 10px 0px; --}}
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document" style=" width: 100%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title title-check-in" id="myModalLabel-booking">Đặt phòng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"
                style="padding: 5px 5px 12px 15px; overflow-x: auto; scrollbar-width: none; -ms-overflow-style: none; margin-bottom: 20px;">



                <form id="bookingForm" action="{{ route('admin.room.book') }}" class="booking-form" method="POST">
                    @csrf


                    {{-- <h5 class="modal-title" id="myModalLabel-booking">Thông tin khách hàng</h5> --}}
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="d-flex flex-column custom-gap-lg">
                                {{-- <div class="mb-3 mt-2">
                                        <label for="email" class="form-label required">Email</label>
                                       <select class="form-select" name="" id="">
                                        <option value="">21</option>
                                        <option value="">2</option>
                                       </select>
                                    </div> --}}
                                <div class="customer-input-container" style="height: 77px">
                                    <div class="col-md-1 mb-1" style="margin-left: 2px">
                                        <p class="btn btn--primary modal--search-customer"
                                            style="white-space: nowrap; font-size: 13px;height: 35px" id="btn-search">
                                            Tìm
                                            khách</p>
                                    </div>
                                    <div class="">
                                        {{-- <label for="name" class="form-label required">Tên khách hàng</label> --}}
                                        <div class="col-md-8">
                                            <input type="text" name="name" id="name" class="form-control"
                                                placeholder="Tên khách hàng" style="height: 35px">
                                            <span class=" invalid-feedback d-block"
                                                style="font-weight: 500"id="name_error"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8" style="height: 39px">
                                    {{-- <label for="phone" class="form-label required ">Số điện thoại</label> --}}
                                    <input type="text" id="phone" name="phone" class="form-control"
                                        style="height: 35px" placeholder="Số điện thoại">
                                    <span class=" invalid-feedback d-block"
                                        style="font-weight: 500"id="phone_error"></span>
                                </div>
                                {{-- <select id="selectphone" name="phone" class="form-control select2" style="width: 100%;">
                                                <option value="">Chọn số điện thoại</option>

                                                <!-- Thêm các số điện thoại khác ở đây -->
                                            </select> --}}




                                <div class="col-md-8 d-flex align-items-center result-add-customer" style="gap:10px">
                                    <input type="checkbox" name="insert_customer" style="width: 15px !important;">
                                    <p style="font-size: 13px">Lưu thông tin khách</p>
                                </div>

                                <p class="add-room-booking" style="width: 185px; height: 35px;">
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
                        <div class="col-12 col-md-6">
                            <div class="d-flex flex-column custom-gap-lg">

                                <div class="customer-input-container">
                                    <label for="phone" class="form-label" style="font-weight: 400">Ngày đặt</label>
                                    <div class="d-flex">
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center justify-content-start"
                                                style="gap: 10px; height: 35px;">
                                                <input type="date" name="checkInDate" id="date-book-room-booking"
                                                    class="form-control " style="height: 35px;">
                                                <input type="time" name="checkInTime" id="time-book-room-booking"
                                                    class="form-control " style="height: 35px;">
                                            </div>
                                            {{-- <span class="invalid-feedback d-block"
                                                    style="font-weight: 500"id="name_error"></span> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8 mt-3" style="display: none">
                                    <label for="phone" class="form-label required">Ngày trả</label>
                                    <div class="d-flex align-items-center justify-content-start" style="gap: 10px">
                                        <input type="date" name="checkOutDate" id="date-book-room-date"
                                            class="form-control">
                                        <input type="time" name="checkOutTime" id="time-book-room-date"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-8 mt-1">
                                    <input type="hidden" name="customer_code" id="customer_code">
                                    {{-- <label for="customer_source" class="form-label">Nguồn khách</label> --}}
                                    {{-- <input type="text" id="phone" name="phone" class="form-control"
                                                placeholder="Số điện thoại"> --}}
                                    <select id="select-customer-source" name="customer_source" class="form-control "
                                        style="width: 100%;height: 35px;">
                                    </select>
                                </div>
                                <div class="col-md-8 mt-1">
                                    {{-- <label for="phone" class="form-label ">Nhân viên </label> --}}
                                    <div class="d-flex align-items-center justify-content-start" style="gap: 10px">
                                        <select id="select-staff"name="name_staff" class="form-control "
                                            style="width: 100%; height: 35px;">

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

                    <div class="row">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="modal-title" id="myModalLabel-booking">Danh sách phòng</h5>
                            <p class="delete-room-booking d-flex align-items-center"
                                style="width: 126px;height: 35px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 20 20">
                                    <path fill="currentColor"
                                        d="m9.129 0l1.974.005c.778.094 1.46.46 2.022 1.078c.459.504.7 1.09.714 1.728h5.475a.69.69 0 0 1 .686.693a.689.689 0 0 1-.686.692l-1.836-.001v11.627c0 2.543-.949 4.178-3.041 4.178H5.419c-2.092 0-3.026-1.626-3.026-4.178V4.195H.686A.689.689 0 0 1 0 3.505c0-.383.307-.692.686-.692h5.47c.014-.514.205-1.035.554-1.55C7.23.495 8.042.074 9.129 0Zm6.977 4.195H3.764v11.627c0 1.888.52 2.794 1.655 2.794h9.018c1.139 0 1.67-.914 1.67-2.794l-.001-11.627ZM6.716 6.34c.378 0 .685.31.685.692v8.05a.689.689 0 0 1-.686.692a.689.689 0 0 1-.685-.692v-8.05c0-.382.307-.692.685-.692Zm2.726 0c.38 0 .686.31.686.692v8.05a.689.689 0 0 1-.686.692a.689.689 0 0 1-.685-.692v-8.05c0-.382.307-.692.685-.692Zm2.728 0c.378 0 .685.31.685.692v8.05a.689.689 0 0 1-.685.692a.689.689 0 0 1-.686-.692v-8.05a.69.69 0 0 1 .686-.692ZM9.176 1.382c-.642.045-1.065.264-1.334.662c-.198.291-.297.543-.313.768l4.938-.001c-.014-.291-.129-.547-.352-.792c-.346-.38-.73-.586-1.093-.635l-1.846-.002Z" />
                                </svg>
                                Xóa phòng
                            </p>
                        </div>
                        <!-- Row: Labels -->
                        <div class="table-scroll-wrapper ">
                            <div class="table-responsive mt-1 table-responsive--md">
                                <table class="table table-bordered text-center table--light width-mobi-table"
                                    id="data-table">
                                    <thead class=" position-sticky top-0">
                                        <tr class="fw-bold main-booking-modal">
                                            <th class="w-10"></th>
                                            <th>Phòng</th>
                                            <th>SL</th>
                                            <th>Hình thức</th>
                                            <th>Ngày nhận phòng</th>
                                            <th>Ngày trả phòng</th>
                                            <th>Tiền phòng</th>
                                            <th>Tiền cọc</th>
                                            <th>Giảm giá</th>
                                            <th>Ghi chú</th>
                                        </tr>
                                    </thead>

                                    <tbody id="list-booking" class="list-booking-scroll">
                                        <!-- Nội dung động -->
                                    </tbody>

                                    <tfoot class="table-group-divider">
                                        <tr>
                                            <td class="d-none-mobi" colspan="6">Tổng tiền: </td>
                                            <td data-label="Tiền phòng" class="text-primary fw-bold text-end"><span
                                                    class="total_amount">0</span></td>
                                            <td data-label="Tiền giảm giá" class="text-success fw-bold text-end"><span
                                                    class="total_deposit">0</span></td>
                                            <td data-label="Tiền đặt cọc" class="text-info fw-bold text-end"><span
                                                    class="total_discount">0</span></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>


                        </div>

                        <div class="alert-danger message-error" role="alert">

                        </div>



                    </div>
                    <div class="flex-column justify-content-end" style="gap: 10px;">

                        <div class=" d-flex justify-content-between mb-2">
                            <div class="col-md-3">
                                @php
                                    $routeName = Route::currentRouteName();
                                @endphp
                                @if ($routeName === 'admin.receptionist.booking.receptionist')
                                    <select id="select-option-pttt" name="payment_pttt" class="form-control "
                                        style="width: 100%;">
                                        <option value="">Chọn phương thức thanh toán</option>
                                        <option value="Tiền mặt">Tiền mặt</option>
                                        <option value="Chuyển khoản ngân hàng">Chuyển khoản ngân hàng</option>
                                        <option value="Thẻ tín dụng">Thẻ tín dụng</option>
                                    </select>
                                    <span class="invalid-feedback d-block"
                                        style="font-weight: 500"id="select-option-pttt_error"></span>
                                @endif
                            </div>
                            <ul class="financial-list">
                                {{-- <li class="financial-item">
                                    <span>Tiền phòng</span>
                                    <span class="total_amount">0</span>
                                </li>
                                <li class="financial-item highlighted">
                                    <span>Giảm giá</span>
                                    <span class="total_discount">0</span>
                                    <input type="text" id="discountInput" class="custom-input-giam-gia">
                                </li> --}}
                                {{-- <li class="financial-item">
                                    <span>Tiền cọc</span>
                                    <span class="total_deposit">0</span>
                                </li> --}}
                                <li class="financial-item">
                                    <span>Còn lại</span>
                                    <span class="total_balance">0</span>
                                </li>

                            </ul>
                        </div>

                    </div>
                    <div class="d-flex justify-content-end" style="gap: 10px;">
                        <button type="button" data-row="booked" class="btn-dat-truoc btn-book">Lưu</button>
                        <p type="button" data-row="booked" class="alert-paragraph close_modal">Hủy</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
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
    document.addEventListener("DOMContentLoaded", function() {
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
<style scoped>
    @media (max-width:768px) {
        .modal {
            padding-bottom: 75px !important;
        }
    }
</style>

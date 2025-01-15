<div class="card mb-3">
    <div class="card-body">

        <div class="row mb-3 justify-content-between">
            <div class="col-md-9">
                <div class="d-flex" style="flex-direction: column; gap:20px">
                   
                    <div class="modal fade" id="addRoomModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Chọn Phòng</h5>
                                    <button type="button" class="btn-close"
                                        data-bs-dismiss="modal" aria-label="Close"></button>
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
                        </div>
                    </div>
                    {{-- <div class="d-flex justify-content-flex-start align-items-end mt-2"
                    style="gap: 10px">
                    <label>Ghi chú</label>
                    <input name="ghichu" class="input-ghichu"
                        placeholder="Nhập ghi chú..."></input>
                </div> --}}
                </div>
            </div>
         

        </div>
    </div>
</div>
<div class="modal fade" id="myModal-booking" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-booking"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document" style="width: 100%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel-booking">Đặt phòng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row ">


                <form  id="bookingForm" action="{{ route('admin.room.book') }}" class="booking-form row"
                    method="POST"> 
                    @csrf
                  <div class="col-md-8">
                    <h5 class="modal-title" id="myModalLabel-booking">Danh sách phòng</h5>
                    <!-- Row: Labels -->
                    <div class="table-responsive mt-2">
                        <table class="table mobi-table">
                            <thead>
                                <tr class="text-center fw-bold main-booking-modal">
                                    {{-- <th>Hạng phòng</th> --}}
                                    <th>Phòng</th>
                                    <th>Số lượng</th>
                                    <th>Hình thức</th>
                                    <th class="d-flex gap-10">Nhận <span class="main-hour-out" id="hour_current">Hiện
                                            tại</span> <span class="main-hour-out" id="hour_regulatory">Quy
                                            định</span></th>
                                    <th>Trả phòng</th>
                                    <th>Đặt cọc</th>
                                    <th></th>
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
                    <hr>
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

                  
                   

                    <div class="col-md-4">
                     
    
                  
                        <h5 class="modal-title" id="myModalLabel-booking">Thông tin khách hàng</h5>
                        <div class="d-flex flex-column">
                            <div class="customer-input-container mt-2">
                                <label for="phone" class="form-label required">Tên khách hàng</label>
                               <div class="d-flex" style="gap: 10px">
                                <input id="customer-name" type="text" name="name" class="form-control" placeholder="Tên khách hàng">
                                <p class="btn btn--primary " style="white-space: nowrap; font-size: 13px" id="btn-search">Tìm kiếm</p>
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
                            <div class="mb-3 mt-2">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="text" id="phone" name="phone" class="form-control" placeholder="Số điện thoại">
                            </div>
                            {{-- <div class="mb-3">
                                <label for="address" class="form-label required">Địa chỉ</label>
                                <input type="text" id="address" class="form-control" placeholder="Địa chỉ">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label required">Email</label>
                                <input type="text" id="email" class="form-control" placeholder="Email">
                            </div>
                          
                            <div class="md-3 d-flex flex-column mt-1 mb-3">
                                <label for="note" class="form-label required">Ghi chú</label>
    
                                <input type="text" name="ghichu" id="note" class="form-control"
                                    placeholder="Nhập ghi chú...">
                            </div> --}}
                            <div class="d-flex mt-1 md-2">
                                {{-- <div class="col-md-6 button-reduce">
                                    <span class="adult__main">Số người</span>
                                    <span class="icon reduce" data-target="adults">-</span>
                                    <input type="number" class="input-field" id="adults" value="1" readonly>
                                    <span class="icon increase" data-target="adults">+</span>
                                </div> --}}
                                {{-- <div class=" col-md-6 button-reduce">
                                    <span class="adult__main">Trẻ em</span>
                                    <span class="icon reduce" data-target="children">-</span>
                                    <input type="number" class="input-field" id="children" value="0" readonly>
                                    <span class="icon increase" data-target="children">+</span>
                                </div> --}}
                            </div>
                            {{-- <div class="mb-3 mt-1">
                                <label for="total_payment" class="form-label required">Nhập tiền thanh toán</label>
                                <input type="text" id="text" class="form-control" placeholder="Tổng cộng">
                            </div> --}}
                            <div class="mb-3 text-end datphong">
                                {{-- <button type="button" data-row="checkin" class=" btn-primary-2 btn-confirm">Nhận
                                phòng</button> --}}
                                <button type="button" data-row="booked" class=" btn-dat-truoc btn-book">Đặt ngay</button>
                            </div>
                           
    
                            {{-- <div>
                                <select name="" id="model">
                                    <option value="">Chọn mô hình</option>
                                    <option value="1">Khách sạn</option>
                                    <option value="2">Khu nghỉ dưỡng</option>
                                </select>
                            </div> --}}
                        </div>
                        <datalist id="customer-names">
                            @forelse ($userList as $user)
                                <option value="{{ $user->customer_code }}">
                                @empty
                                    <p>No items found.</p>
                            @endforelse
                        </datalist>
                        <p id="error-message" style="color: red; display: none;">Không tìm thấy email khách hàng phù
                            hợp
                        </p>
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

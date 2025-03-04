<div class="modal fade" id="changeRoomModal" tabindex="-1" aria-hidden="true" style="overflow: unset">
    <div class="modal-dialog modal-dialog-centered" style="top: 4px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Đổi phòng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form  id="btn-change-booking-room" action="{{ route('admin.booking.changeRoomBooking') }}" method="POST" class="row">
                @csrf
                <div class="col-3 d-flex justify-content-center align-items-center">
                    <div class="room-box">
                        <div class="room-name d-flex">Phòng: <span class="room-number"> </span></div>
                        <div class="booking-date d-flex">Ngày đặt: <span class="date"> </span></div>
                        <input type="hidden" name="room_old" id="room_old">
                        <input type="hidden" name="booking_id" id="booking_id">
                        <input type="hidden" name="id" id="id">
                    </div>
                </div>
                <div class="col-1 d-flex d-flex justify-content-center align-items-center" style="padding: 10px">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 14 14">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            d="M4 10.5L.5 7L4 3.5m6 7L13.5 7L10 3.5"></path>
                    </svg>
                </div>
                <div class="col-8 overflow-change-room">
                    <div class="modal-body " style="padding: 0px">
                        <table class=" table--light style--two table ">
                            <thead>
                                <tr>
                                    <th data-table="Hạng phòng" class="text-left">Hạng phòng</th>
                                    <th data-table="Phòng" class="text-left">Tên phòng</th>
                                    <th data-table="Ngày" class="text-left">Ngày</th>
                                    <th data-table="Trạng thái phòng" class="text-left">Trạng thái phòng</th>
                                    <th data-table="Giá" class="text-right">Giá</th>
                                    <th data-table="Thao tác">Thao tác</th>
                                </tr>
                            </thead>

                            <tbody id="show-room-change" >

                            </tbody>

                        </table>
                    </div>
                </div>
         
                <div class="d-flex justify-content-end" style="gap: 10px;padding: 8px 29px">
                    <p data-row="booked" class=" btn-dat-truoc  change-booking-room" style="cursor: pointer">Lưu
                    </p>
                    <p type="button" data-row="booked" class="alert-paragraph close_modal_booked_room">Hủy</p>
                </div> 
            </form>
        </div>
    </div>
</div>
<style scoped>
    .room-box {
               width: 250px;
               padding: 30px 30px 30px 10px;
               border: 2px solid #007bff;
               border-radius: 10px;
               text-align: center;
               background-color: #f8f9fa;
               box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
               transition: transform 0.3s ease-in-out;
           }
   
           .room-box:hover {
               transform: scale(1.05);
           }
   
           .room-name,.room-number {
               font-size: 20px;
               font-weight: bold;
               color: #007bff;
               margin-bottom: 5px;
           }
   
           .booking-date {
               font-size: 16px;
               color: #555;
           }
   </style>

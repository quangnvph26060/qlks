<div class="modal fade" id="modalCheckIn" tabindex="-1" aria-labelledby="modalCheckInLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="width: 1050px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCheckInLabel">Phòng nhận</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="w3-table table-fz-13">
                  <thead>
                    <tr>
                        <th><input type="checkbox" id="all-check-box"></th>
                        <th>Hạng phòng</th>
                        <th>Phòng</th>
                        <th>Trình trạng phòng</th>
                        <th>Nhận 
                            <span class="btn-receive" id="hour_current">Hiện tại</span> 
                            <span class="btn-receive time-booked">Giờ đặt</span>
                        </th>
                    </tr>
                  </thead>
                 <tbody id="list-room-booking">
                    {{-- <tr>
                        <td ><input type="checkbox"></td>
                        <td>Phòng 01 giường đôi và 1 giường đơn cho 3 người</td>
                        <td>P.01</td>
                        <td>Sạch</td>
                        <td><input type="date" name="checkInDate" class="form-control" id="date-book-room" style="width: 165px;"></td>
                    </tr> --}}
                 </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary">Sửa đặt phòng</button>
                <button type="button" class=" nhan-phong modal-check-in-room" data-bs-toggle="modal" data-bs-target="#checkInRoom">Nhận phòng</button>
            </div>
        </div>
    </div>
</div>

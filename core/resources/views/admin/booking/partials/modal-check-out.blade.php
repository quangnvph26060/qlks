<div class="modal fade" id="modalCheckOut" tabindex="-1" aria-labelledby="modalCheckOutLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="width: 1050px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCheckOutLabel">Phòng nhận</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="w3-table table-fz-13">
                  <thead>
                    <tr>
                        <th><input type="checkbox" id="all-check-boxs"></th>
                        <th>Hạng phòng</th>
                        <th>Phòng</th>
                        <th>Nhận</th>
                        <th>
                            Trả 
                            <span class="btn-receive">Hiện tại</span> 
                            <span class="btn-receive time-booked">Giờ đặt</span>
                        </th>
                    </tr>
                  </thead>
                 <tbody id="list-room-booked">
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
            <div class="modal-footer btn-modalCheckOut">
                {{-- <button type="button" class="btn btn-secondary">Sửa đặt phòng</button> --}}
                <button type="button" class=" nhan-phong check-out-room-detail" >Trả phòng</button>
                <button type="button" class=" nhan-phong modal-check-out-room" data-bs-toggle="modal" data-bs-target="#checkOutRoom">Trả phòng và thanh toán</button>
            </div>
        </div>
    </div>
</div>

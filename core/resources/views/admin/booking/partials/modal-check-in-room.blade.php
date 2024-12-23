<div class="modal fade" id="checkInRoom" tabindex="-1" aria-labelledby="checkInRoomLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="checkInRoomLabel">Thông tin nhận phòng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="main-room-booking">

            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sửa đặt phòng</button>
                <button type="button" class="btn-primary-1 btn-check-in">Xong</button>
            </div>
        </div>
    </div>
</div>
<style scoped>
    .custom-input {
        border: none;
        border-bottom: 1px solid #ccc;
        outline: none;
        width: 70px;
        font-weight: bold;
        border-radius: none;
        background-color: transparent;
        box-shadow: none;
    }

    .payment-box span,
    .payment-box input,
    .payment-box img {
        margin-right: 10px;
    }

    .payment-box {
        display: flex;
        background-color: #f9f9f9;
        padding: 10px 15px;
        justify-content: end;
    }



    .modal-content {
        font-size: 13px;
    }


    .modal-content input,
    .modal-content label {
        font-size: 13px;
    }

    .custom-input-note {
        border: none;
        border-bottom: 1px solid #c1c8c3;
        border-radius: none;
        outline: none;
        font-size: 13px;
        padding-left: 0;
        box-shadow: none;
        border-radius: 0;
    }

    .custom-input-note::placeholder {
        color: #a9a9a9;
        font-style: italic;
    }
</style>

<div id="customer-popup" class="customer-popup">
    <div class="modal-header">
        <h3>Thông tin khách hàng</h3>
        <svg class="close-customer" id="customer-close" xmlns="http://www.w3.org/2000/svg"
            width="20" height="20" viewBox="0 0 24 24">
            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"
                d="M20 20L4 4m16 0L4 20" />
        </svg>
    </div>
    <form>
        {{-- <label for="name">Loại khách hàng</label>
        <select class="form-control" name="guest_type" id="guest_type">
            <option selected value="0">@lang('Khách lưu trú')</option>
            <option value="1">@lang('Khách đã đăng ký')</option>
        </select> --}}

        <label for="name">Tên khách hàng</label>
        <input type="text" id="name" class="customer-input"
            placeholder="Nhập tên của bạn">

        <label for="email">Email</label>
        <input type="email" id="email" class="customer-input"
            placeholder="Nhập email của bạn">

        <label for="phone">Số điện thoại</label>
        <input type="text" id="phone" class="customer-input"
            placeholder="Nhập số điện thoại">

        <label for="address">Địa chỉ</label>
        <input type="text" id="address" class="customer-input"
            placeholder="Nhập địa chỉ của bạn">

        <div class="btn-customer">
            <button type="button" id="customer-closePopup">Bỏ qua</button>
            <button type="button" class="btn btn-primary btn-user-info">Lưu</button>
        </div>
    </form>
</div>
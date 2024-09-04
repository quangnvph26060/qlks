<div class="card">
    <div class="card-body">
        <h5 class="card-title">@lang('Tóm tắt thanh toán')</h5>
        <div class="list">
            <div class="list-item">
                <span>@lang('Thanh toán')</span>
                <span>+{{ showAmount($booking->total_amount) }}</span>
            </div>

            <div class="list-item">
                <span>@lang('Đã nhận được thanh toán')</span>
                <span> -{{ showAmount($receivedPayments->sum('amount')) }}</span>
            </div>

            <div class="list-item">
                <span>@lang('Đã hoàn tiền')</span>
                <span> -{{ showAmount($returnedPayments->sum('amount')) }}</span>
            </div>

            <div class="list-item fw-bold">
                @if ($due < 0)
                    <span class="text-danger">@lang('Có thể hoàn trả') </span>
                    <span class="text-danger"> = {{ showAmount(abs($due)) }}</span>
                @else
                    <span>@lang('Phải thu từ người dùng')</span>
                    <span> = {{ showAmount(abs($due)) }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

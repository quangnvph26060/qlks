<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="card-title">@lang('Thông tin thanh toán')</h5>
            @can('admin.booking.details')
                <a class="btn btn-sm btn--primary" href="{{ route('admin.booking.details', $booking->id) }}"> <i class="las la-desktop"></i>@lang('Xem chi tiết')</a>
            @endcan
        </div>
        <div class="list">
            <div class="list-item">
                <span>@lang('Tổng giá')</span>
                <span class="text-end">+{{ showAmount($totalFare) }}</span>
            </div>

            <div class="list-item">
                <span>{{ __(gs()->tax_name) }} @lang('Thuế')</span>
                <span class="text-end">+{{ showAmount($totalTaxCharge) }}</span>
            </div>

            <div class="list-item">
                <span>@lang('Giá bị hủy')</span>
                <span class="text-end">-{{ showAmount($canceledFare) }}</span>
            </div>

            <div class="list-item">
                <span>@lang('Canceled') {{ __(gs()->tax_name) }} @lang('Charge')</span>
                <span class="text-end">-{{ showAmount($canceledTaxCharge) }}</span>
            </div>

            <div class="list-item">
                <span>@lang('Phí dịch vụ bổ sung')</span>
                <span class="text-end">+{{ showAmount($booking->service_cost) }}</span>
            </div>

            <div class="list-item">
                <span>@lang('Các khoản phí khác')</span>
                <span class="text-end">+{{ showAmount($booking->extraCharge()) }}</span>
            </div>

            <div class="list-item">
                <span>@lang('Phí hủy')</span>
                <span class="text-end">+{{ showAmount($booking->cancellation_fee) }}</span>
            </div>

            <div class="list-item">
                <span class="fw-bold">@lang('Tổng tiền')</span>
                <span class="fw-bold text-end"> = {{ showAmount($booking->total_amount) }}</span>
            </div>

        </div>
    </div>
</div>

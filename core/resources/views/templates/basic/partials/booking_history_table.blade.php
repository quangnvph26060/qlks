<div class="table-responsive--md">
    <table class="custom--table head--base table">
        <thead>
            <tr>
                <th>@lang('ID hóa đơn.')</th>
                <th>@lang('Ngày nhận phòng') - @lang('Ngày trả phòng')</th>
                <th>@lang('Tổng tiền')</th>
                <th>@lang('Quá hạn')</th>
                <th>@lang('Trạng thái')</th>
                <th>@lang('Hành động')</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($bookings as $booking)
                <tr>
                    <td><small class="fw-bold">#{{ $booking->booking_number }}</small></td>
                    <td>{{ showDateTime($booking->check_in, 'd M, Y') }} - {{ showDateTime(\Carbon\Carbon::parse($booking->check_out), 'd M, Y') }}</td>
                    <td>{{ showAmount($booking->total_amount) }}</td>
                    @php
                        $due = $booking->total_amount - $booking->paid_amount;
                        $due = $due > 0 ? $due : 0;
                    @endphp
                    <td><span class="text--danger">{{  showAmount($due) }}</span></td>

                    <td>@php echo $booking->statusBadge; @endphp</td>

                    <td>
                        <div class="group-button">
                            <a class="btn btn-sm btn-outline--info ms-1 @if ($due == 0) disabled @endif" href="{{ route('user.booking.payment', $booking->id) }}">
                                <i class="las la-wallet"></i> @lang('Thanh toán ngay')
                            </a>

                            <a class="btn btn-sm btn-outline--base ms-1" href="{{ route('user.booking.details', $booking->id) }}">
                                <i class="las la-desktop"></i> @lang('Chi tiết')
                            </a>
                        </div>

                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                </tr>
            @endforelse
        </tbody>

    </table>
</div>

@if ($bookings->hasPages())
    {{ paginateLinks($bookings) }}
@endif

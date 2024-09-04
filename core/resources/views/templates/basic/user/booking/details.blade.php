@extends($activeTemplate . 'layouts.master')
@section('content')
    @php
        $totalFare = $booking->bookedRooms->sum('fare');
        $totalTaxCharge = $booking->bookedRooms->sum('tax_charge');
        $canceledFare = $booking->bookedRooms->where('status', Status::ROOM_CANCELED)->sum('fare');
        $canceledTaxCharge = $booking->bookedRooms->where('status', Status::ROOM_CANCELED)->sum('tax_charge');
    @endphp
    <h5 class="text--secondary mb-3 text-center">@lang('Phồng đã đặt')</h5>
    <div class="table-responsive--md">
        <table class="custom--table table">
            <thead>
                <tr>
                    <th>@lang('Đã đặt trước')</th>
                    <th>@lang('Loại phòng')</th>
                    <th>@lang('Số phòng')</th>
                    <th>@lang('Giá') / @lang('Đêm')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($booking->bookedRooms->groupBy('booked_for') as $bookedRoom)
                    @foreach ($bookedRoom as $booked)
                        <tr>
                            @if ($loop->first)
                                <td class="bg--date text-center" data-label="@lang(' Đã đặt chỗ cho')" rowspan="{{ count($bookedRoom) }}">
                                    {{ showDateTime($booked->booked_for, 'd M, Y') }}
                                </td>
                            @endif
                            <td class="text-center" data-label="@lang('Loại phòng')">{{ __($booked->room->roomType->name) }}</td>
                            <td data-label="@lang('Phòng số')">{{ __($booked->room->room_number) }}
                                @if ($booked->status == Status::ROOM_CANCELED)
                                    <span class="text--danger text-sm">(@lang('Đã hủy'))</span>
                                @endif
                            </td>
                            <td data-label="@lang('Giá') / @lang('Đêm')">{{ showAmount($booked->fare) }}</td>
                        </tr>
                    @endforeach
                @endforeach

                <tr>
                    <td class="text-end" colspan="3">
                        <span class="fw-bold">@lang('Tổng giá')</span>
                    </td>
                    <td class="fw-bold">
                        {{ showAmount($totalFare) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    @if ($booking->usedPremiumService->count())
        <h5 class="text--secondary mt-4 mb-3 text-center">@lang('Dịch  vụ')</h5>
        <div class="table-responsive--md">
            <table class="custom--table head--base table">
                <thead>
                    <tr>
                        <th>@lang('Ngày ')</th>
                        <th>@lang('Số phòng')</th>
                        <th>@lang('Dịch vụ')</th>
                        <th>@lang('Tổng ')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($booking->usedPremiumService->groupBy('service_date') as $services)
                        @foreach ($services as $service)
                            <tr>
                                @if ($loop->first)
                                    <td class="bg--date text-center" data-label="@lang('Ngày')" rowspan="{{ count($services) }}">
                                        {{ showDateTime($service->service_date, 'd M, Y') }}
                                    </td>
                                @endif

                                <td data-label="@lang('Số phòng')">
                                    <span class="fw-bold">{{ __($service->room->room_number) }}</span>
                                </td>
                                <td data-label="@lang('Service')">
                                    <span class="fw-bold">
                                        {{ __($service->premiumService->name) }}
                                    </span>
                                    <br>

                                    {{ showAmount($service->unit_price) }} x {{ $service->qty }}
                                </td>
                                <td data-label="@lang('Total')">
                                    <span class="fw-bold">
                                        {{ showAmount($service->total_amount) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach

                    <tr>
                        <td class="text-end" colspan="3">
                            <span class="fw-bold">@lang('Total')</span>
                        </td>
                        <td class="fw-bold">
                            {{ showAmount($booking->service_cost) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

    @php
        $receivedPyaments = $booking->payments->where('type', 'RECEIVED');
        $returnedPyaments = $booking->payments->where('type', 'RETURNED');
    @endphp

    @if ($receivedPyaments->count())
        <h5 class="text--secondary mt-4 mb-3 text-center">@lang('Thanh toán đã nhận')</h5>
        <div class="table-responsive--md">
            <table class="custom--table head--base table">
                <thead>
                    <tr>
                        <th>@lang('Thời gian')</th>
                        <th>@lang('Phương thức thanh toán')</th>
                        <th>@lang('Tổng tiền')</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($receivedPyaments as $payment)
                        <tr>
                            <td class="text-start">{{ __(showDateTime($payment->created_at, 'd M, Y')) }}</td>
                            <td>
                                @if ($payment->admin_id == 0)
                                    @lang('Thanh toán Online')
                                @else
                                    @lang('Thanh toán tiền mặt')
                                @endif

                            </td>
                            <td>{{ showAmount($payment->amount) }}</td>
                        </tr>
                    @endforeach

                    <tr>
                        <td class="text-end fw-bold" colspan="2">@lang('Total')</td>
                        <td class="fw-bold">{{ showAmount($receivedPyaments->sum('amount')) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

    @if ($returnedPyaments->count())
        <h5 class="text--secondary mt-4 mb-3 text-center">@lang('Thanh toán được trả lại')</h5>
        <div class="table-responsive--md">
            <table class="custom--table head--base table">
                <thead>
                    <tr>
                        <th>@lang('Thời gian')</th>
                        <th>@lang('Phương thức thanh toán')</th>
                        <th>@lang('Tổng tiền')</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($returnedPyaments as $payment)
                        <tr>
                            <td class="text-start">{{ __(showDateTime($payment->created_at, 'd M, Y')) }}</td>
                            <td>@lang('Thanh toán tiền mặt')</td>
                            <td>{{ showAmount($payment->amount) }}</td>
                        </tr>
                    @endforeach

                    <tr>
                        <td class="text-end" colspan="2">
                            <span class="fw-bold">@lang('Tổng')</span>
                        </td>
                        <td class="fw-bold">{{ showAmount($returnedPyaments->sum('amount')) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

    @php
        $due = $booking->total_amount - $booking->paid_amount;
    @endphp

    <h5 class="text--secondary mt-4 mb-3 text-center">@lang('Thông tin thanh toán')</h5>
    <div class="card shadow">
        <div class="card-body">
            <ul class="list-group list-group-flush">

                <li class="d-flex justify-content-between list-group-item align-items-start">
                    <span>@lang('Tổng giá')</span>
                    <span> +{{ showAmount($totalFare) }}</span>
                </li>
                <li class="d-flex justify-content-between list-group-item align-items-start">
                    <span>{{ __(gs()->tax_name) }} ({{ showAmount($booking->taxPercentage(), currencyFormat: false) }}%)</span>
                    <span> +{{ showAmount($totalTaxCharge) }}</span>
                </li>

                @if ($canceledFare > 0)
                    <li class="d-flex justify-content-between list-group-item align-items-start">
                        <span>@lang('Thuế đã hủy')</span>
                        <span> -{{ showAmount($canceledFare) }}</span>
                    </li>
                @endif

                @if ($canceledTaxCharge > 0)
                    <li class="d-flex justify-content-between list-group-item align-items-start">
                        <span>@lang('Canceled') {{ __(gs()->tax_name) }} @lang('Charge')</span>
                        <span> -{{ showAmount($canceledTaxCharge) }}</span>
                    </li>
                @endif

                @if ($booking->service_cost > 0)
                    <li class="d-flex justify-content-between list-group-item align-items-start">
                        <span>@lang('Phí dịch vụ bổ sung')</span>
                        <span> +{{ showAmount($booking->service_cost) }}</span>
                    </li>
                @endif

                @if ($booking->extraCharge() > 0)
                    <li class="d-flex justify-content-between list-group-item align-items-start">
                        <span>@lang('Các khoản phí khác')</span>
                        <span> +{{ showAmount($booking->extraCharge()) }}</span>
                    </li>
                @endif

                @if ($booking->cancellation_fee > 0)
                    <li class="d-flex justify-content-between list-group-item align-items-start">
                        <span>@lang('Phí hủy bỏ')</span>  
                        {{-- Cancellation Fee --}}
                        <span> +{{ showAmount($booking->cancellation_fee) }}</span>
                    </li>
                @endif

                <li class="d-flex justify-content-between list-group-item align-items-start">
                    <span class="fw-bold">@lang('Tổng số tiền phải trả')</span>
                    <span class="fw-bold"> = {{ showAmount($booking->total_amount) }}</span>
                </li>

                <li class="d-flex justify-content-between list-group-item align-items-start">
                    <span>@lang('Tổng số tiền đã thanh toán')</span>
                    <span>{{ showAmount($receivedPyaments->sum('amount')) }}</span>
                </li>

                @php
                    $refundedAmount = $returnedPyaments->sum('amount');
                @endphp

                @if ($refundedAmount > 0)
                    <li class="d-flex justify-content-between list-group-item align-items-start">
                        <span class="fw-bold">@lang('Đã hoàn lại')</span>
                        <span class="fw-bold">{{ showAmount($refundedAmount) }}</span>
                    </li>
                @endif

                @if ($due >= 0)
                    <li class="d-flex justify-content-between list-group-item align-items-start">
                        <span class="fw-bold">@lang('Quá hạn')</span>
                        <span class="fw-bold @if ($due > 0) text--danger @else text--success @endif">{{ showAmount($due) }}</span>
                    </li>
                @endif

                @if ($due < 0)
                    <li class="d-flex justify-content-between list-group-item align-items-start">
                        <span class="fw-bold">@lang('Có thể hoàn trả')</span>
                        <span class="fw-bold text--danger">{{ showAmount(abs($due)) }}</span>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    @if ($due > 0 && $booking->status == Status::BOOKING_ACTIVE)
        <div class="text-end mt-4">
            <a class="btn btn-sm btn--base px-5" href="{{ route('user.booking.payment', $booking->id) }}">
                <i class="las la-money-bill-alt"></i> @lang('Thanh toán ngay')
            </a>
        </div>
    @endif
@endsection

@push('style')
    <style>
        .bg--date {
            background-color: #dadada !important;
            color: #656565 !important;
        }

        .custom--table thead th {
            background-color: var(--base-color);
            color: #fff !important;
        }

        .shadow {
            box-shadow: 0 1px 3px 0 #0000000f !important;
        }

        .custom--table tbody td:first-child {
            text-align: center;
        }

        .custom--table tbody td {
            padding: 0.3rem 0.5rem !important;
        }
    </style>
@endpush

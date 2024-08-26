<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta content="IE=edge" http-equiv="X-UA-Compatible" />
    <meta content="IE=edge" http-equiv="X-UA-Compatible" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ gs()->siteName('Invoice') }}</title>
    <link href="{{ siteFavicon() }}" rel="shortcut icon" type="image/png">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/invoice.css') }}">
</head>

<body>
    @php
        $premiumService = count($booking->usedPremiumService);
        $due = $booking->total_amount - $booking->paid_amount;
    @endphp
    <header>
        <div class="row">
            <div class="col-12">
                <div class="list--row">
                    <div class="logo float-left">
                        <img alt="@lang('image')" class="logo-img" src="{{ siteLogo('dark') }}">
                    </div>
                    <h6 class="float-right m-0" style="margin: 0;"> {{ date('d/m/Y') }}</h6>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="row">
            <div class="col-12">
                <div class="address list--row">
                    <div class="float-left">
                        <h5 class="primary-text d-block fw-md">@lang('Invoice To')</h5>
                        <ul class="list" style="--gap: 0.3rem">
                            <li>
                                <div class="list list--row gap-5rem">
                                    <span class="strong">@lang('Name') :</span>
                                    <span>{{ $booking->user ? $booking->user->fullname : $booking->guest_details->name }}</span>
                                </div>
                            </li>
                            <li>
                                <div class="list list--row gap-5rem">
                                    <span class="strong">@lang('Email') :</span>
                                    <span>{{ $booking->user ? $booking->user->email : $booking->guest_details->email }}</span>
                                </div>
                            </li>
                            <li>
                                <div class="list list--row gap-5rem">
                                    <span class="strong">@lang('Mobile') :</span>
                                    <span>+{{ $booking->user ? $booking->user->mobile : $booking->guest_details->mobile }}</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="float-right">
                        <ul class="text-end">
                            <li>
                                <h5 class="primary-text d-block fw-md"> @lang('Bill Information') </h5>
                            </li>

                            <li>
                                <span class="d-inline-block strong">@lang('Booking No') :</span>
                                <span class="d-inline-block">{{ $booking->booking_number }}</span>
                            </li>

                            <li>
                                <span class="d-inline-block strong">@lang('Total Amount') :</span>
                                <span class="d-inline-block">{{ showAmount($booking->total_amount) }}</span>
                            </li>
                            <li>
                                <span class="d-inline-block strong">@lang('Paid Amount') :</span>
                                <span class="d-inline-block">{{ showAmount($booking->paid_amount) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="body">
                    <h5 class="title">@lang('Room\'s Details')</h5>
                    <table class="table-bordered custom-table table">
                        <thead>
                            <tr>
                                <th>@lang('Room No.')</th>
                                <th>@lang('Room Type')</th>
                                <th>@lang('Fare')</th>
                            </tr>
                        </thead>
                        @php
                            $activeBookedRooms = $booking->activeBookedRooms->groupBy('booked_for');
                            $totalFare = $booking->activeBookedRooms->sum('fare');
                        @endphp

                        <tbody>
                            @foreach ($activeBookedRooms as $key => $item)
                                <tr class="custom-table__subhead">
                                    <td colspan="3" style="text-align: center;">
                                        {{ __(showDateTime($key, 'd M, Y')) }}
                                    </td>
                                </tr>
                                @foreach ($item as $booked)
                                    <tr>
                                        <td class="text-start">{{ __($booked->room->room_number) }}</td>
                                        <td>{{ __($booked->room->roomType->name) }}</td>
                                        <td>{{ __(showAmount($booked->fare)) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach

                            <tr class="custom-table__subhead">
                                <td class="text-end" colspan="2">@lang('Total Fare')</td>
                                <td>{{ showAmount($totalFare) }}</td>
                            </tr>

                            @if (!$premiumService)

                                @if ($booking->cancellation_fee > 0)
                                    <tr class="custom-table__subhead">
                                        <td class="text-end" colspan="2">@lang('Cancellation Fee')</td>
                                        <td>{{ showAmount($booking->cancellation_fee) }}</td>
                                    </tr>
                                @endif
                                <tr class="custom-table__subhead">
                                    <td class="text-end" colspan="2">{{ __(gs()->tax_name) }}</td>
                                    <td>{{ showAmount($booking->tax_charge) }}</td>
                                </tr>
                                @if ($booking->extraCharge() > 0)
                                    <tr class="custom-table__subhead">
                                        <td class="text-end" colspan="2">@lang('Other Charges')</td>
                                        <td>{{ showAmount($booking->extraCharge()) }}</td>
                                    </tr>
                                @endif

                                <tr class="custom-table__subhead">
                                    <td class="text-end" colspan="2">@lang('Total')</td>
                                    <td>{{ showAmount($booking->total_amount) }}</td>
                                </tr>

                                @if ($due > 0)
                                    <tr class="custom-table__subhead">
                                        <td class="text-end" colspan="2">@lang('Due')</td>
                                        <td>{{ showAmount($due) }}</td>
                                    </tr>
                                @elseif($due < 0)
                                    <tr class="custom-table__subhead">
                                        <td class="text-end" colspan="2">@lang('Refundable')</td>
                                        <td>{{ showAmount(abs($due)) }}</td>
                                    </tr>
                                @endif
                            @endif
                        </tbody>
                    </table>

                    @if ($premiumService)
                        @php
                            $premiumServices = $booking->usedPremiumService->groupBy('service_date');
                        @endphp
                        <div class="extra-service">
                            <div class="mt-10">
                                <h5 class="title">@lang('Service Details')</h5>
                            </div>
                            <table class="table-bordered custom-table table">
                                <thead>
                                    <tr>
                                        <th>@lang('Room No.')</th>
                                        <th>@lang('Service')</th>
                                        <th>@lang('Quantity')</th>
                                        <th>@lang('Unit Price')</th>
                                        <th>@lang('Amount')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($premiumServices as $key => $serviceItems)
                                        <tr class="custom-table__subhead">
                                            <td colspan="5" style="text-align: center;">{{ __(showDateTime($key, 'd M, Y')) }}</td>
                                        </tr>
                                        @foreach ($serviceItems as $service)
                                            <tr>
                                                <td>{{ __($service->room->room_number) }}</td>
                                                <td>{{ __($service->premiumService->name) }}</td>
                                                <td>{{ $service->qty }}</td>
                                                <td>{{ showAmount($service->unit_price) }}</td>
                                                <td>{{ showAmount($service->total_amount) }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    <tr class="custom-table__subhead">
                                        <td class="text-end" colspan="4">@lang('Total Charge')</td>
                                        <td>{{ showAmount($booking->service_cost) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="summary avoid_page_break">
                            <div class="mt-10">
                                <h5 class="title">@lang('Billing Details')</h5>
                            </div>
                            <table class="table-bordered custom-table table">
                                <tbody>
                                    <tr>
                                        <td class="text-end">@lang('Total Fare')</td>
                                        <td>{{ showAmount($totalFare) }}</td>
                                    </tr>
                                    @if ($booking->cancellation_fee > 0)
                                        <tr>
                                            <td class="text-end">@lang('Cancellation Fee')</td>
                                            <td>{{ showAmount($booking->cancellation_fee) }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="text-end">{{ __(gs()->tax_name) }} @lang('Charge')</td>
                                        <td>{{ showAmount($booking->tax_charge) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">@lang('Service Charge')</td>
                                        <td>{{ showAmount($booking->service_cost) }}</td>
                                    </tr>
                                    @if ($booking->extraCharge() > 0)
                                        <tr>
                                            <td class="text-end">@lang('Other Charges')</td>
                                            <td>{{ showAmount($booking->extraCharge()) }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="text-end">@lang('Total')</td>
                                        <td>{{ showAmount($booking->total_amount) }}</td>
                                    </tr>

                                    @if ($due > 0)
                                        <tr class="text-end">
                                            <td class="text-end">@lang('Due')</td>
                                            <td>{{ showAmount($due) }}</td>
                                        </tr>
                                    @elseif($due < 0)
                                        <tr class="text-end">
                                            <td class="text-end">@lang('Refundable')</td>
                                            <td>{{ showAmount(abs($due)) }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</body>

</html>

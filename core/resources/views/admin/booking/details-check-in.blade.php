@extends('admin.layouts.app')
@section('panel')
    @php
        $totalFare = 1000;
        $totalTaxCharge = 1000;
        $canceledFare = 1000;
        $canceledTaxCharge = 1000;

    @endphp

    <div class="row gy-4">
        <div class="col-md-4 ">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">

                        {{-- <div>
                            <small class="fw-500"> <i class="las la-user-edit"></i> @lang('Loại khách hàng')</small><br>
                            @if ($booking->user_id)
                                <span class="d-bock">@lang('Khách hàng đã đăng ký')</span>
                            @else
                                <span class="d-bock">@lang('Khách lưu trú')</span>
                            @endif
                            <span class="d-bock">@lang('Khách lưu trú')</span>
                        </div> --}}

                        <div>

                            <small class="fw-500"> <i class="la la-user"></i> @lang('Tên')</small><br>
                            {{-- @if ($booking->user_id)
                                <a class="fw-bold d-block text--primary" href="{{ can('admin.users.detail') ? route('admin.users.detail', $booking->user_id) : 'javascript:void(0)' }}">{{ $booking->user->fullname }}</a>
                            @else
                                <span class="d-block">{{ $booking->guest_details->name }}</span>
                            @endif --}}
                            <span class="d-block">{{ $booking['customer_name'] ?? '' }}</span>
                        </div>

                        <div>
                            <small class="fw-500"><i class="la la-envelope"></i> @lang('Email')</small><br>
                            {{-- @if ($booking->user_id)
                                <span class="d-block">{{ $booking->user->email }}</span>
                            @else
                                <span class="d-block">{{ $booking->guest_details->email }}</span>
                            @endif --}}
                            <span class="d-block">{{ $booking['email'] ?? '' }}</span>
                        </div>

                        <div>
                            <small class="fw-500"><i class="la la-mobile"></i> @lang('Số điện thoại động')</small>

                            <span class="d-block">
                                {{-- @if ($booking->user_id)
                                    +{{ $booking->user->mobile }}
                                @else
                                    +{{ $booking->guest_details->mobile }}
                                @endif --}}
                                <span class="d-block">{{ $booking['phone_number'] ?? '' }}</span>
                            </span>
                        </div>

                        {{-- @php
                            $address = $booking->user_id ? $booking->user->address : @$booking->guest_details->address;
                        @endphp --}}

                        {{-- <div>
                            <small class="fw-500"><i class="la la-map"></i> @lang('Địa chỉ')</small> <br>
                            <span class="d-block">{{$booking['address'] ?? ''}}</span>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card position-relative">
                <div class="card-body">
                    <div class="custom-badge position-absolute badge--success">
                        {{-- @php
                            echo $booking->status_badge;
                        @endphp --}}
                        Nhận phòng
                    </div>

                    <div class="d-flex flex-wrap justify-content-between gap-3">
                        <div class="d-flex flex-column gap-3">
                            <div>
                                <small class="fw-500">@lang('Số đặt chỗ')</small> <br>
                                <span>{{ $booking['check_in_id'] ?? '' }}</span>

                            </div>

                            <div>
                                <small class="fw-500">@lang('Số phòng')</small> <br>
                                {{-- <span>{{ $booking->bookedRooms->count() }}</span> --}}
                                <span>{{ $booking['room']['room_number'] ?? '' }}</span>
                            </div>

                            <div>
                                <small class="fw-500">@lang('Tổng phí')</small> <br>
                                {{-- <span>{{ showAmount($booking->total_amount) }}</span> --}}
                                <span>{{ showAmount($booking['total_amount']) ?? '' }}</span>
                            </div>

                            <div>
                                <small class="fw-500">@lang('Số tiền đã thanh toán')</small> <br>
                                {{-- <span>{{ showAmount($booking->paid_amount) }}</span> --}}
                                <span>{{ showAmount($booking['deposit_amount']) ?? '' }}</span>
                            </div>

                            <div>
                                @if ($due < 0)
                                    <small class="fw-500">@lang('Có thể hoàn trả') </small> <br>
                                    <span class="text--warning">{{ showAmount($due) ?? '' }} </span>
                                @else
                                    <small class="fw-500">@lang('Phải thu từ khách hàng')</small><br>
                                    <span class="@if ($due > 0) text--danger @else text--success @endif">
                                        {{ showAmount(abs($due)) }}</span>
                                @endif
                </div>
                        </div>

                        <div class="d-flex flex-column gap-3">
                            {{-- <div>
                                <small class="fw-500">@lang('Đặt lúc')</small> <br>

                                <small> <em class="text-muted">{{ showDateTime($booking->checkin_date, 'd M, Y h:i A') }}</em></small>
                            </div> --}}

                            <div>
                                <small class="fw-500">@lang('Nhận phòng')</small> <br>
                                <small> <em class="text-muted">{{ showDateTime($booking->checkin_date, 'd M, Y h:i A') }}</em></small>
                            </div>

                            <div>
                                <small class="fw-500">@lang('Trả phòng')</small> <br>
                                <small> <em class="text-muted">{{ showDateTime($booking->checkout_date, 'd M, Y h:i A') }}</em></small>
                            </div>

                            <div>
                                <small class="fw-500">@lang('Ghi chú')</small> <br>
                              <textarea name="" id="" cols="30" rows="2">{{$booking['note']}}</textarea>
                            </div>
                            {{-- <div>
                                <small class="fw-500">@lang('Nhận phòng lúc')</small> <br>
                                <small> <em class="text-muted">{{ showDateTime($booking->checked_in_at, 'd M, Y h:i A') }}</em></small>
                            </div> --}}
                            {{-- <div>
                                <small class="fw-500">@lang('Trả phòng lúc')</small> <br>
                                <small> <em class="text-muted">
                                        @if ($booking->checked_out_at)
                                            {{ showDateTime($booking->checked_out_at, 'd M, Y h:i A') }}
                                        @else
                                            @lang('N/A')
                                        @endif
                                    </em>
                                </small>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-12">
            <div class="accordion d-flex flex-column gap-4">
                {{-- <div class="accordion-item">
                    <h2 class="accordion-header" id="bookedRoomsHeading">
                        <button aria-controls="bookedRooms" aria-expanded="true" class="accordion-button"
                            data-bs-target="#bookedRooms" data-bs-toggle="collapse" type="button">
                            @lang('Phòng đã đặt')
                        </button>
                    </h2>
                    <div aria-labelledby="bookedRoomsHeading" class="accordion-collapse collapse show" data-bs-parent="#s"
                        id="bookedRooms">
                        <div class="accordion-body p-0">
                            <div class="table-responsive--sm">
                                <table class="custom--table table table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">@lang('Đã đặt chỗ')</th>
                                            <th>@lang('Loại phòng')</th>
                                            <th>@lang('Phòng số')</th>
                                            <th class="text-end">@lang('Giá') / @lang('Đêm')</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($booking->bookedRooms->groupBy('booked_for') as $bookedRoom)
                                            @foreach ($bookedRoom as $booked)
                                                <tr>
                                                    @if ($loop->first)
                                                        <td class="bg--date text-center" rowspan="{{ count($bookedRoom) }}">
                                                            {{ showDateTime($booked->booked_for, 'd M, Y') }}
                                                        </td>
                                                    @endif

                                                    <td class="text-center" data-label="@lang('Loại phòng')">
                                                        {{ __($booked->room->roomType->name) }}
                                                    </td>
                                                    <td data-label="@lang('Room No.')">
                                                        {{ __($booked->room->room_number) }}
                                                        @if ($booked->status == Status::ROOM_CANCELED)
                                                            <span class="text--danger text-sm">(@lang('Đã hủy'))</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end" data-label="@lang('Giá')">
                                                        {{ showDateTime ($booked->fare) }}
                                                    </td>

                                                </tr>
                                            @endforeach
                                        @endforeach
                                        <tr>
                                            <td class="text-end" colspan="3">
                                                <span class="fw-bold">@lang('Tổng giá')</span>
                                            </td>

                                            <td class="fw-bold text-end">
                                                12312
                                                {{ showAmount($totalFare) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="premiumServiceHeading">
                        <button aria-controls="premiumService" aria-expanded="false" class="accordion-button"
                            data-bs-target="#premiumService" data-bs-toggle="collapse" type="button">
                            @lang('Dịch vụ cao cấp')
                        </button>
                    </h2>
                    <div aria-labelledby="premiumServiceHeading" class="accordion-collapse collapse show"
                        data-bs-parent="#s" id="premiumService">
                        <div class="accordion-body p-0">
                            @if (true)
                                <div class="table-responsive--sm">
                                    <table class="custom--table head--base table table-striped">
                                        <thead>
                                            <tr>
                                                <th>@lang('Ngày')</th>
                                                <th>@lang('Phòng số')</th>
                                                <th>@lang('Dịch vụ')</th>
                                                <th>@lang('Tổng')</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            dich jvuj cao caaps
                                            {{-- @foreach ($booking->usedPremiumService->groupBy('service_date') as $services)
                                                @foreach ($services as $service)
                                                    <tr>
                                                        @if ($loop->first)
                                                            <td class="bg--date text-center" data-label="@lang('Ngày')" rowspan="{{ count($services) }}">
                                                                {{ showDateTime($service->service_date, 'd M, Y') }}
                                                            </td>
                                                        @endif

                                                        <td data-label="@lang('Phòng số')">
                                                            <span class="fw-bold">{{ __($service->room->room_number) }}</span>
                                                        </td>
                                                        <td data-label="@lang('Dịch vụ')">
                                                            <span class="fw-bold">
                                                                {{ __($service->premiumService->name) }}
                                                            </span>
                                                            <br>
                                                            {{ showAmount($service->unit_price) }} x {{ $service->qty }}
                                                        </td>
                                                        <td data-label="@lang('Tổng')">
                                                            <span class="fw-bold text-end">
                                                                {{ showAmount($service->total_amount) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach --}}

                                            <tr>
                                                <td class="text-end" colspan="3">
                                                    <span class="fw-bold">@lang('Tổng')</span>
                                                </td>
                                                <td class="fw-bold text-end">
                                                    {{-- {{ showAmount($booking->service_cost) }} --}}
                                                    34543
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center">
                                    <h6 class="p-3">@lang('Không sử dụng dịch vụ bổ sung')</h6>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @php
                    // $receivedPyaments = $booking->payments->where('type', 'RECEIVED');
                    // $returnedPyaments = $booking->payments->where('type', 'RETURNED');
                    $receivedPyaments = 69000;
                    $returnedPyaments = 2000;
                @endphp

                <div class="accordion-item">
                    <h2 class="accordion-header" id="paymentReceived">
                        <button aria-controls="paymentsReceived" aria-expanded="false" class="accordion-button"
                            data-bs-target="#paymentsReceived" data-bs-toggle="collapse" type="button">
                            @lang('Thanh toán đã nhận')
                        </button>
                    </h2>
                    <div aria-labelledby="paymentReceived" class="accordion-collapse collapse show" data-bs-parent="#s"
                        id="paymentsReceived">
                        <div class="accordion-body p-0">
                            <div class="table-responsive--sm">
                                <table class="custom--table head--base table table-striped">
                                    <thead>
                                        <tr>
                                            <th>@lang('Thời gian')</th>
                                            <th>@lang('Loại thanh toán')</th>
                                            <th>@lang('Số lượng')</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        {{-- @foreach ($receivedPyaments as $payment)
                                            <tr>
                                                <td class="text-start">{{ __(showDateTime($payment->created_at, 'd M, Y H:i A')) }}</td>
                                                <td>
                                                    @if ($payment->admin_id == 0)
                                                        @lang('Chuyển khoản')
                                                    @else
                                                        @lang('Tiền mặt')
                                                    @endif
                                                </td>
                                                <td>{{ showAmount($payment->amount) }}
                                            </tr>
                                        @endforeach --}}

                                        <tr>
                                            <td class="text-end fw-bold" colspan="2">
                                                @lang('Tổng tiền')
                                            </td>
                                            {{-- <td class="fw-bold">
                                                {{ showAmount($receivedPyaments->sum('amount')) }}
                                            </td> --}}
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="paymentReturned">
                        <button aria-controls="paymentsReturned" aria-expanded="false" class="accordion-button"
                            data-bs-target="#paymentsReturned" data-bs-toggle="collapse" type="button">
                            @lang('Thanh toán được trả lại')
                        </button>
                    </h2>
                    <div aria-labelledby="paymentReturned" class="accordion-collapse collapse show" data-bs-parent="#s"
                        id="paymentsReturned">
                        <div class="accordion-body p-0">
                            <table class="custom--table head--base table table-striped">
                                <thead>
                                    <tr>
                                        <th>@lang('Thời gian')</th>
                                        <th>@lang('Phương thức thanh toán')</th>
                                        <th>@lang('Tổng')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    {{-- @foreach ($returnedPyaments as $payment)
                                        <tr>
                                            <td class="text-start">{{ __(showDateTime($payment->created_at, 'd M, Y H:i A')) }}</td>
                                            <td>
                                                @lang('Tiền mặt')
                                            </td>
                                            <td>{{ showAmount($payment->amount) }}
                                        </tr>
                                    @endforeach --}}

                                    <tr>
                                        <td class="text-end" colspan="2">
                                            <span class="fw-bold">@lang('Tổng tiền')</span>
                                        </td>
                                        <td class="fw-bold">
                                            12312
                                            {{-- {{ showAmount($returnedPyaments->sum('amount')) }} --}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- <div class="accordion-item">
                    <h2 class="accordion-header" id="summaryHeading">
                        <button aria-controls="summaryBody" aria-expanded="false" class="accordion-button"
                            data-bs-target="#summaryBody" data-bs-toggle="collapse" type="button">
                            @lang('Thông tin thanh toán')
                        </button>
                    </h2>
                    <div aria-labelledby="summaryHeading" class="accordion-collapse collapse show" data-bs-parent="#s"
                        id="summaryBody">
                        <div class="accordion-body p-0">
                            <ul class="list-group list-group-flush">

                                <li class="d-flex justify-content-between list-group-item align-items-start">
                                    <span>@lang('Tổng giá vé')</span>
                                    <span> +{{ showAmount($totalFare) }}</span>
                                </li>
                                <li class="d-flex justify-content-between list-group-item align-items-start">
                                    <span>{{ __(gs()->tax_name) }} @lang('Thuế') <small>({{ showAmount($booking->taxPercentage(), currencyFormat: false) }}%)</small></span>
                                    <span> +{{ showAmount($totalTaxCharge) }}</span>
                                </li>

                                <li class="d-flex justify-content-between list-group-item align-items-start">
                                    <span>@lang('Thuế đã hủy')</span>
                                    <span> -{{ showAmount($canceledFare) }}</span>
                                </li>

                                <li class="d-flex justify-content-between list-group-item align-items-start">
                                    <span>@lang('Đã hủy') {{ __(gs()->tax_name) }} @lang('Charge')</span>
                                    <span> -{{ showAmount($canceledTaxCharge) }}</span>
                                </li>

                                <li class="d-flex justify-content-between list-group-item align-items-start">
                                    <span>@lang('Phí dịch vụ bổ sung')</span>
                                    <span> +{{ showAmount($booking->service_cost) }}</span>
                                </li>

                                @if ($booking->extraCharge() > 0)
                                    <li class="d-flex justify-content-between list-group-item align-items-start">
                                        <span>@lang('Các khoản phí khác')</span>
                                        <span> +{{ showAmount($booking->extraCharge()) }}</span>
                                    </li>
                                @endif

                                @if ($booking->cancellation_fee > 0)
                                    <li class="d-flex justify-content-between list-group-item align-items-start">
                                        <span>@lang('Phí hủy bỏ')</span>
                                        <span> +{{ showAmount($booking->cancellation_fee) }}</span>
                                    </li>
                                @endif

                                <li class="d-flex justify-content-between list-group-item align-items-start">
                                    <span class="fw-bold">@lang('Tổng số tiền')</span>
                                    <span class="fw-bold"> = {{ showAmount($booking->total_amount) }}</span>
                                </li>

                                <li class="d-flex justify-content-between list-group-item align-items-start">
                                    <span>@lang('Thanh toán đã nhận')</span>
                                    <span>{{ showAmount($receivedPyaments->sum('amount')) }}</span>
                                </li>

                                <li class="d-flex justify-content-between list-group-item align-items-start">
                                    <span>@lang('Đã hoàn lại')</span>
                                    <span>{{ showAmount($returnedPyaments->sum('amount')) }}</span>
                                </li>

                                @php $due = $booking->due(); @endphp

                                <li class="d-flex justify-content-between list-group-item align-items-start">
                                    @if ($due < 0)
                                        <span class="text--warning fw-bold">@lang('Có thể hoàn trả') </span>
                                        <span class="text--warning fw-bold">{{ showAmount(abs($due)) }}</span>
                                    @else
                                        <span class="@if ($due > 0) text--danger @else text--success @endif fw-bold">@lang('Phải thu từ người dùng')</span>
                                        <span class="@if ($due > 0) text--danger @else text--success @endif fw-bold"> {{ showAmount(abs($due)) }}</span>
                                    @endif
                                    <span
                                        class="@if ($due > 0) text--danger @else text--success @endif fw-bold">@lang('Phải thu từ người dùng')</span>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
        @include('admin.booking.partials.modals')
    @endsection

    @can(['admin.booking.details', 'admin.booking.booked.rooms', 'admin.booking.service.details',
        'admin.booking.payment', 'admin.booking.key.handover', 'admin.booking.merge', 'admin.booking.cancel',
        'admin.booking.extra.charge', 'admin.booking.checkout', 'admin.booking.invoice', 'admin.booking.all'])
        @push('breadcrumb-plugins')
            @can('admin.booking.all')
                <a class="btn btn-sm btn--primary" href="{{ route('admin.book.room') }}">
                    <i class="la la-list"></i>@lang('Tất cả các đặt phòng')
                </a>
            @endcan

            <button aria-expanded="false" class="btn btn-sm btn--info dropdown-toggle" data-bs-toggle="dropdown"
                type="button">
                <i class="la la-ellipsis-v"></i>@lang('Thêm')
            </button>

            <div class="dropdown-menu">
                @can('admin.booking.booked.rooms')
                    {{-- <a class="dropdown-item" href="{{ route('admin.booking.booked.rooms', $booking->id) }}">
                        <i class="las la-desktop"></i> @lang('Phòng đã đặt')
                    </a> --}}
                    <i class="las la-desktop"></i> @lang('Phòng đã đặt')
                @endcan

                @can('admin.booking.service.details')
                    {{-- <a class="dropdown-item" href="{{ route('admin.booking.service.details', $booking->id) }}">
                        <i class="las la-server"></i> @lang('Dịch vụ cao cấp')
                    </a> --}}
                    <i class="las la-server"></i> @lang('Dịch vụ cao cấp')
                @endcan

                @can('admin.booking.payment')
                    {{-- <a class="dropdown-item" href="{{ route('admin.booking.payment', $booking->id) }}">
                        <i class="la la-money-bill"></i> @lang('Thanh toán')
                    </a> --}}
                    <i class="la la-money-bill"></i> @lang('Thanh toán')
                @endcan
                {{-- $booking->status == Status::BOOKING_ACTIVE --}}
                @if (true)
                    @can('admin.booking.key.handover')
                        {{-- @if (now()->format('Y-m-d') >= $booking->check_in && now()->format('Y-m-d') < $booking->check_out && $booking->key_status == Status::DISABLE)
                            <a class="dropdown-item handoverKeyBtn" data-booked_rooms="{{ $booking->activeBookedRooms->unique('room_id') }}" data-id="{{ $booking->id }}" href="javascript:void(0)">
                                <i class="las la-key"></i> @lang('Giao chìa khóa')
                            </a>
                        @endif --}}
                        <i class="las la-key"></i> @lang('Giao chìa khóa')
                    @endcan

                    @can('admin.booking.merge')
                        {{-- <a class="dropdown-item mergeBookingBtn" data-booking_number="{{ $booking->booking_number }}" data-id="{{ $booking->id }}" href="javascript:void(0)">
                            <i class="las la-object-group"></i> @lang('Merge Booking')
                        </a> --}}
                        <i class="las la-object-group"></i> @lang('Merge Booking')
                    @endcan

                    @can('admin.booking.cancel')
                        {{-- <a class="dropdown-item" href="{{ route('admin.booking.cancel', $booking->id) }}">
                            <i class="las la-times-circle"></i> @lang('Hủy đặt phòng')
                        </a> --}}
                        <i class="las la-times-circle"></i> @lang('Hủy đặt phòng')
                    @endcan

                    @can('admin.booking.checkout')
                        {{-- @if (now() >= $booking->check_out)
                            <a class="dropdown-item" href="{{ route('admin.booking.checkout', $booking->id) }}">
                                <i class="la la-sign-out"></i> @lang('Trả phòng')
                            </a>
                        @endif --}}
                        <i class="la la-sign-out"></i> @lang('Trả phòng')
                    @endcan
                @endif

                {{-- @can('admin.booking.invoice')
                    <a class="dropdown-item" href="{{ route('admin.booking.invoice', $booking->id) }}" target="_blank"><i class="las la-print"></i> @lang('Xuất hóa đơn')</a>
                @endcan --}}
                <a class="dropdown-item" href="{{ route('admin.booking.invoice', 1) }}" target="_blank"><i
                        class="las la-print"></i> @lang('Xuất hóa đơn')</a>
            </div>
        @endpush
    @endcan

    @push('style')
        <style>
            .custom-badge {
                top: -15px;
                left: calc(50% - 75px)
            }

            .custom-badge .badge {
                width: 150px;
                height: 30px;
                line-height: 24px;
                font-size: 1rem !important;
                font-weight: 500;
            }

            .table-striped>tbody>tr:nth-of-type(odd)>* {
                --bs-table-accent-bg: rgb(255 255 255 / 37%);
            }

            .custom--table thead th {
                background-color: #d9d9d9;
            }

            .custom--table th,
            .custom--table td {
                border: 1px solid #e8e8e8;
            }

            .custom--table {
                border: 1px solid #e8e8e8;
                border-collapse: collapse;
            }

            .custom--table tbody td:first-child {
                text-align: center;
            }

            .custom--table tbody td,
            .custom--table thead th {
                color: #5b6e88 !important;
            }

            @media (min-width: 768px) {
                .custom--table tbody td {
                    padding: 0.5rem 1rem !important;
                }

                .custom--table thead th {
                    padding: 1rem !important;
                }
            }

            .accordion-button:focus {
                box-shadow: none;
            }

            .accordion-button:not(.collapsed) {
                color: #fff;
                background-color: #071251;
                font-weight: bold;
            }

            .list-group-item:nth-of-type(odd) {
                background-color: #f9f9f9f2;
            }

            .accordion-button:not(.collapsed)::after {
                filter: brightness(0) invert(1);
            }

            table thead th:first-child {
                border-radius: 0;
            }

            .accordion-item:first-of-type .accordion-button {
                border-radius: unset;
            }

            .accordion-item:has(table) {
                border: 0;
            }
        </style>
    @endpush

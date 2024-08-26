@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="show-filter mb-3 text-end">
                <button class="btn btn-outline--primary showFilterBtn btn-sm" type="button"><i class="las la-filter"></i> @lang('Filter')</button>
            </div>
            <div class="card responsive-filter-card mb-4">
                <div class="card-body">
                    <form action="">
                        <div class="d-flex flex-wrap gap-4">
                            <div class="flex-grow-1">
                                <label>@lang('Keywords') <i class="las la-info-circle text--info" title="@lang('Search by booking number, username or email')"></i></label>
                                <input class="form-control" name="search" type="text" value="{{ request()->search }}">
                            </div>

                            <div class="flex-grow-1">
                                <label>@lang('Check In')</label>
                                <input autocomplete="off" class="datePicker form-control" name="check_in" type="text" value="{{ request()->check_in }}">
                            </div>

                            <div class="flex-grow-1">
                                <label>@lang('Checkout')</label>
                                <input autocomplete="off" class="datePicker1 form-control" name="check_out" type="text" value="{{ request()->check_out }}">
                            </div>

                            <div class="flex-grow-1 align-self-end">
                                <button class="btn btn--primary w-100 h-45"><i class="fas fa-filter"></i> @lang('Filter')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card bg--transparent b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table bg-white">
                            <thead>
                                <tr>
                                    <th>@lang('Booking Number')</th>
                                    <th>@lang('Guest')</th>
                                    <th>@lang('Check In') | @lang('Check Out')</th>
                                    <th>@lang('Total Amount')</th>
                                    <th>@lang('Total Paid')</th>
                                    <th>@lang('Due')</th>
                                    @if (request()->routeIs('admin.booking.all') || request()->routeIs('admin.booking.active'))
                                        <th>@lang('Status')</th>
                                    @endif

                                    @can(['admin.booking.details', 'admin.booking.booked.rooms', 'admin.booking.service.details', 'admin.booking.payment', 'admin.booking.key.handover', 'admin.booking.merge', 'admin.booking.cancel', 'admin.booking.extra.charge', 'admin.booking.checkout', 'admin.booking.invoice'])
                                        <th>@lang('Action')</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                    <tr class="@if ($booking->isDelayed() && !request()->routeIs('admin.booking.checkout.delayed')) delayed-checkout @endif">

                                        <td>
                                            @if ($booking->key_status)
                                                <span class="text--warning ">
                                                    <i class="las la-key f-size--24"></i>
                                                </span>
                                            @endif

                                            <span class="fw-bold">#{{ $booking->booking_number }}</span><br>
                                            <em class="text-muted text--small">{{ showDateTime($booking->created_at, 'd M, Y h:i A') }}</em>
                                        </td>

                                        <td>
                                            @if ($booking->user_id)
                                                <span class="small">
                                                    @can('admin.users.detail')
                                                        <a href="{{ route('admin.users.detail', $booking->user_id) }}"><span>@</span>{{ $booking->user->username }}</a>
                                                    @else
                                                        {{ $booking->user->username }}
                                                    @endcan
                                                </span>
                                                <br>
                                                <a class="fw-bold text--primary" href="tel:{{ $booking->user->email }}">+{{ $booking->user->mobile }}</a>
                                            @else
                                                <span class="small">{{ $booking->guest_details->name }}</span>
                                                <br>
                                                <span class="fw-bold">{{ $booking->guest_details->email }}</span>
                                            @endif
                                        </td>

                                        <td>
                                            {{ showDateTime($booking->check_in, 'd M, Y') }}
                                            <br>
                                            {{ showDateTime($booking->check_out, 'd M, Y') }}
                                        </td>

                                        <td>{{ showAmount($booking->total_amount) }}</td>

                                        <td>{{ showAmount($booking->paid_amount) }}</td>

                                        @php
                                            $due = $booking->total_amount - $booking->paid_amount;
                                        @endphp

                                        <td class="@if ($due < 0) text--danger @elseif($due > 0) text--warning @endif">
                                            {{ showAmount($due) }}
                                        </td>

                                        @if (request()->routeIs('admin.booking.all') || request()->routeIs('admin.booking.active'))
                                            <td>
                                                @php echo $booking->statusBadge; @endphp
                                            </td>
                                        @endif
                                        @can(['admin.booking.details', 'admin.booking.booked.rooms', 'admin.booking.service.details', 'admin.booking.payment', 'admin.booking.key.handover', 'admin.booking.merge', 'admin.booking.cancel', 'admin.booking.extra.charge', 'admin.booking.checkout', 'admin.booking.invoice'])
                                            <td>
                                                <div class="d-flex justify-content-end flex-wrap gap-1">
                                                    @can('admin.booking.details')
                                                        <a class="btn btn-sm btn-outline--primary" href="{{ route('admin.booking.details', $booking->id) }}">
                                                            <i class="las la-desktop"></i>@lang('Details')
                                                        </a>
                                                    @endcan

                                                    <button aria-expanded="false" class="btn btn-sm btn-outline--info" data-bs-toggle="dropdown" type="button">
                                                        <i class="las la-ellipsis-v"></i>@lang('More')
                                                    </button>

                                                    <div class="dropdown-menu">
                                                        @can('admin.booking.booked.rooms')
                                                            <a class="dropdown-item" href="{{ route('admin.booking.booked.rooms', $booking->id) }}">
                                                                <i class="las la-desktop"></i> @lang('Booked Rooms')
                                                            </a>
                                                        @endcan

                                                        @can('admin.booking.service.details')
                                                            <a class="dropdown-item" href="{{ route('admin.booking.service.details', $booking->id) }}">
                                                                <i class="las la-server"></i> @lang('Premium Services')
                                                            </a>
                                                        @endcan

                                                        @can('admin.booking.payment')
                                                            <a class="dropdown-item" href="{{ route('admin.booking.payment', $booking->id) }}">
                                                                <i class="la la-money-bill"></i> @lang('Payment')
                                                            </a>
                                                        @endcan

                                                        @if ($booking->status == Status::BOOKING_ACTIVE)
                                                            @can('admin.booking.key.handover')
                                                                @if (now()->format('Y-m-d') >= $booking->check_in && now()->format('Y-m-d') < $booking->check_out && $booking->key_status == Status::DISABLE)
                                                                    <a class="dropdown-item handoverKeyBtn" data-booked_rooms="{{ $booking->activeBookedRooms->unique('room_id') }}" data-id="{{ $booking->id }}" href="javascript:void(0)">
                                                                        <i class="las la-key"></i> @lang('Handover Keys')
                                                                    </a>
                                                                @endif
                                                            @endcan

                                                            @can('admin.booking.merge')
                                                                <a class="dropdown-item mergeBookingBtn" data-booking_number="{{ $booking->booking_number }}" data-id="{{ $booking->id }}" href="javascript:void(0)">
                                                                    <i class="las la-object-group"></i> @lang('Merge Booking')
                                                                </a>
                                                            @endcan

                                                            @can('admin.booking.cancel')
                                                                <a class="dropdown-item" href="{{ route('admin.booking.cancel', $booking->id) }}">
                                                                    <i class="las la-times-circle"></i> @lang('Cancel Booking')
                                                                </a>
                                                            @endcan

                                                            @can('admin.booking.checkout')
                                                                @if (now() >= $booking->check_out)
                                                                    <a class="dropdown-item" href="{{ route('admin.booking.checkout', $booking->id) }}">
                                                                        <i class="la la-sign-out"></i> @lang('Check Out')
                                                                    </a>
                                                                @endif
                                                            @endcan
                                                        @endif
                                                        @can('admin.booking.invoice')
                                                            <a class="dropdown-item" href="{{ route('admin.booking.invoice', $booking->id) }}" target="_blank"><i class="las la-print"></i> @lang('Print Invoice')</a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                        @endcan
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($bookings->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($bookings) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    @include('admin.booking.partials.modals')
    <x-confirmation-modal />
@endsection

@can('admin.book.room')
    @push('breadcrumb-plugins')
        <a class="btn btn-sm btn--primary" href="{{ route('admin.book.room') }}">
            <i class="la la-hand-o-right"></i>@lang('Book New')
        </a>
    @endpush
@endcan

@push('script-lib')
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            const start = moment();

            const dateRangeOptions = {
                startDate: start,
                singleDatePicker: true,
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-DD-MM'
                }
            }

            const changeDatePickerText = (element, startDate, endDate) => {
                $(element).val(startDate.format('YYYY-MM-DD'));
            }

            $('.datePicker').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('.datePicker', start));
            $('.datePicker1').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('.datePicker1', start));



            $('.datePicker').on('apply.daterangepicker', (event, picker) => changeDatePickerText(picker.startDate));
            $('.datePicker1').on('apply.daterangepicker', (event, picker) => changeDatePickerText(picker.startDate));

            if ($('.datePicker').val()) {
                $('.datePicker').data('daterangepicker').setStartDate(new Date($('.datePicker').val()));
            }

            if ($('.datePicker1').val()) {
                $('.datePicker').data('daterangepicker').setStartDate(new Date($('.datePicker1').val()));
            }

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .delayed-checkout {
            background-color: #ffefd640;
        }

        .table-responsive {
            min-height: 600px;
            background: transparent
        }

        .card {
            box-shadow: none;
        }
    </style>
@endpush

@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4 booking-wrapper">
        <div class="col-xxl-8 col-xl-6">
            <div class="card">
                <div class="card-header d-flex gap-2 flex-wrap justify-content-between">
                    <div class="card-title d-flex justify-content-between booking-info-title mb-0">
                        <h5>@lang('Booking Information')</h5>
                    </div>
                    <div>
                        <span class="fas fa-circle text--danger" disabled></span>
                        <span class="mr-5">@lang('Booked')</span>
                        <span class="fas fa-circle text--success"></span>
                        <span class="mr-5">@lang('Selected')</span>
                        <span class="fas fa-circle text--primary"></span>
                        <span>@lang('Available')</span>
                    </div>
                </div>

                <div class="card-body">

                    <div class="d-flex flex-wrap gap-3 justify-content-between mb-1">
                        <div class="d-flex flex-column mb-3">
                            <h6 class="text--primary">{{ $bookingRequest->roomType->name }}</h6>
                            <small class="text-muted">@lang('Room Type')</small>
                        </div>

                        <div class="d-flex flex-column mb-3">
                            <h6>{{ $bookingRequest->user->fullname }}</h6>
                            <small class="text-muted">@lang('Guest Name')</small>
                        </div>

                        <div class="d-flex flex-column mb-3">
                            <h6>+{{ $bookingRequest->user->mobile }}</h6>
                            <small class="text-muted">@lang('Mobile')</small>
                        </div>

                        <div class="d-flex flex-column">
                            <h6>{{ $bookingRequest->user->email }}</h6>
                            <small class="text-muted">@lang('Email')</small>
                        </div>
                    </div>

                    <div class="alert alert-info room-assign-alert p-3" role="alert"></div>
                    <div class="bookingInfo"></div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-xl-6 ">

            <div class="card">
                <div class="card-header">
                    <div class="card-title mb-0">
                        <h5>@lang('Book Room')</h5>
                    </div>
                </div>
                <div class="card-body">

                    <input form="confirmation-form" name="booking_request_id" type="hidden" value="{{ $bookingRequest->id }}">

                    <div class="orderList d-none">
                        <ul class="list-group list-group-flush orderItem">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <h6>@lang('Room')</h6>
                                <h6>@lang('Days')</h6>
                                <h6>@lang('Fare')</h6>
                                <h6>@lang('Total')</h6>
                            </li>
                        </ul>
                        <div class="d-flex justify-content-between align-items-center border-top p-2 px-3">
                            <span>@lang('Grand Total')</span>
                            <span class="totalFare" data-amount="0"></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-top p-2 px-3">
                            <span>{{ gs()->tax_name }} <small>({{ gs()->tax }}%)</small></span>
                            <span><span class="taxCharge">{{ showAmount($bookingRequest->tax_charge, currencyFormat:false) }}</span> {{ gs()->cur_text }}</span>
                            <input name="tax_charge" type="hidden">
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-top p-2 px-3">
                            <span>@lang('Total Fare')</span>
                            <span class="grandTotalFare"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="paid_amount">@lang('Paying Amount')</label>
                        <div class="input-group">
                            <input class="form-control" form="confirmation-form" id="paid_amount" min="0" name="paid_amount" step="any" type="number">
                            <span class="input-group-text">{{ __(gs()->cur_text) }}</span>
                        </div>
                    </div>

                    @can('admin.request.booking.assign.room')
                        <button class="btn btn--primary w-100 h-45 btn-book confirmationBtn" data-action="{{ route('admin.request.booking.assign.room') }}" data-question="@lang('Are you sure to book these rooms?')" type="button">@lang('Book Now')</button>
                    @endcan

                </div>
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ url()->previous() }}" />
@endpush

@push('style')
    <style>
        .booking-table td {
            white-space: unset;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            let roomListHtml = @json($view);
            $('.bookingInfo').html(roomListHtml);

            $('[name=paid_amount]').on('keypress', function(e) {
                if (e.keyCode === 13) {
                    e.preventDefault();
                    $('.confirmationBtn').click();
                }
            })
        })(jQuery);
    </script>
@endpush

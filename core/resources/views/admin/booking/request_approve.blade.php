@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4 booking-wrapper">
        <div class="col-xxl-8 col-xl-6">
            <div class="card">
                <div class="card-header d-flex gap-2 flex-wrap justify-content-between">
                    <div class="card-title d-flex justify-content-between booking-info-title mb-0">
                        <h5>@lang('Thông tin đặt phòng')</h5>
                    </div>
                    <div>
                        <span class="fas fa-circle text--danger" disabled></span>
                        <span class="mr-5">@lang('Đã đặt')</span>
                        <span class="fas fa-circle text--success"></span>
                        <span class="mr-5">@lang('Đã chọn')</span>
                        <span class="fas fa-circle text--primary"></span>
                        <span>@lang('Có sẵn')</span>
                    </div>
                </div>

                <div class="card-body">

                    <div class="d-flex flex-wrap gap-3 justify-content-between mb-1">
                        <div class="d-flex flex-column mb-3">
                            {{-- <h6 class="text--primary">{{ $bookingRequest->roomType->name }}</h6> --}}
                            <small class="text-muted">@lang('Loại phòng')</small>
                        </div>

                        <div class="d-flex flex-column mb-3">
                            <h6>{{ $bookingRequest->user->fullname }}</h6>
                            <small class="text-muted">@lang('Tên khách hàng')</small>
                        </div>

                        <div class="d-flex flex-column mb-3">
                            <h6>+{{ $bookingRequest->user->mobile }}</h6>
                            <small class="text-muted">@lang('Di động')</small>
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
                        <h5>@lang('Đặt phòng')</h5>
                    </div>
                </div>
                <div class="card-body">

                    <input form="confirmation-form" name="booking_request_id" type="hidden" value="{{ $bookingRequest->id }}">

                    <div class="orderList d-none">
                        <ul class="list-group list-group-flush orderItem">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <h6>@lang('Danh sách phòng')</h6>
                                <h6>@lang('Ngày')</h6>
                                <h6>@lang('Giá')</h6>
                                <h6>@lang('Tổng')</h6>
                            </li>
                        </ul>
                        <div class="d-flex justify-content-between align-items-center border-top p-2 px-3">
                            <span>@lang('Tổng cộng')</span>
                            <span class="totalFare" data-amount="0"></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-top p-2 px-3">
                            <span>{{ gs()->tax_name }} <small>({{ gs()->tax }}%)</small></span>
                            <span><span class="taxCharge">{{ showAmount($bookingRequest->tax_charge, currencyFormat:false) }}</span> {{ gs()->cur_text }}</span>
                            <input name="tax_charge" type="hidden">
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-top p-2 px-3">
                            <span>@lang('Tổng giá')</span>
                            <span class="grandTotalFare"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="paid_amount">@lang('Số tiền thanh toán')</label>
                        <div class="input-group">
                            <input class="form-control" form="confirmation-form" id="paid_amount" min="0" name="paid_amount" step="any" type="number">
                            <span class="input-group-text">{{ __(gs()->cur_text) }}</span>
                        </div>
                    </div>

                    @can('admin.request.booking.assign.room')
                        <button class="btn btn--primary w-100 h-45 btn-book confirmationBtn" data-action="{{ route('admin.request.booking.assign.room') }}" data-question="@lang('Bạn có chắc chắn muốn đặt những phòng này không?')" type="button">@lang('Đặt ngay')</button>
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

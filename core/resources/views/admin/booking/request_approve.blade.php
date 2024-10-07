@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4 booking-wrapper">
        <div class="col-xxl-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex gap-2 flex-wrap justify-content-between">
                    <div class="card-title d-flex justify-content-between booking-info-title mb-0">
                        <h5>@lang('Thông tin đặt phòng')</h5>
                    </div>
                    <div>
                        <span class="fas fa-circle text--danger" disabled></span>
                        <span class="me-3">@lang('Đang hoạt động')</span>
                        <span class="fas fa-circle text--primary"></span>
                        <span>@lang('Có sẵn')</span>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Loại phòng</th>
                            <td>
                                <p class="fw-bold">{{ $roomTypesName }}</p>
                            </td>
                        </tr>
                        <tr>
                            <th>Tên khách hàng</th>
                            <td>
                                <p class="fw-bold">{{ $bookingRequest->user->fullname }}</p>
                            </td>
                        </tr>
                        <tr>
                            <th>Di động</th>
                            <td>
                                <p class="fw-bold">+{{ $bookingRequest->user->mobile }}</p>
                            </td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>
                                <p class="fw-bold">{{ $bookingRequest->user->email }}</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6>@lang('Danh sách')</h6>
                </div>
                <div class="card-body">
                    {{-- <div class="alert alert-info room-assign-alert p-3" role="alert"></div> --}}
                    <div class="bookingInfo">
                        <table class="table--light table-bordered booking-table table">
                            <thead>
                                <tr>
                                    <th>@lang('Loại phòng')</th>
                                    <th>@lang('Ngày nhận phòng - Ngày trả phòng')</th>
                                    <th>@lang('Phòng')</th>
                                </tr>
                            </thead>
                            <tbody class="room-table">
                                @foreach ($roomTypes as $roomType)
                                    <tr>
                                        <td>{{ $roomType }}</td>
                                        <td>{{ \Carbon\Carbon::parse($bookingRequest->check_in)->format('d M, Y') }} -
                                            {{ \Carbon\Carbon::parse($bookingRequest->check_out)->format('d M, Y') }}</td>
                                        <td>
                                            @foreach ($bookingRequest->bookingItems as $room)
                                                @if ($room->room->roomType->name === $roomType)
                                                    <!-- Kiểm tra loại phòng -->
                                                    <span
                                                        class=" text-white p-2 me-2 rounded {{ $room->is_used ? 'bg-danger' : 'bg--primary' }}">
                                                        {{ $room->room->room_number }}
                                                        @if ($room->is_used)
                                                            <input form="confirmation-form" type="hidden"  name="roomCanNotAssign[]"
                                                                value="{{ $room->room->id }}">
                                                        @else
                                                            <input form="confirmation-form" type="hidden"  name="roomCanAssign[]"
                                                                value="{{ $room->room->id }}">
                                                        @endif
                                                    </span>
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @can('admin.request.booking.assign.room')
        <input form="confirmation-form" name="booking_request_id" type="hidden" value="{{ $bookingRequest->id }}">
        <div class=" mt-3" style="width: 10% !important">
            <button class="btn btn--primary w-100 h-45 btn-book confirmationBtn"
                data-action="{{ route('admin.request.booking.assign.room') }}" data-question="@lang('Bạn có chắc chắn muốn đặt những phòng này không?')"
                type="button">@lang('Xác nhận')</button>
        </div>
    @endcan
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.request.booking.all') }}" />
    {{-- <x-back route="{{ url()->previous() }}" /> --}}

@endpush

@push('style')
    <style>
        .removeItem {
            height: 23px;
            width: 30px;
            line-height: 0.5;
            margin-right: 5px;
            padding-right: 22px;
        }

        .booking-table td {
            white-space: unset;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            // console.log(roomListHtml);

            // $('.bookingInfo').html(roomListHtml);

            $('[name=paid_amount]').on('keypress', function(e) {
                if (e.keyCode === 13) {
                    e.preventDefault();
                    $('.confirmationBtn').click();
                }
            })
        })(jQuery);
    </script>
@endpush

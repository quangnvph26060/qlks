@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $userDate = session()->get('users_date');
    @endphp
    <section class="section">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-8">
                    <div class="room-details-head">
                        <div>
                            <h2 class="title">{{ __($roomType->name) }}</h2>
                            <div class="d-flex justify-content-center flex-wrap gap-3">
                                <span>
                                    @lang('Adult') &nbsp; {{ $roomType->total_adult }}
                                </span>

                                <span>
                                    @lang('Child') &nbsp; {{ $roomType->total_child }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <h2 class="text--base fare">{{ showAmount($roomType->fare) }}</h2>
                            <span class="text--small">+{{ gs()->tax }}% {{ __(gs()->tax_name) }}</span>
                            <span class="text--base text-sm"> / @lang('Night')</span>
                        </div>
                    </div>

                    <div class="room-details-thumb-slider">
                        @foreach ($roomType->images as $roomTypeImage)
                            <div class="single-slide">
                                <div class="room-details-thumb">
                                    <img alt="image" src="{{ getImage(getFilePath('roomTypeImage') . '/' . $roomTypeImage->image, getFileSize('roomTypeImage')) }}">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($roomType->images->count() > 1)
                        <div class="room-details-nav-slider mt-4">
                            @foreach ($roomType->images as $roomTypeImage)
                                <div class="single-slide">
                                    <div class="room-details-nav-thumb">
                                        <img alt="image" src="{{ getImage(getFilePath('roomTypeImage') . '/' . $roomTypeImage->image, getFileSize('roomTypeImage')) }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="room-details-card mt-4">
                        <h5 class="title">@lang('Description')</h5>
                        <div class="body"> @php echo $roomType->description;@endphp </div>
                    </div>

                    <div class="room-details-card mt-4">
                        <h5 class="title">@lang('Check-In Time & Checkout Time')</h5>

                        <div class="body">
                            <div class="d-inline-flex flex-md-row flex-column gap-md-5 flex-wrap gap-3">
                                <span class="me-2">
                                    <i class="las la-door-closed"></i> @lang('Check-In'):
                                    {{ showDateTime(gs()->checkin_time, 'H:i A') }}
                                </span>
                                <span class="me-2">
                                    <i class="las la-door-open"></i> @lang('Checkout'):
                                    {{ showDateTime(gs()->checkout_time, 'H:i A') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="room-details-card mt-4">
                        <h5 class="title">@lang('Cancellation Policy')</h5>

                        <div class="body">
                            @if ($roomType->cancellation_fee == 0)
                                <span> <i class="las la-check-double"></i> @lang('Free Cancellation')</span>
                            @else
                                <h5 class="text-center">@lang('Cancellation Fee') {{ showAmount($roomType->cancellation_fee) }} / @lang('Night')</h5>
                            @endif

                            <div class="mt-2">@php echo $roomType->cancellation_policy; @endphp</div>
                        </div>
                    </div>

                    @if ($roomType->amenities->count())
                        <div class="room-details-card mt-4">
                            <h5 class="title">@lang('Amenities')</h5>

                            <div class="body">
                                <div class="d-inline-flex flex-md-row flex-column gap-md-5 flex-wrap gap-3">
                                    @foreach ($roomType->amenities as $amenity)
                                        <span class="me-2">
                                            @php echo $amenity->icon @endphp
                                            {{ __($amenity->title) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($roomType->facilities->count())
                        <div class="room-details-card mt-4">
                            <h5 class="title">@lang('Facilities')</h5>
                            <div class="body">
                                <div class="d-inline-flex flex-md-row flex-column gap-md-5 flex-wrap gap-3">
                                    @foreach ($roomType->facilities as $facility)
                                        <span class="me-2">
                                            @php echo $facility->icon @endphp
                                            {{ __($facility->title) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($roomType->beds)
                        <div class="room-details-card mt-4">
                            <h5 class="title">@lang('Beds')</h5>
                            <div class="body">
                                <div class="d-inline-flex flex-md-row flex-column gap-md-5 flex-wrap gap-3">
                                    @foreach ($roomType->beds as $bed)
                                        <span class="me-2"><i class="las la-check-double"></i> {{ __($bed) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-4">
                    <input form="confirmation-form" hidden name="room_type_id" type="text" value="{{ $roomType->id }}">
                    <div class="room-booking-sidebar">
                        <div class="room-booking-widget">
                            <div class="room-booking-widget__body mt-0">
                                <div class="mb-3">
                                    <label class="fw-bold">@lang('Check-In')</label>
                                    <div class="custom-icon-field">
                                        <input autocomplete="off" class="check-in-date form--control" data-date-format="mm/dd/yyyy" data-language="en" data-multiple-dates-separator=" - " data-position='top left' data-range="false" form="confirmation-form" name="check_in" placeholder="@lang('Month/Date/Year')" type="text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="fw-bold">@lang('Check-Out')</label>
                                    <div class="custom-icon-field">
                                        <input autocomplete="off" class="check-out-date form--control" data-date-format="mm/dd/yyyy" data-language="en" data-multiple-dates-separator=" - " data-position='top left' data-range="false" form="confirmation-form" name="check_out" placeholder="@lang('Month/Date/Year')" type="text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="bookingLimitationMsg text--warning"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="fw-bold">@lang('Rooms')</label>
                                    <input class="form--control" form="confirmation-form" name="number_of_rooms" placeholder="@lang('Number of Rooms')" required type="number">
                                </div>

                                <div class="room-booking-widget__body">
                                    <ul class="room-booking-widget-list"></ul>
                                    <button class="btn btn--base w-100 confirmationBtn booking" data-action="{{ route('request.booking') }}" data-question="@lang('Are your sure, you want to book this room?')" type="button">@lang('SEND BOOKING REQUEST')</button>
                                </div>

                            </div><!-- room-booking-widget end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- room details section end -->
    <x-confirmation-modal />
@endsection

@push('style')
    <style>
        #confirmationModal button {
            padding: 0.375rem 0.625rem;
            font-size: 0.875rem;
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/datepicker.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/datepicker.en.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            let maxRoomBookingLimit = 0;
            let btnRequest = $('.confirmationBtn');
            btnRequest.attr('disabled', true);

            $('.booking').on('click', function() {
                let minCheckIn = $('[name=check_in]').val();
                let maxCheckOut = $('[name=check_out]').val();
                console.log(minCheckIn >= maxCheckOut);
                if (minCheckIn >= maxCheckOut) {
                    notify('error', 'Check in date must be grater than check out date!')
                    $(this).removeClass('confirmationBtn');
                } else {
                    $(this).addClass('confirmationBtn');
                }
            })


            var datepicker1 = $('.check-in-date').datepicker({
                autoClose: true
            });
            var datepicker2 = $('.check-out-date').datepicker({
                autoClose: true
            });

            @isset($userDate)
                var checkIn = @json($userDate['checkin']);
                var checkout = @json($userDate['check_out']);
                datepicker1.data('datepicker').selectDate(new Date(checkIn));
                datepicker2.data('datepicker').selectDate(new Date(checkout));
                getAvaliableRooms();
            @endisset

            $('.check-in-date, .check-out-date').on('focusout', function(e) {
                e.preventDefault();
                getAvaliableRooms();
            });

            function getAvaliableRooms() {
                let data = {};

                data.check_in = $('input[name=check_in]').val();
                data.check_out = $('input[name=check_out]').val();
                data.room_type_id = $('input[name=room_type_id]').val();

                $('[name=number_of_rooms]').val('');

                if (!data.check_in || !data.check_out) {
                    return;
                }

                $.ajax({
                    type: "get",
                    url: "{{ route('room.available.search') }}",
                    data: data,
                    success: function(response) {
                        let messageBox = $('.bookingLimitationMsg');
                        if (response.success) {
                            maxRoomBookingLimit = response.success;
                            messageBox.text(`@lang('You can book maximum ${response.success} rooms')`);
                            btnRequest.removeAttr('disabled');
                        } else {
                            notify('error', response.error);
                            messageBox.empty();
                            btnRequest.attr('disabled', true);
                        }
                    }
                });
            }

            $('[name=number_of_rooms]').on('input', function() {
                $('.confirmationBtn').attr('disabled', false);
                if ($(this).val() > maxRoomBookingLimit) {
                    btnRequest.attr('disabled', true);
                    notify('error', "Number of rooms can't be greater than maximum allowed room");
                }
            });

            $('.form--control').on('keypress', function(e) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    btnRequest.click();
                }
            })
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .main-wrapper {
            background-color: #fafafa
        }
    </style>
@endpush

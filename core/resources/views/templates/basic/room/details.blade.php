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
                            <h2 class="title">Phòng {{ __($room->room_number) }}</h2>
                            <div class="d-flex justify-content-center flex-wrap gap-3">
                                <span>
                                    @lang('Người lớn') &nbsp; {{ $room->total_adult }}
                                </span>

                                <span>
                                    @lang('Trẻ em') &nbsp; {{ $room->total_child }}
                                </span>
                            </div>
                        </div>

                        <div>
                            {{-- <h2 class="text--base fare">{{ showAmount($room->fare) }}</h2> --}}
                            {{-- <span class="text--small">+{{ gs()->tax }}% {{ __(gs()->tax_name) }}</span> --}}
                            {{-- <span class="text--base text-sm"> / @lang('Night')</span> --}}
                        </div>
                    </div>

                    @php
                        // Convert the collection to an array and merge with the main image
                        $imagesArray = array_merge([['image' => $room->main_image]], $room->images->toArray());
                    @endphp

                    <div class="room-details-thumb-slider">
                        @foreach ($imagesArray as $roomImage)
                            <div class="single-slide">
                                <div class="room-details-thumb">
                                    <img alt="image" src="{{ \Storage::url($roomImage['image']) }}">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($room->images->count() > 1)
                        <div class="room-details-nav-slider mt-4">
                            @foreach ($imagesArray as $roomImage)
                                <div class="single-slide">
                                    <div class="room-details-nav-thumb">
                                        <img alt="image" src="{{ \Storage::url($roomImage['image']) }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="room-details-card mt-4">
                        <h5 class="title">@lang('Description')</h5>
                        <div class="body"> @php echo $room->description;@endphp </div>
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
                            @if ($room->cancellation_fee == 0)
                                <span> <i class="las la-check-double"></i> @lang('Free Cancellation')</span>
                            @else
                                <h5 class="text-center">@lang('Cancellation Fee') {{ showAmount($room->cancellation_fee) }} /
                                    @lang('Night')</h5>
                            @endif

                            <div class="mt-2">@php echo $room->cancellation_policy; @endphp</div>
                        </div>
                    </div>

                    @if ($room->amenities->count())
                        <div class="room-details-card mt-4">
                            <h5 class="title">@lang('Amenities')</h5>

                            <div class="body">
                                <div class="d-inline-flex flex-md-row flex-column gap-md-5 flex-wrap gap-3">
                                    @foreach ($room->amenities as $amenity)
                                        <span class="me-2">
                                            @php echo $amenity->icon @endphp
                                            {{ __($amenity->title) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($room->facilities->count())
                        <div class="room-details-card mt-4">
                            <h5 class="title">@lang('Facilities')</h5>
                            <div class="body">
                                <div class="d-inline-flex flex-md-row flex-column gap-md-5 flex-wrap gap-3">
                                    @foreach ($room->facilities as $facility)
                                        <span class="me-2">
                                            @php echo $facility->icon @endphp
                                            {{ __($facility->title) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($room->beds)
                        <div class="room-details-card mt-4">
                            <h5 class="title">@lang('Beds')</h5>
                            <div class="body">
                                <div class="d-inline-flex flex-md-row flex-column gap-md-5 flex-wrap gap-3">
                                    @foreach ($room->beds as $bed)
                                        <span class="me-2"><i class="las la-check-double"></i> {{ __($bed) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-4">
                    <input form="confirmation-form" hidden name="room_id" type="text" value="{{ $room->id }}">
                    <div class="room-booking-sidebar">
                        <div class="room-booking-widget">
                            <div class="room-booking-widget__body mt-0">
                                <div class="mb-3">
                                    <label class="fw-bold">@lang('Ngày nhận')</label>
                                    <div class="custom-icon-field">
                                        <input autocomplete="off" class="check-in-date form--control"
                                            data-date-format="mm/dd/yyyy" data-language="en"
                                            data-multiple-dates-separator=" - " data-position='top left' data-range="false"
                                            name="check_in_1"
                                            form="confirmation-form" name="check_in" placeholder="@lang('Ngày/Tháng/Năm')"
                                            type="text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="fw-bold">@lang('Ngày trả')</label>
                                    <div class="custom-icon-field">
                                        <input autocomplete="off" class="check-out-date form--control"
                                            data-date-format="mm/dd/yyyy" data-language="en"
                                            data-multiple-dates-separator=" - " data-position='top left' data-range="false"
                                            name="check_out_1"
                                            form="confirmation-form" name="check_out" placeholder="@lang('Ngày/Tháng/Năm')"
                                            type="text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="bookingLimitationMsg text--warning"></div>
                                </div>

                                {{-- <div class="mb-3">
                                    <label class="fw-bold">@lang('Rooms')</label>
                                    <input class="form--control" form="confirmation-form" name="number_of_rooms"
                                        placeholder="@lang('Number of Rooms')" required type="number">
                                </div> --}}

                                <div class="room-booking-widget__body">
                                    <ul class="room-booking-widget-list"></ul>
                                    <button class="btn btn--base w-100 confirmationBtn booking"
                                        data-action="{{ route('request.booking') }}" data-question="@lang('Bạn có chắc chắn muốn đặt phòng này không?')"
                                        type="button">@lang('GỬI YÊU CẦU ĐẶT CHỖ')</button>
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

{{-- @push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/datepicker.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/datepicker.en.js') }}"></script>
@endpush --}}

@push('script')
    <script>
        (function($) {
            "use strict";
            let maxRoomBookingLimit = 0;
            let btnRequest = $('.confirmationBtn');
            // btnRequest.attr('disabled', true);

            $('.booking').on('click', function() {
                let minCheckIn = $('[name=check_in_1]').val();
                let maxCheckOut = $('[name=check_out_1]').val();

                console.log(minCheckIn + ' ' + maxCheckOut);
                if (minCheckIn >= maxCheckOut) {
                    notify('error', 'Ngày nhận phòng phải lớn hơn ngày trả phòng!')
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

                data.check_in = $('input[name=check_in_1]').val();
                data.check_out = $('input[name=check_out_1]').val();
                data.room_type_id = $('input[name=room_id]').val();

                // $('[name=number_of_rooms]').val('');

                if (!data.check_in || !data.check_out) {
                    return;
                }

                // $.ajax({
                //     type: "get",
                //     url: "{{ route('room.available.search') }}",
                //     data: data,
                //     success: function(response) {
                //         let messageBox = $('.bookingLimitationMsg');
                //         if (response.success) {
                //             maxRoomBookingLimit = response.success;
                //             messageBox.text(`@lang('Bạn có thể đặt tối đa ${response.success} phòng')`);
                //             btnRequest.removeAttr('disabled');
                //         } else {
                //             notify('error', response.error);
                //             messageBox.empty();
                //             btnRequest.attr('disabled', true);
                //         }
                //     }
                // });
            }

            // $('[name=number_of_rooms]').on('input', function() {
            //     $('.confirmationBtn').attr('disabled', false);
            //     if ($(this).val() > maxRoomBookingLimit) {
            //         btnRequest.attr('disabled', true);
            //         notify('error',
            //             "Số lượng phòng không được vượt quá số phòng tối đa được phép"
            //         ); //Number of rooms can't be greater than maximum allowed room
            //     }
            // });

            $('.form--control').on('keypress', function(e) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    btnRequest.click();
                }
            })
        })(jQuery);

        // route('')
    </script>
@endpush

@push('style')
    <style>
        .main-wrapper {
            background-color: #fafafa
        }
    </style>
@endpush

@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        @can('admin.room.search')
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.room.search') }}" class="formRoomSearch" method="get">
                            <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
                                <div class="form-group flex-fill">
                                    <label>@lang('Room Type')</label>
                                    <select class="form-control" name="room_type" required>
                                        <option value="">@lang('Select One')</option>
                                        @foreach ($roomTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group flex-fill">
                                    <label>@lang('Check In - Check Out Date')</label>
                                    <input autocomplete="off" class="bookingDatePicker form-control bg--white" name="date" placeholder="@lang('Select Date')" required type="text">
                                </div>
                                <div class="form-group flex-fill">
                                    <label>@lang('Room')</label>
                                    <input class="form-control" name="rooms" placeholder="@lang('How many room?')" required type="text">
                                </div>

                                <div class="form-group flex-fill">
                                    <button class="btn btn--primary w-100 h-45 search" type="submit">
                                        <i class="la la-search"></i>@lang('Search')
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        @endcan
    </div>
    <div class="row booking-wrapper d-none">
        <div class="col-lg-8 mt-3">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-flex justify-content-between booking-info-title mb-0">
                        <h5>@lang('Booking Information')</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="pb-3">
                        <span class="fas fa-circle text--danger" disabled></span>
                        <span class="mr-5">@lang('Booked')</span>
                        <span class="fas fa-circle text--success"></span>
                        <span class="mr-5">@lang('Selected')</span>
                        <span class="fas fa-circle text--primary"></span>
                        <span>@lang('Available')</span>
                    </div>
                    <div class="alert alert-info room-assign-alert p-3" role="alert">
                    </div>
                    <div class="bookingInfo">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mt-3">
            <div class="card">
                <div class="card-header">
                    <div class="card-title mb-0">
                        <h5>@lang('Book Room')</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.room.book') }}" class="booking-form" id="booking-form" method="POST">
                        @csrf
                        <input name="room_type_id" type="hidden">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>@lang('Guest Type')</label>
                                    <select class="form-control" name="guest_type">
                                        <option selected value="0">@lang('Walk-In Guest')</option>
                                        <option value="1">@lang('Existing Guest')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 guestInputDiv">
                                <div class="form-group">
                                    <label>@lang('Name')</label>
                                    <input class="form-control forGuest" name="guest_name" required type="text">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label>@lang('Email')</label>
                                    <input class="form-control" name="email" required type="email">
                                </div>
                            </div>

                            <div class="col-12 guestInputDiv">
                                <div class="form-group">
                                    <label>@lang('Phone Number')</label>
                                    <input class="form-control forGuest" name="mobile" required type="number">
                                </div>
                            </div>
                            <div class="col-12 guestInputDiv">
                                <div class="form-group">
                                    <label>@lang('Address')</label>
                                    <input class="form-control forGuest" name="address" required type="text">
                                </div>
                            </div>

                            <div class="orderList d-none">
                                <ul class="list-group list-group-flush orderItem">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <h6>@lang('Room')</h6>
                                        <h6>@lang('Days')</h6>
                                        <span>
                                            <h6>@lang('Fare')</h6>
                                        </span>
                                        <span>
                                            <h6>@lang('Subtotal')</h6>
                                        </span>
                                    </li>
                                </ul>
                                <div class="d-flex justify-content-between align-items-center border-top p-2">
                                    <span>@lang('Subtotal')</span>
                                    <span class="totalFare" data-amount="0"></span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center border-top p-2">
                                    <span>{{ gs()->tax_name }} <small>({{ gs()->tax }}%)</small></span>
                                    <span><span class="taxCharge" data-percent_charge="{{ gs()->tax }}"></span> {{ __(gs()->cur_text) }}</span>
                                    <input name="tax_charge" type="hidden">
                                </div>

                                <div class="d-flex justify-content-between align-items-center border-top p-2">
                                    <span>@lang('Total Fare')</span>
                                    <span class="grandTotalFare"></span>
                                    <input hidden name="total_amount" type="text">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label>@lang('Paying Amount')</label>
                                    <input class="form-control" min="0" name="paid_amount" placeholder="@lang('Paying Amount')" step="any" type="number">
                                </div>
                            </div>
                            @can('admin.room.book')
                                <div class="form-group mb-0">
                                    <button class="btn btn--primary h-45 w-100 btn-book confirmBookingBtn" type="button">@lang('Book Now')</button>
                                </div>
                            @endcan
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmBookingModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                    <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('Are you sure to book this rooms?')</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn--dark" data-bs-dismiss="modal" type="button">@lang('No')</button>
                    <button class="btn btn--primary btn-confirm" type="button">@lang('Yes')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@can('admin.booking.all')
    @push('breadcrumb-plugins')
        <a class="btn btn-sm btn--primary" href="{{ route('admin.booking.all') }}">
            <i class="la la-list"></i>@lang('All Bookings')
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

@push('style')
    <style>
        .booking-table td {
            white-space: unset;
        }

        .modal-open .select2-container {
            z-index: 9 !important;
        }
    </style>
@endpush

@push('script')
    <script>
        "use strict";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const start = moment();
        const end = moment().add(1, 'days');

        const dateRangeOptions = {
            minDate: start,
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment().add(1, 'days')],
                'Tomorrow': [moment().add(1, 'days'), moment().add(2, 'days')],
                'Next 3 Days': [moment(), moment().add(2, 'days')],
                'Next 7 Days': [moment(), moment().add(6, 'days')],
                'Next 15 Days': [moment(), moment().add(14, 'days')],
                'Next 30 Days': [moment(), moment().add(30, 'days')]
            }
        }


        const changeDatePickerText = (element, startDate, endDate) => {
            $(element).val(startDate.format('L') + ' - ' + endDate.format('L'));
        }

        $('.bookingDatePicker').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('.bookingDatePicker', start, end));
        $('.bookingDatePicker').on('apply.daterangepicker', (event, picker) => changeDatePickerText(event.target, picker.startDate, picker.endDate));



        $('[name=guest_type]').on('change', function() {
            if ($(this).val() == 1) {
                $('.guestInputDiv').addClass('d-none');
                $('.forGuest').attr("required", false);
            } else {
                $('.guestInputDiv').removeClass('d-none');
                $('.forGuest').attr("required", true);
            }
        });


        $('.formRoomSearch').on('submit', function(e) {
            e.preventDefault();

            let searchDate = $('[name=date]').val();
            if (searchDate.split(" - ").length < 2) {
                notify('error', `@lang('Check-In date and checkout date should be given for booking.')`);
                return false;
            }

            resetDOM();
            let formData = $(this).serialize();
            let url = $(this).attr('action');

            $.ajax({
                type: "get",
                url: url,
                data: formData,
                success: function(response) {
                    $('.bookingInfo').html('');
                    $('.booking-wrapper').addClass('d-none');
                    if (response.error) {
                        notify('error', response.error);
                    } else if (response.html.error) {
                        notify('error', response.html.error);
                    } else {
                        $('.bookingInfo').html(response.html);
                        let roomTypeId = $('[name=room_type]').val();
                        $('[name=room_type_id]').val(roomTypeId);
                        $('.booking-wrapper').removeClass('d-none');
                    }
                },
                processData: false,
                contentType: false,
            });
        });

        function resetDOM() {
            $(document).find('.orderListItem').remove();
            $('.totalFare').data('amount', 0);
            $('.totalFare').text(`0 {{ __(gs()->cur_text) }}`);
            $('.taxCharge').text('0');
            $('[name=tax_charge]').val('0');
            $('.grandTotalFare').text(`0 {{ __(gs()->cur_text) }}`);
            $('[name=total_amount]').val('0');
            $('[name=paid_amount]').val('');
            $('[name=room_type_id]').val('');
        }

        $(document).on('click', '.confirmBookingBtn', function() {
            var modal = $('#confirmBookingModal');
            modal.modal('show');
        });

        $('.btn-confirm').on('click', function() {
            $('#confirmBookingModal').modal('hide');
            $('.booking-form').submit();
        });

        $('.booking-form').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            let url = $(this).attr('action');
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                success: function(response) {
                    if (response.success) {
                        notify('success', response.success);
                        $('.bookingInfo').html('');
                        $('.booking-wrapper').addClass('d-none');
                        $(document).find('.orderListItem').remove();
                        $('.orderList').addClass('d-none');
                        $('.formRoomSearch').trigger('reset');
                    } else {
                        notify('error', response.error);
                    }
                },
            });
        });
        $('.select2-basic').select2({
            dropdownParent: $('.select2-parent')
        });
    </script>
@endpush

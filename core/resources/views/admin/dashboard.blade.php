@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-4">
        <div class="col-xxl-3 col-sm-6">
            <x-widget color="danger" icon="la la-sign-out transform-rotate-180" link="admin.delayed.booking.checkout" style="2" cover_cursor="1" overlay_icon="0" title="Delayed Checkout" value="{{ $widget['delayed_checkout'] }}" />
        </div>

        <div class="col-xxl-3 col-sm-6">
            <x-widget color="warning" icon="la la-sign-in" link="admin.pending.booking.checkin" style="2" cover_cursor="1" overlay_icon="0" title="Pending Check-In" value="{{ $widget['pending_checkin'] }}" />
        </div>

        <div class="col-xxl-3 col-sm-6">
            <x-widget color="info" icon="la la-sign-in" link="admin.upcoming.booking.checkin" style="2" cover_cursor="1" overlay_icon="0" title="Upcoming Check-In" value="{{ $widget['upcoming_checkin'] }}" />
        </div>

        <div class="col-xxl-3 col-sm-6">
            <x-widget color="info" icon="la la-sign-out transform-rotate-180" link="admin.upcoming.booking.checkout" style="2" cover_cursor="1" overlay_icon="0" title="Upcoming Checkout" value="{{ $widget['upcoming_checkout'] }}" />
        </div>

        <div class="col-xxl-3 col-sm-6">
            <x-widget color="dark" icon="la la-check-circle" icon_style="false" link="admin.booking.todays.booked" style="2" cover_cursor="1" overlay_icon="0" title="Today's Booked Rooms" value="{{ $widget['today_booked'] }}" />
        </div>

        <div class="col-xxl-3 col-sm-6">
            <x-widget color="info" icon="la la-hospital-alt" icon_style="false" link="admin.booking.todays.booked" query_string="type=not_booked" style="2" cover_cursor="1" overlay_icon="0" title="Today's Available Rooms" value="{{ $widget['today_available'] }}" />
        </div>

        <div class="col-xxl-3 col-sm-6">
            <x-widget color="success" icon="la la-clipboard-check" icon_style="false" link="admin.booking.active" style="2" cover_cursor="1" overlay_icon="0" title="Active Booking" value="{{ $widget['active'] }}" />
        </div>

        <div class="col-xxl-3 col-sm-6">
            <x-widget color="primary" icon="la la-city" icon_style="false" link="admin.booking.all" style="2" cover_cursor="1" overlay_icon="0" title="Total Bookings" value="{{ $widget['total'] }}" />
        </div>
    </div>

    <div class="row mb-none-30 mt-30">
        <div class="col-xl-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between">
                        <div class="d-flex justify-content-start align-items-center gap-1">
                            <h5 class="card-title mb-0">@lang('Booking Report')</h5> <span class="text--small fw-bold">(@lang('Excluding Tax'))</span>
                        </div>

                        <div id="bookingDatePicker" class="border p-1 cursor-pointer rounded">
                            <i class="la la-calendar"></i>&nbsp;
                            <span></span> <i class="la la-caret-down"></i>
                        </div>
                    </div>
                    <div id="bookingReportArea"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between">
                        <h5 class="card-title">@lang('Payment Report')</h5>

                        <div id="paymentDatePicker" class="border p-1 cursor-pointer rounded">
                            <i class="la la-calendar"></i>&nbsp;
                            <span></span> <i class="la la-caret-down"></i>
                        </div>
                    </div>

                    <div id="paymentReportArea"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-none-30 mt-30">
        <div class="col-xxl-3 col-sm-6">
            <x-widget bg="primary" icon="las la-users f-size--56" link="admin.users.all" title="Total Registered Guests" value="{{ $widget['total_users'] }}" />
        </div>

        <div class="col-xxl-3 col-sm-6">
            <x-widget bg="success" icon="las la-user-check f-size--56" link="admin.users.active" title="Active Registered Guests" value="{{ $widget['verified_users'] }}" />
        </div>

        <div class="col-xxl-3 col-sm-6">
            <x-widget bg="danger" icon="lar la-envelope f-size--56" link="admin.users.email.unverified" title="Email Unverified Guests" value="{{ $widget['email_unverified_users'] }}" />
        </div>

        <div class="col-xxl-3 col-sm-6">
            <x-widget bg="red" icon="las la-mobile-alt f-size--56" link="admin.users.mobile.unverified" title="Mobile Unverified Guests" value="{{ $widget['mobile_unverified_users'] }}" />
        </div>
    </div>

    <div class="row mb-none-30 mt-5">
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By Browser') (@lang('Last 30 days'))</h5>
                    <canvas id="userBrowserChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By OS') (@lang('Last 30 days'))</h5>
                    <canvas id="userOsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By Country') (@lang('Last 30 days'))</h5>
                    <canvas id="userCountryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/chart.js.2.8.0.js') }}"></script>
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/charts.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush

@push('script')
    <script>
        "use strict";

        const start = moment().subtract(14, 'days');
        const end = moment();

        const dateRangeOptions = {
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                'Last 30 Days': [moment().subtract(30, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Last 6 Months': [moment().subtract(6, 'months').startOf('month'), moment().endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
            },
            maxDate: moment()
        }

        const changeDatePickerText = (element, startDate, endDate) => {
            $(element).html(startDate.format('MMMM D, YYYY') + ' - ' + endDate.format('MMMM D, YYYY'));
        }

        let bookingReport = barChart(
            document.querySelector("#bookingReportArea"),
            @json(__(gs('cur_sym'))),
            [{
                name: 'Deposited',
                data: []
            }],
            [],
        );

        let paymentReport = lineChart(
            document.querySelector("#paymentReportArea"),
            [{
                    name: "Plus Transactions",
                    data: []
                },
                {
                    name: "Minus Transactions",
                    data: []
                }
            ],
            []
        );


        const bookingReportChart = (startDate, endDate) => {

            const data = {
                start_date: startDate.format('YYYY-MM-DD'),
                end_date: endDate.format('YYYY-MM-DD')
            }

            const url = @json(route('admin.chart.booking'));

            $.get(url, data,
                function(data, status) {
                    if (status == 'success') {
                        bookingReport.updateSeries(data.data);
                        bookingReport.updateOptions({
                            xaxis: {
                                categories: data.created_on,
                            }
                        });
                    }
                }
            );
        }

        const paymentReportChart = (startDate, endDate) => {

            const data = {
                start_date: startDate.format('YYYY-MM-DD'),
                end_date: endDate.format('YYYY-MM-DD')
            }

            const url = @json(route('admin.chart.payment'));


            $.get(url, data,
                function(data, status) {
                    if (status == 'success') {


                        paymentReport.updateSeries(data.data);
                        paymentReport.updateOptions({
                            xaxis: {
                                categories: data.created_on,
                            }
                        });
                    }
                }
            );
        }



        $('#bookingDatePicker').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('#bookingDatePicker span', start, end));
        $('#paymentDatePicker').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('#paymentDatePicker span', start, end));

        changeDatePickerText('#bookingDatePicker span', start, end);
        changeDatePickerText('#paymentDatePicker span', start, end);

        bookingReportChart(start, end);
        paymentReportChart(start, end);

        $('#bookingDatePicker').on('apply.daterangepicker', (event, picker) => bookingReportChart(picker.startDate, picker.endDate));
        $('#paymentDatePicker').on('apply.daterangepicker', (event, picker) => paymentReportChart(picker.startDate, picker.endDate));

        piChart(
            document.getElementById('userBrowserChart'),
            @json(@$chart['user_browser_counter']->keys()),
            @json(@$chart['user_browser_counter']->flatten())
        );

        piChart(
            document.getElementById('userOsChart'),
            @json(@$chart['user_os_counter']->keys()),
            @json(@$chart['user_os_counter']->flatten())
        );

        piChart(
            document.getElementById('userCountryChart'),
            @json(@$chart['user_country_counter']->keys()),
            @json(@$chart['user_country_counter']->flatten())
        );
    </script>
@endpush
@push('style')
    <style>
        .apexcharts-menu {
            min-width: 120px !important;
        }
    </style>
@endpush

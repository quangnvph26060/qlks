@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30 justify-content-center">
        <div class="col-lg-8 col-xl-6 mb-30">
            <div class="card">
                <div class="card-body p-sm-4 p-3">
                    <form action="{{ route('admin.premium.service.save') }}" class="add-service-form" method="POST">
                        @csrf
                        <div class="row append-service">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group flex-fill">
                                    <label>@lang('Service Date')</label>
                                    <input autocomplete="off" class="datePickerRange form-control" name="service_date" required type="text" value="{{ todaysDate() }}">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group flex-fill">
                                    <label>@lang('Room Number')</label>
                                    <input class="form-control" name="room_number" required type="text" value="{{ request()->room }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group d-flex align-items-end justify-content-between">
                            <label class="required">@lang('Services')</label>
                            <button class="btn btn--success addServiceBtn border-0" type="button">
                                <i class="las la-plus"></i>@lang('Add More')
                            </button>
                        </div>

                        <div class="service-wrapper">
                            <div class="first-service-wrapper">
                                <div class="d-flex service-item position-relative mb-3 flex-wrap">
                                    <select class="custom-select no-right-radius flex-fill w-50" name="services[]" required>
                                        <option value="">@lang('Select One')</option>
                                        @foreach ($premiumServices as $premiumService)
                                            <option value="{{ $premiumService->id }}">
                                                {{ __($premiumService->name) }} - {{ showAmount($premiumService->cost) }}/@lang('piece')
                                            </option>
                                        @endforeach
                                    </select>
                                    <input class="form-control w-unset flex-fill no-left-radius w-50" name="qty[]" placeholder="@lang('Quantity')" required type="text">
                                </div>
                            </div>
                        </div>
                        @can('admin.premium.service.save')
                            <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                        @endcan
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .no-right-radius {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        .no-left-radius {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
        }

        .removeServiceBtn {
            position: absolute;
            height: 25px;
            width: 25px;
            border-radius: 50%;
            text-align: center;
            line-height: 13px;
            font-size: 12px;
            right: -8px;
            top: -8px;
        }
    </style>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
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
        const dateRangeOptions = {
            startDate: start,
            maxDate: start,
            singleDatePicker: true,
            locale: {
                format: 'YYYY-MM-DD'
            }

        }
        const changeDatePickerText = (element, startDate, endDate) => {
            $(element).val(startDate.format('MMMM D, YYYY'));
        }
        $('.datePickerRange').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('.datePickerRange', start));
        $('#bookingDatePicker').on('apply.daterangepicker', (event, picker) => changeDatePickerText(picker.startDate));

        $('.addServiceBtn').on('click', function() {
            const content = `
            <div class="d-flex service-item position-relative mb-3 flex-wrap">
                <select class="custom-select no-right-radius flex-fill w-50" name="services[]" required>
                    <option value="">@lang('Select One')</option>
                    @foreach ($premiumServices as $premiumService)
                        <option value="{{ $premiumService->id }}">
                            {{ __($premiumService->name) }} - {{ showAmount($premiumService->cost) }}/@lang('piece')
                        </option>
                    @endforeach
                </select>
                <input class="form-control w-unset flex-fill no-left-radius w-50" name="qty[]" placeholder="@lang('Quantity')" required type="text">

                <button class="btn--danger removeServiceBtn border-0" type="button">
                    <i class="las la-times text--white"></i>
                </button>
            </div>`;

            $('.service-wrapper').append(content);
        });

        $(document).on('click', '.removeServiceBtn', function() {
            $(this).parents('.service-item').remove();

        });

        let serviceForm = $('.add-service-form');

        serviceForm.on('submit', function(e) {
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
                        let firstItem = $('.first-service-wrapper .service-item');
                        $(document).find('.service-wrapper').find('.service-item').not(firstItem).remove();
                        serviceForm.trigger("reset");
                    } else {
                        $.each(response.error, function(key, value) {
                            notify('error', value);
                        });
                    }
                },
            });
        });
    </script>
@endpush

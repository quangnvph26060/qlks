@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.fee.update') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group ">
                                    <label> @lang('Theo giờ')</label>
                                    <input class="form-control" id="per_hour"
                                        value="{{ number_format($fee->per_hour, 0, ',', '.') ?? 0 }}" type="text"
                                        name="per_hour">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group ">
                                    <label> @lang('Theo ngày')</label>
                                    <input class="form-control" id="per_day"
                                        value="{{ number_format($fee->per_day, 0, ',', '.') ?? 0 }}" type="text"
                                        name="per_day">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group ">
                                    <label> @lang('Theo đêm')</label>
                                    <input class="form-control" id="per_night"
                                        value="{{ number_format($fee->per_night, 0, ',', '.') ?? 0 }}" type="text"
                                        name="per_night">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group ">
                                    <label> @lang('Theo mùa')</label>
                                    <input class="form-control" id="per_season"
                                        value="{{ number_format($fee->per_season, 0, ',', '.') ?? 0 }}" type="text"
                                        name="per_season">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group ">
                                    <label> @lang('Theo sự kiện')</label>
                                    <input class="form-control" id="per_event"
                                        value="{{ number_format($fee->per_event, 0, ',', '.') ?? 0 }}" type="text"
                                        name="per_event">
                                </div>
                            </div>
                        </div>

                        @can('admin.setting.update')
                            <div class="form-group">
                                <button type="submit" class="btn btn--primary w-100 h-45">@lang('Lưu')</button>
                            </div>
                        @endcan
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">
@endpush
@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function formatFee(input) {
                let value = input.value.replace(/[^0-9]/g, '');
                value = new Intl.NumberFormat('vi-VN').format(value);
                input.value = value;
            }

            document.getElementById('per_hour').addEventListener('input', function() {
                formatFee(this);
            });
            document.getElementById('per_day').addEventListener('input', function() {
                formatFee(this);
            });
            document.getElementById('per_night').addEventListener('input', function() {
                formatFee(this);
            });
            document.getElementById('per_season').addEventListener('input', function() {
                formatFee(this);
            });
            document.getElementById('per_event').addEventListener('input', function() {
                formatFee(this);
            });

        });
    </script>
@endpush

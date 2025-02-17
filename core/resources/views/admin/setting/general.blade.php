@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.setting.update') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group ">
                                    <label> @lang('Tiêu đề trang web')</label>
                                    <input class="form-control" type="text" name="site_name" required value="{{ gs('site_name') }}">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group ">
                                    <label>@lang('Tiền tệ')</label>
                                    <input class="form-control" type="text" name="cur_text" required value="{{ gs('cur_text') }}">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group ">
                                    <label>@lang('Ký hiệu tiền tệ')</label>
                                    <input class="form-control" type="text" name="cur_sym"  value="{{ gs('cur_sym') }}">
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-6">
                                <label class="required"> @lang('Múi giờ')</label>
                                <select class="select2 form-control" name="timezone">
                                    @foreach ($timezones as $key => $timezone)
                                        <option value="{{ @$key }}" @selected(@$key == $currentTimezone)>{{ __($timezone) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4 col-sm-6">
                                <label class="required"> @lang('Màu cơ sở của trang web')</label>
                                <div class="input-group">
                                    <span class="input-group-text p-0 border-0">
                                        <input type='text' class="form-control colorPicker" value="{{ gs('base_color') }}">
                                    </span>
                                    <input type="text" class="form-control colorCode" name="base_color" value="{{ gs('base_color') }}">
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-6">
                                <label> @lang('Số bản ghi hiển thị trên mỗi trang')</label>
                                <select class="select2 form-control" name="paginate_number" data-minimum-results-for-search="-1" required>
                                    <option value="20" @selected(gs('paginate_number') == 20)>@lang('20 bản ghi một trang')</option>
                                    <option value="50" @selected(gs('paginate_number') == 50)>@lang('50 bản ghi một trang')</option>
                                    <option value="100" @selected(gs('paginate_number') == 100)>@lang('100 bản ghi một trang')</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4 col-sm-6 ">
                                <label> @lang('Định dạng hiển thị tiền tệ')</label>
                                <select class="select2 form-control" name="currency_format" data-minimum-results-for-search="-1" required>
                                    <option value="1" @selected(gs('currency_format') == Status::CUR_BOTH)>@lang('Hiển thị cả văn bản và ký hiệu tiền tệ')</option>
                                    <option value="2" @selected(gs('currency_format') == Status::CUR_TEXT)>@lang('Chỉ hiển thị văn bản tiền tệ')</option>
                                    <option value="3" @selected(gs('currency_format') == Status::CUR_SYM)>@lang('Chỉ hiển thị ký hiệu tiền tệ')</option>
                                </select>
                            </div>

                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Tên thuế')</label>
                                    <input class="form-control" name="tax_name" required type="text" value="{{ gs('tax_name') }}">
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Phí phần trăm thuế')</label>
                                    <div class="input-group">
                                        <input class="form-control" name="tax" required type="text" value="{{ number_format(gs('tax')) }}">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label> @lang('Giờ nhận phòng ngày')</label>
                                    <div class="input-group">
                                        <input autocomplete="off" class="form-control" name="checkin_time" placeholder="--:--" required type="time" value="{{ showDateTime(gs('checkin_time'), 'H:i') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label> @lang('Giờ trả phòng ngày')</label>
                                    <div class="input-group">
                                        <input autocomplete="off" class="form-control" name="checkout_time" placeholder="--:--" required type="time" value="{{ showDateTime(gs('checkout_time'), 'H:i') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Danh sách Check-in sắp tớ') <i class="las la-info-circle" title="@lang('The number of days of data you want to see in the upcoming checkin list.')"></i></label>
                                    <div class="input-group">
                                        <input class="form-control" name="upcoming_checkin_days" min="1" required type="numeric" value="{{ gs('upcoming_checkin_days') }}">
                                        <span class="input-group-text">@lang('Ngày')</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label> @lang('Giờ nhận phòng đêm')</label>
                                    <div class="input-group">
                                        <input autocomplete="off" class="form-control" name="checkin_time_night" placeholder="--:--" required type="time" value="{{ showDateTime(gs('checkin_time_night'), 'H:i') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label> @lang('Giờ trả phòng đêm')</label>
                                    <div class="input-group">
                                        <input autocomplete="off" class="form-control" name="checkout_time_night" placeholder="--:--" required type="time" value="{{ showDateTime(gs('checkout_time_night'), 'H:i') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Danh sách thanh toán sắp tới') <i class="las la-info-circle" title="@lang('The number of days of data you want to see in the upcoming checkout list.')"></i></label>
                                    <div class="input-group">
                                        <input class="form-control" name="upcoming_checkout_days" min="1" required type="numeric" value="{{ gs('upcoming_checkout_days') }}">
                                        <span class="input-group-text">@lang('Ngày')</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Đặt cọc') <i class="las la-info-circle" title="@lang('Phần trăm phải trả khi đặt cọc')"></i></label>
                                    <div class="input-group">
                                        <input class="form-control" name="deposit" min="1" type="numeric" value="{{ gs('deposit') }}">
                                        <span class="input-group-text">@lang('%')</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-4 col-sm-6">
                                <label>@lang('Mô hình')</label>
                                <select class="select2 form-control" name="type" data-minimum-results-for-search="-1" required>
                                    <option value="Nhà nghỉ" @selected(gs('type') == 'Nhà nghỉ')>@lang('Nhà nghỉ')</option>
                                    <option value="Khách sạn" @selected(gs('type') == 'Khách sạn')>@lang('Khách sạn')</option>
                                    <option value="Resort" @selected(gs('type') == 'Resort')>@lang('Resort')</option>
                                </select>
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
        (function($) {
            "use strict";


            $('.colorPicker').spectrum({
                color: $(this).data('color'),
                change: function(color) {
                    $(this).parent().siblings('.colorCode').val(color.toHexString().replace(/^#?/, ''));
                }
            });

            $('.colorCode').on('input', function() {
                var clr = $(this).val();
                $(this).parents('.input-group').find('.colorPicker').spectrum({
                    color: clr,
                });
            });
        })(jQuery);
    </script>
@endpush

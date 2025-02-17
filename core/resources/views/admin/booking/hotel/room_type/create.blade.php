@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.hotel.room.type.save', @$roomType ? $roomType->id : 0) }}" enctype="multipart/form-data" method="POST">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            @lang('Thông tin chung')
                        </h5>
                    </div>
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-lg-6 col-md-4">
                                <div class="form-group">
                                    <label>@lang('Name')</label>
                                    <input class="form-control" name="name" required type="text" value="{{ old('name', @$roomType->name) }}">
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-4">
                                <div class="form-group">
                                    <label>@lang('Tổng số người')</label>
                                    <input class="form-control" min="1" name="total_adult" required type="number" value="{{ old('total_adult', @$roomType->total_adult) }}">
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-4">
                                <div class="form-group">
                                    <label>@lang('Tổng số giường')</label>
                                    <input class="form-control" min="0" name="total_child" required type="number" value="{{ old('total_child', @$roomType->total_child) }}">
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-4">
                                <div class="form-group">
                                    <label class="required" for="fare">@lang('Phí') /@lang('Đêm')</label>
                                    <div class="input-group">
                                        <input class="form-control" id="fare" name="fare" required type="number" value="{{ old('fare', @$roomType->fare ? getAmount(@$roomType->fare) : '') }}">
                                        <span class="input-group-text">{{ __(@$general->cur_text) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-4">
                                <div class="form-group">
                                    <label>@lang('Phí hủy') /@lang('Đêm')</label>
                                    <div class="input-group">
                                        <input class="form-control cancellationFee" min="0" name="cancellation_fee" required step="any" type="number" value="{{ @$roomType->cancellation_fee }}">
                                        <span class="input-group-text">{{ __($general->cur_text) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-4">
                                <div class="form-group position-relative">
                                    <label> @lang('Tiện ích')</label>
                                    <select class="select2-multi-select" multiple="multiple" name="amenities[]">
                                        @foreach ($amenities as $item)
                                            <option value="{{ $item->id }}">{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-4">
                                <div class="form-group position-relative">
                                    <label> @lang('Tiện nghi')</label>
                                    <select class="select2-multi-select" multiple="multiple" name="facilities[]">
                                        @foreach ($facilities as $item)
                                            <option value="{{ $item->id }}">{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-4">
                                <div class="form-group position-relative">
                                    <label>@lang('Từ khóa')</label>
                                    <select class="form-control select2-auto-tokenize" multiple="multiple" name="keywords[]"></select>
                                    <small class="ml-2 mt-2">@lang('Phân tách nhiều từ khóa bằng') <code>,</code>(@lang('dấu phẩy'))
                                        @lang('hoặc') <code>@lang('enter')</code> @lang('key')</small>
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-4">
                                <div class="form-group">
                                    <label> @lang('Tính năng') </label>
                                    <input @if (@$roomType->is_featured) checked @endif data-bs-toggle="toggle" data-height="50" data-off="@lang('Không nổ bật')" data-offstyle="-danger" data-on="@lang('Nổi bật')" data-onstyle="-success" data-size="large" data-width="100%" name="is_featured" type="checkbox">
                                    <small class="ml-2 mt-2"><code><i class="las la-info-circle"></i> @lang('Phòng nổi bật sẽ được hiển thị ở mục "Phòng nổi bật".')</code></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            @lang('Số lượng giường một phòng')
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row d-flex justify-content-center mb-3">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <h4 class="mb-1">@lang('Tổng số giường')</h4>
                                    <input @isset($roomType) readonly @endisset class="form-control" min="1" name="total_bed" required type="number" value="{{ @$roomType ? count(@$roomType->beds) : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="bed">
                            @isset($roomType)
                                <div class="row border-top pt-3">
                                    @foreach ($roomType->beds as $bed)
                                        <div class="col-md-3 number-field-wrapper bed-content">
                                            <div class="form-group">
                                                <label class="required" for="bed">@lang('Giường') - <span class="serialNumber">{{ $loop->iteration }}</span></label>
                                                <div class="input-group">
                                                    <select class="form-control bedType" name="bed[{{ $loop->iteration }}]">
                                                        <option value="">@lang('Chọn một')</option>
                                                        @foreach ($bedTypes as $bedType)
                                                            <option @if ($bedType->name == $bed) selected @endif value="{{ $bedType->name }}">
                                                                {{ $bedType->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button class="input-group-text bg-danger btnRemove border-0" data-name="bed" type="button"><i class="las la-times"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button class="btn btn--success addMore" type="button"> <i class="la la-plus"></i>@lang('Thêm')</button>
                            @endisset
                        </div>
                    </div>
                </div>

                <div class="row gy-3 mt-0">
                    <div class="col-xxl-4 col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    @lang('Ảnh chính')
                                </h5>
                            </div>
                            <div class="card-body">
                                <x-image-uploader name="main_image" class="w-100" type="roomTypeImage" :image="@$roomType->main_image" :required="@$roomType ? false : true" />
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-8 col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    @lang('Mô tả')
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea class="nicEdit" id="description" name="description" rows="11">@php echo @$roomType->description ?? old('description') @endphp</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            @lang('Hình ảnh')
                        </h5>

                        <small class="text--info fs-12"><i class="las la-info-circle"></i> @lang('Mỗi hình ảnh sẽ được điều chỉnh kích cỡ thành') {{ getFileSize('roomTypeImage') }}@lang('px')</small>
                    </div>
                    <div class="card-body">
                        <div class="input-images pb-3"></div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            @lang('Chính sách hủy đặt phòng')
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <textarea class="nicEdit" name="cancellation_policy" rows="6">@php echo old('cancellation_policy', @$roomType->cancellation_policy) @endphp</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @can('admin.hotel.room.type.save')
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-0">
                                        <button class="btn btn--primary w-100 h-45" type="submit">@lang('Xác nhận')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
            </form>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    @if (@$roomType)
        <a href="{{ route('room.type.details', [$roomType->id, slug($roomType->name)]) }}" class="btn btn-sm btn-outline--dark" target="_blank"><i class="las la-eye"></i>@lang('Xem nhanh')</a>
    @endif
    @can('admin.hotel.room.type.all')
        <x-back route="{{ route('admin.hotel.room.type.all') }}" />
    @endcan
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/image-uploader.min.js') }}"></script>
@endpush

@push('style-lib')
    <link href="{{ asset('assets/global/css/image-uploader.min.css') }}" rel="stylesheet">
@endpush

@push('style')
    <style>
        .image-upload-wrapper {
            height: 272px;
        }

        .fs-12 {
            font-size: 12px !important;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            let bedTypes = @json($bedTypes);


            @if (isset($images))
                let preloaded = @json($images);
            @else
                let preloaded = [];
            @endif

            $('.input-images').imageUploader({
                preloaded: preloaded,
                imagesInputName: 'images',
                preloadedInputName: 'old',
                maxSize: 2 * 1024 * 1024,
                maxFiles: 6
            });

            initSelect2MultiSelect();
            initSelect2AutoTokenize();


            // room js
            $('[name=total_room]').on('input', function() {
                var totalRoom = $(this).val();
                if (totalRoom) {
                    let content = '<div class="row border-top pt-3">';
                    for (var i = 1; i <= totalRoom; i++) {
                        content += getRoomContent(i);
                    }
                    content += '</div>';
                    $('.room').html(content);
                }
            });

            function getRoomContent(number) {
                return `
                <div class="col-md-3 number-field-wrapper room-content">
                    <div class="form-group">
                        <label for="room" class="required">@lang('Phòng') - <span class="serialNumber">${number}</span></label>
                        <div class="input-group">
                            <input type="text" name="room[]" class="form-control roomNumber" required>
                            <button type="button" class="input-group-text bg-danger border-0 btnRemove" data-name="room"><i class="las la-times"></i></button>
                        </div>
                    </div>
                </div>`;
            }


            function setTotalRoom() {
                var totalRoom = $('.roomNumber').length;
                console.log(totalRoom);
                $('[name=total_room]').val(totalRoom);
            }

            //bed js
            $('[name=total_bed]').on('input', function() {
                var totalBed = $(this).val();
                if (totalBed) {
                    let content = '<div class="row border-top pt-3">';
                    for (var i = 1; i <= totalBed; i++) {
                        content += getBedContent(i);
                    }
                    content += '</div>';
                    $('.bed').html(content);
                }
            });

            function getBedContent(number) {
                return `
                    <div class="col-md-3 number-field-wrapper bed-content">
                        <div class="form-group">
                            <label for="bed" class="required">@lang('Giường') - <span class="serialNumber">${number}</span></label>
                            <div class="input-group"><select class="form-control bedType" name="bed[${number}]">
                                        <option value="">@lang('Chọn một')</option>
                                        ${allBedType()}
                                    </select><button type="button" class="input-group-text bg-danger border-0 btnRemove" data-name="bed"><i class="las la-times"></i></button>
                            </div>
                        </div>
                    </div>`;
            }

            function setTotalBed() {
                var totalBed = $('.bedType').length;
                $('[name=total_bed]').val(totalBed);
            }

            function allBedType() {
                var options;
                $.each(bedTypes, function(i, e) {
                    options += `<option value="${e.name}">${e.name}</option>`;
                });
                return options;
            }


            //common js
            $('[name=total_bed]').on('input', function() {
                var totalBed = $(this).val();
                if (totalBed) {
                    let content = '<div class="row border-top pt-3">';
                    for (var i = 1; i <= totalBed; i++) {
                        content += getBedContent(i);
                    }
                    content += '</div>';
                    $('.bed').html(content);
                }
            });

            $(document).on('click', '.btnRemove', function() {
                $(this).closest('.number-field-wrapper').remove();
                let divName = null;
                if ($(this).data('name') == 'bed') {
                    setTotalBed();
                    divName = $('.bed-content').find('.serialNumber');
                } else {
                    divName = $('.room-content').find('.serialNumber');
                    setTotalRoom();
                }
                resetSerialNumber(divName);
            });

            function resetSerialNumber(divName) {
                $.each(divName, function(i, e) {
                    $(e).text(i + 1)
                });
            }

            $('.addMore').on('click', function() {
                if ($(this).parents().hasClass('room')) {
                    var total = $('.roomNumber').length;
                    total += 1;

                    $('.room .row').append(getRoomContent(total));
                    setTotalRoom();
                    return;
                }

                var total = $('.bedType').length;
                total += 1;

                $('.bed .row').append(getBedContent(total));
                setTotalBed();
            });

            // Edit part
            let roomType = @json(@$roomType);
            if (roomType) {
                $.each(roomType.amenities, function(i, e) {
                    $(`select[name="amenities[]"] option[value=${e.id}]`).prop('selected', true);
                });

                $.each(roomType.facilities, function(i, e) {
                    $(`select[name="facilities[]"] option[value=${e.id}]`).prop('selected', true);
                });

                initSelect2MultiSelect();

                var keyword = $('select[name="keywords[]"]');
                keyword.html('');

                let options = '';

                $.each(roomType.keywords, function(index, value) {
                    options += `<option value="${value}" selected>${value}</option>`
                });



                keyword.append(options);
                initSelect2AutoTokenize(keyword);
            }

            var dropdownParent;

            function initSelect2MultiSelect(selector = null) {

                if (selector) {
                    dropdownParent = $(selector).parents('.form-group');

                    $(selector).select2({
                        dropdownParent: dropdownParent
                    });
                } else {
                    $.each($('.select2-multi-select'), function(indexInArray, selector) {
                        dropdownParent = $(selector).parents('.form-group');
                        $(selector).select2({
                            dropdownParent: dropdownParent
                        });
                    });
                }
            }

            function initSelect2AutoTokenize(selector = null) {

                if (selector) {
                    dropdownParent = $(selector).parents('.form-group');

                    $(selector).select2({
                        dropdownParent: dropdownParent
                    });
                } else {
                    $.each($('.select2-auto-tokenize'), function(indexInArray, selector) {
                        dropdownParent = $(selector).parents('.form-group');
                        $(selector).select2({
                            dropdownParent: dropdownParent,
                            tags: true,
                            tokenSeparators: [',']
                        });
                    });
                }
            }
        })(jQuery);
    </script>
@endpush

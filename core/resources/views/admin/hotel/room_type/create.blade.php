@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.hotel.room.type.save', @$roomType ? $roomType->id : 0) }}"
                enctype="multipart/form-data" method="POST">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            @lang('Thông tin chung')
                        </h5>
                    </div>
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label>@lang('Loại phòng')</label>
                                    <select class="form-control" name="room_type_id" required>
                                        <option disabled selected value="">@lang('Chọn loại phòng')</option>
                                        @foreach ($roomTypes as $id => $name)
                                            <option @selected(old('room_type_id', @$roomType->room_type_id) == $id) value="{{ $id }}">
                                                {{ __($name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label>@lang('Mã phòng')</label>
                                    <input class="form-control" name="code"  placeholder="Mã phòng" type="text"
                                        value="{{ old('code', @$roomType->code) }}">
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label>@lang('Tên phòng')</label>
                                    <input class="form-control" placeholder="Tên phòng" name="room_number" type="text"
                                        value="{{ old('room_number', @$roomType->room_number) }}">
                                </div>
                            </div>

                            {{-- <div class="col-xl-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label>@lang('Phí hủy bỏ') /@lang('Đêm')</label>
                                    <div class="input-group">
                                        <input class="form-control cancellationFee" min="0" name="cancellation_fee"
                                            required step="any" type="number"
                                            value="{{ old('cancellation_fee', getAmount(@$roomType->cancellation_fee)) }}">
                                        <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                    </div>
                                </div>
                            </div> --}}


                            {{-- <div class="col-xl-6 col-md-4 col-sm-12">
                                @php
                                    if (empty($selectedPrices)) {
                                        $selectedPrices = [];
                                    }
                                @endphp
                                <div class="form-group">
                                    <label for="">Chọn giá</label>
                                    <select class="select2-multi-select" multiple="multiple" name="prices[]">
                                        @foreach ($prices as $id => $name)
                                            <option value="{{ $id }}"
                                                @if (in_array($id, $selectedPrices)) selected @endif>
                                                {{ $name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div> --}}
                            {{-- <div class="col-xl-6 col-md-12">
                                <div class="form-group position-relative">
                                    <label> @lang('Tiện nghi')</label>
                                    <select class="select2-multi-select" multiple="multiple" name="amenities[]">
                                        @foreach ($amenities as $item)
                                            <option value="{{ $item->id }}">{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-12">
                                <div class="form-group position-relative">
                                    <label> @lang('Cở sở')</label>
                                    <select class="select2-multi-select" multiple="multiple" name="facilities[]">
                                        @foreach ($facilities as $item)
                                            <option value="{{ $item->id }}">{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}

                            {{-- <div class="col-xl-6 col-md-12">
                                <div class="form-group position-relative">
                                    <label>@lang('Từ khóa')</label>
                                    <select class="form-control select2-auto-tokenize" multiple="multiple"
                                        name="keywords[]"></select>
                                    <small class="ml-2 mt-2">@lang('Phân tách nhiều từ khóa bằng') <code>,</code>(@lang('dấu phẩy'))
                                        @lang('hoặc') <code>@lang('enter')</code> @lang('key')</small>
                                </div>
                            </div> --}}


                        </div>
                        <div class="row">
                            {{-- <div class="col-xl-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label>@lang('Số phòng')</label>

                                    <div class="d-flex align-items-center qty-input">
                                        <button class="btn btn-light qty-count qty-count--minus border-end"
                                            data-action="minus" type="button">-</button>
                                        <input class="form-control text-center product-qty border-0" type="number"
                                            name="room_number" min="1" max="100"
                                            value="{{ old('room_number', @$roomType->room_number ?? 1) }}" readonly
                                            required>
                                        <button class="btn btn-light qty-count qty-count--add border-start"
                                            data-action="add" type="button">+</button>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="col-xl-3 col-md-3 col-6">
                                {{-- <div class="form-group">
                                    <label>@lang('Tổng số người')</label>
                                    <input class="form-control" min="1" name="total_adult" required type="number"
                                        value="{{ old('total_adult', @$roomType->total_adult) }}">
                                </div> --}}
                                <label>@lang('Tổng số người')</label>
                                <div class="d-flex align-items-center qty-input">
                                    <button class="btn btn-light qty-count qty-count--minus border-end" data-action="minus" style="padding: 0px !important;"
                                        type="button">-</button>
                                    <input class="form-control text-center product-qty border-0" type="number" style="height: 30px !important; padding: 0px !important;"
                                        name="total_adult" min="1" max="100"
                                        value="{{ old('total_adult', @$roomType->total_adult ?? 1) }}"  required>
                                    <button class="btn btn-light qty-count qty-count--add border-start" data-action="add" style="padding: 0px !important;"
                                        type="button">+</button>
                                </div>

                            </div>

                            <div class="col-xl-3 col-md-3 col-6">
                                <div class="form-group">
                                    <label>@lang('Tống số giường')</label>
                                    {{-- <input class="form-control" min="0" name="total_child" required type="number"
                                        value="{{ old('total_child', @$roomType->total_child) }}"> --}}
                                    <div class="d-flex align-items-center qty-input">
                                        <button class="btn btn-light qty-count qty-count--minus border-end" style="padding: 0px !important;"
                                            data-action="minus" type="button">-</button>
                                        <input class="form-control text-center product-qty border-0" type="number" style="height: 30px !important; padding: 0px !important;"
                                            name="beds" min="1" max="100"
                                            value="{{ old('beds', @$roomType->beds ?? 1) }}" readonly
                                            required>
                                        <button class="btn btn-light qty-count qty-count--add border-start" style="padding: 0px !important;"
                                            data-action="add" type="button">+</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-xl-6 col-md-12">
                                <div class="form-group">
                                    <label class="me-2"> @lang('Nổi bật') </label>
                                    <input @if (@$roomType->is_featured) checked @endif data-bs-toggle="toggle" data-height="50" data-off="@lang('Không có đặc điểm')" data-offstyle="-danger" data-on="@lang('Featured')" data-onstyle="-success" data-size="large" data-width="100%" name="is_featured" type="checkbox">
                                    <input type="radio" name="is_featured" class="form-check-input" id="is_featured"
                                        value="1"  {{ @$roomType->is_featured  == 1 ? 'checked' : '' }}> <label class="form-check-label me-2"
                                        for="is_featured">@lang('Yes')</label>
                                    <input type="radio" name="is_featured" class="form-check-input" id="is_featured-2"
                                        value="0" {{ @$roomType->is_featured  == 0 ? 'checked' : '' }} > <label class="form-check-label me-2"
                                        for="is_featured-2">@lang('No')</label>
                                    <p class="ml-2 mt-2"><code><i class="las la-info-circle"></i>@lang('Các phòng nổi bật sẽ được hiển thị trong phần phòng nổi bật.')</code></p>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>

                {{-- <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">@lang('Thêm Sản Phẩm')</h5>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            Thực hiện
                        </button>
                    </div>
                    <div class="card-body results">
                        <div class="row">

                            @foreach ($roomType->products ?? [] as $product)
                                <div class="col-md-4 pb-3 result-item" data-id="{{ $product->id }}}">
                                    <div class="d-flex align-items-center border position-relative p-1"
                                        data-id="{{ $product->id }}">
                                        <div class="img-container">
                                            <img src="{{ \Storage::url($product->image_path) }}" width="100"
                                                alt="" class="img-fluid">
                                        </div>
                                        <div class="info">
                                            <div class="name ellipsis">
                                                <a href="#" class="text-decoration-none">{{ $product->name }}</a>
                                            </div>
                                            <div class="price">
                                                <span class="current-price">Tồn kho: {{ $product->stock ?? 0 }}</span>
                                            </div>
                                            <div class="quantity d-flex align-items-center">
                                                <span class="current-stock me-2">Số lượng</span>
                                                <input type="number" name="products[{{ $product->id }}]"
                                                    class="form-control" min="1"
                                                    value="{{ $product->pivot->quantity }}">
                                            </div>
                                        </div>
                                        <button class="btn-close position-absolute end-0 top-0 bg-danger"
                                            style="border-radius: 0%"></button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            @lang('Giường Mỗi Phòng')
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row d-flex justify-content-center mb-3">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <h4 class="mb-1">@lang('Tổng số giường')</h4>
                                    <input @isset($roomType) readonly @endisset class="form-control"
                                        min="1" name="total_bed" required type="number"
                                        value="{{ @$roomType ? count(@$roomType->beds) : '' }}">
                                    <div class="d-flex align-items-center qty-input">
                                        <button class="btn btn-light qty-count qty-count--minus border-end decrement-bed"
                                            data-action="minus" type="button">-</button>
                                        <input class="form-control text-center product-qty border-0" type="number"
                                            name="total_bed" max="100"
                                            value="{{ @$roomType ? count(@$roomType->beds) : 0 }}"
                                            @isset($roomType) readonly @endisset required>
                                        <button class="btn btn-light qty-count qty-count--add border-start increment-bed"
                                            data-action="add" type="button">+</button>
                                    </div>
                                    <div class="quantity-container-bed d-flex align-items-center qty-input">
                                        <button id="decrease-bed"
                                            class="btn btn-light qty-bed border-end"
                                            type="button">-</button>
                                        <input type="number" @isset($roomType) readonly @endisset
                                            name="total_bed" id="quantity-bed"
                                            class="form-control text-center product-qty border-0"
                                            value="{{ @$roomType ? count(@$roomType->beds) : 0 }}" readonly
                                            min="0" />
                                        <button id="increase-bed"
                                            class="btn btn-light qty-bed border-start"
                                            type="button">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bed d-flex flex-wrap justify-content-start" id="bed">
                            @isset($roomType)
                                <div class="row border-top pt-3">
                                    @foreach ($roomType->beds as $bed)
                                        <div class="col-md-3 number-field-wrapper bed-content">
                                            <div class="form-group">
                                                <label class="required" for="bed">@lang('Bed') - <span
                                                        class="serialNumber">{{ $loop->iteration }}</span></label>
                                                <div class="input-group">
                                                    <select class="form-control bedType" name="bed[{{ $loop->iteration }}]">
                                                        <option value="">@lang('Select One')</option>
                                                        @foreach ($bedTypes as $bedType)
                                                            <option @if ($bedType->name == $bed) selected @endif
                                                                value="{{ $bedType->name }}">
                                                                {{ $bedType->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button class="input-group-text bg-danger btnRemove border-0"
                                                        data-name="bed" type="button"><i class="las la-times"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button class="btn btn--success addMore" type="button"> <i
                                        class="la la-plus"></i>@lang('Add More')</button>
                            @endisset
                        </div>
                    </div>
                </div> --}}

                <div class="row gy-3 mt-0">
                    <div class="col-xxl-4 col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    @lang('Hình ảnh chính')
                                </h5>
                            </div>
                            <div class="card-body">
                                <x-image-uploader name="main_image" class="w-100" type="roomTypeImage" :image="@$roomType->main_image"
                                    :required="@$roomType ? false : true" />
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
                                <textarea class="nicEdit" id="description" name="description" rows="13">@php echo @$roomType->description ?? old('description') @endphp</textarea>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            @lang('Hình ảnh')
                        </h5>

                        <small class="text--info text--small"><i class="las la-info-circle"></i> @lang('Mỗi hình ảnh sẽ được thay đổi kích thước thành')
                            {{ getFileSize('roomTypeImage') }}@lang('px')</small>
                    </div>
                    <div class="card-body">
                        <div class="input-images pb-3"></div>
                    </div>
                </div> --}}

                {{-- <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            @lang('Chính sách hủy bỏ')
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
                </div> --}}
                <div class="card mt-3">
                    {{-- <div class="card-header">
                        <h5 class="card-title mb-0">
                            @lang('Trạng thái')
                        </h5>
                    </div> --}}
                    <div class="card-body">
                        <div class="row d-flex     flex-column">
                            {{-- <div class="radio-container">
                                <label class="toggle">
                                    <input type="checkbox" name="status" class="status-change"
                                        @checked(@$roomType->status == 0)>
                                    <span class="slider"></span>
                                </label>
                            </div> --}}
                            <div class="col-md-1" style=" margin-top: 18px; ">
                                <h5 class="card-title mb-0">
                                    @lang('Trạng thái')
                                </h5>
                            </div>
                            <div class="col-md-2" style="margin-top: 7px;">
                                <div class="form-group">
                                    <input type="checkbox" data-width="80%" data-size="large"
                                           data-onstyle="-success" data-offstyle="-danger"
                                           data-bs-toggle="toggle" data-height="35"
                                           data-on="@lang('Enable')" data-off="@lang('Disable')"
                                           name="status" @checked(old('status', @$roomType->status) == 1)>
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

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-block">
                    <div class="search-container d-flex align-items-center">
                        <input type="search" name="searchInput" class="form-control searchInput"
                            placeholder="Tìm kiếm sản phẩm..." autocomplete="off">
                    </div>
                </div>
                <div class="modal-body">
                    <div class="search-results">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    @if (@$roomType)
        <a href="{{ route('room.type.details', $roomType->room_number) }}" class="btn btn-sm btn-outline--dark"
            target="_blank"><i class="las la-eye"></i>@lang('Quick View')</a>
    @endif
    @can('admin.hotel.room.type.all')
        <x-back route="{{ route('admin.hotel.room.type.all') }}" />
    @endcan
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/image-uploader.min.js') }}"></script>
@endpush

@push('style-lib')
    <link href="{{ asset('assets/admin/css/image-uploader.min.css') }}" rel="stylesheet">
@endpush

@push('style')
    <style>
        .info {
            margin-left: 5px;
        }


        .cursor-pointer {
            cursor: pointer;
            user-select: none;
        }

        .searchInput {
            height: 30px;
            border: 1px solid #ddd;
        }

        /* Ẩn nút tăng giảm */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .quantity input {
            text-align: center;
            width: 50px;
            height: 30px;
            padding: 0;
        }
    </style>
    <style>
        .radio-container {
            display: flex;

        }

        .toggle {
            position: relative;
            display: inline-block;
            width: 52px;
            height: 29px;
        }

        .toggle input {
            display: none;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #4CAF50;
        }

        input:checked+.slider:before {
            transform: translateX(24px);
        }

        .label {
            margin-left: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        .status-input {
            margin-bottom: 20px;
        }

        .status-input label {
            font-weight: bold;
            margin-right: 10px;
        }

        .radio-group {
            display: flex;
            align-items: center;
        }

        .radio-group input[type="radio"] {
            margin-right: 5px;
            accent-color: #007bff;
        }

        .radio-group label {
            margin-right: 20px;
            font-size: 16px;
        }

        .form-check-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .form-check {
            margin-right: 15px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-right: 10px;
        }

        .form-check-label {
            /* font-size: 18px; */
            line-height: 25px;
        }
    </style>
    <style>
        .qty-input {
            border: 1px solid #ccc;
            width: 140px;
            border-radius: 3px;
        }
        .qty-input .product-qty {
            width: 60px;
            -moz-appearance: textfield;
            background-color: white;

        }

        .qty-input .product-qty::-webkit-outer-spin-button,
        .qty-input .product-qty::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .qty-input .qty-count , .qty-bed {
            font-size: 1.25rem;
            width: 2.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: white
        }
    </style>
@endpush


@push('script')
    <script>
        //Chuyển mọi ký tự trong input room_type_id thành uppercased
        $(document).ready(function() {
            $('input[name="room_type_id"]').on('input', function() {
                this.value = this.value.toUpperCase();
            });
        });

        (function($) {
            "use strict";
            let bedTypes = @json($bedTypes);
            let debounceTimer;
            let path_url = "http://qlks.test/storage/";

            $('.search-results').on('click', '.result-item', function() {
                var resource = $(this).data('resource'); // Lấy dữ liệu từ data-resource

                // Kiểm tra xem sản phẩm đã được chọn chưa
                if ($('.results .row').find(`[data-id="${resource.id}"]`).length > 0) {
                    alert('Sản phẩm này đã được chọn.');
                    return; // Dừng hàm nếu sản phẩm đã được chọn
                }

                var productHtml = `
                <div class="col-md-4 pb-3 result-item" data-id="${resource.id}">
                    <div class="d-flex align-items-center border position-relative p-1">
                        <div class="img-container">
                            <img src="${path_url}${resource.image_path}" width="100"  alt="" class="img-fluid">
                        </div>
                        <div class="info">
                            <div class="name ellipsis">
                                <a href="#" class="text-decoration-none">${resource.name}</a>
                            </div>
                            <div class="price">
                                <span class="current-price">Tồn kho: ${resource.stock ?? 0}</span>
                            </div>
                            <div class="quantity d-flex align-items-center">
                                <span class="current-stock me-2">Số lượng</span>
                                <input type="number" name="products[${resource.id}]" class="form-control" min="1" value="1">
                            </div>
                        </div>
                        <button class="btn-close position-absolute end-0 top-0 bg-danger" style="border-radius: 0%"></button>
                    </div>
                </div>`;
                $('.results .row').append(productHtml); // Thêm sản phẩm vào card-body
            });

            $('.results .row').on('click', '.btn-close', function() {
                $(this).closest('.col-md-4').remove(); // Xóa phần tử cha
            });

            function fetchData() {
                const search = $(".searchInput").val();

                $.ajax({
                    url: "{{ route('admin.product.filter') }}",
                    method: "GET",
                    data: {
                        search,
                    },
                    success: function(data) {
                        $(".search-results").html(data.results);
                        notData();
                    },
                });
            }

            $(".searchInput").on("input", function() {
                clearTimeout(debounceTimer);
                const searchValue = $(this).val();

                if (searchValue === "") {
                    $(".search-results").empty();
                } else {
                    debounceTimer = setTimeout(() => {
                        fetchData(); // Gọi fetchData nếu có giá trị tìm kiếm
                    }, 1000);
                }

            });

            function notData() {

                // Check if there are no rows in the tbody
                if ($(".result-item").length === 0) {
                    console.log("no data");

                    // Append the "No data" row
                    $(".search-results").append(
                        `<p id="no-data-row" class="text-center">Không tìm thấy dữ liệu!</p>`
                    );
                } else {
                    console.log("has data");

                    // Remove the "No data" row if it exists
                    $("#no-data-row").remove();
                }
            }

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
                        <label for="room" class="required">@lang('Room') - <span class="serialNumber">${number}</span></label>
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

            //Mã tăng số lượng giường
            // $('[name=total_bed]').on('input', function() {
            //     var totalBed = $(this).val();
            //     if (totalBed) {
            //         let content = '<div class="row border-top pt-3">';
            //         for (var i = 1; i <= totalBed; i++) {
            //             content += getBedContent(i);
            //         }
            //         content += '</div>';
            //         $('.bed').html(content);
            //     }
            // });

            function getBedContent(number) {
                return `
                    <div class="col-md-2 number-field-wrapper bed-content m-1">
                        <div class="form-group">
                            <label for="bed" class="required">@lang('Bed') - <span class="serialNumber">${number}</span></label>
                            <div class="input-group"><select class="form-control bedType" name="bed[${number}]">
                                        <option value="">@lang('Select One')</option>
                                        ${allBedType()}
                                    </select><button type="button" class="input-group-text bg-danger border-0 btnRemove" data-name="bed"><i class="las la-times"></i></button>
                            </div>
                        </div>
                    </div>`;
            }

            function updateDivs() {
                $("#bed").empty();
                let quantity = parseInt($("#quantity-bed").val());
                for (let i = 0; i < quantity; i++) {
                    let newDiv = $(getBedContent(i))
                    $("#bed").append(newDiv);
                }
            }
            $("#increase-bed").click(function() {
                let quantity = parseInt($("#quantity-bed").val());
                $("#quantity-bed").val(quantity + 1);
                updateDivs();
            });
            $("#decrease-bed").click(function() {
                let quantity = parseInt($("#quantity-bed").val());
                if (quantity > 0) {
                    $("#quantity-bed").val(quantity - 1);
                    updateDivs();
                }
            });
            updateDivs();

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


            // /////////////////////////





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

            $('.buildSlug').on('click', function() {
                let slugKey = "{{ @$roomType->slug ?? '' }}";
                let closestForm = $(this).closest('form');
                let title = closestForm.find(`[name=name]`).val();
                closestForm.find('[name=slug]').val(title);
                closestForm.find('[name=slug]').trigger('input');
            });



            $('[name=slug]').on('input', function() {
                let closestForm = $(this).closest('form');
                closestForm.find('[type=submit]').addClass('disabled');

                let slug = $(this).val();

                slug = slug.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
                $(this).val(slug);

                if (slug) {
                    closestForm.find('.slug-verification').removeClass('d-none');
                    closestForm.find('.slug-verification').html(`
                            <small class="text--info"><i class="las la-spinner la-spin"></i> @lang('Verifying')</small>
                        `);
                    $.get("{{ route('admin.hotel.room.type.check.slug') }}", {
                        slug: slug,
                        id: @json(@$roomType->id ?? 0)
                    }, function(response) {
                        if (!response.exists) {
                            closestForm.find('.slug-verification').html(`
                                    <small class="text--success"><i class="las la-check"></i> @lang('Verified')</small>
                                `);
                            closestForm.find('[type=submit]').removeClass('disabled')
                        }
                        if (response.exists) {
                            closestForm.find('.slug-verification').html(`
                                    <small class="text--danger"><i class="las la-times"></i> @lang('Slug already exists')</small>
                                `);
                        }
                    });
                } else {
                    closestForm.find('.slug-verification').addClass('d-none');
                }
            });
        })(jQuery);
    </script>
    <script>
        var QtyInput = (function() {
            var $qtyInputs = $(".qty-input");

            if (!$qtyInputs.length) {
                return;
            }

            var $inputs = $qtyInputs.find(".product-qty");
            var $countBtn = $qtyInputs.find(".qty-count");
            var qtyMin = parseInt($inputs.attr("min"));
            var qtyMax = parseInt($inputs.attr("max"));

            $inputs.change(function() {
                var $this = $(this);
                var $minusBtn = $this.siblings(".qty-count--minus");
                var $addBtn = $this.siblings(".qty-count--add");
                var qty = parseInt($this.val());

                if (isNaN(qty) || qty <= qtyMin) {
                    $this.val(qtyMin);
                    $minusBtn.attr("disabled", true);
                } else {
                    $minusBtn.attr("disabled", false);

                    if (qty >= qtyMax) {
                        $this.val(qtyMax);
                        $addBtn.attr('disabled', true);
                    } else {
                        $this.val(qty);
                        $addBtn.attr('disabled', false);
                    }
                }
            });

            $countBtn.click(function() {
                var operator = this.dataset.action;
                var $this = $(this);
                var $input = $this.siblings(".product-qty");
                var qty = parseInt($input.val());

                if (operator == "add") {
                    qty += 1;
                    if (qty >= qtyMin + 1) {
                        $this.siblings(".qty-count--minus").attr("disabled", false);
                    }

                    if (qty >= qtyMax) {
                        $this.attr("disabled", true);
                    }
                } else {
                    qty = qty <= qtyMin ? qtyMin : (qty -= 1);

                    if (qty == qtyMin) {
                        $this.attr("disabled", true);
                    }

                    if (qty < qtyMax) {
                        $this.siblings(".qty-count--add").attr("disabled", false);
                    }
                }

                $input.val(qty);
            });
        })();
    </script>
@endpush

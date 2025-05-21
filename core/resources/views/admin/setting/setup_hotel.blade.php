@extends('admin.layouts.master_iframe')
@section('panel')
    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 d-flex gap-2">
                        <div class="form-group position-relative mt-1 btn-reload-hotel" id="">
                            <button type="button" class="btn btn--primary " data-bs-toggle="modal">
                                <i class="fa fa-repeat p-1"></i>
                            </button>
                        </div>
                        @can(['admin.hotel.setting.setup.add.hotel'])
                            <div class="form-group position-relative mt-1" id="btn-add-hotel">
                                <button type="button" class="btn btn--primary btn-add " data-bs-toggle="modal"
                                    data-bs-target="#setup-hotel">
                                    <i class="las la-plus p-1"></i>
                                </button>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="modal fade" id="setup-hotel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" id="modal-dialog">

                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="emptyArea"></div>
                </div>
                <div class="table-responsive--md table-responsive">
                    <table class="table--light style--two table">
                        <thead>
                            <tr>
                                <th>Hành động</th>
                                <th>@lang('STT')</th>
                                <th>@lang('Mã cơ sở ')</th>
                                <th>@lang('Tên khách sạn')</th>
                                <th>@lang('Trạng thái')</th>
                            </tr>
                        </thead>
                        <tbody id="main-table-hotel">
                            @forelse($hotels as $id => $item)
                                <tr data-id="{{ $item->id }}" class="{{ $id % 2 !== 0 ? 'bg-white' : 'bg-gray' }}">
                                    <td style="width:20px">
                                        <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30"
                                            height="30" viewBox="0 0 21 21">
                                            <g fill="currentColor" fill-rule="evenodd">
                                                <circle cx="10.5" cy="10.5" r="1" />
                                                <circle cx="10.5" cy="5.5" r="1" />
                                                <circle cx="10.5" cy="15.5" r="1" />
                                            </g>
                                        </svg>
                                        @can(['admin.hotel.*'])
                                            <div class="dropdown menu_dropdown_check_in" id="dropdown-menu"
                                                style="position:fixed">
                                                @can(['admin.hotel.setting.setup.edit.hotel'])
                                                    <div class="dropdown-item booked_room_edit">
                                                        <a class="btn btn-sm btn-outline--primary btn-edit-hotel"
                                                            data-id="{{ $item->id }}" data-bs-toggle="modal"
                                                            data-bs-target="#setup-hotel"
                                                            style="color:black !important;border:none;padding:5px"
                                                            data-modal_title="@lang('Cập nhật cơ sở vật chất')" type="button">
                                                            Sửa
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can(['admin.hotel.setting.setup.delete.hotel'])
                                                    <div class="dropdown-item hotel_delete">
                                                        <button class=" btn-delete-hotel icon-delete-room"
                                                            data-id="{{ $item->id }}" data-modal_title="@lang('Xóa')"
                                                            type="button">
                                                            Xóa
                                                        </button>
                                                    </div>
                                                @endcan
                                            </div>
                                        @endcan
                                    </td>
                                    <td data-label="STT" style="text-align:right;width:20px">

                                        {{ $id + 1 }}
                                    </td>
                                    <td>
                                        {{ $item->ma_coso }}
                                    </td>

                                    <td>
                                        {{ $item->ten_coso }}
                                    </td>
                                    <td style="width:50px;text-align: center" class="status-hotel">
                                        @if ($item->trang_thai == 1)
                                            <i class="fa fa-check" style="color:green;text-align: center"></i>
                                        @else
                                            <i class="fa fa-close" style="color:red;text-align: center"></i>
                                        @endif
                                    </td>


                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- @foreach ($settings as $key => $setting)
                    @php
                        $params = null;
                        if (@$setting->params) {
                            foreach ($setting->params as $paramVal) {
                                $params[] = array_values((array) $paramVal)[0];
                            }
                        }

                    @endphp
                    @can($setting->route_name)
                        <div class="col-xxl-4 col-md-6 {{ $key }} searchItems">
                            <x-widget style="2" link="{{ $setting->route_name }}" :parameters="$params" icon="{{ $setting->icon }}" heading="{{ $setting->title }}" subheading="{{ $setting->subtitle }}" cover_cursor=1 icon_style="fill" color="primary" />
                        </div>
                    @endcan
                @endforeach --}}
            </div>
        </div>
    </div>
@endsection
@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/modal.css') }}">
    <style>
        .pagination .page-item .page-link,
        .pagination .page-item span {
            width: 22px !important;
            height: auto !important;
            background-color: #4634ff !important;
            color: white !important;
        }

        .pagination .page-item.active .page-link {
            background-color: #071251 !important;
        }
    </style>
@endpush
@push('script')
    <script src="{{ asset('assets/admin/js/highlighter22.js') }}"></script>
    <script src="{{ asset('assets/validator/validator.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/modal.css') }}">
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            var formEconomyEdit = {
                'ma_coso': { // passwword thì nên đặt là name trong input đó
                    'element': document.getElementById('ma_coso'), // id trong input đó
                    'error': document.getElementById('ma_coso_error'), // thẻ hiển thị lỗi
                    'validations': [{
                            'func': function(value) {
                                return checkRequired(value); // check trống
                            },
                            'message': generateErrorMessage('MS001')
                        }, // viết tiếp điều kiện validate vào đây (validations)
                    ]
                },
                'ten_coso': { // passwword thì nên đặt là name trong input đó
                    'element': document.getElementById('ten_coso'), // id trong input đó
                    'error': document.getElementById('ten_coso_error'), // thẻ hiển thị lỗi
                    'validations': [{
                            'func': function(value) {
                                return checkRequired(value); // check trống
                            },
                            'message': generateErrorMessage('TKS001')
                        }, // viết tiếp điều kiện validate vào đây (validations)
                    ]
                },
            }
            $(document).on('click', '#click-btn-hotel', function() {
                if (validateAllFields(formEconomyEdit)) {
                    document.getElementById('btn-submit-hotel').submit(); // là id trong form
                }
            });
            $(document).on('click', '#click-btn-hotel-update', function() {
                if (validateAllFields(formEconomyEdit)) {
                    document.getElementById('btn-submit-hotel-update').submit(); // là id trong form
                }
            });
            // add
            $('#btn-add-hotel').on('click', function() {
                $('#modal-dialog').empty();
                let row = '';
                row += `
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Thêm mới cơ sở</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="btn-submit-hotel" action="{{ route('admin.hotel.setting.setup.add.hotel') }}"
                                    method="POST">
                                    @csrf
                                    <!-- Input 1 -->
                                    <div class="mb-3">
                                        <label for="hotelName" class="form-label">Mã cơ sở</label>
                                        <input type="text" class="form-control " name="ma_coso" id="ma_coso"
                                            placeholder="Nhập mã cơ sở">
                                        <span class="invalid-feedback d-block" style="font-weight: 500"
                                            id="ma_coso_error"></span>
                                    </div>
                                    <!-- Input 2 -->
                                    <div class="mb-3">
                                        <label for="hotelLocation" class="form-label">Tên khách sạn</label>
                                        <input type="text" class="form-control " name="ten_coso" id="ten_coso"
                                            placeholder="Nhập tên khách sạn">
                                        <span class="invalid-feedback d-block" style="font-weight: 500"
                                            id="ten_coso_error"></span>
                                    </div>
                                    <div class="mb-3">
                                        <label for="hotelStatus" class="form-label">Trạng thái</label><br>
                                        <input type="radio" name="hotelStatus" value="1" id="statusActive"> Hoạt động
                                        <input type="radio" name="hotelStatus" value="0" id="statusInactive" checked>
                                        Không hoạt
                                        động
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" id="click-btn-hotel">Lưu</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    `;
                $('#modal-dialog').append(row);

                formEconomyEdit.ma_coso.element = document.getElementById('ma_coso');
                formEconomyEdit.ma_coso.error = document.getElementById('ma_coso_error');
                formEconomyEdit.ten_coso.element = document.getElementById('ten_coso');
                formEconomyEdit.ten_coso.error = document.getElementById('ten_coso_error');
            });
            // sửa
            $('.btn-edit-hotel').on('click', function() {
                var dataId = $(this).data('id');

                // ajax request
                $.ajax({
                    url: `{{ route('admin.hotel.setting.setup.edit.hotel', '') }}/${dataId}`,
                    type: 'POST',
                    success: function(data) {
                        if (data.status == 'success') {

                            let rowEdit = '';
                            $('#modal-dialog').empty();
                            rowEdit += `
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Chỉnh sửa cơ sở</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="btn-submit-hotel-update" action="{{ route('admin.hotel.setting.setup.update.hotel', '') }}/${data.data['id']}"
                                        method="POST">
                                        @csrf
                                        <!-- Input 1 -->
                                        <div class="mb-3">
                                            <label for="hotelName" class="form-label">Mã cơ sở</label>
                                            <input type="text" class="form-control " name="ma_coso" id="ma_coso"
                                                placeholder="Nhập mã cơ sở" value="${data.data['ma_coso']}">
                                            <span class="invalid-feedback d-block" style="font-weight: 500"
                                                id="ma_coso_error"></span>
                                        </div>
                                        <!-- Input 2 -->
                                        <div class="mb-3">
                                            <label for="hotelLocation" class="form-label">Tên khách sạn</label>
                                            <input type="text" class="form-control " name="ten_coso" id="ten_coso"
                                                placeholder="Nhập tên khách sạn" value="${data.data['ten_coso']}">
                                            <span class="invalid-feedback d-block" style="font-weight: 500"
                                                id="ten_coso_error"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label for="hotelStatus" class="form-label">Trạng thái</label><br>
                                            <input type="radio" name="hotelStatus" value="1" id="statusActive" ${data.data['trang_thai'] == 1 ? 'checked' : ''}> Hoạt động
                                            <input type="radio" name="hotelStatus" value="0" id="statusInactive" ${data.data['trang_thai'] == 0 ? 'checked' : ''}> Không hoạt động
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" id="click-btn-hotel-update">Lưu</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            `;
                            $('#modal-dialog').append(rowEdit);
                            formEconomyEdit.ma_coso.element = document.getElementById(
                                'ma_coso');
                            formEconomyEdit.ma_coso.error = document.getElementById(
                                'ma_coso_error');
                            formEconomyEdit.ten_coso.element = document.getElementById(
                                'ten_coso');
                            formEconomyEdit.ten_coso.error = document.getElementById(
                                'ten_coso_error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });

            });
            // xóa
            $('.icon-delete-room').on('click', function() {
                var dataId = $(this).data('id');
                var rowToDelete = $(`tr[data-id="${dataId}"]`);
                Swal.fire({
                    title: 'Xác nhận xóa cơ sở?',
                    text: 'Bạn có chắc chắn muốn xóa cơ sở này không?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy bỏ',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ajax
                        $.ajax({
                            url: `{{ route('admin.hotel.setting.setup.delete.hotel', '') }}/${dataId}`,
                            type: 'POST',
                            success: function(data) {
                                if (data.status === 'success') {
                                    rowToDelete.remove();


                                }
                            },
                            error: function(xhr, status, error) {
                                console.log(xhr.responseText);
                            }
                        });


                    }
                });
            });
            // chỉnh sửa trạng thái
            $('.confirmationBtn').on('click', function() {
                var action = $(this).data('action');
                var dataId = $(this).data('id');
                // ajax request
                $.ajax({
                    url: action,
                    type: 'POST',
                    success: function(data) {
                        if (data.status === 'success') {
                            let statusCell = $(`tr[data-id="${dataId}"] .status-hotel`);
                            statusCell.html(data.status_html);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });

            });
            $('input[name="hotelStatus"]').on('change', function() {
                const selectedStatus = $('input[name="hotelStatus"]:checked').val();
            });
        });
        $(document).ready(function() {

            $(document).on('click', '.svg-icon', function(e) {
                e.stopPropagation();
                const $dropdown = $(this).siblings('.menu_dropdown');
                $('.menu_dropdown').not($dropdown).removeClass('show');
                $dropdown.toggleClass('show');
            });
            $(document).on('click', function() {
                $('.menu_dropdown').removeClass('show');
            });
            $(document).on('click', '.svg_menu_check_in', function(e) {
                e.stopPropagation();
                const $dropdown = $(this).siblings('.menu_dropdown_check_in');
                $('.menu_dropdown_check_in').not($dropdown).removeClass('show');
                $dropdown.toggleClass('show');
            });
            $(document).on('click', function() {
                $('.menu_dropdown_check_in').removeClass('show');
            });
            $(document).on('click', function() {
                $('.menu_dropdown').removeClass('show');
            });
            $(document).on('click', '.btn-reload-hotel', function() {
                window.location.reload();
            });

        });
    </script>
    {{-- <script>
        (function($) {
            "use strict";
            var settingsData = @json($settings);
            // Function to filter settings based on search query
            function filterSettings(query) {
                let filteredSettings = [];
                for (var key in settingsData) {
                    if (settingsData.hasOwnProperty(key)) {
                        var setting = settingsData[key];
                        // Check if the query matches keyword, title, or subtitle
                        var keywordMatch = setting.keyword.some(function(keyword) {
                            return keyword.toLowerCase().includes(query.toLowerCase());
                        });
                        var titleMatch = setting.title.toLowerCase().includes(query.toLowerCase());
                        var subtitleMatch = setting.subtitle.toLowerCase().includes(query.toLowerCase());

                        // If any match is found, add the setting to filtered settings
                        if (keywordMatch || titleMatch || subtitleMatch) {
                            filteredSettings[key] = setting;
                        }
                    }
                }
                return filteredSettings;
            }

            function isEmpty(obj) {
                return Object.keys(obj).length === 0;
            }

            // Function to render filtered settings
            function renderSettings(filteredSettings, query) {
                $('.searchItems').addClass('d-none');
                $('.emptyArea').html('');
                if (isEmpty(filteredSettings)) {
                    $('.emptyArea').html(`<div class="col-12 searchItems text-center mt-4"><div class="card">
                                <div class="card-body">
                                    <div class="empty-search text-center">
                                        <img src="{{ getImage('assets/images/empty_list.png') }}" alt="empty">
                                        <h5 class="text-muted">@lang('No search result found.')</h5>
                                    </div>
                                </div>
                            </div>
                        </div>`);
                } else {
                    for (const key in filteredSettings) {
                        if (Object.hasOwnProperty.call(filteredSettings, key)) {
                            const element = filteredSettings[key];
                            var setting = element;
                            $(`.searchItems.${key}`).removeClass('d-none');
                        }
                    }
                }
            }


            $('.searchInput').on('input', function() {
                var query = $(this).val().trim();
                var filteredData = filterSettings(query);
                renderSettings(filteredData, query);
            });

            $('.searchInput').highlighter22({
                targets: [".widget-two__content h3", ".widget-two__content p"],
            });

        })(jQuery);
      
    </script> --}}
@endpush

@push('style')
    <style>
        .system-search-icon {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            aspect-ratio: 1;
            padding: 5px;
            display: grid;
            place-items: center;
            color: #888;
        }

        .system-search-icon~.form-control {
            padding-left: 45px;
        }

        .widget-seven .widget-seven__content-amount {
            font-size: 22px;
        }

        .widget-seven .widget-seven__content-subheading {
            font-weight: normal;
        }

        .empty-search img {
            width: 120px;
            margin-bottom: 15px;
        }

        a.item-link:focus,
        a.item-link:hover {
            background: #4634ff38;
        }
    </style>
@endpush

@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-12">


            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group position-relative mb-0" style="float: inline-end;" id="btn-add-hotel">
                            <button class="btn btn-sm btn-outline--primary" data-modal_title="Thêm mới cơ sở" type="button"
                                data-bs-toggle="modal" data-bs-target="#setup-hotel">
                                <i class="las la-plus"></i>Thêm mới
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="setup-hotel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" id="modal-dialog">

                </div>
            </div>

            <div class="row gy-4">
                <div class="col-12">
                    <div class="emptyArea"></div>
                </div>
                <div class="table-responsive--md table-responsive">
                    <table class="table--light style--two table">
                        <thead>
                            <tr>
                                <th>@lang('Mã cơ sở ')</th>
                                <th>@lang('Tên khách sạn')</th>
                                <th>@lang('Trạng thái')</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="main-table-hotel">
                            @forelse($hotels as $key => $item)
                                <tr data-id="{{ $item->id }}">
                                    <td>
                                        {{ $item->ma_coso }}
                                    </td>

                                    <td>
                                        {{ $item->ten_coso }}
                                    </td>
                                    <td class="status-hotel">
                                        {!! $item->styleStatus() !!}
                                    </td>
                                    <td>
                                        {{-- href="{{ route('admin.setting.setup.edit.hotel', $item->id) }}" --}}
                                        <a class="btn btn-sm btn-outline--primary btn-edit-hotel"
                                            data-id="{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#setup-hotel">
                                            <i class="la la-pencil"></i>@lang('Sửa')
                                        </a>
                                        @if($item->trang_thai == 1)
                                           
                                            <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                            data-action="{{ route('admin.setting.setup.status.hotel', $item->id) }}"   data-id="{{ $item->id }}" 
                                            data-question="@lang('Bạn có chắc chắn muốn tắt tiện ích này không?')" type="button">
                                            <i class="la la-eye-slash"></i> @lang('Ngưng hoạt động')
                                        </button>
                                        @else
                                        <button class="btn btn-sm btn-outline--success confirmationBtn"
                                            data-action="{{ route('admin.setting.setup.status.hotel', $item->id) }}"   data-id="{{ $item->id }}" 
                                            data-question="@lang('Bạn có chắc chắn muốn tắt tiện ích này không?')" type="button">
                                            <i class="la la-eye-slash"></i> @lang('Hoạt động')
                                        </button>
                                        @endif
                                        <button class="btn btn-sm btn-outline--danger btn-delete icon-delete-room"
                                            data-id="{{ $item->id }}" data-modal_title="@lang('Xóa danh mục')"type="button"
                                            data-pro="0">
                                            <i class="fas fa-trash"></i>@lang('Xóa')
                                        </button>
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

@push('script')
    <script src="{{ asset('assets/admin/js/highlighter22.js') }}"></script>
    <script src="{{ asset('assets/validator/validator.js') }}"></script>
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
                                <form id="btn-submit-hotel" action="{{ route('admin.setting.setup.add.hotel') }}"
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
                    url: `{{ route('admin.setting.setup.edit.hotel', '') }}/${dataId}`,
                    type: 'POST',
                    success: function(data) {
                        if (data.status === 'success') {
                          
                            let rowEdit = '';
                            $('#modal-dialog').empty();
                            rowEdit += `
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Chỉnh sửa cơ sở</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="btn-submit-hotel-update" action="{{ route('admin.setting.setup.update.hotel', '') }}/${data.data['id']}"
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
                            formEconomyEdit.ma_coso.element = document.getElementById('ma_coso');
                            formEconomyEdit.ma_coso.error = document.getElementById('ma_coso_error');
                            formEconomyEdit.ten_coso.element = document.getElementById('ten_coso');
                            formEconomyEdit.ten_coso.error = document.getElementById('ten_coso_error');
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
                            url: `{{ route('admin.setting.setup.delete.hotel', '') }}/${dataId}`,
                            type: 'POST',
                            success: function(data) {
                                if (data.status ==='success') {
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
            $('.confirmationBtn').on('click', function(){
                var action =  $(this).data('action');
                var dataId = $(this).data('id');
                // ajax request
                $.ajax({
                    url: action,
                    type: 'POST',
                    success: function(data) {
                        if (data.status ==='success') {
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

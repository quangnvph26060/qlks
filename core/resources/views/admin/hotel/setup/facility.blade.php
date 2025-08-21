@extends('admin.layouts.master_iframe')
@section('panel')
    <div class="row">
        <div class="col-12">

            <div class="card-body">
                <div class="modal fade" id="iconModal" tabindex="-1" aria-labelledby="iconModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Thêm mới</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="btn-submit-status" action="{{ route('admin.hotel.setup.facilities.store') }}"
                                    method="POST">
                                    @csrf
                                    <!-- Input 1 -->
                                    <div class="mb-3">
                                        <label for="statusCode" class="form-label">Mã cơ sở vật chất</label>
                                        <input type="text" class="form-control " value="{{ $code }}"
                                            name="code" id="add-code" placeholder="Nhập mã">
                                        <span class="invalid-feedback d-block" style="font-weight: 500"
                                            id="code_error"></span>
                                    </div>
                                    <!-- Input 2 -->
                                    <div class="mb-3">
                                        <label for="statusName" class="form-label">Tên cơ sở vật chất</label>
                                        <input type="text" class="form-control " name="title" id="add-title"
                                            placeholder="Nhập tiêu đề">
                                        <span class="invalid-feedback d-block" style="font-weight: 500"
                                            id="title_error"></span>
                                    </div>
                                    <div class="mb-3">
                                        <label for="note" class="form-label">Icon</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control iconPicker" id="icon"
                                                name="icon" placeholder="Nhấn để chọn icon...">
                                            <span class="input-group-text">
                                                <i id="icon-preview" class=""></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="statusStatus" class="form-label">Trạng thái</label><br>
                                        <input type="radio" name="status" value="1" id="statusActive" checked>Hoạt
                                        động
                                        <input type="radio" name="status" value="0" id="statusInactive">
                                        Không hoạt động
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary"
                                            id="click-btn-status-code">Lưu</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 d-flex stack-mobile">
                        <div>
                            <a class="mr-1" href="{{ route('admin.hotel.setup.facilities.all') }}">
                                <button class="btn btn--primary" data-modal_title="Làm mới">
                                    <i class="fa fa-repeat p-1"></i>
                                </button>
                            </a>
                            <a>
                                <button class="btn btn--primary" data-modal_title="Thêm mới cơ sở vật chất" type="button"
                                    id="btn-add-status" data-bs-toggle="modal" data-bs-target="#iconModal"
                                    style="margin-left:10px">
                                    <i class="las la-plus  p-1"></i>
                                </button>
                            </a>
                        </div>
                        <form role="form" enctype="multipart/form-data"
                            action="{{ route('admin.hotel.setup.facilities.search') }}">
                            <div class="form-group position-relative mb-0">
                                <input class="searchInput" name="code"
                                    style="height: 35px;border: 1px solid rgb(121, 117, 117, 0.5);margin-left: 8px;"
                                    placeholder="Mã cơ sở vật chất">
                                <input class="searchInput" name="title"
                                    style="height: 35px;border: 1px solid rgb(121, 117, 117, 0.5); margin-left: 8px;"
                                    placeholder="Tiêu đề cơ sở vật chất" value="{{ $title ?? '' }}">
                                <a>
                                    <button type="submit" class="btn btn--primary"
                                        style="padding-right:15px;padding-left:15px">
                                        <i class="las la-search p-1"></i>
                                    </button>
                                </a>

                            </div>
                        </form>
                    </div>
                    @if ($facilities->hasPages())
                        <div class="pager-wrap">
                            <div class="k-widget d-flex">
                                <div class="pagination-tb" style="font-size: 13px;margin: 0 auto">
                                    {{ $facilities->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>


            <div class="row gy-4">
                <div class="col-12">
                    <div class="emptyArea"></div>
                </div>
                <div class="table-responsive--md table-responsive" style="overflow-x: visible;">
                    <table class="table--light style--two table">
                        <thead>
                            <tr>
                                <th>Hành động</th>
                                <th>STT</th>
                                <th>@lang('Mã cơ sở vật chất')</th>
                                <th>@lang('Tên cơ sở vật chất')</th>
                                <th>@lang('Icon')</th>
                                <th>@lang('Trạng thái')</th>

                            </tr>
                        </thead>
                        <tbody id="main-table-hotel">
                            @forelse($facilities as $id => $item)
                                <tr data-id="{{ $item->id }}" class={{ $id % 2 !== 0 ? 'bg-white' : 'bg-gray' }}>
                                    <td class="d-none-mobi" style="width:20px;">
                                        <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30"
                                            height="30" viewBox="0 0 21 21">
                                            <g fill="currentColor" fill-rule="evenodd">
                                                <circle cx="10.5" cy="10.5" r="1" />
                                                <circle cx="10.5" cy="5.5" r="1" />
                                                <circle cx="10.5" cy="15.5" r="1" />
                                            </g>
                                        </svg>
                                        <div class="dropdown menu_dropdown_check_in" id="dropdown-menu"
                                            style="position:fixed">
                                            <div class="dropdown-item"><a data-id="{{ $item->id }}"
                                                    class="btn-edit-status" data-bs-toggle="modal"
                                                    data-bs-target="#status-code" style="color:black">
                                                    Sửa
                                                </a>
                                            </div>

                                            <div class="dropdown-item booked_room_detail"> <button
                                                    class=" btn-delete icon-delete-room" data-id="{{ $item->id }}"
                                                    data-modal_title="@lang('Xóa trạng thái')" type="button"
                                                    data-pro="0">Xóa</div>

                                        </div>
                                    </td>
                                    <td data-label="STT" style="text-align:right">
                                        @php
                                            $stt =
                                                $facilities->total() -
                                                ($facilities->currentPage() - 1) * $facilities->perPage() +
                                                $id;
                                        @endphp
                                        {{ $stt }}
                                    </td>
                                    <td data-label="Mã cơ sở vật chất">
                                        {{ $item->code }}
                                    </td>
                                    <td data-label="Tên cơ sở vật chất">
                                        {{ $item->title }}
                                    </td>
                                    <td data-label="icon">
                                        {!! $item->icon !!}
                                    </td>

                                    <td data-label="Trạng thái" style="width:50px;text-align: center"
                                        class="status-hotel">
                                        @if ($item->status == 1)
                                            <i class="fa fa-check" style="color:green;text-align: center"></i>
                                        @else
                                            <i class="fa fa-close" style="color:red;text-align: center"></i>
                                        @endif
                                    </td>
                                    <td class="d-block-mobi">
                                        <div class="action-buttons gap-2">
                                            <div class="dropdown-item d-flex justify-content-center" style="background: orange;border-radius: 4px">
                                                <a data-id="{{ $item->id }}"
                                                    class="btn-edit-status" data-bs-toggle="modal"
                                                    data-bs-target="#status-code" style="color:white">
                                                    Sửa
                                                </a>
                                            </div>

                                            <div class="dropdown-item booked_room_detail  d-flex justify-content-center" style="background: red;border-radius: 4px"> 
                                                <button
                                                 style="background: red;border-radius: 4px;color:white"
                                                    class=" btn-delete icon-delete-room" data-id="{{ $item->id }}"
                                                    data-modal_title="@lang('Xóa trạng thái')" type="button"
                                                    data-pro="0">Xóa</div>
                                        </div>
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
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/admin/js/highlighter22.js') }}"></script>
    <script src="{{ asset('assets/validator/validator.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fontawesome-iconpicker/3.2.0/js/fontawesome-iconpicker.min.js">
    </script>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush
@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/modal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- FontAwesome Icon Picker CSS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/fontawesome-iconpicker/3.2.0/css/fontawesome-iconpicker.min.css">


    <style>
        .navbar__right {
            display: none;
        }

        #navbar-wrapper {
            padding: 0px 30px 20px;
        }

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
    <script>
        (function($) {
            "use strict";

            $('#cuModal').on('shown.bs.modal', function(e) {
                $(document).off('focusin.modal');
            });

            $('.iconPicker').iconpicker().on('iconpickerSelected', function(e) {
                $('.iconPicker').val(`<i class="${e.iconpickerValue}"></i>`);
            });
            document.getElementById('add-code').addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
        })(jQuery);
        var code = "{{ $code }}";


        $(document).ready(function() {
            var formEconomyEdit = {
                'code': {
                    'element': document.getElementById('add-code'),
                    'error': document.getElementById('code_error'),
                    'validations': [{
                        'func': function(value) {
                            return checkRequired(value); // check trống
                        },
                        'message': generateErrorMessage('MS001')
                    }, ]
                },
                'title': {
                    'element': document.getElementById('add-title'), // id trong input đó
                    'error': document.getElementById('title_error'), // thẻ hiển thị lỗi
                    'validations': [{
                        'func': function(value) {
                            return checkRequired(value);
                        },
                        'message': generateErrorMessage('TD001')
                    }, ]
                },
            }
            $(document).on('click', '#click-btn-status-code', function() {
                if (validateAllFields(formEconomyEdit)) {
                    document.getElementById('btn-submit-status').submit(); // là id trong form
                }
            });
            $(document).on('click', '#click-btn-status-update', function() {
                if (validateAllFields(formEconomyEdit)) {
                    document.getElementById('btn-submit-status-update').submit(); // là id trong form
                }
            });
            // add
            $('#btn-add-status').on('click', function() {
                $('#btn-submit-status')[0].reset();

                $('#icon-preview').attr('class', '');
                $('#code_error').text('');
                $('#title_error').text('');
                $('#btn-submit-status').attr('action',
                '{{ route('admin.hotel.setup.facilities.store') }}');
                $('#exampleModalLabel').text('Thêm mới');
                $('#iconModal').modal('show');

            });
            // sửa
            $('.btn-edit-status').on('click', function() {
                var dataId = $(this).data('id');
                // ajax request
                $.ajax({
                    url: `{{ route('admin.hotel.setup.facilities.edit', '') }}/${dataId}`,
                    type: 'GET',
                    success: function(data) {
                        if (data.status == 'success') {
                            let rowEdit = '';
                            let result = data.data;
                            $('#add-code').val(result.code);
                            $('#add-title').val(result.title);
                            $('#icon').val(result.icon);

                            // Hiển thị icon-preview
                            $('#icon-preview').attr('class', result.icon);

                            // Set trạng thái radio button
                            if (result.status == 1) {
                                $('#statusActive').prop('checked', true);
                            } else {
                                $('#statusInactive').prop('checked', true);
                            }
                            $('#exampleModalLabel').text('Thay đổi');
                            const updateAction =
                                `{{ route('admin.hotel.setup.facilities.update', '') }}/${dataId}`;
                            $('#btn-submit-status').attr('action', updateAction);

                            $('#iconModal').modal('show');

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
                    title: 'Xác nhận xóa cơ sở vật chất?',
                    text: 'Bạn có chắc chắn muốn xóa cơ sở vật chất này không?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy bỏ',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ajax
                        $.ajax({
                            url: `{{ route('admin.hotel.setup.facilities.delete', '') }}/${dataId}`,
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

            $('#icon').iconpicker({
                placement: 'bottomRight',
                animation: false,
                hideOnSelect: true
            });

            // Khi chọn icon, cập nhật preview
            $('#icon').on('iconpickerSelected', function(event) {
                $('#icon-preview').attr('class', event.iconpickerValue + ' icon-preview');
            });

        });
    </script>
@endpush

@push('style')
    <style>
        .d-block-mobi {
            display: none;
        }

        /* Mobile: các nút nằm ngang trong 1 hàng */
        @media (max-width: 768px) {
            .d-block-mobi .action-buttons {
                display: flex;
                flex-wrap: nowrap;
                justify-content: space-between;
            }

            .d-block-mobi .action-buttons .btn {
                flex: 1;
                /* nút tự dàn đều */
                margin: 0 2px;
                /* khoảng cách nhỏ */
            }

            .d-block-mobi {
                display: block;
            }
        }

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

        #add-code {
            text-transform: uppercase;
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
    <style scoped>
        @media (max-width: 768px) {
            .stack-mobile {
                flex-direction: column !important;
                /* đổi từ row sang column */
                gap: 8px;
                /* khoảng cách giữa các item */
                padding-left: 5px !important;
                padding-right: 5px !important;
            }

            .stack-mobile>div,
            .stack-mobile form {
                width: 100% !important;
            }

            .stack-mobile input.searchInput {
                width: 100% !important;
                margin-left: 0 !important;
                margin-top: 8px;
            }

            .stack-mobile .btn-icon {
                margin-top: 5px !important;
            }

        }
    </style>
@endpush

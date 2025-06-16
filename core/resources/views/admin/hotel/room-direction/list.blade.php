@extends('admin.layouts.master_iframe')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive" style="overflow-x:auto">
                        <div>
                            <table class="table--light style--two table">
                                <thead>
                                    <tr>
                                        <th>@lang('Hành động')</th>
                                        <th style="width:8%">@lang('STT')</th>
                                        <th>Mã hướng phòng</th>
                                        <th>@lang('Hướng phòng')</th>
                                        <th>@lang('Giá')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($roomDirection as $id => $item)
                                        <tr data-id="{{ $item->id }}" class={{ $id % 2 !== 0 ? 'bg-white' : 'bg-gray' }}>

                                            <td style="width:20px;">
                                                <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg"
                                                    width="30" height="30" viewBox="0 0 21 21">
                                                    <g fill="currentColor" fill-rule="evenodd">
                                                        <circle cx="10.5" cy="10.5" r="1" />
                                                        <circle cx="10.5" cy="5.5" r="1" />
                                                        <circle cx="10.5" cy="15.5" r="1" />
                                                    </g>
                                                </svg>

                                                <div class="dropdown menu_dropdown_check_in" id="dropdown-menu"
                                                    style="position:fixed">
                                                    @can(['admin.hotel.customer.edit', 'admin.hotel.customer.update'])
                                                        <div class="dropdown-item"><a data-id="{{ $item->id }}"
                                                                data-code="{{ $item->code }}" data-name="{{ $item->name }}"
                                                                data-price="{{ $item->price_offset }}"
                                                                class="btn-edit-direction" style="color:black">
                                                                Sửa
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('admin.hotel.direction.delete')
                                                        <div class="dropdown-item booked_room_detail"> <button
                                                                class=" btn-delete icon-delete-room"
                                                                data-id="{{ $item->id }}"
                                                                data-modal_title="@lang('Xóa khách hàng')" type="button"
                                                                data-pro="0">Xóa
                                                        </div>
                                                    @endcan
                                                </div>
                                            </td>
                                            <td>
                                                {{ $id + 1 }}
                                            </td>
                                            <td>
                                                {{ $item->code }}
                                            </td>
                                            <td>
                                                {{ $item->name }}
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($item->price_offset, 0, ',', '.') }}
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table><!-- table end -->
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>
    @push('breadcrumb-plugins')
        <div class="card-body mt-1">
            <div class="row">
                <div class="col-md-12 col-sm-12 d-flex">
                    <a class="mr-1" href="{{ route('admin.hotel.direction.all') }}">
                        <button class="btn btn--primary" data-modal_title="Làm mới">
                            <i class="fa fa-repeat p-1"></i>
                        </button>
                    </a>
                    @can('admin.hotel.customer.store')
                        <a>
                            <button class="btn btn--primary" data-modal_title="Thêm mới khách hàng" type="button"
                                data-bs-toggle="modal" data-bs-target="#direction" style="margin-left:10px">
                                <i class="las la-plus p-1"></i>
                            </button>
                        </a>
                    @endcan
                    <form role="form" enctype="multipart/form-data" action="{{ route('admin.hotel.direction.all') }}">
                        @csrf
                        <div class="form-group position-relative mb-0">
                            <input class="searchInput" name="name"
                                style="height: 35px;width:70%;border: 1px solid rgb(121, 117, 117, 0.5);margin-left: 8px;"
                                placeholder="Hướng phòng" value="{{ $customer_code ?? '' }}">

                            <a>
                                <button type="submit" class="btn btn--primary">
                                    <i class="las la-search p-1"></i>
                                </button>
                            </a>
                        </div>
                    </form>
                </div>
                @if ($roomDirection->hasPages())
                    <div class="pager-wrap">
                        <div class="k-widget d-flex">
                            <div class="pagination-tb" style="font-size: 13px;margin: 0 auto">
                                {{ $roomDirection->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endpush
    <div class="modal fade" id="direction" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Thêm mới hướng phòng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add_room_directions" method="POST" action="{{ route('admin.hotel.direction.store') }}">
                        {{ method_field('POST') }}
                        @csrf
                        <input type="hidden" name="direction_id" id="direction_id">

                        <div class="row">
                            <div>
                                <div class="mb-3">
                                    <label for="statusCode" class="form-label required">Mã hướng phòng</label>
                                    <input type="text" class="form-control " name="code" id="code"
                                        placeholder="Mã hướng phòng" value="{{ $code }}" required>
                                    <span class="invalid-feedback d-block" style="font-weight: 500"
                                        id="code_error"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label required">Tên hướng phòng</label>
                                    <input type="text" class="form-control" name="name" id="name_directions"
                                        placeholder="Tên hướng phòng" required>
                                    <span class="invalid-feedback d-block" style="font-weight: 500"
                                        id="name_directions_error"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="price_offset" class="form-label">Giá hướng phòng</label>
                                    <input type="text" class="form-control  money-input" name="price_offset"
                                        id="price_offset" placeholder="Nhập giá hướng phòng">
                                    <span class="invalid-feedback d-block" style="font-weight: 500"
                                        id="price_offset_error"></span>
                                </div>
                            </div>


                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                <button type="submit" id="btn-add-directions" class="btn btn-primary">Lưu</button>
                            </div>
                        </div>
                    </form>
                </div>
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
    <script>
        (function($) {
            "use strict"
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('money-input')) {
                    let value = e.target.value.replace(/\D/g, ""); // Xóa ký tự không phải số
                    value = Number(value).toLocaleString('vi-VN'); // Định dạng theo chuẩn Việt Nam
                    e.target.value = value;
                }
            });
            // Chỉ cho nhập số và dấu chấm
            document.querySelector('.money-input').addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9.]/g, '');
            });

            $(document).on('input', 'input[name="code"]', function() {
                this.value = this.value.toUpperCase();
            });
            $(document).ready(function() {
                var formEconomyEdit = {
                    'code': {
                        'element': document.getElementById('code'),
                        'error': document.getElementById('code_error'),
                        'validations': [{
                                'func': function(value) {
                                    return checkRequired(value); // check trống
                                },
                                'message': generateErrorMessage('P001', 'Mã code')
                            },
                            {
                                'func': function(value) {
                                    return isAllUpperCase(value);
                                },
                                'message': generateErrorMessage('INHOA', 'Mã code')
                            }

                        ]
                    },
                    'name': {
                        'element': document.getElementById('name_directions'),
                        'error': document.getElementById('name_directions_error'),
                        'validations': [{
                            'func': function(value) {
                                return checkRequired(value); // check trống
                            },
                            'message': generateErrorMessage('P001', 'Tên')
                        }]
                    }
                }
                $(document).on('click', '#btn-add-directions', function(event) {
                    if (validateAllFields(formEconomyEdit)) {
                        document.getElementById('add_room_directions').submit(); // là id trong form
                    } else {
                        event.preventDefault();
                    }
                });
                $(document).on('keyup', '#add_customer_code', function(event) {
                    var code = $('#add_customer_code').val();
                    $.ajax({
                        url: `{{ route('admin.hotel.customer.check') }}`,
                        type: 'GET',
                        cache: false,
                        data: {
                            'customer_code': code,
                        },
                        success: function(response) {
                            if (response == 1) {
                                $('#customer_code_error').html('Mã khách hàng đã tồn tại');
                                document.getElementById('btn-add-customer').disabled =
                                    'disabled';

                            } else {
                                $('#customer_code_error').html('');
                                document.getElementById('btn-add-customer').disabled =
                                    false;

                            }

                        },
                        error: function(error) {
                            console.log(error);

                        }
                    });
                });
                $(document).on('input', 'input[name="customer_code"]', function() {
                    this.value = this.value.toUpperCase();
                });

                $(document).on('click', '.btn-edit-direction', function(e) {
                    e.preventDefault();

                    // Lấy dữ liệu từ nút
                    let id = $(this).data('id');
                    let name = $(this).data('name');
                    let price = $(this).data('price');
                    let code = $(this).data('code');
                  
                    let formattedPrice = Number(price).toLocaleString('vi-VN');
                    // Đổ dữ liệu vào form
                    $('#direction_id').val(id);
                    $('#name_directions').val(name);
                    $('#price_offset').val(formattedPrice);
                    $('#code').val(code);

                    // Không thay đổi action
                    // Không cần thêm _method PUT

                    // Cập nhật tiêu đề modal
                    $('#staticBackdropLabel').text('Chỉnh sửa hướng phòng');

                    // Mở modal
                    $('#direction').modal('show');
                });

                $('#direction').on('hidden.bs.modal', function() {
                    // Reset form
                    $('#add_room_directions')[0].reset();

                    // Đặt lại action là thêm
                    $('#add_room_directions').attr('action',
                        '{{ route('admin.hotel.direction.store') }}');

                    // Xoá input _method nếu có
                    $('#add_room_directions input[name="_method"]').remove();

                    // Xoá hidden id
                    $('#direction_id').val('');

                    // Đặt lại tiêu đề
                    $('#staticBackdropLabel').text('Thêm mới hướng phòng');
                });
                $(document).on('click', '.btn-delete', function() {
                    let id = $(this).data('id');

                    Swal.fire({
                        title: 'Bạn có chắc muốn xoá?',
                        text: "Hành động này không thể hoàn tác!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Xoá',
                        cancelButtonText: 'Huỷ'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let url =
                                '{{ route('admin.hotel.direction.delete', ['id' => '___ID___']) }}';
                            url = url.replace('___ID___', id);

                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                },
                                success: function(response) {
                                    if (response.success) {
                                        Swal.fire('Đã xoá!', response.message,
                                            'success').then(() => {
                                            location
                                                .reload(); // hoặc reload 1 phần nếu dùng AJAX render danh sách
                                        });
                                    } else {
                                        Swal.fire('Lỗi', response.message, 'error');
                                    }
                                },
                                error: function(xhr) {
                                    Swal.fire('Lỗi',
                                        'Đã xảy ra lỗi không xác định.', 'error'
                                    );
                                }
                            });
                        }
                    });
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

            });
        })(jQuery);
    </script>
@endpush

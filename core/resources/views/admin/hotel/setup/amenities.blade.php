@extends('admin.layouts.master_iframe')
@section('panel')
    <div class="row">
        <div class="col-12">

            <div class="card-body">
                <div class="row">
                <div class="col-md-12 col-sm-12 d-flex">
                        <a class="mr-1" href="{{route('admin.hotel.setup.amenities.all')}}">
                        <button class="btn btn--primary" data-modal_title="Làm mới">
                            <i class="fa fa-repeat p-1"></i>
                        </button>
                         </a>
                         <a>
                            <button class="btn btn--primary"  data-modal_title="Thêm mới tiện nghi"  type="button" id="btn-add-status"
                                    data-bs-toggle="modal" data-bs-target="#status-code"  style="margin-left:10px">
                                <i class="las la-plus  p-1"></i>
                            </button>
                        </a>
                            <form role="form" enctype="multipart/form-data" action="{{route('admin.hotel.setup.amenities.search')}}">
                                <div class="form-group position-relative mb-0">
                                    <input class="searchInput" name="code"
                                        style="height: 35px;border: 1px solid rgb(121, 117, 117, 0.5);margin-left: 8px;"
                                            placeholder="Mã tiện nghi">
                                    <input class="searchInput" name="title"
                                        style="height: 35px;border: 1px solid rgb(121, 117, 117, 0.5); margin-left: 8px;"
                                        placeholder="Tiêu đề tiện nghi" value="{{ $title ?? '' }}">
                                    <a>
                                    <button type="submit" class="btn btn--primary" style="padding-right:15px;padding-left:15px">
                                        <i class="las la-search p-1"></i>
                                    </button>
                                    </a>

                                </div>
                            </form>
                    </div>
                        @if ($amenities->hasPages())
                        <div class="pager-wrap">
                                    <div class="k-widget d-flex">
                                        <div class="pagination-tb" style="font-size: 13px;margin: 0 auto">
                                            {{ $amenities->links('pagination::bootstrap-4') }}
                                        </div>
                                    </div>
                                </div>
                        @endif
                </div>
            </div>
            <div class="modal fade" id="status-code" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" id="modal-dialog">
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
                            <th>@lang('Mã tiện nghi')</th>
                            <th>@lang('Tên tiện nghi')</th>
                            {{-- <th>@lang('Icon')</th> --}}
                            <th>@lang('Trạng thái')</th>

                        </tr>
                        </thead>
                        <tbody id="main-table-hotel">
                        @forelse($amenities as $id => $item)
                            <tr data-id="{{ $item->id }}">
                            <td style="width:20px;">
                                    <svg class="svg_menu_check_in" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 21 21"><g fill="currentColor" fill-rule="evenodd"><circle cx="10.5" cy="10.5" r="1"/><circle cx="10.5" cy="5.5" r="1"/><circle cx="10.5" cy="15.5" r="1"/></g></svg>
                                        <div class="dropdown menu_dropdown_check_in" id="dropdown-menu" style="position:fixed">
                                            <div class="dropdown-item"><a
                                            data-id="{{ $item->id }}" class="btn-edit-status" data-bs-toggle="modal" data-bs-target="#status-code" style="color:black">
                                                Sửa
                                            </a>
                                        </div>

                                         <div class="dropdown-item booked_room_detail"> <button class=" btn-delete icon-delete-room"
                                                data-id="{{ $item->id }}" data-modal_title="@lang('Xóa trạng thái')" type="button"
                                                data-pro="0">Xóa</div>

                                        </div>
                                </td>
                                <td data-label="STT" style="text-align:right">
                                    @php
                                        $stt = $amenities->total() - ($amenities->currentPage() - 1) * $amenities->perPage() + $id;
                                            @endphp
                                        {{ $stt }}
                                </td>
                                <td>
                                    {{ $item->code }}
                                </td>
                                <td>
                                    {{ $item->title }}
                                </td>
                                {{-- <td>
                                    {{ $item->icon }}
                                </td> --}}

                                <td style="width:50px;text-align: center" class="status-hotel">
                                        @if($item->status == 1)
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
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/admin/js/highlighter22.js') }}"></script>
    <script src="{{ asset('assets/validator/validator.js') }}"></script>
@endpush
@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/modal.css') }}">

    <style>
            .navbar__right{
                display: none;
            }
            #navbar-wrapper{
                padding: 0px 30px 20px;
            }
            .pagination .page-item .page-link, .pagination .page-item span{
                width: 22px !important;
                height: auto !important;
                background-color: #4634ff !important;
                color: white !important;
            }
            .pagination .page-item.active .page-link{
                background-color: #071251 !important;
            }
    </style>
@endpush
@push('script')
    <script>
        var code = "{{$code}}";
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
                    },
                    ]
                },
                'title': {
                    'element': document.getElementById('add-title'), // id trong input đó
                    'error': document.getElementById('title_error'), // thẻ hiển thị lỗi
                    'validations': [{
                        'func': function(value) {
                            return checkRequired(value);
                        },
                        'message': generateErrorMessage('TD001')
                    },
                    ]
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
                $('#modal-dialog').empty();
                let row = '';
                row += `
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Thêm mới</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="btn-submit-status" action="{{ route('admin.hotel.setup.amenities.store') }}"
                                    method="POST">
                                    @csrf
                        <!-- Input 1 -->
                        <div class="mb-3">
                            <label for="statusCode" class="form-label">Mã tiện nghi</label>
                            <input type="text" class="form-control " name="code" id="add-code"
                                placeholder="Nhập mã" value="${code}">
                            <span class="invalid-feedback d-block" style="font-weight: 500"
                                id="code_error"></span>
                        </div>
                        <!-- Input 2 -->
                        <div class="mb-3">
                            <label for="statusName" class="form-label">Tiêu đề tiện nghi</label>
                            <input type="text" class="form-control " name="title" id="add-title"
                                placeholder="Nhập tiêu đề">
                            <span class="invalid-feedback d-block" style="font-weight: 500"
                                id="title_error"></span>
                        </div>
                            <div class="mb-3">
                            <label for="note" class="form-label">Ghi chú</label>
                            <input type="text"  data-placement="bottomRight" class="form-control icp icp-auto " name="icon"
                                                    placeholder="Nhập ghi chú">
                        </div>
                        <div class="mb-3">
                            <label for="statusStatus" class="form-label">Trạng thái</label><br>
                            <input type="radio" name="status" value="1" id="statusActive" checked>Hoạt động
                            <input type="radio" name="status" value="0" id="statusInactive">
                            Không hoạt động
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="click-btn-status-code">Lưu</button>
                        </div>
                    </form>
                    </div>
                </div>
            `;
                $('#modal-dialog').append(row);
                formEconomyEdit.code.element = document.getElementById('add-code');
                formEconomyEdit.code.error = document.getElementById('code_error');
                formEconomyEdit.title.element = document.getElementById('add-title');
                formEconomyEdit.title.error = document.getElementById('title_error');
            });
            // sửa
            $('.btn-edit-status').on('click', function() {
                var dataId = $(this).data('id');
                // ajax request
                $.ajax({
                    url: `{{ route('admin.hotel.setup.amenities.edit', '') }}/${dataId}`,
                    type: 'GET',
                    success: function(data) {
                        if (data.status === 'success') {
                            let rowEdit = '';
                            $('#modal-dialog').empty();
                            rowEdit += `
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Chỉnh sửa tiện nghi
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="btn-submit-status-update" action="{{ route('admin.hotel.setup.amenities.update', '') }}/${data.data['id']}"
                                        method="POST">
                                        @csrf
                            <!-- Input 1 -->
                            <div class="mb-3">
                                <label for="statusCode" class="form-label">Mã tiện nghi</label>
                                <input type="text" class="form-control " name="code" id="add-code"
                                    placeholder="Nhập mã" value="${data.data['code']}">
                                            <span class="invalid-feedback d-block" style="font-weight: 500"
                                                id="code_error"></span>
                                        </div>
                                        <!-- Input 2 -->
                                        <div class="mb-3">
                                            <label for="statusName" class="form-label">Tiêu đề tiện nghi</label>
                                            <input type="text" class="form-control " name="title" id="add-title"
                                                placeholder="Nhập tiêu đề" value="${data.data['title']}">
                                            <span class="invalid-feedback d-block" style="font-weight: 500"
                                                id="title_error"></span>
                                        </div>
                                       <div class="mb-3">
                                            <label for="note" class="form-label">Ghi chú</label>
                                               <textarea type="text" class="form-control " name="icon"
                                                placeholder="Nhập ghi chú">${data.data['icon']}</textarea>

                                        </div>
                                        <div class="mb-3">
                                            <label for="hotelStatus" class="form-label">Trạng thái</label><br>
                                            <input type="radio" name="status" value="1" id="statusActive" ${data.data['status'] == 1 ? 'checked' : ''}> Hoạt động
                                            <input type="radio" name="status" value="0" id="statusInactive" ${data.data['status'] == 0 ? 'checked' : ''}> Không hoạt động
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" id="click-btn-status-update">Lưu</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            `;
                            $('#modal-dialog').append(rowEdit);
                            formEconomyEdit.code.element = document.getElementById('add-code');
                            formEconomyEdit.code.error = document.getElementById('code_error');
                            formEconomyEdit.title.element = document.getElementById('add-title');
                            formEconomyEdit.title.error = document.getElementById('title_error');
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
                    title: 'Xác nhận xóa tiện nghi?',
                    text: 'Bạn có chắc chắn muốn xóa tiện nghi này không?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Hủy bỏ',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ajax
                        $.ajax({
                            url: `{{ route('admin.hotel.setup.amenities.delete', '') }}/${dataId}`,
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
    </script>

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
#add-code {
  text-transform: uppercase;
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

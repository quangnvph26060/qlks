@extends('admin.layouts.app')

@section('panel')
    @include('admin.messages')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive p-2">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="dt-length">
                                <select name="example_length" style=" padding: 1px 3px; margin-right: 8px;"
                                    aria-controls="example" class="perPage">
                                    <option value="10">10
                                    <option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select><label for="perPage"> entries per page</label>
                            </div>
                            <div class="search">
                                <label for="searchInput">Search:</label>
                                <input class="searchInput"
                                    style="padding: 1px 3px; border: 1px solid rgb(121, 117, 117, 0.5); margin-left: 8px;"
                                    type="search" placeholder="Tìm kiếm...">
                            </div>
                        </div>
                        <table class="table--light style--two table table-hover" id="data-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Mã Nhà Cung Cấp</th>
                                    <th>Tên Nhà Cung Cấp</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Địa Chỉ</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addRepresentatives" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Thêm người đại diện</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="representativeForm">
                        <div class="form-group mb-3">
                            <label for="name" class="control-label required">@lang('Tên người đại diện')</label>
                            <input type="text" name="name" id="name" class="form-control"
                                placeholder="Nhập tên">
                            <small id="error"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email" class="control-label required">@lang('Email')</label>
                            <input type="text" name="email" id="email" class="form-control"
                                placeholder="Nhập tên">
                            <small id="error"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="phone" class="control-label">@lang('Số điện thoại')</label>
                            <input type="text" name="phone" id="phone" class="form-control"
                                placeholder="Nhập tên">
                            <small id="error"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="position" class="control-label">@lang('Chức vụ')</label>
                            <input type="text" name="position" id="position" class="form-control"
                                placeholder="Nhập tên">
                            <small></small>
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:void(0)" class="btn btn-secondary" id="btn-close"
                                data-dismiss="modal">Đóng</a>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@can('')
    @push('breadcrumb-plugins')
        <a class="btn btn-sm btn-outline--primary" href="{{ route('admin.supplier.create') }}"><i
                class="las la-plus"></i>@lang('Thêm mới')</a>
    @endpush
@endcan


@push('script')
    <script src="{{ asset('assets/global/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/dataTable.js') }}"></script>
    <script>
        (function($) {
            "use strict";

            window.toggleRepresentatives = function(id, button) {
                const row = document.getElementById('rep-' + id);
                row.classList.toggle('show');
                button.classList.toggle('collapsed');
            };

            $(document).ready(function() {
                const apiUrl = '{{ route('admin.supplier.index') }}';
                initDataFetch(apiUrl);


                $("#btn-close").on("click", function() {
                    reset()
                })

                function reset() {
                    $("#representativeForm").trigger("reset");
                    $('small#error').removeClass('invalid-feedback').html('');
                    $('input').removeClass('is-invalid');
                    $("#addRepresentatives").modal('hide');
                    $("#modalLabel").html('Thêm người đại diện')
                    $("#representativeForm").attr('action', '');
                }

                $('#addRepresentatives').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Nút đã được nhấn
                    var dataId = button.data('id'); // Lấy data-id
                    $(this).data('id', dataId); // Lưu data-id vào modal
                });

                $("#representativeForm").on("submit", function(e) {
                    e.preventDefault();
                    var modal = $('#addRepresentatives');
                    var dataId = modal.data('id');
                    var action = $(this).attr('action') ?? '';

                    var formData = $(this).serializeArray();
                    formData.push({
                        name: 'supplier_id',
                        value: dataId
                    });

                    $.ajax({
                        url: action ? action : "{{ route('admin.representative.store') }}",
                        type: action ? "PUT" : "POST",
                        data: formData,
                        success: function(response) {
                            if (response.status) {
                                reset()
                                showSwalMessage('success', response.message);

                                var newRepresentative = `
                                   <span class="badge bg-info me-2 position-relative edit-representative cursor-pointer" data-id="${response.data.id}">
                                       <small class="representative-name">${response.data.name}</small>
                                        <small class="bg-danger rounded-circle position-absolute delete-representative"
                                            style="cursor: pointer; top: -7px !important; right: -5px !important; padding: 1px 4px !important;">x
                                        </small>
                                    </span>
                                `;

                                action ? $(
                                        `#rep-${response.data.supplier_id} .representatives-list .badge.bg-info`
                                    ).replaceWith(newRepresentative) :
                                    $(
                                        `#rep-${dataId} .representatives-list .badge.bg-primary`
                                    )
                                    .before(newRepresentative);

                                modal.modal('hide');
                            } else {
                                response.message && showSwalMessage('error', response
                                    .message);
                                $('#error').removeClass('invalid-feedback')
                                    .html('');
                                $('input').removeClass('is-invalid');
                                $.each(response.errors, function(index, message) {
                                    $(`#${index}`)
                                        .addClass('is-invalid')
                                        .siblings('small')
                                        .addClass('invalid-feedback').html(message);
                                });

                            }
                        }
                    })
                })

                $(document).on('click', '.edit-representative', function() {

                    var representativeId = $(this).data('id');

                    $("#modalLabel").html('Cập nhật người đại diện')

                    $.ajax({
                        url: "{{ route('admin.representative.edit', ':id') }}".replace(':id',
                            representativeId),
                        type: 'GET',
                        success: function(response) {
                            if (response.status) {

                                $("#name").val(response.data.name);
                                $("#email").val(response.data.email);
                                $("#phone").val(response.data.phone);
                                $("#position").val(response.data.position);

                                $("#representativeForm").attr('action',
                                    "{{ route('admin.representative.update', ':id') }}"
                                    .replace(':id', representativeId));

                                $("#addRepresentatives").modal('show');

                            }
                        }
                    })
                })

                $(document).on('click', '.delete-representative', function(e) {
                    e.stopPropagation();
                    var $this = $(this);
                    // Get the representative ID from the closest .badge element
                    var representativeId = $this.closest('.badge').data('id');
                    var $badge = $this.closest('.badge');

                    Swal.fire({
                        title: "@lang('Xóa người đại diện')",
                        text: "@lang('Bạn chắc chắn có muốn xóa không')?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: "@lang('Đồng ý')",
                        cancelButtonText: "@lang('Huỷ')",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.representative.destroy', ':id') }}"
                                    .replace(':id', representativeId),
                                type: 'DELETE',
                                success: function(response) {
                                    if (response.status) {
                                        $badge.remove();
                                        showSwalMessage('success', response.message,
                                            2000);
                                    } else {
                                        showSwalMessage('error', response.message);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.log(xhr.responseText);
                                }
                            });
                        }
                    });
                });

                $(document).on('click', '.btn-delete', function() {
                    const id = $(this).attr('data-id');
                    const row = $(this).closest('tr'); // Get the closest table row

                    Swal.fire({
                        title: "@lang('Xóa nhà cung cấp')?",
                        text: "@lang('Bạn chắc chắn muốn xóa nhà cung cấp này không')?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: "@lang('Đồng ý')",
                        cancelButtonText: "@lang('Huỷ')",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.supplier.destroy', ':id') }}"
                                    .replace(':id', id),
                                method: 'DELETE',
                                success: function(response) {
                                    if (response.status) {
                                        row.remove(); // Remove the row from the DOM
                                        showSwalMessage('success', response
                                            .message);
                                    } else {
                                        showSwalMessage('error', response.message);
                                    }
                                },
                                error: function(xhr) {
                                    console.log(xhr);
                                }
                            });
                        }
                    });
                });
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>

    <style>
        .btn-toggle {
            border: 1px solid #007bff;
            background-color: #007bff;
            color: #fff;
            font-size: 1rem;
            padding: 1px 4px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
            text-align: center;
            line-height: 1;
            border-radius: 50%;
            font-family: 'Courier New', Courier, monospace;
        }

        .btn-toggle:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-toggle.collapsed {
            background-color: red;
            border-color: red;
        }

        .btn-toggle::after {
            content: '+';
            display: inline-block;
        }

        .btn-toggle.collapsed::after {
            content: '−';
        }

        /* Hiệu ứng mở rộng và thu gọn */
        .collapse {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.5s ease, opacity 0.5s ease;
            opacity: 0;
        }

        .collapse.show {
            max-height: 200px;
            /* Điều chỉnh theo nhu cầu */
            opacity: 1;
        }

        .representatives-container {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ddd;
            /* Border-bottom for separation */
            padding-bottom: 8px;
            /* Optional padding */
            margin-bottom: 8px;
            /* Optional margin */
        }

        .representatives-label {
            font-weight: bold;
            margin-right: 8px;
            /* Space between label and list */
        }

        .representatives-list {
            flex: 1;
            display: flex;
            flex-wrap: wrap;
        }

        .representatives-list::after {
            content: '';
            /* Clear floats if needed */
            display: block;
            width: 100%;
        }
    </style>
@endpush

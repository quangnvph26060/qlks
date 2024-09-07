@extends('admin.layouts.app')

@section('panel')
    <div class="container mt-5">
        <div class="row">
            <!-- Khối bên trái: Form thêm mới -->
            <div class="col-md-4">
                <h3>@lang('Thêm Mới Danh Mục')</h3>
                <form action="" method="POST" id="categoryForm">
                    <div class="form-group mb-3">
                        <label for="name">Tên Danh Mục *</label>
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Nhập tên danh mục">
                        <small></small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="description">Mô tả</label>
                        <textarea name="description" id="description" cols="30" rows="5" placeholder="Mô tả"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" id="status" checked>
                            <label class="form-check-label" for="status">@lang('Hoạt động')</label>
                        </div>
                        <small></small>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button class="btn btn-secondary" type="button" onclick="location.reload()"><i
                            class="las la-arrow-left"></i> Quay lại</button>
                </form>
            </div>

            <!-- Khối bên phải: Danh sách danh mục -->
            <div class="col-md-8">
                <h3>Danh Sách Danh Mục</h3>
                <div class="scrollable-table border p-2">
                    <table class="table table-striped table-hover  table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>@lang('STT')</th>
                                <th>@lang('Tên danh mục')</th>
                                <th>@lang('Sản phẩm (số lượng)')</th>
                                <th>@lang('Trạng thái')</th>
                                @can([])
                                    <th>@lang('Hành động')</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @if ($categories->isNotEmpty())
                                @foreach ($categories as $category)
                                    <tr data-id="{{ $category->id }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->products->count() }}</td>
                                        <td>
                                            <div class="form-check form-switch" style="padding-left: 4.5em">
                                                <input class="form-check-input" data-id="{{ $category->id }}"
                                                    type="checkbox" id="update-status" @checked($category->status)>
                                            </div>
                                        </td>
                                        @can([])
                                            <td>
                                                <div class="button--group">
                                                    <button class="btn btn-sm btn-outline--primary btn-edit"
                                                        data-id="{{ $category->id }}" data-modal_title="@lang('Cập nhật danh mục')"
                                                        type="button">
                                                        <i class="fas fa-edit"></i>@lang('Sửa')
                                                    </button>
                                                    <button class="btn btn-sm btn-outline--danger btn-delete"
                                                        data-id="{{ $category->id }}" data-modal_title="@lang('Xóa danh mục')"
                                                        type="button" data-pro="{{ $category->products->count() }}">
                                                        <i class="fas fa-trash"></i>@lang('Xóa')
                                                    </button>
                                                </div>
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">@lang('Không tìm thấy dữ liệu')</td>
                                </tr>
                            @endif

                            <!-- Thêm nhiều danh mục hơn nếu cần -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-key-field placeholder="Tên danh mục" />
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                let lastId = null;

                // Đặt Swal thông báo chung
                const showSwalMessage = (icon, title, timer = 1500) => {
                    Swal.fire({
                        icon: icon,
                        html: `<h3>${title}</h3>`,
                        showConfirmButton: false,
                        timer: timer
                    });
                };

                // Reset form và remove các lớp lỗi
                const resetFormAndErrors = () => {
                    $("#categoryForm")[0].reset();
                    $('small').removeClass('invalid-feedback').html('');
                    $("input").removeClass('is-invalid');
                };

                // Cập nhật lại số thứ tự cho các hàng
                const updateTableIndexes = () => {
                    $("table tbody tr").each(function(index) {
                        $(this).find("td:first").text(index + 1);
                    });
                };

                // Tạo một hàng mới trong bảng
                const createNewRow = (data) => {
                    return `
                    <tr data-id="${data.id}">
                        <td>1</td>
                        <td>${data.name}</td>
                        <td>${data.products_count || 0}</td>
                        <td>
                            <div class="form-check form-switch" style="padding-left: 4.5em">
                                <input class="form-check-input" data-id="${data.id}" type="checkbox" id="update-status" ${data.status ? 'checked' : ''}>
                            </div>
                        </td>
                        <td>
                            <div class="button--group">
                                <button class="btn btn-sm btn-outline--primary btn-edit" data-id="${data.id}" data-modal_title="@lang('Cập nhật danh mục')" type="button">
                                    <i class="fas fa-edit"></i>@lang('Sửa')
                                </button>
                                <a href="#" class="btn btn-sm btn-outline--success" data-toggle="tooltip" data-placement="top">
                                    <i class="la la-eye"></i>@lang('Chi tiết')
                                </a>
                            </div>
                        </td>
                    </tr>`;
                };

                // Xử lý sự kiện khi click vào nút edit
                $(document).on('click', '.btn-edit', function() {
                    const id = $(this).data('id');
                    if (lastId === id) return;

                    lastId = id;
                    resetFormAndErrors();
                    $(".col-md-4 h3").html("@lang('Cập nhật Danh Mục')");
                    $(".table .form-check-input").prop('disabled', true); // Disable tất cả checkbox

                    $.ajax({
                        url: "{{ route('admin.category.edit', ':id') }}".replace(':id', id),
                        method: "GET",
                        success: function(response) {
                            if (response.status) {
                                $("#name").val(response.data.name);
                                $("#description").val(response.data.description);
                                $("#status").prop('checked', response.data.status);
                                $("form").attr('data-id', response.data.id);
                            } else {
                                showSwalMessage('error', '@lang('404 Not Found')');
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr);
                        }
                    });
                });

                // Xử lý sự kiện thay đổi trạng thái checkbox
                $(document).on('change', '#update-status', function() {
                    const isChecked = this.checked;
                    const id = $(this).data('id');
                    const $checkbox = $(this);

                    Swal.fire({
                        title: "@lang('Cập nhật trạng thái')",
                        text: "@lang('Bạn có chắc chắn muốn cập nhật không?')",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: "@lang('Xác nhận')"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.category.update.status') }}",
                                method: "PUT",
                                data: {
                                    id
                                },
                                success: function(response) {
                                    if (response.status) {
                                        showSwalMessage('success', response
                                            .message);
                                    }
                                }
                            });
                        } else {
                            $checkbox.prop('checked', !
                                isChecked); // Nếu hủy, hoàn tác trạng thái checkbox
                        }
                    });
                });

                // Xử lý sự kiện submit form
                $("#categoryForm").on('submit', function(e) {
                    e.preventDefault();
                    const id = $("#categoryForm").data("id");
                    const url = id ? "{{ route('admin.category.update', ':id') }}".replace(':id', id) :
                        "{{ route('admin.category.store') }}";
                    const method = id ? "PUT" : "POST";

                    $.ajax({
                        url: url,
                        method: method,
                        data: $(this).serializeArray(),
                        success: function(response) {
                            if (response.status) {
                                lastId = null;

                                if (id) {
                                    htmlStringSuccessUpdate(response);
                                } else {
                                    $("table tbody").prepend(createNewRow(response.data));
                                    updateTableIndexes();
                                }

                                // Sau khi cập nhật xong, mở lại tất cả checkbox và chuyển về trạng thái thêm mới
                                $(".table .form-check-input").prop('disabled',
                                    false); // Mở lại tất cả checkbox
                                $(".col-md-4 h3").html(
                                    "@lang('Thêm mới Danh Mục')"
                                ); // Đổi tiêu đề form thành "Thêm mới"
                                resetFormAndErrors(); // Reset form
                                showSwalMessage('success', response.message);
                            } else {
                                $('small').removeClass('invalid-feedback').html('');
                                $("input").removeClass('is-invalid');
                                $.each(response.errors, function(index, message) {
                                    $(`#${index}`).addClass('is-invalid').siblings(
                                            'small').addClass('invalid-feedback')
                                        .html(message);
                                });
                            }
                        }
                    });
                });

                // Hàm cập nhật hàng khi chỉnh sửa danh mục thành công
                function htmlStringSuccessUpdate(response) {
                    const $row = $("tr[data-id=" + response.data.id + "]");
                    $row.find("td:nth-child(2)").text(response.data.name);
                    $row.find("td:nth-child(3)").text(response.data.products_count || 0);
                    $row.find("td:nth-child(4) input").prop('checked', response.data.status);
                }

                function deleteCategory(id) {
                    $.ajax({
                        url: "{{ route('admin.category.destroy', ':id') }}".replace(':id', id),
                        method: "DELETE",
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    // Xóa thành công, cập nhật danh sách
                                    location.reload();
                                });
                            } else {
                                showSwalMessage('error', response.message);
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr);
                            showSwalMessage('error', '@lang('Có lỗi xảy ra khi xóa danh mục.')');
                        }
                    });
                }

                // Sử lý sự kiện xóa
                $(document).on('click', '.btn-delete', function() {
                    const id = $(this).data('id');
                    const productCount = $(this).data('pro'); // Số lượng sản phẩm

                    console.log(productCount);


                    // Xử lý xác nhận xóa
                    if (productCount > 0) {
                        Swal.fire({
                            title: '@lang('Danh mục này có sản phẩm. Bạn có chắc chắn muốn xóa không?')',
                            text: '@lang('Danh mục này sẽ bị xóa và tất cả các sản phẩm bên trong cũng sẽ bị xóa!')',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: '@lang('Xóa')',
                            cancelButtonText: '@lang('Hủy')'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                deleteCategory(id);
                            }
                        });
                    } else {
                        Swal.fire({
                            title: '@lang('Bạn có chắc chắn muốn xóa danh mục này không?')',
                            text: '@lang('Danh mục này sẽ bị xóa và không thể hoàn tác!')',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: '@lang('Xóa')',
                            cancelButtonText: '@lang('Hủy')'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                deleteCategory(id);
                            }
                        });
                    }
                });
            })
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .scrollable-table {
            max-height: 400px;
            overflow-y: auto;
        }

        .scrollable-table table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
@endpush

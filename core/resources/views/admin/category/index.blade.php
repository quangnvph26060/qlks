@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <!-- Khối bên trái: Form thêm mới -->
        <div class="col-md-4">
            <h3>@lang('Thêm Mới Danh Mục')</h3>
            <form action="" method="POST" id="categoryForm">
                <div class="form-group mb-3">
                    <label for="name">Tên Danh Mục *</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên danh mục">
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
            <h3 class="my-3">Danh Sách Danh Mục</h3>
            <div class="scrollable-table border p-2">
                <div class="d-flex justify-content-between mb-3">
                    <div class="dt-length">
                        <select name="example_length" id="perPage" style=" padding: 1px 3px; margin-right: 8px;"
                            aria-controls="example" class="perPage">
                            <option value="10">10</option>
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
                <div class="card b-radius--10">
                    <div class="card-body p-0">
                        <div class="table-responsive--sm table-responsive">
                            <table class="table--light style--two table" id="data-table">
                                <thead>
                                    <tr>
                                        <th>@lang('STT')</th>
                                        <th>@lang('Tên danh mục')</th>
                                        <th class="w-25">@lang('Sản phẩm (số lượng)')</th>
                                        <th>@lang('Trạng thái')</th>
                                        @can([])
                                            <th>@lang('Hành động')</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="pagination" class="mt-3">
                {{-- {{ $categories->links('vendor.pagination.custom') }} --}}
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/sweetalert2@11.js') }}"></script>
@endpush

@push('script')
    <script src="{{ asset('assets/admin/js/dataTable.js') }}"></script>
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {

                let lastId = null;
                const apiUrl = '{{ route('admin.category.index') }}'; // Thay đổi URL phù hợp
                initDataFetch(apiUrl, true);


                // Reset form và remove các lớp lỗi
                const resetFormAndErrors = () => {
                    $("#categoryForm")[0].reset();
                    $('small').removeClass('invalid-feedback').html('');
                    $("input").removeClass('is-invalid');
                };

                // Cập nhật lại số thứ tự cho các hàng
                const updateTableIndexes = () => {
                    const perPage = parseInt($('.perPage').val());
                    $("table tbody tr").each(function(index) {
                        const rowIndex = (currentPage - 1) * perPage + index + 1;
                        $(this).find("td:first").text(rowIndex);
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
                                <input class="form-check-input update-status" data-id="${data.id}" type="checkbox" ${data.status ? 'checked' : ''}>
                            </div>
                        </td>
                        <td>
                            <div class="button--group">
                                <button class="btn btn-sm btn-outline--primary btn-edit" data-id="${data.id}" data-modal_title="@lang('Cập nhật danh mục')" type="button">
                                    <i class="fas fa-edit"></i>@lang('Sửa')
                                </button>
                                <button class="btn btn-sm btn-outline--danger btn-delete"
                                    data-id="${data.id}" data-modal_title="@lang('Xóa danh mục')"
                                    type="button" data-pro="0">
                                    <i class="fas fa-trash"></i>@lang('Xóa')
                                </button>
                            </div>
                        </td>
                    </tr>`;
                };

                // khóa nút xóa của record
                function disabledDeleteBtn(id) {
                    $(".btn-delete").prop('disabled', false);
                }

                // Xử lý sự kiện khi click vào nút edit
                $(document).on('click', '.btn-edit', function() {
                    const id = $(this).data('id');
                    disabledDeleteBtn(id);
                    $(".btn-delete[data-id='" + id + "']").prop('disabled', true);

                    if (lastId === id) return;

                    lastId = id;
                    resetFormAndErrors();
                    $(".col-md-4 h3").html("@lang('Cập nhật Danh Mục')");
                    $(".table .form-check-input").prop('disabled', true); // Disable all checkboxes

                    $.ajax({
                        url: "{{ route('admin.category.edit', ':id') }}".replace(':id', id),
                        method: "GET",
                        success: function(response) {
                            if (response.status) {
                                $("#name").val(response.data.name);
                                $("#description").val(response.data.description);
                                $("#status").prop('checked', response.data.status);
                                $("form").attr('data-id', response.data
                                    .id); // Set data-id on the form
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
                $(document).on('change', '.update-status', function() {
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
                    const id = e.target.getAttribute('data-id');

                    console.log(id);


                    const url = id ? "{{ route('admin.category.update', ':id') }}".replace(':id',
                            id) :
                        "{{ route('admin.category.store') }}";
                    const method = id ? "PUT" : "POST";

                    $.ajax({
                        url: url,
                        method: method,
                        data: $(this).serializeArray(),
                        success: function(response) {
                            if (response.status) {
                                lastId = null;
                                disabledDeleteBtn(id);
                                if (id) {
                                    htmlStringSuccessUpdate(response);
                                } else {
                                    $("table tbody").prepend(createNewRow(response
                                        .data));
                                    updateTableIndexes();
                                    4
                                    notData();
                                }

                                $(".table .form-check-input").prop('disabled', false);
                                $(".col-md-4 h3").html("@lang('Thêm mới Danh Mục')");
                                resetFormAndErrors();
                                showSwalMessage('success', response.message);
                            } else {
                                $('small').removeClass('invalid-feedback').html('');
                                $("input").removeClass('is-invalid');
                                $.each(response.errors, function(index, message) {
                                    $(`#${index}`).addClass('is-invalid')
                                        .siblings(
                                            'small').addClass(
                                            'invalid-feedback')
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
                    $("#categoryForm").removeAttr('data-id');
                }

                function deleteCategory(id) {
                    $.ajax({
                        url: "{{ route('admin.category.destroy', ':id') }}".replace(':id', id),
                        method: "DELETE",
                        success: function(response) {
                            if (response.status) {
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: "top-end",
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.onmouseenter = Swal.stopTimer;
                                        toast.onmouseleave = Swal.resumeTimer;
                                    }
                                });

                                // Xóa hàng tương ứng
                                $('tr[data-id="' + id + '"]').remove();
                                updateTableIndexes();
                                notData
                                    (); // Kiểm tra và hiển thị thông báo nếu không có bản ghi

                                Toast.fire({
                                    icon: 'success',
                                    title: `<p>${response.message}</p>`
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
                $(document).on('click', '.btn-delete', function(e) {
                    e.preventDefault();
                    const id = $(this).data('id');
                    const productCount = $(this).data('pro'); // Số lượng sản phẩm

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


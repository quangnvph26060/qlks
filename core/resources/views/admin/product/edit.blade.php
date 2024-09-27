@extends('admin.layouts.app')
@section('panel')
    <form action="" method="POST" enctype="multipart/form-data" id="productForm">

        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Thông tin sản phẩm</h5>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="form-group mb-3 col-lg-6">
                                <label for="name" class="form-label">Tên sản phẩm</label>
                                <input type="text" name="name" id="name" value="{{ $product->name }}"
                                    class="form-control" placeholder="Nhập tên sản phẩm">
                                <small></small>
                            </div>
                            <div class="form-group mb-3 col-lg-3">
                                <label for="import_price" class="form-label">Giá nhập</label>
                                <input type="text" name="import_price" value="{{ $product->import_price }}"
                                    id="import_price" class="form-control" placeholder="Giá nhập">
                                <small></small>
                            </div>
                            <div class="form-group mb-3 col-lg-3">
                                <label for="selling_price" class="form-label">Giá bán</label>
                                <input type="text" name="selling_price" value="{{ $product->selling_price }}"
                                    id="selling_price" class="form-control" placeholder="Giá bán">
                                <small></small>
                            </div>
                            <div class="form-group mb-3 col-lg-12">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea name="description" id="description" cols="30" rows="10" placeholder="Mô tả">{{ $product->description }}</textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title">Tồn kho</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group mb-3 col-lg-6">
                                <label for="stock" class="form-label">Tồn kho</label>
                                <input type="text" value="{{ $product->stock }}" name="stock" id="stock"
                                    class="form-control" placeholder="Tồn kho">
                            </div>
                            <div class="form-group mb-3 col-lg-6">
                                <label for="sku" class="form-label">SKU</label>
                                <input value="{{ $product->sku }}" type="text" name="sku" id="sku"
                                    class="form-control" placeholder="Mã sản phẩm">
                                <small></small>
                            </div>
                            <div class="form-group mb-3 col-lg-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" name="is_published" type="checkbox" id="is_published"
                                        @checked($product->is_published)>
                                    <label class="form-check-label" for="is_published">Xuất bản</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Danh mục</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="category" class="form-label">Tên danh mục</label>
                            <select name="category_id" id="category_id" class="form-select">
                                <option disabled selected>--- Chọn danh mục ---</option>
                                @foreach ($categories as $id => $category)
                                    <option value="{{ $id }}" @selected($id == $product->category_id)>{{ $category }}
                                    </option>
                                @endforeach
                            </select>
                            <small></small>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title">Thương hiệu</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="category" class="form-label">Tên thương hiệu</label>
                            <select name="brand_id" id="brand_id" class="form-select">
                                <option disabled selected>--- Chọn thương hiệu ---</option>
                                @foreach ($brands as $id => $brand)
                                    <option value="{{ $id }}" @selected($id == $product->brand_id)>{{ $brand }}
                                    </option>
                                @endforeach
                            </select>
                            <small></small>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title">Ảnh đại diện</h5>
                    </div>
                    <div class="card-body py-0 px-3">
                        <div class="form-group mb-3">
                            <div class="upload-box" id="image_path">
                                <input type="file" id="image" name="image_path" accept="image/*">
                                <label for="image" class="upload-label">
                                    <i class="fas fa-cloud-upload-alt"
                                        style="display: {{ $product->image_path ? 'none' : 'block' }};"></i>
                                    <span style="display: {{ $product->image_path ? 'none' : 'block' }};">Upload
                                        Image</span>
                                </label>
                                <img id="preview" class="preview-image"
                                    src="{{ \Storage::url($product->image_path) }}" alt="Preview Image"
                                    style="display: {{ $product->image_path ? 'block' : 'none' }};">
                            </div>
                            <small></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="my-3">
            <button type="submit" class="btn btn--primary btn-block">Thực hiện</button>
            <a href="javascript:void(0)" class="btn btn-outline--secondary btn-block" id="btn-reset">Đặt lại</a>
        </div>
    </form>
    @push('breadcrumb-plugins')
        <a class="btn btn-sm btn-outline--danger" href="{{ route('admin.product.index') }}"><i
                class="las la-list"></i>@lang('Danh sách sản phẩm')</a>
    @endpush
@endsection

@push('script')
    <script src="{{ asset('assets/admin/js/ckeditor.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('input[name="sku"]').on('input', function() {
                this.value = this.value.toUpperCase();
            });
        });
        (function($) {
            "use strict";
            $(document).ready(function() {
                ClassicEditor
                    .create(document.querySelector('#description'))
                    .catch(error => {
                        console.error(error);
                    });

                $('#image').on('change', function(event) {
                    var input = event.target;
                    var preview = $('#preview'); // Sử dụng jQuery để lấy thẻ img preview
                    var labelIcon = $('.upload-label i'); // Sử dụng jQuery để lấy icon
                    var labelText = $('.upload-label span'); // Sử dụng jQuery để lấy text

                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        // Xóa src của ảnh trước đó để tránh hiển thị ảnh cũ
                        preview.attr('src', '');

                        reader.onload = function(e) {
                            // Cập nhật ảnh mới
                            preview.attr('src', e.target.result).show();
                            labelIcon.hide(); // Ẩn icon
                            labelText.hide(); // Ẩn text
                        };

                        // Đọc file dưới dạng URL base64
                        reader.readAsDataURL(input.files[0]);
                    } else {
                        // Nếu không có file nào được chọn, hiển thị lại icon và văn bản
                        labelIcon.show();
                        labelText.show();
                        preview.hide(); // Ẩn ảnh xem trước
                    }
                });

            });

            $('#btn-reset').on('click', function() {
                resetForm();
            });

            function resetForm(is_error = false) {
                !is_error && $("#productForm").trigger("reset");
                $('input, select').removeClass('is-invalid');
                $('small').removeClass('invalid-feedback').html('');
            }

            $("#productForm").on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                formData.append('_method', 'PUT');

                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.product.update', $product->id) }}',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status) {
                            window.location.href = "{{ route('admin.product.index') }}";
                        } else {
                            resetForm(true);
                            $.each(response.errors, function(index, message) {
                                $(`#${index}`)
                                    .addClass('is-invalid')
                                    .siblings('small')
                                    .addClass('invalid-feedback').html(message);
                            });
                        }
                    }
                });
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .ck-editor__editable {
            min-height: 155px;

            height: auto;

        }

        .upload-box {
            width: 100%;
            height: 200px;
            border: 2px dashed #ccc;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            position: relative;
            margin: 20px auto;
            transition: border-color 0.3s ease;
            overflow: hidden;
        }

        .upload-box:hover {
            border-color: #999;
        }

        .upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 16px;
            color: #666;
            z-index: 2;
        }

        .upload-label i {
            font-size: 40px;
            margin-bottom: 10px;
            color: #666;
        }

        .upload-box input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 3;
            /* Đảm bảo input file nằm trên cùng để có thể click vào */
        }

        .upload-box span {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
            text-align: center;
        }

        .preview-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
        }
    </style>
@endpush

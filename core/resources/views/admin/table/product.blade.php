@foreach ($response as $product)
    <tr data-id="{{ $product->id }}">
        <td>
            <button class="btn btn-link btn-toggle" type="button"
                onclick=" toggleRepresentatives('{{ $product->id }}', this)"></button>
        </td>
        <td data-label="@lang('Mã')">{{ $product->sku }}</td>
        <td data-label="@lang('Ảnh')"><img class="img-fluid" width="80px"
                src="{{ \Storage::url($product->image_path) }}" alt=""></td>
        <td data-label="@lang('Tên sản phẩm')">
            <p id="ellipsis">{{ $product->name ?? "" }}</p>
        </td>
        <td data-label="@lang('Giá nhập')">{{ showAmount($product->import_price) }}</td>
        <td data-label="@lang('Giá bán')">{{ showAmount($product->selling_price) }}</td>
        <td data-label="@lang('Tồn kho')">{{ $product->stock ?? 0 }}</td>
        @can([])
            <td>
                <div class="button--group">
                    <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-sm btn-outline--primary"
                        data-modal_title="@lang('Cập nhật sản phẩm')">
                        <i class="fas fa-edit"></i>@lang('Sửa')
                    </a>
                    <button class="btn btn-sm btn-outline--danger btn-delete" data-modal_title="@lang('Xóa sản phẩm')"
                        type="button">
                        <i class="fas fa-trash"></i>@lang('Xóa')
                    </button>
                </div>
            </td>
        @endcan
    </tr>
    <tr class="collapse" id="rep-{{ $product->id }}">
        <td colspan="8">
            <div class="representatives-container">
                <span class="representatives-label">Danh mục:</span>
                <span class="representatives-list">
                    <span class="badge bg-warning me-2 cursor-pointer">
                        <small class="representative-name"> {{ $product->category->name }}</small>
                    </span>
                </span>
            </div>
            <div class="representatives-container">
                <span class="representatives-label">Thương hiệu:</span>
                <span class="representatives-list">
                    <span class="badge bg-primary me-2 cursor-pointer">
                        <small class="representative-name"> {{ $product->brand->name }}</small>
                    </span>
                </span>
            </div>
            <div class="representatives-container">
                <span class="representatives-label">Xuất bản:</span>
                <span class="representatives-list">
                    <div class="form-check form-switch m-0">
                        <input class="form-check-input update-status" type="checkbox" id="is_published"
                            @checked($product->is_published)>
                    </div>
                </span>
            </div>
        </td>
    </tr>
@endforeach

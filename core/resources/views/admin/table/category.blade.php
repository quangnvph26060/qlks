
@if ($response->isNotEmpty())
@foreach ($response as $category)
    <tr data-id="{{ $category->id }}">
        <td data-label="@lang('STT')">{{ $loop->iteration }}</td>
        <td data-label="@lang('Tên danh mục')">{{ $category->name }}</td>
        <td data-label="@lang('Sản phẩm (số lượng)')">{{ $category->products->count() }}</td>
        <td data-label="@lang('Trạng thái')">
            <div class="form-check form-switch" style="padding-left: 4.5em">
                <input class="form-check-input update-status" data-id="{{ $category->id }}" type="checkbox"
                    @checked($category->status)>
            </div>
        </td>
        @can([])
            <td data-label="@lang('Hành động')">
                <div class="button--group">
                    <button class="btn btn-sm btn-outline--primary btn-edit" data-id="{{ $category->id }}"
                        data-modal_title="@lang('Cập nhật danh mục')" type="button">
                        <i class="fas fa-edit"></i>@lang('Sửa')
                    </button>
                    <button class="btn btn-sm btn-outline--danger btn-delete" data-id="{{ $category->id }}"
                        data-modal_title="@lang('Xóa danh mục')" type="button"
                        data-pro="{{ $category->products->count() }}">
                        <i class="fas fa-trash"></i>@lang('Xóa')
                    </button>
                </div>
            </td>
        @endcan
    </tr>
@endforeach
@endif

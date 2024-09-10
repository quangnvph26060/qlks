
@if ($response->isNotEmpty())
@foreach ($response as $category)
    <tr data-id="{{ $category->id }}">
        <td>{{ $loop->iteration }}</td>
        <td>{{ $category->name }}</td>
        <td>{{ $category->products->count() }}</td>
        <td>
            <div class="form-check form-switch" style="padding-left: 4.5em">
                <input class="form-check-input" data-id="{{ $category->id }}" type="checkbox" id="update-status"
                    @checked($category->status)>
            </div>
        </td>
        @can([])
            <td>
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

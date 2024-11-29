@if ($response->isNotEmpty())
    @foreach ($response as $brand)
        <tr data-id="{{ $brand->id }}">
            <td>{{ $loop->iteration }}</td>
            <td>{{ $brand->code ?? 'Chưa có mã thương hiệu' }}</td>
            <td>{{ $brand->name }}</td>
            <td>{{ $brand->products->count() }}</td>
            <td>
                <div class="form-check form-switch" style="padding-left: 4.5em">
                    <input class="form-check-input" data-id="{{ $brand->id }}" type="checkbox" id="update-status"
                        @checked($brand->is_active)>
                </div>
            </td>
            @can([])
                <td>
                    <div class="button--group">
                        <button class="btn btn-sm btn-outline--primary btn-edit" data-id="{{ $brand->id }}"
                            data-modal_title="@lang('Cập nhật danh mục')" type="button">
                            <i class="fas fa-edit"></i>@lang('Sửa')
                        </button>
                        <button class="btn btn-sm btn-outline--danger btn-delete" data-id="{{ $brand->id }}"
                            data-modal_title="@lang('Xóa danh mục')" type="button" data-pro="{{ $brand->products->count() }}">
                            <i class="fas fa-trash"></i>@lang('Xóa')
                        </button>
                    </div>
                </td>
            @endcan
        </tr>
    @endforeach
@endif

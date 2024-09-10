@foreach ($response as $item)
    <tr style="border-bottom: 1px solid #dee2e6">
        <td>
            <button class="btn btn-link btn-toggle" type="button"
                onclick=" toggleRepresentatives('{{ $item->id }}', this)"></button>
        </td>
        <td>{{ $item->name }}</td>
        <td>{{ $item->email }}</td>
        <td>{{ $item->phone }}</td>
        <td>{{ $item->address }}</td>
        @can([])
            <td>
                <div class="button--group">
                    <button class="btn btn-sm btn-outline--primary btn-edit" data-id="{{ $item->id }}"
                        data-modal_title="@lang('Cập nhật danh mục')" type="button">
                        <i class="fas fa-edit"></i>@lang('Sửa')
                    </button>
                    <button class="btn btn-sm btn-outline--danger btn-delete" data-id="{{ $item->id }}"
                        data-modal_title="@lang('Xóa danh mục')" type="button" data-pro="{{ $item->products->count() }}">
                        <i class="fas fa-trash"></i>@lang('Xóa')
                    </button>
                </div>
            </td>
        @endcan
    </tr>
    <tr class="collapse" id="rep-{{ $item->id }}">
        <td colspan="6">
            <div class="representatives-container">
                <span class="representatives-label">Người đại diện:</span>
                <span class="representatives-list">
                    @foreach ($item->supplier_representatives as $rep)
                        <span class="badge bg-info me-2 position-relative">
                            {{ $rep->name }}
                            <small class="bg-danger rounded-circle p-1 position-absolute delete-representative"
                                data-id="{{ $rep->id }}"
                                style="cursor: pointer; top: -7px !important; right: -5px !important;">x</small>
                        </span>
                    @endforeach
                    <span class="badge bg-primary cursor-pointer" data-toggle="modal" data-id="{{ $item->id }}"
                        data-target="#addRepresentatives">Thêm
                        (+)</span>
                </span>
            </div>
        </td>
    </tr>
@endforeach

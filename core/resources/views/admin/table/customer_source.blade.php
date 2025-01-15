@if ($response->isNotEmpty())
    @foreach ($response as $item)
        <tr data-id="{{ $item->id }}">
            <td data-label="@lang('STT')">{{ $loop->iteration }}</td>
            <td data-label="@lang('Mã nguồn')">{{ $item->source_code ?? '' }}</td>
            <td data-label="@lang('Tên nguồn')">{{ $item->source_name }}</td>
            <td data-label="@lang('Mã đơn vị')">{{ $item->unit_code }}</td>
          
            @can([])
                <td data-label="@lang('Hành động')">
                    <div class="button--group">
                        <button class="btn btn-sm btn-outline--primary btn-edit" data-id="{{ $item->id }}"
                            data-modal_title="@lang('Cập nhật nguồn khách')" type="button">
                            <i class="fas fa-edit"></i>@lang('Sửa')
                        </button>
                        <button class="btn btn-sm btn-outline--danger btn-delete" data-id="{{ $item->id }}"
                            data-modal_title="@lang('Xóa  nguồn khách')" type="button"
                            data-pro="">
                            <i class="fas fa-trash"></i>@lang('Xóa')
                        </button>
                    </div>
                </td>
            @endcan
        </tr>
    @endforeach
@endif

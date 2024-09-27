@if ($response->isNotEmpty())
    @foreach ($response as $price)
        <tr data-id="{{ $price->id }}">
            <th>{{ $loop->iteration }}</th>
            <td>{{ $price->code }}</td>
            <td>{{ $price->name }}</td>
            <td>{{ showAmount($price->price) }}</td>
            <td>{{ \Carbon\Carbon::parse($price->start_date)->format('d/m/Y H:i') }}</td>
            <td>{{ \Carbon\Carbon::parse($price->end_date)->format('d/m/Y H:i') }}</td>
            <td>
                <div class="radio-container">
                    <label class="toggle">
                        <input type="checkbox" class="status-change" data-id="{{ $price->id }}" @checked($price->status == 'active')>
                        <span class="slider"></span>
                    </label>
                </div>
            </td>
            @can([])
                <td>
                    <div class="button--group">
                        <button class="btn btn-sm btn-outline--primary btn-edit" data-id="{{ $price->id }}"
                            data-modal_title="@lang('Cập nhật danh mục')" type="button">
                            <i class="fas fa-edit"></i>@lang('Sửa')
                        </button>
                        <button class="btn btn-sm btn-outline--danger btn-delete" data-id="{{ $price->id }}"
                            data-modal_title="@lang('Xóa danh mục')" type="button">
                            <i class="fas fa-trash"></i>@lang('Xóa')
                        </button>
                    </div>
                </td>
            @endcan
        </tr>
    @endforeach
@endif

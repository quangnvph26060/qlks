<table class="table table--light style--two custom-data-table">
    <thead>
        <tr>
            <th>@lang('Tên phương thức')</th>
            <th>@lang('Trạng thái')</th>
            @can(['admin.transaction.edit'])
                <th>@lang('Hành động')</th>
            @endcan
        </tr>
    </thead>
    <tbody>
        @forelse($transactions->sortBy('alias') as $k => $transaction)
            <tr>
                <td>
                    {{ __($transaction->name) }}
                </td>
                <td>
                    @if ($transaction->status == 1)
                        Đang hoạt động
                    @else
                        Không hoạt động
                    @endif
                </td>
                @can(['admin.transaction.edit', 'admin.transaction.changeStatus'])
                    <td>
                        <div class="button--group">
                            @can('admin.transaction.edit')
                                <a href="{{ route('admin.transaction.edit', ['id' => $transaction->id]) }}"
                                    class="btn btn-sm btn-outline--primary editGatewayBtn">
                                    <i class="la la-pencil"></i>@lang('Sửa')
                                </a>
                            @endcan

                            @can('admin.transaction.changeStatus')
                                @if ($transaction->status == 0)
                                    <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn"
                                        data-question="@lang('Bạn muốn cho phép phương thức thanh toán này?')"
                                        data-action="{{ route('admin.transaction.changeStatus', $transaction->id) }}">
                                        <i class="la la-eye"></i> @lang('Cho phép')
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-outline--warning ms-1 confirmationBtn"
                                        data-question="@lang('Bạn muốn cấm phương thức thanh toán này?')"
                                        data-action="{{ route('admin.transaction.changeStatus', $transaction->id) }}">
                                        <i class="la la-eye-slash"></i> @lang('Ngưng hoạt động')
                                    </button>
                                @endif
                            @endcan
                            @can('admin.transaction.edit')
                                <a href="{{ route('admin.transaction.delete', ['id' => $transaction->id]) }}"
                                    class="btn btn-sm btn-outline--danger btn-delete editGatewayBtn">
                                    <i class="la la-trash"></i>@lang('Xóa')
                                </a>
                            @endcan
                        </div>
                    </td>
                @endcan
            </tr>
        @empty
            <tr>
                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
            </tr>
        @endforelse
    </tbody>
</table><!-- table end -->

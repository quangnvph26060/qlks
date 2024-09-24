@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>@lang('Tên phòng')</th>
                                    <th>@lang('Giá')</th>
                                    <th>@lang('Trạng thái')</th>
                                    @can(['admin.hotel.room.type.edit', 'admin.hotel.room.type.status'])
                                        <th>@lang('Hành động')</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($typeList as $type)
                                    <tr>
                                        <td>
                                            <button class="btn btn-link btn-toggle" type="button"
                                                onclick=" toggleRepresentatives('{{ $type->id }}', this)"></button>
                                        </td>
                                        <td>
                                            {{ $type->name }}
                                        </td>

                                        <td>
                                            <span class="fw-bold">
                                                {{ showAmount($type->fare) }}
                                            </span>
                                        </td>


                                        <td>@php echo $type->statusBadge  @endphp</td>
                                        @can(['admin.hotel.room.type.edit', 'admin.hotel.room.type.status'])
                                            <td>
                                                <div class="button--group">
                                                    @can('admin.hotel.room.type.edit')
                                                        <a class="btn btn-sm btn-outline--primary"
                                                            href="{{ route('admin.hotel.room.type.edit', $type->id) }}"> <i
                                                                class="la la-pencil"></i>@lang('Sửa')
                                                        </a>
                                                    @endcan
                                                    @can('admin.hotel.room.type.status')
                                                        @if ($type->status == 0)
                                                            <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn"
                                                                data-action="{{ route('admin.hotel.room.type.status', $type->id) }}"
                                                                data-question="@lang('Bạn có chắc chắn muốn bật loại phòng này không?')">
                                                                <i class="la la-eye"></i> @lang('Cho phép')
                                                            </button>
                                                        @else
                                                            <button class="btn btn-sm btn-outline--danger ms-1 confirmationBtn"
                                                                data-action="{{ route('admin.hotel.room.type.status', $type->id) }}"
                                                                data-question="@lang('Bạn có chắc chắn muốn vô hiệu hóa loại phòng này không?')">
                                                                <i class="la la-eye-slash"></i> @lang('Ngưng hoạt động')
                                                            </button>
                                                        @endif
                                                    @endcan
                                                </div>
                                            </td>
                                        @endcan

                                    </tr>

                                    <tr class="collapse" id="rep-{{ $type->id }}">
                                        <td colspan="8">
                                            @if ($type->products->count() > 0)
                                                <div class="representatives-container">
                                                    <span class="representatives-label">Sản phẩm:</span>
                                                    <span class="representatives-list">
                                                        @foreach ($type->products as $product)
                                                            <span class="badge {{ getRandomColor() }} me-2 mb-1 cursor-pointer">
                                                                <small class="representative-name">
                                                                    {{ $product->name }}</small>
                                                            </span>
                                                        @endforeach
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="representatives-container">
                                                <span class="representatives-label">Số lượng người:</span>
                                                <span class="representatives-list">
                                                    {{ $type->total_adult }} người lớn | {{ $type->total_child }} trẻ em
                                                </span>
                                            </div>
                                            <div class="representatives-container">
                                                <span class="representatives-label">Trạng thái tính năng:</span>
                                                <span class="representatives-list">
                                                    {!! $type->featureBadge !!}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($typeList->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($typeList) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    @can('admin.hotel.room.type.status')
        <x-confirmation-modal />
    @endcan
@endsection
@can('admin.hotel.room.type.create')
    @push('breadcrumb-plugins')
        <a class="btn btn-sm btn-outline--primary" href="{{ route('admin.hotel.room.type.create') }}"><i
                class="las la-plus"></i>@lang('Thêm mới')</a>
    @endpush
@endcan

@push('script')
    <script>
        toggleRepresentatives = function(id, button) {
            const row = document.getElementById('rep-' + id);
            row.classList.toggle('show');
            button.classList.toggle('collapsed');
        };
    </script>
@endpush

@push('style')
    <style>
        @media (max-width: 991px) {

            .table-responsive--md tr th,
            .table-responsive--md tr td {
                padding-left: 4% !important;
            }
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #fff;
        }

        .table th,
        td {
            text-align: unset !important;
        }

        .btn-toggle {
            border: 1px solid #007bff;
            background-color: #007bff;
            color: #fff;
            font-size: 1rem;
            padding: 1px 4px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
            text-align: center;
            line-height: 1;
            border-radius: 50%;
            font-family: 'Courier New', Courier, monospace;
        }

        .btn-toggle:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-toggle.collapsed {
            background-color: red;
            border-color: red;
        }

        .btn-toggle::after {
            content: '+';
            display: inline-block;
        }

        .btn-toggle.collapsed::after {
            content: '−';
        }

        .collapse {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.5s ease, opacity 0.5s ease;
            opacity: 0;
        }

        .collapse.show {
            max-height: 200px;
            /* Điều chỉnh theo nhu cầu */
            opacity: 1;
        }

        .representatives-container {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ddd;
            /* Border-bottom for separation */
            padding-bottom: 8px;
            /* Optional padding */
            margin-bottom: 8px;
            /* Optional margin */
        }

        .representatives-label {
            font-weight: bold;
            margin-right: 8px;
            /* Space between label and list */
        }

        .representatives-list {
            flex: 1;
            display: flex;
            flex-wrap: wrap;
        }

        .representatives-list::after {
            content: '';
            /* Clear floats if needed */
            display: block;
            width: 100%;
        }
    </style>
@endpush

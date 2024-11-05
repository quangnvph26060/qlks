@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th></th>
                                    {{-- <th>@lang('STT')</th> --}}
                                    <th>@lang('Loại phòng')</th>
                                    <th>@lang('Tên phòng')</th>
                                    <th>@lang('Mã phòng')</th>
                                    <th>@lang('Tiện nghi')</th>
                                    <th>@lang('Cở sở vật chất')</th>
                                    <th>@lang('Các loại giá')</th>
                                    <th>@lang('Trạng thái')</th>
                                    @can(['admin.hotel.room.type.edit', 'admin.hotel.room.type.status',
                                        'admin.hotel.room.type.destroy'])
                                        <th>@lang('Hành động')</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($typeList as $type)
                                    <tr>
                                        <td>
                                            <button class="btn btn-link btn-toggle" type="button"
                                                onclick="toggleRepresentatives('{{ $type->id }}', this)"></button>
                                        </td>
                                        {{-- <td data-label="STT">{{ $loop->iteration }}</td> --}}

                                        <td data-label="Loại phòng">
                                            {{ $type->roomType->name }}
                                        </td>
                                        <td data-label="Tên phòng">
                                            {{ $type->room_number }}
                                        </td>
                                        <td data-label="Mã phòng">
                                            {{ $type->code }}
                                        </td>
                                        <td>
                                            @if ($type->amenities->count() > 0)
                                                <div class="d-flex flex-wrap">
                                                    @foreach ($type->amenities as $amenity)
                                                        <span
                                                            class="badge {{ getRandomColor() }} m-1 p-1 rounded-pill text-bg-primary">
                                                            {{ $amenity->title }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                Chưa có tiện nghi nào
                                            @endif
                                        </td>
                                        <td>
                                            @if ($type->facilities->count() > 0)
                                                <div class="d-flex flex-wrap">
                                                    @foreach ($type->facilities as $facility)
                                                        <span
                                                            class="badge {{ getRandomColor() }} m-1 p-1 rounded-pill text-bg-primary">
                                                            {{ $facility->title }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                Chưa có cơ sở vật chất
                                            @endif
                                        </td>

                                        <td>---------</td>
                                        <td>@php echo $type->statusBadge  @endphp</td>
                                        @can(['admin.hotel.room.type.deleted.all'])
                                            <td>
                                                <div class="button--group">
                                                    @can('admin.hotel.room.type.deleted.all')
                                                        <button class="btn btn-sm btn-outline--warning btn-delete"
                                                            data-id="{{ $type->id }}" data-modal_title="@lang('Xóa')"
                                                            type="button">
                                                            <i class="fas fa-trash-restore"></i>@lang('Khôi phục')
                                                        </button>
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
                                                            <span
                                                                class="badge {{ getRandomColor() }} me-2 mb-1 cursor-pointer">
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
                        </table><!-- table end -->
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
@endsection

@push('script')
    <script src="{{ asset('assets/admin/js/dataTable.js') }}"></script>

    <script>
        window.toggleRepresentatives = function(id, button) {
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

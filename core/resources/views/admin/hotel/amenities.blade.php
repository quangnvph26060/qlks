@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('Mã tiện nghi')</th>
                                    <th>@lang('Tên tiện nghi')</th>
                                    <th>@lang('Biểu tượng')</th>
                                    <th>@lang('Trạng thái')</th>
                                    @can(['admin.hotel.amenity.save', 'admin.hotel.amenity.status'])
                                        <th>@lang('Hành động')</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($amenities as $item)
                                    <tr>
                                        <td>{{ $item->amenity_id ?? 'Chưa có mã tiện nghi' }}</td>
                                        <td><span class="me-2">{{ $amenities->firstItem() + $loop->index }}.</span>
                                            {{ $item->title }}</td>
                                        <td> @php echo $item->icon @endphp </td>
                                        <td> @php echo $item->statusBadge @endphp </td>
                                        @can(['admin.hotel.amenity.save', 'admin.hotel.amenity.status'])
                                            <td>
                                                <div class="button--group">
                                                    @can('admin.hotel.amenity.save')
                                                        <button class="btn btn-sm btn-outline--primary cuModalBtn" data-has_status="1"
                                                            data-modal_title="@lang('Update Amenity')" data-resource="{{ $item }}"
                                                            type="button">
                                                            <i class="la la-pencil"></i>@lang('Sửa')
                                                        </button>
                                                    @endcan
                                                    @can('admin.hotel.amenity.status')
                                                        @if ($item->status == Status::DISABLE)
                                                            <button class="btn btn-sm btn-outline--success me-1 confirmationBtn"
                                                                data-action="{{ route('admin.hotel.amenity.status', $item->id) }}"
                                                                data-question="@lang('Bạn có chắc chắn bật tiện ích này không?')" type="button">
                                                                <i class="la la-eye"></i> @lang('Cho phép')
                                                            </button>
                                                        @else
                                                            <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                                                data-action="{{ route('admin.hotel.amenity.status', $item->id) }}"
                                                                data-question="@lang('Bạn có chắc chắn muốn vô hiệu hóa tiện ích này không?')" type="button">
                                                                <i class="la la-eye-slash"></i> @lang('Ngưng hoạt động')
                                                            </button>
                                                        @endif
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
                        </table>
                    </div>
                </div>
                @if ($amenities->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($amenities) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @can('admin.hotel.amenity.save')
        {{-- Add METHOD MODAL --}}
        <div class="modal fade" id="cuModal" role="dialog" tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"> @lang('Add Amenties')</h5>
                        <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <form action="{{ route('admin.hotel.amenity.save') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label> @lang('Mã tiện ích')</label>
                                <input class="form-control" name="amenity_id" type="text" value="{{ old('amenity_id') }}">
                            </div>
                            <div class="form-group">
                                <label> @lang('Tiện ích')</label>
                                <input class="form-control" name="title" required type="text" value="{{ old('title') }}">
                            </div>
                            <div class="form-group">
                                <label> @lang('Biểu tượng')</label>
                                <div class="input-group">
                                    <input autocomplete="off" class="form-control iconPicker icon" name="icon" required
                                        type="text">
                                    <span class="input-group-text input-group-addon" data-icon="las la-home"
                                        role="iconpicker"></span>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
    @can('admin.hotel.amenity.status')
        <x-confirmation-modal />
    @endcan
@endsection
@can('admin.hotel.amenity.save')
    @push('breadcrumb-plugins')
        <button class="btn btn-sm btn-outline--primary cuModalBtn" data-modal_title="@lang('Thêm tiện ích mới')" type="button">
            <i class="las la-plus"></i>@lang('Thêm mới')
        </button>
    @endpush
@endcan

@push('style-lib')
    <link href="{{ asset('assets/admin/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/fontawesome-iconpicker.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('#cuModal').on('shown.bs.modal', function(e) {
                $(document).off('focusin.modal');
            });

            $('.iconPicker').iconpicker().on('iconpickerSelected', function(e) {
                $('.iconPicker').val(`<i class="${e.iconpickerValue}"></i>`);
            });

        })(jQuery);
    </script>
@endpush

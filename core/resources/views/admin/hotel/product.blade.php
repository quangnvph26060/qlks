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
                                    <th>@lang('STT')</th>
                                    <th>@lang('Mã tiện nghi')</th>
                                    <th>@lang('Tên tiện nghi')</th>
                                    <th>@lang('Biểu tượng')</th>
                                    <th>@lang('Trạng thái')</th>
                                    @can(['admin.hotel.facility.save', 'admin.hotel.facility.status'])
                                        <th>@lang('Hành động')</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($facilities as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->code ?? 'Chưa có mã tiện nghi' }}</td>
                                        <td>
                                            {{ $item->title }}</td>
                                        <td> @php echo $item->icon @endphp </td>
                                        <td> @php echo $item->statusBadge @endphp </td>
                                        @can(['admin.hotel.facility.save', 'admin.hotel.facility.status'])
                                            <td>
                                                <div class="button--group">
                                                    @can('admin.hotel.facility.save')
                                                        <button class="btn btn-sm btn-outline--primary cuModalBtn" data-has_status="1"
                                                            data-modal_title="@lang('Update Facility')" data-resource="{{ $item }}"
                                                            type="button">
                                                            <i class="la la-pencil"></i>@lang('Sửa')
                                                        </button>
                                                    @endcan
                                                    @can('admin.hotel.facility.status')
                                                        @if ($item->status == Status::DISABLE)
                                                            <button class="btn btn-sm btn-outline--success me-1 confirmationBtn"
                                                                data-action="{{ route('admin.hotel.facility.status', $item->id) }}"
                                                                data-question="@lang('Bạn có chắc chắn kích hoạt tiện ích này không?')" type="button">
                                                                <i class="la la-eye"></i> @lang('Hoạt động')
                                                            </button>
                                                        @else
                                                            <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                                                data-action="{{ route('admin.hotel.facility.status', $item->id) }}"
                                                                data-question="@lang('Bạn có chắc chắn tắt tiện ích này không?')" type="button">
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
                @if ($facilities->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($facilities) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @can('admin.hotel.facility.save')
        {{-- Add METHOD MODAL --}}
        <div class="modal fade" id="cuModal" role="dialog" tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <form action="{{ route('admin.hotel.facility.save') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label> @lang('Mã cở sở vật chất')</label>
                                <input class="form-control" name="code" required type="text"
                                    value="{{ old('code') }}">
                            </div>
                            <div class="form-group">
                                <label> @lang('Cở sở vật chất')</label>
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
                            <button class="btn btn--primary w-100 h-45" type="submit">@lang('Lưu')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
    @can('admin.hotel.facility.status')
        <x-confirmation-modal />
    @endcan
@endsection
@can('admin.hotel.facility.save')
    @push('breadcrumb-plugins')
        <button class="btn btn-sm btn-outline--primary cuModalBtn" data-modal_title="@lang('Thêm Cơ sở mới')" type="button">
            <i class="las la-plus"></i>@lang('Thêm mới ')
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
        //Chuyển mọi ký tự trong input facility_id thành uppercase
        $(document).ready(function() {
            $('input[name="facility_id"]').on('input', function() {
                this.value = this.value.toUpperCase();
            });
        });

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
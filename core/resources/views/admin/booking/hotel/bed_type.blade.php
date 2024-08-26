@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Bed Type')</th>
                                    @can(['admin.hotel.bed.save', 'admin.hotel.bed.delete'])
                                        <th>@lang('Action')</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bedTypes as $item)
                                    <tr>
                                        <td>{{ $bedTypes->firstItem() + $loop->index }}</td>
                                        <td>{{ __($item->name) }}</td>
                                        @can(['admin.hotel.bed.save', 'admin.hotel.bed.delete'])
                                            <td>
                                                @can('admin.hotel.bed.save')
                                                    <button class="btn btn-sm btn-outline--primary cuModalBtn" data-modal_title="@lang('Update Bed Type')" data-resource="{{ $item }}" type="button">
                                                        <i class="la la-pencil"></i>@lang('Edit')
                                                    </button>
                                                @endcan
                                                @can('admin.hotel.bed.delete')
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.hotel.bed.delete', $item->id) }}" data-question="@lang('Are you sure, you want to delete this bed type?')" type="button">
                                                        <i class="la la-trash"></i>@lang('Delete')
                                                    </button>
                                                @endcan
                                            </td>
                                        @endcan
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($bedTypes->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($bedTypes) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @can('admin.hotel.bed.save')
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
                    <form action="{{ route('admin.hotel.bed.save') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label> @lang('Bed Type')</label>
                                <input class="form-control" name="name" required type="text" value="{{ old('type_name') }}">
                            </div>
                            <div class="status"></div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    @can('admin.hotel.bed.delete')
        <x-confirmation-modal />
    @endcan
@endsection
@can('admin.hotel.bed.save')
    @push('breadcrumb-plugins')
        <button class="btn btn-sm btn-outline--primary cuModalBtn" data-modal_title="@lang('Add New Bed Type')" type="button">
            <i class="las la-plus"></i>@lang('Add New ')
        </button>
    @endpush
@endcan
@push('script')
    <script>
        (function($) {
            "use strict";

            $('#cuModal').on('shown.bs.modal', function(e) {
                $(document).off('focusin.modal');
            });

        })(jQuery);
    </script>
@endpush

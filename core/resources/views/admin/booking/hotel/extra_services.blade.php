@extends('admin.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light table">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Cost')</th>
                                    <th>@lang('Status')</th>
                                    @can(['admin.hotel.extra_services.save', 'admin.hotel.extra_services.status'])
                                        <th>@lang('Action')</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($premiumServices as $premiumService)
                                    <tr>
                                        <td><span class="me-2">{{ $premiumServices->firstItem() + $loop->index }}.</span>{{ __($premiumService->name) }}</td>

                                        <td>
                                            {{ showAmount($premiumService->cost) }} {{ __($general->cur_text) }}
                                        </td>

                                        <td>
                                            @php echo $premiumService->statusBadge @endphp
                                        </td>
                                        @can(['admin.hotel.extra_services.save', 'admin.hotel.extra_services.status'])
                                            <td>
                                                <div class="button--group">
                                                    @can('admin.hotel.extra_services.save')
                                                        <button class="btn btn-sm btn-outline--primary cuModalBtn" data-has_status="1" data-modal_title="@lang('Update Extra Service')" data-resource="{{ $premiumService }}" type="button">
                                                            <i class="la la-pencil"></i>@lang('Edit')
                                                        </button>
                                                    @endcan

                                                    @can('admin.hotel.extra_services.status')
                                                        @if ($premiumService->status == Status::DISABLE)
                                                            <button class="btn btn-sm btn-outline--success me-1 confirmationBtn" data-action="{{ route('admin.hotel.extra_services.status', $premiumService->id) }}" data-question="@lang('Are you sure to enable this extra service?')" type="button">
                                                                <i class="la la-eye"></i> @lang('Enable')
                                                            </button>
                                                        @else
                                                            <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.hotel.extra_services.status', $premiumService->id) }}" data-question="@lang('Are you sure to disable this extra service?')" type="button">
                                                                <i class="la la-eye-slash"></i> @lang('Disable')
                                                            </button>
                                                        @endif
                                                    @endcan
                                                </div>
                                            </td>
                                        @endcan
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ $emptyMessage }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($premiumServices->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($premiumServices) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>

    {{-- Add METHOD MODAL --}}
    @can('admin.hotel.extra_services.save')
        <div class="modal fade" id="cuModal" role="dialog" tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <form action="{{ route('admin.hotel.extra_services.save') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label> @lang('Service Name')</label>
                                <input class="form-control" name="name" required type="text" value="{{ old('name') }}">
                            </div>
                            <div class="form-group">
                                <label> @lang('Cost')</label>
                                <div class="input-group">
                                    <input class="form-control" name="cost" required step="0.01" type="number" value="{{ old('cost') }}">
                                    <span class="input-group-text"> {{ $general->cur_text }}</span>
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

    <x-confirmation-modal />
@endsection

@can('admin.hotel.extra_services.save')
    @push('breadcrumb-plugins')
        <button class="btn btn-sm btn-outline--primary cuModalBtn" data-modal_title="@lang('Add Extra Service')" type="button">
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

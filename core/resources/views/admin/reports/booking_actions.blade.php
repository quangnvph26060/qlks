@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">

                    <div class="table-responsive--sm table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('Booking No.')</th>
                                    <th>@lang('Details')</th>
                                    <th>@lang('Action By')</th>
                                    <th>@lang('Date')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookingLog as $log)
                                    <tr>
                                        <td>
                                            <a href="{{ can('admin.booking.all') ? route('admin.booking.all', ['search' => @$log->booking->booking_number]) : 'javascript:void(0)' }}" class="fw-bold">#{{ @$log->booking->booking_number }}</a>
                                        </td>

                                        <td>
                                            @if ($log->details)
                                                {{ __($log->details) }}
                                            @else
                                                {{ __(keyToTitle($log->remark)) }}
                                            @endif
                                        </td>

                                        <td>
                                            {{ __(@$log->admin->name) }}
                                        </td>
                                        <td>
                                            {{ showDateTime($log->created_at) }} <br>
                                            {{ diffForHumans($log->created_at) }}
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
                @if ($bookingLog->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($bookingLog) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>

    </div>
@endsection

@push('breadcrumb-plugins')
    <form action="" method="GET" class="form-search">
        <select name="remark" class="form-control">
            <option value="">@lang('All Remark')</option>
            @foreach ($remarks as $remark)
                <option value="{{ $remark->remark }}">{{ __(keyToTitle($remark->remark)) }}</option>
            @endforeach
        </select>
    </form>

    <x-search-form placeholder="Booking No." />
@endpush

@push('script')
    <script>
        "use strict";

        $('[name=remark]').on('change', function() {
            $('.form-search').submit();
        })

        @if (request()->remark)
            let remark = @json(request()->remark);
            $(`[name=remark] option[value="${remark}"]`).prop('selected', true);
        @endif
    </script>
@endpush

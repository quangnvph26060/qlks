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
                                    <th>@lang('User')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Issued By')</th>
                                    <th>@lang('Date')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($paymentLog as $log)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ @$log->booking->booking_number }}</span>
                                        </td>

                                        <td>
                                            @if (@$log->booking->user_id)
                                                {{ __($log->booking->user->fullname) }}
                                                @can('admin.users.detail')
                                                    <br>
                                                    <span class="small">
                                                        <a href="{{ route('admin.users.detail', $log->booking->user_id) }}"><span>@</span>{{ $log->booking->user->username }}</a>
                                                    </span>
                                                @endcan
                                            @else
                                                {{ __(@$log->booking->guest_details->name) }}
                                                <br>
                                                <span class="small fw-bold">{{ @$log->booking->guest_details->email }}</span>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="fw-bold">{{ showAmount($log->amount) }}</span>
                                        </td>

                                        <td>
                                            @if ($log->admin_id)
                                                {{ __($log->admin->name) }}
                                            @else
                                                <span class="text--cyan">@lang('Direct Payment')</span>
                                            @endif
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
                @if ($paymentLog->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($paymentLog) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="User/Booking No." />
@endpush

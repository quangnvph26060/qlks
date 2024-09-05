@extends('admin.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">

                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Người dùng')</th>
                                    <th>@lang('Đăng nhập tại')</th>
                                    <th>@lang('IP')</th>
                                    <th>@lang('Ví trí')</th>
                                    <th>@lang('Browser | OS')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loginLogs as $log)
                                    <tr>

                                        <td>
                                            <span class="fw-bold">{{ @$log->user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                @can('admin.users.detail')
                                                    <a href="{{ route('admin.users.detail', $log->user_id) }}"><span>@</span>{{ @$log->user->username }}</a>
                                                @else
                                                    {{ @$log->user->username }}
                                                @endcan
                                            </span>
                                        </td>

                                        <td>
                                            {{ showDateTime($log->created_at) }} <br> {{ diffForHumans($log->created_at) }}
                                        </td>

                                        <td>
                                            <span class="fw-bold">
                                                @can('admin.report.login.ipHistory')
                                                    <a href="{{ route('admin.report.login.ipHistory', [$log->user_ip]) }}">{{ $log->user_ip }}</a>
                                                @else
                                                    {{ $log->user_ip }}
                                                @endcan
                                            </span>
                                        </td>

                                        <td>{{ __($log->city) }} <br> {{ __($log->country) }}</td>
                                        <td>
                                            {{ __($log->browser) }} <br> {{ __($log->os) }}
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
                @if ($loginLogs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($loginLogs) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>

    </div>
@endsection

@push('breadcrumb-plugins')
    @can('admin.report.login.history')
        @if (request()->routeIs('admin.report.login.history'))
            <x-search-form placeholder="Search Username" dateSearch='yes' />
        @endif
    @endcan
@endpush

@if (request()->routeIs('admin.report.login.ipHistory'))
    @can('admin.report.login.ipHistory')
        @push('breadcrumb-plugins')
            <a href="https://www.ip2location.com/{{ $ip }}" target="_blank" class="btn btn-outline--primary">@lang('Lookup IP') {{ $ip }}</a>
        @endpush
    @endcan
@endif

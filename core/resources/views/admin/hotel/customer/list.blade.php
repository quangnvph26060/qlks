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
                                <th>@lang('Tên')</th>
                                <th>@lang('Số điện thoại')</th>
                                <th>@lang('Email')</th>
                                <th>@lang('Địa chỉ')</th>
                                <th>@lang('Ngày tạo')</th>
                                <th>@lang('Ghi chú')</th>
                                <th>@lang('Trạng thái')</th>
                                <th>@lang('Mã đơn vị')</th>
{{--                            @can('admin.users.detail')--}}
{{--                                    <th>@lang('Hành động')</th>--}}
{{--                                @endcan--}}
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($customers as $customer)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $customer->name }}</span>
{{--                                        @can('admin.users.detail')--}}
{{--                                            <br>--}}
{{--                                            <span class="small">--}}
{{--                                                    <a href="{{ route('admin.users.detail', $user->id) }}"><span>@</span>{{ $user->username }}</a>--}}
{{--                                                </span>--}}
{{--                                        @endcan--}}
                                    </td>
                                    <td>
                                        {{ $customer->phone }}
                                    </td>
                                    <td>
                                        {{ $customer->email }}
                                    </td>
                                    <td>
                                        {{ $customer->address }}
                                    </td>
                                    <td>
                                        {{ showDateTime($customer->created_at) }} <br> {{ diffForHumans($customer->created_at) }}
                                    </td>

{{--                                    @can('admin.users.detail')--}}
{{--                                        <td>--}}
{{--                                            <a class="btn btn-sm btn-outline--primary" href="{{ route('admin.users.detail', $user->id) }}">--}}
{{--                                                <i class="las la-desktop"></i> @lang('Details')--}}
{{--                                            </a>--}}
{{--                                        </td>--}}
{{--                                    @endcan--}}
                                    <td>
                                        {{ $customer->note }}
                                    </td>
                                    <td class="status-hotel">
                                        {!! $customer->styleStatus() !!}
                                    </td>
                                    <td>
                                        {{ $customer->unit_code }}
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
                @if ($customers->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($customers) }}
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
@push('breadcrumb-plugins')
    <x-search-form placeholder="Username / Email" />
@endpush

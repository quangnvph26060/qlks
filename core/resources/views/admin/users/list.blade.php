@extends('admin.layouts.master_iframe')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive" style="overflow:auto">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                @can('admin.users.detail')
                                        <th>@lang('Hành động')</th>
                                    @endcan
                                    <th style="width:50px">@lang('STT')</th>

                                    <th>@lang('Tên')</th>
                                    <th>@lang('Email - Số điện thoại')</th>
                                    <th>@lang('Quốc gia')</th>
                                    <th>@lang('Ngày tạo')</th>
                               
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $id => $user)
                                    <tr>
                                         @can('admin.users.detail')
                                            <td>
                                                <a class="btn btn-sm btn-outline--primary" href="{{ route('admin.users.detail', $user->id) }}">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </a>
                                            </td>
                                        @endcan
                                        <td data-label="STT" style="text-align:right">      
                                            @php
                                              $stt = $users->total() - ($users->currentPage() - 1) * $users->perPage() - $id;
                                            @endphp
                                        {{ $stt }}
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $user->fullname }}</span>
                                            @can('admin.users.detail')
                                                -
                                                <span class="small">
                                                    <a href="{{ route('admin.users.detail', $user->id) }}"><span>@</span>{{ $user->username }}</a>
                                                </span>
                                            @endcan
                                        </td>
                                        <td>
                                            {{ $user->email }} - {{ $user->mobileNumber }}
                                        </td>
                                        <td>
                                            <span class="fw-bold" title="{{ @$user->country_name }}">{{ $user->country_code }}</span>
                                        </td>
                                        <td>
                                            {{ showDateTime($user->created_at) }} - {{ diffForHumans($user->created_at) }}
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
                @if ($users->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($users) }}
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
@push('breadcrumb-plugins')

    <a  href="{{ route('admin.users.all') }}">
            <button type="button" class="btn btn-outline--primary" data-modal_title="Làm mới">
                    <i class="fa fa-repeat p-2 "></i>

                </button>
            </a>   
            <a>
            <button type="button" class="btn btn-outline--primary btn-add ">
                    <i class="las la-plus p-2 "></i>

            </button>
    </a>     
@endpush
@push('breadcrumb-plugins')
    <x-search-form placeholder="Username / Email" />
@endpush

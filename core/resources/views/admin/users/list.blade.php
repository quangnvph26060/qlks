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
                                                <a class="btn btn-sm btn-outline--primary"
                                                    href="{{ route('admin.users.detail', $user->id) }}">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </a>
                                            </td>
                                        @endcan
                                        <td data-label="STT" style="text-align:right">
                                            @php
                                                $stt =
                                                    $users->total() -
                                                    ($users->currentPage() - 1) * $users->perPage() -
                                                    $id;
                                            @endphp
                                            {{ $stt }}
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $user->fullname }}</span>
                                            @can('admin.users.detail')
                                                -
                                                <span class="small">
                                                    <a
                                                        href="{{ route('admin.users.detail', $user->id) }}"><span>@</span>{{ $user->username }}</a>
                                                </span>
                                            @endcan
                                        </td>
                                        <td>
                                            {{ $user->email }} - {{ $user->mobileNumber }}
                                        </td>
                                        <td>
                                            <span class="fw-bold"
                                                title="{{ @$user->country_name }}">{{ $user->country_code }}</span>
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

            </div>
        </div>

    </div>
@endsection
@push('breadcrumb-plugins')
    <div class="card-body mt-1">
        <div class="row">

            <div class="col-md-12 d-flex">
                <a href="{{ route('admin.users.all') }}">
                    <button type="button" class="btn btn--primary"data-modal_title="Làm mới">
                        <i class="fa fa-repeat p-1"></i>

                    </button>
                </a>
                <a>
                    <button type="button" class="btn btn--primary btn-add " style="margin-left:8px">
                        <i class="las la-plus p-1 "></i>

                    </button>
                </a>
                <form role="form" enctype="multipart/form-data" action="">
                    <div class="form-group mb-0" style="display: flex;">
                        <input class="searchInput" name="name"
                            style="height: 35px;border: 1px solid rgb(121, 117, 117, 0.5);margin-left:8px"
                            placeholder="Tên người dùng" value="{{ $name ?? '' }}">
                        <input class="searchInput" name="email"
                            style="height: 35px;border: 1px solid rgb(121, 117, 117, 0.5);margin-left:8px"
                            placeholder="Email" value="{{ $email ?? '' }}">

                        <button type="submit" class="btn btn--primary" style="margin-left: 8px;">
                            <i class="las la-search p-1"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="pager-wrap">
                <div class="k-widget d-flex">
                    <div class="pagination-tb" style="font-size: 13px;margin: 0 auto">
                        {{ $users->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/modal.css') }}">

    <style>
        .pagination .page-item .page-link,
        .pagination .page-item span {
            width: 22px !important;
            height: auto !important;
            background-color: #4634ff !important;
            color: white !important;
        }

        .pagination .page-item.active .page-link {
            background-color: #071251 !important;
        }
    </style>
@endpush

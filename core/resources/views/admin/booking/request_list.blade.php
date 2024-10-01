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
                                    <th>@lang('S.N.') </th>
                                    <th>@lang('Tên người dùng') | @lang('Email')</th>
                                    <th>@lang('Số lượng phòng') | @lang('Loại phòng')</th>
                                    <th>@lang('Nhận phòng') | @lang('Trả phòng')</th>
                                    <th>@lang('Đặt cho')</th>
                                    <th>@lang('Phí/Đêm') | @lang('Tổng tiền')</th>
                                    @can(['admin.request.booking.approve', 'admin.request.booking.cancel'])
                                        <th>@lang('Hành động')</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookingRequests as $bookingRequest)
                                    <tr>
                                        <td> {{ $bookingRequests->firstItem() + $loop->index }}</td>
                                        <td>
                                            <span class="small">

                                                @can('admin.users.detail')
                                                    <a href="{{ route('admin.users.detail', $bookingRequest->user_id) }}">
                                                        <span>@</span>{{ $bookingRequest->user->username }}
                                                    </a>
                                                @else
                                                    {{ $bookingRequest->user->username }}
                                                @endcan
                                            </span>
                                            <br>
                                            <span>+{{ $bookingRequest->user->mobile }}</span>
                                        </td>

                                        <td>
                                            <span class="text--info fw-bold">
                                                {{ $bookingRequest->number_of_rooms }}
                                            </span>
                                            <br>
                                            <span class="fw-bold">{{ __($bookingRequest->roomType->name) }}</span>
                                        </td>

                                        <td>
                                            {{ showDateTime($bookingRequest->check_in, 'd M, Y') }}
                                            <br>
                                            {{ showDateTime($bookingRequest->check_out, 'd M, Y') }}
                                        </td>

                                        <td>
                                            {{ $bookingRequest->bookFor() }} @lang('Đêm')
                                            <br>
                                            <span>
                                                {{ $bookingRequest->number_of_rooms }} @lang('Danh sách phòng')
                                            </span>
                                        </td>
                                        <td>
                                            {{ showAmount($bookingRequest->unit_fare) }}
                                            <span class="text--danger">+ {{ showAmount($bookingRequest->taxPercentage(), currencyFormat:false) }}% {{ __(gs()->tax_name) }}</span>
                                            <br>
                                            <span class="fw-bold">{{ showAmount($bookingRequest->total_amount) }}</span>
                                        </td>

                                        @can(['admin.request.booking.approve', 'admin.request.booking.cancel'])
                                            <td>
                                                @can('admin.request.booking.approve')
                                                    <a class="btn btn-sm btn-outline--success ms-1" href="{{ route('admin.request.booking.approve', $bookingRequest->id) }}"><i class="las la-check"></i>@lang('Chấp thuận')</a>
                                                @endcan

                                                @can('admin.request.booking.cancel')
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn ms-1" data-action="{{ route('admin.request.booking.cancel', $bookingRequest->id) }}" data-question="@lang('Bạn có chắc chắn muốn hủy yêu cầu đặt chỗ này không?')">
                                                        <i class="las la-times-circle"></i>@lang('Hủy bỏ')
                                                    </button>
                                                @endcan
                                            </td>
                                        @endcan
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($bookingRequests->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($bookingRequests) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    @can('admin.request.booking.cancel')
        <x-confirmation-modal />
    @endcan
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Người dùng/Email" />
    @can('admin.booking.active')
        <a class="btn btn--success" href="{{ route('admin.booking.active') }}"><i class="las la-check-circle"></i>@lang('Đặt chỗ đang hoạt động')</a>
    @endcan

    @can('admin.request.booking.canceled')
        <a class="btn btn-outline--danger" href="{{ route('admin.request.booking.canceled') }}"><i class="las la-times-circle"></i>@lang('Yêu cầu đã hủy')</a>
    @endcan
@endpush

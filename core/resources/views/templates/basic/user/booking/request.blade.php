@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="table-responsive--md">
        <table class="custom--table head--base table">
            <thead>
                <tr>
                    <th>@lang('Room')</th>
                    <th>@lang('Check In') - @lang('Check Out')</th>
                    <th>@lang('Rooms')</th>
                    <th>@lang('Fare') </th>
                    <th>@lang('Total')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookingRequests as $bookingRequest)
                    <tr>
                        <td><span class="fw-bold me-1">{{ $bookingRequests->firstItem() + $loop->index }}.</span> {{ __($bookingRequest->roomType->name) }}</td>

                        <td>
                            {{ showDateTime($bookingRequest->check_in, 'd M, Y') }} - {{ showDateTime($bookingRequest->check_out, 'd M, Y') }}</td>

                        <td>
                            {{ $bookingRequest->number_of_rooms }} {{ Str::plural(trans('room'), $bookingRequest->number_of_rooms) }}
                            <br>@lang('for')
                            {{ $bookingRequest->bookFor() }} {{ Str::plural(trans('night'), $bookingRequest->bookFor()) }}
                        </td>

                        <td>
                            {{ showAmount($bookingRequest->unit_fare + $bookingRequest->taxCharge()) }}
                            <br>
                            @lang('Including') {{ __(gs()->tax_name) }}
                        </td>

                        <td class="fw-bold">{{ showAmount($bookingRequest->total_amount) }}</span></td>

                        <td>@php echo $bookingRequest->statusBadge;@endphp</td>

                        <td>
                            <button @disabled($bookingRequest->status) class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('user.booking.request.cancel', $bookingRequest->id) }}" data-question="@lang('Are you sure to cancel this request?')"><i class="las la-times-circle"></i> @lang('Cancel')</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
        @if ($bookingRequests->hasPages())
            <nav aria-label="Page navigation example">
                {{ paginateLinks($bookingRequests) }}
            </nav>
        @endif
    </div>
    <x-confirmation-modal />
@endsection

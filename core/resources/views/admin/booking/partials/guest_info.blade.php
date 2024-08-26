<div class="card">
    <div class="card-body">
        <h5 class="card-title">@lang('Guest Info')</h5>
        <div class="list">
            <div class="list-item">
                <span>@lang('Name')</span>
                <span>
                    @if ($booking->user_id)
                        @can('admin.users.detail')
                            <a href="{{ route('admin.users.detail', $booking->user_id) }}">{{ __($booking->user->fullname) }}</a>
                        @else
                            {{ __($booking->user->fullname) }}
                        @endcan
                    @else
                        {{ $booking->guest_details->name }}
                    @endif
                </span>
            </div>
            <div class="list-item">
                <span>@lang('Email')</span>
                <span>
                    @if ($booking->user_id)
                        {{ $booking->user->email }}
                    @else
                        {{ $booking->guest_details->email }}
                    @endif
                </span>
            </div>
            <div class="list-item">
                <span>@lang('Phone')</span>
                <span>
                    @if ($booking->user_id)
                        +{{ $booking->user->mobile }}
                    @else
                        +{{ $booking->guest_details->mobile }}
                    @endif
                </span>
            </div>
            <div class="list-item">
                <span>@lang('Address')</span>
                <span>
                    @if ($booking->user_id)
                        {{ $booking->user->address ?? 'N/A' }}
                    @else
                        {{ $booking->guest_details->address ?? 'N/A' }}
                    @endif
                </span>
            </div>
        </div>
    </div>
</div>

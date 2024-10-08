@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="card custom--card">
        <div class="card-header">
            <h5 class="card-title">@lang('Password Change')</h5>
        </div>

        <div class="card-body">
            <form action="" method="post">
                @csrf
                <div class="form-group">
                    <label>@lang('Current Password')</label>
                    <div class="custom-icon-field">
                        <input type="password" class="form--control" name="current_password" placeholder="@lang('Current Password')" required autocomplete="current-password">
                        <i class="la la-lock-open"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label>@lang('New Password')</label>
                    <div class="secure-password-popup">
                        <div class="custom-icon-field">
                            <input type="password" class="form--control @if(gs('secure_password')) secure-password @endif" name="password" placeholder="@lang('New Password')" required autocomplete="current-password">
                            <i class="las la-lock"></i>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>@lang('Confirm Password')</label>
                    <div class="custom-icon-field">
                        <input type="password" class="form--control" name="password_confirmation" placeholder="@lang('Confirm Password')" required>
                        <i class="las la-lock"></i>
                    </div>
                </div>

                <button type="submit" class="btn btn-md btn--base w-100">@lang('Submit Changes')</button>
            </form>
        </div>
    </div>
@endsection

@if (gs()->secure_password)
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif

@php
    $text = Route::is('user.register') ? 'Register' : 'Login';
@endphp

<div class="social-auth">
    <div class="auth-devide text-center">
        <span>@lang('OR')</span>
    </div>

    <div class="social-auth-list">
        @if (@gs('socialite_credentials')->google->status == Status::ENABLE)
            <div class="continue-auth-list">
                <a title="@lang($text . ' With Google')" href="{{ route('user.social.login', 'google') }}" class="social-login-btn google-color">
                    <span class="auth-icon">
                        <i class="fa-brands fa-google"></i>
                    </span>
                </a>
            </div>
        @endif
        @if (@gs('socialite_credentials')->facebook->status == Status::ENABLE)
            <div class="continue-auth-list">
                <a title="@lang($text . ' With Facebook')" href="{{ route('user.social.login', 'facebook') }}" class="social-login-btn facebook-color">
                    <span class="auth-icon">
                        <i class="fa-brands fa-facebook-f"></i>
                    </span>
                </a>
            </div>
        @endif
        @if (@gs('socialite_credentials')->linkedin->status == Status::ENABLE)
            <div class="continue-auth-list">
                <a title="@lang($text . ' With linkedin')" href="{{ route('user.social.login', 'linkedin') }}" class="social-login-btn linkedin-color">
                    <span class="auth-icon">
                        <i class="fa-brands fa-linkedin-in"></i>
                    </span>
                </a>
            </div>
        @endif
    </div>
</div>

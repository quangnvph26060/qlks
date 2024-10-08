@php
    $loginContent = getContent('login.content', true);
@endphp

@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="auth-section">
        <div class="container">
            <div class="row align-items-lg-center justify-content-center justify-content-xl-between">
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="{{ getImage('assets/images/frontend/login/' . @$loginContent->data_values->image, '1037x890') }}" alt="@lang('Image')" class="img-fluid">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="auth-section__form">
                        <h3 class="title mb-2">Đăng nhập tài khoản</h3>
                        <p class="subtitle">Chào mừng đến với Viser Hotel,
                            phần mềm quản lý khách sạn chuyên nghiệp</p>
                        <form method="POST" action="{{ route('user.login') }}" class="account-form verify-gcaptcha mt-3">
                            @csrf
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Tên người dùng hoặc Email')</label>
                                    <div class="custom-icon-field">
                                        <input type="text" name="username" value="{{ old('username') }}" class="form--control" placeholder="@lang('Tên người dùng hoặc Email')" required>
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Mật khẩu')</label>
                                    <div class="custom-icon-field">
                                        <input type="password" class="form--control" id="password-seen" name="password" placeholder="@lang('Mật khẩu')" required>
                                        <i class="fas fa-lock"></i>
                                        <span class="input-eye"><i class="la la-eye"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <x-captcha />
                            </div>

                            <div class="d-flex justify-content-between form-group flex-wrap">
                                <div class="form-check custom--checkbox me-4">
                                    <input class="form-check-input" type="checkbox" {{ old('remember') ? 'checked' : '' }} id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        @lang('Remember Me')
                                    </label>
                                </div>

                                <a href="{{ route('user.password.request') }}" class="text--base">@lang('Quên mật khẩu?')</a>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn--base w-100">@lang('Đăng nhập tài khoản')</button>
                            </div>
                        </form>

                        @if (@gs('socialite_credentials')->linkedin->status || @gs('socialite_credentials')->facebook->status == Status::ENABLE || @gs('socialite_credentials')->google->status == Status::ENABLE)
                            @include($activeTemplate . 'partials.social_login')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $('.input-eye i').on('click', function() {
            let element = document.getElementById('password');
            if (this.classList.contains('la-eye')) {
                this.classList.add('la-eye-slash')
                this.classList.remove('la-eye')
            } else {
                this.classList.add('la-eye')
                this.classList.remove('la-eye-slash')
            }
            if (element.type == 'password') {
                element.type = "text";
            } else {
                element.type = "password";
            }
        });
    </script>
@endpush

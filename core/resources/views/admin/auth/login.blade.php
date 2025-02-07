@extends('admin.layouts.master')
@section('content')
    <div class="login-main" style="background-image: url('{{ asset('assets/admin/images/login.jpg') }}')">
        <div class="container custom-container">
            <div class="row justify-content-center">
                <div class="col-xxl-5 col-xl-5 col-lg-6 col-md-8 col-sm-11">
                    <div class="login-area">
                        <div class="login-wrapper">
                            <div class="login-wrapper__top">
                                <h3 class="title text-white font-size-login">@lang('Chào mừng đến với')
                                    <strong>{{ __(gs('site_name')) }}</strong></h3>
                                <p class="text-white">
                                    {{ $pageTitle }}
                                </p>
                            </div>
                            <div class="login-wrapper__body">
                                <form action="{{ route('admin.login') }}" method="POST"
                                    class="cmn-form mt-30 verify-gcaptcha login-form">
                                    @csrf
                                    <div class="form-group">
                                        <label>@lang('Mã cơ sở')</label>
                                        <input type="text" class="form-control" value="{{ old('unit_code') }}"
                                            name="unit_code" required>
                                    </div>
                                    <div class="form-group">
                                        <label>@lang('Tài khoản')</label>
                                        <input type="text" class="form-control" value="{{ old('username') }}"
                                            name="username" required>
                                    </div>
                                    <div class="form-group">
                                        <label>@lang('Mật khẩu')</label>
                                        <input type="password" class="form-control" name="password" required>
                                    </div>
                                    <a href="{{ route('admin.password.reset') }}"
                                        class="forget-text float-end">@lang('Quên mật khẩu?')</a>
                                    <x-captcha />
                                    <button type="submit" class="btn cmn-btn w-100">@lang('Đăng nhập')</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style scoped>
        .font-size-login {
            font-size: 28px !important;
        }
    </style>
@endsection

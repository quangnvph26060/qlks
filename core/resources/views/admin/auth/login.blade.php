@extends('admin.layouts.master')
@section('content')
    {{-- <div class="login-main" style="background-image: url('{{ asset('assets/admin/images/login.jpg') }}')">
        <div class="container custom-container">
            <div class="row justify-content-center">
                <div class="col-xxl-5 col-xl-5 col-lg-6 col-md-8 col-sm-11">
                    <div class="login-area">
                        <div class="login-wrapper">
                            <div class="login-wrapper__top">
                                <h3 class="title text-white font-size-login">@lang('Chào mừng đến với')
                                
                                    Fasthotel
                                </h3>
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
    </div> --}}
<div class="login-main">
    <div class="login-wrapper">
        <div class="login-left">
            <h3 class="text-white text-center">LIÊN HỆ VỚI CHÚNG TÔI</h3>
            <div class="contact-box">
                <div class="contact-row">
                    <span class="label">Hỗ trợ kỹ thuật:</span>
                    <span class="value">(024) 62 927 089 (24/7) <br> 0981 185 620 (24/7)</span>
                </div>
                <div class="contact-row">
                    <span class="label">Hỗ trợ hoá đơn:</span>
                    <span class="value">(024) 62 927 089 (8h30 - 18h00) <br>0912 399 322 (8h30 - 18h00)</span>
                </div>
                <div class="contact-row">
                    <span class="label">Hỗ trợ gia hạn:</span>
                    <span class="value">(024) 62 927 089 (8h30 - 18h00) <br>0981 185 620 (8h30 - 18h00)</span>
                </div>
                <div class="contact-row">
                    <span class="label">Email:</span>
                    <span class="value">info@sgomedia.vn</span>
                </div>
            </div>
        </div>

        <div class="login-right">
            <img src="{{ siteLogo() }}"   alt="image" class="logo">
            <form action="{{ route('admin.login') }}" method="POST" class="login-form">
                @csrf
                <div class="form-group">
                    <label class="text-dark">@lang('Mã cơ sở')</label>
                    <input type="text"  class="form-control" value="{{ old('unit_code') }}" name="unit_code" required>
                </div>
                <div class="form-group">
                    <label class="text-dark">@lang('Tài khoản')</label>
                    <input type="text"  class="form-control" value="{{ old('username') }}" name="username" required>
                </div>
                <div class="form-group">
                    <label class="text-dark">@lang('Mật khẩu')</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <a href="{{ route('admin.password.reset') }}" class="forget-text float-end text-dark">@lang('Quên mật khẩu?')</a>
                <div class="checkbox-row d-flex align-items-center">
                    <input type="checkbox" id="remember" class="me-2" />
                    <label for="remember" class="text-dark m-0">Lưu mật khẩu</label>
                </div>
                <button type="submit" class="btn cmn-btn w-100">ĐĂNG NHẬP</button>
            </form>
        </div>
    </div>
</div>

<style scoped>
    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
    }

    .login-main {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background: #e5e5e5 !important;
        padding: 20px;
    }

    .login-wrapper {
        display: flex;
        width: 960px;
        max-width: 100%;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        flex-direction: row;
    }

    .login-left {
        width: 50%;
        background-color: #005aa7;
        color: white;
        padding: 30px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .contact-box,
    .contact-box * {
        color: white !important;
    }

    .contact-box {
        border: 1px dashed white;
        padding: 15px;
        font-size: 14px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        border-radius: 8px;
    }

    .contact-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .contact-row .label {
        width: 40%;
        font-weight: bold;
    }

    .contact-row .value {
        width: 60%;
        text-align: right;
    }

    .login-right {
        width: 50%;
        padding: 30px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: white;
    }

    .logo {
        height: 60px;
        margin-bottom: 30px;
    }

    .login-form {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .login-form input[type="text"],
    .login-form input[type="password"] {
        padding: 10px;
        font-size: 14px;
        border: 1px solid black;
        border-radius: 5px;
        background-color: #fff;
        color: #000;
    }

    .login-form label {
        color: black;
        font-weight: 500;
    }

    .checkbox-row {
        display: flex;
        align-items: center;
        font-size: 14px;
    }

    .checkbox-row input {
        margin-right: 8px;
    }

    button[type="submit"] {
        background-color: #005aa7;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .login-wrapper {
            flex-direction: column;
            height: auto;
        }

        .login-left,
        .login-right {
            width: 100%;
            padding: 20px;
        }
.login-left{
    display: none;
}
        .contact-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .contact-row .label,
        .contact-row .value {
            width: 100%;
            text-align: left;
        }

        .logo {
            height: 50px;
        }

        .login-main {
            padding: 10px;
        }
    }
</style>


@endsection

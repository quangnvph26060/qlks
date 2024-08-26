@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $policyPages = getContent('policy_pages.element', false, null, true);
        $registerContent = getContent('register.content', true);
    @endphp

    <div class="auth-section">
        <div class="container">
            <div class="row align-items-lg-center justify-content-center justify-content-xl-between">
                <div class="col-lg-5 d-none d-lg-block">
                    <img alt="@lang('Image')" class="img-fluid" src="{{ getImage('assets/images/frontend/register/' . @$registerContent->data_values->image, '1037x890') }}">
                </div>
                <div class="col-md-10 col-lg-7 col-xl-6">
                    <div class="auth-section__form @if (!gs('registration')) form-disabled @endif">
                        @if (!gs('registration'))
                            <span class="form-disabled-text">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="80" height="80" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                    <g>
                                        <path d="M255.999 0c-79.044 0-143.352 64.308-143.352 143.353v70.193c0 4.78 3.879 8.656 8.659 8.656h48.057a8.657 8.657 0 0 0 8.656-8.656v-70.193c0-42.998 34.981-77.98 77.979-77.98s77.979 34.982 77.979 77.98v70.193c0 4.78 3.88 8.656 8.661 8.656h48.057a8.657 8.657 0 0 0 8.656-8.656v-70.193C399.352 64.308 335.044 0 255.999 0zM382.04 204.89h-30.748v-61.537c0-52.544-42.748-95.292-95.291-95.292s-95.291 42.748-95.291 95.292v61.537h-30.748v-61.537c0-69.499 56.54-126.04 126.038-126.04 69.499 0 126.04 56.541 126.04 126.04v61.537z" fill="rgb(0 0 0 / 60%)" opacity="1" data-original="rgb(0 0 0 / 60%)" class=""></path>
                                        <path d="M410.63 204.89H101.371c-20.505 0-37.188 16.683-37.188 37.188v232.734c0 20.505 16.683 37.188 37.188 37.188H410.63c20.505 0 37.187-16.683 37.187-37.189V242.078c0-20.505-16.682-37.188-37.187-37.188zm19.875 269.921c0 10.96-8.916 19.876-19.875 19.876H101.371c-10.96 0-19.876-8.916-19.876-19.876V242.078c0-10.96 8.916-19.876 19.876-19.876H410.63c10.959 0 19.875 8.916 19.875 19.876v232.733z" fill="rgb(0 0 0 / 60%)" opacity="1" data-original="rgb(0 0 0 / 60%)" class=""></path>
                                        <path d="M285.11 369.781c10.113-8.521 15.998-20.978 15.998-34.365 0-24.873-20.236-45.109-45.109-45.109-24.874 0-45.11 20.236-45.11 45.109 0 13.387 5.885 25.844 16 34.367l-9.731 46.362a8.66 8.66 0 0 0 8.472 10.436h60.738a8.654 8.654 0 0 0 8.47-10.434l-9.728-46.366zm-14.259-10.961a8.658 8.658 0 0 0-3.824 9.081l8.68 41.366h-39.415l8.682-41.363a8.655 8.655 0 0 0-3.824-9.081c-8.108-5.16-12.948-13.911-12.948-23.406 0-15.327 12.469-27.796 27.797-27.796 15.327 0 27.796 12.469 27.796 27.796.002 9.497-4.838 18.246-12.944 23.403z" fill="rgb(0 0 0 / 60%)" opacity="1" data-original="rgb(0 0 0 / 60%)" class=""></path>
                                    </g>
                                </svg>
                            </span>
                        @endif

                        <h3 class="title mb-2">{{ __(@$registerContent->data_values->heading) }}</h3>
                        <p class="subtitle">{{ __(@$registerContent->data_values->subheading) }} </p>
                        <form action="{{ route('user.register') }}" class="account-form disableSubmission verify-gcaptcha mt-3" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>@lang('First Name')</label>
                                        <div class="custom-icon-field">
                                            <input type="text" class="form--control" name="firstname" placeholder="@lang('First Name')" value="{{ old('firstname') }}" required>
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>@lang('Last Name')</label>
                                        <div class="custom-icon-field">
                                            <input type="text" class="form--control" name="lastname" placeholder="@lang('Last Name')" value="{{ old('lastname') }}" required>
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>@lang('Email')</label>
                                        <div class="custom-icon-field">
                                            <input class="form--control checkUser" name="email" placeholder="@lang('Email Address')" required type="email" value="{{ old('email') }}">
                                            <i class="fa-solid fa-envelope"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Password')</label>
                                        <div class="custom-icon-field">
                                            <input type="password" class="form--control @if (gs('secure_password')) secure-password @endif" name="password" placeholder="@lang('Password')" required>
                                            <i class="fas fa-lock"></i>

                                            <span class="input-eye"><i class="far fa-eye-slash"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Confirm Password')</label>
                                        <div class="custom-icon-field">
                                            <input type="password" class="form--control" name="password_confirmation" placeholder="@lang('Confirm Password')" required>
                                            <i class="fas fa-lock"></i>

                                            <span class="input-eye"><i class="far fa-eye-slash"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-2">
                                    <x-captcha />
                                </div>

                                @if (gs()->agree)
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="form-check custom--checkbox">
                                                <input @checked(old('agree')) class="form-check-input" id="agree" name="agree" type="checkbox">
                                                <label class="form-check-label" for="agree">
                                                    @lang('I agree with') @foreach ($policyPages as $policy)
                                                        <a class="text--base" href="{{ route('policy.pages', $policy->slug) }}" target="_blank">{{ __($policy->data_values->title) }}</a>
                                                        @if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-lg-12">
                                    <button class="btn btn--base w-100" type="submit">@lang('CREATE AN ACCOUNT')</button>
                                </div>
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

    <div aria-hidden="true" aria-labelledby="existModalCenterTitle" class="modal fade" id="existModalCenter" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                    <span aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <h6 class="text-center">@lang('You already have an account please Login ')</h6>
                </div>
                <div class="modal-footer">
                    <button class="btn btn--danger btn-sm text-white" data-bs-dismiss="modal" type="button">@lang('Close')</button>
                    <a class="btn btn--base btn-sm" href="{{ route('user.login') }}">@lang('Login')</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('style')
    <style>
        .form-disabled {
            overflow: hidden;
            position: relative;
            user-select: none;
        }

        .form-disabled::after {
            content: "";
            position: absolute;
            height: 100%;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.2);
            top: 0;
            left: 0;
            backdrop-filter: blur(2px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            z-index: 99;
        }

        .form-disabled-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 991;
            font-size: 24px;
            height: auto;
            width: 100%;
            text-align: center;
            font-weight: 800;
            line-height: 1.2;
        }
    </style>
@endpush

@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif

@push('script')
    <script>
        "use strict";
        (function($) {

            @if (!gs('registration'))
                notify('error', 'Registration is currently disabled');
            @endif

            $('.input-eye i').on('click', function() {
                let element = $(this).closest('.form-group').find('input')[0];

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


            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';

                var data = {
                    email: value,
                    _token: token
                }

                $.post(url, data, function(response) {
                    if (response.data != false) {
                        $('#existModalCenter').modal('show');
                    }
                });
            });
        })(jQuery);
    </script>
@endpush

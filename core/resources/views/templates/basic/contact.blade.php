@php
    $contactCon = getContent('contact_us.content', true);
    $socialElements = getContent('social_icon.element');
@endphp
@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <!-- contact section start -->
    <section class="section">
        <div class="container">
            <div class="contact-wrapper pt-100 pb-100">
                <div class="contact-wrapper-right-thumb bg_img" style="background-image: url('{{ getImage('assets/images/frontend/contact_us/' . @$contactCon->data_values->image, '900x1020') }}');">
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="contact-left-area bg_img" style="background-image: url('{{ getImage('assets/images/frontend/contact_us/' . @$contactCon->data_values->image, '900x1020') }}');">
                            <div class="contact-info-wrapper">
                                <div class="contact-info-list mb-4">

                                    <div class="contact-info">
                                        <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                                        <div class="content">
                                            <h6 class="title mb-1">@lang('Office Address')</h6>
                                            <p>{{ __(@$contactCon->data_values->address) }}</p>
                                        </div>
                                    </div><!-- contact-info end -->

                                    <div class="contact-info">
                                        <div class="icon"><i class="fas fa-envelope"></i></div>
                                        <div class="content">
                                            <h6 class="title mb-1">@lang('Email Address')</h6>
                                            <p>
                                                <a href="mailto:{{ @$contactCon->data_values->email_address }}">{{ @$contactCon->data_values->email_address }}</a>
                                            </p>
                                        </div>
                                    </div><!-- contact-info end -->

                                    <div class="contact-info">
                                        <div class="icon"><i class="fas fa-phone-alt"></i></div>
                                        <div class="content">
                                            <h6 class="title mb-1">@lang('Phone Number')</h6>
                                            <p><a href="tel:{{ @$contactCon->data_values->contact_number }}">{{ @$contactCon->data_values->contact_number }}</a>
                                            </p>
                                        </div>
                                    </div><!-- contact-info end -->

                                </div>
                                @if (count($socialElements) > 0)
                                    <h6 class="fs--16px text-center">@lang('Follow Us')</h6>
                                    <ul class="social-list justify-content-center mt-3">
                                        @foreach ($socialElements as $item)
                                            <li><a href="{{ $item->data_values->url }}" target="_blank">@php echo $item->data_values->social_icon @endphp</a></li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-lg-0 mt-4">
                        <div class="contact-right-area">
                            <div class="row mb-5">
                                <div class="col-lg-10">
                                    <h3 class="title mb-2">{{ __(@$contactCon->data_values->title) }}</h3>
                                    <p class="description">{{ __(@$contactCon->data_values->short_details) }}</p>
                                </div>
                            </div>
                            <form action="" class="verify-gcaptcha" method="post">
                                @csrf
                                <div class="form-group mb-3">
                                    <label>@lang('Name')</label>
                                    <div class="custom-icon-field">
                                        <input name="name" type="text" class="form--control" value="{{ old('name', @$user->fullname) }}" @if ($user && $user->profile_complete) readonly @endif required>
                                        <i class="fas fa-user-alt"></i>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label>@lang('Email')</label>
                                    <div class="custom-icon-field">
                                        <input name="email" type="email" class="form--control" value="{{ old('email', @$user->email) }}" @if ($user) readonly @endif required>
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label>@lang('Subject')</label>
                                    <div class="custom-icon-field">
                                        <input class="form--control" name="subject" placeholder="@lang('Enter Subject')" required type="text" value="{{ old('subject') }}">
                                        <i class="fas fa-heading"></i>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label>@lang('Message')</label>
                                    <textarea class="form--control" name="message" placeholder="@lang('Write Message')" required wrap="off">{{ old('message') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <x-captcha />
                                </div>
                                <button class="btn btn--base w-100" type="submit">@lang('SEND MESSAGE')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="map-area">
        <iframe src="https://maps.google.com/maps?q={{ @$contactCon->data_values->latitude }},{{ @$contactCon->data_values->longitude }}&hl=es;z=14&amp;output=embed"></iframe>
    </div>

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection

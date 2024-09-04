@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="section">
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="verification-code-wrapper">
                    <div class="verification-area">
                        <h5 class="mb-2 text-center">@lang('Xác minh địa chỉ email')</h5>
                        <form action="{{ route('user.verify.email') }}" method="POST" class="submit-form">
                            @csrf
                            <p class="verification-text mb-2 text-center">@lang('A 6 digit verification code sent to your email address'): {{ showEmailAddress(auth()->user()->email) }}</p>

                            @include($activeTemplate . 'partials.verification_code')

                            <div class="mb-3">
                                <button type="submit" class="btn btn--base w-100">@lang('Xác nhận')</button>
                            </div>

                            <div class="mb-3">
                                <p>
                                    @lang('Nếu bạn không nhận được bất kỳ mã nào'), <span class="countdown-wrapper">@lang('thử lại sau') <span id="countdown" class="fw-bold">--</span> @lang('vài giây.')</span> <a href="{{ route('user.send.verify.code', 'email') }}" class="try-again-link d-none"> @lang('Thử lại.')</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        var distance = Number("{{ @$user->ver_code_send_at->addMinutes(2)->timestamp - time() }}");
        var x = setInterval(function() {
            distance--;
            document.getElementById("countdown").innerHTML = distance;
            if (distance <= 0) {
                clearInterval(x);
                document.querySelector('.countdown-wrapper').classList.add('d-none');
                document.querySelector('.try-again-link').classList.remove('d-none');
            }
        }, 1000);
    </script>
@endpush

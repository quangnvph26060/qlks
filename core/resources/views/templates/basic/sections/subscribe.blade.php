@php
    $subscribeContent = getContent('subscribe.content', true);
@endphp
<section class="section base--overlay bg_img" style="background-image: url('{{ getImage('assets/images/frontend/subscribe/' . @$subscribeContent->data_values->image, '1800x300') }}');">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <h2 class="section-title text-center text-white">{{ __(@$subscribeContent->data_values->heading) }}</h2>
                <form action="" class="subscribe-form" id="subscribe">
                    @csrf
                    <input type="email" name="email" class="form--control" placeholder="@lang('Enter email address')">
                    <button type="submit" class="btn btn--dark"><i class="fa fa-paper-plane" aria-hidden="true"></i> &nbsp; {{ __(@$subscribeContent->data_values->button_title) }}</button>
                </form>
            </div>
        </div>
    </div>
</section>

@push('script')
    <script type="text/javascript">
        $('.subscribe-form').on('submit', function(e) {
            var email = $(this).find('input[name=email]').val();

            if (email == '') {
                $(this).find('input[name=email]').focus();
                return false;
            }

            e.preventDefault();
            let url = `{{ route('subscribe') }}`;
            let data = {
                email
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': `{{ csrf_token() }}`
                }
            });
            $.post(url, data, function(response) {
                if (response.errors) {
                    notify('error', response.errors);
                } else {
                    $('.subscribe-form').trigger("reset");
                    notify('success', response.success);
                }
            });
            this.reset();
        })
    </script>
@endpush

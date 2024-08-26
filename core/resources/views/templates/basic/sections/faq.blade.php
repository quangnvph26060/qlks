@php
    $content  = getContent('faq.content', true);
    $elements = getContent('faq.element', false);
@endphp

<section class="section section--bg">
    <div class="container">
        @if (Request::path() != 'faq')
            <div class="row justify-content-center">
                <div class="col-lg-7 text-center">
                    <div class="section-header">
                        <h2 class="section-title">{{ __($content->data_values->heading) }}</h2>
                        <p>{{ __($content->data_values->subheading) }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="row gy-4 justify-content-center">
            <div class="accordion custom--accordion" id="faqAccordion1">
                @foreach ($elements as $item)
                    <div class="accordion-item">
                        <h6 class="accordion-header collapsed" id="heading{{ $item->id }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $item->id }}" aria-expanded="false"
                                aria-controls="collapse{{ $item->id }}">
                                {{ __($item->data_values->question) }}
                            </button>
                        </h6>
                        <div id="collapse{{ $item->id }}" class="accordion-collapse collapse"
                            aria-labelledby="heading{{ $item->id }}" data-bs-parent="#faqAccordion1">
                            <div class="accordion-body">
                                <p>{{ __($item->data_values->answer) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

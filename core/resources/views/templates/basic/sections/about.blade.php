@php
$aboutContent = getContent('about.content', true);
@endphp

<section class="section">
    <div class="container">
        <div class="row gy-4 justify-content-between">
            <div class="col-xl-5 col-lg-6">
                <div class="about-thumb-wrapper">
                    <div class="about-thumb">
                        <img src="{{ getImage('assets/images/frontend/about/' . $aboutContent->data_values->image_1, '250x260') }}" alt="image">
                    </div>

                    <div class="about-thumb">
                        <img src="{{ getImage('assets/images/frontend/about/' . $aboutContent->data_values->image_2, '250x260') }}" alt="image">
                    </div>

                    <div class="about-thumb">
                        <img src="{{ getImage('assets/images/frontend/about/' . $aboutContent->data_values->image_3, '250x260') }}" alt="image">
                    </div>

                    <div class="about-thumb">
                        <img src="{{ getImage('assets/images/frontend/about/' . $aboutContent->data_values->image_4, '250x260') }}" alt="image">
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6">
                <div class="section-header mb-xl-4 mb-3">
                    <h2 class="section-title">{{ __($aboutContent->data_values->heading) }}</h2>
                    <p>{{ __($aboutContent->data_values->subheading) }}</p>
                </div>
                @php echo trans($aboutContent->data_values->description) @endphp
            </div>
        </div>
    </div>
</section>

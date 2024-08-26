@php
$content = getContent('featured_room.content', true);

$roomType = App\Models\RoomType::active()
    ->featured()
    ->with(['images', 'amenities'])
    ->get();
@endphp

@if (count($roomType))
    <section class="section section--bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 text-center">
                    <div class="section-header">
                        <h2 class="section-title">{{ __($content->data_values->heading) }}</h2>
                        <p>{{ __($content->data_values->subheading) }}</p>
                    </div>
                </div>
            </div>
            <div class="row g-4 justify-content-center">
                <!-- room section start -->
                @include($activeTemplate.'partials.room_cards', ['roomType' => $roomType, 'class' => 'col-lg-6 col-xl-4 col-md-8'])
                <!-- room section end -->
            </div>
        </div>
    </section>
@endif

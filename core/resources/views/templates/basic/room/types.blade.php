@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="section">
        <div class="container">
            <div class="row gy-4 justify-content-center">
                @if ($roomTypes->count())
                    @include($activeTemplate . 'partials.room_cards', ['roomType' => $roomTypes, 'class' => 'col-xl-4 col-md-6 col-xs-10'])
                @else
                    <div class="col-md-9">
                        <div class="card custom--card border-0">
                            <div class="card-body empty-message">
                                <i class="la la-lg la-10x la-frown text--warning"></i>
                                <span class="text--muted mt-3">{{ __($emptyMessage) }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        .empty-message {
            text-align: center;
        }

        .empty-message span {
            font-size: 25px;
            display: block;
        }
    </style>
@endpush

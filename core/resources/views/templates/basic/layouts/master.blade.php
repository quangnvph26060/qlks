@extends($activeTemplate . 'layouts.app')
@section('layout')
    @include($activeTemplate . 'partials.header')
    <main class="main-wrapper">
        @include($activeTemplate . 'partials.breadcrumb')
        <section class="pt-80 pb-80">
            <div class="container">
                <div class="row gy-4">
                    <div class="col-xl-3">
                        @include($activeTemplate . 'partials.sidenav')
                    </div>
                    <div class="col-xl-9 ps-xl-4">
                        <div class="user-sidebar-toggler-wrapper d-inline-block text-end d-xl-none">
                            <span class="user-sidebar-toggler"><i class="fas fa-sliders-h"></i></span>
                        </div>
                        @yield('content')
                    </div>
                </div>
            </div>
        </section>
    </main>
    @include($activeTemplate . 'partials.footer')
@endsection

@push('style')
    <style>
        #confirmationModal button {
            padding: 0.375rem 0.625rem;
            font-size: 0.875rem;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('form').on('submit', function() {
                if ($(this).valid()) {
                    $(':submit', this).attr('disabled', 'disabled');
                }
            });

            $('.showFilterBtn').on('click', function() {
                $('.responsive-filter-card').slideToggle();
            });


            Array.from(document.querySelectorAll('table')).forEach(table => {
                let heading = table.querySelectorAll('thead tr th');
                Array.from(table.querySelectorAll('tbody tr')).forEach(row => {
                    Array.from(row.querySelectorAll('td')).forEach((column, i) => {
                        (column.colSpan == 100) || column.setAttribute('data-label', heading[i].innerText)
                    });
                });
            });

        })(jQuery);
    </script>
@endpush

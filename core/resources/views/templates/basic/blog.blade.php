@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="section">
        <div class="container">
            <div class="row gy-4 justify-content-center">
                @foreach ($blogs as $blog)
                    <div class="col-xl-4 col-md-6">
                        <div class="blog-post h-100">
                            <div class="blog-post__thumb">
                                <img src="{{ getImage('assets/images/frontend/blog/thumb_' . $blog->data_values->image, '400x280') }}" alt="image">
                            </div>
                            <div class="blog-post__content">
                                <div class="blog-meta">
                                    <div class="date-time">
                                        <span class="blog-date">{{ showDateTime(@$blog->created_at, 'd M, Y') }}</span>
                                    </div>
                                </div>
                                <h3 class="title"><a href="{{ route('blog.details', $blog->slug) }}"> @php echo substr(trans($blog->data_values->title),0,60) @endphp</a></h3>
                                <p class="mt-sm-3 mt-2">@php echo substr(trans(strip_tags($blog->data_values->description)),0,100)  @endphp</p>
                                <a href="{{ route('blog.details', $blog->slug) }}" class="mt-3">@lang('Read More') <i class="las la-long-arrow-alt-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($blogs->hasPages())
                <div class="mt-3 mt-md-4">
                    {{ paginateLinks($blogs) }}
                </div>
            @endif
        </div>
    </section>
@endsection

@props([
    'link' => '',
    'title' => '',
    'value' => '',
    'heading' => '',
    'subheading' => '',
    'icon' => '',
    'bg' => 'white',
    'color' => 'primary',
    'icon_style' => 'outline',
    'overlay_icon' => 1,
    'cover_cursor' => 0,
    'date' => 0,
    'inputName' =>'date',
    'dateNow'=> \Carbon\Carbon::today()->format('Y-m-d'),
])
<div
    class="widget-two box--shadow2 b-radius--5 @if ($cover_cursor && $link) has-link @endif bg--{{ $bg }}">

    @if ($cover_cursor)
        <a href="{{ $link }}" class="item-link" style="position: absolute; inset: 0; z-index: 1;"></a>
    @endif
    @if ((bool) $overlay_icon)
        <i class="{{ $icon }} overlay-icon text--{{ $color }}"></i>
    @endif

    <div
        class="widget-two__icon b-radius--5  @if ($icon_style == 'outline') border border--{{ $color }} text--{{ $color }} @else bg--{{ $color }} @endif ">
        <i class="{{ $icon }}"></i>
    </div>

    <div class="widget-two__content">
        <div class="d-flex" style="justify-content: space-between">
            <div class="d-flex flex-column">
                <h3>{{ $value || $value === '0' || $value === 0 ? $value : __($heading) }}</h3>
                <p>{{ __($title ? $title : $subheading) }}</p>
            </div>
            @if($date)
            <form method="GET" action="{{ route('admin.dashboard') }}" style=" z-index: 2;">
                 <input type="date" id="date" name="{{$inputName}}" value="{{$dateNow}}" style="    height: 32px;
                    width: 158px;
                   
                    border: 1px gray;"  onchange="this.form.submit()">
                </form>

               
           @endif
        </div>
    </div>

    @if ($link && !$cover_cursor)
        <a href="{{ $link }}"
            class="widget-two__btn btn btn-outline--{{ $color }}">@lang('View All')</a>
    @endif
</div>

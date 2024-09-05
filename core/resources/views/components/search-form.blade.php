@props([
    'placeholder' => 'Search...',
    'btn' => 'btn--primary',
    'dateSearch' => 'yes',
    'keySearch' => 'yes',
])

<form class="d-flex flex-wrap gap-2">
    @if ($dateSearch == 'yes')
        <x-search-date-field />
    @endif
    @if ($keySearch == 'yes')
        <x-search-key-field placeholder="{{ $placeholder }}" btn="{{ $btn }}" />
    @endif


</form>

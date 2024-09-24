{{-- @dd($roomType) --}}
@foreach ($roomType as $type)
{{-- @dd($type) --}}
    <div class="item p-3" style="{{ $loop->iteration % 2 == 0 ? 'flex-direction: row-reverse;' : '' }}">
        <img alt="image" class="rounded"
            src="{{\Storage::url($type->main_image)}}">
        <div class="info">
            <h2>{{ $type->name }}</h2>
            <p class="price">Giá: {{ number_format($type->fare, 0, '', '.') }} VNĐ/ngày</p>
            <p>Số lượng người: {{ $type->total_adult + $type->total_child }} ({{ $type->total_adult }} người lớn +
                {{ $type->total_child }} trẻ em)</p>
            <p>Mô tả: Phòng rộng rãi, đầy đủ tiện nghi, view đẹp.</p>
            <p>Cơ sở: Khách sạn XYZ, 123 Đường ABC, Hà Nội.</p>
            <div class="mt-3">
                <a class="btn btn-sm btn--base" href="{{ route('room.type.details', $type->slug) }}">
                    <i class="la la-desktop me-2"></i>@lang('Đặt ngay')
                </a>
            </div>

        </div>


    </div>
@endforeach

@foreach ($response as $product)
    <div class="result-item d-flex align-items-center mb-2 cursor-pointer" data-resource="{{ $product }}">
        <div class="image me-3">
            <img width="80" src="{{ \Storage::url($product->image_path) }}"
                alt="{{ \Storage::url($product->image_path) }}">
        </div>
        <div class="info">
            <div class="name fw-bold">{{ $product->name }}</div>
            <div class="price text-success">Giá: {{ $product->import_price }}</div>
            <div class="stock text-muted">Tồn kho: {{ $product->stock ?? 0 }}</div>
        </div>
    </div>
@endforeach

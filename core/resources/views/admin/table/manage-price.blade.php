@if ($response->isNotEmpty())
    @foreach ($response as $room)
        <tr data-id="{{ $room->id }}">
            <td>
                {{ $room->code }}
            </td>
            <td>
                <div class="row">
                    <div class="border-bottom p-4">
                        Giá giờ
                    </div>
                </div>
                <div class="row">
                    <div class="border-bottom p-4">
                        Giá cả ngày
                    </div>
                </div>
                <div class="row">
                    <div class="p-4">
                        Giá đêm
                    </div>
                </div>
            </td>
            <td>
                @foreach ($collection as $item)
                    
                @endforeach
                <div class="row">
                    <div class="border-bottom p-3">
                        <input type="number" step="1000" class="form-control" id="pricePerHour1"
                            data-bs-toggle="modal" data-bs-target="#priceModal">
                    </div>
                </div>
                <div class="row">
                    <div class="border-bottom p-3">
                        <input type="number" step="1000" class="form-control" id="fullDayPrice1"
                            data-bs-toggle="modal" data-bs-target="#priceModal">
                    </div>
                </div>
                <div class="row">
                    <div class="p-3">
                        <input type="number" step="1000" class="form-control" id="overnightPrice1"
                            data-bs-toggle="modal" data-bs-target="#priceModal">
                    </div>
                </div>
            </td>

        </tr>
    @endforeach


@endif

@extends('admin.layouts.master_iframe')
@section('panel')
    <div class="row">
        @php
            $province = file_get_contents(resource_path('views/admin/partials/tinh_thanh.json'));
            $provinces = json_decode($province, true);
        @endphp
        <div class="col-12">
            <ul class="nav nav-tabs mb-3" id="hotelTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basicTab"
                        type="button" role="tab">Thông tin cơ bản</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="images-tab" data-bs-toggle="tab" data-bs-target="#imagesTab" type="button"
                        role="tab">Hình ảnh</button>
                </li>
            </ul>
            <form action="{{ route('admin.hotel_configurations.store') }}" method="POST" enctype="multipart/form-data"
                class="form-section">
                @csrf
                <div class="tab-content" id="hotelTabContent">

                    <!-- Tab 1: Thông tin cơ bản -->
                    <div class="tab-pane fade show active" id="basicTab" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="mb-3">
                                    <label for="hotelName" class="form-label required">Tên khách sạn</label>
                                    <input type="text" class="form-control" id="hotelName"
                                        value="{{ $configs?->hotel_name ?? '' }}" name="hotel_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label required">Số điện thoại</label>
                                    <input type="text" class="form-control" id="phone"
                                        value="{{ $configs?->phone ?? '' }}" name="phone"
                                        placeholder="Ví dụ: 0123 456 789" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label required">Email</label>
                                    <input type="text" class="form-control" id="email"
                                        value="{{ $configs?->email ?? '' }}" name="email" placeholder="Email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="pageLink" class="form-label">Link fanpage hoặc YouTube</label>
                                    <input type="url" class="form-control" id="pageLink"
                                        value="{{ $configs?->external_link ?? '' }}" name="external_link"
                                        placeholder="https://...">
                                </div>
                                <div class="mb-3">
                                    <label for="pageLink" class="form-label required">Khu vực </label>
                                    <select id="province-select" name="province_code" class="form-select">
                                        <option value="">-- Chọn tỉnh/thành --</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province['code'] }}" data-name="{{ $province['name'] }}"
                                                {{ $configs?->province_code == $province['code'] ? 'selected' : '' }}>
                                                {{ $province['name'] }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <!-- Hidden input để lưu name -->
                                    <input type="hidden" name="province" id="province-name"
                                        value="{{ $configs?->province_name }}">

                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label required">Địa chỉ maps</label><span
                                        style="font-size: 10px">( Nhập địa chỉ sau dó bấm tìm )</span>
                                    <div class="d-flex  gap-2">
                                        <input type="text" class="form-control" id="address"
                                            value="{{ $configs?->address ?? '' }}" name="address" required> <button
                                            type="button" class="btn btn-primary" style="width: 95px;"
                                            onclick="searchAddress()">Tìm</button>
                                    </div>
                                    <div id="map"></div>
                                    <input type="hidden" id="lat" name="latitude"
                                        value="{{ $configs?->latitude ?? '' }}" readonly>
                                    <input type="hidden" id="lng" name="longitude"
                                        value="{{ $configs?->longitude ?? '' }}" readonly>
                                </div>
                            </div>
                            <div>
                                <label for="pageLink" class="form-label ">Giới thiệu </label>
                                <div class="card-body">
                                    <textarea class="nicEdit" id="description" name="gioi_thieu" rows="13">@php echo @$configs->gioi_thieu ?? old('gioi_thieu') @endphp</textarea>
                                </div>
                            </div>
                            <div>
                                <label for="pageLink" class="form-label ">Chính sách </label>
                                <div class="card-body">
                                    <textarea class="nicEdit" id="description" name="chinh_sach" rows="13">@php echo @$configs->chinh_sach ?? old('chinh_sach') @endphp</textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Tab 2: Hình ảnh -->
                    <div class="tab-pane fade" id="imagesTab" role="tabpanel">
                        <div class="row">
                            <!-- Icon khách sạn -->
                            <div class=" col-md-4 mb-3">
                                <label for="icon" class="form-label required">Icon khách sạn</label>
                                <input class="form-control" type="file" id="icon" name="icon"
                                    accept="image/*" />
                                <div id="iconPreview" class="mt-2">
                                    @if (!empty(optional($configs)->icon))
                                        <div class="preview-img">
                                            <img src="{{ asset('storage/' . $configs->icon) }}" alt="Icon khách sạn"
                                                style="max-width: 100px; max-height: 100px;">
                                            <button type="button" class="btn-remove">&times;</button>
                                        </div>
                                    @endif

                                </div>
                            </div>


                            <!-- Logo -->
                            <div class="  col-md-4 mb-3">
                                <label for="logo" class="form-label required">Logo khách sạn</label>
                                <input class="form-control" type="file" id="logo" name="logo"
                                    accept="image/*" />
                                <div id="logoPreview" class="mt-2">
                                    @if (!empty(optional($configs)->logo))
                                        <div class="preview-img">
                                            <img src="{{ asset('storage/' . $configs->logo) }}" alt="Logo khách sạn"
                                                style="max-width: 100px; max-height: 100px;">
                                            <button type="button" class="btn-remove">&times;</button>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Ảnh chính -->
                            <div class=" col-md-4 mb-3">
                                <label for="mainImage" class="form-label required">Ảnh chính</label>
                                <input class="form-control" type="file" id="mainImage" name="main_image"
                                    accept="image/*" />
                                <div id="mainImagePreview" class="mt-2">
                                    @if (!empty(optional($configs)->main_image))
                                        <div class="preview-img">
                                            <img src="{{ asset('storage/' . $configs->main_image) }}" alt="Ảnh chính"
                                                style="max-width: 100px; max-height: 100px;">
                                            <button type="button" class="btn-remove">&times;</button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>


                        <!-- Thư viện ảnh -->
                        <div class="mb-3">
                            <label for="galleryImages" class="form-label">Thư viện ảnh</label>
                            <input class="form-control" type="file" id="galleryImages" name="gallery_images[]"
                                multiple accept="image/*" />
                            <div id="galleryPreview" class="mt-2 d-flex flex-wrap gap-2">
                                @if (!empty(optional($configs)->hotelFacility) && !empty(optional($configs->hotelFacility)->galleryImages))
                                    @foreach ($configs->hotelFacility->galleryImages as $image)
                                        <div class="preview-img position-relative" data-id="{{ $image->id }}">
                                            <img src="{{ asset('storage/' . $image->image_url) }}" alt="Ảnh gallery"
                                                style="max-width: 150px; max-height: 100px; object-fit: cover; border: 1px solid #ccc; border-radius: 4px;" />
                                            <button type="button" class="btn-remove">&times;</button>
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                        </div>

                    </div>
                </div>

                <!-- Submit -->
                <div class="row">
                    <div class=" mt-3">
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </div>
            </form>


        </div>

    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endpush
@push('script')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        document.getElementById('province-select').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const provinceName = selectedOption.getAttribute('data-name');
            document.getElementById('province-name').value = provinceName;
        });
    </script>

    <script>
        var longitude = "{{ $configs?->longitude ?? 105.8542 }}";
        var latitude = "{{ $configs?->latitude ?? 21.0285 }}";

        const map = L.map('map').setView([parseFloat(latitude), parseFloat(longitude)], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let marker;

        // ✅ Cắm marker mặc định ngay khi load trang
        updateMarker(parseFloat(latitude), parseFloat(longitude));

        // Hàm cập nhật marker và form
        function updateMarker(lat, lng) {
            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng]).addTo(map);
            }

            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;
        }

        // Lắng nghe sự kiện click trên bản đồ
        map.on('click', function(e) {
            const {
                lat,
                lng
            } = e.latlng;
            updateMarker(lat, lng);
        });


        // Hàm tìm địa chỉ
        function searchAddress() {
            const address = document.getElementById('address').value;
            if (!address) return alert('Vui lòng nhập địa chỉ');

            const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (!data.length) {
                        alert('Không tìm thấy địa chỉ');
                        return;
                    }

                    const {
                        lat,
                        lon
                    } = data[0];
                    updateMarker(lat, lon);
                    map.setView([lat, lon], 16);
                })
                .catch(error => {
                    alert('Lỗi khi tìm địa chỉ');
                    console.error(error);
                });
        }


        $(document).ready(function() {
            // Hàm tạo preview ảnh với nút xóa
            function createImagePreview(file, container, inputElement) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const wrapper = document.createElement('div');
                    wrapper.classList.add('preview-img');

                    const img = document.createElement('img');
                    img.src = e.target.result;

                    const btnRemove = document.createElement('button');
                    btnRemove.classList.add('btn-remove');
                    btnRemove.innerHTML = '&times;';

                    btnRemove.addEventListener('click', () => {
                        wrapper.remove();

                        // Xóa file khỏi input.files (bằng DataTransfer)
                        const dt = new DataTransfer();
                        Array.from(inputElement.files)
                            .filter((f) => f !== file)
                            .forEach((f) => dt.items.add(f));
                        inputElement.files = dt.files;
                    });

                    wrapper.appendChild(img);
                    wrapper.appendChild(btnRemove);
                    container.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            }

            function setupImageInputPreview(inputId, previewId) {
                const inputElement = document.getElementById(inputId);
                const previewContainer = document.getElementById(previewId);

                // Xử lý xóa ảnh cũ (đã load từ server)
                previewContainer.addEventListener('click', (e) => {
                    if (e.target.classList.contains('btn-remove')) {
                        const previewDiv = e.target.closest('.preview-img');
                        previewDiv.remove();

                        // Nếu chưa có file mới chọn, thêm input ẩn báo xóa ảnh cũ
                        if (!inputElement.files.length) {
                            let inputDelete = document.getElementById('delete_old_' + inputId);
                            if (!inputDelete) {
                                inputDelete = document.createElement('input');
                                inputDelete.type = 'hidden';
                                inputDelete.name = 'delete_old_' + inputId;
                                inputDelete.value = '1';
                                inputDelete.id = 'delete_old_' + inputId;
                                inputElement.parentNode.appendChild(inputDelete);
                            }
                        }
                    }
                });

                // Xử lý khi chọn ảnh mới: xóa hết preview cũ + preview mới
                inputElement.addEventListener('change', () => {
                    previewContainer.innerHTML = '';
                    if (inputElement.files.length > 0) {
                        createImagePreview(inputElement.files[0], previewContainer, inputElement);
                    }
                });
            }

            // Áp dụng cho từng input
            setupImageInputPreview('icon', 'iconPreview');
            setupImageInputPreview('logo', 'logoPreview');
            setupImageInputPreview('mainImage', 'mainImagePreview');

            function setupMultipleImageInputPreview(inputId, previewId) {
                const inputElement = document.getElementById(inputId);
                const previewContainer = document.getElementById(previewId);

                // Xử lý xóa ảnh cũ từ server
                previewContainer.addEventListener('click', (e) => {
                    if (e.target.classList.contains('btn-remove')) {
                        const previewDiv = e.target.closest('.preview-img');
                        const imageId = previewDiv.getAttribute('data-id');

                        if (imageId && imageId !== 'new') {
                            // Tạo input hidden để gửi ID ảnh cần xóa về server
                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'delete_gallery_images[]';
                            hiddenInput.value = imageId;
                            inputElement.form.appendChild(hiddenInput);
                        }

                        previewDiv.remove();
                    }
                });

                // Xử lý preview ảnh mới
                inputElement.addEventListener('change', () => {
                    // Xoá tất cả preview ảnh mới trước đó
                    previewContainer.querySelectorAll('.preview-img[data-id="new"]').forEach(div => div
                        .remove());

                    Array.from(inputElement.files).forEach((file) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const wrapper = document.createElement('div');
                            wrapper.classList.add('preview-img');
                            wrapper.setAttribute('data-id', 'new');
                            wrapper.style.position = 'relative';

                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.style =
                                "max-width: 150px; max-height: 100px; object-fit: cover; border: 1px solid #ccc; border-radius: 4px;";

                            const btnRemove = document.createElement('button');
                            btnRemove.classList.add('btn-remove');
                            btnRemove.innerHTML = '&times;';

                            btnRemove.addEventListener('click', () => {
                                wrapper.remove();
                                const dt = new DataTransfer();
                                Array.from(inputElement.files)
                                    .filter(f => f !== file)
                                    .forEach(f => dt.items.add(f));
                                inputElement.files = dt.files;
                            });

                            wrapper.appendChild(img);
                            wrapper.appendChild(btnRemove);
                            previewContainer.appendChild(wrapper);
                        };
                        reader.readAsDataURL(file);
                    });
                });
            }
            setupMultipleImageInputPreview('galleryImages', 'galleryPreview');

        });
    </script>
@endpush
<style scoped>
    .preview-img {
        position: relative;
        display: inline-block;
        max-height: 100px;
        margin: 5px;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid #ccc;
    }

    #map {
        height: 400px;
        width: 100%;
        margin-top: 10px;
    }

    .preview-img img {
        display: block;
        max-height: 100px;
        max-width: 100px;
    }

    .btn-remove {
        position: absolute;
        top: 2px;
        right: 2px;
        background: rgba(255, 0, 0, 0.7);
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 14px;
        line-height: 18px;
        cursor: pointer;
        padding: 0;
        text-align: center;
    }
</style>

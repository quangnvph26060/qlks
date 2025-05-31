@extends('admin.layouts.master_iframe')
@section('panel')
    <div class="row">
        <div class="col-12">
            <form action="#" method="POST" enctype="multipart/form-data" class="form-section">

                <!-- Tên khách sạn -->
                <div class="mb-3">
                    <label for="hotelName" class="form-label">Tên khách sạn</label>
                    <input type="text" class="form-control" id="hotelName" name="hotel_name" required>
                </div>

                <!-- Logo (có preview + xóa) -->
                <div class="mb-3">
                    <label for="logo" class="form-label">Logo khách sạn</label>
                    <input class="form-control" type="file" id="logo" name="logo" accept="image/*" />
                    <div id="logoPreview" class="mt-2"></div>
                </div>

                <!-- Ảnh chính (có preview + xóa) -->
                <div class="mb-3">
                    <label for="mainImage" class="form-label">Ảnh chính</label>
                    <input class="form-control" type="file" id="mainImage" name="main_image" accept="image/*" />
                    <div id="mainImagePreview" class="mt-2"></div>
                </div>

                <!-- Thêm nhiều ảnh (có preview + xóa) -->
                <div class="mb-3">
                    <label for="galleryImages" class="form-label">Thư viện ảnh (nhiều ảnh)</label>
                    <input class="form-control" type="file" id="galleryImages" name="gallery_images[]" multiple
                        accept="image/*" />
                    <div id="galleryPreview" class="mt-2"></div>
                </div>

                <!-- Địa chỉ -->
                <div class="mb-3">
                    <label for="address" class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>

                <!-- Link page hoặc YouTube -->
                <div class="mb-3">
                    <label for="pageLink" class="form-label">Link fanpage hoặc video YouTube</label>
                    <input type="url" class="form-control" id="pageLink" name="page_link" placeholder="https://...">
                </div>

                <!-- Nút submit -->
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Lưu cấu hình</button>
                </div>
            </form>
        </div>
   
    </div>
@endsection
@push('script')
    <script>
      $(document).ready(function() {
  // Hàm tạo preview ảnh với nút xóa
  function createImagePreview(file, container, inputElement) {
    const reader = new FileReader();
    reader.onload = function (e) {
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

  // Preview cho logo (1 file)
  const logoInput = document.getElementById('logo');
  const logoPreview = document.getElementById('logoPreview');
  logoInput.addEventListener('change', () => {
    logoPreview.innerHTML = ''; // xóa preview cũ
    if (logoInput.files.length > 0) {
      createImagePreview(logoInput.files[0], logoPreview, logoInput);
    }
  });

  // Preview cho ảnh chính (1 file)
  const mainImageInput = document.getElementById('mainImage');
  const mainImagePreview = document.getElementById('mainImagePreview');
  mainImageInput.addEventListener('change', () => {
    mainImagePreview.innerHTML = ''; // xóa preview cũ
    if (mainImageInput.files.length > 0) {
      createImagePreview(mainImageInput.files[0], mainImagePreview, mainImageInput);
    }
  });

  // Preview cho nhiều ảnh (gallery)
  const galleryInput = document.getElementById('galleryImages');
  const galleryPreview = document.getElementById('galleryPreview');
  galleryInput.addEventListener('change', () => {
    galleryPreview.innerHTML = ''; // xóa preview cũ
    Array.from(galleryInput.files).forEach((file) => {
      createImagePreview(file, galleryPreview, galleryInput);
    });
  });
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

    .preview-img img {
        display: block;
        max-height: 100px;
        max-width: 100px;
    }
#logoPreview, #mainImagePreview, #galleryPreview {
  max-height: 120px;      /* chiều cao tối đa */
  overflow-y: auto;       /* cho phép cuộn dọc nếu vượt quá */
  display: flex;
  gap: 10px;
  padding: 5px;
  border: 1px solid #ddd;
  border-radius: 5px;
  background: #fff;
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

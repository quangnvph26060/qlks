@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-md-12 mb-30">
            <div class="card bl--5 border--primary">
                <div class="card-body">
                    <p class="text--primary">@lang('Nếu logo và favicon không thay đổi sau khi bạn cập nhật từ trang này, vui lòng') <a href="{{ route('admin.system.optimize.clear') }}" class="text--info text-decoration-underline">@lang('Xóa bộ nhớ đệm')</a> @lang('từ trình duyệt của bạn. Vì chúng tôi giữ nguyên tên tệp sau khi cập nhật, nên có thể hiển thị hình ảnh cũ cho bộ nhớ đệm. Thông thường, nó hoạt động sau khi xóa bộ nhớ đệm nhưng nếu bạn vẫn thấy logo hoặc favicon cũ, có thể do bộ nhớ đệm cấp máy chủ hoặc cấp mạng. Vui lòng xóa chúng.')</p>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.setting.update.logo.icon') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row justify-content-center">

                            <div class="form-group col-md-4 col-sm-6">
                                <label> @lang('Logo cho nền trắng')</label>
                                <x-image-uploader name="logo_dark" :imagePath="siteLogo('dark') . '?' . time()" :size="false" class="w-100" id="uploadLogo" :required="false" />
                            </div>
                            <div class="form-group col-md-4 col-sm-6">
                                <label> @lang('Logo For Dark Background')</label>
                                <x-image-uploader name="logo" :imagePath="siteLogo() . '?' . time()" :size="false" class="w-100" id="uploadLogo1" :required="false" :darkMode="true" />
                            </div>
                            <div class="form-group col-md-4 col-sm-6">
                                <label> @lang('Favicon')</label>
                                <x-image-uploader name="favicon" :imagePath="siteFavicon() . '?' . time()" :size="false" class="w-100" id="uploadFavicon" :required="false" />
                            </div>
                        </div>

                        @can('admin.setting.update.logo.icon')
                            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Lưu')</button>
                        @endcan
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

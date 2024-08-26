@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="col-lg-12">

        <div class="card custom--card">
            <div class="card-header">
                <h6 class="card-title">{{ __($pageTitle) }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('ticket.store') }}" method="post" enctype="multipart/form-data" onsubmit="return submitUserForm();">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label>@lang('Subject')</label>
                            <input type="text" name="subject" value="{{ old('subject') }}" class="form--control" placeholder="@lang('Enter subject')" required>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>@lang('Priority')</label>
                            <select name="priority" class="select" required>
                                <option value="3">@lang('High')</option>
                                <option value="2">@lang('Medium')</option>
                                <option value="1">@lang('Low')</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>@lang('Message')</label>
                                <textarea name="message" id="inputMessage" class="form--control" placeholder="@lang('Your reply...')" required>{{ old('message') }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <button type="button" class="btn btn--dark btn-sm addAttachment my-2"> <i class="fas fa-plus"></i> @lang('Add Attachment') </button>
                            <p class="mb-2">
                                <span class="text--info text--small">@lang('Max 5 files can be uploaded | Maximum upload size is ' . convertToReadableSize(ini_get('upload_max_filesize')) . ' | Allowed File Extensions: .jpg, .jpeg, .png, .pdf, .doc, .docx')</span>
                            </p>
                            <div class="row fileUploadsContainer"></div>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn--base w-100 my-2" type="submit"><i class="las la-paper-plane"></i> @lang('Submit')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- card end -->
    </div>
@endsection

@push('style')
    <style>
        .input-group-text:focus {
            box-shadow: none !important;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addAttachment').on('click', function() {
                fileAdded++;
                if (fileAdded == 5) {
                    $(this).attr('disabled', true)
                }

                $(".fileUploadsContainer").append(`
                <div class="col-lg-6 col-md-12 removeFileInput">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="file" name="attachments[]" class="form-control form--control" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
                            <button type="button" class="input-group-text removeFile bg--danger border-0"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                </div>
            `)
            });
            $(document).on('click', '.removeFile', function() {
                $('.addAttachment').removeAttr('disabled', true)
                fileAdded--;
                $(this).closest('.removeFileInput').remove();
            });
        })(jQuery);
    </script>
@endpush

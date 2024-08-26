@extends($activeTemplate . 'layouts.' . $layout)
@section('content')

    @if ($layout == 'frontend')
        <section class="pt-80 pb-80">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
    @endif

    <div class="card custom--card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-10 d-flex align-items-center flex-wrap">
                    @php echo $myTicket->statusBadge; @endphp

                    <h6 class="ms-2">[@lang('Ticket')#{{ $myTicket->ticket }}] {{ $myTicket->subject }}</h6>
                </div>
                <div class="col-sm-2 text-end">
                    @if ($myTicket->status != Status::TICKET_CLOSE && $myTicket->user)
                        <button class="btn btn--danger btn-sm confirmationBtn" type="button" data-question="@lang('Are you sure to close this ticket?')" data-action="{{ route('ticket.close', $myTicket->id) }}" @guest disabled @endguest><i class="la la-lg la-times-circle"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="post" class="disableSubmission" action="{{ route('ticket.reply', $myTicket->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="row justify-content-between">
                    <div class="col-md-12">
                        <div class="form-group">
                            <textarea name="message" class="form-control form--control" rows="4" required>{{ old('message') }}</textarea>
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
                        <button class="btn btn--base w-100 my-2" type="submit"><i class="la la-fw la-lg la-reply"></i> @lang('Reply')</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="card custom--card mt-4 border-0 bg-transparent">
        @foreach ($messages as $message)
            @if ($message->admin_id == 0)
                <div class="single-reply">
                    <div class="left">
                        <h6>{{ $message->ticket->name }}</h6>
                    </div>
                    <div class="right">
                        <small class="fs--14px text--base mb-2">@lang('Posted on')
                            {{ $message->created_at->format('l, dS F Y @ H:i') }}</small>
                        <p>{{ $message->message }}</p>
                        @if ($message->attachments->count() > 0)
                            <div class="mt-2">
                                @foreach ($message->attachments as $k => $image)
                                    <a href="{{ route('ticket.download', encrypt($image->id)) }}" class="me-3"><i class="la la-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="single-reply author-reply">
                    <div class="left">
                        <h6>{{ $message->admin->name }}</h6>
                        <small class="lead text-muted">@lang('Staff')</small>
                    </div>
                    <div class="right">
                        <small class="fs--14px text--base mb-2">@lang('Posted on')
                            {{ $message->created_at->format('l, dS F Y @ H:i') }}</small>
                        <p>{{ $message->message }}</p>
                        @if ($message->attachments->count() > 0)
                            <div class="mt-2">
                                @foreach ($message->attachments as $k => $image)
                                    <a href="{{ route('ticket.download', encrypt($image->id)) }}" class="me-3"><i class="la la-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    </div>
    </div>
    </div>
    </div>

    @if ($layout == 'frontend')
        </div>
        </section>
    @endif

    <x-confirmation-modal />

@endsection

@push('style')
    <style>
        .input-group-text:focus {
            box-shadow: none !important;
        }

        #confirmationModal .btn {
            padding: 0.375rem .75rem !important;
        }

        .author-reply {
            background-color: #ab8a625c;
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

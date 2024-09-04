@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('User')</th>
                                    <th>@lang('Email-Mobile')</th>
                                    <th>@lang('Country')</th>
                                    <th>@lang('Joined At')</th>
                                    @can('admin.users.detail')
                                        <th>@lang('Action')</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $user->fullname }}</span>
                                            @can('admin.users.detail')
                                                <br>
                                                <span class="small">
                                                    <a
                                                        href="{{ route('admin.users.detail', $user->id) }}"><span>@</span>{{ $user->username }}</a>
                                                </span>
                                            @endcan
                                        </td>
                                        <td>
                                            {{ $user->email }}<br>{{ $user->mobileNumber }}
                                        </td>
                                        <td>
                                            <span class="fw-bold"
                                                title="{{ @$user->country_name }}">{{ $user->country_code }}</span>
                                        </td>
                                        <td>
                                            {{ showDateTime($user->created_at) }} <br>
                                            {{ diffForHumans($user->created_at) }}
                                        </td>
                                        @can('admin.users.detail')
                                            <td>
                                                <a class="btn btn-sm btn-outline--primary"
                                                    href="{{ route('admin.users.detail', $user->id) }}">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </a>
                                            </td>
                                        @endcan
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($users->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($users) }}
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .date-picker button {
            top: 50%;
            transform: translateY(-50%);
            right: 5px;
            background: none;
            font-weight: bold;
        }
    </style>
@endpush

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#dateInput", {
            dateFormat: "Y-m-d", // Định dạng ngày
            allowInput: true
        });

        $("#dateInput").on("change", function(e) {
            if ($(this).val() != "") {
                $(".date-picker button").removeClass("d-none");
            }
        })

        if($("#dateInput").val() != "") {
            $(".date-picker button").removeClass("d-none");
        }

        $(".date-picker button").on("click", function() {
            $("#dateInput").val("");

            if($("#dateInput").val() == "") {
            $(".date-picker button").addClass("d-none");
        }
        })
    </script>
@endpush

@push('breadcrumb-plugins')
    <x-search-form placeholder="Username / Email" />
@endpush

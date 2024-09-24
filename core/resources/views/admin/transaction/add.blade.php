@extends('admin.layouts.app')
@section('panel')
    @push('topBar')
        @include('admin.gateways.top_bar')
    @endpush
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <form action="{{ route('admin.transaction.store') }}" class="disableSubmission" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="payment-method-item">
                            <label for="name" class="form-label">Tên phương thức</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                    </div>

                    @can('admin.gateway.manual.store')
                        <div class="card-footer">
                            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                        </div>
                    @endcan
                </form>
            </div>
        </div>
    </div>

    <x-form-generator-modal />
@endsection

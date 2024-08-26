@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">

                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Created At')</th>
                                    @can('admin.roles.edit')
                                        <th>@lang('Action')</th>
                                    @endcan
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($roles as $role)
                                    <tr>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ showDateTime($role->created_at) }}</td>
                                        @can('admin.roles.edit')
                                            <td>
                                                <a class="btn btn-sm btn-outline--primary" href="{{ route('admin.roles.edit', $role->id) }}"><i class="las la-pencil-alt"></i>@lang('Edit')</a>
                                            </td>
                                        @endcan
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@can('admin.roles.add')
    @push('breadcrumb-plugins')
        <a class="btn btn-sm btn-outline--primary" href="{{ route('admin.roles.add') }}"><i class="las la-plus"></i>@lang('Add New')</a>
    @endpush
@endcan

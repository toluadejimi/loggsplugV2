@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-8">
            @if(session('new_api_key'))
                <div class="alert alert-success">
                    <strong>@lang('New API key (save it; it will not be shown again):')</strong>
                    <code class="d-block mt-2">{{ session('new_api_key') }}</code>
                </div>
            @endif
            <div class="card b-radius--10">
                <div class="card-body">
                    <form action="{{ route('admin.resellers.update', $reseller->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>@lang('User')</label>
                            <input type="text" class="form-control" value="{{ $reseller->user->username ?? '' }} ({{ $reseller->user->email ?? '' }})" disabled>
                        </div>
                        <div class="form-group">
                            <label>@lang('Admin discount %')</label>
                            <input type="number" step="0.01" min="0" max="99.99" name="admin_discount_percent" class="form-control" value="{{ old('admin_discount_percent', $reseller->admin_discount_percent) }}">
                        </div>
                        <div class="form-group">
                            <label>@lang('Status')</label>
                            <select name="status" class="form-control">
                                <option value="1" {{ $reseller->status == 1 ? 'selected' : '' }}>@lang('Active')</option>
                                <option value="0" {{ $reseller->status == 0 ? 'selected' : '' }}>@lang('Suspended')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Business name')</label>
                            <input type="text" name="business_name" class="form-control" value="{{ old('business_name', $reseller->business_name) }}">
                        </div>
                        <div class="form-group">
                            <label>@lang('Contact email')</label>
                            <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $reseller->contact_email) }}">
                        </div>
                        <button type="submit" class="btn btn--primary">@lang('Update')</button>
                    </form>
                </div>
            </div>
            <div class="card b-radius--10 mt-3">
                <div class="card-body">
                    <h6 class="mb-3">@lang('API Key')</h6>
                    <p class="text-muted small">@lang('Revoke to block API access. Regenerate to issue a new key (old key stops working).')</p>
                    <div class="d-flex gap-2">
                        <form action="{{ route('admin.resellers.revoke-key', $reseller->id) }}" method="POST" class="d-inline" onsubmit="return confirm('@lang('Revoke API key? Reseller will not be able to use the API until you regenerate.')');">
                            @csrf
                            <button type="submit" class="btn btn-outline--danger btn-sm">@lang('Revoke Key')</button>
                        </form>
                        <form action="{{ route('admin.resellers.regenerate-key', $reseller->id) }}" method="POST" class="d-inline" onsubmit="return confirm('@lang('Generate new key? Current key will stop working.')');">
                            @csrf
                            <button type="submit" class="btn btn-outline--primary btn-sm">@lang('Regenerate Key')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.resellers.index') }}" class="btn btn-sm btn--secondary">
        <i class="las la-arrow-left"></i> @lang('Back')
    </a>
    <a href="{{ route('admin.resellers.orders', $reseller->id) }}" class="btn btn-sm btn--primary">
        <i class="las la-list"></i> @lang('Orders')
    </a>
    <a href="{{ url('/reseller-site/download?api_key=' . urlencode($reseller->api_key)) }}" class="btn btn-sm btn--success" target="_blank">
        <i class="las la-download"></i> @lang('Download Mini-Site')
    </a>
@endpush

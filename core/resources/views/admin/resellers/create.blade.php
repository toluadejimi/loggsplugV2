@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-8">
            <div class="card b-radius--10">
                <div class="card-body">
                    <form action="{{ route('admin.resellers.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>@lang('User')</label>
                            <select name="user_id" class="form-control" required>
                                <option value="">@lang('Select User')</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->username }} ({{ $u->email }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Only users who are not already resellers are listed.</small>
                        </div>
                        <div class="form-group">
                            <label>@lang('Admin discount %')</label>
                            <input type="number" step="0.01" min="0" max="99.99" name="admin_discount_percent" class="form-control" value="{{ old('admin_discount_percent', 0) }}" placeholder="0">
                            <small class="text-muted">Platform cut from base price. Reseller pays (100 - this)% of product price.</small>
                        </div>
                        <div class="form-group">
                            <label>@lang('Business name')</label>
                            <input type="text" name="business_name" class="form-control" value="{{ old('business_name') }}" placeholder="Optional">
                        </div>
                        <div class="form-group">
                            <label>@lang('Contact email')</label>
                            <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email') }}" placeholder="Optional, defaults to user email">
                        </div>
                        <button type="submit" class="btn btn--primary">@lang('Create Reseller')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

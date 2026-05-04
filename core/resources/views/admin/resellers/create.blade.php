@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-8">
            <div class="card b-radius--10">
                <div class="card-body">
                    <form action="{{ route('admin.resellers.store') }}" method="POST">
                        @csrf
                        <div class="form-group position-relative" id="reseller_user_select_wrapper">
                            <label>@lang('User')</label>
                            <select name="user_id" id="reseller_user_select" class="form-control" required>
                                @if($oldUserId ?? null)
                                    <option value="{{ $oldUserId }}" selected>{{ $oldUserLabel }}</option>
                                @endif
                            </select>
                            <small class="text-muted">@lang('Search by username or email. Only users who are not already resellers are listed.')</small>
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
                        <div class="form-group">
                            <label>@lang('Public site URL (API)')</label>
                            <input type="text" name="api_site_url" class="form-control" value="{{ old('api_site_url') }}" placeholder="https://reseller-shop.example.com">
                            <small class="text-muted">@lang('Optional here; reseller must set a valid URL before API calls work.')</small>
                        </div>
                        <button type="submit" class="btn btn--primary">@lang('Create Reseller')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function ($) {
            'use strict';
            $('#reseller_user_select').select2({
                ajax: {
                    url: @json(route('admin.resellers.search-users')),
                    dataType: 'json',
                    delay: 300,
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.results,
                            pagination: data.pagination
                        };
                    },
                    cache: true
                },
                placeholder: @json(__('Type to search users…')),
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                dropdownParent: $('#reseller_user_select_wrapper')
            });
        })(jQuery);
    </script>
@endpush

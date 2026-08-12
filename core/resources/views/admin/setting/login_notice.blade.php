@extends('admin.layouts.app')
@section('panel')
<div class="row mb-none-30">
    <div class="col-lg-12">
        <div class="card">
            <form action="" method="post">
                @csrf
                <div class="card-body">
                    <p class="text-muted mb-4">
                        @lang('This popup appears once after a customer successfully logs in. Disable it anytime, or edit the message as needed.')
                    </p>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Status')</label>
                                <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" @if(@$notice->data_values->status) checked @endif name="status">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>@lang('Title')</label>
                        <input type="text" class="form-control" name="title" value="{{ old('title', @$notice->data_values->title ?: 'Notice') }}" required maxlength="120">
                    </div>
                    <div class="form-group">
                        <label>@lang('Message')</label>
                        <textarea class="form-control nicEdit" rows="12" name="description">@php echo old('description', @$notice->data_values->description) @endphp</textarea>
                        <small class="text-muted">@lang('You can add links (WhatsApp, Telegram, etc.) and style text in the editor.')</small>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

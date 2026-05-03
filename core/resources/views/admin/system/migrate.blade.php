@extends('admin.layouts.app')
@section('panel')
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card b-radius--10">
                <div class="card-body">
                    <p class="mb-3">
                        @lang('Run pending Laravel migrations on the server database. Use this when you cannot use SSH or the hosting terminal.')
                    </p>
                    <ul class="list-unstyled mb-4 text-muted">
                        <li class="mb-2"><i class="las la-exclamation-triangle text--warning"></i> @lang('Take a database backup first (System → Database backup).')</li>
                        <li class="mb-2"><i class="las la-info-circle"></i> @lang('This runs the same as:') <code>php artisan migrate --force</code></li>
                    </ul>
                    @if (session('migrate_cli_output'))
                        <div class="alert alert--dark mb-4">
                            <strong>@lang('Output')</strong>
                            <pre class="mb-0 mt-2 small" style="white-space: pre-wrap; max-height: 320px; overflow: auto;">{{ session('migrate_cli_output') }}</pre>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <form action="{{ route('admin.system.migrate.run') }}" method="post" onsubmit="return confirm(@json(__('Run pending migrations now?')));">
                        @csrf
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Run migrations')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

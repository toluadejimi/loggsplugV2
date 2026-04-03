@extends('admin.layouts.app')
@section('panel')
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card b-radius--10 mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">@lang('Create backup')</h5>
                    <p class="text-muted">
                        @lang('Large databases cannot finish inside one browser request (the server will time out). Use the button below to run the backup in the background, then download the file from the list when it appears.')
                    </p>
                    <ul class="text-muted small mb-3">
                        <li>@lang('Current queue driver'): <code>{{ $queueDriver }}</code></li>
                        <li>
                            @lang('If backups never complete, set') <code>QUEUE_CONNECTION=database</code> @lang('in') <code>.env</code>,
                            @lang('run') <code>php artisan migrate</code> @lang('(creates the jobs table if missing), then on the server run:')
                            <code>php artisan queue:work --timeout=7200</code>
                            @lang('(Supervisor or a screen session is ideal).')
                        </li>
                        <li>@lang('With') <code>sync</code> @lang('queue, the backup still runs on the server after this page loads, but very long jobs may be stopped by PHP-FPM; a real queue worker is more reliable.')</li>
                    </ul>
                    <form action="{{ route('admin.system.database.backup.queue') }}" method="post" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn--primary h-45">@lang('Start background backup')</button>
                    </form>
                </div>
            </div>

            <div class="card b-radius--10 mb-4" id="db-backup-status-card">
                <div class="card-body">
                    <h5 class="card-title mb-3">@lang('Status')</h5>
                    <p class="mb-0" id="db-backup-status-text">
                        @if (is_array($status ?? null))
                            <strong>{{ strtoupper($status['state'] ?? '') }}</strong>
                            — {{ $status['message'] ?? '' }}
                            @if (!empty($status['file']))
                                <span class="d-block mt-2">@lang('File'): <code>{{ $status['file'] }}</code></span>
                            @endif
                        @else
                            @lang('No recent backup activity.')
                        @endif
                    </p>
                </div>
            </div>

            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table--light style--two mb-0" id="db-backup-files-table">
                            <thead>
                                <tr>
                                    <th>@lang('File')</th>
                                    <th>@lang('Size')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($files as $f)
                                    <tr>
                                        <td><code>{{ $f['name'] }}</code></td>
                                        <td>{{ number_format($f['size'] / 1024, 1) }} KB</td>
                                        <td>{{ date('Y-m-d H:i:s', $f['mtime']) }}</td>
                                        <td>
                                            <a href="{{ route('admin.system.database.backup.download', ['file' => $f['name']]) }}" class="btn btn-sm btn--primary">@lang('Download')</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">@lang('No backup files yet.')</td>
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

@push('script')
<script>
(function () {
    var statusUrl = @json(route('admin.system.database.backup.status'));
    function esc(s) {
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }
    function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        return (bytes / 1024).toFixed(1) + ' KB';
    }
    function refresh() {
        fetch(statusUrl, { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                var el = document.getElementById('db-backup-status-text');
                if (el && data.status && typeof data.status === 'object') {
                    var st = data.status;
                    var line = '<strong>' + esc(String(st.state || '').toUpperCase()) + '</strong> — ' + esc(String(st.message || ''));
                    if (st.file) line += '<span class="d-block mt-2">@lang('File'): <code>' + esc(String(st.file)) + '</code></span>';
                    el.innerHTML = line;
                }
                var tbody = document.querySelector('#db-backup-files-table tbody');
                if (tbody && Array.isArray(data.files)) {
                    if (data.files.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">@lang('No backup files yet.')</td></tr>';
                    } else {
                        var dlBase = @json(url('/admin/system/database-backup/download/'));
                        tbody.innerHTML = data.files.map(function (f) {
                            return '<tr><td><code>' + esc(f.name) + '</code></td><td>' + esc(formatSize(f.size)) + '</td><td>' + esc(new Date(f.mtime * 1000).toISOString().replace('T', ' ').slice(0, 19)) + '</td><td><a href="' + esc(dlBase + encodeURIComponent(f.name)) + '" class="btn btn-sm btn--primary">@lang('Download')</a></td></tr>';
                        }).join('');
                    }
                }
            })
            .catch(function () {});
    }
    setInterval(refresh, 8000);
})();
</script>
@endpush

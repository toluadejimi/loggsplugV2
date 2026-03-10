@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Business')</th>
                                <th>@lang('Discount %')</th>
                                <th>@lang('Balance')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('API Key')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($resellers as $r)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $r->user->username ?? '-' }}</span>
                                        <br><span class="small">{{ $r->user->email ?? '' }}</span>
                                    </td>
                                    <td>{{ $r->business_name ?: '-' }}</td>
                                    <td>{{ $r->admin_discount_percent }}%</td>
                                    <td>{{ $general->cur_sym }}{{ showAmount($r->user->balance ?? 0) }}</td>
                                    <td>
                                        @if($r->status == Status::ENABLE && !$r->api_key_revoked_at)
                                            <span class="badge badge--success">@lang('Active')</span>
                                        @elseif($r->api_key_revoked_at)
                                            <span class="badge badge--danger">@lang('Key Revoked')</span>
                                        @else
                                            <span class="badge badge--warning">@lang('Suspended')</span>
                                        @endif
                                    </td>
                                    <td>
                                        <code class="small">{{ Str::limit($r->api_key, 20) }}</code>
                                    </td>
                                    <td>
                                        <div class="button--group">
                                            <a href="{{ route('admin.resellers.edit', $r->id) }}" class="btn btn-sm btn-outline--primary">
                                                <i class="las la-pen"></i> @lang('Edit')
                                            </a>
                                            <a href="{{ route('admin.resellers.orders', $r->id) }}" class="btn btn-sm btn-outline--info">
                                                <i class="las la-list"></i> @lang('Orders')
                                            </a>
                                        </div>
                                    </td>
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
                @if($resellers->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($resellers) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.resellers.create') }}" class="btn btn-sm btn--primary">
        <i class="las la-plus"></i> @lang('Add Reseller')
    </a>
    <a href="{{ url('/reseller-site/download') }}" class="btn btn-sm btn--success" target="_blank">
        <i class="las la-download"></i> @lang('Download Mini-Site Template')
    </a>
@endpush

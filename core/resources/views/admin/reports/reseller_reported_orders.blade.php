@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body">
                <h5 class="card-title mb-4">@lang('Reseller reported orders')</h5>
                <p class="text-muted">Orders reported by resellers so you can replace the product. Open order details to handle replacement.</p>
            </div>
            <div class="table-responsive--sm table-responsive">
                <table class="table table--light style--two">
                    <thead>
                        <tr>
                            <th>@lang('Order ID')</th>
                            <th>@lang('Reseller')</th>
                            <th>@lang('Product')</th>
                            <th>@lang('Reported at')</th>
                            <th>@lang('Reason')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td><span class="fw-bold">{{ $order->id }}</span></td>
                                <td>
                                    {{ $order->reseller->business_name ?? '—' }}
                                    @if($order->reseller && $order->reseller->user)
                                        <br><span class="small text-muted">{{ $order->reseller->user->email ?? '' }}</span>
                                    @endif
                                </td>
                                <td>{{ $order->orderItems->first()->product->name ?? '—' }}</td>
                                <td>{{ $order->reported_at ? showDateTime($order->reported_at) : '—' }}</td>
                                <td><span class="small">{{ $order->report_reason ?: '—' }}</span></td>
                                <td>
                                    <a href="{{ route('admin.report.order.details', $order->id) }}" class="btn btn-sm btn-outline--primary">
                                        <i class="las la-desktop"></i> @lang('View / Replace')
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="6">@lang('No reseller reported orders.')</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($orders->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($orders) }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

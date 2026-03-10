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
                                <th>@lang('Order ID')</th>
                                <th>@lang('Product')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Date')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->orderItems->first()->product->name ?? '-' }}</td>
                                    <td>{{ $general->cur_sym }}{{ showAmount($order->total_amount) }}</td>
                                    <td>{{ showDateTime($order->created_at) }}</td>
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
                @if($orders->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($orders) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.resellers.index') }}" class="btn btn-sm btn--secondary">
        <i class="las la-arrow-left"></i> @lang('Back to Resellers')
    </a>
@endpush

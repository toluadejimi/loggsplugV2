@extends($activeTemplate . 'layouts.main')
@section('content')

    <div class="dashboard-body__content category-products-page">

        <a href="{{ route('products') }}" class="category-products-back d-inline-flex align-items-center gap-2 text-muted text-decoration-none mb-4">
            <i class="las la-arrow-left"></i>
            <span>All products</span>
        </a>

        <div class="category-products-header mb-4">
            <h1 class="category-products-title mb-2">{{ __($category->name ?? 'Category') }}</h1>
            <p class="text-muted small mb-0">{{ $products->total() }} {{ $products->total() === 1 ? 'product' : 'products' }}</p>
        </div>

        <section class="category-products-list">
            @forelse($products as $product)
                @include($activeTemplate . 'partials.products')
            @empty
                <div class="category-products-empty card border-0 shadow-sm text-center py-5 px-4">
                    <div class="category-products-empty-icon mb-3">
                        <i class="las la-box-open text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-600 mb-2">No products found</h5>
                    <p class="text-muted mb-4">No results for "{{ __($category->name ?? '') }}". Try browsing all products.</p>
                    <a href="{{ route('products') }}" class="btn btn-primary rounded-pill px-4">Browse all products</a>
                </div>
            @endforelse
        </section>

        @if($products->hasPages())
            <div class="category-products-pagination mt-4 d-flex justify-content-center">
                {{ paginateLinks($products) }}
            </div>
        @endif
    </div>

@endsection

@push('style')
<style>
.category-products-page { padding-bottom: 2rem; }
.category-products-back:hover { color: #3219E3 !important; }
.category-products-title { font-size: 1.5rem; font-weight: 700; color: #1e293b; }
.category-products-empty { border-radius: 12px; }
/* Reuse product card look from products page */
.category-products-page .product-card {
    border-radius: 12px;
    transition: box-shadow 0.2s ease;
}
.category-products-page .product-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08) !important;
}
.category-products-page .product-card__img {
    width: 56px; height: 56px; object-fit: cover; display: block; border-radius: 8px;
}
.category-products-page .product-card__pill {
    display: inline-block;
    padding: 0.25rem 0.65rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #fff;
    background: #1e293b;
    border-radius: 9999px;
}
.category-products-page .product-card__pill--stock { background: #0f172a; }
.category-products-page .product-card__title { font-size: 0.95rem; font-weight: 600; }
.category-products-page .product-card__title:hover { color: #3219E3 !important; }
</style>
@endpush

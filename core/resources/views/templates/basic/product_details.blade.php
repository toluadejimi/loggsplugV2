@extends($activeTemplate . 'layouts.main')
@section('content')

    <div class="dashboard-body__content product-detail-page">

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session()->get('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session()->get('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Back link --}}
        <a href="{{ route('products') }}" class="product-detail-back d-inline-flex align-items-center gap-2 text-muted text-decoration-none mb-4">
            <i class="las la-arrow-left"></i>
            <span>Back to products</span>
        </a>

        <div class="row g-4">
            {{-- Product image --}}
            <div class="col-lg-4 col-md-5">
                <div class="product-detail-card product-detail-card--image card border-0 shadow-sm overflow-hidden">
                    <img src="{{ getImage(getFilePath('product') . '/' . $product->image, getFileSize('product')) }}"
                         alt="{{ __($product->name) }}"
                         class="product-detail-img card-img-top">
                </div>
            </div>

            {{-- Product info & form --}}
            <div class="col-lg-8 col-md-7">
                <div class="product-detail-card card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="product-detail-title mb-3">{{ __($product->name) }}</h1>
                        <div class="product-detail-meta d-flex flex-wrap align-items-center gap-2 mb-4">
                            <span class="product-detail-pill product-detail-pill--price">{{ $general->cur_sym ?? 'NGN' }}{{ number_format($product->price) }} <small>/ pcs</small></span>
                            <span class="product-detail-pill product-detail-pill--stock">{{ number_format($product->in_stock) }} in stock</span>
                        </div>


                        <div class="product-detail-disclaimer card mb-4">
                        <div class="card-body py-3">
                        <div class="product-detail-description mb-4">
                            <h6 class="text-muted text-uppercase small fw-600 mb-2">Description</h6>
                            <div class="product-detail-description__body">
                                @php echo $product->description; @endphp
                            </div>
                        </div>
                        </div>
                        </div>
                    

                        <form id="buyForm" action="{{ route('user.deposit.insert') }}" method="POST" class="product-detail-form">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <input type="hidden" name="payment" value="wallet">
                            <input type="hidden" name="gateway" value="250">

                            <div class="product-detail-order-row row align-items-center g-3 mb-4">
                                <div class="col-auto">
                                    <label for="quantity" class="form-label small text-muted mb-1">Quantity</label>
                                    <input type="number"
                                           name="qty"
                                           id="quantity"
                                           class="form-control product-detail-qty"
                                           value="1"
                                           min="1"
                                           max="{{ min($product->in_stock, 100) }}"
                                           aria-label="Quantity">
                                </div>
                                <div class="col">
                                    <label class="form-label small text-muted mb-1 d-block">Total</label>
                                    <p class="product-detail-total mb-0 fw-700">{{ $general->cur_sym ?? 'NGN' }}<span id="total">{{ number_format($product->price, 2) }}</span></p>
                                </div>
                            </div>

                            <div class="product-detail-share mb-4">
                                <h6 class="text-muted text-uppercase small fw-600 mb-2">Share</h6>
                                <div id="social-links" class="product-detail-share-links">
                                    {!! $shareComponent !!}
                                </div>
                            </div>

                            <div class="product-detail-disclaimer card border mb-4">
                                <div class="card-body py-3">
                                    <p class="small text-muted mb-0">
                                        By purchasing, you agree to our terms and conditions.
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#TermsModal" class="fw-600">Terms &amp; conditions</a>
                                    </p>
                                </div>
                            </div>

                            @if($product->in_stock > 0)
                                <button type="button"
                                        id="buyNowBtn"
                                        class="btn product-detail-btn-buy btn-lg w-100 rounded-pill d-inline-flex align-items-center justify-content-center gap-2">
                                    <i class="las la-shopping-cart"></i>
                                    Buy now
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary btn-lg w-100 rounded-pill" disabled>
                                    Out of stock
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Related products --}}
        @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
            <section class="product-detail-related mt-5 pt-4">
                <h5 class="fw-600 mb-3">Related products</h5>
                <div class="row g-3">
                    @foreach($relatedProducts->take(4) as $rel)
                        <div class="col-6 col-md-3">
                            <a href="{{ route('product.details', $rel->id) }}" class="product-detail-related-card card border-0 shadow-sm h-100 text-decoration-none text-dark">
                                <img src="{{ getImage(getFilePath('product') . '/' . $rel->image, getFileSize('product')) }}"
                                     alt="{{ __($rel->name) }}"
                                     class="card-img-top product-detail-related-img">
                                <div class="card-body py-2 px-3">
                                    <p class="small fw-600 mb-1 text-truncate">{{ __($rel->name) }}</p>
                                    <p class="small text-muted mb-0">{{ $general->cur_sym ?? 'NGN' }}{{ number_format($rel->price) }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    {{-- Terms modal --}}
    <div id="TermsModal" class="modal fade" tabindex="-1" aria-labelledby="TermsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-600" id="TermsModalLabel">Terms and conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2">
                    <p class="text-muted small mb-0">
                        Do not use our products to harm others, for bullying on social networks, comment spam, threats, or any illegal activity including fraud, extortion, or data theft.
                    </p>
                    <p class="text-danger small fw-600 mt-2 mb-0">Do not use our products for illegal activities.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            var quantityEl = document.getElementById("quantity");
            var totalEl = document.getElementById("total");
            if (quantityEl && totalEl) {
                var price = {{ $product->price }};
                function syncTotal() {
                    var qty = parseInt(quantityEl.value, 10) || 1;
                    totalEl.textContent = (qty * price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
                quantityEl.addEventListener('input', syncTotal);
                quantityEl.addEventListener('change', syncTotal);
            }
        })();
    </script>
@endsection

@push('style')
<style>
/* Product detail page */
.product-detail-page { padding-bottom: 2rem; }
.product-detail-back:hover { color: #3219E3 !important; }
.product-detail-card { border-radius: 12px; }
.product-detail-card--image { background: #f8fafc; }
.product-detail-img {
    width: 100%;
    height: 280px;
    object-fit: contain;
    padding: 1rem;
}
@media (min-width: 768px) {
    .product-detail-img { height: 320px; }
}
.product-detail-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1.3;
}
.product-detail-meta { gap: 0.5rem; }
.product-detail-pill {
    display: inline-block;
    padding: 0.35rem 0.85rem;
    font-size: 0.9rem;
    font-weight: 600;
    color: #fff;
    background: #1e293b;
    border-radius: 9999px;
}
.product-detail-pill--stock { background: #0f172a; }
.product-detail-pill--price small { opacity: 0.9; font-weight: 500; }
.product-detail-description__body {
    font-size: 0.95rem;
    color: #475569;
    line-height: 1.6;
}
.product-detail-description__body p:last-child { margin-bottom: 0; }
.product-detail-qty {
    width: 90px;
    text-align: center;
    border-radius: 10px;
    font-weight: 600;
}
.product-detail-total { font-size: 1.25rem; color: #0F0673; }
.product-detail-share-links ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.product-detail-share-links ul li { display: inline-block; }
.product-detail-share-links a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #f1f5f9;
    color: #475569;
    font-size: 1.1rem;
    transition: background 0.2s, color 0.2s;
}
.product-detail-share-links a:hover { background: #e2e8f0; color: #0F0673; }
.product-detail-disclaimer .card { border-radius: 10px; }
.product-detail-btn-buy {
    background: linear-gradient(135deg, #0F0673 0%, #3219E3 50%, #B00BD9 100%);
    color: #fff;
    font-weight: 600;
    border: none;
    padding: 0.85rem 1.5rem;
    transition: opacity 0.2s, transform 0.2s;
}
.product-detail-btn-buy:hover {
    color: #fff;
    opacity: 0.95;
    transform: translateY(-1px);
}
.product-detail-related-img {
    height: 120px;
    object-fit: contain;
    background: #f8fafc;
    padding: 0.5rem;
}
.product-detail-related-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08) !important; }
</style>
@endpush

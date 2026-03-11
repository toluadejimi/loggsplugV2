@extends($activeTemplate . 'layouts.main')
@section('content')

    @if ($errors->any())
        <div class="alert alert-danger my-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger mt-2">
            {{ session()->get('error') }}
        </div>
    @endif

    <!-- Products page slider - set image & URL in Admin → Frontend → Manage Section → Products Page Slider -->
    <div class="m-b1 products-slider-wrap">
        <div class="swiper-btn-center-lr position-relative">
            <button type="button" class="swiper-btn-prev"><i class="las la-arrow-left"></i></button>
            <button type="button" class="swiper-btn-next"><i class="las la-arrow-right"></i></button>
            <div class="swiper-container tag-group mt-4 products-slider-swiper">
                <div class="swiper-wrapper">
                    @foreach($productSliders as $slide)
                        @php
                            $data = $slide->data_values;
                            $imgFile = isset($data->image) ? trim((string) $data->image) : '';
                            $useDefault = $imgFile !== '' && in_array($imgFile, productSliderDefaultImages(), true);
                            if ($useDefault) {
                                $imgSrc = url('') . '/assets/assets2/images/slider/' . $imgFile;
                            } else {
                                $imgSrc = getImage('assets/images/frontend/products_slider/' . $imgFile, '800x260');
                            }
                            $slideUrl = $data->url ?? '#';
                            $external = $slideUrl && (str_starts_with((string)$slideUrl, 'http') || str_starts_with((string)$slideUrl, '//'));
                        @endphp
                        <div class="swiper-slide">
                            <a href="{{ $slideUrl }}" @if($external) target="_blank" rel="noopener" @endif>
                                <div class="card products-slider-card">
                                    <img src="{{ $imgSrc }}" alt="{{ $data->title ?? 'wallet-image' }}" class="products-slider-img">
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="swiper-pagination products-slider-pagination"></div>
        </div>
    </div>

    <!-- Recent -->
    <div class="dashboard-body__content">

        <!-- welcome + category filter -->
        <div class="products-page-header mt-2 mb-4">
            <div class="row align-items-center g-3">
                <div class="col-lg-8 col-12">
                    <h2 class="products-greeting mb-0">
                        Hi{{ Auth::check() && Auth::user()->username ? ', ' . Auth::user()->username : '' }} 👋
                    </h2>
                    <p class="products-subtext text-muted mb-0 mt-1 small">Browse categories or explore products below.</p>
                </div>
                <div class="col-lg-4 col-12 d-flex justify-content-lg-end">
                    <div class="products-category-select-wrap">
                        <select id="urlSelect" onchange="redirectToUrl()" class="products-category-select">
                            <option value="">All categories</option>
                            @foreach($categoriesdrop as $data)
                                <option value="{{ url('') }}/category-products/{{ $data->name }}/{{ $data->id }}">{{ $data->name }}</option>
                            @endforeach
                        </select>
                        <i class="las la-chevron-down products-category-select-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function redirectToUrl() {
                var selectElement = document.getElementById("urlSelect");
                var selectedUrl = selectElement.options[selectElement.selectedIndex].value;
                if (selectedUrl !== "") window.location.href = selectedUrl;
            }
        </script>

        <div class="dashboard-body__item-wrapper">

            <div class="products-content">

                @auth
                <div class="col-12 mb-4">
                    <div class="products-recent-card card border-0 shadow-sm overflow-hidden">
                        <div class="card-header products-recent-header border-0 py-3">
                            <h6 class="mb-0 d-flex align-items-center gap-2">
                                <i class="las la-history"></i> Recent orders
                            </h6>
                        </div>
                        <div class="products-recent-body card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover products-recent-table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-muted fw-500 small">Item</th>
                                            <th class="text-muted fw-500 small text-end">Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($bought_qty == 0)
                                            <tr><td colspan="2" class="text-muted text-center py-4 small">No recent orders yet.</td></tr>
                                        @else
                                            @foreach($bought as $data)
                                                <tr class="products-recent-row">
                                                    <td class="products-recent-cell">
                                                        <div class="products-recent-who">
                                                            <span class="products-recent-username">{{ \Illuminate\Support\Str::limit($data->user_name, 4, '.') }}</span>
                                                            <span class="products-recent-badge">just purchased</span>
                                                        </div>
                                                        <div class="products-recent-item">{{ \Illuminate\Support\Str::limit($data->item, 24, '...') }}</div>
                                                        <div class="products-recent-pills d-flex flex-wrap gap-2 mt-1">
                                                            <span class="products-recent-pill">₦{{ number_format($data->amount) }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="products-recent-time text-muted small text-end">{{ diffForHumans($data->created_at) }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endauth

                <div class="products-explore-head mb-3">
                    <h5 class="mb-0 fw-600">Explore products</h5>
                    <p class="text-muted small mb-0 mt-1">Choose a category to see more.</p>
                </div>





                <div class="col-12">
                    <div id="category-wrapper">
                        @include($activeTemplate . 'partials.category_loop')
                    </div>

                </div>

                <div class="text-center py-4" id="loading" style="display: none;">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="text-muted small mt-2 mb-0">Loading more products…</p>
                </div>

            </div>



        </div>











    </div>



    <div id="flash-buy-box" class="products-flash-buy" style="display: none;">
        <i class="las la-shopping-cart products-flash-buy__icon"></i>
        <span id="flash-buy-text" class="products-flash-buy__text"></span>
    </div>


    <script>
        let nextPage = "{{ $categories->nextPageUrl() }}";
        let loading = false;

        window.onscroll = function () {
            if (loading || !nextPage) return;

            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 500) {
                loading = true;
                document.getElementById('loading').style.display = 'block';

                fetch(nextPage, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        const wrapper = document.getElementById('category-wrapper');
                        wrapper.insertAdjacentHTML('beforeend', data.html);
                        nextPage = data.next_page;
                        loading = false;
                        document.getElementById('loading').style.display = 'none';
                    })
                    .catch(error => {
                        console.error('Error loading more:', error);
                        loading = false;
                        document.getElementById('loading').style.display = 'none';
                    });
            }
        };
    </script>

    <script>
        const messages = [
            @foreach($bought as $purchase)
                "{{ Str::limit($purchase->user_name, 4, '***') }} just bought {{ Str::limit($purchase->item, 16, '...') }} for ₦{{ number_format($purchase->amount) }}",
            @endforeach
        ];

        let index = 0;
        const flashBox = document.getElementById('flash-buy-box');
        const flashText = document.getElementById('flash-buy-text');

        if (messages.length > 0) {
            flashBox.style.display = 'block';

            setInterval(() => {
                flashText.innerText = messages[index];
                flashBox.style.opacity = 1;

                setTimeout(() => {
                    flashBox.style.opacity = 0;
                }, 4000);

                index = (index + 1) % messages.length;
            }, 5000);
        }
    </script>

@endsection

@push('style')
<style>
/* Products page slider */
.products-slider-wrap .products-slider-swiper { min-height: 200px; overflow: hidden; }
.products-slider-wrap .swiper-slide { height: auto; }
.products-slider-card {
    border-radius: 12px;
    overflow: hidden;
    margin: 0;
    border: 1px solid rgba(0,0,0,.06);
}
.products-slider-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
}
/* Mobile: 1 slide, taller image for better visibility */
@media (max-width: 767px) {
    .products-slider-wrap .products-slider-swiper { min-height: 150px; }
    .products-slider-wrap .products-slider-swiper .swiper-slide { width: 100% !important; }
    .products-slider-wrap .products-slider-card { margin: 0; }
    .products-slider-img {
        height: 150px;
        object-fit: fill;
    }
}
@media (min-width: 768px) { .products-slider-img { height: 260px; } }
.products-slider-wrap .swiper-btn-center-lr .swiper-btn-prev,
.products-slider-wrap .swiper-btn-center-lr .swiper-btn-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(0,0,0,.5);
    color: #fff;
    border: none;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    cursor: pointer;
    transition: background .2s;
}
.products-slider-wrap .swiper-btn-center-lr .swiper-btn-prev:hover,
.products-slider-wrap .swiper-btn-center-lr .swiper-btn-next:hover {
    background: rgba(0,0,0,.75);
    color: #fff;
}
.products-slider-wrap .swiper-btn-prev { left: 8px; }
.products-slider-wrap .swiper-btn-next { right: 8px; }
.products-slider-pagination { position: relative; margin-top: 12px; text-align: center; }
.products-slider-wrap .swiper-pagination-bullet {
    width: 8px;
    height: 8px;
    background: #cbd5e1;
    opacity: 1;
}
.products-slider-wrap .swiper-pagination-bullet-active {
    background: #6366f1;
    transform: scale(1.2);
}

/* Products page header & greeting */
.products-page-header { padding: 0 0 0.5rem; }
.products-greeting {
    font-size: 1.2rem;
    font-weight: 700;
    background: linear-gradient(135deg, #a78bfa 0%, #c4b5fd 40%, #e879f9 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    filter: drop-shadow(0 0 6px rgba(255,255,255,0.4)) drop-shadow(0 1px 2px rgba(0,0,0,0.2));
}
.products-subtext { font-size: 0.8rem; }
.products-category-select-wrap {
    position: relative;
    display: inline-block;
    min-width: 200px;
}
.products-category-select {
    width: 100%;
    padding: 0.5rem 2.25rem 0.5rem 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 500;
    color: #334155;
    background: #fff;
    appearance: none;
    cursor: pointer;
    transition: border-color .2s, box-shadow .2s;
}
.products-category-select:hover,
.products-category-select:focus {
    border-color: #6366f1;
    outline: none;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
}
.products-category-select-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: #64748b;
    font-size: 1rem;
}

/* Recent orders card */
.products-recent-card { border-radius: 12px; }
.products-recent-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    font-weight: 600;
    font-size: 0.9rem;
    color: #334155;
}
.products-recent-body { max-height: 340px; overflow-y: auto; }
.products-recent-table thead { background: #f1f5f9; }
.products-recent-table th {
    padding: 0.65rem 1rem;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #475569;
    font-weight: 600;
}
.products-recent-row { border-bottom: 1px solid #f1f5f9; }
.products-recent-row:last-child { border-bottom: none; }
.products-recent-cell { padding: 0.85rem 1rem !important; vertical-align: top !important; }
.products-recent-time { padding: 0.85rem 1rem !important; vertical-align: top !important; white-space: nowrap; }
.products-recent-who {
    font-size: 0.85rem;
    margin-bottom: 0.2rem;
}
.products-recent-username {
    font-weight: 700;
    color: #1e293b;
}
.products-recent-badge {
    font-size: 0.75rem;
    color: #6366f1;
    font-weight: 600;
    margin-left: 0.25rem;
}
.products-recent-item {
    font-size: 0.85rem;
    color: #475569;
    line-height: 1.4;
}
.products-recent-pills { display: flex; flex-wrap: wrap; gap: 0.4rem; }
.products-recent-pill {
    display: inline-block;
    padding: 0.25rem 0.65rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: #fff;
    background: #1e293b;
    border-radius: 9999px;
}
.products-recent-pill--stock {
    background: #0f172a;
}

/* Explore section */
.products-explore-head { padding: 0.25rem 0; }
.products-explore-head h5 { font-size: 1rem; }
.products-explore-head .text-muted { font-size: 0.8rem; }

/* Product cards */
.product-card {
    border-radius: 12px;
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}
.product-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08) !important;
}
.product-card__img {
    width: 56px;
    height: 56px;
    object-fit: cover;
    display: block;
}
.product-card__img-link:hover .product-card__img { opacity: 0.9; }
.product-card__title {
    font-size: 0.875rem;
    font-weight: 600;
    line-height: 1.35;
}
.product-card__title:hover { color: #3219E3 !important; }
.product-card__meta { font-size: 0.8rem; gap: 0.4rem; }
.product-card__pill {
    display: inline-block;
    padding: 0.25rem 0.65rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: #fff;
    background: #1e293b;
    border-radius: 9999px;
}
.product-card__pill--stock {
    background: #0f172a;
}
.product-card__btn {
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
}
.product-card__btn--lock {
    font-size: 0.75rem;
    background: #1e293b !important;
    color: #fff !important;
    border: 1px solid #1e293b !important;
}
.product-card__btn--lock:hover {
    background: #0f172a !important;
    color: #fff !important;
    border-color: #0f172a !important;
}

/* Category blocks */
.category-block__header { padding: 0 0.15rem; }
.category-block__title {
    font-size: 0.8rem;
    font-weight: 700;
    color: #1e293b;
    padding: 0.35rem 0.8rem;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    border-radius: 10px;
    display: inline-block;
}
.category-block__view-all {
    font-size: 0.8rem;
    font-weight: 600;
    color: #3219E3;
}
.category-block__view-all:hover {
    color: #0F0673 !important;
}

/* Flash buy toast - edge-to-edge, frosted glass, more text visible */
.products-flash-buy {
    position: fixed;
    bottom: 72px;
    left: 0;
    right: 0;
    width: 100%;
    z-index: 9998;
    background: rgba(15, 6, 115, 0.75);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    color: #fff;
    padding: 8px 12px;
    border-radius: 10px;
    border: 1px solid rgba(255, 255, 255, 0.12);
    text-align: left;
    font-size: 0.75rem;
    font-weight: 500;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.05) inset;
    transition: opacity 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 8px;
}
.products-flash-buy__icon {
    flex-shrink: 0;
    font-size: 0.95rem;
    opacity: 0.95;
}
.products-flash-buy__text {
    min-width: 0;
    flex: 1;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    line-clamp: 2;
}

/* Mobile: smaller fonts on product list, cart-only (no View text) */
@media (max-width: 767px) {
    .products-greeting { font-size: 1rem; }
    .products-subtext { font-size: 0.75rem; }
    .products-explore-head h5 { font-size: 0.9rem; }
    .products-explore-head .text-muted { font-size: 0.75rem; }
    .category-block__title { font-size: 0.75rem; padding: 0.3rem 0.65rem; }
    .category-block__view-all { font-size: 0.72rem; }
    .product-card .card-body { padding: 0.5rem 0.75rem !important; }
    .product-card__img { width: 44px; height: 44px; }
    .product-card__title { font-size: 0.75rem; line-height: 1.3; }
    .product-card__meta { font-size: 0.7rem; gap: 0.3rem; }
    .product-card__pill { font-size: 0.65rem; padding: 0.2rem 0.5rem; }
    .product-card__btn { font-size: 0.7rem; padding: 0.35rem 0.5rem !important; }
    .product-card__btn--lock { font-size: 0.7rem; padding: 0.35rem 0.5rem !important; }
    .product-card__btn-text { display: none !important; }
    .product-card__btn .las { margin: 0; }
    .products-recent-header { font-size: 0.8rem; }
    .products-recent-who { font-size: 0.78rem; }
    .products-recent-badge { font-size: 0.7rem; }
    .products-recent-item { font-size: 0.78rem; }
    .products-recent-pill { font-size: 0.7rem; }
    .products-recent-time { font-size: 0.75rem !important; }
}
</style>
@endpush

@push('script')
<script>
(function() {
    if (typeof Swiper === 'undefined' || !document.querySelector('.products-slider-swiper')) return;
    new Swiper('.products-slider-swiper', {
        speed: 500,
        slidesPerView: 1,
        spaceBetween: 0,
        centeredSlides: true,
        loop: false,
        autoplay: { delay: 4000, disableOnInteraction: false },
        navigation: {
            nextEl: '.products-slider-wrap .swiper-btn-next',
            prevEl: '.products-slider-wrap .swiper-btn-prev'
        },
        pagination: {
            el: '.products-slider-wrap .products-slider-pagination',
            clickable: true
        },
        breakpoints: {
            768: {
                slidesPerView: 1.5,
                spaceBetween: 10
            }
        }
    });
})();
</script>
@endpush

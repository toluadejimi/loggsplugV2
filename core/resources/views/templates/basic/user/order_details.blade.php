@extends($activeTemplate.'layouts.main')

@section('content')
    <div class="container py-4">

        {{-- Header Section --}}
        <div class="row mb-4">
            <div class="col-lg-8 col-md-9">
{{--                <div class="card border-0 shadow-sm mb-3">--}}
{{--                    <div class="card-body">--}}
{{--                        <h6 class="fw-bold text-muted mb-1">@lang('Category')</h6>--}}
{{--                        <p class="mb-0">{{ @$orderItems->first()->product->category->name ?? 'N/A' }}</p>--}}
{{--                    </div>--}}
{{--                </div>--}}

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold text-muted mb-1">@lang('Product')</h6>
                        <p class="mb-0">{{ @$orderItems->first()->product->name ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-3 d-flex align-items-end justify-content-end my-3">
                <a href="/user/copy/{{$get_id}}" class="btn btn-primary btn-sm rounded-pill px-4">
                    <i style="color: white" class="fa fa-copy me-2"></i>@lang('Copy All')
                </a>
            </div>
        </div>

        {{-- Order History --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="fw-bold mb-4 text-center text-uppercase">@lang('Latest Order History')</h5>

                @if($orderItems->count())
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>@lang('Product Details')</th>
                                <th class="text-center">@lang('Copy')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orderItems as $item)
                                <tr>
                                    <td>
                                        <input type="text"
                                               readonly
                                               class="form-control border-0 bg-light small copy-input"
                                               value="{{ strip_tags($item->productDetail->details ?? '') }}"
                                               id="copyInput{{ $item->productDetail->id }}">
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-outline-secondary btn-sm rounded-circle copy-btn"
                                                data-target="copyInput{{ $item->productDetail->id }}">
                                            <i style="color: black" class="fa fa-copy"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ paginateLinks($orderItems) }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" width="60" class="mb-3" alt="No data">
                        <h6 class="fw-semibold">@lang('No order data found')</h6>
                        <p class="text-muted small">@lang('You have no recent orders yet.')</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Copy to Clipboard Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('.copy-btn');
            buttons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const inputId = this.getAttribute('data-target');
                    const input = document.getElementById(inputId);
                    input.select();
                    input.setSelectionRange(0, 99999);
                    document.execCommand('copy');
                    input.blur();

                    // Show toast message
                    const alert = document.createElement('div');
                    alert.textContent = "Copied!";
                    alert.className = "copy-toast";
                    document.body.appendChild(alert);
                    setTimeout(() => alert.remove(), 2000);
                });
            });
        });
    </script>

    {{-- Copy Toast Styling --}}
    <style>
        .copy-toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #28a745;
            color: #fff;
            padding: 10px 18px;
            border-radius: 50px;
            font-size: 14px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            animation: fadeInOut 2s ease;
            z-index: 9999;
        }
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(20px); }
            10%, 90% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(20px); }
        }
    </style>
@endsection

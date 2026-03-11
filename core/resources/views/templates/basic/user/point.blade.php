@extends($activeTemplate.'layouts.main')
@section('content')
    <div class="container">
        <div class="row gy-4 my-5">
            <div class="col-xl-12 col-sm-12">
                <div class="dashboard-widget">
                    <form action="{{ route('user.deposit.insert') }}" method="POST">
                        @csrf

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

                        <h6 class="mt-3 p-3">Pay Here</h6>
                        <p class="mt-3 p-3">Pay to the Account details below once payment is received your wallet will be funded</p>


                        <div class="p-3">
                            <div class="card-body">
                                <h6>Amount</h6>
                                <p class="point-copy-row d-flex align-items-center gap-2 flex-wrap">
                                    <span class="point-copy-value" id="point-amount">NGN {{ number_format($amount ?? 0, 2) }}</span>
                                    <button type="button" class="point-copy-btn btn btn-sm btn-outline-secondary" data-copy-target="point-amount" title="Copy amount" aria-label="Copy amount">
                                        <i class="las la-copy"></i>
                                    </button>
                                </p>
                            </div>
                        </div>

                        <div class="p-3">
                            <div class="card-body">
                                <h6>Bank Account</h6>
                                <p>{{$bank_name ?? "Not Available"}}</p>
                            </div>
                        </div>

                        <!-- Gateway -->
                        <div class="p-3">
                            <div class="card-body">
                                <h6 class="mb-2">Account Name</h6>
                                <p>{{$account_name ?? "Not Available"}}</p>

                            </div>
                        </div>

                        <div class="p-3">
                            <div class="card-body">
                                <h6 class="mb-2">Account No</h6>
                                <p class="point-copy-row d-flex align-items-center gap-2 flex-wrap">
                                    <span class="point-copy-value" id="point-account-no">{{ $account_no ?? "Not Available" }}</span>
                                    <button type="button" class="point-copy-btn btn btn-sm btn-outline-secondary" data-copy-target="point-account-no" title="Copy account number" aria-label="Copy account number">
                                        <i class="las la-copy"></i>
                                    </button>
                                </p>
                            </div>
                        </div>

                        <!-- Extra fields (hidden by default) -->


                        <div class="p-3">

                            <a href="/products" type="button"
                                    style="background: linear-gradient(90deg, #0F0673 0%, #B00BD9 100%); color:#ffffff;"
                                    class="btn btn-main btn-lg w-100 pill p-3" id="btn-confirm">@lang('Home')
                            </a>

                        </div>



                    </form>

                </div>
            </div>


        </div>

@endsection

@push('script')
<script>
(function() {
    document.querySelectorAll('.point-copy-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.getAttribute('data-copy-target');
            var el = document.getElementById(id);
            if (!el) return;
            var text = (el.textContent || el.innerText || '').trim();
            if (!text) return;
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(function() {
                    var icon = btn.querySelector('i');
                    var cls = icon.className;
                    icon.className = 'las la-check';
                    btn.setAttribute('title', 'Copied!');
                    setTimeout(function() {
                        icon.className = cls;
                        btn.setAttribute('title', id === 'point-amount' ? 'Copy amount' : 'Copy account number');
                    }, 1500);
                });
            } else {
                var ta = document.createElement('textarea');
                ta.value = text;
                ta.setAttribute('readonly', '');
                ta.style.position = 'fixed';
                ta.style.opacity = '0';
                document.body.appendChild(ta);
                ta.select();
                try {
                    document.execCommand('copy');
                    var icon = btn.querySelector('i');
                    var cls = icon.className;
                    icon.className = 'las la-check';
                    btn.setAttribute('title', 'Copied!');
                    setTimeout(function() {
                        icon.className = cls;
                        btn.setAttribute('title', id === 'point-amount' ? 'Copy amount' : 'Copy account number');
                    }, 1500);
                } catch (e) {}
                document.body.removeChild(ta);
            }
        });
    });
})();
</script>
@endpush

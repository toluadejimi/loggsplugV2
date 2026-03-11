@extends($activeTemplate . 'layouts.main')
@section('content')

    <div class="dashboard-body__content deposit-page">

        <div class="deposit-page-header mb-4">
            <div class="deposit-page-header-icon mb-2">
                <i class="las la-wallet"></i>
            </div>
            <h1 class="deposit-page-title mb-2">Fund wallet</h1>
            <p class="deposit-page-subtitle mb-0">Top up your wallet using bank transfer or card.</p>
        </div>

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

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card deposit-form-card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <a href="https://t.me/loggsplug/32" target="_blank" rel="noopener" class="deposit-help-link d-inline-flex align-items-center gap-2 text-decoration-none mb-4">
                            <i class="las la-external-link-alt"></i>
                            <span>How to fund your wallet</span>
                        </a>

                        <form action="{{ route('user.deposit.insert') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="amount" class="deposit-label">Amount (NGN)</label>
                                <input type="number"
                                       name="amount"
                                       id="amount"
                                       class="form-control form-control-lg deposit-amount-input"
                                       placeholder="e.g. 5000"
                                       min="2000"
                                       step="1"
                                       required>
                                <p class="deposit-hint mt-1 mb-0">Minimum amount: NGN 2,000</p>
                            </div>

                            <div class="mb-4">
                                <label for="gateway-select" class="deposit-label">Payment gateway</label>
                                <select class="form-select form-select-lg deposit-gateway-select" id="gateway-select" name="gateway" required>
                                    <option value="">Choose payment gateway</option>
                                    @foreach ($gateway_currency as $data)
                                        <option value="{{ $data->method_code }}">{{ $data->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="deposit-extra-fields d-none mb-4" id="extra-fields">
                                <h6 class="deposit-extra-title mb-3">Details required</h6>
                                @if (auth()->user()->name == null)
                                    <div class="mb-3">
                                        <input type="text" name="name" class="form-control deposit-input" placeholder="Your full name">
                                    </div>
                                @endif
                                @if (auth()->user()->phone == null)
                                    <div class="mb-3">
                                        <input type="text" name="phone" class="form-control deposit-input" placeholder="Phone number">
                                    </div>
                                @endif
                            </div>

                            <button type="submit" class="btn deposit-submit-btn btn-lg w-100 rounded-pill py-3" id="btn-confirm">
                                <i class="las la-arrow-right me-2"></i>
                                Continue
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card deposit-history-card border-0 shadow-sm">
                    <div class="card-header deposit-history-header border-0">
                        <h5 class="deposit-history-title mb-0">
                            <i class="las la-history"></i>
                            Payment History
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table deposit-history-table mb-0">
                                <thead>
                                    <tr>
                                        <th class="deposit-th">Date</th>
                                        <th class="deposit-th">Type</th>
                                        <th class="deposit-th">Amount</th>
                                        <th class="deposit-th deposit-th--end">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($deposits as $deposit)
                                        <tr>
                                            <td class="deposit-td">{{ showDateTime($deposit->created_at, 'M d, Y') }}</td>
                                            <td class="deposit-td">
                                                @if($deposit->method_code == 1000)
                                                    Manual
                                                @elseif($deposit->method_code == 250)
                                                    SprintPay
                                                @elseif($deposit->method_code == 251)
                                                    Payment Point
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="deposit-td deposit-td--amount">NGN {{ number_format($deposit->amount, 2) }}</td>
                                            <td class="deposit-td deposit-td--end">
                                                @if($deposit->status == 1)
                                                    <span class="deposit-status deposit-status--success">Completed</span>
                                                @elseif($deposit->status == 2)
                                                    <span class="deposit-status deposit-status--pending">Pending</span>
                                                @elseif($deposit->status == 3)
                                                    <span class="deposit-status deposit-status--rejected">Rejected</span>
                                                @else
                                                    <span class="deposit-status deposit-status--pending">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="deposit-empty text-center py-5">No payment history yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($deposits->hasPages())
                            <div class="deposit-history-pagination p-3 border-top">
                                {{ paginateLinks($deposits) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('gateway-select').addEventListener('change', function () {
            var selected = this.value;
            @if(auth()->user()->name == null || auth()->user()->phone == null)
            if (selected == 251) {
                document.getElementById('extra-fields').classList.remove('d-none');
            } else {
                document.getElementById('extra-fields').classList.add('d-none');
            }
            @endif
        });
    </script>
@endsection

@push('style')
<style>
/* Page container */
.deposit-page { padding-bottom: 2rem; }

/* Header – high contrast */
.deposit-page-header { text-align: center; }
.deposit-page-header-icon {
    width: 56px;
    height: 56px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #0F0673 0%, #3219E3 100%);
    color: #fff;
    font-size: 1.75rem;
    border-radius: 14px;
}
.deposit-page-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0f172a;
}
.deposit-page-subtitle {
    font-size: 0.95rem;
    color: #475569;
}

/* Cards – solid background so text is always readable */
.deposit-form-card,
.deposit-history-card {
    border-radius: 14px;
    background: #ffffff;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}
.deposit-history-card .card-body { background: #fff; }

/* Help link – visible and clickable */
.deposit-help-link {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background: #f1f5f9;
    color: #0F0673 !important;
    font-size: 0.9rem;
    font-weight: 600;
    border-radius: 10px;
    transition: background 0.2s, color 0.2s;
}
.deposit-help-link:hover {
    background: #e2e8f0;
    color: #0f172a !important;
}

/* Form labels – dark, readable */
.deposit-label {
    display: block;
    font-size: 0.95rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
}
.deposit-hint {
    font-size: 0.85rem;
    color: #64748b;
}
.deposit-extra-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: #1e293b;
}

/* Inputs – clear border and text */
.deposit-amount-input,
.deposit-gateway-select,
.deposit-input {
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    color: #1e293b;
    font-size: 1rem;
}
.deposit-amount-input::placeholder,
.deposit-input::placeholder { color: #94a3b8; }
.deposit-amount-input:focus,
.deposit-gateway-select:focus,
.deposit-input:focus {
    border-color: #3219E3;
    color: #1e293b;
    box-shadow: 0 0 0 3px rgba(49, 25, 227, 0.15);
}
.deposit-gateway-select option { color: #1e293b; }

/* Submit button */
.deposit-submit-btn {
    background: linear-gradient(135deg, #0F0673 0%, #3219E3 50%, #B00BD9 100%);
    color: #fff !important;
    font-weight: 600;
    font-size: 1rem;
    border: none;
    transition: opacity 0.2s, transform 0.2s;
}
.deposit-submit-btn:hover { color: #fff !important; opacity: 0.95; transform: translateY(-1px); }

/* History card header */
.deposit-history-header {
    padding: 1rem 1.25rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}
.deposit-history-title {
    font-size: 1.05rem;
    font-weight: 600;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.deposit-history-title i { color: #64748b; }

/* Table – readable text */
.deposit-th {
    padding: 0.75rem 1rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #475569;
    background: #f8fafc;
}
.deposit-th--end { text-align: right; }
.deposit-td {
    padding: 0.85rem 1rem;
    font-size: 0.9rem;
    color: #1e293b;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    background: #fff;
}
.deposit-td--amount { font-weight: 600; color: #0f172a; }
.deposit-td--end { text-align: right; }
.deposit-empty {
    font-size: 0.9rem;
    color: #64748b;
    background: #fff;
}

/* Status pills – high visibility */
.deposit-status {
    display: inline-block;
    padding: 0.35rem 0.75rem;
    font-size: 0.85rem;
    font-weight: 700;
    border-radius: 9999px;
    color: #fff !important;
    text-align: center;
    white-space: nowrap;
}
.deposit-status--success {
    background: #15803d !important;
    color: #fff !important;
}
.deposit-status--pending {
    background: #b45309 !important;
    color: #fff !important;
}
.deposit-status--rejected {
    background: #b91c1c !important;
    color: #fff !important;
}

.deposit-history-pagination { background: #fff; }
</style>
@endpush

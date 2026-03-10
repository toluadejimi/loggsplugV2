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
                                <p>NGN {{number_format($amount, 2 ?? 0.0)}}</p>
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
                                <p>{{$account_no ?? "Not Available"}}</p>
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

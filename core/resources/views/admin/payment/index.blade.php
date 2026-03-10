@extends('admin.layouts.app')
@section('panel')

    <div class="row mb-none-30">


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
            <div class="alert alert-success my-3">
                {{ session()->get('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger mt-2">
                {{ session()->get('error') }}
            </div>
        @endif


            <div class="col-lg-6 col-md-9 mb-30">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-3">List of Payment Gateways</h6>

                        @foreach($gateway as $data)
                            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                <span class="fw-semibold">{{ $data->name }}</span>

                                <div class="form-check form-switch">
                                    <input
                                        class="form-check-input gateway-toggle"
                                        type="checkbox"
                                        role="switch"
                                        id="gateway-{{ $data->id }}"
                                        data-id="{{ $data->id }}"
                                        {{ $data->status ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label ms-2" for="gateway-{{ $data->id }}">
                            <span id="label-{{ $data->id }}">
                                {{ $data->status ? 'Active' : 'Inactive' }}
                            </span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <script>
                document.querySelectorAll('.gateway-toggle').forEach(toggle => {
                    toggle.addEventListener('change', function() {
                        let id = this.dataset.id;
                        let isActive = this.checked ? 1 : 0;

                        fetch(`payment-gateway/${id}/toggle`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({ status: isActive })
                        })
                            .then(res => res.json())
                            .then(data => {
                                document.getElementById(`label-${id}`).innerText = data.status ? "Active" : "Inactive";
                            });
                    });
                });
            </script>



    </div>


    <script>
        document.getElementById('status-toggle').addEventListener('change', function() {
            let isActive = this.checked ? 1 : 0;

            fetch("/", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ status: isActive })
            })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('status-label').innerText = data.status ? "Active" : "Inactive";
                });
        });
    </script>

@endsection

{{--@push('breadcrumb-plugins')--}}
{{--    <a href="{{route('admin.profile')}}" class="btn btn-sm btn-outline--primary"><i--}}
{{--            class="las la-user"></i>@lang('Profile Setting')</a>--}}
{{--@endpush--}}

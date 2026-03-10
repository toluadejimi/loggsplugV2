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
    <div class="m-b1">
        <div class="swiper-btn-center-lr">
            <div class="swiper-container tag-group mt-4 demo-swiper">
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
                                <div class="card">
                                    <img src="{{ $imgSrc }}" alt="{{ $data->title ?? 'wallet-image' }}">
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recent -->
    <div class="dashboard-body__content">

        <!-- welcome balance Content Start -->
        <div class="welcome-balance mt-2 mb-5">

            <div class="row">

                <div class="col-xl-9 col-sm-12 d-flex justify-content-start ">

                    <h4 class="mb-0"
                        style=" background: linear-gradient(270deg, #D0CCA1 9.16%, #DD553E 42.99%, #3219E3 87.83%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; text-fill-color: transparent;">
                        HI {{Auth::user()->username ?? ""}},</h4>

                </div>

                <div class="col-xl-3 col-sm-12 d-flex justify-content-end">
                    <select id="urlSelect" onchange="redirectToUrl()" class="btn btn-sm btn-dark">
                        <option value="">Categories</option>
                        @foreach($categoriesdrop as $data)
                            <option value="{{url('')}}/category-products/{{$data->name}}/{{$data->id}}">{{$data->name}}
                            </option>
                        @endforeach
                    </select>

                    <script>
                        function redirectToUrl() {
                            var selectElement = document.getElementById("urlSelect");
                            var selectedUrl = selectElement.options[selectElement.selectedIndex].value;
                            if (selectedUrl !== "") {
                                window.location.href = selectedUrl;
                            }
                        }
                    </script>

                </div>

            </div>


        </div>
        <!-- welcome balance Content End -->

        <div class="dashboard-body__item-wrapper">

            <div class="">


                <div class="col-12 mb-5 my-4">
                    @auth

                        <div class="card-title mt-3 text-center">
                            <h6 style="background: #565656; padding: 10px; border-radius: 10px; color: white"
                                class="text-left">RECENT ORDER</h6>
                        </div>


                        <div style="height:400px; width:100%; overflow-y: scroll;" class="card">
                            <div class="card-body">


                                <div class="dashboard-body__item">
                                    <div class="table-responsive">
                                        <table class="table style-two">
                                            <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Time</th>

                                            </tr>

                                            @if($bought_qty == 0)
                                            @else
                                                @foreach($bought as $data)

                                                    <tr>
                                                        <td>{{\Illuminate\Support\Str::limit($data->user_name,4, '.')}}, <span style="color: #f10054">just purchase</span><br/> {{\Illuminate\Support\Str::limit($data->item,
                                    16, '...')}}<span style="color: #000000">₦{{number_format($data->amount)}}</span></td>
                                                        <td>{{ diffForHumans($data->created_at) }}</td>
                                                    </tr>

                                                @endforeach
                                            @endif



                                            </thead>
                                        </table>
                                    </div>
                                </div>





                            </div>
                        </div>
                    @else

                    @endauth

                </div>




                <div>
                    <h5 class="d-flex justify-content-start">Explore Product 👌</h5>
                </div>





                <div class="col-12">
                    <div id="category-wrapper">
                        @include($activeTemplate . 'partials.category_loop')
                    </div>

                    <div class="text-center my-3" id="loading" style="display: none;">
                        <p>Please wait Loading more products...</p>
                    </div>
                </div>

                <div class="text-center my-3" id="loading" style="display: none;">
                    <p>Please wait Loading more Products...</p>
                </div>

            </div>



        </div>











    </div>



    <div id="flash-buy-box" style="position: fixed; bottom: 80px; left: 10px; right: 10px; z-index: 9999; background: rgba(5,67,159,0.8); color: white; padding: 10px; border-radius: 10px; display: none; text-align: center;">
        <span id="flash-buy-text"></span>
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

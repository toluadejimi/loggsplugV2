<div class="dashboard-body__item my-2">
    <div class="row gy-4">
        <div class="col-xl-12 col-sm-12">
            <div class="dashboard-widget">
                <div class="row">
                    <div class="col-2">
                        <a href="#" data-help="Click to read detailed description">
                            <img src="{{ url('') }}/assets/images/product/{{$product->image}}" height="50" width="50">
                        </a>
                    </div>


                    <div class="col-8" >
                        <p class="mb-1">@php $text = $product->name.' | '.strLimit(strip_tags($product->description), 20); @endphp
                            <a class="text-small text-dark" href="{{ route('product.details', $product->id) }}"
                               style="font-size: 14px;"
                               class="text-dark font-size">
                                @php echo $text; @endphp
                            </a>
                        </p>


                        <p class="mb-0 text-muted"><a class="text-white btn btn-dark btn-rounded btn-sm"
                                                      style="font-size: 12px; font-weight: bolder"> {{ $general->cur_sym }}{{ showAmount($product->price) }} </a>
                            | <a class="text-white btn btn-dark btn-rounded btn-sm"
                                 style="font-size: 12px; font-weight: bolder"> {{ $product->in_stock }} pcs </a></p>

                    </div>




                    <div class="col-2">

                        @if ($product->in_stock == 0)
                            <div>
                                {{--                            <button type="button" class="form-control" type="button" data-id="12005">--}}
                                {{--                                <ion-icon class="text-dark" name="bag-add"></ion-icon>--}}
                                {{--                            </button>--}}
                            </div>
                        @else
                            @auth


                                <div class="d-flex justify-content-center row">

                                    <div class="col-12 d-flex justify-content-center">
                                        <a href="/product/details/{{ $product->id }}"
                                           style="font-size: 10px"
                                           class="btn btn-sm">   <svg width="20" height="20" viewBox="0 0 94 91" fill="none"
                                                                               xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M86.7692 21.84H68.6923C68.6923 16.0477 66.4069 10.4926 62.3388 6.39679C58.2707 2.30099 52.7532 0 47 0C41.2468 0 35.7293 2.30099 31.6612 6.39679C27.5931 10.4926 25.3077 16.0477 25.3077 21.84H7.23077C5.31305 21.84 3.47388 22.607 2.11784 23.9723C0.761811 25.3375 0 27.1892 0 29.12V83.72C0 85.6508 0.761811 87.5025 2.11784 88.8677C3.47388 90.233 5.31305 91 7.23077 91H86.7692C88.6869 91 90.5261 90.233 91.8822 88.8677C93.2382 87.5025 94 85.6508 94 83.72V29.12C94 27.1892 93.2382 25.3375 91.8822 23.9723C90.5261 22.607 88.6869 21.84 86.7692 21.84ZM32.5385 40.04C32.5385 41.0054 32.1576 41.9312 31.4795 42.6139C30.8015 43.2965 29.8819 43.68 28.9231 43.68C27.9642 43.68 27.0446 43.2965 26.3666 42.6139C25.6886 41.9312 25.3077 41.0054 25.3077 40.04V32.76C25.3077 31.7946 25.6886 30.8688 26.3666 30.1861C27.0446 29.5035 27.9642 29.12 28.9231 29.12C29.8819 29.12 30.8015 29.5035 31.4795 30.1861C32.1576 30.8688 32.5385 31.7946 32.5385 32.76V40.04ZM47 7.28C50.8354 7.28 54.5138 8.814 57.2258 11.5445C59.9379 14.2751 61.4615 17.9784 61.4615 21.84H32.5385C32.5385 17.9784 34.0621 14.2751 36.7741 11.5445C39.4862 8.814 43.1646 7.28 47 7.28ZM68.6923 40.04C68.6923 41.0054 68.3114 41.9312 67.6334 42.6139C66.9554 43.2965 66.0358 43.68 65.0769 43.68C64.1181 43.68 63.1985 43.2965 62.5205 42.6139C61.8424 41.9312 61.4615 41.0054 61.4615 40.04V32.76C61.4615 31.7946 61.8424 30.8688 62.5205 30.1861C63.1985 29.5035 64.1181 29.12 65.0769 29.12C66.0358 29.12 66.9554 29.5035 67.6334 30.1861C68.3114 30.8688 68.6923 31.7946 68.6923 32.76V40.04Z"
                                                    fill="#0601B4"/>
                                            </svg>
                                        </a>
                                    </div>

                                </div>


                            @else

                                <div class="d-flex justify-content-center row">


                                    <div class="col-12 d-flex justify-content-center">
                                        <a href="/user/login"
                                           style="font-size: 8px"
                                           class="btn btn-sm btn-dark"> <i class="fa fa-lock"> </i></a>
                                    </div>


                                </div>


                            @endauth
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title> LOGS PLUG</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ url('') }}/assets/assets2/images/logo/favicon.png">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ url('') }}/assets/assets2/css/bootstrap.min.css">
    <!-- Fontawesome -->
    <link rel="stylesheet" href="{{ url('') }}/assets/assets2/css/fontawesome-all.min.css">
    <!-- Slick -->
    <link rel="stylesheet" href="{{ url('') }}/assets/assets2/css/slick.css">
    <!-- magnific popup -->
    <link rel="stylesheet" href="{{ url('') }}/assets/assets2/css/magnific-popup.css">
    <!-- line awesome -->
    <link rel="stylesheet" href="{{ url('') }}/assets/assets2/css/line-awesome.min.css">
    <!-- Main css -->
    <link rel="stylesheet" href="{{ url('') }}/assets/assets2/css/main.css">

    <script src="https://accounts.google.com/gsi/client" async defer></script>


</head>
<body>

<!--==================== Preloader Start ====================-->
<div class="loader-mask">
    <div class="loader">
        <div></div>
        <div></div>
    </div>
</div>
<!--==================== Preloader End ====================-->

<!--==================== Overlay Start ====================-->
<div class="overlay"></div>
<!--==================== Overlay End ====================-->

<!--==================== Sidebar Overlay End ====================-->
<div class="side-overlay"></div>
<!--==================== Sidebar Overlay End ====================-->

<!-- ==================== Scroll to Top End Here ==================== -->
<div class="progress-wrap">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
    </svg>
</div>
<!-- ==================== Scroll to Top End Here ==================== -->

<!-- ==================== Mobile Menu Start Here ==================== -->
<div class="mobile-menu d-lg-none d-block">
    <button type="button" class="close-button"> <i class="las la-times"></i> </button>
    <div class="mobile-menu__inner">
        <a href="/" class="mobile-menu__logo">
            <img src="{{ url('') }}/assets/assets2/images/slider/img.png" alt="Logo">
        </a>
        <div class="mobile-menu__menu">

            <ul class="nav-menu flx-align nav-menu--mobile">
                <li class="nav-menu__item has-submenu">
                    <a href="javascript:void(0)" class="nav-menu__link">Home</a>
                    <ul class="nav-submenu">
                        <li class="nav-submenu__item">
                            <a href="index.html" class="nav-submenu__link"> Home One</a>
                        </li>
                        <li class="nav-submenu__item">
                            <a href="index-two.html" class="nav-submenu__link"> Home Two</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-menu__item has-submenu">
                    <a href="javascript:void(0)" class="nav-menu__link">Products</a>
                    <ul class="nav-submenu">
                        <li class="nav-submenu__item">
                            <a href="all-product.html" class="nav-submenu__link"> All Products</a>
                        </li>
                        <li class="nav-submenu__item">
                            <a href="product-details.html" class="nav-submenu__link"> Product Details</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-menu__item has-submenu">
                    <a href="javascript:void(0)" class="nav-menu__link">Pages</a>
                    <ul class="nav-submenu">
                        <li class="nav-submenu__item">
                            <a href="profile.html" class="nav-submenu__link"> Profile</a>
                        </li>
                        <li class="nav-submenu__item">
                            <a href="cart.html" class="nav-submenu__link"> Shopping Cart</a>
                        </li>
                        <li class="nav-submenu__item">
                            <a href="cart-personal.html" class="nav-submenu__link"> Mailing Address</a>
                        </li>
                        <li class="nav-submenu__item">
                            <a href="cart-payment.html" class="nav-submenu__link"> Payment Method</a>
                        </li>
                        <li class="nav-submenu__item">
                            <a href="cart-thank-you.html" class="nav-submenu__link"> Preview Order</a>
                        </li>
                        <li class="nav-submenu__item">
                            <a href="dashboard.html" class="nav-submenu__link"> Dashboard</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-menu__item has-submenu">
                    <a href="javascript:void(0)" class="nav-menu__link">Blog</a>
                    <ul class="nav-submenu">
                        <li class="nav-submenu__item">
                            <a href="blog.html" class="nav-submenu__link"> Blog</a>
                        </li>
                        <li class="nav-submenu__item">
                            <a href="blog-details.html" class="nav-submenu__link"> Blog Details</a>
                        </li>
                        <li class="nav-submenu__item">
                            <a href="blog-details-sidebar.html" class="nav-submenu__link"> Blog Details Sidebar</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-menu__item">
                    <a href="contact.html" class="nav-menu__link">Contact</a>
                </li>
            </ul>
            <div class="header-right__inner d-lg-none my-3 gap-1 d-flex flx-align">

                <a href="register.html" class="btn btn-main pill">
        <span class="icon-left icon">
            <img src="{{ url('') }}/assets/assets2/images/icons/user.svg" alt="">
        </span>Create Account
                </a>
                <div class="language-select flx-align select-has-icon">
                    <img src="{{ url('') }}/assets/assets2/images/icons/globe.svg" alt="" class="globe-icon">
                    <select class="select py-0 ps-2 border-0 fw-500">
                        <option value="1">Eng</option>
                        <option value="2">Bn</option>
                        <option value="3">Eur</option>
                        <option value="4">Urd</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ==================== Mobile Menu End Here ==================== -->

<!-- ================================== Account Page Start =========================== -->
<section class="account d-flex">
    <img src="{{ url('') }}/assets/assets2/images/thumbs/account-img.png" alt="" class="account__img">
    <div class="account__left d-md-flex d-none flx-align section-bg position-relative z-index-1 overflow-hidden">
        <img src="{{ url('') }}/assets/assets2/images/shapes/pattern-curve-seven.png" alt="" class="position-absolute end-0 top-0 z-index--1 h-100">
        <div class="account-thumb">
            <img src="{{ url('') }}/assets/assets2/images/thumbs/barrr.png" alt="">
            <div class="statistics animation bg-main text-center">
                <h5 class="statistics__amount text-white">LOGS</h5>
                <span class="statistics__text text-white font-14">PLUG</span>
            </div>
        </div>
    </div>
    <div class="account__right padding-y-120 flx-align">
        <div class="account-content">
            <a href="/" class="logo mb-64">
                <img src="{{ url('') }}/assets/assets2/images/slider/img.png" alt="Logo">
            </a>
            <h4 class="account-content__title mb-48 text-capitalize">Welcome Back!</h4>


            <form method="POST" action="{{ route('user.login') }}" class="verify-gcaptcha">
                @csrf


                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session()->has('message'))
                    <div class="alert pill alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert pill alert-danger">
                        {{ session()->get('error') }}
                    </div>
                @endif


                <div class="row gy-4">
                    <div class="col-12">
                        <label for="email" class="form-label mb-2 font-18 font-heading fw-600">Email</label>
                        <div class="position-relative">
                            <input type="text" class="common-input common-input--bg common-input--withIcon" id="email" name="username" value="{{ old('username') }}" placeholder="infoname@mail.com">
                            <span class="input-icon"><img src="{{ url('') }}/assets/assets2/images/icons/envelope-icon.svg" alt=""></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="your-password" class="form-label mb-2 font-18 font-heading fw-600">Password</label>
                        <div class="position-relative">
                            <input type="password" name="password"  class="common-input common-input--bg common-input--withIcon" id="your-password" placeholder="6+ characters, 1 Capital letter">
                            <span class="input-icon toggle-password cursor-pointer" id="#your-password"><img src="{{ url('') }}/assets/assets2/images/icons/lock-icon.svg" alt=""></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="flx-between gap-1">
                            <div class="common-check my-2">
                                <input class="form-check-input" type="checkbox" name="checkbox" id="keepMe">
                                <label class="form-check-label mb-0 fw-400 font-14 text-body" for="keepMe">Keep me signed in</label>
                            </div>
                            <a href="{{ route('user.password.request') }}" class="forgot-password text-decoration-underline text-main text-poppins font-14">Forgot password?</a>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-main btn-lg w-100 pill"> Sign In</button>
                    </div>



                    <div id="g_id_onload"
                         data-client_id="1050407863306-8j292r68v5mn4ea9qjuqo89iv4j7i4h8.apps.googleusercontent.com"
                         data-context="signin"
                         data-ux_mode="popup"
                         data-login_uri="{{url('')}}/auth/google/callback"
                         data-auto_prompt="false">
                    </div>

                    <div class="g_id_signin"
                         data-type="standard"
                         data-shape="rectangular"
                         data-theme="outline"
                         data-text="sign_in_with"
                         data-size="large"
                         data-logo_alignment="left">
                    </div>



                    <div class="col-sm-12 mb-0">
                        <div class="have-account">
                            <p class="text font-14">New to the LOGS PLUG? <a class="link text-main text-decoration-underline fw-500" href="register">Register</a></p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- ================================== Account Page End =========================== -->

<!-- Jquery js -->
<script src="{{ url('') }}/assets/assets2/js/jquery-3.7.1.min.js"></script>
<!-- Bootstrap Bundle Js -->
<script src="{{ url('') }}/assets/assets2/js/boostrap.bundle.min.js"></script>
<!-- CountDown -->
<script src="{{ url('') }}/assets/assets2/js/countdown.js"></script>
<!-- counter up -->
<script src="{{ url('') }}/assets/assets2/js/counterup.min.js"></script>
<!-- Slick js -->
<script src="{{ url('') }}/assets/assets2/js/slick.min.js"></script>
<!-- magnific popup -->
<script src="{{ url('') }}/assets/assets2/js/jquery.magnific-popup.js"></script>
<!-- apex chart -->
<script src="{{ url('') }}/assets/assets2/js/apexchart.js"></script>

<!-- main js -->
<script src="{{ url('') }}/assets/assets2/js/main.js"></script>

</body>
</html>

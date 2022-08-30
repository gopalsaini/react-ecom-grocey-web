<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title') | Five Ferns</title>
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="title" content="@yield('title')" />
    <meta name="description" content="@yield('meta_description')" />
    <meta name="keywords" content="@yield('meta_keywords')" />
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fonts/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    @stack('custom_css')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fancybox.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fSelect.css') }}">

    <script src="https://kit.fontawesome.com/0b8334f960.js" crossorigin="anonymous"></script>
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-E6LG32TZG6"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-E6LG32TZG6');
    </script>

    <script>
        var baseUrl = "{{ url('/') }}";

        var loading_set =
            '<div style="text-align:center;width:100%;height:200px; position:relative;top:100px;"><i style="color:black;font-size:25px;" class="fa fa-refresh fa-spin fa-3x fa-fw"></i><p>Please wait</p></div>';

        var userLogin = "{{ Session::has('5ferns_user') }}";
    </script>
</head>

<body>
    <nav class="navbar navbar-expand-sm main-header">
        <div class="container header-inner">

            @if(strpos(URL::current(), 'kids') !== false)
                <a class="order-0  order-lg-0 navbar-brand" href="{{ url('/') }}"><img src="{{ asset('images/kids_logo.png') }}"
                    class="img-fluid" alt="logo"></a>
            @else
                <a class="order-0  order-lg-0 navbar-brand" href="{{ url('/') }}"><img src="{{ asset('images/logo.png') }}"
                    class="img-fluid" alt="logo"></a>
            @endif
            <form class=' order-2 order-lg-1 search-box' action="">
                <div class="input-group-prepend main-search">
                    <select class="selectpicker searchbarcategory w-100">
                        <option selected value="all">All</option>
                        @if(!empty($category))
                        @foreach($category['result'] as $cat)
                        <option value="{{ $cat['id'] }}">{{ ucfirst($cat['name']) }}</option>
                        @endforeach
                        @endif
                    </select> </div>
                <input type="text" placeholder="Search for product" id="search">
                <i class="fas fa-search"></i>
            </form>
            <ul class="navbar-nav order-1 order-lg-2 ps-rel">
                <li class="nav-item mobile-search">
                    <a class="d-none nav-link" href="javascript:void(0)"><i class="icon-magnifier"></i><span
                            id=""></span></a>
                </li>

                <li class="nav-item d-flex align-items-center">
                    <form action="{{url('/')}}" method="POST" name="currencyform">
                        @csrf
                        <div class="country-dropdown">
                            <select class="vodiapicker"  id="currency" name="currency" >
                                @if(!empty($currency))
                                    @foreach($currency as $data)
                                        <option value="{{$data->id}}" @if($data->id== Session::get('country_id')) selected @endif class="test"
                                            data-thumbnail="{{$data->image}}" @if(Session::get('country_id') == $data->id) selected @endif>
                                            {{$data->name}}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="lang-select">
                            <button class="btn-select" value="" type="button" name="button"></button>
                            
                            <div class="b">
                                <ul id="a">

                                </ul>
                            </div>
                        </div>
                    </form>
                </li>
    
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('cart') }}"><i class="icon-basket"></i><span
                            id="total_cart_product">0</span></a>
                </li>

                <li class="nav-item">
                    @if(Session::has('5ferns_user'))
                    <div class="dropdown hide-dromenu">
                        <a class="nav-link dropdown-toggle">
                            <i class="icon-user"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ url('myprofile') }}">Profile</a>
                            <a class="dropdown-item" href="{{ url('logout') }}">Logout</a>
                        </div>
                    </div>
                    @else
                    <div class="dropdown hide-dromenu">
                        <a class="nav-link dropdown-toggle">
                            <i class="icon-user"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ url('login') }}">Login</a>
                            <a class="dropdown-item" href="{{ url('register') }}">Register</a>
                        </div>
                    </div>

                    @endif
                </li>
            </ul>
        </div>
        @if(!empty($category))
        <div class="container-fluid cat-list">
            <div class="container">
                <a class='mob-menu ' href="javascript: void(0);">☰ &nbsp; Categories<span> <i
                            class='icon-arrow-right'></i></span></a>
                <ul class="categories">
                    @foreach($category['result'] as $cat)
                    <li>
                        <a href="{{ url('product-listing/'.$cat['slug'])}}">{{ ucfirst($cat['name']) }}</a>

                        @if(isset($cat['child']) && !empty($cat['child']) && $cat['child'][0]!='')
                        <div class="cate-drop-menu">

                            @foreach($cat['child'] as $fchild)
                            <div class="menu-column">
                                <ul class="desktop-navBlock">
                                    <li><a href="{{ url('product-listing/'.$fchild['slug']) }}"
                                            class="categoryname sec-level">{{ ucfirst($fchild['name'] )}}</a></li>

                                    @if(isset($fchild['child']) && !empty($fchild['child']) && $fchild['child'][0]!='')

                                    @foreach($fchild['child'] as $schild)
                                    <li><a href="{{ url('product-listing/'.$schild['slug'])}}"
                                            class="categorylink">{{ ucfirst($schild['name'])}}</a></li>
                                    @endforeach
                                    @endif
                                    <div class="hrLine"></div>
                                </ul>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </nav>

    @yield('content')

    <div class="container-fluid main-padding facilities-container">
        <div class="container">
            <div class="row justify-content-around">
                <div class="facilities-box  col-md-6 col-lg-4">
                    <div class="facilities-icon-box">
                        <img src="{{ asset('images/2.png') }}" alt="" style="height: 100px;">
                    </div>
                    <div class="facilities-text-box">
                        <p class="fas-text">Delivery Across the World</p>
                        <p>Handmade with love from India, shipped across the Globe</p>
                    </div>
                </div>
                <div class="facilities-box  col-md-6 col-lg-4">
                    <div class="facilities-icon-box">
                        <img src="{{ asset('images/3.png')}}" alt="" style="height: 80px;">
                    </div>
                    <div class="facilities-text-box">
                        <p class="fas-text">100% Secure Payments</p>
                        <p>Domestic and international Cards accepted</p>
                    </div>
                </div>
                <div class="facilities-box col-md-6 col-lg-4 mt-md-2">
                    <div class="facilities-icon-box">
                        <img src="{{ asset('images/1.png') }}" alt="" style="height: 80px;">
                    </div>
                    <div class="facilities-text-box">
                        <p class="fas-text">Handcrafted Genuine Products</p>
                        <p>Sourced ethically from local Artisans</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="main-padding footer-wrapper pb-0">
        <div class="container">
            <div class="footer-item">
                <div class="footer-blocks column-count-5">
                    <div class="footer-block-item">
                        <h2>Follow us</h2>
                        <div class="social-icons">
                            <ul>
                                <li><a target="_blank" href="https://www.facebook.com/Five-Ferns-104150188797623/"><i class="icon-social-facebook"></i></a></li>
                                <!-- <li><a href="javascript:void(0);"><i class="icon-social-twitter"></i></a></li> -->
                                <li><a target="_blank" href="https://www.instagram.com/fiveferns.luxe/"><i class="icon-social-instagram"></i></a></li>
                                <!-- <li><a href="javascript:void(0);"><i class="icon-social-pinterest"></i></a></li> -->
                                <!-- <li><a href="javascript:void(0);"><i class="icon-envelope"></i></a></li> -->
                            </ul>
                        </div>
                        <h2 style="margin-top:1rem;margin-bottom:1px">Support</h2>
                        ordersupport@fiveferns.in<br>
                        +91-7302036153
                    </div>
                    <div class="footer-block-item">
                        <h2>Categories</h2>
                        @if($category)
                            <ul>
                                @foreach($category['result'] as $cat)
                                    <li><a href="{{ url('product-listing/'.$cat['slug']) }}">{{ $cat['name'] }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="footer-block-item">
                        <h2>Shop</h2>
                        <ul>
                            <li><a href="javascript:void(0);">Coupons</a></li>
                            <li><a href="#dealday">Deal of the Day</a></li>
                            <li><a href="#dealweek">Deal of the Week</a></li>
                        </ul>
                    </div>
                    <div class="footer-block-item">
                        <h2>Informative Links</h2>
                        <ul>
                            <li><a href="{{ url('term-condition') }}">Terms & Condition</a></li>
                            <li><a href="{{ url('privacy-policy') }}">Privacy Policy</a></li>
                            <li><a href="{{ url('about-us') }}">About Us</a></li>
                            <li><a href="{{ url('return-refund-policy') }}">Return & Refund Policy</a></li>
                            <!-- <li><a href="{{ url('cancellation-policy') }}">Cancellation Policy</a></li> -->
                            <li><a href="{{ url('shipping-policy') }}">Shipping Policy</a></li>
                            <li><a href="{{ url('track-order') }}">Track Order</a></li>
                        </ul>
                    </div>
                    <div class="footer-block-item">
                        <h2>Newsletter</h2>
                        <p>Subscribe and Get regular updates</p>
                        <div class="newsletter">
                            <span class="subscribe"></span>
                            <form action="{{ route('subscribe') }}" method="post" class="formsubmit"
                                id="newsletterSubscribe">
                                @csrf
                                <div class="input-group mb-3">
                                    <input type="email" name="email" class="form-control" placeholder="Email Adderess"
                                        required>
                                    <div class="input-group-append">
                                        <button type='submit' class="subs-btn spinner-btn"
                                            id="newsletterSubscribeSubmit">Subscribe
                                            <pre
                                                class="spinner-border spinner-border-sm newsletterSubscribeLoader"></pre>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <p class='disc-line'
                style='text-align: center;margin-bottom: 0; margin-top: 25px;font-size: 14px;font-weight: 600;'>
                Credit and Debit Cards, International Included UPI, Netbanking, Payment Wallets, Pay later
            </p>
        </div>
        <div class="container-fluid copyright-container">
        <div class="disclaimer col-md-12 d-flex">
                <h5 class='col-md-7' style='text-align:end;'>&copy; <span>2021</span> Five Ferns. All Rights Reserved.
                </h5>
                <div class='col-md-5' style="text-align:right">Made By <a target="_blank" href="{{ url('https://d2rtech.com/') }}">D2rtech </a></div>
            </div>
        </div>
    </section>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/SmoothScroll.min.js') }}"></script>
    <script src="{{ asset('js/fancybox.umd.js') }}"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    <script>
        AOS.init();
    </script>
    <script>
        $(window).scroll(function () {
            if ($(window).scrollTop() >= 200) {
                $('.main-header').addClass('header-fixed');
            } else {
                $('.main-header').removeClass('header-fixed');
            }
        });
    </script>


    <script>
        $(document).ready(function () {

            var selectedCatValue = $(".searchbarcategory :selected").val();
            cateclick(selectedCatValue);

            $(".searchbarcategory").change(function () {
                var selectedCatValue = $(".searchbarcategory :selected").val();
                cateclick(selectedCatValue);

            });

            function cateclick(selectedCatValue) {

                $("#search").autocomplete({
                    source: "{{ route('searchproduct-byname') }}?category_id=" + selectedCatValue,
                    minLength: 2,
                    select: function (event, ui) {
                        if (ui.item.value != 'no') {
                            location.href = ui.item.link;
                        }
                        return false;
                    }
                });
            }

        });
    </script>

    <div style='z-index:1051;' class="toast" data-autohide="true">
        <div class="toast-body">

        </div>
    </div>

    <script src="https://kit.fontawesome.com/0b8334f960.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('js/fSelect.js') }}"></script>
    <script src="{{ asset('js/common.js') }}"></script>

    <script>


        $(document).ready(function () {
            @if(Session::has('5fernsuser_error'))
            showMsg('error', "{{ Session::get('5fernsuser_error') }}");
            @elseif(Session::has('5fernsuser_success'))
            showMsg('success', "{{ Session::get('5fernsuser_success') }}");
            @endif

            addToCart();

            getTotalCartProduct();
        });
    </script>
    <script>
        $(document).ready(function () {
            $(".mobile-search").click(function () {
                $(".search-box").toggle();
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $(".mob-menu").click(function () {
                $(".categories").toggle();
            });
        });
    </script>
    <script>
        //test for getting url value from attr
        // var img1 = $('.test').attr("data-thumbnail");
        // console.log(img1);

        //test for iterating over child elements
        var langArray = [];
        $('.vodiapicker option').each(function () {
            var img = $(this).attr("data-thumbnail");
            var text = this.innerText;
            var value = $(this).val();
            var item = '<li><img src="' + img + '" alt="" value="' + value + '"/><span>' + text +
            '</span></li>';
            langArray.push(item);
        })

        $('#a').html(langArray);

        //Set the button value to the first el of the array
        $('.btn-select').html(langArray["{{Session::get('country_id')-1}}"]);
        $('.btn-select').attr('value', 'en');

        //change button stuff on click
        $('#a li').click(function () {
            var img = $(this).find('img').attr("src");
            var value = $(this).find('img').attr('value');
            var text = this.innerText;
            var item = '<li><img src="' + img + '" alt="" /><span>' + text + '</span></li>';
            $('.btn-select').html(item);
            $('.btn-select').attr('value', value);
            $('#currency').val(value);
            $(".b").toggle();
            $(this).closest('form').submit();
            //console.log(value);
        });

        $(".btn-select").click(function () {
            $(".b").toggle();
        });

        //check local storage for the lang
        var sessionLang = localStorage.getItem('lang');
        if (sessionLang) {
            //find an item with value of sessionLang
            var langIndex = langArray.indexOf(sessionLang);
            $('.btn-select').html(langArray[langIndex]);
            $('.btn-select').attr('value', sessionLang);
        } else {
            var langIndex = langArray.indexOf('ch');
            console.log(langIndex);
            $('.btn-select').html(langArray[langIndex]);
            //$('.btn-select').attr('value', 'en');
        }
    </script>
    @stack('custom_js')
</body>

</html>
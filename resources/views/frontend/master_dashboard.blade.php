{{-- <!DOCTYPE html>
    <html class="no-js" lang="en">
        
        <head>
            <meta charset="utf-8" />
            <title>Nest - Multipurpose eCommerce HTML Template</title>
            <meta http-equiv="x-ua-compatible" content="ie=edge" />
            <meta name="description" content="" />
            <meta name="viewport" content="width=device-width, initial-scale=1" />
            <meta property="og:title" content="" />
            <meta property="og:type" content="" />
            <meta property="og:url" content="" />
            <meta property="og:image" content="" /> --}}
    <!-- Favicon -->
    {{-- <link rel="shortcut icon" type="image/x-icon" href=" {{asset('frontend/assets/imgs/theme/favicon.svg')}}" /> --}}
    <!-- Template CSS -->
    
    {{-- <link rel="stylesheet" href=" {{asset('frontend/assets/css/plugins/animate.min.css')}}" />
    <link rel="stylesheet" href=" {{asset('frontend/assets/css/main.css?v=5.3')}}/" />
</head>

<body> --}}
    <!-- Modal -->
    
    <!-- Quick view -->
    @include('frontend.body.header')
    @include('frontend.body.quick_view')
    <!-- Header  -->

   <!-- End Header  -->




    
    <!--End header-->








    <main class="main">
        @yield('main')
 <!--End Vendor List -->





    </main>







    @include('frontend.body.footer')
    <!-- Preloader Start -->
    
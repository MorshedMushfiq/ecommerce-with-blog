<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>CakeZone - Cake Shop Website Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="{{URL::asset('img/favicon.ico')}}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Oswald:wght@500;600;700&family=Pacifico&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="{{URL::asset('css/all.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/bootstrap-icons.css')}}">

    <!-- Libraries Stylesheet -->
    <link href="{{URL::asset('lib/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{URL::asset('css/bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/bootstrap.min.css')}}">



    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{URL::asset('css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{URL::asset('css/style.css')}}" rel="stylesheet">
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid px-0 d-none d-lg-block">
        <div class="row gx-0">
            <div class="col-lg-4 text-center bg-secondary py-3">
                <div class="d-inline-flex align-items-center justify-content-center">
                    <i class="bi bi-envelope fs-1 text-primary me-3"></i>
                    <div class="text-start">
                        <h6 class="text-uppercase mb-1">Email Us</h6>
                        <span>info@example.com</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center bg-primary border-inner py-3">
                <div class="d-inline-flex align-items-center justify-content-center">
                    <a href="{{route('cake.index')}}" id='home' class="navbar-brand">
                        <h1 class="m-0 text-uppercase text-white"><i class="fa fa-birthday-cake fs-1 text-dark me-3"></i>CakeZone</h1>
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-center bg-secondary py-3">
                <div class="d-inline-flex align-items-center justify-content-center">
                    <i class="bi bi-phone-vibrate fs-1 text-primary me-3"></i>
                    <div class="text-start">
                        <h6 class="text-uppercase mb-1">Call Us</h6>
                        <span>+012 345 6789</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow-sm py-3 py-lg-0 px-3 px-lg-0">
        <a href="{{route('cake.index')}}" class="navbar-brand d-block d-lg-none">
            <h1 class="m-0 text-uppercase text-white"><i class="fa fa-birthday-cake fs-1 text-primary me-3"></i>CakeZone</h1>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto mx-lg-auto py-0">
                <a href="/" id='home' class="nav-item nav-link active">Home</a>
                <a href="{{route('cake.about')}}" id='about' class="nav-item nav-link">About Us</a>
                <a href="{{route('cake.menu')}}" id='menu' class="nav-item nav-link">Menu & Pricing</a>
                <a href="team" id='team' class="nav-item nav-link">Master Chefs</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                    <div class="dropdown-menu m-0">
                        <a href="{{route('cake.services')}}" id='services' class="dropdown-item">Our Service</a>
                        <a href="{{route('cake.testimonial')}}" id='testimonial' class="dropdown-item">Testimonial</a>
                        @auth
                        <a href="{{route('admin.dashboard')}}" id='testimonial' class="dropdown-item">My Profile</a>
                        <a href="{{route('people.profile')}}" id='testimonial' class="dropdown-item">My Cart</a>
                        <a href="{{route('cake.logout')}}" id='testimonial' class="dropdown-item">Log out</a>
                        @else 
                        <a href="{{route('cake.login')}}" id='testimonial' class="dropdown-item">Login</a>
                        <a href="{{route('cake.register')}}" id='testimonial' class="dropdown-item">Register</a>
                        @endauth

                    </div>
                </div>
                <a href="{{route('cake.contact')}}" id='contact' class="nav-item nav-link">Contact Us</a>
                @auth
                <a href="{{URL::to('/chatify')}}" id='chatify' class="nav-item nav-link">Chat with us</a>
                @endauth
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

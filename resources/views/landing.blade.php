<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Required Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- AdminLTE and Bootstrap CSS -->
    <link href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminlte/css/adminlte.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>@yield('page-title')</title>
</head>
    <!-- Hero Section with Slider -->
    <section id="hero" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="https://placehold.co/1920x500.png?text=Shreeji+Quarry+Works+Slider+1" alt="Slider Image 1">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Welcome to Shreeji Quarry Works</h5>
                    <p>Your trusted partner for quality quarry and stone products.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="https://placehold.co/1920x500.png?text=Shreeji+Quarry+Works+Slider+2" alt="Slider Image 2">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Innovative Solutions</h5>
                    <p>Building a better future with reliable products and services.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="https://placehold.co/1920x500.png?text=Shreeji+Quarry+Works+Slider+3" alt="Slider Image 3">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Quality You Can Trust</h5>
                    <p>Serving the construction industry with top-notch materials.</p>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#hero" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#hero" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </section>

    <!-- About Us Section -->
    <section id="about" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2>About Us</h2>
                    <p>Shreeji Quarry Works is a leading provider of high-quality quarry and stone products for the construction industry. We are committed to delivering materials that meet the highest standards, ensuring your projects are built to last. With years of experience and a dedicated team, we provide innovative solutions and exceptional service to our clients.</p>
                </div>
                <div class="col-lg-6">
                    <img src="https://placehold.co/500x300.png?text=About+Shreeji+Quarry+Works" alt="About Image" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="bg-light py-5">
        <div class="container">
            <h2 class="text-center">Our Services</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <img class="card-img-top" src="https://placehold.co/350x200.png?text=Service+1" alt="Service 1">
                        <div class="card-body">
                            <h5 class="card-title">Stone Supply</h5>
                            <p class="card-text">We provide a wide variety of stone products suitable for construction, landscaping, and industrial applications.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <img class="card-img-top" src="https://placehold.co/350x200.png?text=Service+2" alt="Service 2">
                        <div class="card-body">
                            <h5 class="card-title">Quarry Operations</h5>
                            <p class="card-text">Our state-of-the-art quarry operations ensure we extract the best materials while maintaining safety and environmental standards.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <img class="card-img-top" src="https://placehold.co/350x200.png?text=Service+3" alt="Service 3">
                        <div class="card-body">
                            <h5 class="card-title">Delivery Services</h5>
                            <p class="card-text">We provide reliable and timely delivery services, ensuring your materials reach the site on time and in perfect condition.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5">
        <div class="container">
            <h2 class="text-center">Contact Us</h2>
            <div class="row">
                <div class="col-lg-6">
                    <form action="#" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Your Name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" placeholder="Your Email" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea class="form-control" id="message" rows="4" placeholder="Your Message" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
                <div class="col-lg-6">
                    <h4>Our Location</h4>
                    <p>123 Quarry Road, City Name, Country</p>
                    <div id="map" style="height: 300px; width: 100%"></div>
                </div>
            </div>
        </div>
    </section>

@push('scripts')
    <!-- Initialize Carousel -->
    <script>
        $('.carousel').carousel({
            interval: 5000
        });
    </script>
@endpush

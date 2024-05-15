@section('title', 'Home')
@extends('layouts.app')
@push('style')

@endpush
@section('content')
<!-- Testimonial Section End -->
<section class="bannersec">
    <div class="container">
        <div class="bannerarea d-flex align-items-center">
            <div class="bannertxt">
                <h2>Make great podcast and stay tuned Hiiii</h2>
                <p class="mt-4"><a href="{{route('download.app')}}" class="bd_btn">Get Started</a></p>
            </div>
            <div class="bannerimg">
                <img src="{{ url('frontend/images/img1.png')}}" alt="Image">
            </div>
        </div>
    </div>
    <span class="roundborder"></span>
</section>

<section class="sec-space">
    <div class="container">
        <div class="heading"><h2>Most popular creator</h2></div>
        <div class="creatorsec pt-4">
            <div class="owl-carousel owl-theme" id="creator">
                <div class="item">
                    <div class="creatorbox">
                        <img src="{{ url('frontend/images/img.jpg')}}" alt="Image">
                        <div class="creatortxt">
                            <h2>Get to know the business world</h2>
                            <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod</p>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="creatorbox">
                        <img src="{{ url('frontend/images/img.jpg')}}" alt="Image">
                        <div class="creatortxt">
                            <h2>Market research vs design research</h2>
                            <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod</p>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="creatorbox">
                        <img src="{{ url('frontend/images/img.jpg')}}" alt="Image">
                        <div class="creatortxt">
                            <h2>Get to know the business world</h2>
                            <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod</p>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="creatorbox">
                        <img src="{{ url('frontend/images/img.jpg')}}" alt="Image">
                        <div class="creatortxt">
                            <h2>Market research vs design research</h2>
                            <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="sec-space bg1">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="filterimg">
                    <img src="{{ url('frontend/images/img2.png')}}" alt="Image">
                </div>
            </div>
            <div class="col-md-6">
                <div class="filtesec pt-4">
                    <div class="owl-carousel owl-theme" id="filte">
                        <div class="item">
                            <div class="filtetxt">
                                <h2>Video Effects and Filters</h2>
                                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
                                <p class="mt-lg-5 mt-4 appstore">
                                    <a href="#0"><img src="{{ url('frontend/images/appstore.png')}}" alt="App"></a>
                                    <a href="#0"><img src="{{ url('frontend/images/playstore.png')}}" alt="Play"></a>
                                </p>
                            </div>
                        </div>
                        <div class="item">
                            <div class="filtetxt">
                                <h2>Video Effects and Filters</h2>
                                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="sec-space">
    <div class="container">
        <div class="heading"><h2>Follow us</h2></div>
        <div class="socialsec pt-4 row">
            <div class="col-sm-4">
                <a href="#0" class="socialbox">
                    <img src="{{ url('frontend/images/img.jpg')}}" alt="Image">
                    <i class="fa fa-facebook-official" aria-hidden="true"></i>
                </a>
            </div>
            <div class="col-sm-4">
                <a href="#0" class="socialbox">
                    <img src="{{ url('frontend/images/img.jpg')}}" alt="Image">
                    <i class="fa fa-twitter" aria-hidden="true"></i>
                </a>
            </div>
            <div class="col-sm-4">
                <a href="#0" class="socialbox">
                    <img src="{{ url('frontend/images/img.jpg')}}" alt="Image">
                    <i class="fa fa-instagram" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
@push('script')

@endpush

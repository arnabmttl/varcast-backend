@section('title', "Download ")
@extends('layouts.app')
@push('style')

@endpush
@section('content')
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
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

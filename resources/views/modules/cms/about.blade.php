@section('title', @$about->name)
@extends('layouts.app')
@push('style')
<style type="text/css">
    p {
        color:#fff;
        font-family: "Outfit", sans-serif;
        font-size: 14px;
    }
</style>
@endpush
@section('content')
<section class="sec-space about-page bggray">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-5 mb-lg-0 mb-4 pr-lg-5 {{--  wow  --}} zoomIn" data-wow-duration="1.5s" style="visibility: visible; animation-duration: 1.5s; animation-name: zoomIn;">
                {{--  <img class="w-100" src="images/your-business-growth.jpg" alt="">  --}}
                @if(@$about->image)
                    <img src="{{url('storage/content/'.@$about->image)}}" class="w-100" alt="{{ @$about->name }}">
                @else
                    <img src="{{url('images/no-image.png')}}" class="w-100" style="width: 88px;">
                @endif
            </div>
            <div class="col-lg-5 {{--  wow fadeInRight  --}}" data-wow-duration="2s" style="visibility: visible; animation-duration: 2s; animation-name: fadeInRight;">
                <h2>{{ @$about->name }}</h2>
                {!! @$about->content !!}
            </div>
        </div>
    </div>
</section>
@endsection

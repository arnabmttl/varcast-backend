<footer class="ftrsec">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <a href="{{route('home1')}}" class="ftrlogo">
                    <img src="{{ url('frontend/images/logo.png')}}" alt="Logo" title="Logo"/>
                </a>
            </div>
            <div class="col-md-5">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="menuhead">Company</div>
                        <ul class="ftrmenu">
                            <li><a href="{{route('about.us')}}">About Us</a></li>
                            <li><a href="{{ route('contact.us') }}">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <div class="menuhead">Follow us</div>              
                        <ul class="softrmenu">
                            <li><a href="#0"><i class="fa fa-facebook-official" aria-hidden="true"></i></a></li>
                            <li><a href="#0"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            <li><a href="#0"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <div class="menuhead">Get the app</div>
                        <div class="apps">
                            <a href="#0"><img src="{{ url('frontend/images/appstore.png')}}" alt="App"></a>
                            <a href="#0"><img src="{{ url('frontend/images/playstore.png')}}" alt="Play"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ftrcopy">
            <p>Copyright © {{date('Y')}} {{env('APP_NAME')}} All right reserved</p>
            <p><a href="#0">Privacy Policy</a><a href="#0">Cookies</a></p>
        </div>
    </div>
</footer>

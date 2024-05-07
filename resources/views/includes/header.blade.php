<header class="headersec">
    <div class="container">
        <nav class="navbar navbar-expand-lg">
            <a href="{{route('home1')}}" class="navbar-brand">
                <img src="{{ url('frontend/images/logo.png')}}" alt="Logo" title="Logo"/>
            </a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto align-items-center headermenu">
                    <li><a href="{{route('about.us')}}">About Us</a></li>
                    <li><a href="{{ route('contact.us') }}">Contact Us</a></li>           
                </ul>
                <a href="{{route('download.app')}}" class="bd_btn ml-md-5">Login</a>
            </div>
        </nav>
    </div>
</header>
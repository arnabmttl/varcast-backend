<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="{{ url('customer/images/favicon.png') }}" type="image/x-icon">
        <title>Admin :: @yield('title')</title>

        <!-- Scripts -->
        {{--  <script src="{{ asset('public/js/app.js') }}" defer></script>
        <script src="{{ asset('public/js/custom.js') }}" defer></script>  --}}

        <!-- Fonts -->
        {{--  <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">  --}}

        <!-- Styles -->
        @include('admin.includes.style')
        @stack('style')
        {{--  <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">  --}}
    </head>
    <body class="hold-transition @auth('admin') skin-blue sidebar-mini @else login-page @endauth">
        @auth('admin')
            <div class="wrapper">
                @include('admin.includes.header')

                @include('admin.includes.sidebar')

                <div class="content-wrapper">
                    @yield('content')
                </div>

                @include('admin.includes.footer')
               
            </div>
        @else
            @yield('content')
        @endauth

        @include('admin.includes.script')
        @stack('script')

        @include('admin.includes.messages')
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </body>
</html>
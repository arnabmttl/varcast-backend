<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ url('frontend/images/favicon.png') }}" type="image/x-icon">
    <title>{{env('APP_NAME')}} :: @yield('title')</title>
    @include('includes.style')
    @stack('style')

</head>
<body>




    @include('includes.header')
    @yield('content')
    @include('includes.footer')

    @include('includes.script')
    @stack('script')

    @include('includes.messages')
    {{-- <script type="text/javascript">
        var source = new EventSource("{{URL('/sse-update')}}");
        source.onmessage = function(event){
            let ac = JSON.parse(event.data);
            alert(ac.message);
        }
    </script> --}}
</body>
</html>

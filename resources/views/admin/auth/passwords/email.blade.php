@extends('admin.layouts.app')
@section('title', 'Reset Password')
@section('content')
<div class="login-box">
    <div class="login-logo" style="font-size: 25px;">
        <a href="{{route('admin.password.request')}}"><b>{{env('APP_NAME')}}</b> {{ __('Reset Password') }}</a>
    </div>
    <div class="login-box-body">
        <form id="Resetform" action="{{ route('admin.password.email') }}" method="post">
            @csrf

            <div class="form-group has-feedback">
                <input type="email" class="form-control" name="email" placeholder="Email">
                <span class="fa fa-envelope form-control-feedback"></span>
                <label for="email" class="error" style="display: none;"></label>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{ __('Send Password Reset Link') }}</button>
                </div>
            </div>
        </form>
        <br><a href="{{route('admin.login')}}">Login</a>
    </div>
</div>
@endsection
@push('script')
<script type="text/javascript">
    $.validator.addMethod("customemail", 
        function(value, element) {
            return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
        }, 
        "Please enter a valid email address."
        );
    $('#Resetform').validate({
        rules:{
            email:{
                required:true,
                customemail: true
            }
        }
    });
</script>
@endpush

@extends('admin.layouts.app')
@section('title', 'Reset Password')
@section('content')
<div class="login-box">
    <div class="login-logo" style="font-size: 25px;">
        <a href="javascript:void(0);"><b>{{env('APP_NAME')}}</b> Reset Password</a>
    </div>

    <div class="login-box-body">
        <form id="passwordResetForm" action="{{ route('admin.password.update') }}" method="post">
            @csrf
             <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group has-feedback">
                <input type="email" class="form-control" name="email" placeholder="Email" value="{{ $email ?? old('email') }}">
                <span class="fa fa-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="new-password">
                <span class="fa fa-lock form-control-feedback"></span>
                <label for="password" class="error" style="display: none;"></label>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password_confirmation" id="password-confirm" placeholder="Password" autocomplete="new-password">
                <span class="fa fa-lock form-control-feedback"></span>
                <label for="password-confirm" class="error" style="display: none;"></label>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{ __('Reset Password') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('script')
<script>
    $(document).ready(function(){
        $('#passwordResetForm').validate({
            rules:{
                email:{
                    required:true,
                    email:true,
                },
                password:{
                    required:true,
                    minlength:8,
                },
                password_confirmation :{
                    required:true,
                    minlength:8,
                    equalTo:"#password"
                }
            }
        });
    })
</script>
@endpush

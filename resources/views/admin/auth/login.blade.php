@section('title', 'Login')
@extends('admin.layouts.app')

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="{{route('admin.home')}}"><b>{{env('APP_NAME')}}</b> LOGIN</a>
    </div>

    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form id="loginform" action="{{route('admin.login')}}" method="post">
            @csrf

            <div class="form-group has-feedback">
                <input type="email" class="form-control" name="email" placeholder="Email">
                <span class="fa fa-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" placeholder="Password">
                <span class="fa fa-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <input type="checkbox" name="remember_me" id="remember_me"> <label for="remember_me"> Remember Me </label>
                    </div>
                </div>

                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
            </div>
        </form>
        <br><a href="{{route('admin.password.request')}}">I forgot my password</a>
    </div>
</div>
@endsection

@push('script')
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' /* optional */
        });
    });

    $('#loginform').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
            },
        },
        messages: {
            email: {
                required: "Please enter your email address",
                email: "The inserted email address must be a email"
            },
            password: {
                required: "Please enter a password to continue"
            }
        },
        errorElement: "p",
        errorPlacement: function ( error, element ) {
            if ( element.prop("tagName").toLowerCase() === "select" ) {
                error.insertAfter( element.closest( ".form-group" ).find(".select2") );
            } else {
                error.insertAfter( element );
            }
        },
        submitHandler: function() {
                //form.submit();
                var form = $('#loginform');
                Pace.track(function(){
                    form.ajaxSubmit({
                        dataType:'json',
                        beforeSubmit:function(){
                            form.find('button[type="submit"]').button('loading');
                        },
                        success:function(data){
                            // form.find('button[type="submit"]').button('reset');

                            form[0].reset();
                            notify(data.status, 'success');
                            window.location.href = "{{route('admin.home')}}";
                        },
                        error: function(errors) {
                            form.find('button[type="submit"]').button('reset');
                            showErrors(errors, form);
                        }
                    });
                });
            }
        });
    </script>
    @endpush

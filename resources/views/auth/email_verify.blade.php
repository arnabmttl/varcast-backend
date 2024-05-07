@section('title', 'Email Verify')
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Verify Your Email Address</div>

                <div class="card-body">
                    {{--  @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},  --}}
                    <form class="d-inline" method="POST" action="{{ route('email.verify.vcode') }}" id="emailVcodeForm">
                        @csrf
                        <div class="row mb-3">
                            <label for="email_vcode" class="col-md-4 col-form-label text-md-end">Email Verification OTP</label>

                            <div class="col-md-6">
                                <input type="text" name="email_vcode" class="form-control" id="email_vcode" rquired>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a class="btn btn-link" href="{{ route('resend.email.verify') }}">Resend OTP</a>
                            </div>
                        </div>
                        {{--  <input type="text" name="email_vcode" class="form-control" id="email_vcode" rquired>
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">Submit</button>.  --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready( function(){
        // form validation
        $('#emailVcodeForm').validate({
            rules: {
                email_vcode: {
                    required: true,
                    digits: true,
                    minlength: 4,
                    maxlength: 4
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    })

</script>
@endpush

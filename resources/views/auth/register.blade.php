@section('title', 'Register')
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" id="registrationForm">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                {{--  @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror  --}}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('Phone Number') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required autocomplete="phone">
                                <p id="phonemsg" style="display: none;"></p>

                                {{--  @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror  --}}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="address" class="col-md-4 col-form-label text-md-end">{{ __('Address') }}</label>

                            <div class="col-md-6">
                                <textarea id="address" class="form-control" name="address" required autocomplete="address" rows="4">{{ old('address') }}</textarea>
                                {{--  <input id="address" type="email" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required autocomplete="address">  --}}

                                {{--  @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror  --}}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email">
                                <p id="emailmsg" style="display: none;"></p>

                                {{--  @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror  --}}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password">

                                {{--  @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror  --}}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    // email checking
    $('#email').blur(function(){
        $.ajax({
            dataType: 'json',
            url: "{{ route('check.email') }}",
            type: 'Post',
            data: {
                '_token': '{{ csrf_token() }}',
                'email': $('#email').val()
            },
            success: function(response) {
                console.log(response);
                if(response.status == 'fail') {
                    $('#emailmsg').css('color', 'red');
                    $('#email').val('');
                    $('#emailmsg').html(response.message);
                    $('#emailmsg').show();
                } else {
                    $('#emailmsg').hide();
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
    });

    // phone chicking
    $('#phone').blur(function(){
        $.ajax({
            dataType: 'json',
            url: "{{ route('check.phone') }}",
            type: 'Post',
            data: {
                '_token': '{{ csrf_token() }}',
                'phone': $('#phone').val()
            },
            success: function(response) {
                console.log(response);
                if(response.status=='fail') {
                    $('#phonemsg').css('color', 'red');
                    $('#phone').val('');
                    $('#phonemsg').html(response.message);
                    $('#phonemsg').show();
                } else {
                    $('#phonemsg').hide();
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
    });

    $(document).ready( function(){
        // form validation
        $('#registrationForm').validate({
            rules: {
                name: {
                    required: true,
                    alpha: true,
                    minlength: 1
                },
                phone: {
                    required: true,
                    digits: true,
                    minlength: 9,
                    maxlength: 15
                },
                address: {
                    required: true,
                    minlength: 1
                },
                email: {
                    required: true,
                    email: true,
                    minlength: 1
                },
                password: {
                    required: true,
                    alphanumeric: true,
                    minlength: 10
                },
                password_confirmation: {
                    required:true,
                    equalTo:"#password"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    })

</script>
@endpush

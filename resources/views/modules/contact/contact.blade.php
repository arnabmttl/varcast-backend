@section('title', 'Contact Us')
@extends('layouts.app')
@push('style')
    <style type="text/css">
        label.error{
            color:red;
        }
    </style>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
<section class="sec-space contact-page bggray">
    <div class="container">
        <div class="row justify-content-center no-gutters">
            <div class="col-lg-5 col-md-6 mb-4 mb-md-0 {{--  wow  --}} zoomIn" data-wow-duration="1.5s" style="visibility: visible; animation-duration: 1.5s; animation-name: zoomIn;">
                <div class="contactbox-left">
                    <form id="contactForm" method="POST" action="{{ route('contact.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="name" placeholder="Full Name" id="name" maxlength="50">
                                    </div>
                                    <label for="name" class="error" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input class="form-control" type="email" name="email" placeholder="Email" id="email" maxlength="50">
                                    </div>
                                    <label for="email" class="error" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="phone" placeholder="Phone" id="phone" maxlength="15">
                                    </div>
                                    <label for="phone" class="error" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <textarea class="form-control" name="message" id="message" placeholder="Message" maxlength="500"></textarea>
                                    </div>
                                    <label for="message" class="error" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-sm-12 text-center">
                                <button class="btn bd_btn " type="submit">Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-5 col-md-6 {{--  wow  --}} fadeInRight" data-wow-duration="1s" style="visibility: visible; animation-duration: 2s; animation-name: fadeInRight;">
                <div class="contactbox  pl-5">
                    <h2 class="mb-md-5">Contact Us</h2>
                    @if(!empty(@Helper::getContactUsContent()->phone))
                    <a href="tel:{{@Helper::getContactUsContent()->phone}}" class="contact-phone cblock mb-4 text-light"><i class="fa fa-phone" aria-hidden="true"></i> {{@Helper::getContactUsContent()->phone}}</a>
                    @endif
                    @if(!empty(@Helper::getContactUsContent()->email))
                    <a href="mailto:{{@Helper::getContactUsContent()->email}}" class="contact-phone cblock mb-4 text-light"><i class="fa fa-envelope-o" aria-hidden="true"></i> {{@Helper::getContactUsContent()->email}}</a>
                    {{--  <div class="contact-mail cblock mb-4"><i class="fa fa-envelope-o" aria-hidden="true"></i> {{@Helper::getContactUsContent()->email}}</div>  --}}
                    @endif
                    @if(!empty(@Helper::getContactUsContent()->address))
                    <div class="contact-map cblock"><i class="fa fa-location-arrow" aria-hidden="true"></i> {{@Helper::getContactUsContent()->address}}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script')
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script>

    $(document).ready( function(){
        // contact form validation
        $('#contactForm').validate({
            rules: {
                name: {
                    required: true,
                    alpha: true,
                    minlength: 1,
                    maxlength:50
                },
                phone: {
                    required: true,
                    digits: true,
                    minlength: 9,
                    maxlength: 15
                },
                message: {
                    required: true,
                    minlength: 1,
                    maxlength:500
                },
                email: {
                    required: true,
                    email: true,
                    minlength: 1,
                    maxlength:50
                }
            },
            submitHandler: function(form) {
                form.submit();
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            }
        });
    })

</script>
@endpush

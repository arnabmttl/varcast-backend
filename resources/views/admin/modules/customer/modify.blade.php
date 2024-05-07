@extends('admin.layouts.app')
@section('title')
@if(!empty(@$user_data))
Edit
@else
Add
@endif
User
@endsection
@section('content')
<section class="content-header">
    <h1>User Management<small>@if(!empty(@$user_data)) Edit @else Add @endif User</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        {{-- <li><a href="{{URL::previous()}}"><i class="fa fa-dashboard"></i> Master Management</a></li> --}}
        <li class="">
            <a href="{{URL::previous()}}">
                User Management
            </a>
        </li>
        @if(!empty(@$user_data))
        <li class="active">
            Edit User
        </li>
        @else
        <li class="active">
            Add User
        </li>
        @endif
    </ol>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@if(!empty(@$user_data)) Edit @else Add @endif User
                </h3>
                <a href="{{route('admin.customer.management')}}" class="btn bg-navy btn-xs pull-right"><i class="fa fa-arrow-circle-left"></i> Back</a>
            </div>
            <div class="box-body">
                <form action="{{route('admin.customer.store')}}" id="userForm" method="post" enctype="multipart/form-data">
                    <div class="row">
                        @csrf
                        <input type="hidden" name="rowid" value="{{@$user_data->_id}}">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" value="@if(!empty(@$user_data)){{ $user_data->name }}@else{{old('name')}}@endif" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="@if(!empty(@$user_data)){{ $user_data->email }}@else{{old('email')}}@endif" placeholder="Enter Email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone </label>
                                <input type="text" class="form-control" id="phone" name="phone" value="@if(!empty(@$user_data)){{ $user_data->phone }}@else{{old('phone')}}@endif" placeholder="Enter Phone" maxlength="15">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="imageInput">Profile Image</label>
                                <input type="file" name="image" id="imageInput" class="form-control">
                                <div class="edit-user-img">
                                    @if(!empty(@$user_data))
                                    <img src="{{ @$user_data->full_path_image }}" width="20%">
                                    @else
                                    <img  width="20%">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Account Description </label>
                                <textarea class="form-control" name="description" id="description" placeholder="Enter Description" rows="4">@if(!empty(@$user_data)){{ $user_data->description }}@else{{old('description')}}@endif</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Password @if(empty(@$user_data))<span class="text-danger">*</span>@endif</label>
                                <input type="password" class="form-control" name="password" placeholder="Create Password" autocomplete="new-password" id="new-password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Confirm Password @if(empty(@$user_data))<span class="text-danger">*</span>@endif</label>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <button class="btn btn-md btn-primary" type="submit">Submit</button>
                                <button type="button" class="btn btn-md btn-info" onclick="location.href='{{route('admin.customer.management')}}'">Back</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script>
    const imageInput = document.getElementById('imageInput');
    imageInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = (e) => {
            const imageUrl = e.target.result;
            $('.edit-user-img img').attr('src',imageUrl);
        };

        reader.readAsDataURL(file);
    });
    $('#userForm').validate({
        ignore: [],
        rules: {
            name:{
                required: true,
            },
            email:{
                required: true,
                email:true
            },
            phone:{
                required: false,
                digits:true,
                minlength:9,
                maxlength:15,
            },
            image:{
                required: false,
            },
            password:{
                required: {{ !empty(@$user_data) ? 'false'  : 'true' }},
                minlength: 6
            },
            password_confirmation: {
                required: {{ !empty(@$user_data) ? 'false'  : 'true' }},
                minlength: 6,
                equalTo: "#new-password"
            }
        },
    });
</script>
@endsection

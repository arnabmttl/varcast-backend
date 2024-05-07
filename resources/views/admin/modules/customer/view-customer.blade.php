@extends('admin.layouts.app')
@section('title', 'User Details')
@section('content')

<section class="content-header">
    <h1>
        User Details
        {{-- <small>Customer Details</small> --}}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.customer.management')}}">User Management</a></li>
        <li class="active">User Details</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img src="{{ @$user_details->full_path_image }}" class="profile-user-img img-responsive img-circle" style="width: 88px; height: 88px;" alt="User profile picture">
                    <h3 class="profile-username text-center">{{@$user_details->name}}</h3>
                    <p class="text-muted text-center">{{@$user_details->email}}</p>
                    <ul class="list-group list-group-unbordered">
                        {{--<li class="list-group-item">
                            <b>Approval</b> 
                            <a class="pull-right">
                                @if(@$user_details->is_approved == 'Y')
                                Yes
                                @else
                                No
                                @endif
                            </a>
                        </li>
                         <li class="list-group-item">
                            <b>Email Verify</b> 
                            <a class="pull-right">
                                @if(@$user_details->is_email_verify == 'Y')
                                Yes
                                @else
                                No
                                @endif 
                            </a>
                        </li>
                        <li class="list-group-item">
                            <b>Phone Verify</b> 
                            <a class="pull-right">
                                @if(@$user_details->is_phone_verify == 'Y')
                                Yes
                                @else
                                No
                                @endif 
                            </a>
                        </li> --}}
                        <li class="list-group-item">
                            <b>Status</b> 
                            <a class="pull-right">
                                @if(@$user_details->status == 'U')
                                Unverify
                                @elseif(@$user_details->status == 'A')
                                Active
                                @elseif(@$user_details->status == 'I')
                                Inactive
                                @endif
                            </a>
                        </li>
                    </ul>
                    <a href="{{route('admin.customer.management')}}" class="btn btn-primary btn-block"><b>Go Back</b></a>
                </div>

            </div>
        </div>
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Customer Details</h3>
                </div>

                <div class="box-body">
                    <div class="col-md-6">
                        <strong> <i class="fa fa-hand-o-right margin-r-5"></i> Name</strong>
                        <p class="text-muted">
                            {{@$user_details->name}}
                        </p>
                        <strong> <i class="fa fa-hand-o-right margin-r-5"></i> Email</strong>
                        <p class="text-muted">
                            {{@$user_details->email}}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <strong> <i class="fa fa-hand-o-right margin-r-5"></i> Phone</strong>
                        <p class="text-muted">
                            {{@$user_details->phone}}
                        </p>
                        <strong> <i class="fa fa-hand-o-right margin-r-5"></i>Registration Date</strong>
                        <p class="text-muted">
                            {{@$user_details->created_at->format('d/m/Y')}}
                        </p>
                    </div>
                    <div class="col-md-12">
                        <strong> <i class="fa fa-hand-o-right margin-r-5"></i> Account Description</strong>
                        <p class="text-muted">
                            {!!@$user_details->description!!}
                        </p>
                        
                    </div>
                </div>
            </div>
        </div> 
    </section>

    @endsection
    @push('script')

    @endpush

@extends('admin.layouts.app')
@section('title', 'Change Password')
@section('content')
<section class="content-header">
	<h1>Profile Management<small>Change Password</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Change Password</li>
	</ol>
</section>
<section class="content">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">{{ "Change Password" }}</h3>
		</div>
		<form id="editForm" action="{{route('admin.update.password')}}" method="post" enctype="multipart/form-data" class="bg-white">
			@csrf
			<div class="box-body">
				<div class="col-md-4">
					<label for="old_password" class="form-label">Current Password <span class="text-danger">*</span></label>
					<input type="password" class="form-control" id="old_password" placeholder="Current Password" autocomplete="old-password" name="old_password">
				</div>
				<div class="col-md-4">
					<label for="new_password" class="form-label">New Password <span class="text-danger">*</span></label>
					<input type="password" class="form-control" id="new_password" placeholder="Password" autocomplete="new-password" name="new_password">
				</div>
				<div class="col-md-4">
					<label for="password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
					<input type="password" class="form-control" id="password-confirm" placeholder="Password" autocomplete="new-password" name="password_confirmation">
				</div>
			</div>
			<div class="box-footer">
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary">Change Password</button>
				</div>
			</div>
		</form>

	</div>
</section>
@endsection
@push('script')
<script>
	$(document).ready(function(){
		$('#editForm').validate({
			rules:{
				old_password:{
					required:true,
					minlength:8,
				},
				new_password:{
					required:true,
					minlength:8,
				},
				password_confirmation :{
					required:true,
					minlength:8,
					equalTo:"#new_password"
				}
			}
		});
	})


</script>

@endpush
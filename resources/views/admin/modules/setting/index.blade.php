@extends('admin.layouts.app')
@section('title', 'Setting')
@push('style')
<style>
	.switch {
		position: relative;
		display: inline-block;
		width: 60px;
		height: 34px;
	}
	.switch input { 
		opacity: 0;
		width: 0;
		height: 0;
	}
	.slider {
		position: absolute;
		cursor: pointer;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: #ccc;
		-webkit-transition: .4s;
		transition: .4s;
	}
	.slider:before {
		position: absolute;
		content: "";
		height: 26px;
		width: 26px;
		left: 4px;
		bottom: 4px;
		background-color: white;
		-webkit-transition: .4s;
		transition: .4s;
	}
	input:checked + .slider {
		background-color: #2196F3;
	}
	input:focus + .slider {
		box-shadow: 0 0 1px #2196F3;
	}
	input:checked + .slider:before {
		-webkit-transform: translateX(26px);
		-ms-transform: translateX(26px);
		transform: translateX(26px);
	}
	/* Rounded sliders */
	.slider.round {
		border-radius: 34px;
	}
	.slider.round:before {
		border-radius: 50%;
	}
</style>
@endpush
@section('content')
<section class="content-header">
	<h1>Setting Management<small>Edit Setting</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Edit Setting</li>
	</ol>
</section>
<section class="content">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">{{ "Edit Setting" }}</h3>
		</div>
		<form id="editForm" action="{{route('admin.setting.store')}}" method="post" enctype="multipart/form-data" class="bg-white">
			@csrf
			<div class="box-body">
				<div class="col-md-4">
					<label for="email" class="form-label">Email <span class="text-danger">*</span></label>
					<input type="email" class="form-control" name="email" id="email" aria-describedby="email" value="{{ @$setting->email }}" placeholder="Enter Email">
				</div>
				<div class="col-md-4">
					<label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
					<input type="text" class="form-control numberonly" name="phone" id="phone" aria-describedby="phone" value="{{ @$setting->phone }}" placeholder="Enter Phone">
				</div>
				<div class="col-md-4">
					<label for="address" class="form-label">Address <span class="text-danger">*</span></label>
					<input type="text" class="form-control " name="address" id="address" aria-describedby="address" value="{{ @$setting->address }}" placeholder="Enter Address">
				</div>
				<div class="col-md-4">
					<label for="mail_host" class="form-label">Mail Host</label>
					<input type="text" class="form-control" name="mail_host" id="mail_host" aria-describedby="mail_host" value="{{ @$setting->mail_host }}" placeholder="Enter Mail Host">
				</div>
				<div class="col-md-4">
					<label for="mail_port" class="form-label">Mail Port</label>
					<input type="text" class="form-control" name="mail_port" id="mail_port" aria-describedby="mail_port" value="{{ @$setting->mail_port }}" placeholder="Enter Mail Port">
				</div>
				<div class="col-md-4">
					<label for="mail_username" class="form-label">Mail Username</label>
					<input type="text" class="form-control" name="mail_username" id="mail_username" aria-describedby="mail_username" value="{{ @$setting->mail_username }}" placeholder="Enter Mail Username">
				</div>
				<div class="col-md-4">
					<label for="mail_password" class="form-label">Mail Password</label>
					<input type="text" class="form-control" name="mail_password" id="mail_password" aria-describedby="mail_password" value="{{ @$setting->mail_password }}" placeholder="Enter Mail Password">
				</div>
				<div class="col-md-4">
					<label for="mail_encryption" class="form-label">Mail Encryption</label>
					<input type="text" class="form-control" name="mail_encryption" id="mail_encryption" aria-describedby="mail_encryption" value="{{ @$setting->mail_encryption }}" placeholder="Enter Mail Encryption">
				</div>
				<div class="col-md-4">
					<label for="mail_from_address" class="form-label">Mail From Address</label>
					<input type="email" class="form-control" name="mail_from_address" id="mail_from_address" aria-describedby="mail_from_address" value="{{ @$setting->mail_from_address }}" placeholder="Enter From Address">
				</div>
				<div class="col-md-4">
					<label for="mail_from_name" class="form-label">Mail From Name</label>
					<input type="text" class="form-control" name="mail_from_name" id="mail_from_name" aria-describedby="mail_from_name" value="{{ @$setting->mail_from_name }}" placeholder="Enter From Name">
				</div>
				<div class="col-md-4">
					<label for="per_coin_price" class="form-label">Per Coin Price</label>
					<input type="text" class="form-control" name="per_coin_price" id="per_coin_price" aria-describedby="per_coin_price" value="{{ @$setting->per_coin_price }}" placeholder="Enter Per Coin Price">
				</div>
				<div class="col-md-4">
					<label for="push_notification" class="form-label" style="width: 100%;">Push Notification</label>
					<label class="switch">
						<input type="checkbox" name="push_notification" value="Y" @if(@$setting->push_notification == 'Y') checked @endif>
						<span class="slider round"></span>
					</label>
					
				</div>
			</div>
			<div class="box-footer">
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary">Submit</button>
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
				phone:{
					required:true,
					digits:true,
					minlength:10,
					maxlength:12
				},
				email:{
					required:true,
					email:true,
				},
				mail_from_address:{
					required:false,
					email:true,
				},
				address:{
					required:true,
				},
				per_coin_price:{
					required:true,
					digits:true,
				},
			}
		});
	})
</script>

@endpush
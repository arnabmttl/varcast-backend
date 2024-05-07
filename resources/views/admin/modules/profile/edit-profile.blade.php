@extends('admin.layouts.app')
@section('title', 'Edit Profile')
@section('content')
<section class="content-header">
	<h1>Profile Management<small>Edit Profile</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Edit Profile</li>
	</ol>
</section>
<section class="content">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">{{ "Edit Profile" }}</h3>
		</div>
		<form id="editForm" action="{{route('admin.update.profile')}}" method="post" enctype="multipart/form-data" class="bg-white">
			@csrf
			<div class="box-body">
				<div class="col-md-4">
					<label for="name" class="form-label">Name <span class="text-danger">*</span></label>
					<input type="text" class="form-control" name="name" id="name" aria-describedby="name" value="{{Auth::guard('admin')->user()->name }}">
				</div>
				<div class="col-md-4">
					<label for="email" class="form-label">Email address<span class="text-danger">*</span></label>
					<input type="email" class="form-control" name="email" id="email" aria-describedby="email" value="{{Auth::guard('admin')->user()->email }}">
				</div>
				<div class="col-md-4">
					<label for="phone" class="form-label">Phone<span class="text-danger">*</span></label>
					<input type="phone" class="form-control" name="phone" id="phone" aria-describedby="phone" value="{{Auth::guard('admin')->user()->phone }}" placeholder="Enter Phone">
				</div>
				<div class="col-md-6">
					<label for="country" class="form-label">Country </label>
					<input type="country" class="form-control" name="country" id="country" aria-describedby="country" value="{{@Auth::guard('admin')->user()->countryDetails->name }} [{{@Auth::guard('admin')->user()->countryDetails->sortname }}]" placeholder="Enter Country" disabled="">
				</div>
				<div class="col-md-6">
					<label class="form-label">
						Image
					</label>
					<div class="custom-file">
						<input type="file" class="form-control custom-file-input" type="file" id="formFile" name="image" accept="image/*">
						{{-- <label class="custom-file-label" for="formFile">Choose file</label> --}}
					</div>
					<div class="previewholder" @if(empty(Auth::guard('admin')->user()->image)) style="display: none;" @endif>
		                <img id="digital_signatureimgPreview" src="{{url('storage/admin_pics/'.@Auth::guard('admin')->user()->image)}}" alt="pic" style="width: 100px;  margin-top: 10px;" />
		            </div>
				</div>
			</div>
			<div class="box-footer">
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary">Edit Profile</button>
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
				name:{
					required:true,
					minlength:2
				},
				email:{
					required:true,
					email:true,
				},
				phone:{
					required:true,
					digits:true,
					minlength:9,
					maxlength:15,
				},
			}
		});
		$('#changeImageForm').validate({
			rules:{
				image:{
					required:true,
				},
			}
		});

		$("#formFile").change(function () {
			const file = this.files[0];
			if (file) {
				let reader = new FileReader();
				reader.onload = function (event) {
					$("#digital_signatureimgPreview").attr("src", event.target.result);
					$('.previewholder').show();
				};
				reader.readAsDataURL(file);
			}
		});
	})


</script>

@endpush
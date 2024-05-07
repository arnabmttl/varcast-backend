@extends('admin.layouts.app')
@section('title', 'Home Content Management')
@section('content')
<section class="content-header">
	<h1>Content Management
		<small>Home Content</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{route('admin.content.management',@$page)}}"><i class="fa fa-file"></i>Home Content Management</a></li>
		<li class="active">Home Content</li>
	</ol>
</section>
<section class="content">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Home Content</h3>
		</div>
		<form id="editForm" action="{{route('admin.store.home.content.management')}}" method="post" enctype="multipart/form-data" class="bg-white">
			@csrf
			<div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<label class="form-label" for="title_4">
							Banner Title <span class="text-danger">*</span>
						</label>
						<input type="text" name="title_4" id="title_4" class="form-control" value="{{@$home_content->title_4}}" placeholder="Enter Title" required="">
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label class="form-label" for="title_1">
							Title 1 <span class="text-danger">*</span>
						</label>
						<input type="text" name="title_1" id="title_1" class="form-control" value="{{@$home_content->title_1}}" placeholder="Enter Title" required="">
					</div>
					<div class="col-md-6">
						<label class="form-label" for="short_title_1">
							Short Title 1 <span class="text-danger">*</span>
						</label>
						<input type="text" name="short_title_1" id="short_title_1" class="form-control" value="{{@$home_content->short_title_1}}" placeholder="Enter Title" required="">
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label class="form-label" for="formFile1">
							Image 1 <span class="text-danger">*</span>
						</label>
						<div class="custom-file">
							<input type="file" class="form-control custom-file-input" type="file" id="formFile1" name="image_1" accept="image/*" @if(empty(@$home_content->image_1)) required="" @endif>
						</div>
						<div class="previewholder_1" @if(empty(@$home_content->image_1)) style="display: none;" @endif>
							<img id="digital_signatureimgPreview_1" src="{{url('storage/app/public/content/'.@$home_content->image_1)}}" alt="pic" style="width: 40px;  margin-top: 10px;" />
						</div>
					</div>
					<div class="col-md-6">
						<label class="form-label" for="image_title_1">
							Image Title 1 <span class="text-danger">*</span>
						</label>
						<input type="text" name="image_title_1" id="image_title_1" class="form-control" value="{{@$home_content->image_title_1}}" placeholder="Enter Title" required="">
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label class="form-label" for="formFile2">
							Image 2 <span class="text-danger">*</span>
						</label>
						<div class="custom-file">
							<input type="file" class="form-control custom-file-input" type="file" id="formFile2" name="image_2" accept="image/*" @if(empty(@$home_content->image_2)) required="" @endif>
						</div>
						<div class="previewholder_2" @if(empty(@$home_content->image_2)) style="display: none;" @endif>
							<img id="digital_signatureimgPreview_2" src="{{url('storage/app/public/content/'.@$home_content->image_2)}}" alt="pic" style="width: 40px;  margin-top: 10px;" />
						</div>
					</div>
					<div class="col-md-6">
						<label class="form-label" for="image_title_2">
							Image Title 2 <span class="text-danger">*</span>
						</label>
						<input type="text" name="image_title_2" id="image_title_2" class="form-control" value="{{@$home_content->image_title_2}}" placeholder="Enter Title" required="">
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label class="form-label" for="formFile3">
							Image 3 <span class="text-danger">*</span>
						</label>
						<div class="custom-file">
							<input type="file" class="form-control custom-file-input" type="file" id="formFile3" name="image_3" accept="image/*" @if(empty(@$home_content->image_3)) required="" @endif>
						</div>
						<div class="previewholder_3" @if(empty(@$home_content->image_3)) style="display: none;" @endif>
							<img id="digital_signatureimgPreview_3" src="{{url('storage/app/public/content/'.@$home_content->image_3)}}" alt="pic" style="width: 40px;  margin-top: 10px;" />
						</div>
					</div>
					<div class="col-md-6">
						<label class="form-label" for="image_title_3">
							Image Title 3 <span class="text-danger">*</span>
						</label>
						<input type="text" name="image_title_3" id="image_title_3" class="form-control" value="{{@$home_content->image_title_3}}" placeholder="Enter Title" required="">
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label class="form-label" for="formFile4">
							Image 4 <span class="text-danger">*</span>
						</label>
						<div class="custom-file">
							<input type="file" class="form-control custom-file-input" type="file" id="formFile4" name="image_4" accept="image/*" @if(empty(@$home_content->image_4)) required="" @endif>
						</div>
						<div class="previewholder_4" @if(empty(@$home_content->image_4)) style="display: none;" @endif>
							<img id="digital_signatureimgPreview_4" src="{{url('storage/app/public/content/'.@$home_content->image_4)}}" alt="pic" style="width: 40px;  margin-top: 10px;" />
						</div>
					</div>
					<div class="col-md-6">
						<label class="form-label" for="image_title_4">
							Image Title 4 <span class="text-danger">*</span>
						</label>
						<input type="text" name="image_title_4" id="image_title_4" class="form-control" value="{{@$home_content->image_title_4}}" placeholder="Enter Title" required="">
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<label class="form-label" for="title_2">
							Title 2 <span class="text-danger">*</span>
						</label>
						<input type="text" name="title_2" id="title_2" class="form-control" value="{{@$home_content->title_2}}" placeholder="Enter Title" required="">
					</div>
					<div class="col-md-4">
						<label class="form-label" for="short_title_2">
							Short Title 2 <span class="text-danger">*</span>
						</label>
						<input type="text" name="short_title_2" id="short_title_2" class="form-control" value="{{@$home_content->short_title_2}}" placeholder="Enter Title" required="">
					</div>
					<div class="col-md-4">
						<label class="form-label" for="formFile5">
							Image 5 <span class="text-danger">*</span>
						</label>
						<div class="custom-file">
							<input type="file" class="form-control custom-file-input" type="file" id="formFile5" name="image_5" accept="image/*" @if(empty(@$home_content->image_5)) required="" @endif>
						</div>
						<div class="previewholder_5" @if(empty(@$home_content->image_5)) style="display: none;" @endif>
							<img id="digital_signatureimgPreview_5" src="{{url('storage/app/public/content/'.@$home_content->image_5)}}" alt="pic" style="width: 100px;  margin-top: 10px;" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label class="form-label" for="title_3">
							Title 3 <span class="text-danger">*</span>
						</label>
						<input type="text" name="title_3" id="title_3" class="form-control" value="{{@$home_content->title_3}}" placeholder="Enter Title" required="">
					</div>
					<div class="col-md-6">
						<label class="form-label" for="title_3">
							Title 4 <span class="text-danger">*</span>
						</label>
						<input type="text" name="title_5" id="title_5" class="form-control" value="{{@$home_content->title_5}}" placeholder="Enter Title" required="">
					</div>
				</div>
				{{-- <div class="col-md-12">
					<label class="form-label">
						Content <span class="text-danger">*</span>
					</label>
					<textarea class="form-control" name="content" id="content">{{@$home_content->content}}</textarea>
				</div> --}}
				{{-- <div class="col-md-12">
					<label for="content" class="error" style="display: none;"></label>
				</div> --}}
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
<script src="//cdn.ckeditor.com/4.19.0/standard/ckeditor.js"></script>
<script>
	$(document).ready(function(){
		$('#editForm').validate({
			// ignore: [],
			// rules:{
			// 	content:{
			// 		ckrequired:true
			// 	},
			// }
		});

		$("#formFile1").change(function () {
			const file = this.files[0];
			if (file) {
				let reader = new FileReader();
				reader.onload = function (event) {
					$("#digital_signatureimgPreview_1").attr("src", event.target.result);
					$('.previewholder_1').show();
				};
				reader.readAsDataURL(file);
			}
		});
		$("#formFile2").change(function () {
			const file = this.files[0];
			if (file) {
				let reader = new FileReader();
				reader.onload = function (event) {
					$("#digital_signatureimgPreview_2").attr("src", event.target.result);
					$('.previewholder_2').show();
				};
				reader.readAsDataURL(file);
			}
		});
		$("#formFile3").change(function () {
			const file = this.files[0];
			if (file) {
				let reader = new FileReader();
				reader.onload = function (event) {
					$("#digital_signatureimgPreview_3").attr("src", event.target.result);
					$('.previewholder_3').show();
				};
				reader.readAsDataURL(file);
			}
		});
		$("#formFile4").change(function () {
			const file = this.files[0];
			if (file) {
				let reader = new FileReader();
				reader.onload = function (event) {
					$("#digital_signatureimgPreview_4").attr("src", event.target.result);
					$('.previewholder_4').show();
				};
				reader.readAsDataURL(file);
			}
		});
		$("#formFile5").change(function () {
			const file = this.files[0];
			if (file) {
				let reader = new FileReader();
				reader.onload = function (event) {
					$("#digital_signatureimgPreview_5").attr("src", event.target.result);
					$('.previewholder_5').show();
				};
				reader.readAsDataURL(file);
			}
		});
	})
	CKEDITOR.replace( 'content' );
	jQuery.validator.addMethod("ckrequired", function (value, element) {  
		var idname = $(element).attr('id');  
		var editor = CKEDITOR.instances[idname];  
		var ckValue = GetTextFromHtml(editor.getData()).replace(/<[^>]*>/gi, '').trim();  
		if (ckValue.length === 0) {  
			$(element).val(ckValue);  
		} else {   
			$(element).val(editor.getData());  
		}  
		return $(element).val().length > 0;  
	}, "This field is required");  

	function GetTextFromHtml(html) {  
		var dv = document.createElement("DIV");  
		dv.innerHTML = html;  
		return dv.textContent || dv.innerText || "";  
	}  
</script>

@endpush
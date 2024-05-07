@extends('admin.layouts.app')
@section('title', 'Scorecard Content Management')
@section('content')
<section class="content-header">
	<h1>Content Management
		<small>Scorecard Content</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{route('admin.content.management',@$page)}}"><i class="fa fa-file"></i>Scorecard Content Management</a></li>
		<li class="active">Scorecard Content</li>
	</ol>
</section>
<section class="content">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Scorecard Content</h3>
		</div>
		<form id="editForm" action="{{route('admin.store.scorecard.content.management')}}" method="post" enctype="multipart/form-data" class="bg-white">
			@csrf
			<div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<label class="form-label" for="banner_title">
							Banner Title <span class="text-danger">*</span>
						</label>
						<input type="text" name="banner_title" id="banner_title" class="form-control" value="{{@$scorecard_content->banner_title}}" placeholder="Enter Title" required="">
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label class="form-label" for="formFile1">
							Banner Image <span class="text-danger">*</span>
						</label>
						<div class="custom-file">
							<input type="file" class="form-control custom-file-input" type="file" id="formFile1" name="banner_image" accept="image/*" @if(empty(@$scorecard_content->banner_image)) required="" @endif>
						</div>
						<div class="previewholder_1" @if(empty(@$scorecard_content->banner_image)) style="display: none;" @endif>
							<img id="digital_signatureimgPreview_1" src="{{url('storage/app/public/content/'.@$scorecard_content->banner_image)}}" alt="pic" style="width: 40px;  margin-top: 10px;" />
						</div>
						{{--  <input type="text" name="title_1" id="title_1" class="form-control" value="{{@$scorecard_content->title_1}}" placeholder="Enter Title" required="">  --}}
					</div>
					<div class="col-md-6">
						<label class="form-label" for="banner_short_description">
							Banner Short Description <span class="text-danger">*</span>
						</label>
                        <textarea class="form-control ckeditor ckrequired" name="banner_short_description" id="banner_short_description">{!! @$scorecard_content->banner_short_description !!}</textarea>
						{{--  <input type="text" name="short_title_1" id="short_title_1" class="form-control" value="{{@$scorecard_content->short_title_1}}" placeholder="Enter Title" required="">  --}}
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label class="form-label" for="banner_form_title">
							Banner Form Title <span class="text-danger">*</span>
						</label>
                        <input type="text" name="banner_form_title" id="banner_form_title" class="form-control" value="{{@$scorecard_content->banner_form_title}}" placeholder="Enter Title" required="">
					</div>
					<div class="col-md-6">
						<label class="form-label" for="banner_form_description">
							banner_form_description <span class="text-danger">*</span>
						</label>
                        <textarea class="form-control required" name="banner_form_description" id="banner_form_description">{!! @$scorecard_content->banner_form_description !!}</textarea>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label class="form-label" for="formFile2">
							Section Image <span class="text-danger">*</span>
						</label>
						<div class="custom-file">
							<input type="file" class="form-control custom-file-input" type="file" id="formFile2" name="section_image" accept="image/*" @if(empty(@$scorecard_content->section_image)) required="" @endif>
						</div>
						<div class="previewholder_2" @if(empty(@$scorecard_content->section_image)) style="display: none;" @endif>
							<img id="digital_signatureimgPreview_2" src="{{url('storage/app/public/content/'.@$scorecard_content->section_image)}}" alt="pic" style="width: 40px;  margin-top: 10px;" />
						</div>
					</div>
					<div class="col-md-6">
						<label class="form-label" for="section_title">
							Section Title <span class="text-danger">*</span>
						</label>
						<input type="text" name="section_title" id="section_title" class="form-control" value="{{@$scorecard_content->section_title}}" placeholder="Enter Title" required="">
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label class="form-label" for="section_description">
							section_description <span class="text-danger">*</span>
						</label>
                        <textarea class="form-control ckeditor ckrequired" name="section_description" id="section_description">{!! @$scorecard_content->section_description !!}</textarea>
						{{--  <input type="text" name="image_title_3" id="image_title_3" class="form-control" value="{{@$scorecard_content->image_title_3}}" placeholder="Enter Title" required="">  --}}
					</div>
					<div class="col-md-6">
						<label class="form-label" for="gird_section_title">
							gird_section_title <span class="text-danger">*</span>
						</label>
						<input type="text" name="gird_section_title" id="gird_section_title" class="form-control" value="{{@$scorecard_content->gird_section_title}}" placeholder="Enter Title" required="">
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label class="form-label" for="gird_title_1">
							Gird Title 1 <span class="text-danger">*</span>
						</label>
						<input type="text" name="gird_title_1" id="gird_title_1" class="form-control" value="{{@$scorecard_content->gird_title_1}}" placeholder="Enter Title" required="">
					</div>
					<div class="col-md-6">
						<label class="form-label" for="gird_short_description_1">
							Gird Short Description 1 <span class="text-danger">*</span>
						</label>
                        <textarea class="form-control required" name="gird_short_description_1" id="gird_short_description_1">{!! @$scorecard_content->gird_short_description_1 !!}</textarea>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label class="form-label" for="formFile3">
							gird_image_1 <span class="text-danger">*</span>
						</label>
						<div class="custom-file">
							<input type="file" class="form-control custom-file-input" type="file" id="formFile3" name="gird_image_1" accept="image/*" @if(empty(@$scorecard_content->gird_image_1)) required="" @endif>
						</div>
						<div class="previewholder_3" @if(empty(@$scorecard_content->gird_image_1)) style="display: none;" @endif>
							<img id="digital_signatureimgPreview_3" src="{{url('storage/app/public/content/'.@$scorecard_content->gird_image_1)}}" alt="pic" style="width: 40px;  margin-top: 10px;" />
						</div>
					</div>
					<div class="col-md-6">
						<label class="form-label" for="gird_title_2">
							Gird Title 2 <span class="text-danger">*</span>
						</label>
						<input type="text" name="gird_title_2" id="gird_title_2" class="form-control" value="{{@$scorecard_content->gird_title_2}}" placeholder="Enter Title" required="">
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label class="form-label" for="gird_short_description_2">
							Gird Short Description 2 <span class="text-danger">*</span>
						</label>
                        <textarea class="form-control required" name="gird_short_description_2" id="gird_short_description_2">{!! @$scorecard_content->gird_short_description_2 !!}</textarea>
					</div>
                    <div class="col-md-6">
						<label class="form-label" for="formFile4">
							gird_image_2 <span class="text-danger">*</span>
						</label>
						<div class="custom-file">
							<input type="file" class="form-control custom-file-input" type="file" id="formFile4" name="gird_image_2" accept="image/*" @if(empty(@$scorecard_content->gird_image_2)) required="" @endif>
						</div>
						<div class="previewholder_4" @if(empty(@$scorecard_content->gird_image_2)) style="display: none;" @endif>
							<img id="digital_signatureimgPreview_4" src="{{url('storage/app/public/content/'.@$scorecard_content->gird_image_2)}}" alt="pic" style="width: 40px;  margin-top: 10px;" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label class="form-label" for="gird_title_3">
							Gird Title 3 <span class="text-danger">*</span>
						</label>
						<input type="text" name="gird_title_3" id="gird_title_3" class="form-control" value="{{@$scorecard_content->gird_title_3}}" placeholder="Enter Title" required="">
					</div>
					<div class="col-md-6">
						<label class="form-label" for="gird_short_description_3">
							Gird Short Description 3 <span class="text-danger">*</span>
						</label>
                        <textarea class="form-control required" name="gird_short_description_3" id="gird_short_description_3">{!! @$scorecard_content->gird_short_description_3 !!}</textarea>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label class="form-label" for="formFile5">
							gird_image_3 <span class="text-danger">*</span>
						</label>
						<div class="custom-file">
							<input type="file" class="form-control custom-file-input" type="file" id="formFile5" name="gird_image_3" accept="image/*" @if(empty(@$scorecard_content->gird_image_3)) required="" @endif>
						</div>
						<div class="previewholder_5" @if(empty(@$scorecard_content->gird_image_3)) style="display: none;" @endif>
							<img id="digital_signatureimgPreview_5" src="{{url('storage/app/public/content/'.@$scorecard_content->gird_image_3)}}" alt="pic" style="width: 100px;  margin-top: 10px;" />
						</div>
					</div>
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

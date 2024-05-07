@extends('admin.layouts.app')
@section('title', 'Content Management')
@section('content')
<section class="content-header">
	<h1>Content Management
		<small>
			@if(@$page == 'about')
			About Us
			@elseif(@$page == 'terms')
			Terms & Conditions
			@else
			Privacy Policy
			@endif
		Content</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{route('admin.content.management',@$page)}}"><i class="fa fa-file"></i> Content Management</a></li>
		<li class="active">
			@if(@$page == 'about')
			About Us
			@elseif(@$page == 'terms')
			Terms & Conditions
			@else
			Privacy Policy
			@endif 
		Content</li>
	</ol>
</section>
<section class="content">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">
				@if(@$page == 'about')
				About Us
				@elseif(@$page == 'terms')
				Terms & Conditions
				@else
				Privacy Policy
				@endif 
			Content</h3>
		</div>
		<form id="editForm" action="{{route('admin.content.store')}}" method="post" enctype="multipart/form-data" class="bg-white">
			@csrf
			<input type="hidden" name="page" value="{{@$page}}">
			<div class="box-body">
				<div class="col-md-6">
					<label class="form-label">
						Page Name <span class="text-danger">*</span>
					</label>
					<input type="text" name="page_name" id="page_name" class="form-control" value="{{@$content->name}}">
				</div>
				@if(@$page == 'about')
				<div class="col-md-6">
					<label class="form-label">
						Image
					</label>
					<div class="custom-file">
						<input type="file" class="form-control custom-file-input" type="file" id="formFile" name="image" accept="image/*">
						{{-- <label class="custom-file-label" for="formFile">Choose file</label> --}}
					</div>
					<div class="previewholder" @if(empty(@$content->image)) style="display: none;" @endif>
						<img id="digital_signatureimgPreview" src="{{url('storage/content/'.@$content->image)}}" alt="pic" style="width: 100px;  margin-top: 10px;" />
					</div>
				</div>
				@endif
				<div class="col-md-12">
					<label class="form-label">
						Content <span class="text-danger">*</span>
					</label>
					<textarea class="form-control" name="content" id="content">{{@$content->content}}</textarea>
				</div>
				<div class="col-md-12">
					<label for="content" class="error" style="display: none;"></label>
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
			ignore: [],
			rules:{
				content:{
					ckrequired:true
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
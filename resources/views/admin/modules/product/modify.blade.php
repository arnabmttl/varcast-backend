@section('title', 'Product Management')
@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <h1>Product Management<small>@if(!empty(@$product_data)) Edit Product @else Add Product @endif</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class=""><a href="{{route('admin.banner.management')}}">Product Management</a></li>
        @if(!empty(@$product_data))
        <li class="active">Edit Product</li>
        @else
        <li class="active">Add Product</li>
        @endif
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">@if(!empty(@$product_data)) Edit Product @else Add Product @endif</h3>

            <div class="box-tools pull-right">
                {{-- Tools --}}
            </div>
        </div>
        <form action="{{ route('admin.product.store') }}" method="POST" id="bannerForm" enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                <div class="row">
                    <input type="hidden" name="rowid" value="{{@$product_data->id}}">
                    
                    <div class="form-group col-md-4">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="title" id="title" required value="{{!empty(@$product_data->title) ?  @$product_data->title : old('title')}}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="formFile">Image <span class="text-danger">*</span></label>
                        <input type="file" name="image" id="formFile" class="form-control custom-file-input">
                        <div class="previewholder" @if(empty(@$product_data->image)) style="display: none;" @endif>
                            <img id="digital_signatureimgPreview" src="{{url('storage/app/public/product/'.@$product_data->image)}}" alt="pic" style="width: 100px;  margin-top: 10px;" />
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="is_order">Display Order </label>
                        <input type="text" class="form-control numberonly" name="is_order" id="is_order" value="{{ !empty(@$product_data->is_order) ?  @$product_data->is_order : (@$is_order_count + 1) }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="model_number">Model Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="model_number" id="model_number" value="{{ !empty(@$product_data->model_number) ?  @$product_data->model_number : old('model_number') }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="make">Make <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="make" id="make" value="{{ !empty(@$product_data->make) ?  @$product_data->make : old('make') }}">
                    </div>

                    <div class="form-group col-md-12">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="5">{{!empty(@$product_data->description) ? @$product_data->description : old('description') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <input type="submit" value="Submit" class="btn btn-md btn-primary">
                <button type="button" class="btn btn-md btn-info" onclick="location.href='{{ route('admin.product.management') }}'">Back</button>
            </div>
        </form>
    </div>
</section>
@endsection

@push('script')
<script>
    $('#bannerForm').validate({
        rules: {
            title: {
                required: true,
            },
            image: {
                @if(!empty(@$product_data->image))
                required: false,
                @else
                required: true,
                @endif
            },
            model_number: {
                required: true,
            },
            make: {
                required: true,
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
</script>
@endpush

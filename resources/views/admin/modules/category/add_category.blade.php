@section('title', 'Category')
@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <h1>Category Management<small>@if(!empty(@$category_data)) Edit Category @else Add Category @endif</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class=""><a href="{{route('admin.category')}}">Category Management</a></li>
        @if(!empty(@$category_data))
        <li class="active">Edit Category</li>
        @else
        <li class="active">Add Category</li>
        @endif
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">@if(!empty(@$category_data)) Edit Category @else Add Category @endif</h3>

            <div class="box-tools pull-right">
                {{-- Tools --}}
            </div>
        </div>
        <form action="{{ route('admin.category.store') }}" method="POST" id="categoryForm" enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                <div class="row">
                    <input type="hidden" name="rowid" value="{{@$category_data->_id}}">
                    {{-- <div class="form-group col-md-6">
                        <label for="parent_id">Parent Category <span class="text-danger">( {{ "Leave this field for parent category" }} )</span></label>
                        <select name="parent_id" class="form-control" style="width: 100%" id="parent_category" @if(!empty(@$category_data)) disabled="" @endif>
                            <option value="">Select Category</option>
                            @if(@$category->isNotEmpty())
                            @foreach (@$category as $row)
                            <option value="{{ $row->_id }}">{{ $row->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="parent_sub_category">Sub Category <span class="text-danger">( {{ "Leave this field for sub category" }} )</span></label>
                        <select name="parent_sub_category_id" class="form-control" style="width: 100%" id="parent_sub_category" @if(!empty(@$category_data)) disabled="" @endif>
                            <option value="">Select Sub Category</option>
                        </select>
                    </div> --}}
                    <div class="form-group col-md-6">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="name" required value="{{@$category_data->name}}" placeholder="Name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="formFile">Image </label>
                        <input type="file" name="image" id="formFile" class="form-control custom-file-input">
                        <div class="previewholder" @if(empty(@$category_data->image)) style="display: none;" @endif>
                            <img id="digital_signatureimgPreview" src="{{url('storage/category/'.@$category_data->image)}}" alt="pic" style="width: 100px;  margin-top: 10px;" />
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" placeholder="Description">{{@$category_data->description}}</textarea>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <input type="submit" value="Submit" class="btn btn-md btn-primary">
                <button type="button" class="btn btn-md btn-info" onclick="location.href='{{ route('admin.category') }}'">Back</button>
            </div>
        </form>
    </div>
</section>
@endsection

@push('script')
<script>
    $('#categoryForm').validate({
        rules: {
            name: {
                required: true,
            }
        }
    });
    {{-- $('#parent_category').select2({
        placeholder: "Please select category",
        allowClear: true,
        width: '100%'
    });
    $('select[name="parent_id"]').change(function(){
        var catIds = $(this).val();
        $.ajax({
            url:"{{route('admin.get.sub.category')}}",
            type:"POST",
            data:{
                '_token':"{{@csrf_token()}}",
                'category_id': catIds
            },
            success:function(responce){
                if(responce.data.status == 'success'){
                    $('#parent_sub_category').html(responce.data.html);
                }
                else{
                    $('#parent_sub_category').html('<option value="">Select Sub Category</option>');
                }
            },
            error: function(xhr){
                console.log(xhr);
            }
        })
    })
    $('#parent_sub_category').select2({
        placeholder: "Please select sub category",
        allowClear: true,
        width: '100%'
    });
    @if(!empty(@$category_data))
    @if(@$category_data->level == '3')
    $('#parent_category').val({{@$category_data->parentCategory->parentCategory->_id}}).trigger('change');
    setTimeout(() => {
        $('#parent_sub_category').val({{@$category_data->parentCategory->_id}}).trigger('change');
    }, 1000);
    @elseif(@$category_data->level == '2')
    $('#parent_category').val({{@$category_data->parentCategory->_id}}).trigger('change');
    @endif
    @endif --}}
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

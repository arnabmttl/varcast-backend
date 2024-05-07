@section('title', 'Tag Management')
@extends('admin.layouts.app')
@section('content')
<section class="content-header">
    <h1>Tag Management<small>@if(!empty(@$tag_data)) Edit Tag @else Add Tag @endif</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class=""><a href="{{route('admin.tag.index')}}">Tag Management</a></li>
        @if(!empty(@$tag_data))
        <li class="active">Edit Tag</li>
        @else
        <li class="active">Add Tag</li>
        @endif
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">@if(!empty(@$tag_data)) Edit Tag @else Add Tag @endif</h3>

            <div class="box-tools pull-right">
                {{-- Tools --}}
            </div>
        </div>
        <form action="{{ route('admin.tag.store') }}" method="POST" id="coinPriceForm" enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                <div class="row">
                    <input type="hidden" name="rowid" value="{{@$tag_data->_id}}">
                    <div class="form-group col-md-4">
                        <label for="name">Tag <span class="text-danger">*</span></label>
                        <input type="text" class="form-control alphaonly" name="name" id="name" required value="{{@$tag_data->name}}" placeholder="Tag">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="is_order">Display Order </label>
                        <input type="text" class="form-control numberonly" name="is_order" id="is_order" value="{{ !empty(@$tag_data->is_order) ?  @$tag_data->is_order : (@$is_order_count + 1) }}">
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <input type="submit" value="Submit" class="btn btn-md btn-primary">
                <button type="button" class="btn btn-md btn-info" onclick="location.href='{{ route('admin.tag.index') }}'">Back</button>
            </div>
        </form>
    </div>
</section>
@endsection

@push('script')
<script>
    $('#coinPriceForm').validate({
        rules: {
            name: {
                required: true,
            },
            usage_coin: {
                required: true,
                digits:true,
            },
        }
    });
    // $("#formFile").change(function () {
    //     const file = this.files[0];
    //     if (file) {
    //         let reader = new FileReader();
    //         reader.onload = function (event) {
    //             $("#digital_signatureimgPreview").attr("src", event.target.result);
    //             $('.previewholder').show();
    //         };
    //         reader.readAsDataURL(file);
    //     }
    // });
    
</script>
@endpush

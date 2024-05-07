@section('title', 'My Music')
@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <h1>My Music Management<small>@if(!empty(@$my_music_data)) Edit Music @else Add Music @endif</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class=""><a href="{{route('admin.banner.management')}}">My Music Management</a></li>
        @if(!empty(@$my_music_data))
        <li class="active">Edit Music</li>
        @else
        <li class="active">Add Music</li>
        @endif
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">@if(!empty(@$my_music_data)) Edit Music @else Add Music @endif</h3>

            <div class="box-tools pull-right">
                {{-- Tools --}}
            </div>
        </div>
        <form action="{{ route('admin.my.music.store') }}" method="POST" id="bannerForm" enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                <div class="row">
                    <input type="hidden" name="rowid" value="{{@$my_music_data->_id}}">
                    
                    <div class="form-group col-md-4">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="name" required value="{{@$my_music_data->name}}" placeholder="Name">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="author">Author <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="author" id="author" required value="{{@$my_music_data->author}}" placeholder="Author">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="is_order">Display Order </label>
                        <input type="text" class="form-control numberonly" name="is_order" id="is_order" value="{{ !empty(@$my_music_data->is_order) ?  @$my_music_data->is_order : (@$is_order_count + 1) }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="formFile">Thumbnail Image <span class="text-danger">*</span></label>
                        <input type="file" name="thumbnail_image" id="formFile" class="form-control custom-file-input">
                        <div class="previewholder" @if(empty(@$my_music_data->thumbnail_image)) style="display: none;" @endif>
                            <img id="digital_signatureimgPreview" src="{{@$my_music_data->full_path_thmb_image}}" alt="pic" style="width: 100px;  margin-top: 10px;" />
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="MediaFile">File <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="MediaFile" class="form-control custom-file-input" accept="audio/*">
                        <small>Please Upload : mp3,mpeg file</small>
                        <div @if(empty(@$my_music_data->file)) style="display: none;" @endif>
                            {{-- <img id="digital_signatureimgPreview" src="{{@$my_music_data->full_path_file}}" alt="pic" style="width: 100px;  margin-top: 10px;" /> --}}
                            <audio controls muted>
                                <source src="{{@$my_music_data->full_path_thmb_image}}" type="audio/ogg">
                                <source src="{{@$my_music_data->full_path_file}}" type="audio/mpeg">
                            </audio>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <input type="submit" value="Submit" class="btn btn-md btn-primary">
                <button type="button" class="btn btn-md btn-info" onclick="location.href='{{ route('admin.my.music.index') }}'">Back</button>
            </div>
        </form>
    </div>
</section>
@endsection

@push('script')
<script>
    $('#bannerForm').validate({
        rules: {
            name: {
                required: true,
            },
            author: {
                required: true,
            },
            thumbnail_image: {
                @if(!empty(@$my_music_data->thumbnail_image))
                required: false,
                @else
                required: true,
                @endif
            },
            file: {
                @if(!empty(@$my_music_data->file))
                required: false,
                @else
                required: true,
                @endif
                // extension: "mp3|mpeg|mp4",
                // accept: "audio/*"
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

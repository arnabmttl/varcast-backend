@section('title', 'Emoji Management')
@extends('admin.layouts.app')
@section('content')
<section class="content-header">
    <h1>Emoji Management<small>@if(!empty(@$emoji_data)) Edit Emoji @else Add Emoji @endif</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class=""><a href="{{route('admin.emoji.index')}}">Emoji Management</a></li>
        @if(!empty(@$emoji_data))
        <li class="active">Edit Emoji</li>
        @else
        <li class="active">Add Emoji</li>
        @endif
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">@if(!empty(@$emoji_data)) Edit Emoji @else Add Emoji @endif</h3>

            <div class="box-tools pull-right">
                {{-- Tools --}}
            </div>
        </div>
        <form action="{{ route('admin.emoji.store') }}" method="POST" id="coinPriceForm" enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                <div class="row">
                    <input type="hidden" name="rowid" value="{{@$emoji_data->_id}}">
                    <div class="form-group col-md-4">
                        <label for="emoji">Emoji <span class="text-danger">*</span></label>
                        <input type="text" class="form-control alphaonly" name="emoji" id="emoji" required value="{{@$emoji_data->emoji}}" placeholder="Emoji">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="usage_coin">Coin Required <span class="text-danger">*</span></label>
                        <input type="text" class="form-control numberOnly" name="usage_coin" id="usage_coin" required value="{{@$emoji_data->usage_coin}}" placeholder="Price">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="is_order">Display Order </label>
                        <input type="text" class="form-control numberonly" name="is_order" id="is_order" value="{{ !empty(@$emoji_data->is_order) ?  @$emoji_data->is_order : (@$is_order_count + 1) }}">
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <input type="submit" value="Submit" class="btn btn-md btn-primary">
                <button type="button" class="btn btn-md btn-info" onclick="location.href='{{ route('admin.emoji.index') }}'">Back</button>
            </div>
        </form>
    </div>
</section>
@endsection

@push('script')
<script>
    $('#coinPriceForm').validate({
        rules: {
            emoji: {
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

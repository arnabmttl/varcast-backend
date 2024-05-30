@section('title', 'Gift Management')
@extends('admin.layouts.app')
@section('content')
<section class="content-header">
    <h1>Gift Management<small>@if(!empty($data)) Edit Gift @else Add Gift @endif</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class=""><a href="{{route('admin.coin.price.index')}}">Gift Management</a></li>
        @if(!empty($data))
        <li class="active">Edit Gift</li>
        @else
        <li class="active">Add Gift</li>
        @endif
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">@if(!empty($data)) Edit Gift @else Add Gift @endif</h3>

            <div class="box-tools pull-right">
                {{-- Tools --}}
            </div>
        </div>
        <form action="{{ route('admin.gift.store') }}" method="POST" id="giftForm" enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                <div class="row">
                    <input type="hidden" name="rowid" @if(!empty($data)) value="{{$data->_id}}" @endif>
                    <div class="form-group col-md-4">
                        <label for="gift_name">Gift Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control alphaonly" name="gift_name" id="gift_name" @if(!empty($data)) value="{{$data->gift_name}}" @else value="{{ old('gift_name') }}" @endif placeholder="Gift name">
                        @error('gift_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="plan_coin">Icon </label>
                        <input type="file" class="form-control " name="image" id="image"  placeholder="Plan Coin" accept="image/*">
                        @error('image')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="coin_value">Coin Value <span class="text-danger">*</span></label>
                        <input type="text" class="form-control numberOnly" name="coin_value" id="coin_value" @if(!empty($data))  value="{{$data->coin_value}}" @else value="{{ old('coin_value') }}" @endif placeholder="Coin Value" maxlength="5">
                        @error('coin_value')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                </div>
            </div>
            <div class="box-footer">
                <input type="submit" value="Submit" class="btn btn-md btn-primary">
                <button type="button" class="btn btn-md btn-info" onclick="location.href='{{ route('admin.gift.index') }}'">Back</button>
            </div>
        </form>
    </div>
</section>
@endsection

@push('script')
<script>
    $('#giftForm').validate({
        rules: {
            gift_name: {
                required: true
            },
            // image: {
            //     required: true
            // },
            // coin_value: {
            //     required: true,
            //     digits:true,
            // }
        }
    });
    
    jQuery(".numberOnly").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
        // Allow: Ctrl+A, Command+A
        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
        // Allow: home, end, left, right, down, up
        (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
</script>
@endpush

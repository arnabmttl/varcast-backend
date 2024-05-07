@section('title', 'Coin Plan')
@extends('admin.layouts.app')
@section('content')
<section class="content-header">
    <h1>Coin Plan Management<small>@if(!empty(@$coin_price_data)) Edit Coin Plan @else Add Coin Plan @endif</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class=""><a href="{{route('admin.coin.price.index')}}">Coin Plan Management</a></li>
        @if(!empty(@$coin_price_data))
        <li class="active">Edit Coin Plan</li>
        @else
        <li class="active">Add Coin Plan</li>
        @endif
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">@if(!empty(@$coin_price_data)) Edit Category @else Add Category @endif</h3>

            <div class="box-tools pull-right">
                {{-- Tools --}}
            </div>
        </div>
        <form action="{{ route('admin.coin.price.store') }}" method="POST" id="coinPriceForm" enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                <div class="row">
                    <input type="hidden" name="rowid" value="{{@$coin_price_data->_id}}">
                    <div class="form-group col-md-6">
                        <label for="plan_name">Plan Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control alphaonly" name="plan_name" id="plan_name" required value="{{@$coin_price_data->plan_name}}" placeholder="Plan Name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="plan_coin">Plan Coin <span class="text-danger">*</span></label>
                        <input type="text" class="form-control numberOnly" name="plan_coin" id="plan_coin" required value="{{@$coin_price_data->from_coin}}" placeholder="Plan Coin">
                    </div>
                    {{-- <div class="form-group col-md-4">
                        <label for="to_coin">To Coin <span class="text-danger">*</span></label>
                        <input type="text" class="form-control numberOnly" name="to_coin" id="to_coin" required value="{{@$coin_price_data->to_coin}}" placeholder="To Coin">
                    </div> --}}
                    <div class="form-group col-md-6">
                        <label for="price">Regular Price <span class="text-danger">*</span></label>
                        <input type="text" class="form-control numberOnly" name="price" id="price" required value="{{@$coin_price_data->price}}" placeholder="Regular Price">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="sale_price">Sale Price </label>
                        <input type="text" class="form-control numberOnly" name="sale_price" id="sale_price" required value="{{@$coin_price_data->sale_price}}" placeholder="Sale Price">
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <input type="submit" value="Submit" class="btn btn-md btn-primary">
                <button type="button" class="btn btn-md btn-info" onclick="location.href='{{ route('admin.coin.price.index') }}'">Back</button>
            </div>
        </form>
    </div>
</section>
@endsection

@push('script')
<script>
    $('#coinPriceForm').validate({
        rules: {
            from_coin: {
                required: true,
                digits:true,
            },
            to_coin: {
                required: true,
                digits:true,
            },
            price: {
                required: true,
                number:true,
            },
            sale_price: {
                required: false,
                number:true,
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

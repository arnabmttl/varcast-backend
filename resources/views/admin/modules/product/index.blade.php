@section('title', 'Product Management')
@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <h1>Product Management</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Product Management</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    {{-- <h3 class="box-title">All Category</h3> --}}
                    <div class="row">
                        <form>
                            <div class="col-md-3">
                                <label for="keyword">Keyword</label>
                                <input type="text" name="keyword" class="form-control" placeholder="Keyword" value="{{@request()->keyword}}" id="keyword">
                            </div>
                            <div class="col-md-2">
                                <label for="from_date">From Date</label>
                                <input type="text" name="from_date" class="form-control" placeholder="From Date" value="{{@request()->from_date}}" id="from_date">
                            </div>
                            <div class="col-md-2">
                                <label for="to_date">To Date</label>
                                <input type="text" name="to_date" class="form-control" placeholder="To Date" value="{{@request()->to_date}}" id="to_date">
                            </div>
                            <div class="col-md-2">
                                <label for="status">Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="">All</option>
                                    <option value="A" @if(@request()->status == 'A') selected="" @endif>Active</option>
                                    <option value="I" @if(@request()->status == 'I') selected="" @endif>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-3" style="margin-top: 26px;">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Search</button>
                                <a href="{{route('admin.product.management')}}" class="btn btn-info btn-sm"><i class="fa fa-refresh"></i> Reset</a>
                                <a href="{{route('admin.product.add')}}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add New</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="box-body">
                    <table id="my-datatable" class="table table-bordered table-striped" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Model number</th>
                                <th>Make</th>
                                <th>Display Order</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (@$product_list->isNotEmpty())
                            @foreach (@$product_list as $product)
                            <tr>
                                <td>{{ @$product->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{url('storage/app/public/product/'.@$product->image)}}" target="_blank"><img src="{{url('storage/app/public/product/'.@$product->image)}}" width="80px"></a>
                                </td>
                                <td>
                                    {{ @$product->title }}
                                </td>
                                <td>
                                    {{ @$product->model_number }}
                                </td>
                                <td>
                                    {{ @$product->make }}
                                </td>
                                <td>
                                    {{ @$product->is_order }}
                                </td>
                                <td>
                                    {{ substr(@$product->description,0,100) }}@if(substr(@$product->description,100))...@endif
                                </td>
                                <td>
                                    @if (@$product->status=='A')
                                    <span class="label label-success">Active</span>
                                    @else
                                    <span class="label label-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @if (@$product->status=='A')
                                    <a href="javascript:void(0);" class="btn btn-xs btn-danger" title="Inactive" onclick="statusChange('{{@$product->id}}','{{@$product->status}}');"><i class="fa fa-ban"></i></a>
                                    @else
                                    <a href="javascript:void(0);" class="btn btn-xs btn-success" title="Active" onclick="statusChange('{{@$product->id}}','{{@$product->status}}');"><i class="fa fa-check"></i></a>
                                    @endif
                                    <a href="{{ route('admin.product.edit',@$product->id) }}" class="btn btn-xs btn-info" title="Edit"><i class="fa fa-edit"></i></a>
                                    <a href="javascript:void(0);" class="btn btn-xs btn-danger" title="Delete" onclick="CategoryDelete('{{@$product->id}}');"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="9" style="text-align: center;">Records not found</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="pull-right">
                    @if(@$product_list->isNotEmpty())
                    {{ @$product_list->appends(Request::except('page'))->links("pagination::bootstrap-4") }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script type="text/javascript">
    $(document).ready(function(){
        $("#from_date").datepicker({
            numberOfMonths: 1,
            onSelect: function(selected) {
                $("#to_date").datepicker("option","minDate", selected);
            },
            dateFormat: "dd/mm/yy"
        });
        $("#to_date").datepicker({ 
            numberOfMonths: 1,
            onSelect: function(selected) {
                $("#from_date").datepicker("option","maxDate", selected)
            },
            dateFormat: "dd/mm/yy"
        });  
    });
    function statusChange(bannerId,status){
        if(status == 'I'){
            $msg = "Active this product.";
        }
        else if(status == 'A'){
            $msg = "Inactive this product.";
        }
        Swal.fire({
            title: 'Are you sure?',
            text: $msg,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                location.replace("{{url('admin/product-status')}}/"+bannerId);
            }
        });
    }
    function CategoryDelete(bannerids){
        Swal.fire({
            title: 'Are you sure?',
            text: "Delete this banner.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                location.replace("{{url('admin/delete-product')}}/"+bannerids);
            }
        });
    }
</script>
@endpush

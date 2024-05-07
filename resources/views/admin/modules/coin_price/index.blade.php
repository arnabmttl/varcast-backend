@section('title', 'Coin Plan')
@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <h1>Coin Plan Management</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Coin Plan Management</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            {{-- <h3 class="box-title">All Category</h3> --}}
            <div class="row">
                <form>
                    <div class="col-md-3">
                        <label for="keyword">Keyword</label>
                        <input type="text" name="keyword" class="form-control" placeholder="Keyword" value="{{@request()->keyword}}" id="keyword">
                    </div>
                    <div class="col-md-3" style="margin-top: 26px;">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Search</button>
                        <a href="{{route('admin.coin.price.index')}}" class="btn btn-info btn-sm"><i class="fa fa-refresh"></i> Reset</a>
                        <a href="{{route('admin.coin.price.add')}}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add New</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="box-body">
            <table id="my-datatable" class="table table-bordered table-striped" style="width: 100%">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Plan Name</th>
                        <th>Plan Coin</th>
                        <th>Regular Price</th>
                        <th>Sale Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (@$coin_prices->isNotEmpty())
                    @foreach (@$coin_prices as $row)
                    <tr>
                        <td>{{ @$loop->index+1 }}</td>
                        <td>{{@$row->plan_name}}</td>
                        <td>{{@$row->from_coin}}</td>
                        <td class="text-right">{{@$row->price}}</td>
                        <td class="text-right">{{@$row->sale_price}}</td>
                        <td>
                            @if (@$row->status=='A')
                            <span class="label label-success">Active</span>
                            @else
                            <span class="label label-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if (@$row->status=='A')
                            <a href="javascript:void(0);" class="btn btn-xs btn-danger" title="Inactive" onclick="statusChange('{{@$row->_id}}','{{@$row->status}}');"><i class="fa fa-ban"></i></a>
                            @else
                            <a href="javascript:void(0);" class="btn btn-xs btn-success" title="Active" onclick="statusChange('{{@$row->_id}}','{{@$row->status}}');"><i class="fa fa-check"></i></a>
                            @endif
                            <a href="{{ route('admin.coin.price.edit',@$row->_id) }}" class="btn btn-xs btn-info" title="Edit"><i class="fa fa-edit"></i></a>
                            <a href="javascript:void(0);" class="btn btn-xs btn-danger" title="Delete" onclick="CategoryDelete('{{@$row->_id}}');"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="6" style="text-align: center;">Records not found</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="pull-right">
            @if(@$coin_prices->isNotEmpty())
            {{ @$coin_prices->appends(Request::except('page'))->links("pagination::bootstrap-4") }}
            @endif
        </div>
    </div>
</section>
@endsection
@push('script')
<script type="text/javascript">
    function statusChange(userId,status){
        if(status == 'I'){
            $msg = "Active this plan coin.";
        }
        else if(status == 'A'){
            $msg = "Inactive this plan coin.";
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
                location.replace("{{url('admin/coin-price/status')}}/"+userId);
            }
        });
    }
    function CategoryDelete(userId){
        Swal.fire({
            title: 'Are you sure?',
            text: "Delete this plan coin.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                location.replace("{{url('admin/coin-price/delete')}}/"+userId);
            }
        });
    }
</script>
@endpush

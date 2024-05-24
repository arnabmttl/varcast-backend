@section('title', 'Gift Management')
@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <h1>Gift Management</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Gift Management</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <div class="row">
                <form>
                    <div class="col-md-3">
                        <label for="keyword">Keyword</label>
                        <input type="text" name="keyword" class="form-control" placeholder="Keyword" value="{{@request()->keyword}}" id="keyword">
                    </div>
                    <div class="col-md-3" style="margin-top: 26px;">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Search</button>
                        <a href="{{route('admin.gift.index')}}" class="btn btn-info btn-sm"><i class="fa fa-refresh"></i> Reset</a>
                        <a href="{{route('admin.gift.add')}}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add New</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="box-body">
            <table id="my-datatable" class="table table-bordered table-striped" style="width: 100%">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Gift Name</th>
                        <th>Icon</th>
                        <th>Coin Value</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($data))
                    @foreach ( $data as $row)
                    <tr>
                        <td>{{ $loop->index+1 }}</td>
                        <td>{{ $row['gift_name']}}</td>
                        <td>
                            @if(!empty($row['icon_image']))
                                <img src="{{ url($row['icon_image']) }}" style="width: 40px;" alt="">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" style="width: 40px;" alt="">
                            @endif

                        </td>
                        <td class="text-right">{{ $row['coin_value']}}</td>
                        <td>
                            @if ( $row['status']=='A')
                            <span class="label label-success">Active</span>
                            @else
                            <span class="label label-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if ($row['status']=='A')
                            <a href="javascript:void(0);" class="btn btn-xs btn-danger" title="Inactive" onclick="statusChange('{{$row['_id']}}','{{$row['status']}}');"><i class="fa fa-ban"></i></a>
                            @else
                            <a href="javascript:void(0);" class="btn btn-xs btn-success" title="Active" onclick="statusChange('{{$row['_id']}}','{{$row['status']}}');"><i class="fa fa-check"></i></a>
                            @endif
                            <a href="{{ route('admin.gift.edit',@$row['_id']) }}" class="btn btn-xs btn-info" title="Edit"><i class="fa fa-edit"></i></a>
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
            
        </div>
    </div>
</section>
@endsection
@push('script')
<script type="text/javascript">
    function statusChange(userId,status){
        if(status == 'I'){
            $msg = "Active this plan gift.";
        }
        else if(status == 'A'){
            $msg = "Inactive this plan gift.";
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
                location.replace("{{url('admin/gift/status')}}/"+userId);
            }
        });
    }
    
</script>
@endpush

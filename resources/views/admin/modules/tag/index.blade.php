@section('title', 'Tag Management')
@extends('admin.layouts.app')
@section('content')
<section class="content-header">
    <h1>Tag Management</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Tag Management</li>
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
                            <div class="col-md-3" style="margin-top: 26px;">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Search</button>
                                <a href="{{route('admin.tag.index')}}" class="btn btn-info btn-sm"><i class="fa fa-refresh"></i> Reset</a>
                                <a href="{{route('admin.tag.add')}}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add New</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <table id="my-datatable" class="table table-bordered table-striped" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Tag</th>
                                <th>Slug</th>
                                <th>Display Order</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (@$tags->isNotEmpty())
                            @foreach (@$tags as $row)
                            <tr>
                                <td>{{ (@$loop->index+1) + (@$tags->perPage() * (@$tags->currentPage() - 1)) }}</td>
                                <td>{{@$row->name}}</td>
                                <td>{{@$row->slug}}</td>
                                <td>{{@$row->is_order}}</td>
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
                                    <a href="{{ route('admin.tag.edit',@$row->_id) }}" class="btn btn-xs btn-info" title="Edit"><i class="fa fa-edit"></i></a>
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
                    @if(@$tags->isNotEmpty())
                    {{ @$tags->appends(Request::except('page'))->links("pagination::bootstrap-4") }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script type="text/javascript">
    function statusChange(userId,status){
        if(status == 'I'){
            $msg = "Active this tag.";
        }
        else if(status == 'A'){
            $msg = "Inactive this tag.";
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
                location.replace("{{url('admin/tag/status')}}/"+userId);
            }
        });
    }
    function CategoryDelete(userId){
        Swal.fire({
            title: 'Are you sure?',
            text: "Delete this tag.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                location.replace("{{url('admin/tag/delete')}}/"+userId);
            }
        });
    }
</script>
@endpush

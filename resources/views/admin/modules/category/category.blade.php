@section('title', 'Category')
@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <h1>Category Management</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Category Management</li>
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
                    {{-- <div class="col-md-3">
                        <label for="status">Is Parent</label>
                        <select class="form-control" name="is_parent" id="is_parent">
                            <option value="">All</option>
                            <option value="Y" @if(@request()->is_parent == 'Y') selected="" @endif>Yes</option>
                            <option value="N" @if(@request()->is_parent == 'N') selected="" @endif>No</option>
                        </select>
                    </div> --}}
                    <div class="col-md-3" style="margin-top: 26px;">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Search</button>
                        <a href="{{route('admin.category')}}" class="btn btn-info btn-sm"><i class="fa fa-refresh"></i> Reset</a>
                        <a href="{{route('admin.category.add')}}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add New</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="box-body">
            <table id="my-datatable" class="table table-bordered table-striped" style="width: 100%">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Category</th>
                        <th>Slug</th>
                        {{-- <th>Sub Category</th> --}}
                        {{-- <th>Sub Sub Category</th> --}}
                        {{-- <th>Level</th> --}}
                        {{-- <th>Is Parent</th> --}}
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (@$category->isNotEmpty())
                    @foreach (@$category as $row)
                    <tr>
                        <td>{{ @$loop->index+1 }}</td>
                        <td>{{@$row->name}}</td>
                        <td>{{@$row->slug}}</td>
                        {{-- @if(@$row->level == '1')
                        <td>
                            {{@$row->name}}
                        </td>
                        <td>
                            --
                        </td>
                        <td>
                            --
                        </td>   
                        @elseif(@$row->level == '2')
                        <td>
                            {{@$row->parentCategory->name}}
                        </td>
                        <td>
                            {{@$row->name}}
                        </td>
                        <td>
                            --
                        </td> 
                        @elseif(@$row->level == '3')
                        <td>
                            {{@$row->parentCategory->parentCategory->name}}
                        </td>
                        <td>
                            {{@$row->parentCategory->name}}
                        </td>
                        <td>
                            {{@$row->name}}
                        </td>          
                        @endif --}}
                        {{-- <td align="center">{{@$row->level}}</td> --}}
                        {{-- <td>
                            @if(@$row->level == '1')
                            Yes
                            @else
                            No
                            @endif
                        </td> --}}
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
                            <a href="{{ route('admin.category.edit',@$row->_id) }}" class="btn btn-xs btn-info" title="Edit"><i class="fa fa-edit"></i></a>
                            <a href="javascript:void(0);" class="btn btn-xs btn-danger" title="Delete" onclick="CategoryDelete('{{@$row->_id}}');"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="5" style="text-align: center;">Records not found</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="pull-right">
            @if(@$category->isNotEmpty())
            {{ @$category->appends(Request::except('page'))->links("pagination::bootstrap-4") }}
            @endif
        </div>
    </div>
</section>
@endsection
@push('script')
<script type="text/javascript">
    function statusChange(userId,status){
        if(status == 'I'){
            $msg = "Active this category.";
        }
        else if(status == 'A'){
            $msg = "Inactive this category.";
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
                location.replace("{{url('admin/category-status')}}/"+userId);
            }
        });
    }
    function CategoryDelete(userId){
        Swal.fire({
            title: 'Are you sure?',
            text: "Delete this category.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                location.replace("{{url('admin/delete-category')}}/"+userId);
            }
        });
    }
</script>
@endpush

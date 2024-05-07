@extends('admin.layouts.app')
@section('title')
User Management
@endsection

@section('content')

<section class="content-header">
    <h1>
        User Management
        {{-- <small>Customer Management</small> --}}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">
            User Management
        </li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="{{-- input-group input-group-sm --}} row">
                        <form>
                            <div class="col-md-2">
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
                            {{-- <div class="col-md-2">
                                <label for="email_verify">Email Verify</label>
                                <select class="form-control" name="email_verify" id="email_verify">
                                    <option value="">All</option>
                                    <option value="Y" @if(@request()->email_verify == 'Y') selected="" @endif>Yes</option>
                                    <option value="N" @if(@request()->email_verify == 'N') selected="" @endif>No</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="phone_verify">Phone Verify</label>
                                <select class="form-control" name="phone_verify" id="phone_verify">
                                    <option value="">All</option>
                                    <option value="Y" @if(@request()->phone_verify == 'Y') selected="" @endif>Yes</option>
                                    <option value="N" @if(@request()->phone_verify == 'N') selected="" @endif>No</option>
                                </select>
                            </div> --}}
                            <div class="col-md-2">
                                <label for="status">Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="">All</option>
                                    {{-- <option value="U" @if(@request()->status == 'U') selected="" @endif>Unverify</option> --}}
                                    <option value="A" @if(@request()->status == 'A') selected="" @endif>Active</option>
                                    <option value="I" @if(@request()->status == 'I') selected="" @endif>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-4" style="margin-top: 25px;">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                                <a href="{{route('admin.customer.management')}}" class="btn btn-info"><i class="fa fa-refresh"></i> Reset</a>
                                <a href="{{route('admin.customer.add')}}" class="btn btn-success"><i class="fa fa-plus"></i> Add User</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="box-body table-responsive{{--  no-padding --}}">
                    <table class="table table-hover table-bordered">
                        <tbody>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Created Date</th>
                                {{-- <th>Email Verify</th> --}}
                                {{-- <th>Phone Verify</th> --}}
                                {{-- <th>Is Approved</th> --}}
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            @if(@$all_users->isNotEmpty())
                            @foreach(@$all_users as $user)
                            <tr>
                                <td> 
                                    <img src="{{ @$user->full_path_image }}" style="width: 40px;">
                                </td>
                                <td>{{@$user->name}}</td>
                                <td>{{@$user->email}}</td>
                                <td>{{@$user->phone}}</td>
                                <td>{{@$user->created_at->format('d/m/Y')}}</td>
                                {{-- <td align="center">
                                    @if(@$user->is_email_verify == 'Y')
                                    <span class="label label-success">YES</span>
                                    @else
                                    <span class="label label-danger">NO</span>
                                    @endif
                                </td>
                                <td align="center">
                                    @if(@$user->is_phone_verify == 'Y')
                                    <span class="label label-success">YES</span>
                                    @else
                                    <span class="label label-danger">NO</span>
                                    @endif
                                </td>
                                <td align="center">
                                    @if(@$user->is_approved == 'Y')
                                    <span class="label label-success">YES</span>
                                    @else
                                    <span class="label label-danger">NO</span>
                                    @endif
                                </td> --}}
                                <td align="center">
                                    @if(@$user->status == 'U')
                                    <span class="label label-warning">Unverify</span>
                                    @elseif(@$user->status == 'A')
                                    <span class="label label-success">Active</span>
                                    @elseif(@$user->status == 'I')
                                    <span class="label label-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- @if(@$user->is_approved == 'N')
                                    <a href="javascript:void(0);" class="btn btn-xs btn-info" title="Approve" onclick="CustomerApprove('{{@$user->id}}');"><i class="fa fa-thumbs-up"></i></a>
                                    @endif --}}
                                    @if(@$user->status == 'U')
                                    <a href="javascript:void(0);" class="btn btn-xs btn-warning" title="Verify" onclick="statusChange('{{@$user->_id}}','A');"><i class="fa fa-check"></i></a>
                                    @elseif(@$user->status == 'I')
                                    <a href="javascript:void(0);" class="btn btn-xs btn-success" title="Active" onclick="statusChange('{{@$user->_id}}','A');"><i class="fa fa-check-square-o"></i></a>
                                    @elseif(@$user->status == 'A')
                                    <a href="javascript:void(0);" class="btn btn-xs btn-danger" title="Inactive" onclick="statusChange('{{@$user->_id}}','I');"><i class="fa fa-ban"></i></a>
                                    @endif
                                    <a href="{{route('admin.customer.add',[@$user->_id])}}" class="btn btn-xs btn-info" title="Edit User"><i class="fa fa-edit"></i></a>
                                    <a href="{{route('admin.customer.details',[@$user->_id])}}" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></a>
                                    <a href="javascript:void(0);" class="btn btn-xs btn-danger" onclick="CustomerDelete('{{@$user->_id}}');"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="7" align="center">
                                    No Data Found
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="pull-right">
                    @if(@$all_users->isNotEmpty())
                    {{ @$all_users->appends(Request::except('page'))->links("pagination::bootstrap-4") }}
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
    function statusChange(userId,status){
        if(status == 'A'){
            @if(@$type == 'C')
            $msg = "Active this customer.";
            @else
            $msg = "Active this agent.";
            @endif
        }
        else if(status == 'I'){
            @if(@$type == 'C')
            $msg = "Inactive this customer.";
            @else
            $msg = "Inactive this agent.";
            @endif
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
                location.replace("{{url('admin/user-change-status')}}-"+userId+"-"+status);
            }
        });
    }
    function CustomerApprove(userId){
        Swal.fire({
            title: 'Are you sure?',
            text: "Approve this customer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                location.replace("{{url('admin/user-approve')}}-"+userId);
            }
        });
    }
    function CustomerDelete(userId){
        Swal.fire({
            title: 'Are you sure?',
            text: "Delete this customer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                location.replace("{{url('admin/user-delete')}}-"+userId);
            }
        });
    }
</script>
@endpush

@section('title', 'Contact')
@extends('admin.layouts.app')

@section('content')
<section class="content-header">
	<h1>Contact Management</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="">Contact Management</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					{{-- <h3 class="box-title">All Contact</h3> --}}
					<div class="row">
						<form>
							<div class="col-md-3">
								<label for="keyword">Keyword</label>
								<input type="text" name="keyword" class="form-control" placeholder="Keyword" value="{{@request()->keyword}}" id="keyword">
							</div>
							<div class="col-md-3" style="margin-top: 26px;">
								<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Search</button>
								<a href="{{route('admin.contact.list')}}" class="btn btn-info btn-sm"><i class="fa fa-refresh"></i> Reset</a>
							</div>
						</form>
					</div>
				</div>
				<div class="box-body">
					<table id="my-datatable" class="table table-bordered table-striped" style="width: 100%">
						<thead>
							<tr>
								<th>Sl</th>
								<th>Name</th>
								<th>Email</th>
								<th>Phone</th>
								<th>Message</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@if (@$contact_list->isNotEmpty())
							@foreach (@$contact_list as $row)
							<tr>
								<td>{{ (@$loop->index+1) + (@$contact_list->perPage() * (@$contact_list->currentPage() - 1)) }}</td>
								<td>{{ @$row->name }}</td>
								<td>{{ @$row->email }}</td>
								<td>{{ @$row->phone }}</td>
								<td>{{ @$row->message }}</td>
								<td>
									<a href="javascript:void(0);" class="btn btn-xs btn-danger" title="Delete" onclick="CategoryDelete('{{@$row->id}}');"><i class="fa fa-trash-o"></i></a>
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
					@if(@$contact_list->isNotEmpty())
					{{ @$contact_list->appends(Request::except('page'))->links("pagination::bootstrap-4") }}
					@endif
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
@push('script')
<script type="text/javascript">
	function CategoryDelete(conId){
		Swal.fire({
			title: 'Are you sure?',
			text: "Delete this contact.",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes'
		}).then((result) => {
			if (result.isConfirmed) {
				location.replace("{{route('admin.contact.delete')}}/"+conId);
			}
		});
	}
</script>
@endpush

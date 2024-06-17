@section('title', 'Live')
@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <h1>Live</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Live</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <div class="row">
                
            </div>
        </div>
        <div class="box-body">
            <table id="my-datatable" class="table table-bordered table-striped" style="width: 100%">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Title</th>
                        <th>Overview</th>
                        <th>Video</th>
                        <th>Image</th>
                        <th>Created By</th>
                        <th>View Count</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        
                        $page = Request::get('page');
                        $i = 1;
                        if( $page == 1){
                            $i = 1;
                        } else if ( $page > 1) {
                            $i = ($paginate*($page-1))+1; 
                        }
                    @endphp
                    @if (!empty($data))
                    @foreach ( $data as $row)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $row['title'] }}</td>
                        <td>{{ $row['overview'] }}</td>                        
                        <td>
                            {{-- <video width="300" height="150" controls>
                                <source src="{{url($row['image'])}}" >
                            </video> --}}
                            <a href="{{ $row['videoUrl'] }}" target="_blank" title="{{ $row['videoUrl'] }}">Click here</a>                            
                        </td>
                        <td>
                            <img src="{{ asset('images/no-image.png') }}" style="width: 40px;" alt="">
                        </td>
                        <td>
                            {{ $row['user']['name'] }}
                        </td>
                        <td>
                            {{ count($row['views']) }}
                        </td>
                        
                    </tr>
                    @php
                        $i++;
                    @endphp
                    @endforeach
                    @else
                    <tr>
                        <td colspan="6" style="text-align: center;">Records not found</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            {{ $data->appends(Request::except('page'))->links("pagination::bootstrap-4") }}
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

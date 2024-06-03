@section('title', 'Podcasts')
@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <h1>Podcasts</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Podcasts</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <div class="row">
                {{-- <form>
                    <div class="col-md-3">
                        <label for="keyword">Keyword</label>
                        <input type="text" name="keyword" class="form-control" placeholder="Keyword" value="{{@request()->keyword}}" id="keyword">
                    </div>
                    <div class="col-md-3" style="margin-top: 26px;">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Search</button>
                        <a href="{{route('admin.gift.index')}}" class="btn btn-info btn-sm"><i class="fa fa-refresh"></i> Reset</a>
                        <a href="{{route('admin.gift.add')}}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add New</a>
                    </div>
                </form> --}}
            </div>
        </div>
        <div class="box-body">
            <table id="my-datatable" class="table table-bordered table-striped" style="width: 100%">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Title</th>
                        <th>Overview</th>
                        <th>Image</th>
                        <th>Audio/Video</th>
                        <th>Created By</th>
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
                    
                    @php
                        $audioEx = explode(".",$row['audio']);
                        $audioEx = end($audioEx);
                        $audioExtensions = ["mp3","flac","ogg","wav"];
                        $videoExtensions = ["mp4","mov","wmv","avi","flv","avchd","f4v","swf","mkv"];
                        $isAudio = $isVideo = false;
                        if(in_array($audioEx,$audioExtensions)){
                            $isAudio = true;
                        }
                        if(in_array($audioEx,$videoExtensions)){
                            $isVideo = true;
                        }                        
                    @endphp
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $row['title']}}</td>
                        <td>{{ $row['overview']}}</td>
                        <td>
                            @if(!empty($row['image']))
                                <img src="{{ url($row['image']) }}" style="width: 40px;" alt="">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" style="width: 40px;" alt="">
                            @endif

                        </td>
                        <td> 
                            @if ($isAudio)
                            <audio controls>
                                <source src="{{url($row['audio'])}}" >
                            </audio>
                            @else
                            <video width="300" height="150" controls>
                                <source src="{{url($row['audio'])}}" >
                            </video>
                            @endif                           
                            
                        </td>
                        <td>
                            {{ $row['user']['name'] }}
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

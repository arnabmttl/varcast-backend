@section('title', 'Publications')
@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <h1>Publications</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Publications</li>
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
                        <th>Description</th>
                        <th>Tags</th>
                        <th>Audio</th>
                        <th>Categories</th>
                        <th>Image/Video</th>
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
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $row['title'] }}</td>
                        <td>{{ $row['description'] }}</td>
                        <td>
                            @if(!empty($row['tags']))
                            @foreach($row['tags'] as $tags)
                            <ul>
                                <li>{{$tags}}</li>
                            </ul>
                            @endforeach
                            @endif
                        </td>
                        <td>                            
                            <audio controls>
                                <source src="{{ url($row['audioUrl']) }}" >
                            </audio>
                        </td>
                        <td>
                            @php    
                            $categories = $row['categories'];

                            @endphp
                            @if(!empty($categories))
                            @foreach($categories as $cat)
                            @php

                                $category = Helper::getSingleCollectionData('categories',$cat->categoryId);
                                $catName = $category['name'];
                                // dd($catName);
                            @endphp
                            <ul>
                                <li>{{ $catName }}</li>
                            </ul>
                            @endforeach
                            @endif
                        </td>
                        <td>
                            @if($row['image_type'] == 'image')
                                @if(!empty($row['image']))
                                    <img src="{{ url($row['image']) }}" style="width: 40px;" alt="">
                                @else
                                    <img src="{{ asset('images/no-image.png') }}" style="width: 40px;" alt="">
                                @endif
                            @else
                                <video width="300" height="150" controls>
                                    <source src="{{url($row['image'])}}" >
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

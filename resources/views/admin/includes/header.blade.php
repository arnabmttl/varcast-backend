<header class="main-header">
    <a href="{{route('admin.home')}}" class="logo">
        <span class="logo-mini"><b>VC</b></span>
        <span class="logo-lg"><b>{{env('APP_NAME')}}</b></span>
    </a>

    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img @if(empty(Auth::guard('admin')->user()->image)) src="{{ url('images/admin_profile.png') }}" @else src="{{url('storage/admin_pics/'.@Auth::guard('admin')->user()->image)}}" @endif class="user-image" alt="User Image">
                        <span class="hidden-xs">{{Auth::guard('admin')->user()->name}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">

                            <img @if(empty(Auth::guard('admin')->user()->image)) src="{{ url('images/admin_profile.png') }}" @else src="{{url('storage/admin_pics/'.@Auth::guard('admin')->user()->image)}}" @endif class="img-circle" alt="User Image">

                            <p> {{Auth::guard('admin')->user()->name}} <small>Member since {{Auth::guard('admin')->user()->created_at->format('d/m/Y h:i A')}}</small> </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href=" {{route('admin.edit.profile')}} " class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{route('admin.logout')}}" class="btn btn-default btn-flat" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign out</a>
                            </div>
                        </li>
                        <li class="user-body">
                            <div class="row">
                                <div class="col-xs-12 text-left">
                                    <a href=" {{route('admin.change.password')}}"><i class="fa fa-refresh"></i>  Change Password</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

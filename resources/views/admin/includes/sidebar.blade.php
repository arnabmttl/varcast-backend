<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img @if(empty(Auth::guard('admin')->user()->image)) src="{{ url('images/admin_profile.png') }}" @else src="{{url('storage/admin_pics/'.@Auth::guard('admin')->user()->image)}}" @endif class="img-circle" alt="User Image" style="width: 45px; height: 45px;">
            </div>
            <div class="pull-left info">
                <p>{{Auth::guard('admin')->user()->name}}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>

            <li class="@if(Route::is('admin.home')) active @endif">
                <a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="@if(Route::is('admin.customer.management','admin.customer.details','admin.customer.add')) active @endif">
                <a href="{{route('admin.customer.management')}}"><i class="fa fa-users"></i>
                    <span>User Management</span>
                </a>
            </li>
            <li class="@if(Route::is('admin.tag.index') || Route::is('admin.tag.add') || Route::is('admin.tag.edit')) active @endif">
                <a href="{{route('admin.tag.index')}}"><i class="fa fa-tag"></i>
                    <span>Tag Management</span>
                </a>
            </li>
            <li class="@if(Route::is('admin.category') || Route::is('admin.category.add') || Route::is('admin.category.edit')) active @endif">
                <a href="{{route('admin.category')}}"><i class="fa fa-list"></i>
                    <span>Category Management</span>
                </a>
            </li>
            <li class="@if(Route::is('admin.coin.price.index') || Route::is('admin.coin.price.add') || Route::is('admin.coin.price.edit')) active @endif">
                <a href="{{route('admin.coin.price.index')}}"><i class="fa fa-dollar"></i>
                    <span>Coin Plan Management</span>
                </a>
            </li>
            <li class="@if(Route::is('admin.gift.index') || Route::is('admin.gift.add') || Route::is('admin.gift.edit')) active @endif">
                <a href="{{route('admin.gift.index')}}"><i class="fa fa-gift"></i>
                    <span>Gift Management</span>
                </a>
            </li>
            <li class="@if(Route::is('admin.podcast.*') ) active @endif">
                <a href="{{route('admin.podcast.index')}}"><i class="fa fa-file-video-o"></i>
                    <span>Podcasts</span>
                </a>
            </li>
            <li class="@if(Route::is('admin.video.*') ) active @endif">
                <a href="{{route('admin.video.index')}}"><i class="fa fa-video-camera"></i>
                    <span>Publications</span>
                </a>
            </li>
            <li class="@if(Route::is('admin.emoji.index') || Route::is('admin.emoji.add') || Route::is('admin.emoji.edit')) active @endif">
                <a href="{{route('admin.emoji.index')}}"><i class="fa fa-smile-o"></i>
                    <span>Emoji Management</span>
                </a>
            </li>
            <li class="@if(Route::is('admin.my.music.index') || Route::is('admin.my.music.add') || Route::is('admin.my.music.edit')) active @endif">
                <a href="{{route('admin.my.music.index')}}"><i class="fa fa-music"></i>
                    <span>My Music Management</span>
                </a>
            </li>
           
            {{-- <li class="@if(Route::is('admin.banner.management') || Route::is('admin.banner.add') || Route::is('admin.banner.edit')) active @endif">
                <a href="{{route('admin.banner.management')}}"><i class="fa fa-image"></i>
                    <span>Banner Management</span>
                </a>
            </li>
            <li class="@if(Route::is('admin.faq.management') || Route::is('admin.faq.add') || Route::is('admin.faq.edit')) active @endif">
                <a href="{{route('admin.faq.management')}}"><i class="fa fa-question-circle"></i>
                    <span>Faq Management</span>
                </a>
            </li>
            <li class="@if(Route::is('admin.contact.list')) active @endif">
                <a href="{{route('admin.contact.list')}}"><i class="fa fa-address-book"></i>
                    <span>Contact Us Management</span>
                </a>
            </li> --}}
            <li class="@if(Route::is('admin.setting.management')) active @endif">
                <a href="{{route('admin.setting.management')}}"><i class="fa fa-cogs"></i>
                    <span>Setting Management</span>
                </a>
            </li>
            <li class="treeview @if(Route::is('admin.content.management','admin.home.content.management','admin.scorecard.content.management','admin.testimonial.page','admin.add.testimonial.page','admin.testimonial.edit')) active menu-open @endif">
                <a href="javascript:void(0);">
                    <i class="fa fa-file"></i> <span>Content Management</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="@if(@request()->is('admin/content-management/about')) active @endif"><a href="{{route('admin.content.management','about')}}"><i class="fa fa-circle-o"></i>About Us</a></li>
                    <li class="@if(@request()->is('admin/content-management/terms')) active @endif"><a href="{{route('admin.content.management','terms')}}"><i class="fa fa-circle-o"></i>Terms & Conditions</a></li>
                    <li class="@if(@request()->is('admin/content-management/privacy')) active @endif"><a href="{{route('admin.content.management','privacy')}}"><i class="fa fa-circle-o"></i>Privacy Policy</a></li>
                    {{-- <li class="@if(@request()->is('admin/testimonial','admin/add-testimonial','admin/edit-testimonial/*')) active @endif"><a href="{{route('admin.testimonial.page')}}"><i class="fa fa-circle-o"></i>Testimonial</a></li> --}}

                </ul>
            </li>
            {{-- <li class="@if(Route::is('admin.subscribe.list')) active @endif">
                <a href="{{route('admin.subscribe.list')}}"><i class="fa fa-bars"></i>
                    <span>Subscriber List</span>
                </a>
            </li> --}}
        </ul>
    </section>
</aside>

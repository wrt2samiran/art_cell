<!-- Main Sidebar Container -->
@php
    $admin = auth()->guard('admin')->user();
    $admin_image = $admin->profilePicLink
@endphp
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('admin.dashboard')}}" class="brand-link">
        <img src="{{asset('assets/dist/img/AdminLTELogo.png')}}" alt="User Logo"
             class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">{{__('general_sentence.admin_panel')}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
               <!--  <img src="{{asset('assets/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2"
                     alt="User Image"> -->
                <img style="height:30px;width: 30px" src="{{auth()->guard('admin')->user()->profile_image_url()}}" class="img-circle elevation-2"
                     alt="User Image">

            </div>
            <div class="info">
                <a href="#" class="d-block">{{ $admin->name }}<br><span>({{$admin->role?$admin->role->role_name:''}})</span></a>

            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item has-treeview @if(Route::currentRouteName()=='admin.dashboard' 
                || Route::currentRouteName()=='admin.settings'){{'menu-open'}}@endif">
                    <a href="#"
                       class="nav-link @if(Route::currentRouteName()=='admin.dashboard' 
                       || Route::currentRouteName()=='admin.settings' 
                       ){{'active'}}@endif">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                            {{__('nav_link_text.home')}}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview @if(Route::currentRouteName()=='admin.dashboard' 
                    || Route::currentRouteName()=='admin.settings'){{'style="display: block;"'}}@endif">
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}"
                               class="nav-link @if(Route::currentRouteName()=='admin.dashboard'){{'active'}}@endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('nav_link_text.dashboard')}}</p>
                            </a>
                        </li>
                        @if ($admin->role_id == '1')
                        <li class="nav-item">
                            <a href="{{ route('admin.settings') }}"
                               class="nav-link @if(Route::currentRouteName()=='admin.settings'){{'active'}}@endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('nav_link_text.settings')}}</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                    
                </li>
                @if(auth()->guard('admin')->user()->hasModulePermission('master-modules'))
                <li class="nav-item has-treeview {{(request()->is('admin/country/*','admin/country', 'admin/state/*','admin/state', 'admin/cities/*','admin/cities', 'admin/mobile_brand/*','admin/mobile_brand', 'admin/mobile_brand_model/*','admin/mobile_brand_model', 'admin/property-types/*','admin/property-types', 'admin/services/*','admin/services','admin/unit','admin/unit/*','admin/skills','admin/skills/*','admin/statuses/*','admin/statuses'))?'menu-open':''}}">
                    <a href="#"
                       class="nav-link {{(request()->is('admin/country/*','admin/country', 'admin/state/*','admin/state', 'admin/cities/*','admin/cities', 'admin/mobile_brand/*','admin/mobile_brand', 'admin/mobile_brand_model/*','admin/mobile_brand_model', 'admin/property-types/*','admin/property-types', 'admin/services/*','admin/services','admin/unit','admin/unit/*','admin/unit/*','admin/skills','admin/skills/*','admin/statuses/*','admin/statuses'))?'active':''}}">
                        <i class="nav-icon fas fa-bars"></i>
                        <p>
                            {{__('nav_link_text.master_modules')}}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    
                    <ul class="nav nav-treeview">
                        @if(auth()->guard('admin')->user()->hasAllPermission(['manage-country']))
                        <li class="nav-item">
                            <a href="{{ route('admin.country.list') }}"
                               class="nav-link {{(request()->is('admin/country/*','admin/country'))?'active':''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('nav_link_text.country_management')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->hasAllPermission(['manage-state']))
                        <li class="nav-item">
                            <a href="{{ route('admin.state.list') }}"
                               class="nav-link {{(request()->is('admin/state/*','admin/state'))?'active':''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('nav_link_text.state_management')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->hasAllPermission(['manage-city']))
                        <li class="nav-item">
                            <a href="{{ route('admin.cities.list') }}"
                               class="nav-link {{(request()->is('admin/cities/*','admin/cities'))?'active':''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('nav_link_text.city_management')}}</p>
                            </a>
                        </li>
                        @endif

                        @if(auth()->guard('admin')->user()->hasAllPermission(['manage-mobile-brand']))
                        <li class="nav-item">
                            <a href="{{ route('admin.mobile_brand.list') }}"
                               class="nav-link {{(request()->is('admin/mobile_brand/*','admin/mobile_brand'))?'active':''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Mobile Brand</p>
                            </a>
                        </li>
                        @endif

                        @if(auth()->guard('admin')->user()->hasAllPermission(['manage-brand-model']))
                        <li class="nav-item">
                            <a href="{{ route('admin.mobile_brand_model.list') }}"
                               class="nav-link {{(request()->is('admin/mobile_brand_model/*','admin/mobile_brand_model'))?'active':''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Brand Model</p>
                            </a>
                        </li>
                        @endif

                        
                    </ul>
                    
                </li>
                @endif           
                

             

                @if(!auth()->guard('admin')->user()->hasAllPermission(['manage-order']))
                <li class="nav-item">
                    <a href="{{route('admin.order.list')}}"
                    class="nav-link {{(request()->is('admin/order/*','admin/order'))?'active':''}}">
                        <i class="nav-icon fab fa-usps"></i>
                        <p>{{__('nav_link_text.manage_orders')}}</p>
                    </a>
                </li>
                @endif
                @if(!auth()->guard('admin')->user()->hasAllPermission(['user-list']) &&  auth()->guard('admin')->user()->hasAllPermission(['property-owner-list']))
                <li class="nav-item ">
                    <a href="{{route('admin.property_owners.list')}}"
                    class="nav-link {{(request()->is('admin/property-owners/*','admin/property-owners'))?'active':''}}">
                        <i class="nav-icon far fa-building"></i>
                        <p>{{__('nav_link_text.property_owners')}}</p>
                    </a>
                </li>
                @endif
                @if(!auth()->guard('admin')->user()->hasAllPermission(['user-list']) &&  auth()->guard('admin')->user()->hasAllPermission(['property-manager-list']))
                <li class="nav-item ">
                    <a href="{{route('admin.property_managers.list')}}"
                    class="nav-link {{(request()->is('admin/property-managers/*','admin/property-managers'))?'active':''}}">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p>{{__('nav_link_text.property_managers')}}</p>
                    </a>
                </li>
                @endif

              



            </ul>

        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
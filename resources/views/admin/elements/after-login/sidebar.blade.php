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
                <img src="{{asset('assets/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2"
                     alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ $admin->name }}</a>
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
                        <li class="nav-item">
                            <a href="{{ route('admin.settings') }}"
                               class="nav-link @if(Route::currentRouteName()=='admin.settings'){{'active'}}@endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('nav_link_text.settings')}}</p>
                            </a>
                        </li>
                    </ul>
                    
                </li>

                <li class="nav-item has-treeview @if(Route::currentRouteName()=='admin.country.list' || Route::currentRouteName()=='admin.country.country.add' || Route::currentRouteName()=='admin.country.edit' || Route::currentRouteName()=='admin.country.show' ||
                Route::currentRouteName()=='admin.state.list' || Route::currentRouteName()=='admin.state.country.add' || Route::currentRouteName()=='admin.state.show' ||
                Route::currentRouteName()=='admin.state.edit' || Route::currentRouteName()=='admin.city.list' || Route::currentRouteName()=='admin.city.add' || Route::currentRouteName()=='admin.city.edit' || Route::currentRouteName()=='admin.city.show'
                ){{'menu-open'}}@endif">
                    <a href="#"
                       class="nav-link @if(Route::currentRouteName()=='admin.dashboard' 
                       || Route::currentRouteName()=='admin.settings' 
                       ){{'active'}}@endif">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                            {{__('nav_link_text.master_modules')}}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    
                    <ul class="nav nav-treeview @if(Route::currentRouteName()=='admin.country.list' 
                    || Route::currentRouteName()=='admin.country.edit' || Route::currentRouteName()=='admin.country.country.add'){{'style="display: block;"'}}@endif">
                        <li class="nav-item">
                            <a href="{{ route('admin.country.list') }}"
                               class="nav-link @if(Route::currentRouteName()=='admin.country.list' || Route::currentRouteName()=='admin.country.edit' || Route::currentRouteName()=='admin.country.country.add' || Route::currentRouteName()=='admin.country.show'){{'active'}}@endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('nav_link_text.country_management')}}</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview @if(Route::currentRouteName()=='admin.state.list' || Route::currentRouteName()=='admin.state.show' 
                    || Route::currentRouteName()=='admin.state.edit' || Route::currentRouteName()=='admin.state.add' || Route::currentRouteName()=='admin.state.show'){{'style="display: block;"'}}@endif">
                        <li class="nav-item">
                            <a href="{{ route('admin.state.list') }}"
                               class="nav-link @if(Route::currentRouteName()=='admin.state.list' || Route::currentRouteName()=='admin.state.edit' || Route::currentRouteName()=='admin.state.add' || Route::currentRouteName()=='admin.state.show'){{'active'}}@endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('nav_link_text.state_management')}}</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview @if(Route::currentRouteName()=='admin.city.list' || Route::currentRouteName()=='admin.city.show'
                    || Route::currentRouteName()=='admin.city.edit' || Route::currentRouteName()=='admin.city.add' || Route::currentRouteName()=='admin.city.show'){{'style="display: block;"'}}@endif">
                        <li class="nav-item">
                            <a href="{{ route('admin.city.list') }}"
                               class="nav-link @if(Route::currentRouteName()=='admin.city.list' || Route::currentRouteName()=='admin.city.edit' || Route::currentRouteName()=='admin.city.add' || Route::currentRouteName()=='admin.city.show'){{'active'}}@endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('nav_link_text.city_management')}}</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview @if(Route::currentRouteName()=='admin.shared-service.list' || Route::currentRouteName()=='admin.shared-service.show'
                    || Route::currentRouteName()=='admin.shared-service.edit' || Route::currentRouteName()=='admin.shared-service.add' || Route::currentRouteName()=='admin.shared-service.show'){{'style="display: block;"'}}@endif">
                        <li class="nav-item">
                            <a href="{{ route('admin.shared-service.list') }}"
                               class="nav-link @if(Route::currentRouteName()=='admin.shared-service.list' || Route::currentRouteName()=='admin.shared-service.edit' || Route::currentRouteName()=='admin.shared-service.add' || Route::currentRouteName()=='admin.shared-service.show'){{'active'}}@endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('nav_link_text.shared_service_management')}}</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
               
                    <li  class="nav-item has-treeview @if(Route::currentRouteName()=='admin.module-management.module.list'|| 
                                                     Route::currentRouteName()=='admin.module-management.functionality.list' || 
                                                     Route::currentRouteName()=='admin.module-management.module.add' || 
                                                     Route::currentRouteName()=='admin.module-management.edit' || 
                                                     Route::currentRouteName()=='admin.module-management.function.add' || 
                                                     Route::currentRouteName()=='admin.module-management.functionality-edit'
                                                    ){{'menu-open'}}@endif">
                        <a href="#"
                           class="nav-link @if(Route::currentRouteName()=='admin.module-management.module.list' || 
                                                Route::currentRouteName()=='admin.module-management.functionality.list' || 
                                                 Route::currentRouteName()=='admin.module-management.module.add' ||
                                                Route::currentRouteName()=='admin.module-management.edit' ||
                                                Route::currentRouteName()=='admin.module-management.function.add' || 
                                                Route::currentRouteName()=='admin.module-management.functionality-edit'){{'active'}}@endif">
                            <i class="nav-icon fa fa-universal-access"></i>
                            <p>
                                {{__('nav_link_text.access_control')}}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                    </li>
                
                
                <li class="nav-item has-treeview @if(Route::currentRouteName()=='admin.user-management.role-list' ||
                            Route::currentRouteName()=='admin.user-management.user.list'||
                            Route::currentRouteName()=='admin.user-management.user.add'||
                            Route::currentRouteName()=='admin.user-management.user-edit'|| 
                            Route::currentRouteName()=='admin.user-management.role-add' ||
                            Route::currentRouteName()=='admin.user-management.edit' ||
                            Route::currentRouteName()=='admin.user-management.role.permission' ||
                            Route::currentRouteName()=='admin.user-management.site.user.list'){{'menu-open'}}@endif">
                    <a href="#"
                    class="nav-link @if(Route::currentRouteName()=='admin.user-management.role-list' || 
                                        Route::currentRouteName()=='admin.user-management.user.list'||
                                        Route::currentRouteName()=='admin.user-management.user.add' ||
                                        Route::currentRouteName()=='admin.user-management.user-edit' ||
                                        Route::currentRouteName()=='admin.user-management.edit' ||
                                        Route::currentRouteName()=='admin.user-management.role-add' ||
                                        Route::currentRouteName()=='admin.user-management.role.permission' ||
                                        Route::currentRouteName()=='admin.user-management.site.user.list'){{'active'}}@endif">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                             {{__('nav_link_text.user_management')}}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <li class="nav-item">
                        <a href="{{route('admin.roles.list')}}" class="nav-link {{(request()->is('admin/roles/*','admin/roles'))?'active':''}}">
                           &nbsp;&nbsp;<i class="fas fa-users"></i>
                          <p>
                            &nbsp;&nbsp;Roles/User Groups
                          </p>
                        </a>
                    </li>
                </li>
               

            </ul>

        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
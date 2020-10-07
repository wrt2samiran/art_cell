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
                || Route::currentRouteName()=='admin.settings' 
                ){{'menu-open'}}@endif">
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

                <li class="nav-item">
                    <a href="{{route('admin.roles.list')}}" class="nav-link {{(request()->is('admin/roles/*','admin/roles'))?'active':''}}">
                       &nbsp;&nbsp;<i class="fas fa-users"></i>
                      <p>
                        &nbsp;&nbsp;Roles/User Groups
                      </p>
                    </a>
                </li>
                @if (checkModulePermission("access.control"))
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
<!--                         <ul class="nav nav-treeview @if(Route::currentRouteName()=='admin.module-management.module.list' ||
                                    Route::currentRouteName()=='admin.module-management.module.add' || 
                                    Route::currentRouteName()=='admin.module-management.edit' ||
                                    Route::currentRouteName()=='admin.module-management.function.add' || 
                                    Route::currentRouteName()=='admin.module-management.functionality.list' ||
                                    Route::currentRouteName()=='admin.module-management.functionality-edit'){{'style="display: block;"'}}@endif">
                            @if(checkFunctionPermission('module-management.module.list'))
                            <li class="nav-item">
                                <a href="{{route('admin.module-management.module.list')}}"
                                class="nav-link @if(Route::currentRouteName()=='admin.module-management.module.list' ||
                                                Route::currentRouteName()=='admin.module-management.module.add' ||
                                                Route::currentRouteName()=='admin.module-management.edit'){{'active'}}@endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Module Management</p>
                                </a>
                            </li>
                            @endif
                            @if(checkFunctionPermission('module-management.functionality.list'))
                            <li class="nav-item">
                                <a href="{{route('admin.module-management.functionality.list')}}"
                                class="nav-link @if(Route::currentRouteName()=='admin.module-management.functionality.list' ||
                                                    Route::currentRouteName()=='admin.module-management.function.add'||
                                                    Route::currentRouteName()=='admin.module-management.functionality-edit'){{'active'}}@endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Functionality Management</p>
                                </a>
                            </li>
                            @endif
                        </ul> -->
                    </li>
                @endif
                @if (checkModulePermission("user.management"))
                <li class="nav-item has-treeview @if(
                            Route::currentRouteName()=='admin.user-management.user.list'||
                            Route::currentRouteName()=='admin.user-management.user.add'||
                            Route::currentRouteName()=='admin.user-management.user-edit'|| 
             
                            Route::currentRouteName()=='admin.user-management.edit' ||
                   
                            Route::currentRouteName()=='admin.user-management.site.user.list'){{'menu-open'}}@endif">
                    <a href="#"
                    class="nav-link @if( 
                                        Route::currentRouteName()=='admin.user-management.user.list'||
                                        Route::currentRouteName()=='admin.user-management.user.add' ||
                                        Route::currentRouteName()=='admin.user-management.user-edit' ||
                                        Route::currentRouteName()=='admin.user-management.edit' ||
                                        Route::currentRouteName()=='admin.user-management.site.user.list'){{'active'}}@endif">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                             {{__('nav_link_text.user_management')}}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
<!--                     <ul class="nav nav-treeview @if(
                                    Route::currentRouteName()=='admin.user-management.user.list' ||
                                    Route::currentRouteName()=='admin.user-management.user.add' || 
                                    Route::currentRouteName()=='admin.user-management.user-edit' || 
                                   
                                    Route::currentRouteName()=='admin.user-management.edit' ||
                                 
                                    Route::currentRouteName()=='admin.user-management.site.user.list'){{'style="display: block;"'}}@endif">

                        @if(checkFunctionPermission('user-management.user.add'))

                        <li class="nav-item">
                            <a href="{{route('admin.user-management.user.add')}}"
                             class="nav-link @if( \Route::currentRouteName()=='admin.user-management.user.add'){{'active'}}@endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p> User Create</p>
                            </a>
                        </li>
                                    
                        @endif 

                        @if(checkFunctionPermission('user-management.user.list'))
                        <li class="nav-item">
                            <a href="{{route('admin.user-management.user.list' )}}"
                             class="nav-link @if(Route::currentRouteName()=='admin.user-management.user.list' 
                                        ||(Route::currentRouteName()=='admin.user-management.user-edit' && isset($details->usertype) && $details->usertype != 'FU')
                                      ){{'active'}}@endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Admin User List</p>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{route('admin.user-management.site.user.list' )}}"
                             class="nav-link @if(Route::currentRouteName()=='admin.user-management.site.user.list' 
                                        || (Route::currentRouteName()=='admin.user-management.user-edit' && isset($details->usertype) && $details->usertype == 'FU')
                                        ){{'active'}}@endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p> App User List</p>
                            </a>
                        </li>
                    </ul> -->
                </li>
                @endif

            </ul>

        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
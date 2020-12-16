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
                <li class="nav-item has-treeview {{(request()->is('admin/country/*','admin/country', 'admin/state/*','admin/state', 'admin/cities/*','admin/cities', 'admin/property-types/*','admin/property-types', 'admin/services/*','admin/services','admin/unit','admin/unit/*'))?'menu-open':''}}">
                    <a href="#"
                       class="nav-link {{(request()->is('admin/country/*','admin/country', 'admin/state/*','admin/state', 'admin/cities/*','admin/cities', 'admin/property-types/*','admin/property-types', 'admin/services/*','admin/services','admin/unit','admin/unit/*'))?'active':''}}">
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
                        @if(auth()->guard('admin')->user()->hasAllPermission(['manage-property-type']))
                        <li class="nav-item">
                            <a href="{{ route('admin.property_types.list') }}"
                               class="nav-link {{(request()->is('admin/property-types/*','admin/property-types'))?'active':''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('nav_link_text.property_types')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->hasAllPermission(['manage-services']))
                        <li class="nav-item">
                            <a href="{{ route('admin.services.list') }}"
                               class="nav-link {{(request()->is('admin/services/*','admin/services'))?'active':''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('nav_link_text.services')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->hasAllPermission(['manage-units']))
                        <li class="nav-item">
                            <a href="{{ route('admin.unit.list') }}"
                               class="nav-link {{(request()->is('admin/unit/*','admin/unit'))?'active':''}}">
                                <i class="nav-icon fas fa-envelope-open-text"></i>
                                <p>{{__('nav_link_text.unit_management')}}</p>
                            </a>
                        </li>   
                        @endif     
                    </ul>
                    
                </li>
                @endif
                @if(auth()->guard('admin')->user()->hasAllPermission(['manage-translations']))
                <li class="nav-item ">
                    <a href="{{URL::to('/').'/admin/translations'}}"
                    class="nav-link {{(request()->is('admin/translations/*','admin/translations'))?'active':''}}">
                        <i class="nav-icon fas fa-globe"></i>
                        <p>
                             {{__('nav_link_text.translation_management')}}
                        </p>
                    </a>
                </li>
                @endif
                
                
                @if(auth()->guard('admin')->user()->hasAllPermission(['group-list']))
                <li class="nav-item">
                    <a href="{{route('admin.roles.list')}}" class="nav-link {{(request()->is('admin/user-groups/*','admin/user-groups'))?'active':''}}">
                       <i class="nav-icon fas fa-users"></i>
                      <p>
                        {{__('nav_link_text.user_groups')}}
                      </p>
                    </a>
                </li>
                @endif
                @if(auth()->guard('admin')->user()->hasAllPermission(['user-list']))
                <li class="nav-item ">
                    <a href="{{route('admin.users.list')}}"
                    class="nav-link {{(request()->is('admin/users/*','admin/users'))?'active':''}}">
                        <i class="nav-icon fa fa-user"></i>
                        <p>
                             {{__('nav_link_text.user_management')}}
                        </p>
                    </a>
                </li>
                @endif

                @if(auth()->guard('admin')->user()->hasAllPermission(['shared-service-list']))
                <li class="nav-item has-treeview {{(request()->is('admin/shared-services/*','admin/shared-services'))?'menu-open':''}}">
                    
                    <li class="nav-item">
                            <a href="{{ route('admin.shared_services.list') }}" class="nav-link {{(request()->is('admin/shared-services/*','admin/shared-services'))?'active':''}}">
                              
                               
                                <i class="nav-icon fas fa-hammer"></i>
                                <p>{{__('nav_link_text.shared_service_management')}}</p>
                            </a>
                        </li>
                </li>
                @endif
                @if(auth()->guard('admin')->user()->hasAllPermission(['spare-parts-list']))
                <li class="nav-item has-treeview {{(request()->is('admin/spare-parts/*','admin/spare-parts'))?'menu-open':''}}">
                    <li class="nav-item">
                        <a href="{{route('admin.spare_parts.list')}}" class="nav-link {{(request()->is('admin/spare-parts/*','admin/spare-parts'))?'active':''}}">
                            <i class="nav-icon fas fa-screwdriver"></i>
                                <p>{{__('nav_link_text.spare_parts_management')}}</p>
                        </a>
                    </li>
                </li>
                @endif

                @if(auth()->guard('admin')->user()->hasAllPermission(['spare-part-order-management']))
                <li class="nav-item ">
                    <a href="{{route('admin.spare_part_orders.order_list')}}"
                    class="nav-link {{(request()->is('admin/spare-part-orders/manage/orders','admin/spare-part-orders/manage/orders/*'))?'active':''}}">
                        <i class="nav-icon fas fa-book-open"></i>
                        <p>{{__('nav_link_text.spare_part_order_management')}}</p>
                    </a>
                </li>
                @endif

                @if(auth()->guard('admin')->user()->hasAllPermission(['shared-service-order-management']))
                <li class="nav-item ">
                    <a href="{{route('admin.shared_service_orders.order_list')}}"
                    class="nav-link {{(request()->is('admin/shared-service-orders/manage/orders','admin/shared-service-orders/manage/orders/*'))?'active':''}}">
                        <i class="nav-icon fas fa-book-open"></i>
                        <p>{{__('nav_link_text.shared_service_order_management')}}</p>
                        
                    </a>
                </li>
                @endif

                @if(!auth()->guard('admin')->user()->hasAllPermission(['user-list']) && auth()->guard('admin')->user()->hasAllPermission(['service-provider-list']))
                <li class="nav-item">
                    <a href="{{route('admin.service_providers.list')}}"
                    class="nav-link {{(request()->is('admin/service-providers/*','admin/service-providers'))?'active':''}}">
                        <i class="nav-icon fab fa-usps"></i>
                        <p>{{__('nav_link_text.service_providers')}}</p>
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

                @if(auth()->guard('admin')->user()->hasAllPermission(['property-list']))
                <li class="nav-item ">
                    <a href="{{route('admin.properties.list')}}"
                    class="nav-link {{(request()->is('admin/properties/*','admin/properties'))?'active':''}}">
                        <i class="nav-icon fas fa-igloo"></i>
                        <p>{{__('nav_link_text.property_management')}}</p>
                    </a>
                </li>
                @endif
                @if(auth()->guard('admin')->user()->hasAllPermission(['contract-list']))
                <li class="nav-item ">
                    <a href="{{route('admin.contracts.list')}}"
                    class="nav-link {{(request()->is('admin/contracts/*','admin/contracts'))?'active':''}}">
                        <i class="nav-icon fas fa-igloo"></i>
                        <p>{{__('nav_link_text.contracts_management')}}</p>
                    </a>
                </li>
                @endif


                @if(auth()->guard('admin')->user()->hasAllPermission(['can-order-spare-part']))
                <li class="nav-item ">
                    <a href="{{route('admin.spare_part_orders.create_order')}}"
                    class="nav-link {{(request()->is('admin/spare-part-orders/create-order','admin/spare-part-orders/cart','admin/spare-part-orders/checkout','admin/spare-part-orders/my-orders'))?'active':''}}">
                        <i class="nav-icon fas fa-screwdriver"></i>
                        <p>{{__('nav_link_text.order_spare_part')}}</p>
                    </a>
                </li>
                @endif

                @if(auth()->guard('admin')->user()->hasAllPermission(['can-order-shared-service']))
                <li class="nav-item ">
                    <a href="{{route('admin.shared_service_orders.create_order')}}"
                    class="nav-link {{(request()->is('admin/shared-service-orders/create-order','admin/shared-service-orders/cart','admin/shared-service-orders/checkout','admin/shared-service-orders/my-orders'))?'active':''}}">
                        <i class="nav-icon fas fa-hammer"></i>
                        <p>{{__('nav_link_text.order_shared_service')}}</p>
                    </a>
                </li>
                @endif

                @if(auth()->guard('admin')->user()->hasAllPermission(['quotation-list']))
                <li class="nav-item ">
                    <a href="{{route('admin.quotations.list')}}"
                    class="nav-link {{(request()->is('admin/quotations/*','admin/quotations'))?'active':''}}">
                        <i class="nav-icon fas fa-sticky-note"></i>
                        <p>{{__('nav_link_text.quotations')}}</p>
                    </a>
                </li> 
                @endif

                @if(auth()->guard('admin')->user()->hasAllPermission(['users-contract-list']))
                <li class="nav-item ">
                    <a href="{{route('admin.user_contracts.list')}}"
                    class="nav-link {{(request()->is('admin/user-contracts/*','admin/user-contracts'))?'active':''}}">
                        <i class="nav-icon fas fa-handshake"></i>
                        <p>{{__('nav_link_text.contracts')}}</p>
                    </a>
                </li>
                @endif
                @if(auth()->guard('admin')->user()->hasAllPermission(['users-property-list']))
                <li class="nav-item ">
                    <a href="{{route('admin.user_properties.list')}}"
                    class="nav-link {{(request()->is('admin/user-properties/*','admin/user-properties'))?'active':''}}">
                        <i class="nav-icon fas fa-house-user"></i>
                        <p>{{__('nav_link_text.properties')}}</p>
                    </a>
                </li>
                @endif 

                <!-- @if(auth()->guard('admin')->user()->hasAllPermission(['service_management_list']))
                <li class="nav-item ">
                    <a href="{{route('admin.service_management.list')}}"
                    class="nav-link {{(request()->is('admin/service_management/*','admin/service_management'))?'active':''}}">
                        <i class="nav-icon fas fa-quote-right"></i>
                        <p>{{__('nav_link_text.service_management')}}</p>
                    </a>
                </li>
                @endif -->

                @if(auth()->guard('admin')->user()->hasAllPermission(['calendar-data']))
                <li class="nav-item ">
                    <a href="{{route('admin.calendar.calendardata')}}"
                    class="nav-link {{(request()->is('admin/calendar/*','admin/calendar'))?'active':''}}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>{{__('nav_link_text.calendar')}}</p>
                    </a>
                </li>
                @endif

                @if(auth()->guard('admin')->user()->hasAllPermission(['manage-email-template']))
                <li class="nav-item ">
                    <a href="{{route('admin.email.list')}}"
                    class="nav-link {{(request()->is('admin/email/*','admin/email'))?'active':''}}">
                        <i class="nav-icon fa fa-file"></i>
                        <p>{{__('nav_link_text.email_templete')}}</p>
                    </a>
                </li>            
                @endif

                @if(auth()->guard('admin')->user()->hasAllPermission(['complaint-list']))
                <li class="nav-item ">
                    <a href="{{route('admin.complaints.list')}}"
                    class="nav-link {{(request()->is('admin/complaints/*','admin/complaints'))?'active':''}}">
                        <i class="nav-icon far fa-question-circle"></i>
                        <p>{{__('nav_link_text.complaints')}}</p>
                    </a>
                </li>
                @endif

                @if(auth()->guard('admin')->user()->hasAllPermission(['view-messages']))
                <li class="nav-item ">
                    <a href="{{route('admin.messages.list')}}"
                    class="nav-link {{(request()->is('admin/messages/*','admin/messages'))?'active':''}}">
                        <i class="nav-icon fas fa-envelope-open-text"></i>
                        <p>
                             {{__('nav_link_text.messages')}}
                        </p>
                    </a>
                </li>
                @endif

                <li class="nav-item ">
                    <a href="{{route('admin.notifications.list')}}"
                    class="nav-link {{(request()->is('admin/notifications/*','admin/notifications'))?'active':''}}">
                        <i class="nav-icon far fa-bell"></i>
                        <p>{{__('nav_link_text.notifications')}}</p>
                    </a>
                </li>     
                @if(auth()->guard('admin')->user()->hasAllPermission(['work-order-list']))
                <li class="nav-item ">
                    <a href="{{route('admin.work-order-management.list')}}"
                    class="nav-link {{(request()->is('admin/work-order-management/*','admin/work-order-management'))?'active':''}}">
                        <i class="nav-icon fas fa-quote-right"></i>
                        <p>{{__('nav_link_text.work-order-management')}}</p>
                    </a>
                </li>            
                @endif
                <!-- @if ($admin->role->user_type->slug =='super-admin' || $admin->role->user_type->slug =='service-provider')
                <li class="nav-item has-treeview {{(request()->is('admin/labour/*','admin/labour'))?'menu-open':''}}">
                    
                    <li class="nav-item">
                            <a href="{{ route('admin.labour.list') }}" class="nav-link {{(request()->is('admin/labour/*','admin/labour'))?'active':''}}">
                                <i class="nav-icon fas fa-envelope-open-text"></i>
                                <p>{{__('nav_link_text.labour_management')}}</p>
                            </a>
                        </li>
                </li>
                @endif -->

                @if(auth()->guard('admin')->user()->hasModulePermission('labour-management'))
                <li class="nav-item has-treeview {{(request()->is('admin/labour/*','admin/labour'))?'menu-open':''}}">
                    <a href="#"
                       class="nav-link {{(request()->is('admin/labour/*','admin/labour'))?'active':''}}">
                        <i class="nav-icon fas fa-bars"></i>
                        <p>
                            {{__('nav_link_text.labour_management')}}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    
                    <ul class="nav nav-treeview">
                        @if(auth()->guard('admin')->user()->hasAllPermission(['manage-labour']))
                        <li class="nav-item">
                            <a href="{{ route('admin.labour.list') }}"
                               class="nav-link {{(request()->is('admin/labour','admin/labour'))?'active':''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('nav_link_text.labour_list')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(auth()->guard('admin')->user()->hasAllPermission(['leave-list']))
                        <li class="nav-item">
                            <a href="{{ route('admin.labour.leaveList') }}" class="nav-link {{(request()->is('admin/labour/leave-list','admin/labour/leave-list', 'admin/labour/show-leave/*','admin/labour/show-leave'))?'active':''}}"
                               >
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{__('nav_link_text.labour_leave_management')}}</p>
                            </a>
                        </li>
                        @endif
                        
                    </ul>
                   
                    
                </li>
                @endif


            </ul>

        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
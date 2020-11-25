<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return redirect()->route('admin.login');
// });

Route::get('/language/{locale}','admin\DashboardController@changeLanguage')->name('changeLanguage');
Route::get('/multi-lang','PostController@index');

Route::get('/datatable-url','PostController@datatable')->name('datatable');
Route::post('/store-post','PostController@storePost')->name('storePost');

Route::group(["prefix" => "ajax", 'as' => 'ajax.'], function() {
   Route::post('/check_user_email_unique/{id?}','CommonAjaxController@check_user_email_unique')
   ->name('check_user_email_unique');    
});

/* Start Admin's route */
Route::group(["prefix" => "admin","namespace"=>"admin", 'as' => 'admin.'], function() {
        Route::get('/testing','AuthController@mailTest');
        Route::get('/test', 'AuthController@test');
        Route::get('/', 'AuthController@index');
        Route::any('/login', 'AuthController@index')->name('login');
        Route::post('/quotetion', 'AuthController@quotetion')->name('quotetion');
        Route::post('/authentication','AuthController@verifyCredentials')->name('authentication');
        Route::any('/forgot-password', 'AuthController@forgotPassword')->name('forgot.password');
        Route::any('/reset-password/{encryptCode}','AuthController@resetPassword')->name('reset.password');

        Route::group(['middleware' => 'admin'], function () {
            Route::get('/dashboard', 'DashboardController@dashboardView')->name('dashboard');
            Route::any('/edit-settings', 'DashboardController@editSetting')->name('settings');
            Route::any('/update-settings', 'DashboardController@updateSetting')->name('updateSetting');
            //Route::any('/settings', 'DashboardController@settings')->name('settings');
            
            
            Route::get('/logout', 'AuthController@logout')->name('logout');
            Route::get('/change-password','DashboardController@showChangePasswordForm')->name('changePassword');
            Route::post('/change-password','DashboardController@changePassword')->name('changePassword');

            Route::group(['prefix'=>'profile','as' => 'profile.'],function(){
                Route::get('/edit-profile','ProfileController@edit_profile')->name('edit_profile');
                Route::put('/update-profile','ProfileController@update_profile')->name('update_profile');
                Route::get('/change-password','ProfileController@change_password')->name('change_password');
                Route::put('/update-password','ProfileController@update_password')->name('update_password');
            });

            /*Routes for role/user groups management */
            Route::group(['prefix'=>'user-groups','as'=>'roles.'],function(){
                Route::get('/', 'RoleController@list')->name('list')->middleware('check_permissions:group-list');
                Route::get('/create', 'RoleController@create')->name('create')->middleware('check_permissions:group-create');
                Route::post('/store', 'RoleController@store')->name('store')->middleware('check_permissions:group-create');
                Route::get('/{id}', 'RoleController@show')->name('show')->middleware('check_permissions:group-details');
                Route::get('/{id}/edit', 'RoleController@edit')->name('edit');
                Route::put('/{id}', 'RoleController@update')->name('update');
                Route::delete('/{id}/delete', 'RoleController@delete')->name('delete')->middleware('check_permissions:group-delete');
                Route::get('/{id}/change-status', 'RoleController@change_status')->name('change_status');
                Route::post('/ajax/check_role_name_unique/{role_id?}', 'RoleController@ajax_check_role_name_unique')
                ->name('ajax_check_role_name_unique');
                
          

            });
            /************************************/

            /*Routes for property type management */
            Route::group(['prefix'=>'property-types','middleware'=>['check_permissions:manage-property-type'],'as'=>'property_types.'],function(){
                Route::get('/', 'PropertyTypeController@list')->name('list');
                Route::get('/create', 'PropertyTypeController@create')->name('create');
                Route::post('/store', 'PropertyTypeController@store')->name('store');
                Route::get('/{id}', 'PropertyTypeController@show')->name('show');
                Route::get('/{id}/edit', 'PropertyTypeController@edit')->name('edit');
                Route::put('/{id}', 'PropertyTypeController@update')->name('update');
                Route::delete('/{id}/delete', 'PropertyTypeController@delete')->name('delete');
                Route::get('/{id}/change-change', 'PropertyTypeController@change_status')->name('change_status');
                Route::post('/ajax/ajax_check_type_name_unique/{property_type_id?}', 'PropertyTypeController@ajax_check_type_name_unique')
                ->name('ajax_check_type_name_unique');
            });
            /************************************/

            /*Routes for service management */
            Route::group(['prefix'=>'services','middleware'=>['check_permissions:manage-services'],'as'=>'services.'],function(){
                Route::get('/', 'ServiceController@list')->name('list');
                Route::get('/create', 'ServiceController@create')->name('create');
                Route::post('/store', 'ServiceController@store')->name('store');
                Route::get('/{id}', 'ServiceController@show')->name('show');
                Route::get('/{id}/edit', 'ServiceController@edit')->name('edit');
                Route::put('/{id}', 'ServiceController@update')->name('update');
                Route::delete('/{id}/delete', 'ServiceController@delete')->name('delete');
                Route::get('/{id}/change-change', 'ServiceController@change_status')->name('change_status');
                Route::post('/ajax/ajax_check_service_name_unique/{service_id?}', 'ServiceController@ajax_check_service_name_unique')
                ->name('ajax_check_service_name_unique');

            });
            /************************************/

            /*Routes for quotations */
            Route::group(['prefix'=>'quotations','as'=>'quotations.'],function(){
                Route::get('/', 'QuotationController@list')->name('list')->middleware('check_permissions:quotation-list');
                Route::get('/{id}', 'QuotationController@show')->name('show')->middleware('check_permissions:quotation-details');
                Route::delete('/{id}/delete', 'QuotationController@delete')->name('delete')->middleware('check_permissions:quotation-delete');
            });
            /************************************/

            /*Routes for service management */
            Route::group(['prefix'=>'service-providers','as'=>'service_providers.'],function(){
                Route::get('/', 'ServiceProviderController@list')->name('list')->middleware('check_permissions:service-provider-list');

                Route::get('/{id}', 'ServiceProviderController@show')->name('show')->middleware('check_permissions:service-provider-details');
   

            });
            /************************************/

            /*Routes for property owner management */
            Route::group(['prefix'=>'property-owners','as'=>'property_owners.'],function(){
                Route::get('/', 'PropertyOwnerController@list')->name('list')->middleware('check_permissions:property-owner-list');

                Route::get('/{id}', 'PropertyOwnerController@show')->name('show')->middleware('check_permissions:property-owner-details');
  
            });
            /************************************/
            /*Routes for property manager management */
            Route::group(['prefix'=>'property-managers','as'=>'property_managers.'],function(){
                Route::get('/', 'PropertyManagerController@list')->name('list')->middleware('check_permissions:property-manager-list');

                Route::get('/{id}', 'PropertyManagerController@show')->name('show')->middleware('check_permissions:property-manager-details');

            });
            
            /************************************/

            /*Routes for users management */
            Route::group(['prefix'=>'users','as'=>'users.'],function(){
                Route::get('/', 'UserController@list')->name('list')->middleware('check_permissions:user-list');
                Route::get('/create', 'UserController@create')->name('create')->middleware('check_permissions:user-create');
                Route::post('/store', 'UserController@store')->name('store')->middleware('check_permissions:user-create');
                Route::get('/{id}', 'UserController@show')->name('show')->middleware('check_permissions:user-details');
                Route::get('/{id}/edit', 'UserController@edit')->name('edit')->middleware('check_permissions:user-edit');
                Route::put('/{id}', 'UserController@update')->name('update')->middleware('check_permissions:user-edit');
                Route::delete('/{id}/delete', 'UserController@delete')->name('delete')->middleware('check_permissions:user-delete');
                Route::get('/{id}/change-change', 'UserController@change_status')->name('change_status')->middleware('check_permissions:user-status-change');
  
            });
            
            /************************************/

            /*Routes for property management */
            Route::group(['prefix'=>'properties','as'=>'properties.'],function(){
                Route::get('/', 'PropertyController@list')->name('list')->middleware('check_permissions:property-list');
                Route::get('/create', 'PropertyController@create')->name('create')->middleware('check_permissions:property-create');
                Route::post('/store', 'PropertyController@store')->name('store')->middleware('check_permissions:property-create');
                Route::get('/{id}', 'PropertyController@show')->name('show')->middleware('check_permissions:property-details');
                Route::get('/{id}/edit', 'PropertyController@edit')->name('edit')->middleware('check_permissions:property-edit');
                Route::put('/{id}', 'PropertyController@update')->name('update')->middleware('check_permissions:property-edit');
                Route::delete('/{id}/delete', 'PropertyController@delete')->name('delete')->middleware('check_permissions:property-delete');
                Route::get('/{id}/change-change', 'PropertyController@change_status')->name('change_status')->middleware('check_permissions:property-status-change');
                Route::get('attachment/{id}/download', 'PropertyController@download_attachment')->name('download_attachment');

                Route::delete('attachment/{id}/delete_attachment_through_ajax', 'PropertyController@delete_attachment_through_ajax')->name('delete_attachment_through_ajax');
            });
            
            /************************************/

            /*Routes for contracts management */
            Route::group(['prefix'=>'contracts','as'=>'contracts.'],function(){
                Route::get('/', 'ContractController@list')->name('list')->middleware('check_permissions:contract-list');
                
                Route::get('/create', 'ContractController@create')->name('create')->middleware('check_permissions:contract-create');
                Route::post('/store', 'ContractController@store')->name('store')->middleware('check_permissions:contract-create');


                Route::get('/{contract_id}/services', 'ContractController@services')->name('services');

                Route::delete('/{contract_id}/services/{service_id}/delete', 'ContractController@service_delete')->name('service_delete');

                Route::get('/{contract_id}/services/{service_id}/enable-disable', 'ContractController@service_enable_disable')->name('service_enable_disable');

                Route::get('/{contract_id}/services/{service_id}/details', 'ContractController@service_details')->name('service_details');

                Route::post('/{contract_id}/services/store', 'ContractController@store_service')->name('services.store');

                Route::get('/{contract_id}/payment-info', 'ContractController@payment_info')->name('payment_info');

                Route::post('/{contract_id}/store-payment-info', 'ContractController@store_payment_info')->name('store_payment_info');

                Route::get('/{contract_id}/files', 'ContractController@files')->name('files');

                Route::post('/{contract_id}/store-files', 'ContractController@store_files')->name('store_files');

                Route::get('/{id}', 'ContractController@show')->name('show')->middleware('check_permissions:contract-details');
                Route::get('/{id}/edit', 'ContractController@edit')->name('edit')->middleware('check_permissions:contract-edit');
                Route::put('/{id}', 'ContractController@update')->name('update')->middleware('check_permissions:contract-edit');
                Route::delete('/{id}/delete', 'ContractController@delete')->name('delete')->middleware('check_permissions:contract-delete');
                Route::get('attachment/{id}/download', 'ContractController@download_attachment')->name('download_attachment');
                
                Route::delete('attachment/{file_id}/delete_attachment_through_ajax', 'ContractController@delete_attachment_through_ajax')->name('delete_attachment_through_ajax');
            });
            
            /************************************/

            /*Routes for user contracts */
            Route::group(['prefix'=>'user-contracts','as'=>'user_contracts.'],function(){
                Route::get('/', 'UserContractController@list')->name('list')->middleware('check_permissions:users-contract-list');
                Route::get('/{id}', 'UserContractController@show')->name('show')->middleware('check_permissions:users-contract-details');
            });
            /************************************/

            /*Routes for user properties */
            Route::group(['prefix'=>'user-properties','as'=>'user_properties.'],function(){
                Route::get('/', 'UserPropertyController@list')->name('list')->middleware('check_permissions:users-property-list');
                Route::get('/{id}', 'UserPropertyController@show')->name('show')->middleware('check_permissions:users-property-details');
            });
            /************************************/


            Route::group(['prefix' => 'module-management', 'as' => 'module-management.'], function () {
                Route::get('/list', 'ModuleManagementController@moduleList')->name('module.list');
                Route::get('/module-list-table', 'ModuleManagementController@moduleListTable')->name('list.table');
                Route::any('/add','ModuleManagementController@moduleAdd')->name('module.add');
                Route::get('/edit/{encryptCode}', 'ModuleManagementController@moduleEdit')->name('edit');
                Route::post('/edit-submit/{encryptCode}', 'ModuleManagementController@moduleEdit')->name('editSubmit');
                Route::get('/module-delete/{encryptCode}','ModuleManagementController@moduleDelete')->name('module-delete');
                Route::get('/reset-module-status/{encryptCode}','ModuleManagementController@resetmoduleStatus')->name('reset-module-status');
                Route::get('/functionality-list', 'ModuleManagementController@functionalityList')->name('functionality.list');
                Route::get('/functionality-table', 'ModuleManagementController@functionalityTable')->name('functionality.table');
                Route::any('/add-function','ModuleManagementController@functionAdd')->name('function.add');
                Route::get('/function-edit/{encryptCode}', 'ModuleManagementController@functionalityEdit')->name('functionality-edit');
                Route::post('/function-edit-submit/{encryptCode}', 'ModuleManagementController@functionalityEdit')->name('function-editSubmit');
                Route::get('/function-delete/{encryptCode}', 'ModuleManagementController@functionaDelete')->name('function-delete');
                Route::get('/reset-function-status/{encryptCode}','ModuleManagementController@resetfunctionStatus')->name('reset-function-status');
            });
     

            Route::group(['prefix' => 'country','middleware'=>['check_permissions:manage-country'], 'as' => 'country.'], function () {
                Route::get('/', 'CountryController@list')->name('list');
                Route::any('/add','CountryController@countryAdd')->name('country.add');
                Route::any('/edit/{encryptCode}', 'CountryController@edit')->name('edit');
                Route::put('/update', 'CountryController@update')->name('update');
                Route::get('/{id}/change-change', 'CountryController@change_status')->name('change_status');
                Route::get('/delete/{id}', 'CountryController@delete')->name('delete');
                Route::get('/{id}', 'CountryController@show')->name('show');                
            });

            Route::group(['prefix' => 'state','middleware'=>['check_permissions:manage-state'], 'as' => 'state.'], function () {
                Route::get('/', 'StateController@list')->name('list');
                Route::any('/add','StateController@stateAdd')->name('add');
                Route::any('/edit/{encryptCode}', 'StateController@edit')->name('edit');
                Route::put('/update', 'StateController@update')->name('update');
                Route::get('/{id}/change-change', 'StateController@change_status')->name('change_status');
                Route::get('/delete/{id}', 'StateController@delete')->name('delete');
                Route::get('/{id}', 'StateController@show')->name('show');
                
            });

            Route::group(['prefix' => 'cities','middleware'=>['check_permissions:manage-city'],'as' => 'cities.'], function () {
                Route::get('/', 'CitiesController@list')->name('list');
                Route::any('/add','CitiesController@cityAdd')->name('add');
                Route::any('/edit/{encryptCode}', 'CitiesController@edit')->name('edit');
                Route::put('/update', 'CitiesController@update')->name('update');
                Route::get('/{id}/change-change', 'CitiesController@change_status')->name('change_status');
                Route::get('/delete/{id}', 'CitiesController@delete')->name('delete');
                Route::get('/{id}', 'CitiesController@show')->name('show');
                Route::post('/get-states', 'CitiesController@getStates')->name('getStates');                
            });

            Route::group(['prefix' => 'shared-service', 'as' => 'shared-service.'], function () {
                Route::get('/', 'SharedServiceController@list')->name('list')->middleware('check_permissions:shared-service-list');
                Route::any('/add','SharedServiceController@sharedServiceAdd')->name('add')->middleware('check_permissions:shared-service-add');
                Route::any('/edit/{encryptCode}', 'SharedServiceController@edit')->name('edit')->middleware('check_permissions:shared-service-edit');
                Route::get('/{id}/change-change', 'SharedServiceController@change_status')->name('change_status')->middleware('check_permissions:shared-service-change-status');
                Route::delete('/{id}/delete', 'SharedServiceController@delete')->name('delete')->middleware('check_permissions:shared-service-delete');
                Route::get('/{id}', 'SharedServiceController@show')->name('show')->middleware('check_permissions:shared-service-show');
            });

            Route::group(['prefix' => 'spare-parts', 'as' => 'spare-parts.'], function () {
                Route::get('/', 'SparePartsController@list')->name('list')->middleware('check_permissions:spare-parts-list');
                Route::any('/add','SparePartsController@sparePartsAdd')->name('add')->middleware('check_permissions:spare-parts-add');
                Route::any('/edit/{encryptCode}', 'SparePartsController@edit')->name('edit')->middleware('check_permissions:spare-parts-edit');
                Route::get('/{id}/change-change', 'SparePartsController@change_status')->name('change_status')->middleware('check_permissions:spare-parts-change-status');
                Route::delete('/{id}/delete', 'SparePartsController@delete')->name('delete')->middleware('check_permissions:spare-parts-delete');
                Route::get('/{id}', 'SparePartsController@show')->name('show')->middleware('check_permissions:spare-parts-show');
            });


            Route::group(['prefix' => 'spare-part-orders', 'as' => 'spare_part_orders.'], function () {
                Route::get('/create-order','SparePartOrderController@create_order')->name('create_order');

                Route::get('/{shared_service_id}/add-to-cart', 'SparePartOrderController@add_to_cart')->name('add_to_cart');

                Route::get('/cart','SparePartOrderController@cart')->name('cart');

                Route::delete('/cart/{cart_id}/delete', 'SparePartOrderController@delete_cart')->name('delete_cart');

                Route::post('/{cart_id}/update-cart', 'SparePartOrderController@update_cart')->name('update_cart');

                Route::get('/checkout','SparePartOrderController@checkout')->name('checkout');

                Route::post('/submit-order', 'SparePartOrderController@submit_order')->name('submit_order');

                Route::get('/my-orders', 'SparePartOrderController@my_orders')->name('my_orders');

                Route::get('/my-orders/{order_id}/ajax', 'SparePartOrderController@ajax_my_order_details')->name('ajax_my_order_details');
                
                Route::get('{order_id}/download-invoice', 'SparePartOrderController@download_invoice')->name('download_invoice');

                /** manage spare part orders **/
                Route::get('/manage/orders','SparePartOrderController@order_list')->name('order_list');

                Route::put('/manage/orders/{order_id}/update_status','SparePartOrderController@update_order_status')->name('update_order_status');

                Route::get('/manage/orders/{order_id}','SparePartOrderController@order_details')->name('order_details');
                /******/

            });

            /**********/

            /* route for order shared services */
            Route::group(['prefix' => 'shared-service-orders', 'as' => 'shared_service_orders.'], function () {
                Route::get('/create-order','SharedServiceOrderController@create_order')->name('create_order');

                Route::get('/{shared_service_id}/add-to-cart', 'SharedServiceOrderController@add_to_cart')->name('add_to_cart');

                Route::get('/cart','SharedServiceOrderController@cart')->name('cart');

                Route::delete('/cart/{cart_id}/delete', 'SharedServiceOrderController@delete_cart')->name('delete_cart');

                Route::post('/{cart_id}/update-cart', 'SharedServiceOrderController@update_cart')->name('update_cart');

                Route::get('/checkout','SharedServiceOrderController@checkout')->name('checkout');

                Route::post('/submit-order', 'SharedServiceOrderController@submit_order')->name('submit_order');

                Route::get('/my-orders', 'SharedServiceOrderController@my_orders')->name('my_orders');

                Route::get('/my-orders/{order_id}/ajax', 'SharedServiceOrderController@ajax_my_order_details')->name('ajax_my_order_details');

                Route::get('{order_id}/download-invoice', 'SharedServiceOrderController@download_invoice')->name('download_invoice');

                /** manage spare part orders **/
                Route::get('/manage/orders','SharedServiceOrderController@order_list')->name('order_list');

                Route::put('/manage/orders/{order_id}/update_status','SharedServiceOrderController@update_order_status')->name('update_order_status');

                Route::get('/manage/orders/{order_id}','SharedServiceOrderController@order_details')->name('order_details');
                /******/

            });

            /*************/




            Route::group(['prefix' => 'message', 'as' => 'message.'], function () {
                Route::get('/', 'MessageController@list')->name('list')->middleware('check_permissions:message-list');
                Route::any('/add','MessageController@messageAdd')->name('add')->middleware('check_permissions:message-add');
                Route::get('/get-user', 'MessageController@getUser')->name('get-user');
                Route::any('/edit/{encryptCode}', 'MessageController@edit')->name('edit')->middleware('check_permissions:message-edit');
                Route::get('/{id}/change-change', 'MessageController@change_status')->name('change_status')->middleware('check_permissions:message-change-status');
                Route::get('/{id}', 'MessageController@show')->name('show')->middleware('check_permissions:message-show');
            });


            Route::group(['prefix' => 'email', 'as' => 'email.'], function () {
                Route::get('/', 'EmailTemplateController@list')->name('list');
                Route::any('/add','EmailTemplateController@emailAdd')->name('add');
                Route::get('/resend', 'EmailTemplateController@sendMail')->name('resend');
                Route::any('/edit/{encryptCode}', 'EmailTemplateController@edit')->name('edit');
                Route::get('/delete/{id}', 'EmailTemplateController@delete')->name('delete');
                Route::get('/{id}', 'EmailTemplateController@show')->name('show');
                
            });

            

            Route::group(['prefix' => 'service_management', 'as' => 'service_management.'], function () {
                Route::get('/', 'ServiceManagementController@list')->name('list')->middleware('check_permissions:service_management_list');
                Route::any('/add-service','ServiceManagementController@addService')->name('addService')->middleware('check_permissions:service_management-add-service');
                Route::post('/get-data', 'ServiceManagementController@getData')->name('getData');
               // Route::any('/add','ServiceManagementController@cityAdd')->name('add')->middleware('check_permissions:service_management_list');
                Route::any('/edit/{encryptCode}', 'ServiceManagementController@edit')->name('edit')->middleware('check_permissions:service_managemen_edit');
                Route::get('/{id}/change-change', 'ServiceManagementController@change_status')->name('change_status')->middleware('check_permissions:service_management_change-status');
                Route::any('/{id}/delete', 'ServiceManagementController@delete')->name('delete')->middleware('check_permissions:service_management-delete');
                Route::get('/{id}', 'ServiceManagementController@show')->name('show')->middleware('check_permissions:service_management-show');
            });


            Route::group(['prefix' => 'work-order-management', 'as' => 'work-order-management.'], function () {
                Route::get('/calendar', 'WorkOrderManagementController@calendar')->name('calendar');
                Route::get('/', 'WorkOrderManagementController@list')->name('list');
                Route::any('/create','WorkOrderManagementController@workOrderCreate')->name('workOrderCreate');
                Route::get('/get-contract-data', 'WorkOrderManagementController@getContractData')->name('getContractData');
                Route::get('/get-contract-service-status', 'WorkOrderManagementController@getContractServiceStatus')->name('getContractServiceStatus');

                Route::put('/{id}', 'WorkOrderManagementController@update')->name('update');  
                Route::get('/{id}', 'WorkOrderManagementController@show')->name('show'); 
                Route::get('/{id}/edit', 'WorkOrderManagementController@edit')->name('edit'); 

                
                Route::get('/labour-task-list/{id}', 'WorkOrderManagementController@labourTaskList')->name('labourTaskList'); 
                Route::get('/labour-task-create/{id}', 'WorkOrderManagementController@labourTaskCreate')->name('labourTaskCreate');
                Route::post('/assign-labour-task', 'WorkOrderManagementController@taskAssign')->name('taskAssign');

                Route::any('/{id}/delete', 'WorkOrderManagementController@delete')->name('delete');
                Route::post('/task-feedback', 'WorkOrderManagementController@taskFeedback')->name('taskFeedback'); 
                Route::get('/daily-task-show/{id}', 'WorkOrderManagementController@dailyTaskShow')->name('dailyTaskShow');
                Route::any('/{id}/edit-daily-task', 'WorkOrderManagementController@editDailyTask')->name('editDailyTask'); 
                Route::any('/{id}/delete-labour-task', 'WorkOrderManagementController@deleteLabourTask')->name('deleteLabourTask');
                
                Route::get('/{id}/change-status', 'WorkOrderManagementController@change_status')->name('change_status');
  
                
            });

            Route::group(['prefix' => 'calendar', 'as' => 'calendar.'], function () {
                Route::get('/calendar-data', 'CalendarController@calendardata')->name('calendardata')->middleware('check_permissions:calendar-data');  
                Route::any('/calendar-data-add','CalendarController@calendardataAdd')->name('calendardataAdd');
                Route::get('/{id}', 'TaskManagementController@show')->name('show');
                Route::post('/update-task', 'CalendarController@updateTask')->name('updateTask');  
                Route::post('/get-data', 'CalendarController@getData')->name('getData');       
                
            });

            /************************************/

            /*Routes for labour management */
            Route::group(['prefix'=>'labour','as'=>'labour.'],function(){
                Route::get('/', 'LabourController@list')->name('list');
                Route::get('/create', 'LabourController@create')->name('create');
                Route::post('/store', 'LabourController@store')->name('store');
                Route::get('/{id}', 'LabourController@show')->name('show');
                Route::get('/{id}/edit', 'LabourController@edit')->name('edit');
                Route::put('/{id}', 'LabourController@update')->name('update');
                Route::delete('/{id}/delete', 'LabourController@delete')->name('delete');
                Route::get('/{id}/change-change', 'LabourController@change_status')->name('change_status');
  
            });
            /*Routes for unit management */
            Route::group(['prefix'=>'unit','as'=>'unit.'],function(){
                Route::get('/', 'UnitController@list')->name('list');
                Route::any('/add','UnitController@add')->name('add');
                Route::any('/{id}/edit', 'UnitController@edit')->name('edit');
                Route::put('/{id}', 'UnitController@update')->name('update');
                Route::delete('/{id}/delete', 'UnitController@delete')->name('delete');
                Route::get('/{id}/change-change', 'UnitController@change_status')->name('change_status');
  
            });

            /*Routes for complaint management */
            Route::group(['prefix'=>'complaints','middleware'=>[],'as'=>'complaints.'],function(){
                Route::get('/', 'ComplaintController@list')->name('list');
                Route::get('/create', 'ComplaintController@create')->name('create');
                Route::post('/store', 'ComplaintController@store')->name('store');
                Route::get('/{id}', 'ComplaintController@show')->name('show');
                Route::get('/{id}/edit', 'ComplaintController@edit')->name('edit');
                Route::put('/{id}', 'ComplaintController@update')->name('update');
                Route::delete('/{id}/delete', 'ComplaintController@delete')->name('delete');

                Route::post('/{complaint_id}/add-note', 'ComplaintController@add_note')->name('add_note');
                Route::put('/{complaint_id}/update-note/{note_id}', 'ComplaintController@update_note')->name('update_note');
                Route::delete('/{complaint_id}/delete-note/{note_id}', 'ComplaintController@delete_note')->name('delete_note');

                Route::put('/{complaint_id}/update-status', 'ComplaintController@update_status')->name('update_status');
            });
            /************************************/

            
            
        });
       
});




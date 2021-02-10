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

// Route::group(["prefix" => "","namespace"=>"Frontend", 'as' => 'frontend.'], function() {
//     Route::get('/', 'QuotationController@create_quotation')->name('create_quotation');
//     Route::post('/quotation/store', 'QuotationController@store_quotation')->name('store_quotation');
// });


/* Start Admin's route */
Route::group(["prefix" => "admin","namespace"=>"admin", 'as' => 'admin.'], function() {

        Route::get('/', 'AuthController@index');
        Route::any('/login', 'AuthController@index')->name('login');
       
        Route::post('/authentication','AuthController@verifyCredentials')->name('authentication');
        Route::any('/forgot-password', 'AuthController@forgotPassword')->name('forgot.password');
        Route::any('/reset-password/{encryptCode}','AuthController@resetPassword')->name('reset.password');

        Route::put('/quotation/{quotation_id}/update_status', 'QuotationController@update_status')->name('quotations.update_status');
        

        Route::group(['middleware' => 'admin'], function () {

            Route::match(['get','post'],'/dashboard', 'DashboardController@index')->name('dashboard');
            Route::any('/edit-settings', 'DashboardController@editSetting')->name('settings');
            Route::any('/update-settings', 'DashboardController@updateSetting')->name('updateSetting');
            //Route::any('/settings', 'DashboardController@settings')->name('settings');
            
            
            Route::get('/logout', 'AuthController@logout')->name('logout');

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

            Route::group(['prefix' => 'mobile_brand','middleware'=>['check_permissions:manage-mobile-brand'], 'as' => 'mobile_brand.'], function () {
                Route::get('/', 'MobileBrandController@list')->name('list');
                Route::any('/add','MobileBrandController@mobileBrandAdd')->name('mobile_brand.add');
                Route::any('/edit/{encryptCode}', 'MobileBrandController@edit')->name('edit');
                Route::put('/update', 'MobileBrandController@update')->name('update');
                Route::get('/{id}/change-change', 'MobileBrandController@change_status')->name('change_status');
                Route::get('/delete/{id}', 'MobileBrandController@delete')->name('delete');
                Route::get('/{id}', 'MobileBrandController@show')->name('show');                
            });

            Route::group(['prefix' => 'mobile_brand_model','middleware'=>['check_permissions:manage-brand-model'], 'as' => 'mobile_brand_model.'], function () {
                Route::get('/', 'BrandModelController@list')->name('list');
                Route::any('/add','BrandModelController@stateAdd')->name('add');
                Route::any('/edit/{encryptCode}', 'BrandModelController@edit')->name('edit');
                Route::put('/update', 'BrandModelController@update')->name('update');
                Route::get('/{id}/change-change', 'BrandModelController@change_status')->name('change_status');
                Route::get('/delete/{id}', 'BrandModelController@delete')->name('delete');
                Route::get('/{id}', 'BrandModelController@show')->name('show');
                
            });

            Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
                Route::get('/', 'OrderController@list')->name('list');
                Route::any('/add','OrderController@orderAdd')->name('add');
                Route::any('/edit/{encryptCode}', 'OrderController@edit')->name('edit');
                Route::put('/update', 'OrderController@update')->name('update');
                Route::get('/{id}/change-change', 'OrderController@change_status')->name('change_status');
                Route::get('/delete/{id}', 'OrderController@delete')->name('delete');
                Route::get('/{id}', 'OrderController@show')->name('show');
                Route::post('/get-states-order', 'OrderController@getStates')->name('getStates');
                Route::post('/get-cities-order', 'OrderController@getCityList')->name('getCityList');  
                Route::post('/get-brand-model', 'OrderController@getModelList')->name('getModelList'); 
                 
                
            });

             /*Routes for notifications management */
            Route::group(['prefix'=>'notifications','middleware'=>[],'as'=>'notifications.'],function(){
                Route::get('/', 'NotificationController@list')->name('list');
                Route::get('/{notification_id}', 'NotificationController@details')->name('details');
            });

            
            /*************/

            Route::group(['prefix' => 'email','middleware'=>['check_permissions:manage-email-template'], 'as' => 'email.'], function () {
                Route::get('/', 'EmailTemplateController@list')->name('list');
                Route::any('/add','EmailTemplateController@emailAdd')->name('add');
                Route::get('/resend', 'EmailTemplateController@sendMail')->name('resend');
                Route::any('/edit/{encryptCode}', 'EmailTemplateController@edit')->name('edit');
                Route::get('/delete/{id}', 'EmailTemplateController@delete')->name('delete');
                Route::get('/{id}', 'EmailTemplateController@show')->name('show');
                
            });


            /*Routes for notifications management */
            Route::group(['prefix'=>'reports','middleware'=>[],'as'=>'reports.'],function(){
                Route::any('/', 'ReportController@index')->name('index');
                
                Route::post('/schedule-compliance-report', 'ReportController@schedule_compliance_report')->name('schedule_compliance_report');
               
                Route::post('/maintenance-backlog-report', 'ReportController@maintenance_backlog_report')->name('maintenance_backlog_report');
               

                Route::post('/upcoming-weekly-maintenance-report', 'ReportController@upcoming_weekly_maintenance_report')->name('upcoming_weekly_maintenance_report');

               

                Route::post('/upcoming-schedule-maintenance-report', 'ReportController@upcoming_schedule_maintenance_report')->name('upcoming_schedule_maintenance_report');


                Route::post('/work-order-report', 'ReportController@work_order_report')->name('work_order_report');

                Route::post('/work-order-completed-per-month', 'ReportController@work_order_completed_per_month_report')->name('work_order_completed_per_month_report');

                Route::post('/work-order-requested-vs-completed', 'ReportController@work_order_requested_vs_completed_report')->name('work_order_requested_vs_completed_report');

                Route::post('/contract-status','ReportController@contract_status_report')->name('contract_status_report');

                Route::post('/payment-report','ReportController@payment_report')->name('payment_report');

                

                Route::get('/get-property-list', 'ReportController@getAssignedProperty')->name('getAssignedProperty');

                Route::get('/get-work-order-list', 'ReportController@getWorkOderList')->name('getWorkOderList');

                Route::get('/get-task-list', 'ReportController@getTaskList')->name('getTaskList');

                Route::get('/get-service-list', 'ReportController@getServices')->name('getServices');

                Route::get('/get-labour-list', 'ReportController@getLabourList')->name('getLabourList');

                
                
                

            });
            /************************************/


        });
       
});




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

Route::get('/', function () {
    return redirect()->route('admin.login');
});

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
        Route::get('/login', 'AuthController@index')->name('login');
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

            /*Routes for role/group management */
            Route::group(['prefix'=>'roles','as'=>'roles.'],function(){
                Route::get('/', 'RoleController@list')->name('list');
                Route::get('/create', 'RoleController@create')->name('create');
                Route::post('/store', 'RoleController@store')->name('store');
                Route::get('/{id}', 'RoleController@show')->name('show');
                Route::get('/{id}/edit', 'RoleController@edit')->name('edit');
                Route::put('/{id}', 'RoleController@update')->name('update');
                Route::delete('/{id}/delete', 'RoleController@delete')->name('delete');
                Route::get('/{id}/change-status', 'RoleController@change_status')->name('change_status');
                Route::post('/ajax/check_role_name_unique/{role_id?}', 'RoleController@ajax_check_role_name_unique')
                ->name('ajax_check_role_name_unique');
                
                Route::post('/ajax/ajax_parent_module_permissions/{role_id?}', 'RoleController@ajax_parent_module_permissions')
                ->name('ajax_parent_module_permissions');

            });
            /************************************/

            /*Routes for property type management */
            Route::group(['prefix'=>'property-types','as'=>'property_types.'],function(){
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
            Route::group(['prefix'=>'services','as'=>'services.'],function(){
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
                Route::get('/', 'QuotationController@list')->name('list');
                Route::get('/{id}', 'QuotationController@show')->name('show');
                Route::delete('/{id}/delete', 'QuotationController@delete')->name('delete');
            });
            /************************************/

            /*Routes for service management */
            Route::group(['prefix'=>'service-providers','as'=>'service_providers.'],function(){
                Route::get('/', 'ServiceProviderController@list')->name('list');
                Route::get('/create', 'ServiceProviderController@create')->name('create');
                Route::post('/store', 'ServiceProviderController@store')->name('store');
                Route::get('/{id}', 'ServiceProviderController@show')->name('show');
                Route::get('/{id}/edit', 'ServiceProviderController@edit')->name('edit');
                Route::put('/{id}', 'ServiceProviderController@update')->name('update');
                Route::delete('/{id}/delete', 'ServiceProviderController@delete')->name('delete');
                Route::get('/{id}/change-change', 'ServiceProviderController@change_status')->name('change_status');

            });
            /************************************/

            /*Routes for property owner management */
            Route::group(['prefix'=>'property-owners','as'=>'property_owners.'],function(){
                Route::get('/', 'PropertyOwnerController@list')->name('list');
                Route::get('/create', 'PropertyOwnerController@create')->name('create');
                Route::post('/store', 'PropertyOwnerController@store')->name('store');
                Route::get('/{id}', 'PropertyOwnerController@show')->name('show');
                Route::get('/{id}/edit', 'PropertyOwnerController@edit')->name('edit');
                Route::put('/{id}', 'PropertyOwnerController@update')->name('update');
                Route::delete('/{id}/delete', 'PropertyOwnerController@delete')->name('delete');
                Route::get('/{id}/change-change', 'PropertyOwnerController@change_status')->name('change_status');
  
            });
            /************************************/
            /*Routes for property manager management */
            Route::group(['prefix'=>'property-managers','as'=>'property_managers.'],function(){
                Route::get('/', 'PropertyManagerController@list')->name('list');
                Route::get('/create', 'PropertyManagerController@create')->name('create');
                Route::post('/store', 'PropertyManagerController@store')->name('store');
                Route::get('/{id}', 'PropertyManagerController@show')->name('show');
                Route::get('/{id}/edit', 'PropertyManagerController@edit')->name('edit');
                Route::put('/{id}', 'PropertyManagerController@update')->name('update');
                Route::delete('/{id}/delete', 'PropertyManagerController@delete')->name('delete');
                Route::get('/{id}/change-change', 'PropertyManagerController@change_status')->name('change_status');
  
            });
            
            /************************************/

            /*Routes for users management */
            Route::group(['prefix'=>'users','as'=>'users.'],function(){
                Route::get('/', 'UserController@list')->name('list');
                Route::get('/create', 'UserController@create')->name('create');
                Route::post('/store', 'UserController@store')->name('store');
                Route::get('/{id}', 'UserController@show')->name('show');
                Route::get('/{id}/edit', 'UserController@edit')->name('edit');
                Route::put('/{id}', 'UserController@update')->name('update');
                Route::delete('/{id}/delete', 'UserController@delete')->name('delete');
                Route::get('/{id}/change-change', 'UserController@change_status')->name('change_status');
  
            });
            
            /************************************/

            /*Routes for property management */
            Route::group(['prefix'=>'properties','as'=>'properties.'],function(){
                Route::get('/', 'PropertyController@list')->name('list');
                Route::get('/create', 'PropertyController@create')->name('create');
                Route::post('/store', 'PropertyController@store')->name('store');
                Route::get('/{id}', 'PropertyController@show')->name('show');
                Route::get('/{id}/edit', 'PropertyController@edit')->name('edit');
                Route::put('/{id}', 'PropertyController@update')->name('update');
                Route::delete('/{id}/delete', 'PropertyController@delete')->name('delete');
                Route::get('/{id}/change-change', 'PropertyController@change_status')->name('change_status');
                Route::get('attachment/{id}/download', 'PropertyController@download_attachment')->name('download_attachment');
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
            Route::group(['prefix' => 'attendance-management', 'as' => 'attendance-management.'], function () {
                Route::get('/list', 'AttendanceController@list')->name('list');
            });

            Route::group(['prefix' => 'country', 'as' => 'country.'], function () {
                Route::get('/', 'CountryController@list')->name('list');
                Route::any('/add','CountryController@countryAdd')->name('country.add');
                Route::any('/edit/{encryptCode}', 'CountryController@edit')->name('edit');
                Route::put('/update', 'CountryController@update')->name('update');
                Route::get('/{id}/change-change', 'CountryController@change_status')->name('change_status');
                Route::delete('/{id}/delete', 'CountryController@delete')->name('delete');
                Route::get('/{id}', 'CountryController@show')->name('show');                
            });

            Route::group(['prefix' => 'state', 'as' => 'state.'], function () {
                Route::get('/', 'StateController@list')->name('list');
                Route::any('/add','StateController@stateAdd')->name('add');
                Route::any('/edit/{encryptCode}', 'StateController@edit')->name('edit');
                Route::put('/update', 'StateController@update')->name('update');
                Route::get('/{id}/change-change', 'StateController@change_status')->name('change_status');
                Route::delete('/{id}/delete', 'StateController@delete')->name('delete');
                Route::get('/{id}', 'StateController@show')->name('show');
                
            });

            Route::group(['prefix' => 'city', 'as' => 'city.'], function () {
                Route::get('/', 'CityController@list')->name('list');
                Route::any('/add','CityController@cityAdd')->name('add');
                Route::any('/edit/{encryptCode}', 'CityController@edit')->name('edit');
                Route::put('/update', 'CityController@update')->name('update');
                Route::get('/{id}/change-change', 'CityController@change_status')->name('change_status');
                Route::delete('/{id}/delete', 'CityController@delete')->name('delete');
                Route::get('/{id}', 'CityController@show')->name('show');
                Route::post('/get-zone', 'CityController@getZone')->name('getZone');                
            });

            Route::group(['prefix' => 'shared-service', 'as' => 'shared-service.'], function () {
                Route::get('/', 'SharedServiceController@list')->name('list');
                Route::any('/add','SharedServiceController@sharedServiceAdd')->name('add');
                Route::any('/edit/{encryptCode}', 'SharedServiceController@edit')->name('edit');
                Route::get('/{id}/change-change', 'SharedServiceController@change_status')->name('change_status');
                Route::delete('/{id}/delete', 'SharedServiceController@delete')->name('delete');
                Route::get('/{id}', 'SharedServiceController@show')->name('show');
            });

            Route::group(['prefix' => 'spare-parts', 'as' => 'spare-parts.'], function () {
                Route::get('/', 'SparePartsController@list')->name('list');
                Route::any('/add','SparePartsController@sparePartsAdd')->name('add');
                Route::any('/edit/{encryptCode}', 'SparePartsController@edit')->name('edit');
                Route::get('/{id}/change-change', 'SparePartsController@change_status')->name('change_status');
                Route::delete('/{id}/delete', 'SparePartsController@delete')->name('delete');
                Route::get('/{id}', 'SparePartsController@show')->name('show');
            });

            Route::group(['prefix' => 'message', 'as' => 'message.'], function () {
                Route::get('/', 'MessageController@list')->name('list');
                Route::any('/add','MessageController@messageAdd')->name('add');
                Route::any('/edit/{encryptCode}', 'MessageController@edit')->name('edit');
                Route::get('/{id}/change-change', 'MessageController@change_status')->name('change_status');
                Route::delete('/{id}/delete', 'MessageController@delete')->name('delete');
                Route::get('/{id}', 'MessageController@show')->name('show');
            });
        });
       
});


Route::get('/{any}', 'Controller@callFrontendRoute')->where('any', '.*');

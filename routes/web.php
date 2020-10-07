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
            Route::any('/settings', 'DashboardController@settings')->name('settings');
            Route::get('/logout', 'AuthController@logout')->name('logout');
            Route::get('/change-password','DashboardController@showChangePasswordForm')->name('changePassword');
            Route::post('/change-password','DashboardController@changePassword')->name('changePassword');

            Route::group(['prefix'=>'roles','as'=>'roles.'],function(){
                Route::get('/', 'RoleController@list')->name('list');
                Route::get('/create', 'RoleController@create')->name('create');
                Route::post('/store', 'RoleController@store')->name('store');
                Route::get('/{id}', 'RoleController@show')->name('show');
                Route::get('/{id}/edit', 'RoleController@edit')->name('edit');
                Route::put('/{id}', 'RoleController@update')->name('update');
                Route::delete('/{id}/delete', 'RoleController@delete')->name('delete');
                Route::get('/{id}/change-change', 'RoleController@change_status')->name('change_status');
            });



            Route::group(['prefix' => 'user-management', 'as' => 'user-management.'], function () {
                Route::get('/role-list', 'RoleController@roleList')->name('role-list');
                Route::any('/role-add','RoleController@roleAdd')->name('role-add');
                Route::any('/role-permission/{encryptCode}','RoleController@rolePermission')->name('role.permission');
                Route::get('/edit/{id}', 'RoleController@editRole')->name('edit')->where('id','[0-9]+');
                Route::post('/edit-submit/{id}', 'RoleController@editRole')->name('editSubmit')->where('id','[0-9]+');
                Route::get('/delete/{id}', 'RoleController@roleDelete')->name('delete')->where('id','[0-9]+');
                Route::get('/reset-role-status/{encryptCode}','RoleController@resetRoleStatus')->name('reset-role-status');
                Route::get('/admin-user-list', 'UserController@userList')->name('user.list');
                Route::get('/user-list-table', 'UserController@userListTable')->name('user.list.table');
                Route::get('/site-user-list', 'UserController@SiteuserList')->name('site.user.list');
                Route::get('/site-user-list-table', 'UserController@SiteuserListTable')->name('site.user.list.table');
                Route::any('/user-add','UserController@userAdd')->name('user.add');
                Route::get('/user-edit/{encryptCode}', 'UserController@userEdit')->name('user-edit');
                Route::post('/user-edit-submit/{encryptCode}', 'UserController@userEdit')->name('user-editSubmit');
                Route::any('/user-change-password/{encryptCode}', 'UserController@userChangePassword')->name('user-changepassword');
                Route::get('/user-delete/{encryptCode}','UserController@userDelete')->name('user-delete');
                Route::get('/reset-user-status/{encryptCode}','UserController@resetuserStatus')->name('reset-user-status');
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
            Route::group(['prefix' => 'attendance-management', 'as' => 'attendance-management.'], function () {
                Route::get('/list', 'AttendanceController@list')->name('list');
            });

            Route::group(['prefix' => 'country', 'as' => 'country.'], function () {
                Route::get('/', 'CountryController@list')->name('list');
               // Route::get('/module-list-table', 'ModuleManagementController@moduleListTable')->name('list.table');
                Route::any('/add','CountryController@countryAdd')->name('country.add');
                Route::any('/edit/{encryptCode}', 'CountryController@edit')->name('edit');
                Route::put('/update', 'CountryController@update')->name('update');
                Route::get('/{id}/change-change', 'CountryController@change_status')->name('change_status');
                Route::delete('/{id}/delete', 'CountryController@delete')->name('delete');
                Route::get('/{id}', 'CountryController@show')->name('show');
                
                
            });

            Route::group(['prefix' => 'state', 'as' => 'state.'], function () {
                Route::get('/', 'CountryController@list')->name('list');
               // Route::get('/module-list-table', 'ModuleManagementController@moduleListTable')->name('list.table');
                Route::any('/add','StateController@countryAdd')->name('add');
                Route::any('/edit/{encryptCode}', 'StateController@edit')->name('edit');
                Route::put('/update', 'StateController@update')->name('update');
                Route::get('/{id}/change-change', 'StateController@change_status')->name('change_status');
                Route::delete('/{id}/delete', 'StateController@delete')->name('delete');
                Route::get('/{id}', 'StateController@show')->name('show');
                
            });
        });
       
});


Route::get('/{any}', 'Controller@callFrontendRoute')->where('any', '.*');

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


Auth::routes();

Route::namespace('Admin')->name('admin.')->prefix('admin')->middleware(['auth','2fa'])->group(function () {
    // Controllers Within The "App\Http\Controllers\Admin" Namespace

    /* USER */
    Route::get('/users', 'UserController@index')->name('users');
    Route::get('/users/{id}', 'UserController@show')->name('user');
    Route::get('/profile', 'UserController@showProfile')->name('profile');
    Route::get('/user/create', 'UserController@create')->name('user.create');
    Route::post('/user/register', 'UserController@register')->name('user.register');
    Route::get('/user/complete-registration', 'UserController@completeRegistration')->name('user.register.complete');
    Route::get('/user/complete-update', 'UserController@completeUpdate')->name('user.update.complete');
    Route::post('/user/store', 'UserController@store')->name('user.store');
    Route::post('/user/update', 'UserController@update')->name('user.update');
    Route::post('/user/delete', 'UserController@destroy')->name('user.destroy');
    Route::post('/users/{id}/notifications/delete', 'NotificationController@delete')->name('notifications.delete');
    Route::get('/users/{id}/reauthenticate', 'UserController@reauthenticate')->name('user.reauthenticate');
    Route::post('/2fa', function () { return redirect(URL()->previous());})->name('2fa');

    /* Roles */
    Route::get('/roles/{id}', 'RoleController@index')->name('role');
    Route::get('/role/create', 'RoleController@create')->name('role.create');
    Route::post('/role/store', 'RoleController@store')->name('role.store');
    Route::post('/roles/{id}/update', 'RoleController@update')->name('role.update');
    Route::post('/roles/{id}/destroy', 'RoleController@destroy')->name('role.destroy');

    /* Permissions */
    Route::get('/permissions', 'PermissionController@index')->name('permissions');
    Route::post('/roles/{id}/permissions', 'PermissionController@getPermissionByRole');
    Route::post('/roles/{id}/permissions/update', 'PermissionController@updatePermissionByRole');
    Route::post('/permissions', 'PermissionController@update')->name('permissions.update');

    Route::get('/', 'VehicleController@index')->name('index');

    /* Update Settings*/
    Route::get('/settings', 'SettingController@index')->name('settings');
    Route::post('/settings', 'SettingController@update')->name('settings.update');

    /* Vehicle */
    Route::get('/vehicles/', 'VehicleController@index')->name('vehicles.index');
    Route::get('/vehicles/vehicledata/{typeId}', 'VehicleController@getUpdatedVehicleData')->name('vehicleData');
    Route::get('/vehicle/{typeId}', 'VehicleController@create')->name('vehicle.create');
    Route::get('/vehicles/{id}', 'VehicleController@show')->name('vehicle.show');
    Route::post('/vehicle/store', 'VehicleController@store')->name('vehicle.store');
    Route::post('/vehicle/update', 'VehicleController@update')->name('vehicle.update');
    Route::post('/vehicle/destroy', 'VehicleController@destroy')->name('vehicle.destroy');
    Route::post('/vehicle/update/specifications', 'VehicleController@updateSpecifications')->name('vehicle.update.specifications');
    Route::post('/vehicle/upload/photos', 'VehicleController@uploadPhotos')->name('vehicle.upload.photos');
    Route::post('/vehicle/update/photos', 'VehicleController@updatePhotos')->name('vehicle.update.photos');
    Route::post('/vehicle/update/photos/position', 'VehicleController@updatePhotosPosition')->name('vehicle.update.photos.position');
    Route::post('/vehicle/update/youtube', 'VehicleController@updateYoutube')->name('vehicle.update.youtube');
    Route::post('/vehicle/update/status', 'VehicleController@updateStatus')->name('vehicle.update.status');
    Route::post('/stock/update', 'VehicleController@updateStock')->name('stock.update');
    Route::post('/vehicle/lookup', 'LookupController@lookup');

    /* Import FOR Autotrader */
    Route::get('/vehicles/autotrader/importer', 'AutotraderImporter@index')->name('autotrader.importer.index');
    Route::post('/vehicles/autotrader/create', 'AutotraderImporter@create')->name('autotrader.importer.create');
    Route::get('/vehicles/autotrader/download/{id}', 'AutotraderImporter@download')->name('autotrader.importer.download');
    Route::get('/vehicles/autotrader/delete/{id}', 'AutotraderImporter@delete')->name('autotrader.importer.delete');
    Route::post('/vehicles/autotrader/process/{id}', 'AutotraderImporter@process')->name('autotrader.importer.process');

    /* Import FROM Autotrader */
    Route::get('/import/autotrader/xml', 'AutotraderExporter@index')->name('autotrader.exporter.index');
    Route::post('/import/autotrader/xml/process', 'AutotraderExporter@process')->name('autotrader.exporter.process');

    /* Import FROM CSV */
    Route::get('/import/csv/index', 'ImportFromCSVController@index')->name('csv.import.index');
    Route::post('/import/csv/process', 'ImportFromCSVController@processCSV')->name('csv.import.process');

    Route::get("/resize/images",'VehicleController@ResizeAllImages');

});

    /*
     *  API Routes to get Data in Json format
     * */
    Route::prefix('api')->middleware('auth')->group(function () {
    Route::get('/categories/{id}', 'ApiController@categories');
    Route::get('/makes', 'ApiController@makes');
    Route::get('/makes/{id}/models', 'ApiController@models');
    Route::get('/fuelTypes', 'ApiController@fuelTypes');
    Route::get('/emissionClasses', 'ApiController@emissionClasses');
    Route::get('/colours', 'ApiController@colours');
    Route::get('/registration-plates', 'ApiController@registrationPlates');
    Route::get('/body-types/{categoryId}', 'ApiController@bodyTypes');
    Route::get('/axle-drive/{type}/{category}', 'ApiController@axleDrives');
    Route::get('/cab-types/{categoryId}', 'ApiController@cabTypes');
});


Route::get('/',function (){
    return redirect()->route('admin.vehicles.index');
});


<?php
Route::get('/', function() {
    return redirect(route('admin.dashboard'));
});

Route::get('home', function() {
    return redirect(route('admin.dashboard'));
});


Route::prefix('holidays')->group(function() {
    Route::get('/', 'Holidays\ManageHolidaysController@index')->name("holiday.index");
    Route::get('count-working/{startDate}', 'Holidays\ManageHolidaysController@countWorking')->name('holiday.count.working');

});

Route::prefix('company')->middleware('auth')->group(function(){
    Route::get('/', 'Company\ManageCompanyController@index')->name('company.index')->middleware(['role:Admin']);
    Route::get('data', 'Company\ManageCompanyController@data')->name('company.data');
    Route::get('create', 'Company\ManageCompanyController@create')->name('company.create');
    Route::post('create', 'Company\ManageCompanyController@store')->name('company.store');
    Route::get('edit/{id}', 'Company\ManageCompanyController@edit')->name('company.edit');
    Route::post('update/{id}', 'Company\ManageCompanyController@update')->name('company.update');
    Route::delete('delete/{id}', 'Company\ManageCompanyController@destroy')->name('company.destroy');
});

Route::prefix("employee")->middleware("auth")->group(function(){
    Route::get("/", "Company\EmployeeController@index")->name("employee.index");
    Route::get("create", "Company\EmployeeController@create")->name("employee.create");
    Route::post("store", "Company\EmployeeController@store")->name("employee.store");
    Route::get("edit/{id}", "Company\EmployeeController@edit")->name("employee.edit");
    Route::post("update/{id}", "Company\EmployeeController@update")->name("employee.update");
});

Route::prefix("leave")->middleware("auth")->group(function(){
    Route::get("create", "Holidays\ManageHolidaysController@createLeave");
    Route::post("store", "Holidays\ManageHolidaysController@storeLeave")->name("leave.store");
    Route::post("submit-leave", "Holidays\ManageHolidaysController@summitLeave")->name("submit.leave");
    Route::post("approve", "Holidays\ManageHolidaysController@approve")->name("approve.leave");
    Route::get('/', 'Holidays\ManageHolidaysController@indexLeave');
});

Route::prefix("ovense")->middleware("auth")->group(function(){
    Route::get("/", "Company\OvenseController@index")->name("ovense.index");
    Route::get("create", "Company\OvenseController@create")->name("ovense.create");
    Route::post("store", "Company\OvenseController@store")->name("ovense.store");
    Route::get("edit/{id}", "Company\OvenseController@edit")->name("ovense.edit");
    Route::post("update/{id}", "Company\OvenseController@update")->name("ovense.update");
});



























Route::name('admin.')->prefix('admin')->middleware('auth')->group(function() {
    Route::get('dashboard', 'DashboardController')->name('dashboard');

    Route::get('users/roles', 'UserController@roles')->name('users.roles');
    Route::resource('users', 'UserController', [
        'names' => [
            'index' => 'users'
        ]
    ])->middleware(['permission:manage-users']);
});

Route::middleware('auth')->get('logout', function() {
    Auth::logout();
    return redirect(route('login'))->withInfo('You have successfully logged out!');
})->name('logout');

Auth::routes(['verify' => true]);

Route::name('js.')->group(function() {
    Route::get('dynamic.js', 'JsController@dynamic')->name('dynamic');
});

// Get authenticated user
Route::get('users/auth', function() {
    return response()->json(['user' => Auth::check() ? Auth::user() : false]);
});

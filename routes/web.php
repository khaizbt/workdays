<?php
Route::get('/', function() {
    return redirect(route('admin.dashboard'));
});

Route::get('home', function() {
    return redirect(route('admin.dashboard'));
});

Route::get("file/{file}", "DashboardController@getFile")->name('get.file')->middleware("auth");
Route::get("salary", "Holidays\ManageHolidaysController@countSalaryEmployee")->middleware("auth");
Route::prefix('holidays')->group(function() {
    Route::get('/', 'Holidays\ManageHolidaysController@index')->name("holiday.index");
    Route::get('count-working/{startDate}', 'Holidays\ManageHolidaysController@countWorking')->name('holiday.count.working');

});

Route::prefix('company')->middleware('auth')->group(function(){
    Route::get('/', 'Company\ManageCompanyController@index')->name('company.index')->middleware(['role:Super']);
    Route::get('data', 'Company\ManageCompanyController@data')->name('company.data')->middleware(['role:Super']);
    Route::get('create', 'Company\ManageCompanyController@create')->name('company.create')->middleware(['role:Super']);
    Route::post('create', 'Company\ManageCompanyController@store')->name('company.store')->middleware(['role:Super']);
    Route::get('edit/{id}', 'Company\ManageCompanyController@edit')->name('company.edit')->middleware(['role:Super', 'role:Admin']);
    Route::post('update/{id}', 'Company\ManageCompanyController@update')->name('company.update')->middleware(['role:Super', 'role:Admin']);
    Route::delete('delete/{id}', 'Company\ManageCompanyController@destroy')->name('company.destroy')->middleware(['role:Super']);
    Route::get("edit-company", 'Company\ManageCompanyController@editCompany')->name('edit.company');
    Route::post("update-company", 'Company\ManageCompanyController@updateCompany')->name('update.company');
});

Route::prefix("employee")->middleware("auth")->group(function(){
    Route::get("/", "Company\EmployeeController@index")->name("employee.index")->middleware(['role:Admin']);
    Route::get("create", "Company\EmployeeController@create")->name("employee.create")->middleware(['role:Admin']);
    Route::post("store", "Company\EmployeeController@store")->name("employee.store")->middleware(['role:Admin']);
    Route::get("edit/{id}", "Company\EmployeeController@edit")->name("employee.edit")->middleware(['role:Admin']);
    Route::post("update/{id}", "Company\EmployeeController@update")->name("employee.update")->middleware(['role:Admin']);
    Route::get("salary", "Holidays\ManageHolidaysController@countSalaryEmployeeAll")->name('salary.index')->middleware(['role:Admin']);
    Route::get("salary/{employee}" , "Holidays\ManageHolidaysController@detailSalary")->name("salary.show");
    Route::get("data", "Company\EmployeeController@data")->name("employee.data")->middleware(['role:Admin']);
    Route::delete("delete/{id}", "Company\EmployeeController@delete")->middleware(['role:Admin']);
    Route::get("export-salary/{id}", "Holidays\ManageHolidaysController@exportPdf");
    Route::post("export-excel-salary", "Company\EmployeeController@exportExcel"); //TODO Nama Excel
});

Route::prefix("leave")->middleware("auth")->group(function(){
    Route::get("create", "Holidays\ManageHolidaysController@createLeave")->name("leave.create")->middleware(['role:Admin']);
    Route::post("store", "Holidays\ManageHolidaysController@storeLeave")->name("leave.store")->middleware(['role:Admin']);
    Route::get("submit", "Holidays\ManageHolidaysController@submitIndex")->name("submit.leave.index");
    Route::post("submit-leave", "Holidays\ManageHolidaysController@summitLeave")->name("submit.leave");
    Route::post("approve/{id}", "Holidays\ManageHolidaysController@approve")->name("approve.leave")->middleware(['role:Admin']);
    Route::get('/', 'Holidays\ManageHolidaysController@indexLeave')->name("leave.index")->middleware(['role:Admin']);
    Route::get("data", "Holidays\ManageHolidaysController@dataLeave")->name("leave.data")->middleware(['role:Admin']);
    Route::get("edit/{id}", "Holidays\ManageHolidaysController@editLeave")->name("leave.edit")->middleware(['role:Admin']);
    Route::post("update/{id}", "Holidays\ManageHolidaysController@updateLeave")->name("leave.update")->middleware(['role:Admin']);
    Route::delete("delete/{id}", "Holidays\ManageHolidaysController@deleteLeave")->name("leave.delete")->middleware(['role:Admin']);
    Route::get("count-cuti", "Holidays\ManageHolidaysController@countHolidaysYear");
    Route::get("count-cuti-employee", "Holidays\ManageHolidaysController@countHolidaysEmployee");
    Route::get("my-leave/data", "Holidays\ManageHolidaysController@listMyLeave")->name("myleave.data");
    Route::get("my-leave", "Holidays\ManageHolidaysController@myLeave");
    Route::post("reject/{id}", "Holidays\ManageHolidaysController@reject");
});

Route::prefix("ovense")->middleware("auth")->group(function(){
    Route::get("/", "Company\OvenseController@index")->name("ovense.index")->middleware(['role:Admin']);
    Route::get("data", "Company\OvenseController@data")->name("ovense.data")->middleware(['role:Admin']);
    Route::get("create", "Company\OvenseController@create")->name("ovense.create")->middleware(['role:Admin']);
    Route::post("store", "Company\OvenseController@store")->name("ovense.store")->middleware(['role:Admin']);
    Route::get("edit/{id}", "Company\OvenseController@edit")->name("ovense.edit")->middleware(['role:Admin']);
    Route::post("update/{id}", "Company\OvenseController@update")->name("ovense.update")->middleware(['role:Admin']);
    Route::delete("delete/{id}", "Company\OvenseController@delete")->name("ovense.delete")->middleware(['role:Admin']);
    Route::get("my-ovense", "Company\OvenseController@myOvense");
});

Route::prefix("salary-cut")->middleware("auth")->group(function(){
    Route::get("/", "Company\SalaryCutController@index")->name("salarycut.index")->middleware(['role:Admin']);
    Route::get("data", "Company\SalaryCutController@data")->name("salarycut.data")->middleware(['role:Admin']);
    Route::get("create", "Company\SalaryCutController@create")->name("salarycut.create")->middleware(['role:Admin']);
    Route::post("store", "Company\SalaryCutController@store")->name("salarycut.store")->middleware(['role:Admin']);
    Route::get("edit/{id}", "Company\SalaryCutController@edit")->name("salarycut.edit")->middleware(['role:Admin']);
    Route::post("update/{id}","Company\SalaryCutController@update")->name("salarycut.update")->middleware(['role:Admin']);
    Route::delete("delete/{id}", "Company\SalaryCutController@delete")->name("salarycut.delete")->middleware(['role:Admin']);
    Route::get("my-salary-cut", "Company\SalaryCutController@mySalaryCut");
});

Route::prefix("notification")->middleware("auth")->group(function(){
    Route::get("read/{id}", "NotificationController@readNotif")->name("notif.read");
    Route::get("read-all", 'NotificationController@readNotifAll')->name('notif.read.all');
});

Route::prefix("event")->middleware("auth")->group(function(){
    Route::get("/", "EventController@index")->name("event.index")->middleware(['role:Admin']);
    Route::get("data", "EventController@data")->name("event.data")->middleware(['role:Admin']);
    Route::get("create", "EventController@create")->name("event.create")->middleware(['role:Admin']);
    Route::post("store", "EventController@store")->name("event.store")->middleware(['role:Admin']);
    Route::post("update/{id}", "EventController@update")->name("event.update")->middleware(['role:Admin']);
    Route::get("edit/{id}", "EventController@edit")->name("event.edit")->middleware(['role:Admin']);
    Route::delete("delete/{id}", "EventController@delete")->middleware(['role:Admin']);
    Route::get('my-event', 'EventController@myEvent');
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

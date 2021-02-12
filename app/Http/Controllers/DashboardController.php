<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Company;
use App\Models\Employee;
use Auth;
use Storage;
use Image;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // return Auth::user()->unreadNotifications[0];
        // foreach(Auth::user()->unreadNotifications as $key => $value){
        //     return $value['data']['message'];
        // }
        $user = User::where('id', Auth::id())->first();

        if($user['level'] == 2) {
            $company = Company::where('id_user', $user['id'])->first();
            if($company)
                session(["company_id" => $company['id']]);
            else
                session(["company_id" => 1]);

        } elseif ($user['level'] == 3){
            $employee = Employee::where("user_id", Auth::id())->first();

            session(["company_id" => $employee['company_id']]);
        }
        return view('admin.dashboard.index');
    }

    public function getFile($file){
        $file_storage = str_replace('+', '/', $file);
        $file = Storage::disk('local')->get($file_storage);

        return Image::make($file)->response();
    }
}

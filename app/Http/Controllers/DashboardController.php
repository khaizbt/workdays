<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Company;
use App\Models\Employee;
use Auth;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
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
}

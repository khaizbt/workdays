<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Employee;
use App\User;
use App\Company;
use Auth;
use Hash;
use DB;
class EmployeeController extends Controller
{
    public function index() {

    }

    public function create() {
        return view("employee.create");
    }

    public function store(Request $request) {
        $post = $request->except("_token");
        // return $post;
        DB::beginTransaction();
        // try {
            $save = User::create([
                'name' => $post['name'],
                'email' => $post['email'],
                'password' => Hash::make($post['password']),
                'level' => 3
            ]);
            $company = Company::where("id_user",2)->first();
            if($save) {
                $employee = Employee::create([
                    "name" => $post['name'],
                    'position' => $post['position'],
                    "status" => $post['status'],
                    "salary" => $post['salary'],
                    "company_id" => $company['id'],
                    "user_id" => $save['id'],
                ]);

                

            }
            DB::commit();
            return $company;

        // } catch (\Throwable $th) {
        //     //throw $th;
        // }
    }
}

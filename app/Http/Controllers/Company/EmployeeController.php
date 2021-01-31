<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\User;
use App\Models\Company;
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
        try {
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

        } catch (\Throwable $th) {
            DB::rollback();
        }
    }

    public function edit($id) {
        $employee = Employee::where('id', $id)->first();

        $user = User::where('id', $employee['user_id'])->first();

        return view("employee.edit")->with(['user' => $user, 'employee' => $employee]);
    }

    public function update(Request $request, $id) {
        $post = $request->except("_token", 'user_id');



        DB::beginTransaction();
        try {

            $employee = Employee::where('id', $id)->first();

            $update = $employee->update($post);

            $user = User::where('id', $employee['user_id'])->first();

            $update_user = $user->update(["email" => $post['email']]);

            if(isset($post['password']) && $post['password'] != null) {
                $user->update(["password" => \Hash::make($post['password'])]);
            }

            DB::commit();
            return "success";
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function delete($id) {
        DB::beginTransaction();
        try {
            $employee = Employee::where('id', $id)->first();
            User::where("id", $employee['user_id'])->delete();
            $delete = $employee->delete();
            DB::commit();
            return "success";
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

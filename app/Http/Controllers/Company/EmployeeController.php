<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\User;
use App\Models\Company;
use App\Helpers\MyHelper;
use DataTables;
use Excel;
use App\Exports\UsersExport;
use Auth;
use Hash;
use DB;
use Validator;
// use Rule;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index() {
        $is_data_empty = Employee::where('company_id', session('company_id'))->get()->count() == 0 ? true : false;

        return view('employee.index', ['is_data_empty' => $is_data_empty]);
    }

    public function data(Request $request) {

        // if($request->ajax()) {
            $data = Employee::where('company_id', session('company_id'))->get();
            return Datatables::of($data)
                ->addColumn('action', function ($data) {
                    return "<a href='".route('employee.edit', [$data['id']])."'><i class='fa fa-edit text-info'></i></a>
                    | <a href='javascript:;' class='btn-delete' onClick='deleteSweet(".$data["id"].")' title=".$data['name']."><i class='fa fa-trash text-danger'></i></a>";
            })->addColumn('salary_str', function($row){
                return "Rp.".number_format($row['salary']);
            })
            ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        // } else {
        //     return "Request Not Allowed";
        // }
    }

    public function create() {
        return view("employee.create");
    }

    public function store(Request $request) {
        $post = $request->except("_token");
        $validator = Validator::make($post, [
            'name' => 'required',
            'position' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'salary' =>'required'
        ]);

        if ($validator->fails()) {
            return redirect('employee/create')
                        ->withErrors($validator)
                        ->withInput();
        }
        DB::beginTransaction();
        // try {
            //Validasi Email
            $post['salary'] = str_replace(['Rp', " ", "."], "", $post['salary']);
            $user = User::where('email', $post['email'])->first();
            if($user)
                return redirect('/employee/create')->with(['error' => 'Email or Phone has been taken'])->withInput();
            $save = User::create([
                'name' => $post['name'],
                'email' => $post['email'],
                'password' => Hash::make($post['password']),
                'level' => 3
            ]);

            $save->assignRole("User");

            if($save) {
                Employee::create([
                    "name" => $post['name'],
                    'position' => $post['position'],
                    "salary" => $post['salary'],
                    "company_id" => session('company_id'),
                    "user_id" => $save['id'],
                ]);

            }
            DB::commit();
            return redirect('/employee')->withSuccess(['Employee has been created']);

        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     return redirect('/employee')->withErrors(['Create Employee Failed']);
        // }
    }

    public function edit($id) {
        $employee = Employee::where('id', $id)->first();
        $validate = MyHelper::validationEmployee($id, session("company_id"));
        if($validate == false) {
            return redirect('/employee')->withErrors(['Something went wrong, please try again']);
        }
        $user = User::where('id', $employee['user_id'])->first();

        return view("employee.edit")->with(['user' => $user, 'employee' => $employee]);
    }

    public function update(Request $request, $id) {
        $post = $request->except("_token", 'user_id');
        $validator = Validator::make($post, [
            'name' => 'required',
            'position' => 'required',
            'email' => 'required|email|unique:users,email',
            'email' => ['required', Rule::unique('users')->ignore($id)],
            'salary' =>'required'
        ]);

        if ($validator->fails()) {
            return redirect('employee')
                        ->withErrors($validator)
                        ->withInput();
        }
        $validate = MyHelper::validationEmployee($id, session("company_id"));
        if($validate == false) {
            return redirect('/employee')->with(['error' => 'Something went wrong, please try again']);
        }

        DB::beginTransaction();
        try {

            $employee = Employee::where('id', $id)->first();
            $post['salary'] = str_replace(['Rp', " ", "."], "", $post['salary']);
            $update = $employee->update([
                "name" => $post['name'],
                "position" => $post['position'],
                "status" => $post['status'],
                "salary" => $post['salary']
            ]);

            $user = User::where('id', $employee['user_id'])->first();

            $update_user = $user->update(["email" => $post['email'], "name" => $post['name']]);

            if(isset($post['password']) && $post['password'] != null) {
                $user->update(["password" => \Hash::make($post['password'])]);
            }

            DB::commit();
            return redirect('/company')->with(['success' => 'Employee has been updated']);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect('/company')->with(['error' => 'Update Employee Failed']);
        }
    }

    public function delete($id) {
        DB::beginTransaction();
        try {
            $employee = Employee::where('id', $id)->first();
            User::where("id", $employee['user_id'])->delete();
            $delete = $employee->delete();
            DB::commit();
            return 1;
        } catch (\Throwable $th) {
            DB::rollback();
            return 0;
        }
    }

    public function exportExcel(){
        $company = Company::where('id', session('company_id'))->first();
        return Excel::download(new UsersExport,  'List gaji '.$company['name'].'.xlsx');
    }

    public function salary(){
        return view('employee.salary');
    }
}

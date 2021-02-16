<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Ovense;
use App\Models\Employee;
use App\Models\EmployeeSalary;
use App\Notifications\OvensePublish;
use DataTables;
use DB;

class OvenseController extends Controller
{
    public function index() {
        $is_data_empty = Ovense::whereHas("employee", function($q){
            $q->where("company_id", session("company_id"));
        })->get()->count() == 0 ? true : false;

        return view('ovense.index', ['is_data_empty' => $is_data_empty]);
    }

    public function data(Request $request){
        if($request->ajax()) {
            $data = Ovense::whereHas("employee", function($q){
                $q->where("company_id", session("company_id"));
            })->with("employee")->orderBy("created_at", "desc")->get();
            return Datatables::of($data)
                ->addColumn('action', function ($data) {
                    return "<a href='".route('ovense.edit', [Crypt::encrypt($data['id'])])."'><i class='fa fa-edit text-info'></i></a>
                    | <a href='javascript:;' class='btn-delete' onClick='deleteSweet(".$data["id"].")' title=".$data['name']."><i class='fa fa-trash text-danger'></i></a>";
            })
            ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create() {
        $employee = Employee::where("company_id", session("company_id"))->get();

        // di view id employee dibuat Illuminate\Support\Facades\Crypt::encrypt($value['id]);

        return view("ovense.create", compact("employee"));
    }

    public function store(Request $request) {
        $post = $request->except("_token");
        // return $post;
        DB::beginTransaction();
        try {
            if($post['punishment'] == null)
                $post['punishment'] = 0;
            $message = $post['ovense_name'].", You Charge = ".$post['punishment'];
            $post['employee_id'] = Crypt::decryptString($post['employee_id']);
            // return $post;
            $save = Ovense::create($post);


            $notification = [
                'message' => $message,
                'url' => 'dashboard/pre-assesment',
                'action_status' => 'Pra Penilaian'
            ];
            $save->employee->user->notify(new OvensePublish($notification));
            DB::commit();
            return "sukses";
        } catch (\Throwable $th) {
            DB::rollback();
            // return false;
            return "error";
        }
    }

    public function edit($id) {
        $data = Ovense::where('id', Crypt::decrypt($id))->with("employee")->first();
        $employee = Employee::where("company_id", session("company_id"))->get();
        // return $data;
        return view("ovense.edit", compact("data", "employee"));
    }

    public function update(Request $request, $id) {
        $post = $request->except('_token', 'id');
        $post['id'] = Crypt::decrypt($id);

        DB::beginTransaction();
        try {
            $update = Ovense::where("id", $post['id'])->update([
                "ovense_name" => $post['ovense_name'],
                "pinalty_type" => $post['pinalty_type'],
                "date" => $post['date'],
                "punishment" => $post['punishment'],
                "employee_id" => Crypt::decrypt($post['employee_id']),
            ]);

            // $update->employee->user->notify(new OvensePublish($notification));
            DB::commit();
            return "sukses";
        } catch (\Throwable $th) {
            DB::rollback();
            return "fail";
        }
    }

    public function delete($id){
        return Ovense::where('id', $id)->delete();
    }

    public function myOvense() {
        $data = Employee::where("user_id", \Auth::id())->with("ovense")->first();
        $is_data_empty = $data->ovense->count() == 0 ? true : false;

        return view("ovense.my", compact('data', 'is_data_empty'));
    }
}

//TODO membuat tabel Salary dan diisi ketika ada perubahan gaji karyawan

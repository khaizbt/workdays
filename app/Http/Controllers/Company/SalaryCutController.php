<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Helpers\MyHelper;
use App\Models\SalaryCuts;
use App\Models\Employee;
use DataTables;
use Auth;
use DB;

class SalaryCutController extends Controller
{
    public function index() {
        $is_data_empty = SalaryCuts::whereHas("employee", function($q){
            $q->where("company_id", session("company_id"));
        })->get()->count() == 0 ? true : false;

        return view('salarycut.index', ['is_data_empty' => $is_data_empty]);
    }

    public function data(Request $request) {
        // if($request->ajax()) {
            $data = SalaryCuts::whereHas("employee", function($q){
                $q->where("company_id", session("company_id"));
            })->with("employee")->orderBy("created_at", "desc")->get();


            return Datatables::of($data)
                ->addColumn('action', function ($data) {
                    return "<a href='".route('salarycut.edit', [Crypt::encrypt($data['id'])])."'><i class='fa fa-edit text-info'></i></a>
                    | <a href='javascript:;' class='btn-delete' onClick='deleteSweet(".$data["id"].")' title=".$data['name']."><i class='fa fa-trash text-danger'></i></a>";
            })
            ->addColumn("status_value", function($q){
                return ($q->status == 0) ? 'Dipotong 1x' : "Dipotong Perbulan";
            })
            ->addIndexColumn()
                ->rawColumns(['action', 'status_value'])
                ->make(true);
        // }
    }

    public function create() {
        $employee = Employee::where("company_id", session("company_id"))->get();

        // di view id employee dibuat Illuminate\Support\Facades\Crypt::encrypt($value['id]);

        return view("salarycut.create", compact("employee"));
    }

    public function store(Request $request) {
        $post = $request->all();
        DB::beginTransaction();

        try {
        $post['employee_id'] = Crypt::decryptString($post['employee_id']);
        $post['value'] = str_replace(['Rp', ',', '.', " "],"", $post['value']);

        if(isset($post['image']) && $post['image'] != null) {
            $upload = MyHelper::uploadFile($post['image'], "company/salary-cut/");

            if($upload['status'] == "fail")
                return redirect()->back()->withInput();

                $post['image'] = $upload['path'];
        }

        $salary_cuts = SalaryCuts::create($post);

        if($salary_cuts && $post['status'] == 1){
            $employee = Employee::where("id", $post['employee_id'])->first();

            $employee = $employee->update([
                "salary" => $employee['salary'] - $post['value'] //Ketika Update tinggal sesuaikan, Jumlah sekarang lbh besar maka dikurangi, jika lbh kecil maka ditambah sisa
            ]);
        }

        DB::commit();
        return redirect('/salary-cut')->withSuccess(['Salary Cut has been created']);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect('/salary-cut')->withErrors(['Create Salary Cut Failed']);
        }
    }

    public function edit($id) {
        $data = SalaryCuts::where("id", Crypt::decrypt($id))->with("employee")->first();

        // return $data;
        $employee = Employee::where("company_id", session("company_id"))->get();
        return view("salarycut.edit", compact("data", "employee"));
    }

    public function update(Request $request, $id) {
        $post = $request->except("id","_token");

        DB::beginTransaction();
        try {
            $data = SalaryCuts::where("id", Crypt::decrypt($id))->first();
            $post['value'] = str_replace(['Rp', ',', '.', " "],"", $post['value']);

            if($data['status'] == 1 && $post['value'] != $data['value']){
                $employee = Employee::where("id", Crypt::decrypt($post['employee_id']))->first();
                if($post['value'] > $data['value']){
                    $post['value'] = $post['value'] - $data['value'];
                    $employee = $employee->update([
                        "salary" => $employee['salary'] - $post['value'] //Ketika Update tinggal sesuaikan, Jumlah sekarang lbh besar maka dikurangi, jika lbh kecil maka ditambah sisa
                    ]);
                } elseif($post['value'] < $data['value']) {
                    $post['value'] = $data['value'] - $post['value'];
                    $employee = $employee->update([
                        "salary" => $employee['salary'] + $post['value'] //Ketika Update tinggal sesuaikan, Jumlah sekarang lbh besar maka dikurangi, jika lbh kecil maka ditambah sisa
                    ]);
                }
            }

        $update = $data->update([
            "cuts_name" => $post['cuts_name'],
            "notes" => $post['notes'],
            "value" => $post['value'],
            "status" => $post['status'],
            "employee_id" => Crypt::decrypt($post['employee_id'])
        ]);

        if(isset($post['image']) && $post['image'] != null) {
            $upload = MyHelper::uploadFile($post['image'], "company/salary-cut/");

            if($upload['status'] == "fail")
                return redirect()->back()->withInput();

                $post['image'] = $upload['path'];
            $data->update([
                "image" => $post['image']
            ]);
        }
        DB::commit();
        return redirect('/salary-cut')->withSuccess(['Salary Cut has been updated']);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect('/salary-cut')->withErrors(['Update Salary Cut failed']);
        }
    }


    public function delete($id) {
        $data = SalaryCuts::where("id", $id)->first();
        DB::beginTransaction();
        if($data['status'] == 1){
            $employee = Employee::where("id", $data['employee_id'])->first();
            $employee->update([
                "salary" => $employee['salary'] + $data['value'],
            ]);
        }


        $data  = $data->delete();
        if($data) {
            DB::commit();
            return 1;
        }

        DB::rollback();
        return 0;

    }

    public function mySalaryCut() {
        $data = Employee::where("user_id", Auth::id())->with("salary_cut")->first();
        $is_data_empty = $data->salary_cut->count() == 0 ? true : false;


        return view("salarycut.mysalarycut", compact('data', 'is_data_empty'));
    }
}

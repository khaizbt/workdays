<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Helpers\MyHelper;
use App\Models\SalaryCuts;
use App\Models\Employee;
use DB;

class SalaryCutController extends Controller
{
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

        if($post['image']) {
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
        return "sukses";
        } catch (\Throwable $th) {
            return "error";
        }
    }
}

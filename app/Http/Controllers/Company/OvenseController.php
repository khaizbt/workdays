<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Ovense;

class OvenseController extends Controller
{
    public function index() {
        $data = Ovense::orderBy("created_at", "asc")->get();

        //Datatable in here
    }

    public function create() {
        $employee = Employee::where("company_id", session("company_id"))->get();

        // di view id employee dibuat Illuminate\Support\Facades\Crypt::encrypt($value['id]);

        return view("ovense.create", compact("employee"));
    }

    public function store(Request $request) {
        $post = $request->except("_token");
        DB::beginTransaction();
        try {
            $message = $post['ovense_name'].", You Charge = ".$post['punishment'];
            $post['employee_id'] = Crypt::decrypt($post['employee_id']);

            $save = Ovense::create($post);
            $notification = [
                'message' => $message,
                'url' => 'dashboard/pre-assesment',
                'action_status' => 'Pra Penilaian'
            ];
            $save->employee->user->notify(new OvensePublish($notification));
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollback();
            return false;
        }
    }

    public function edit($id) {
        $data = Ovense::where('id', Crypt::decrypt($id))->with("employee")->get();

        return view("ovense.edit", compact("data"));
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
                "punishment" => $post['punishment']
            ]);

            // $update->employee->user->notify(new OvensePublish($notification));
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollback();
            return false;
        }
    }

    public function delete($id){
        return Ovense::where('id', Crypt::decrypt($id))->delete();
    }
}

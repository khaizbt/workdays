<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use App\Models\Company;
use DataTables;
use App\User;
use App\Models\WorkDay;
use App\Helpers\MyHelper;
use App\Notifications\SubmitLeave;
use Illuminate\Support\Facades\Crypt;

class ManageCompanyController extends Controller
{
    public function index(){
        $is_data_empty = Company::all()->count() == 0 ? true : false;

        return view('admin.company.index', ['is_data_empty' => $is_data_empty]);
    }

    public function data(Request $request){
        $post = $request->all();
        if($request->ajax()) {
            $data = Company::with('user')->get();

            return Datatables::of($data)
                ->addColumn('action', function ($data) {
                    return "<a href='".route('company.edit', [$data['id']])."'><i class='fa fa-edit text-info'></i></a>
                    | <a href='javascript:;' class='btn-delete' onClick='deleteSweet(".$data["id"].")' title=".$data['name']."><i class='fa fa-trash text-danger'></i></a>";
            })->addColumn("user_name", function($row){
               return $row['user']['name'];
            })
            // href='".route('company.destroy', [Crypt::decrypt($data['id'])])."'
            ->addIndexColumn()
                ->rawColumns(['action', 'user_name'])
                ->make(true);
        }
    }
//TODO Edit company by admin company
    public function create(){
        $user_data = User::where('level', 2)->get()->toArray();
        $id_user = array_column($user_data, 'id');

        $user = User::where('level', 2)->get();

        // return $user;
        return view('admin.company.create')->with('user', $user);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|unique:companies',
            'logo'  => 'required'
            // 'id_user' => 'required|unique:table,column,except,id'

        ]);
        $post = $request->all();
        // return $post;
        $post['id_user'] = Crypt::decrypt($post['id_user']);


        DB::beginTransaction();
        if($post['logo']) {
            $upload = MyHelper::uploadFile($post['logo'], "company/logo/");

            if($upload['status'] == "fail")
                return redirect()->back()->withInput();

                $post['logo'] = $upload['path'];
        }

        if(isset($post['work_holiday']) && $post['work_holiday'] == "on")
            $post['work_holiday'] = 1;
        $check_user = Company::where("id_user", $post['id_user'])->first();
        if($check_user){
            return redirect('/company')->with(['error' => 'User has been asigned to other company']);
        }
        $company = Company::create($post);

        foreach($post['value'] as $k => $v) {
            $work_days = WorkDay::create([
                'id_company' => $company['id'],
                'days' => $v
            ]);
        }

        // $user = User::where("id")
        // $user = User::find($value['id_customer_analyst']);
        $notification = [
            'message' => "Company Has Been Craeted",
            'url' => 'dashboard/pre-assesment',
            'action_status' => 'Pra Penilaian'
        ];
        $company->user->notify(new SubmitLeave($notification));
        DB::commit();
        return redirect('/company')->with(['success' => 'Data Company has been created']);
    }

    public function edit($id){
        $data = Company::where('id', $id)->with("work_days")->first();
        $user = User::where('level', 2)->get();
        // return $data;
        return view('admin.company.edit')->with('data', $data)->with('user', $user);
    }

    public function update(Request $request, $id){
        $post = $request->except('id', "_token");
        $post['id_user'] = Crypt::decrypt($post['id_user']);
        if(isset($post['work_holiday']) && $post['work_holiday'] == "on")
        $post['work_holiday'] = 1;

        if(isset($post['logo'])) {
            $upload = MyHelper::uploadFile($post['logo'], "company/logo/");

            if($upload['status'] == "fail")
                return redirect()->back()->withInput();

                $post['logo'] = $upload['path'];
        }
        DB::beginTransaction();
        $delete = WorkDay::where("id_company", $id)->delete();
        foreach($post['value'] as $k => $v) {
            $work_days = WorkDay::create([
                'id_company' => $id,
                'days' => $v
            ]);
        }


        unset($post['value']);
        $update = Company::where('id', $id)
            ->update($post);
            DB::commit();
        return redirect('/company')->with(['success' => 'Data Company has been updated']);

    }

    public function destroy($id) {
        return Company::where("id", $id)->delete();
    }

}


//TODO Hitung Jumlah hari kerja di dashboard(Tidak dari klik kalender, list Holiday/Event bisa mencontoh company management)


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
use Validator;
use Illuminate\Validation\Rule;

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

    public function create(){
        $user_data = User::where('level', 2)->get()->toArray();
        $id_user = array_column($user_data, 'id');

        $user = User::where('level', 2)->get();
        return view('admin.company.create')->with('user', $user);
    }

    public function store(Request $request){
        $post = $request->all();
        $post['id_user'] = Crypt::decrypt($post['id_user']);

        $validator = Validator::make($post, [
            'name' => 'required|unique:companies',
            'address' => 'required|min:10',
            'email' => 'required|email',
            'phone' => 'required',
            'id_user' =>'required|unique:companies',
            'date_salary' => 'required',
            'number_leave' => 'required',
            'maximum_leave' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('company/create')
                        ->withErrors($validator)
                        ->withInput();
        }

        // try {
            $user = User::where('id', $post['id_user'])->where('level', 2)->first();
            $user->assignRole("Admin");



            DB::beginTransaction();
            if(isset($post['logo']) && $post['logo'] != null) {
                $upload = MyHelper::uploadFile($post['logo'], "company/logo/");

                if($upload['status'] == "fail")
                    return redirect()->back()->withInput();

                    $post['logo'] = $upload['path'];
            }

            if(isset($post['work_holiday']) && $post['work_holiday'] == "on")
                $post['work_holiday'] = 1;

            // return $post;

            $company = Company::create($post);

            // foreach($post['value'] as $k => $v) {
            //     $work_days = WorkDay::create([
            //         'id_company' => $company['id'],
            //         'days' => $v
            //     ]);
            // }

            // $user = User::where("id")
            // $user = User::find($value['id_customer_analyst']);
            $notification = [
                'message' => "Company Has Been Created",
                'url' => 'company/edit-company',
                'action_status' => 'Pra Penilaian'
            ];
            $company->user->notify(new SubmitLeave($notification));
            DB::commit();
            return redirect('/company')->withSuccess(['Data Company has been created']);
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     return redirect()->back()->withErrors(['Create Company Failed'])->withInput();
        // }
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
        $validator = Validator::make($post, [
            'name' => ['required', Rule::unique('companies')->ignore($id)],
            'address' => 'required|min:10',
            'email' => 'required|email',
            'phone' => 'required',
            'id_user' =>['required', Rule::unique('companies')->ignore($id)],
            'date_salary' => 'required',
            'number_leave' => 'required',
            'maximum_leave' => 'required'
        ]);


        if ($validator->fails()) {
            return redirect('company')
                        ->withErrors($validator)
                        ->withInput();
        }

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
        // foreach($post['value'] as $k => $v) {
        //     $work_days = WorkDay::create([
        //         'id_company' => $id,
        //         'days' => $v
        //     ]);
        // }

        unset($post['value']);
        $update = Company::where('id', $id)
            ->update($post);
            DB::commit();
        return redirect('/company')->withSuccess(['Company has been updated']);

    }

    public function destroy($id) {
        return Company::where("id", $id)->delete();
    }

    public function editCompany(){
        $data = Company::where('id', session("company_id"))->first();

        return view('admin.company.editmy', compact('data'));
    }

    public function updateCompany(Request $request){
        $post = $request->except('id', 'id_user', '_token');

        $data = $update = Company::where('id', session('company_id'))->first();
        $data->update([
            "name" => $post['name'],
            "address" => $post['address'],
            "phone" => $post['phone'],
            "email" => $post['email'],
            "number_leave" => $post['number_leave'],
            "maximum_leave" => $post['maximum_leave'],
            "date_salary" => $post['date_salary']
        ]);

        if(isset($post['logo']) && $post['logo'] != null) {
            $upload = MyHelper::uploadFile($post['logo'], "company/logo/");

            if($upload['status'] == "fail")
                return redirect()->back()->withInput();

            $data->update([
                'logo' => $upload['path']
            ]);
        }

        return redirect()->back()->withSuccess(["Your Company has been updated"])->withInput();
    }
}


//TODO Hitung Jumlah hari kerja di dashboard(Tidak dari klik kalender, list Holiday/Event bisa mencontoh company management)


<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Company;
use DataTables;
use App\User;
class ManageCompanyController extends Controller
{
    public function index(){
        $is_data_empty = Company::all()->count() == 0 ? true : false;

        return view('admin.company.index', ['is_data_empty' => $is_data_empty]);
    }

    public function data(Request $request){
        $post = $request->all();
        if($request->ajax()) {
            $data = Company::all();

            // return $data;

            return Datatables::of($data)
                ->addColumn('action', function ($data) {
                    return "<a href='".route('company.edit', [$data['id']])."'><i class='fa fa-envelope text-info btn-email'></i></a>
                    | <a href='".route('company.destroy', [$data['id']])."' class='btn-delete' title=".$data['name']."><i class='fa fa-trash text-danger'></i></a>";
            })
            ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create(){
        $user = User::where('level', 2)->get();
        return view('admin.company.create')->with('user', $user);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|unique:companies',

        ]);
        $user = Company::create($request->all());
        return response()->json($user);
    }

    public function edit($id){
        $data = Company::where('id', $id)->first();
        $user = User::where('level', 2)->get();

        return view('admin.company.edit')->with('data', $data)->with('user', $user);
    }

    public function update(Request $request, $id){
        $update = Company::where('id', $id)
            ->update(['name' => $request->name, 'id_user' => $request->id_user, 'logo' => $request->logo]);

        return 'Sukses';
    }

}


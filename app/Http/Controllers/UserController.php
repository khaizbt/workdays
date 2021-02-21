<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\{UserUpdateRequest,UserAddRequest};
use Spatie\Permission\Models\Role;
use App;
use Auth;
use Validator;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['permission:manage-users|view-users']);
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if($request->ajax())
        {
            $users = new User;
            if($request->q)
            {
                $users = $users->where('name', 'like', '%'.$request->q.'%')->orWhere('email', $request->q);
            }
            $users = $users->whereNotIn('level', [3])->paginate(config('stisla.perpage'))->appends(['q' => $request->q]);
            return response()->json($users);
        }
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            // 'notes' => 'required|min:10',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect('admin/users/create')
                        ->withErrors($validator);
        }

        $post = $request->all();
        $post['level'] = 2;
        $post['password'] = \Hash::make($post['password']);
        $user = User::create($post);
        return redirect('admin/users')->withSuccess(['Admin has been created']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // return $user;
        if(Auth::user()->hasRole('Super'))
            return view('admin.users.edit', compact('user'));


        if($user['id'] != Auth::id() )
            return redirect('/');



        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id){
        // $post = $request->only("name", "")
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            // 'notes' => 'required|min:10',
            'email' => ['required', Rule::unique('users')->ignore($id)],
            'password' => 'confirmed',
        ]);

        if ($validator->fails()) {
           return redirect()->back()->withErrors($validator)->withInput();
        }

        $post = $request->only('name', 'email', 'password');
        $user = User::where('id', $id)->first();
        $update = $user->update([
            'name' => $post['name'],
            "email" => $post['email']
        ]);

        if(isset($post['password']) && $post['password'] != null) {
            $data->update([
                "password" => \Hash::make($post['password']),
            ]);
        }

        return redirect()->back()->withSuccess(['User has been updated'])->withInput();
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        // return $request;
        // if(!App::environment('demo'))
        // {
            $user->update($request->only([
                'name', 'email'
            ]));

            if($request->password)
            {
                $user->update(['password' => Hash::make($request->password)]);
            }

            if($request->role && $request->user()->can('edit-users') && !$user->isme)
            {
                $role = Role::find($request->role);
                if($role)
                {
                    $user->syncRoles([$role]);
                }
            }
        // }

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if(!App::environment('demo') && !$user->isme)
        {
            $user->delete();
        } else
        {
            return response()->json(['message' => 'User accounts cannot be deleted in demo mode.'], 400);
        }
    }

    public function roles()
    {
        return response()->json(Role::get());
    }
}

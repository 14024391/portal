<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function index($id)
    {
        $this->authorize('view',Role::class);
        $roles = Role::all();
        $role = Role::find($id);
        return view('admin.role.index',['roles' => $roles, 'role' => $role]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create',Role::class);

        $roles = Role::all();
        return view('admin.role.create',['roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create',Role::class);

        $request->validate([
            'name' => 'required|alpha|min:4|max:14',
            'description' => 'required|min:4'
        ]);
        $role = new Role();
        $role->name = strtolower($request->name);
        $role->description = $request->description;
        $role->save();

        return redirect()->route('role',$role->id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update', Role::class);

        $request->validate([
           'name' => ['required', 'alpha',Rule::unique('roles')->ignore($id),'min:4','max:14'],
           'description' => 'required|min:4'
        ]);
        $role = Role::find($id);

        if(in_array($role->name, config('constant.roles'))){
            return redirect()->back()->withErrors('You can update or delete Admin Role');
        }

        $role->name = strtolower($request->name);
        $role->description = $request->description;
        $role->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function destroy(Request $request,$id)
    {
        $this->authorize('delete', Role::class);

        $role = Role::find($request->id);
        if(in_array($role->name, config('constant.roles'))){
            return redirect()->back()->withErrors('You can not delete Admin Role');
        }
        Role::destroy($request->id);
        return redirect()->route('role',1);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Exception;

class PermissionController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view', Permission::class);
        $roles = Role::all();
        $permissions = Permission::orderBy('group','asc')->get();
        $groups = Permission::distinct()->pluck('group');
        $role_permission = DB::table('permission_role')->get();
        return view('admin.permission.index',['roles' => $roles,'groups' => $groups,'permissions' => $permissions]);
    }


    /**
     * Get Permission Ids from Role Id.
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getPermissionByRole($id){
        $this->authorize('view', Permission::class);
        $role_permissions = Role::find($id)->permissions()->pluck('permission_id');
        return response($role_permissions);
    }


    /**
     * Remove All Permissions for Role.
     * Update new Permissions to Role
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function updatePermissionByRole(Request $request, $id){
        try{
            $this->authorize('update', Permission::class);
            Role::find($id)->permissions()->detach();
            Role::find($id)->permissions()->attach($request->selected);
            $role_permissions = Role::find($id)->permissions()->pluck('permission_id');
            return response($role_permissions);
        }catch (Exception $e){
            return response(['error'=> $e->getMessage()]);
        }

    }


}

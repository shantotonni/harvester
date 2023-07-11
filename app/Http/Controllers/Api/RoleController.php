<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleRequest;
use App\Http\Resources\Role\RoleCollection;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('ID','desc')->paginate(15);
        return new RoleCollection($roles);
    }

    public function store(RoleRequest $request)
    {
        $role = new Role();
        $role->Name = $request->Name;
        $role->save();
        return response()->json(['message'=>'Role Created Successfully'],200);
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        $role->Name = $request->Name;
        $role->save();
        return response()->json(['message'=>'Role Updated Successfully'],200);
    }

    public function destroy($id)
    {
        Role::where('ID',$id)->delete();
        return response()->json(['message'=>'Role Deleted Successfully'],200);
    }

    public function search($query)
    {
        return new RoleCollection(Role::where('Name','LIKE',"%$query%")->latest()->paginate(20));
    }
}

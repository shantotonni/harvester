<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceEngineer\ServiceEngineerStoreRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\ServiceEngineer\ServiceEngineerCollection;
use App\Http\Resources\User\UserCollection;
use App\Models\ServiceEngineer;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\Image;

class ServiceEngineerController extends Controller
{
    public function index()
    {
        $users = User::with('role')->paginate(15);
        return new UserCollection($users);
    }

    public function store(UserStoreRequest $request){
        $this->validate($request, [
            'image' => 'required|min:jpeg,jpg,png,svg'
        ]);

        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid().time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/user/').$name);
        } else {
            $name = 'not_found.jpg';
        }

        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->address = $request->address;
        $user->role_id = $request->role_id;
        $user->designation = $request->designation;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->image = $name;
        $user->company_id = '1';
        $user->Password = bcrypt($request->Password);

        $user->save();

        return response()->json(['message'=>'Service Engineer Created Successfully'],200);
    }

    public function update(UserUpdateRequest $request, $id){
        $this->validate($request,[
            'image' => 'required|min:jpeg,jpg,png,svg'
        ]);
        $user = User::where('id',$id)->first();
        $image = $request->image;
        if ($image != $user->image) {
            if ($request->has('image')) {
                $destinationPath = 'images/user/';
                $file_old = public_path('/').$destinationPath.$user->image;
                if (file_exists($file_old)){
                    unlink($file_old);
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->save(public_path('images/user/') . $name);
            } else {
                $name = $user->image;
            }

        }
        else{
            $name = $user->image;
        }
        $user->name = $request->name;
        $user->username = $request->username;
        $user->address = $request->address;
        $user->role_id =$request->role_id;
        $user->designation = $request->designation;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->image = $name;
        $user->company_id = '1';
        $user->save();
        return response()->json(['message'=>'Service Engineer Updated Successfully'],200);
    }

    public function destroy($id)
    {
        User::where('id', $id)->delete();
        return response()->json(['message' => 'Service Engineer Deleted Successfully', 200]);
    }

    public function search($query)
    {
        return new ServiceEngineerCollection(ServiceEngineer::Where('name', 'like', "%$query%")->latest()->paginate(10));
    }

}

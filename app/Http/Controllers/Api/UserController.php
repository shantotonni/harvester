<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\User\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{

    public function index(){
        $users = User::with('role')->paginate(15);
        return new UserCollection($users);
    }

    public function store(UserStoreRequest $request){
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
        $user->role_id = $request->role_id;
        $user->designation = $request->designation;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->image = $name;
        $user->Password = bcrypt($request->Password);
        $user->Active = 'Y';
        $user->save();

        return response()->json(['message'=>'User Created Successfully'],200);
    }

    public function update(UserUpdateRequest $request, $id){

        $user = User::where('id',$id)->first();
        $image = $request->Image;

        if ($image != $user->Image) {
            if ($request->has('Image')) {
                //code for remove old file
                if ($user->Image != '' && $user->Image != null) {
                    $destinationPath = 'images/user/';
                    $file_old = $destinationPath . $user->Image;
                    if (file_exists($file_old)) {
                        unlink($file_old);
                    }
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->resize(1600,1000)->save(public_path('images/user/') . $name);
            } else {
                $name = $user->Image;
            }
        }else{
            $name = $user->Image;
        }

        $user->name = $request->name;
        $user->username = $request->username;
        $user->role_id = $request->role_id;
        $user->designation = $request->designation;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->image = $name;
        $user->save();
        return response()->json(['message'=>'User Updated Successfully'],200);
    }

    public function getAllUser(){
        return new UserCollection(User::all());
    }

    public function getUserByUserId(){
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json([
            'user'=>$user
        ],200);
    }

    public function updateProfile(Request $request){
        $user = User::where('id',$request->id)->where('UserID',$request->userId)->first();
        $user->designation = $request->designation;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->name = $request->name;
        $user->save();
        return response()->json([
            'message'=>'success'
        ]);
    }

    public function search($query)
    {
        return new UserCollection(User::where('Name','LIKE',"%$query%")->orWhere('username','LIKE',"%$query%")->latest()->paginate(20));
    }
}

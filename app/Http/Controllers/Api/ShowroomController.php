<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Showroom\ShowroomStoreRequest;
use App\Http\Resources\Showroom\ShowroomCollection;
use App\Models\Showroom;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ShowroomController extends Controller
{
    public function index(Request $request)
    {

        $district_id =$request->district_id;
        $showrooms = Showroom::query()->with('District');
        if (!empty($district_id)){
            $showrooms=$showrooms->where('district_id',$district_id);

        }
        $showrooms = $showrooms->paginate();

        return new ShowroomCollection($showrooms);
    }

    public function store(ShowroomStoreRequest $request)
    {
        $this->validate($request, [
            'image' => 'required|min:jpeg,jpg,png,svg'
        ]);
        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid().time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/showroom/').$name);
        } else {
            $name = 'not_found.jpg';
        }

        $showroom = new Showroom();
        $showroom->owner_name = $request->owner_name;
        $showroom->district_id = $request->district_id;
        $showroom->address = $request->address;
        $showroom->showroom_name = $request->showroom_name;
        $showroom->mobile = $request->mobile;
        $showroom->lat = $request->lat;
        $showroom->long = $request->long;
        $showroom->image =$name;
        $showroom->save();
        return response()->json(['message' => 'Showroom created Successfully', 200]);
    }


    public function update(Request $request, $id)
    {
        $showroom = Showroom::where('showroom_id', $id)->first();
        $this->validate($request, [
            'image' => 'required|min:jpeg,jpg,png,svg'
        ]);

        $image = $request->image;
        if ($image != $showroom->image) {
            if ($request->has('image')) {
                $destinationPath = 'images/showroom/';
                if ($showroom->image){
                    $file_old = public_path('/').$destinationPath.$showroom->image;
                    if (file_exists($file_old)){
                        unlink($file_old);
                    }
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->save(public_path('images/showroom/') . $name);
            } else {
                $name = $showroom->image;
            }

        }
        else{
            $name = $showroom->image;
        }
        $showroom->owner_name = $request->owner_name;
        $showroom->district_id = $request->district_id;;
        $showroom->address = $request->address;
        $showroom->showroom_name = $request->showroom_name;
        $showroom->mobile = $request->mobile;
        $showroom->lat = $request->lat;
        $showroom->long = $request->long;
        $showroom->image =$name;
        $showroom->save();
        return response()->json(['message' => 'Showroom updated Successfully', 200]);
    }

    public function destroy($id)
    {
        Showroom::where('showroom_id', $id)->delete();
        return response()->json(['message' => 'Showroom Deleted Successfully', 200]);
    }

    public function search($query)
    {
        return new ShowroomCollection(Showroom::Where('showroom_name', 'like', "%$query%")->latest()->paginate(10));
    }

}

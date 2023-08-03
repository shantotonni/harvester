<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Showroom\ShowroomStoreRequest;
use App\Http\Resources\Showroom\ShowroomCollection;
use App\Models\Showroom;
use Illuminate\Http\Request;

class ShowroomController extends Controller
{
    public function index()
    {

        $showrooms = Showroom::paginate(10);

        return new ShowroomCollection($showrooms);
    }

    public function store(ShowroomStoreRequest $request)
    {
        $showroom = new Showroom();
        $showroom->owner_name = $request->owner_name;
        $showroom->address = $request->address;
        $showroom->showroom_name = $request->showroom_name;
        $showroom->mobile = $request->mobile;
        $showroom->lat = $request->lat;
        $showroom->long = $request->long;
        $showroom->save();
        return response()->json(['message' => 'Showroom created Successfully', 200]);
    }


    public function update(Request $request, $id)
    {
        $showroom = Showroom::where('showroom_id', $id)->first();
        $showroom->owner_name = $request->owner_name;
        $showroom->address = $request->address;
        $showroom->showroom_name = $request->showroom_name;
        $showroom->mobile = $request->mobile;
        $showroom->lat = $request->lat;
        $showroom->long = $request->long;
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

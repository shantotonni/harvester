<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\HarvesterInfo\HarvesterInfoStoreRequest;
use App\Http\Resources\HarvesterInfo\HarvesterInfoCollection;
use App\Models\HarvesterInfo;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use ZipStream\File;

class HarvesterInfoController extends Controller
{

    public function index()
    {
        $harvester_infos = HarvesterInfo::with('ProductModel', 'Products')->paginate(10);
        return new HarvesterInfoCollection($harvester_infos);
    }

    public function store(HarvesterInfoStoreRequest $request)
    {
        $this->validate($request, [
            'image' => 'required|min:jpeg,jpg,png,svg'
        ]);

        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/HarvesterInfo/') . $name);
        } else {
            $name = 'not_found.jpg';
        }

        $harvester_info = new HarvesterInfo();
        $harvester_info->product_name = $request->product_name;
        $harvester_info->product_id = $request->product_id;
        $harvester_info->model_id = $request->model_id;
        $harvester_info->horse_power = $request->horse_power;
        $harvester_info->details = $request->details;
        $harvester_info->image = $name;
        $harvester_info->save();
        return response()->json(['message' => 'Successfully Harvester Info Created', 200]);

    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'image' => 'required|min:jpeg,jpg,png,svg'
        ]);
        $harvester_info = HarvesterInfo::where('id', $id)->first();
        $image = $request->image;
        if ($image != $harvester_info->image) {
            if ($request->has('image')) {
                $destinationPath = 'images/HarvesterInfo/';
                $file_old = public_path('/') . $destinationPath . $harvester_info->image;
                if (file_exists($file_old)) {
                    unlink($file_old);
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->save(public_path('images/HarvesterInfo/') . $name);
            } else {
                $name = $harvester_info->image;
            }

        } else {
            $name = $harvester_info->image;
        }

        $harvester_info->product_name = $request->product_name;
        $harvester_info->product_id = $request->product_id;
        $harvester_info->model_id = $request->model_id;
        $harvester_info->horse_power = $request->horse_power;
        $harvester_info->details = $request->details;
        $harvester_info->image = $name;
        $harvester_info->save();
        return response()->json(['message' => 'Harvester Info Successfully Updated '], 200);
    }

    public function destroy(Request $request, $id)
    {
        $harvester_info = HarvesterInfo::where('id', $id)->first();
        if ($harvester_info->image) {
            $destinationPath = 'images/HarvesterInfo/';

            $file = public_path('/') . $destinationPath . $harvester_info->image;
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $harvester_info->delete();
        return response()->json(['message' => 'Harvester Info Deleted Successfully']);
    }


    public function search($query)
    {
        return new HarvesterInfoCollection(HarvesterInfo::Where('product_name', 'like', "%$query%")
            ->paginate(10));
    }

}

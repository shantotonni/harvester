<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\HarvesterInfo\HarvesterInfoStoreRequest;
use App\Http\Requests\HarvesterInfo\HarvesterInfoUpdateRequest;
use App\Http\Resources\HarvesterInfo\HarvesterInfoCollection;
use App\Http\Resources\ProductModel\ProductModelCollection;
use App\Models\HarvesterInfo;
use App\Models\ProductModel;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;

class HarvesterInfoController extends Controller
{

    public function index()
    {
        $harvester_infos = HarvesterInfo::with('ProductModel','Products')->paginate(10);
        return new HarvesterInfoCollection($harvester_infos);
    }

    public function store(HarvesterInfoStoreRequest $request)
    {
        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/HarvesterInfoImage') . $name);
        } else {
            $name = 'not_found.jpg';
        }

        $harvester_info = new HarvesterInfo();
        $harvester_info->product_category = $request->product_category;
        $harvester_info->product_id = $request->product_id;
        $harvester_info->model_id = $request->model_id;
        $harvester_info->horse_power = $request->horse_power;
        $harvester_info->details = $request->details;
        $harvester_info->image = $name;
        $harvester_info->save();
        return response()->json(['message' => 'Successfully Harvester Info Created', 200]);

    }

    public function update(HarvesterInfoUpdateRequest $request, HarvesterInfo $id)
    {
        $harvester_info = HarvesterInfo::where('id', $id)->first();
        $image = $request->image;
        if ($image != $image->image) {
            if ($request->has('image')) {
                $destinationPath = 'images/HarvesterInfoImage/';
                $file_old = public_path('/').$destinationPath.$image->image;
                if (file_exists($file_old)){
                    unlink($file_old);
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->save(public_path('images/HarvesterInfoImage') . $name);
            } else {
                $name = $image->image;
            }

        }
        else{
            $name = $image->image;
        }

        $harvester_info->product_category = $request->product_category;
        $harvester_info->product_id = $request->product_id;
        $harvester_info->model_id = $request->model_id;
        $harvester_info->horse_power = $request->horse_power;
        $harvester_info->details = $request->details;
        $harvester_info->image = $name;
        $harvester_info->save();
        return response()->json(['message'=>'Harvester Info Sucessfully Updated '],200);
    }

    public function destroy($id)
    {
        HarvesterInfo::where('id',$id)->delete();
        return response()->json(['message'=>'Harvester Info Deleted Successfully']);
    }

    public function getAllModelByProduct($id){

        return new ProductModelCollection(ProductModel::where('product_id',$id)->paginate(20));
    }


}

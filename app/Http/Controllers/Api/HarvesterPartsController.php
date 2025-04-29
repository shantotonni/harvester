<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\HarvesterParts\HarvesterPartsStoreRequest;
use App\Http\Resources\HarvesterParts\HarvesterPartsCollection;
use App\Models\HarvesterParts;
use App\Models\harvesterPartsModels;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class HarvesterPartsController extends Controller
{
    public function index()
    {
        $harvester_parts = HarvesterParts::with('ProductModel', 'SparePartsMirror', 'section','HarvesterPartsModels')->paginate(10);
        return new HarvesterPartsCollection($harvester_parts);
    }

    public function store(HarvesterPartsStoreRequest $request)
    {
        $exist_check = HarvesterParts::query()->where('ProductCode',$request['ProductCode']['ProductCode'])->exists();
        if ($exist_check){
            return response()->json([
                'message' => 'Already Exist', 200]);
        }

        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->resize('800', '700')->save(public_path('images/HarvesterParts/') . $name);
        } else {
            $name = 'not_found.jpg';
        }

        $harvester_part = new HarvesterParts();
        $harvester_part->custom_name = $request->custom_name;
        $harvester_part->ProductCode = $request['ProductCode']['ProductCode'];
//        $harvester_part->product_model_id = $request->product_model_id;
        $harvester_part->section_id = $request->section_id;
        $harvester_part->image = $name;
        $harvester_part->parts_type = $request->parts_type;
        if ($harvester_part->save()){
            $product_model = $request->product_model;
            foreach ($product_model as $model ){
                $partsmodel = new HarvesterPartsModels();
                $partsmodel->parts_id = $harvester_part->parts_id;
                $partsmodel->model_id = $model['id'];
                $partsmodel->save();
        }
        }
        return response()->json(['message' => 'Successfully Harvester Part Created', 200]);

    }

    public function update(Request $request, $parts_id)
    {
        $this->validate($request, [
            'image' => 'required|min:jpeg,jpg,png,svg'
        ]);
        $harvester_part = HarvesterParts::where('parts_id', $parts_id)->first();
        $image = $request->image;
        if ($image != $harvester_part->image) {
            if ($request->has('image')) {
                $destinationPath = 'images/HarvesterParts/';
                $file_old = public_path('/') . $destinationPath . $harvester_part->image;
                if (file_exists($file_old)) {
                    unlink($file_old);
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->resize('800', '700')->save(public_path('images/HarvesterParts/') . $name);
            } else {
                $name = $harvester_part->image;
            }
        } else {
            $name = $harvester_part->image;
        }

        $harvester_part->custom_name = $request->custom_name;
        $harvester_part->ProductCode =  $request['ProductCode']['ProductCode'];
//        $harvester_part->product_model_id = $request->product_model_id;
        $harvester_part->section_id = $request->section_id;
        $harvester_part->image = $name;
        $harvester_part->parts_type = $request->parts_type;
        if ($harvester_part->save()){
            $product_model = $request->product_model;
            HarvesterPartsModels::where('parts_id',$harvester_part->parts_id)->delete();
            foreach ($product_model as $model ){
                $partsmodel = new HarvesterPartsModels();
                $partsmodel->parts_id = $harvester_part->parts_id;
                $partsmodel->model_id = $model['id'];
                $partsmodel->save();
            }
        }
        return response()->json(['message' => 'Harvester Part Successfully Updated '], 200);
    }

    public function destroy($parts_id)
    {
        $HarvesterParts = HarvesterParts::where('parts_id', $parts_id)->first();
        if ($HarvesterParts->image) {
            $destinationPath = 'images/HarvesterParts/';

            $file = public_path('/') . $destinationPath . $HarvesterParts->image;
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $HarvesterParts->delete();
        HarvesterPartsModels::where('parts_id',$parts_id)->delete();
        return response()->json(['message' => 'Harvester Part Deleted Successfully']);
    }


    public function search($query)
    {
        return new HarvesterPartsCollection(HarvesterParts::Where('custom_name', 'like', "%$query%")
            ->paginate(10));
    }
}

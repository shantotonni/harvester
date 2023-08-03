<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\HarvesterParts\HarvesterPartsStoreRequest;
use App\Http\Resources\HarvesterParts\HarvesterPartsCollection;
use App\Models\HarvesterParts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class HarvesterPartsController extends Controller
{
    public function index()
    {
        $harvester_parts = HarvesterParts::with('ProductModel','MirrorProducts','section')->paginate(10);
        return new HarvesterPartsCollection($harvester_parts);
    }

    public function store(HarvesterPartsStoreRequest $request)
    {

        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->resize('800','700')->save(public_path('images/HarvesterParts/') . $name);
        } else {
            $name = 'not_found.jpg';
        }

        $harvester_part = new HarvesterParts();
        $harvester_part->custom_name = $request->custom_name;
        $harvester_part->ProductCode = $request->ProductCode;
        $harvester_part->product_model_id = $request->product_model_id;
        $harvester_part->section_id = $request->section_id;
        $harvester_part->image = $name;
        $harvester_part->save();
        return response()->json(['message' => 'Successfully Harvester Part Created', 200]);

    }

    public function update(Request $request, $parts_id)
    {
        $harvester_part = HarvesterParts::where('parts_id', $parts_id)->first();
        $image = $request->image;
        if ($image != $harvester_part->image) {
            if ($request->has('image')) {
                $destinationPath = 'images/HarvesterParts/';
                $file_old = public_path('/').$destinationPath.$harvester_part->image;
                if (file_exists($file_old)){
                    unlink($file_old);
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->resize('800','700')->save(public_path('images/HarvesterParts/') . $name);
            } else {
                $name = $harvester_part->image;
            }

        }
        else{
            $name = $harvester_part->image;
        }

        $harvester_part->custom_name = $request->custom_name;
        $harvester_part->ProductCode = $request->ProductCode;
        $harvester_part->product_model_id = $request->product_model_id;
        $harvester_part->section_id = $request->section_id;
        $harvester_part->image = $name;
        $harvester_part->save();
        return response()->json(['message'=>'Harvester Part Successfully Updated '],200);
    }

    public function destroy($parts_id)
    {
        HarvesterParts::where('parts_id',$parts_id)->delete();
        return response()->json(['message'=>'Harvester Part Deleted Successfully']);
    }
    public function search($query)
    {
        return new HarvesterPartsCollection(HarvesterParts::Where('custom_name', 'like', "%$query%")->latest()->paginate(10));
    }

}

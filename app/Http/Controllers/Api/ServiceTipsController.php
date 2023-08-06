<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceTips\ServiceTipsStoreRequest;
use App\Http\Resources\ServiceTips\ServiceTipsCollection;
use App\Models\ServiceTips;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ServiceTipsController extends Controller
{

    public function index()
    {
        $service_tips = ServiceTips::paginate(10);
        return new ServiceTipsCollection($service_tips);
    }

    public function store(ServiceTipsStoreRequest $request)
    {
        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/ServiceTips/') . $name);
        } else {
            $name = 'not_found.jpg';
        }

        $service_tip = new ServiceTips();
        $service_tip->title = $request->title;
        $service_tip->type = $request->type;
        $service_tip->description = $request->description;
        $service_tip->video_link = $request->video_link;
        $service_tip->active= 'Y';
        $service_tip->image = $name;
        $service_tip->save();
        return response()->json(['message' => 'Successfully Service Tip Created', 200]);

    }

    public function update(Request $request, $id)
    {
        $service_tip = ServiceTips::where('id', $id)->first();
        $image = $request->image;
        if ($image != $service_tip->image) {
            if ($request->has('image')) {
                $destinationPath = 'images/ServiceTips/';
                $file_old = public_path('/').$destinationPath.$service_tip->image;
                if (file_exists($file_old)){
                    unlink($file_old);
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->save(public_path('images/ServiceTips/') . $name);
            } else {
                $name = $service_tip->image;
            }

        }
        else{
            $name = $service_tip->image;
        }

        $service_tip->title = $request->title;
        $service_tip->type = $request->type;
        $service_tip->description = $request->description;
        $service_tip->video_link = $request->video_link;
        $service_tip->active= 'Y';
        $service_tip->image = $name;
        $service_tip->save();
        return response()->json(['message' => 'Successfully Service Tip Updated', 200]);
    }

    public function destroy($id)
    {
        ServiceTips::where('id',$id)->delete();
        return response()->json(['message'=>'Service Tips Deleted Successfully']);
    }

}

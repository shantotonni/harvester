<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceCenter\ServiceCenterStoreRequest;
use App\Http\Requests\ServiceCenter\ServiceCenterUpdateRequest;
use App\Http\Resources\ServiceCenter\ServiceCenterCollection;
use App\Models\ServiceCenter;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ServiceCenterController extends Controller
{

    public function index(Request $request)
    {
        $area_id = $request->area_id;
        $service_centers = ServiceCenter::query()->with('area');
        if (!empty($area_id)) {

            $service_centers = $service_centers->where('area_id', $area_id);
        }
        $service_centers = $service_centers->paginate(10);

        return new ServiceCenterCollection($service_centers);
    }

    public function store(ServiceCenterStoreRequest $request)
    {
        $this->validate($request, [
            'image' => 'required|min:jpeg,jpg,png,svg'
        ]);
        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid().time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/service_center/').$name);
        } else {
            $name = 'not_found.jpg';
        }

        $service_center                         = new ServiceCenter();
        $service_center->area_id                = $request->area_id;
        $service_center->service_center_name    = $request->service_center_name;
        $service_center->address                = $request->address;
        $service_center->responsible_person     = $request->responsible_person;
        $service_center->mobile                 = $request->mobile;
        $service_center->lat                    = $request->lat;
        $service_center->lon                    = $request->lon;
        $service_center->image                  = $name;
        $service_center->save();
        return response()->json(['message' => 'Service Center created Successfully', 200]);
    }


    public function update(ServiceCenterStoreRequest $request, $id)
    {
        $service_center = ServiceCenter::where('id', $id)->first();
        $this->validate($request, [
            'image' => 'required|min:jpeg,jpg,png,svg'
        ]);

        $image = $request->image;
        if ($image != $service_center->image) {
            if ($request->has('image')) {
                $destinationPath = 'images/service_center/';
                if ($service_center->image){
                    $file_old = public_path('/').$destinationPath.$service_center->image;
                    if (file_exists($file_old)){
                        unlink($file_old);
                    }
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->save(public_path('images/service_center/') . $name);
            }else {
                $name = $service_center->image;
            }
        }else{
            $name = $service_center->image;
        }

        $service_center->area_id                = $request->area_id;
        $service_center->service_center_name    = $request->service_center_name;
        $service_center->address                = $request->address;
        $service_center->responsible_person     = $request->responsible_person;
        $service_center->mobile                 = $request->mobile;
        $service_center->lat                    = $request->lat;
        $service_center->lon                    = $request->lon;
        $service_center->image                  = $name;
        $service_center->save();
        return response()->json(['message' => 'Service Center updated Successfully', 200]);
    }

    public function destroy($id)
    {
        ServiceCenter::where('id', $id)->delete();
        return response()->json(['message' => 'Service Center Deleted Successfully', 200]);
    }

    public function search($query)
    {
        return new ServiceCenterCollection(ServiceCenter::Where('address', 'like', "%$query%")->latest()->paginate(10));
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceCenter\ServiceCenterStoreRequest;
use App\Http\Requests\ServiceCenter\ServiceCenterUpdateRequest;
use App\Http\Resources\ServiceCenter\ServiceCenterCollection;
use App\Models\ServiceCenter;
use Illuminate\Http\Request;

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
        $service_center = new ServiceCenter();
        $service_center->area_id = $request->area_id;
        $service_center->address = $request->address;
        $service_center->responsible_person = $request->responsible_person;
        $service_center->mobile = $request->mobile;
        $service_center->lat = $request->lat;
        $service_center->lon = $request->lon;
        $service_center->save();
        return response()->json(['message' => 'Service Center created Successfully', 200]);
    }


    public function update(ServiceCenterUpdateRequest $request, $id)
    {
        $service_center = ServiceCenter::where('id', $id)->first();
        $service_center->area_id = $request->area_id;
        $service_center->address = $request->address;
        $service_center->responsible_person = $request->responsible_person;
        $service_center->mobile = $request->mobile;
        $service_center->lat = $request->lat;
        $service_center->lon = $request->lon;
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

    public function getAllServiceCenter()
    {
        $service_centers = ServiceCenter::orderBy('CreatedDate', 'desc')->get();
        return response()->json([
            'service_centers' => $service_centers
        ]);
    }

}

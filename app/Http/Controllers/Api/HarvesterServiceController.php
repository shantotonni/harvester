<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\HarvesterService\HarvesterServiceStoreRequest;
use App\Http\Requests\HarvesterService\HarvesterServiceUpdateRequest;
use App\Http\Resources\HarvesterService\HarvesterServiceCollection;
use App\Models\HarvesterService;


class HarvesterServiceController extends Controller
{
    public function index()
    {
        $harvester_services = HarvesterService::with('models','service_types')->paginate(10);
        return new HarvesterServiceCollection($harvester_services);
    }

    public function store(HarvesterServiceStoreRequest $request)
    {
        $harvester_service = new HarvesterService();
        $harvester_service->model_id = $request->model_id;
        $harvester_service->servicing_type_id = $request->servicing_type_id;
        $harvester_service->from_hr = $request->from_hr;
        $harvester_service->to_hr = $request->to_hr;
        $harvester_service->save();
        return response()->json(['message' => 'Harvester Service Details created Successfully', 200]);
    }


    public function update(HarvesterServiceUpdateRequest $request, $id)
    {
        $harvester_service = HarvesterService::where('id', $id)->first();
        $harvester_service->model_id = $request->model_id;
        $harvester_service->servicing_type_id = $request->servicing_type_id;
        $harvester_service->from_hr = $request->from_hr;
        $harvester_service->to_hr = $request->to_hr;
        $harvester_service->save();
        return response()->json(['message' => 'Harvester Service Details  updated Successfully', 200]);
    }

    public function destroy($id)
    {
        HarvesterService::where('id', $id)->delete();
        return response()->json(['message' => 'Harvester Service Details  Deleted Successfully', 200]);
    }

    public function search($query)
    {
        return new HarvesterServiceCollection(HarvesterService::Where('address', 'like', "%$query%")->latest()->paginate(10));
    }

    public function getAllHarvesterServiceDetails()
    {
        $harvester_services = HarvesterService::orderBy('CreatedDate', 'desc')->get();
        return response()->json([
            'harvester_services' => $harvester_services
        ]);
    }
}

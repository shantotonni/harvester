<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\HarvesterService\HarvesterServiceStoreRequest;
use App\Http\Requests\HarvesterService\HarvesterServiceUpdateRequest;
use App\Http\Resources\HarvesterService\HarvesterServiceCollection;
use App\Models\HarvesterService;
use App\Models\SparePartsMirror;
use Illuminate\Http\Request;


class HarvesterServiceController extends Controller
{
    public function index(Request  $request)
    {
        $model_id = $request->model_id;

        $harvester_services = HarvesterService::query()->with(['ProductModel','SparePartsMirror']);
        if (!empty($model_id)){
            $harvester_services = $harvester_services->where('model_id',$model_id);
        }
        $harvester_services = $harvester_services->paginate(10);
        return new HarvesterServiceCollection($harvester_services);
    }

    public function store(HarvesterServiceStoreRequest $request)
    {

        $harvester_service = new HarvesterService();
        $harvester_service->model_id = $request->model_id;
        $harvester_service->servicing_type_id = $request->servicing_type_id;
        $harvester_service->from_hr = $request->from_hr;
        $harvester_service->to_hr = $request->to_hr;
        $harvester_service->fix_hour = $request->fix_hour;
        $harvester_service->parts_name = $request->parts_name;
//        $harvester_service->parts_code = $request['ProductCode']['ProductCode'];
        $harvester_service->parts_code = $request['ProductCode']['ProductCode'];
        $harvester_service->quantity = $request->quantity;
        $harvester_service->servicing_status = $request->servicing_status;
        //$harvester_service->price = $request->UnitPrice;
        $harvester_service->save();
        return response()->json(['message' => 'Harvester Service Details created Successfully', 200]);
    }


    public function update(Request $request, $id)
    {
        $harvester_service = HarvesterService::where('id', $id)->first();
        $harvester_service->model_id = $request->model_id;
        $harvester_service->servicing_type_id = $request->servicing_type_id;
        $harvester_service->from_hr = $request->from_hr;
        $harvester_service->to_hr = $request->to_hr;
        $harvester_service->fix_hour = $request->fix_hour;
        $harvester_service->parts_name = $request->parts_name;
//        $harvester_service->parts_code = $request['ProductCode']['ProductCode'];
        $harvester_service->parts_code = $request['ProductCode']['ProductCode'];
        $harvester_service->quantity = $request->quantity;
        $harvester_service->servicing_status = $request->servicing_status;
        //$harvester_service->price = $request->UnitPrice;
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
        return new HarvesterServiceCollection(HarvesterService::Where('product_model.model_name', 'like', "%$query%")
            ->join('product_model','product_model.id','harvester_service_details.model_id')
            ->paginate(10));
    }


}

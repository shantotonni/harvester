<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceCenter\ServiceCenterUpdateRequest;
use App\Http\Requests\ServiceType\ServiceTypeStoreRequest;
use App\Http\Resources\ServiceType\ServiceTypeCollection;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    public function index(){
        $service_types = ServiceType::paginate(10);
        return new ServiceTypeCollection($service_types);
    }

    public function store(ServiceTypeStoreRequest $request)
    {
        $service_type = new ServiceType();
        $service_type->name = $request->name;
        $service_type->name_bn = $request->name_bn;
        $service_type->code = $request->code;
        $service_type->save();
        return response()->json(['message' => 'Service Type created Successfully', 200]);
    }


    public function update(Request $request, $id)
    {
        $service_type = ServiceType::where('id', $id)->first();

        $service_type->name = $request->name;
        $service_type->name_bn = $request->name_bn;
        $service_type->code = $request->code;
        $service_type->save();
        return response()->json(['message' => 'Service Type updated Successfully', 200]);
    }

    public function destroy($id)
    {
        ServiceType::where('id', $id)->delete();
        return response()->json(['message' => 'Service Type Deleted Successfully', 200]);
    }

    public function search($query)
    {
        return new ServiceTypeCollection(ServiceType::Where('name', 'like', "%$query%")->latest()->paginate(10));
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceEngineer\ServiceEngineerStoreRequest;
use App\Http\Resources\ServiceEngineer\ServiceEngineerCollection;
use App\Models\ServiceEngineer;
use Illuminate\Http\Request;

class ServiceEngineerController extends Controller
{
    public function index(Request $request)
    {

        $district_id =$request->district_id;
        $service_engineers = ServiceEngineer::query()->with('District');
        if (!empty($district_id)){
            $service_engineers=$service_engineers->where('district_id',$district_id);

        }
        $service_engineers = $service_engineers->paginate();

        return new ServiceEngineerCollection($service_engineers);
    }

    public function store(ServiceEngineerStoreRequest $request)
    {
        $service_engineer = new ServiceEngineer();
        $service_engineer->engineer_name = $request->engineer_name;
        $service_engineer->district_id = $request->district_id;
        $service_engineer->address = $request->address;
        $service_engineer->mobile = $request->mobile;
        $service_engineer->save();
        return response()->json(['message' => 'Showroom created Successfully', 200]);
    }


    public function update(Request $request, $id)
    {
        $service_engineer = ServiceEngineer::where('id', $id)->first();
        $service_engineer->engineer_name = $request->engineer_name;
        $service_engineer->district_id = $request->district_id;
        $service_engineer->address = $request->address;
        $service_engineer->mobile = $request->mobile;
        $service_engineer->save();
        return response()->json(['message' => 'Service Engineer updated Successfully', 200]);
    }

    public function destroy($id)
    {
        ServiceEngineer::where('id', $id)->delete();
        return response()->json(['message' => 'Service Engineer Deleted Successfully', 200]);
    }

    public function search($query)
    {
        return new ServiceEngineerCollection(ServiceEngineer::Where('engineer_name', 'like', "%$query%")->latest()->paginate(10));
    }

}

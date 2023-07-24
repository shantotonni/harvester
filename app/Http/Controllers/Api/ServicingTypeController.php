<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServicingType\ServicingTypeStoreRequest;
use App\Http\Requests\ServicingType\ServicingTypeUpdateRequest;
use App\Http\Resources\ServicingType\ServicingTypeCollection;
use App\Models\ServiceCenter;
use App\Models\ServicingType;
use Illuminate\Http\Request;

class ServicingTypeController extends Controller
{
    public function index(){

        $servicing_types = ServicingType::paginate(10);
        return new ServicingTypeCollection($servicing_types);

    }
    public function store(ServicingTypeStoreRequest $request)
    {
        $servicing_type = new ServicingType();
        $servicing_type->name = $request->name;
        $servicing_type->code = $request->code;

        $servicing_type->save();
        return response()->json(['message' => 'Servicing Type created Successfully', 200]);
    }


    public function update(Request $request, $id)
    {
        $servicing_type = ServicingType::where('id', $id)->first();
        $servicing_type->name = $request->name;
        $servicing_type->code = $request->code;
        $servicing_type->save();
        return response()->json(['message' => 'Servicing Type updated Successfully', 200]);
    }

    public function destroy($id)
    {
        ServicingType::where('id', $id)->delete();
        return response()->json(['message' => 'Servicing Type Deleted Successfully', 200]);
    }

    public function search($query)
    {
        return new ServicingTypeCollection(ServicingType::Where('name', 'like', "%$query%")->latest()->paginate(10));
    }
}

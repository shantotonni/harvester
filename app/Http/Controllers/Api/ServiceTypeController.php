<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceType\ServiceTypeCollection;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    public function index(){
        $servicetypes = ServiceType::all();
        return new ServiceTypeCollection($servicetypes);
    }
    public function getAllServicetype(Request $request){
        $servicetypes = ServiceType::OrderBy('id','asc')->get();
        return response()->json([
            'servicetypes'=>$servicetypes
        ]);

    }
}

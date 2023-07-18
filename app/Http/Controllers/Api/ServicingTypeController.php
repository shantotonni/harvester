<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServicingType\ServicingTypeCollection;
use App\Models\ServiceCenter;
use App\Models\ServicingType;
use Illuminate\Http\Request;

class ServicingTypeController extends Controller
{
    public function index(){
        $servicingtypes = ServicingType::all();
        return new ServicingTypeCollection($servicingtypes);
    }
    public function getAllServicingtype(Request $request){
        $servicingtypes = ServicingType::OrderBy('id','asc')->get();
        return response()->json([
            'servicingtypes'=>$servicingtypes
        ]);

    }
}

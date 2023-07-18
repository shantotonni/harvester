<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductModel\ProductModelCollection;
use App\Models\ProductModel;
use Illuminate\Http\Request;

class ProductModelController extends Controller
{
    public function index(){
        $models = ProductModel::all();
        return new ProductModelCollection($models);
    }
    public function getAllProductModel(Request $request){
        $models = ProductModel::OrderBy('id','asc')->get();
        return response()->json([
            'models'=>$models
        ]);
    }
}

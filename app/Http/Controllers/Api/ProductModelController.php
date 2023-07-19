<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductModel\ProductModelCollection;
use App\Models\ProductModel;
use Illuminate\Http\Request;

class ProductModelController extends Controller
{
    public function index(){
        $models = ProductModel::with('Products')->paginate(20);
        return new ProductModelCollection($models);
    }


}

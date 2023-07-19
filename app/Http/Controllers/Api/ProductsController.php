<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Products\ProductsCollection;
use App\Models\Products;

class ProductsController extends Controller
{
    public function index(){
        $products = Products::paginate(10);
        return new ProductsCollection($products);
    }
}

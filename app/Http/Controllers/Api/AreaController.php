<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Area\AreaCollection;
use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
public function index(){
    $areas = Area::all();
    return new AreaCollection($areas);
}

}

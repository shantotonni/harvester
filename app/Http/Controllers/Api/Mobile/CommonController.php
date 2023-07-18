<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Banner\BannerCollection;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\District\DistrictCollection;
use App\Http\Resources\Doctor\DoctorCollection;
use App\Http\Resources\Doctor\DoctorResource;
use App\Http\Resources\MOInfo\MOInfoCollection;
use App\Http\Resources\Portfolio\PortfolioCollection;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Shop\ShopCollection;
use App\Http\Resources\Upazila\UpazilaCollection;
use App\Models\Area;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\District;
use App\Models\Doctor;
use App\Models\Menu;
use App\Models\MOInfo;
use App\Models\Portfolio;
use App\Models\Product;
use App\Models\ProductModel;
use App\Models\Role;
use App\Models\ServiceCenter;
use App\Models\ServiceType;
use App\Models\ServicingType;
use App\Models\Shop;
use App\Models\Upazila;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommonController extends Controller
{

    public function getAllBrand()
    {
        $brands = Brand::orderBy('CreatedDate', 'desc')->get();
        return response()->json([
            'brands' => $brands
        ]);
    }

    public function getAllUser()
    {
        $users = User::orderBy('CreatedDate', 'desc')->get();
        return response()->json([
            'users' => $users
        ]);
    }

    public function getAllCustomer()
    {
        $customers = Customer::orderBy('CreatedDate', 'desc')->get();
        return response()->json([
            'customers' => $customers
        ]);
    }

    public function getAllMenu()
    {
        $menus = Menu::orderBy('CreatedAt', 'desc')->get();
        return response()->json([
            'menus' => $menus
        ]);
    }

    public function getAllRole()
    {
        $roles = Role::orderBy('CreatedDate', 'desc')->get();
        return response()->json([
            'roles' => $roles
        ]);
    }
    public function getAllServiceCenter()
    {
        $service_centers = ServiceCenter::orderBy('CreatedDate', 'desc')->get();
        return response()->json([
            'service_centers' => $service_centers
        ]);
    }

    public function getAllArea(Request $request){
        $areas = Area::OrderBy('id','asc')->get();
        return response()->json([
            'areas'=>$areas
        ]);

    }
    public function getAllProductModel(Request $request){
        $models = ProductModel::OrderBy('id','asc')->get();
        return 'ok';
        return response()->json([
            'models'=>$models
        ]);

    }
    public function getAllServicetype(Request $request){
        $servicetypes = ServiceType::OrderBy('id','asc')->get();
        return response()->json([
            'servicetypes'=>$servicetypes
        ]);

    }
    public function getAllServicingtype(Request $request)
    {
        $servicingtypes = ServicingType::OrderBy('id', 'asc')->get();
        return response()->json([
            'servicingtypes' => $servicingtypes
        ]);
    }
}

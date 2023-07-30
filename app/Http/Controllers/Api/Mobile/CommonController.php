<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Banner\BannerCollection;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\District\DistrictCollection;
use App\Http\Resources\Doctor\DoctorCollection;
use App\Http\Resources\Doctor\DoctorResource;
use App\Http\Resources\HarvesterInfo\HarvesterInfoCollection;
use App\Http\Resources\MOInfo\MOInfoCollection;
use App\Http\Resources\Portfolio\PortfolioCollection;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\ProductModel\ProductModelCollection;
use App\Http\Resources\Products\ProductsCollection;
use App\Http\Resources\Shop\ShopCollection;
use App\Http\Resources\Upazila\UpazilaCollection;
use App\Models\Area;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Crop;
use App\Models\Customer;
use App\Models\District;
use App\Models\Doctor;
use App\Models\HarvesterInfo;
use App\Models\HarvesterService;
use App\Models\Menu;
use App\Models\MOInfo;
use App\Models\Portfolio;
use App\Models\Product;
use App\Models\ProductModel;
use App\Models\Products;
use App\Models\Role;
use App\Models\SeasonalCrops;
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
        $service_centers = ServiceCenter::orderBy('created_at', 'desc')->paginate(15);
        return response()->json([
            'service_centers' => $service_centers
        ]);
    }

    public function getAllArea(){
        $areas = Area::OrderBy('id','asc')->get();
        return response()->json([
            'areas'=>$areas
        ]);
    }

    public function getAllProductModel(){
        $models = ProductModel::OrderBy('id','asc')->get();
        return response()->json([
            'models'=>$models
        ]);
    }

    public function getAllProducts(){
        $products = Products::OrderBy('id','asc')->paginate(15);
        return response()->json([
            'products'=>$products
        ]);
    }

    public function getAllServiceType(){
        $service_types = ServiceType::OrderBy('id','asc')->paginate(15);
        return response()->json([
            'service_types'=>$service_types
        ]);
    }

    public function getAllServicingType()
    {
        $servicing_types = ServicingType::OrderBy('id', 'asc')->get();
        return response()->json([
            'servicing_types' => $servicing_types
        ]);
    }

    public function getAllHarvesterServiceDetails()
    {
        $harvester_services = HarvesterService::orderBy('created_at', 'desc')->paginate(15);
        return response()->json([
            'harvester_services' => $harvester_services
        ]);
    }

    public function getAllHarvesterInfo()
    {
        $harvester_infos = HarvesterInfo::orderBy('created_at', 'desc')->paginate(15);
        return response()->json([
            'harvester_infos' => new HarvesterInfoCollection($harvester_infos)
        ]);
    }

    public function getAllDistrictWiseSeasonalCrops()
    {
        $seasonal_crops = SeasonalCrops::orderBy('created_at', 'desc')->get();
        return response()->json([
            'seasonal_crops' => $seasonal_crops
        ]);
    }

    public function getAllCrops()
    {
        $crops = Crop::orderBy('created_at', 'desc')->get();
        return response()->json([
            'crops' => $crops
        ]);
    }

    public function getAllDistricts()
    {
        $districts = District::orderBy('created_at', 'desc')->get();
        return response()->json([
            'districts' => $districts
        ]);
    }

    public function getAllModelByProduct($id){
        return new ProductModelCollection(ProductModel::where('product_id',$id)->get());
    }
}

<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Banner\BannerCollection;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\District\DistrictCollection;
use App\Http\Resources\Doctor\DoctorCollection;
use App\Http\Resources\Doctor\DoctorResource;
use App\Http\Resources\HarvesterInfo\HarvesterInfoCollection;
use App\Http\Resources\HarvesterParts\HarvesterPartsCollection;
use App\Http\Resources\HarvesterServiceDetailsCollection;
use App\Http\Resources\MirrorProduct\MirrorProductCollection;
use App\Http\Resources\MOInfo\MOInfoCollection;
use App\Http\Resources\Portfolio\PortfolioCollection;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\ProductModel\ProductModelCollection;
use App\Http\Resources\Products\ProductsCollection;
use App\Http\Resources\SeasonalCrops\SeasonalCropsCollection;
use App\Http\Resources\ServiceEngineer\ServiceEngineerCollection;
use App\Http\Resources\ServiceRequest\ServiceRequestJobCardCollection;
use App\Http\Resources\ServiceRequest\ServiceRequestJobCardResource;
use App\Http\Resources\ServiceTips\ServiceTipsCollection;
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
use App\Models\HarvesterParts;
use App\Models\HarvesterService;
use App\Models\Menu;
use App\Models\MOInfo;
use App\Models\Portfolio;
use App\Models\MirrorProduct;
use App\Models\ProductModel;
use App\Models\Products;
use App\Models\Role;
use App\Models\SeasonalCrops;
use App\Models\Section;
use App\Models\ServiceCenter;
use App\Models\ServiceEngineer;
use App\Models\ServiceRequest;
use App\Models\ServiceTips;
use App\Models\ServiceType;
use App\Models\ServicingType;
use App\Models\Shop;
use App\Models\Showroom;
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
        $service_centers = ServiceCenter::orderBy('created_at', 'desc')->get();
        return response()->json([
            'service_centers' => $service_centers
        ]);
    }
    public function getAllShowroom()
    {
        $showrooms = Showroom::orderBy('created_at', 'desc')->get();
        return response()->json([
            'showrooms' => $showrooms
        ]);
    }

    public function getAllArea(){
        $areas = Area::OrderBy('id','asc')->get();
        return response()->json([
            'areas'=>$areas
        ]);
    }

    public function getAllProductModel(){
        $models = ProductModel::OrderBy('id','asc')->where('product_id',4)->select('id','product_id','model_name','model_name_bn')->get();
        return response()->json([
            'models'=>$models
        ]);
    }

    public function getAllProducts(){
        $products = Products::OrderBy('id','asc')->where('id',4)->get();
        return response()->json([
            'products'=>$products
        ]);
    }
    public function getAllMirrorProduct(){

        $mirror_products = MirrorProduct::OrderBy('ProductCode','desc')
            ->select('ProductCode','ProductName','UnitPrice')
            ->where('Business','W')
            ->where('Active','Y')
            ->get();
        return response()->json([
            'mirror_products'=>$mirror_products
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
    public function getAllSectionList()
    {
        $sections = Section::OrderBy('id', 'asc')->get();
        return response()->json([
            'sections' => $sections
        ]);
    }
    public function getAllTechnician()
    {
        $technitians = Section::OrderBy('id', 'asc')->get();
        return response()->json([
            'technitians' => $technitians
        ]);
    }

    public function getAllHarvesterServiceDetails(Request $request)
    {
        $hour = $request->hour;
        $harvester_services = HarvesterService::orderBy('created_at', 'desc')->with('ServicingType','ProductModel')
            ->where('from_hr','<=', $hour)
            ->where('to_hr','>=', $hour)
            ->where('model_id',$request->model_id)->get();
       return new HarvesterServiceDetailsCollection($harvester_services);
    }

    public function getAllHarvesterInfo()
    {
        $harvester_infos = HarvesterInfo::orderBy('created_at', 'desc')->paginate(15);
        return response()->json([
            'product_list' => new HarvesterInfoCollection($harvester_infos)
        ]);
    }
    public function getAllHarvesterParts()
    {
        $harvester_parts = HarvesterParts::orderBy('created_at', 'desc')->paginate(15);
        return response()->json([
            'harvester_parts' => new HarvesterPartsCollection($harvester_parts)
        ]);
    }

    public function getAllDistrictWiseSeasonalCrops(Request $request)
    {
        $district_id = $request->district_id;
        $seasonal_crops_id = $request->seasonal_crops_id;

        $seasonal_crops = SeasonalCrops::query()->with('District','Crop');
        if ($district_id){
            $seasonal_crops = $seasonal_crops ->where('district_id',$district_id);
        }
        if ($seasonal_crops_id){
            $seasonal_crops = $seasonal_crops->where('seasonal_crops_id',$request->seasonal_crops_id);
        }

        $seasonal_crops = $seasonal_crops->orderBy('created_at', 'desc')->get();
        return new SeasonalCropsCollection($seasonal_crops);
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
    public function getAllSections()
    {
        $sections = Section::orderBy('created_at', 'asc')->get();
        return response()->json([
            'sections' => $sections
        ]);
    }
    public function getAllServiceTips()
    {
        $service_tips = ServiceTips::orderBy('created_at', 'asc')->get();
        return response()->json([
            'service_tips' => new ServiceTipsCollection($service_tips)
        ]);
    }
    public function getAllServiceEngineer()
    {
        $service_engineers = ServiceEngineer::orderBy('created_at', 'asc')->get();
        return response()->json([
            'service_engineers' => new ServiceEngineerCollection($service_engineers)
        ]);
    }

    public function getAllPendingServiceRequestList()
    {
        $job_cards = ServiceRequest::orderBy('created_at', 'asc')->get();
        return response()->json([
            'job_cards' => new ServiceRequestJobCardCollection($job_cards)
        ]);
    }
    public function getAllCompletedServiceRequestList()
    {
        $job_cards = ServiceRequest::orderBy('created_at', 'asc')->get();
        return response()->json([
            'job_cards' => new ServiceRequestJobCardCollection($job_cards)
        ]);
    }

    public function getAllDistrictsUpazilla()
    {
        $districts = District::orderBy('created_at', 'desc')->get();
        $upazilla = Upazila::orderBy('created_at', 'desc')->get();
        return response()->json([
            'districts' => $districts,
            'upazilla' => $upazilla,
        ]);
    }

    public function getAllModelByProduct($id){
        return new ProductModelCollection(ProductModel::where('product_id',$id)->get());
    }
    public function getAllProblemSection(){
        $sections = Section::select('id','name','product_id')->where('product_id',4)->get();
        return response()->json([
            'status' => 'success',
            'sections' => $sections
        ]);
    }
}

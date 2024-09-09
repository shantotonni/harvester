<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceRequest\ServiceRequestCollection;
use App\Http\Resources\ServiceRequest\ServiceRequestJobCardCollection;
use App\Models\Customer;
use App\Models\CustomerChassis;
use App\Models\District;
use App\Models\JobCard;
use App\Models\ProductModel;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\UserArea;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Facades\JWTAuth;

class ServiceRequestController extends Controller
{
    function __construct()
    {
        Config::set('jwt.user', Customer::class);
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => Customer::class,
        ]]);
    }

    public function getAllCustomerServiceRequest()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $customer_chassis = CustomerChassis::where('customer_id',$user->id)->pluck('chassis_no');
        $job_cards = JobCard::whereIn('chassis_number',$customer_chassis)->with('model','products','district','upazila','section','technitian')
            ->orderBy('id','desc')
            ->paginate(15);
        return new ServiceRequestJobCardCollection($job_cards);
    }

    public function customerServiceRequest(Request $request){
        $this->validate($request,[
            'customer_mobile'=>'required',
            'section_id'=>'required',
            'model_id'=>'required',
        ]);

      $user = JWTAuth::parseToken()->authenticate();

      $product_model = ProductModel::where('id',$request->model_id)->first();
      $district = District::query()->where('id',$request->district_id)->first();
      $user_area = UserArea::query()->where('area_id', $district->area_id)->first();

      $service_request = new ServiceRequest();
      $service_request->technitian_id = '';
      $service_request->service_type_id = null;
      $service_request->call_type_id = 1;
      $service_request->engineer_id = $user_area->user_id;
      $service_request->area_id = $user_area->area_id;
      $service_request->product_id = $product_model->product_id;
      $service_request->model_id = $product_model->id;
      $service_request->section_id = $request->section_id;
      $service_request->district_id = $request->district_id;
      $service_request->upazila_id = $request->upazila_id;
      $service_request->remarks = $request->remarks;
      $service_request->customer_moblie = $request->customer_mobile;
      $service_request->customer_name = $user->name;
      $service_request->address = $user->address;
      $service_request->chassis_number = $request->chassis_number;
      $service_request->customer_id = $user->id;
      $service_request->service_requested_at = Carbon::now();
      $service_request->request_creator = 'customer';
      $service_request->lat = $request->lat;
      $service_request->lon = $request->lon;
      $service_request->save();

      return response()->json([
          'status'=>'success',
           'message'=>'Service Request Created Successfully'
          ], 200);

    }

}

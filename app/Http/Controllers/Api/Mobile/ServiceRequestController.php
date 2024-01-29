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

    public function customerServiceRequest(Request $request)
    {
        $this->validate($request,[
            'customer_mobile'=>'required',
            'section_id'=>'required',
            'model_id'=>'required',
        ]);

      $user = JWTAuth::parseToken()->authenticate();

      $product_model = ProductModel::where('id',$request->model_id)->first();
      $district = District::query()->where('id',$request->district_id)->first();
      $user_area = UserArea::query()->where('area_id', $district->area_id)->first();

      $job_card = new JobCard();
      $job_card->technitian_id = '';
      $job_card->engineer_id = $user_area->user_id;
      $job_card->area_id = $user_area->area_id;
      $job_card->product_id = $product_model->product_id;
      $job_card->model_id = $product_model->id;
      $job_card->section_id = $request->section_id;
      $job_card->customer_name = $user->name;
      $job_card->customer_moblie = $request->customer_mobile;
      $job_card->district_id = $request->district_id;
      $job_card->upazila_id = $request->upazila_id;
      $job_card->remarks = $request->remarks;
      $job_card->chassis_number = $request->chassis_number;
      $job_card->customer_id = $user->id;

      $job_card->service_wanted_at = Carbon::now();
      $job_card->service_date = Carbon::now();
      $job_card->job_creator = 'customer';
      $job_card->job_status = 'started';
      $job_card->is_approved = 0;
      $job_card->approver_id = null;
      $job_card->product_type = 'Harvester';
      $job_card->job_card_no = 'MS'.$job_card->id;
      $job_card->save();
      $job_card->job_card_no = 'MS'.$job_card->id;
      $job_card->save();

      return response()->json([
          'status'=>'success',
           'message'=>'Job Card Created Successfully'
          ], 200);

    }

}

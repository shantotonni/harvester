<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
use App\Http\Requests\ServiceRequest\ServiceRequestStoreRequest;
use App\Http\Resources\ServiceRequest\ServiceRequestCollection;
use App\Models\Customer;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Auth;


class ServiceRequestController extends Controller
{

    public function index()
    {
        $service_requests = ServiceRequest::paginate(10);
        return new ServiceRequestCollection($service_requests);
    }


    public function customerServiceRequest(ServiceRequestStoreRequest $request)
    {
        $service_request = new ServiceRequest();

        $service_request->technitian_id = '';
        $service_request->district_id = $request->district_id;
        $service_request->upazila_id = $request->upazila_id;
        $service_request->customer_id =  Auth::user();
        $service_request->customer_mobile = $request->customer_mobile;
        $service_request->remarks = $request->remarks;
        $service_request->chassis_number = $request->chassis_number;
        $service_request->job_status = $request->job_status;
        $service_request->section_id = $request->section_id;
        $service_request->product_id = $request->product_id;
        $service_request->model_id = $request->model_id;
        dd($service_request);
        $service_request->save();

        return response()->json(['message' => 'Customer Request Successfully'], 200);

    }

=======
use App\Http\Requests\JobCard\JobCardRequest;
use App\Http\Resources\ServiceRequest\ServiceRequestCollection;
use App\Models\Customer;
use App\Models\JobCard;
use App\Models\ProductModel;
use App\Models\ServiceRequest;
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
        $service_requests = JobCard::where('customer_id',$user->id)->with('model','product','district','upazila','section')->paginate(15);
        return new ServiceRequestCollection($service_requests);
    }

    public function customerServiceRequest(Request $request)
    {
        $this->validate($request,[
            'customer_mobile'=>'required',
            'section_id'=>'required',
            'customer_id'=>'required',
            'model_id'=>'required',
        ]);

      $user = JWTAuth::parseToken()->authenticate();
      $product_model = ProductModel::where('id',$request->model_id)->first();

      $job_card = new JobCard();
      $job_card->technitian_id = '';
      $job_card->product_id = $product_model->product_id;
      $job_card->model_id = $product_model->id;
      $job_card->section_id = $request->section_id;
      $job_card->customer_name = $user->name;
      $job_card->customer_moblie = $request->customer_mobile;
      $job_card->district_id = $request->district_id;
      $job_card->upazila_id = $request->upazila_id;
      $job_card->remarks = $request->remarks;
      $job_card->chassis_number = $request->chassis_number;
      $job_card->customer_id = $request->customer_id;
      $job_card->service_wanted_at = Carbon::now();
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
>>>>>>> 2fad115dd100494791695c988769a905899e016c
}

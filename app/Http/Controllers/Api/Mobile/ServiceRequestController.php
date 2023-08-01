<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
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

}

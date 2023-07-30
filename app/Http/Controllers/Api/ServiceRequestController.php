<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest\ServiceRequestStoreRequest;
use App\Http\Resources\ServiceRequest\ServiceRequestCollection;
use App\Models\Customer;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $service_requests = ServiceRequest::with('ProductModel', 'Products', 'section', 'district', 'upazila')->paginate(10);
        return new ServiceRequestCollection($service_requests);
    }


    public function store(ServiceRequestStoreRequest $request)
    {
        $user = Auth::user();
$customer = Customer::all();
      $service_request = New ServiceRequest();
      $service_request->technitian_id = '';
      $service_request->district_id = $request->district_id;
      $service_request->upazila_id = $request->upazila_id;
      $service_request->customer_moblie = $request->customer_moblie;
      $service_request->remarks = $request->remarks;
      $service_request->chassis_number = $request->chassis_no;

      $service_request->customer_id =Auth::id();
      $service_request->job_status = $request->job_status;
      $service_request->section_id = $request->section_id;
      $service_request->product_id = $request->product_id;
      $service_request->model_id = $request->model_id;
      $service_request->save();
      dd($service_request);
        return response()->json(['message'=>'Customer Request Successfully'], 200);

    }


    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ServiceRequest $serviceRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceRequest $serviceRequest)
    {
        //
    }
}

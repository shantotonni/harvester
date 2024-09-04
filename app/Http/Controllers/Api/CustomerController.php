<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerRequest;
use App\Http\Resources\Customer\CustomerCollection;
use App\Models\Customer;
use App\Models\CustomerChassis;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerController extends Controller
{
    public function index(){
        $customers = Customer::with(['ProductModel', 'Products', 'area', 'District', 'chassis_one'])->orderBy('id', 'desc')
            ->where('customer_type', 'harvester')
            ->orderBy('created_at','desc')
            ->paginate(15);
        return new CustomerCollection($customers);
    }

    public function store(CustomerRequest $request){

        DB::beginTransaction();
        try {

            if ($request->image) {
                $image = $request->image;
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->save(public_path('images/customer/') . $name);
            } else {
                $name = 'not_found.jpg';
            }
            $sdmsCustomerInfo = DB::connection('MotorBrInvoiceMirror')->table('InvoiceDetails')
                ->select('InvoiceDetails.InvoiceNo','InvoiceDetails.ChassisNo','Invoice.CustomerCode')
                ->join('Invoice','Invoice.InvoiceNo','=','InvoiceDetails.Invoiceno')
                ->where('ChassisNo',$request->chassis)->first();
            $CustomerCode = $sdmsCustomerInfo->CustomerCode;

            if (empty($sdmsCustomerInfo)){
                return response()->json([
                    'status' => "error",
                    'message' => 'Chassis Not Found'
                ], 200);
            }

            $exist_customer = Customer::where('mobile', $request->mobile)->where('customer_type', 'harvester')->exists();
            if ($exist_customer) {
                return response()->json([
                    'status' => "error",
                    'message' => 'Mobile number Already Exists'
                ], 200);
            }

            $customer = new Customer();
            $customer->code = $CustomerCode ? $CustomerCode : '';
            $customer->name = $request->name;
            $customer->mobile = $request->mobile;
            $customer->email = $request->email;
            $customer->image = $name;
            $customer->address = $request->address;
            $customer->service_hour = $request->service_hour;
            $customer->district_id = $request->district_id;
            $customer->date_of_purchase = $request->date_of_purchase;
            $customer->area_id = $request->area_id;
            $customer->product_id = $request->product_id;
            $customer->password = bcrypt($request->password);
            $customer->customer_type = 'harvester';
            $customer->save();
            if ($customer->save()) {
                $customer_chassis = new CustomerChassis();
                $customer_chassis->customer_id = $customer->id;
                $customer_chassis->model = $request->model;
                $customer_chassis->chassis_no = $request->chassis;
                $customer_chassis->save();
                DB::commit();
                return response()->json([
                    'message' => 'Customer Created Successfully',
                    'user' => $customer,
                ], 200);
            } else {
                return response()->json([
                    'status' => "error",
                    'message' => 'Something went wrong!',
                ], 200);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => "error",
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function update(CustomerRequest $request, $id)
    {
        DB::beginTransaction();
        $customer = Customer::where('id', $id)->first();
        $image = $request->image;
        if ($image != $customer->image) {
            if ($request->has('image')) {
                $destinationPath = 'images/customer/';
                if ($customer->image){
                    $file_old = public_path('/').$destinationPath.$customer->image;
                    if (file_exists($file_old)){
                        unlink($file_old);
                    }
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->save(public_path('images/customer/') . $name);
            } else {
                $name = $customer->image;
            }
        }
        else{
            $name = $customer->image;
        }

        $customer->name = $request->name;
        $customer->mobile = $request->mobile;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->service_hour = $request->service_hour;
        $customer->district_id = $request->district_id;
        $customer->date_of_purchase = $request->date_of_purchase;
        $customer->area_id = $request->area_id;
        $customer->product_id = $request->product_id;
        $customer->password = bcrypt($request->password);
        $customer->customer_type = 'harvester';
        $customer->image = $name ;
        if ($customer->save()) {
            $customer_chassis = CustomerChassis::where('customer_id', $request->id)->first();
            $customer_chassis->model = $request->model;
            $customer_chassis->chassis_no = $request->chassis;
            $customer_chassis->save();

            DB::commit();
            return response()->json([
                'message' => 'Customer Updated Successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => "error",
                'message' => 'Something went wrong!',
            ], 200);
        }
    }

    public function search($query){
        $customers = Customer::where('name', 'LIKE', "%$query%")
//            ->orWhere('CustomerCode', 'like', '%' . $query . '%')
//            ->orderBy('CustomerID','desc')
            ->paginate(10);
        return new CustomerCollection($customers);
    }
}

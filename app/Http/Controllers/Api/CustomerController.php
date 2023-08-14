<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerRequest;
use App\Http\Resources\Customer\CustomerCollection;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with('ProductModel', 'Products','area','District')->orderBy('id','desc')
            ->where('customer_type','harvester')
            ->paginate(15);
        return new CustomerCollection($customers);
    }

    public function store(CustomerRequest $request)
    {

        if ($request->has('Image')) {
            $image = $request->Image;
            $name = uniqid().time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/customer/').$name);
        } else {
            $name = 'not_found.jpg';
        }
        $customer = new Customer();
        $customer->name = $request->name;
        $customer->model_id = $request->model_id;
        $customer->product_id = $request->product_id;
        $customer->mobile = $request->mobile;
        $customer->email = $request->email;
        $customer->service_hour = $request->service_hour;
        $customer->date_of_purchase = $request->date_of_purchase;
        $customer->area_id = $request->area_id;
        $customer->district_id = $request->district_id;
        $customer->address = $request->address;
        $customer->customer_type = $request->customer_type;
        $customer->chassis = $request->chassis;
        $customer->password = bcrypt($request->password);
        $customer->date_of_purchase = $request->date_of_purchase;
        $customer->image = $name;
        $customer->save();
        return response()->json(['message'=>'Customer Created Successfully'],200);
    }

    public function update(CustomerRequest $request, $id)
    {
        $customer = Customer::where('id',$id)->first();
        $image = $request->Image;

        if ($request->has('Image')) {
            //code for remove old file
            if ($customer->image != '' && $customer->image != null) {
                $destinationPath = 'images/customer/';
                $file_old = public_path('/').$destinationPath . $customer->image;
                if (file_exists($file_old)) {
                    unlink($file_old);
                }
            }
            $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/customer/') . $name);
        } else {
            $name = $customer->image;
        }

        $customer->name = $request->name;
        $customer->model_id = $request->model_id;
        $customer->product_id = $request->product_id;
        $customer->mobile = $request->mobile;
        $customer->email = $request->email;
        $customer->service_hour = $request->service_hour;
        $customer->date_of_purchase = $request->date_of_purchase;
        $customer->area_id = $request->area_id;
        $customer->district_id = $request->district_id;
        $customer->address = $request->address;
        $customer->customer_type = $request->customer_type;
        $customer->chassis = $request->chassis;
        $customer->password = bcrypt($request->password);
        $customer->date_of_purchase = $request->date_of_purchase;
        $customer->image = $name;
        $customer->save();
        return response()->json(['message'=>'Customer Updated Successfully'],200);
    }



    public function search($query)
    {
        $customers = Customer::where('CustomerName','LIKE',"%$query%")
            ->orWhere('CustomerCode', 'like', '%' . $query . '%')
            ->orderBy('CustomerID','desc')->paginate(10);
        return new CustomerCollection($customers);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\Customer\CustomerInfoCollection;
use App\Http\Resources\Customer\CustomerInfoResource;
use App\Models\Customer;
use App\Models\CustomerChassis;
use App\Models\Otp;
use App\Models\StockBatch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerAuthController extends Controller
{
    function __construct() {
        Config::set('jwt.user', Customer::class);
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => Customer::class,
        ]]);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'mobile' => 'required',
            'password' => 'required',
        ]);

        if ($token = JWTAuth::attempt(['mobile' => $request->mobile, 'password' => $request->password,'customer_type'=>'harvester'])) {
            $customer = Customer::where('mobile',$request->mobile)->where('customer_type','harvester')->with('customer_chassis','District')->first();

//            $customer = Customer::where('mobile',$request->mobile)->where('customer_type','harvester')->with('customer_chassis','District')->first();
            //$chassis = CustomerChassis::where('customer_id',$user->id)->select('id','customer_id','chassis_no','model')->get();
            return response()->json([
                'status' => 'success',
                'token' => $token,
                'user' => new CustomerInfoResource($customer),
            ], 200);
        }
        return response()->json([
            'message' => 'Mobile or Password Not Match',
            'status' => 'error'
        ], 200);
    }

    public function findChassisNumber(Request $request)
    {
        $chassis = $request->chassis;
        $exiats = CustomerChassis::where('chassis_no',$chassis)->exists();
        if ($exiats){
            return response()->json([
                'message' => 'Chassis Already Registered',
                'status' => 'success'
            ], 200);
        }

        $chassis = StockBatch::where('BatchNo', $chassis)->with('product')->first();
        //return $chassis;
        if ($chassis) {
            $productName = '';
            if ($chassis){
                if ($chassis->ProductCode == 'W051'){
                    $productName = 'লিউলিন কম্বাইন হারভেস্টার';
                }elseif ($chassis->ProductCode == 'W098'){
                    $productName = 'মিনি কম্বাইন হারভেস্টার';
                }elseif ($chassis->ProductCode == 'W112'){
                    $productName = 'ইয়ানমার AG600 কম্বাইন হারভেস্টার';
                }elseif ($chassis->ProductCode == 'W127'){
                    $productName = 'ইয়ানমার AG600 কম্বাইন হারভেস্টার';
                }elseif ($chassis->ProductCode == 'W133'){
                    $productName = 'লিউলিন কম্বাইন হারভেস্টার';
                }elseif ($chassis->ProductCode == 'W146'){
                    $productName = 'লোভল RG108 কম্বাইন হারভেস্টার';
                }elseif ($chassis->ProductCode == 'W148'){
                    $productName = 'ইয়ানমার YH700 কম্বাইন হারভেস্টার';
                }elseif ($chassis->ProductCode == 'W150'){
                    $productName = 'ইয়ানমার AG600 কম্বাইন হারভেস্টার';
                }elseif ($chassis->ProductCode == 'W151'){
                    $productName = 'ইয়ানমার AG600 কম্বাইন হারভেস্টার';
                }else{
                    $productName = $chassis->product->ProductName;
                }
            }
            return response()->json([
                'status' => 'success',
                'chassis' => $chassis->BatchNo,
                'model' => $productName,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No Found Data',
            ]);
        }
    }

    public function sendOtp(Request $request)
    {
        $this->validate($request, [
            'mobile' => 'required|min:11|max:11',
        ]);

        $exist_customer = Customer::where('mobile', $request->mobile)->where('customer_type', 'harvester')->exists();

        if ($exist_customer) {
            return response()->json([
                'status' => 200,
                'message' => 'Mobile number Already Exists'
            ], 200);
        } else {
            try {
                $six_digit_random_number = random_int(100000, 999999);
                $smscontent = 'Otp Code - ' . $six_digit_random_number;
                $mobile = $request->mobile;
                $this->sendsms($ip = '192.168.100.213', $userid = 'motors', $password = 'Asdf1234', $smstext = urlencode($smscontent), $receipient = urlencode($mobile));

                $otp = new Otp();
                $otp->otp_code = $six_digit_random_number;
                $otp->mobile = $mobile;
                $otp->Status = 0;
                $otp->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'success'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 401,
                    'error' => $e
                ]);
            }
        }
    }

    public function checkOtp(Request $request)
    {
        $otp = $request->otp_code;
        $mobile = $request->mobile;

        $check_otp = Otp::where('mobile', $mobile)->where('otp_code', $otp)->where('Status', 0)->orderBy('created_at', 'desc')->first();

        if ($check_otp) {
            $check_otp->status = 1;
            $check_otp->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Otp Match Successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Otp Not Match'
            ]);
        }
    }

    public function registration(Request $request)
    {
        $chassis = $request->chassis;
        $model = $request->model;
        $this->validate($request, [
            'name' => 'required',
            'mobile' => 'required',
            'chassis' => 'required',
            'password' => 'required',
        ]);

        DB::beginTransaction();

        try {
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
                    'status' => 200,
                    'message' => 'Mobile number Already Exists'
                ], 200);
            }
            $customer = new Customer();
            $customer->code             = $CustomerCode ? $CustomerCode : '';
            $customer->name             = $request->name;
            $customer->mobile           = $request->mobile;
            $customer->email            = $request->email;
            $customer->district_id      = $request->district_id;
            $customer->password         = bcrypt($request->password);
            $customer->customer_type    = 'harvester';

            if ($customer->save()) {
                if ($token = JWTAuth::attempt(['mobile' => $request->mobile, 'password' => $request->password,'customer_type'=>'harvester'])) {
                    $customer_chassis               = new CustomerChassis();
                    $customer_chassis->customer_id  = $customer->id;
                    $customer_chassis->model        = $model;
                    $customer_chassis->chassis_no   = $chassis;
                    $customer_chassis->save();

                    $customer = Customer::where('mobile',$request->mobile)->where('customer_type','harvester')->with('Customer_chassis')->first();

                    DB::commit();
                    return response()->json([
                        'status' => "success",
                        'token' => $token,
                        'user' => $customer,
                    ], 200);
                } else {
                    return response()->json([
                        'status' => "error",
                        'message' => 'Something went wrong!',
                    ], 200);
                }
            }

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => "error",
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function updateProfileMobile(Request $request)
    {

        $filename = $this->uploadFile($request->file('image'));

        $user =JWTAuth::parseToken()->authenticate();

        $customer = Customer::where('id', $user->id)->first();
        $customer->name = $request->name;
        $customer->mobile = $request->mobile;
        $customer->image = $filename;
        $customer->email = $request->email;
        $customer->district_id = $request->district_id;
        $customer->save();

        //$customer = Customer::where('id',$user->id)->where('customer_type','harvester')->with('customer_chassis','District')->first();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully updated',
            'user' => new CustomerInfoResource($customer),
        ], 200);
    }

    public function uploadFile($file) {
        $image = md5(uniqid(rand(), true)) . '.' .str_replace(" ", "-", $file->getClientOriginalName());
        $destinationPath = public_path('/images/customer');
        $file->move($destinationPath,$image);
        return $image;
    }

    public function sendOtpForForgotPassword(Request $request){
        $mobile = $request->mobile;
        $exist_customer = Customer::where('mobile', $mobile)->where('customer_type', 'harvester')->exists();
        if ($exist_customer){
            try {
                $six_digit_random_number = random_int(100000, 999999);
                $smscontent = 'Otp Code - ' . $six_digit_random_number;

                $this->sendsms($ip = '192.168.100.213', $userid = 'motors', $password = 'Asdf1234', $smstext = urlencode($smscontent), $receipient = urlencode($mobile));

                $otp = new Otp();
                $otp->otp_code = $six_digit_random_number;
                $otp->mobile = $mobile;
                $otp->status = 0;
                $otp->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Successfully Otp Send'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 401,
                    'error' => $e
                ]);
            }
        }else{
            return response()->json([
                'status' => 'error',
                'error' => 'Mobile Number Not Found'
            ]);
        }
    }

    public function verifyOtpForForgotPassword(Request $request){
        $otp = $request->otp_code;
        $mobile = $request->mobile;

        $check_otp = Otp::where('mobile', $mobile)->where('otp_code', $otp)->where('status', 0)->orderBy('created_at', 'desc')->first();

        if ($check_otp) {
            $check_otp->status = 1;
            $check_otp->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Otp Match Successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Otp Not Match'
            ]);
        }
    }

    public function customerForgotPassword(Request $request){
        $this->validate($request, [
            'password' => 'required',
        ]);
        $mobile = $request->mobile;
        $password = $request->password;

        $customer = Customer::where('mobile', $mobile)->where('customer_type', 'harvester')->first();

        if ($customer){
            $customer->password = bcrypt($password);
            $customer->save();
            return response()->json([
                'status'=>'success',
                'message' => 'Password Change successfully :)'
            ]);
        }else{
            return response()->json([
                'status'=>'error',
                'message' => 'Something Went Wrong'
            ]);
        }
    }

    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'previous_password' => 'required',
            'password' => 'required',
        ]);

        $current_password = Auth::User()->password;

        $customer = JWTAuth::parseToken()->authenticate();

        if (Hash::check($request->previous_password, $current_password)) {
            if (Hash::check($request->password, $current_password)) {
                return response()->json(['message' => 'Previous Password and Old Password Same']);
            } else {
                $customer = Customer::where('id', $customer->id)->first();
                $customer->password = bcrypt($request->password);

                $customer->save();
                return response()->json([
                    'status'=>'success',
                    'message' => 'Password Change successfully :)'
                ]);
            }

        } else {
            return response()->json([
                'status'=>'error',
                'message' => 'Previous Password Not Correct :)'
            ]);
        }
    }

    public function me(){
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json($user);
    }

    public function logout(): \Illuminate\Http\JsonResponse{
        try {
            $this->guard()->logout();
        } catch (\Exception $exception) {

        }
        return response()->json([
            'status' => 200,
            'message' => 'Successfully logged out'
        ], 200);
    }

    public function sendsms($ip, $userid, $password, $smstext, $receipient){
        $smsUrl = "http://{$ip}/httpapi/sendsms?userId={$userid}&password={$password}&smsText=" . $smstext . "&commaSeperatedReceiverNumbers=" . $receipient;
        $response = file_get_contents($smsUrl);
        return json_decode($response);
    }



}

<?php

namespace App\Http\Controllers;

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

        if ($token = JWTAuth::attempt(['mobile' => $request->mobile, 'password' => $request->password])) {
            $user = Auth::user();
            $customer = Customer::where('id',$user->id)->with('customer_chassis')->first();
            //$chassis = CustomerChassis::where('customer_id',$user->id)->select('id','customer_id','chassis_no','model')->get();
            return response()->json([
                'status' => 'success',
                'token' => $token,
                'user' => $customer,
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
        $chassis = StockBatch::where('BatchNo', $chassis)->with('product')->first();
        if ($chassis) {
            return response()->json([
                'status' => 'success',
                'chassis' => $chassis->BatchNo,
                'model' => isset($chassis->product) ? $chassis->product->Model : '',
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

            $customer = new Customer();
            $customer->name = $request->name;
            $customer->mobile = $request->mobile;
            $customer->email = $request->email;
            $customer->district_id = $request->district_id;
            $customer->password = bcrypt($request->password);
            $customer->customer_type = 'harvester';

            if ($customer->save()) {
                if ($token = JWTAuth::attempt(['mobile' => $request->mobile, 'password' => $request->password])) {
                    $user = Auth::user();
                    $customer_chassis = new CustomerChassis();
                    $customer_chassis->customer_id = $customer->id;
                    $customer_chassis->model = $model;
                    $customer_chassis->chassis_no = $chassis;
                    $customer_chassis->save();

                    DB::commit();
                    return response()->json([
                        'status' => "success",
                        'token' => $token,
                        'user' => $user,
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

    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
//            'Division' => 'required',
//            'District' => 'required',
//            'Upazilla' => 'required',
        ]);

        $customer = Customer::where('id', $request->id)->first();
        $customer->name = $request->name;
        $customer->mobile = $request->mobile;
        $customer->email = $request->email;
        $customer->district_id = $request->district_id;
        $customer->save();

        return response()->json([
            'status' => 200,
            'message' => 'success'
        ], 200);
    }

    public function changePassword(Request $request)
    {

        $this->validate($request, [
            'previous_password' => 'required|min:6',
            'password' => 'required|min:6|confirmed',
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
                return response()->json(['message' => 'Password Change successfully :)']);
            }

        } else {
            return response()->json(['message' => 'Previous Password Not Correct :)']);
        }
    }

    public function me()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json($user);
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        try {
            $this->guard()->logout();
        } catch (\Exception $exception) {

        }
        return response()->json([
            'status' => 200,
            'message' => 'Successfully logged out'
        ], 200);
    }

    public function sendsms($ip, $userid, $password, $smstext, $receipient)
    {
        $smsUrl = "http://{$ip}/httpapi/sendsms?userId={$userid}&password={$password}&smsText=" . $smstext . "&commaSeperatedReceiverNumbers=" . $receipient;
        //echo $smsUrl; exit();
        $response = file_get_contents($smsUrl);
        //print_r($response); exit();
        return json_decode($response);
    }


}

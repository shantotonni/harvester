<?php

namespace App\Http\Controllers;

use App\Http\Resources\Customer\CustomerInfoCollection;
use App\Http\Resources\Customer\CustomerInfoResource;
use App\Models\Customer;
use App\Models\CustomerChassis;
use App\Models\JobCard;
use App\Models\Otp;
use App\Models\ProductModel;
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

    public function login(Request $request){
        $this->validate($request, [
            'mobile'    => 'required',
            'password'  => 'required',
        ]);

        if ($token = JWTAuth::attempt(['mobile' => $request->mobile, 'password' => $request->password,'customer_type'=>'harvester'])) {
            $customer = Customer::where('mobile',$request->mobile)->where('customer_type','harvester')
                ->with('customer_chassis','District','customer_chassis.mirror_customer')->first();
            $customer->device_token = $request->device_token;
            $customer->save();
            return response()->json([
                'status'    => 'success',
                'token'     => $token,
                'user'      => new CustomerInfoResource($customer),
            ], 200);
        }
        return response()->json([
            'message'   => 'আপনি ভুল মোবাইল/পাসওয়ার্ড দিয়েছেন।',
            'status'    => 'error'
        ], 200);
    }

    public function findChassisNumber(Request $request)
    {
        $chassis    = $request->chassis;
        $exiats     = CustomerChassis::where('chassis_no',$chassis)->exists();

        if ($exiats){
            return response()->json([
                'message'   => 'এই চেসিসটি ইতিমধ্যে নিবন্ধিত হয়েছে।',
                'status'    => 'success'
            ], 200);
        }

        $chassis        = StockBatch::where('BatchNo', $chassis)->with('product')->first();
        $productModel   = ProductModel::query()->where('product_id',4)->get();
        if ($chassis) {
            $productName = '';
            if ($chassis){
                if ($chassis->ProductCode == 'W051'){
                    $productName    = $productModel[4]->model_name_bn;
                    $modelId        = $productModel[4]->id;
                }elseif ($chassis->ProductCode == 'W098'){
                    $productName    = $productModel[5]->model_name_bn;
                    $modelId        = $productModel[5]->id;
                }elseif ($chassis->ProductCode == 'W112'){
                    $productName    = $productModel[0]->model_name_bn;
                    $modelId        = $productModel[0]->id;
                }elseif ($chassis->ProductCode == 'W127'){
                    $productName    = $productModel[0]->model_name_bn;
                    $modelId        = $productModel[0]->id;
                }elseif ($chassis->ProductCode == 'W133'){
                    $productName    = $productModel[4]->model_name_bn;
                    $modelId        = $productModel[4]->id;
                }elseif ($chassis->ProductCode == 'W146'){
                    $productName    = $productModel[3]->model_name_bn;
                    $modelId        = $productModel[3]->id;
                }elseif ($chassis->ProductCode == 'W148'){
                    $productName    = $productModel[1]->model_name_bn;
                    $modelId        = $productModel[1]->id;
                }elseif ($chassis->ProductCode == 'W150'){
                    $productName    = $productModel[0]->model_name_bn;
                    $modelId        = $productModel[0]->id;
                }elseif ($chassis->ProductCode == 'W151'){
                    $productName    = $productModel[0]->model_name_bn;
                    $modelId        = $productModel[0]->id;
                }else{
                    $productName    = $productModel[5]->model_name_bn;
                    $modelId        = $productModel[5]->id;
                }
            }
            return response()->json([
                'status'    => 'success',
                'chassis'   => $chassis->BatchNo,
                'model'     => $productName,
                'modelId'   => $modelId,
            ]);
        } else {
            return response()->json([
                'status'    => 'error',
                'message'   => 'এই চেসিস নম্বরটি আমাদের সিস্টেমে পাওয়া যায় নি।',
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
                'status'    => 200,
                'message'   => 'এই মোবাইল নম্বরটি ইতিমধ্যে নিবন্ধিত।'
            ], 200);
        } else {
            try {
                $four_digit_random_number   = mt_rand(1000,9999);
                $smscontent                 = 'Otp Code - ' . $four_digit_random_number;
                $mobile                     = $request->mobile;

                $to                 = $mobile;
                $sId                = '8809617615000';
                $applicationName    = 'Harvester';
                $moduleName         = 'Registration';
                $otherInfo          = '';
                $userId             = '2845';
                $vendor             = 'smsq';
                $message            = $smscontent;
                $this->sendSmsQ($to, $sId, $applicationName, $moduleName, $otherInfo, $userId, $vendor, $message);

                $otp            = new Otp();
                $otp->otp_code  = $four_digit_random_number;
                $otp->mobile    = $mobile;
                $otp->status    = 0;
                $otp->save();

                return response()->json([
                    'status'    => 'success',
                    'message'   => 'success'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status'    => 401,
                    'message'     => $e
                ]);
            }
        }
    }

    public function checkOtp(Request $request)
    {
        $otp        = $request->otp_code;
        $mobile     = $request->mobile;
        $check_otp  = Otp::where('mobile', $mobile)->where('otp_code', $otp)->where('status', 0)->orderBy('created_at', 'desc')->first();

        if ($check_otp) {
            $check_otp->status = 1;
            $check_otp->save();
            return response()->json([
                'status'    => 'success',
                'message'   => 'সফলভাবে ওটিপি ম্যাচ করেছে'
            ]);
        } else {
            return response()->json([
                'status'    => 'error',
                'message'   => 'ওটিপি ম্যাচ করেনি'
            ]);
        }
    }

    public function registration(Request $request){
        $chassis    = $request->chassis;
        $model      = $request->model;
        $model_id   = $request->model_id;
        $this->validate($request, [
            'name'      => 'required',
            'mobile'    => 'required',
            'chassis'   => 'required',
            'password'  => 'required',
        ]);

        DB::beginTransaction();

        try {
            $sdmsCustomerInfo = DB::connection('MotorBrInvoiceMirror')->table('InvoiceDetailsBatch')
                ->select('InvoiceDetailsBatch.Invoiceno','InvoiceDetailsBatch.BatchNo as ChassisNo','Invoice.CustomerCode','Customer.Address1','Invoice.InvoiceDate')
                ->join('Invoice','Invoice.InvoiceNo','=','InvoiceDetailsBatch.Invoiceno')
                ->join('Customer','Customer.CustomerCode','=','Invoice.CustomerCode')
                ->where('BatchNo',$request->chassis)->first();

            $CustomerCode       = '';
            $Address            = '';
            $DateOfPurchase     = null;

            if (empty($sdmsCustomerInfo)){
                return response()->json([
                    'status'    => "error",
                    'message'   => 'চেসিস নম্বরটি আমাদের সিস্টেমে পাওয়া যায়নি'
                ], 200);
            }else{
                $CustomerCode   = $sdmsCustomerInfo->CustomerCode;
                $Address        = $sdmsCustomerInfo->Address1;
                $DateOfPurchase = $sdmsCustomerInfo->InvoiceDate;
            }


            $exist_customer = Customer::where('mobile', $request->mobile)->where('customer_type', 'harvester')->exists();
            if ($exist_customer) {
                return response()->json([
                    'status'    => 200,
                    'message'   => 'এই মোবাইল নম্বরটি ইতিমধ্যে নিবন্ধিত।'
                ], 200);
            }

            $getLastServiceHour = JobCard::query()->where('chassis_number',$request->chassis)
                ->where('is_approved',1)
                ->orderBy('created_at','desc')->first();

            $customer = new Customer();
            $customer->code                 = $CustomerCode ? $CustomerCode : '';
            $customer->name                 = $request->name;
            $customer->mobile               = $request->mobile;
            $customer->email                = $request->email;
            $customer->area_id              = $request->area_id;
            $customer->district_id          = $request->district_id;
            $customer->upazilla_id          = $request->upazilla_id;
            $customer->password             = bcrypt($request->password);
            $customer->customer_type        = 'harvester';
            $customer->address              = $Address;
            $customer->image                = '';
            $customer->chassis_image        = '';
            $customer->date_of_purchase     = $DateOfPurchase;
            $customer->service_hour         = $getLastServiceHour ? $getLastServiceHour->hour : 0;
            $customer->last_service_date    = $getLastServiceHour ? $getLastServiceHour->service_date : null;

            if ($customer->save()) {
                if ($token = JWTAuth::attempt(['mobile' => $request->mobile, 'password' => $request->password,'customer_type'=>'harvester'])) {
                    $existingChassisCheck = CustomerChassis::query()->where('chassis_no',$chassis)->exists();
                    if ($existingChassisCheck){
                        return response()->json([
                            'status'    => "success",
                            'message'   => 'এই চেসিসটি ইতিমধ্যে নিবন্ধিত হয়েছে।',
                        ], 200);
                    }

                    $customer_chassis                       = new CustomerChassis();
                    $customer_chassis->customer_id          = $customer->id;
                    $customer_chassis->customer_code        = $CustomerCode ? $CustomerCode : '';
                    $customer_chassis->model_id             = $model_id;
                    $customer_chassis->model                = $model;
                    $customer_chassis->chassis_no           = $chassis;
                    $customer_chassis->date_of_purchase     = $DateOfPurchase;
                    $customer_chassis->service_hour         = $getLastServiceHour ? $getLastServiceHour->hour : 0;
                    $customer_chassis->last_service_date    = $getLastServiceHour ? $getLastServiceHour->service_date : null;
                    $customer_chassis->save();

                    $customer = Customer::where('mobile',$request->mobile)->where('customer_type','harvester')
                        ->with('customer_chassis','customer_chassis.mirror_customer','upazilla')->first();

                    DB::commit();
                    return response()->json([
                        'status'    => "success",
                        'token'     => $token,
                        'user'      => new CustomerInfoResource($customer),
                    ], 200);
                } else {
                    return response()->json([
                        'status'    => "error",
                        'message'   => 'Something went wrong!',
                    ], 200);
                }
            }

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'    => "error",
                'message'   => $e->getMessage()
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
                $four_digit_random_number = mt_rand(1000,9999);
                $smscontent = 'Otp Code - ' . $four_digit_random_number;
                $mobile = $request->mobile;

                $to = $mobile;
                $sId = '8809617615000';
                $applicationName = 'Harvester';
                $moduleName = 'Forgot Password';
                $otherInfo = '';
                $userId = '2845';
                $vendor = 'smsq';
                $message = $smscontent;
                $this->sendSmsQ($to, $sId, $applicationName, $moduleName, $otherInfo, $userId, $vendor, $message);

                $otp = new Otp();
                $otp->otp_code = $four_digit_random_number;
                $otp->mobile = $mobile;
                $otp->status = 0;
                $otp->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'সফলভাবে ওটিপি পাঠানো হয়েছে'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 401,
                    'message' => $e
                ]);
            }
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'মোবাইল নম্বর পাওয়া যায়নি'
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

    public static function sendSmsQ($to, $sId, $applicationName, $moduleName, $otherInfo, $userId, $vendor, $message)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.102.10/apps/api/send-sms/sms-master',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'To='.$to.'&SID='.$sId.'&ApplicationName='.urlencode($applicationName).'&ModuleName='.urlencode($moduleName).'&OtherInfo='.urlencode($otherInfo).'&userID='.$userId.'&Message='.$message.'&SmsVendor='.$vendor,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ));
        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function sendsms($ip, $userid, $password, $smstext, $receipient){
        $smsUrl = "http://{$ip}/httpapi/sendsms?userId={$userid}&password={$password}&smsText=" . $smstext . "&commaSeperatedReceiverNumbers=" . $receipient;
        $response = file_get_contents($smsUrl);
        return json_decode($response);
    }
    public function notifyUser(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $user = Customer::where('id', $user->id)->first();
        $notification_id = $user->notification_id;
        $title = $request->title;
        $message = $request->body;
        $id = $user->id;
        $type = "basic";

        $accesstoken = env('FCM_KEY');

        $URL = 'https://fcm.googleapis.com/fcm/send';


        $post_data = '{
            "to" : "' . $notification_id . '",
            "data" : {
              "body" : "",
              "title" : "' . $title . '",
              "type" : "' . $type . '",
              "id" : "' . $id . '",
              "message" : "' . $message . '",
            },
            "notification" : {
                 "body" : "' . $message . '",
                 "title" : "' . $title . '",
                  "type" : "' . $type . '",
                 "id" : "' . $id . '",
                 "message" : "' . $message . '",
                "icon" : "new",
                "sound" : "default"
                },

          }';
//         print_r($post_data);die;

        $crl = curl_init();

        $headr = array();
        $headr[] = 'Content-type: application/json';
        $headr[] = 'Authorization: ' . $accesstoken;
        curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($crl, CURLOPT_URL, $URL);
        curl_setopt($crl, CURLOPT_HTTPHEADER, $headr);

        curl_setopt($crl, CURLOPT_POST, true);
        curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);

        $rest = curl_exec($crl);

        if ($rest === false) {
            // throw new Exception('Curl error: ' . curl_error($crl));
            //print_r('Curl error: ' . curl_error($crl));
            $result_noti = 0;
        } else {

            $result_noti = 1;
        }

//        curl_close($crl);
//        print_r($result_noti);die;

        if($result_noti == 1){

            return response()->json([
                'status' => 'success',
                'message'=>'notification sent'
            ]);


        }else{

            return response()->json([
                'status' => 'success',
                'message'=>'notification cannot be sent'
            ]);
        }


    }


}

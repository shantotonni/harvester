<?php

namespace App\Http\Controllers;

use App\Http\Resources\PushNotificationCollection;
use App\Models\PushNotification;
use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;

class PushNotificationController extends Controller
{
    public function index(){
        $notification = PushNotification::query()->where('product_type','harvester')->paginate(10);
        return new PushNotificationCollection($notification);
    }

    public function store(Request $request){
        $request->validate([
            'title' => 'required',
            'message' => 'required',
            'image' => 'required',
        ]);

        try {
            if ($request->has('image')) {
                $image = $request->image;
                $fileName = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->save(public_path('images/notification/') . $fileName);
            } else {
                $fileName = 'not_found.jpg';
            }

            $pushNotification               = new PushNotification();
            $pushNotification->title        = $request->title;
            $pushNotification->message      = $request->message;
            $pushNotification->image        = $fileName;
            $pushNotification->product_type = 'harvester';
            $pushNotification->save();

            $token = $this->getAccessToken();

            // prep the bundle
            $msg = array(
                'title'     => $request->title,
                'body'   => $request->message,
                'image'     => URL::to('/').'/images/notification/'.$fileName,
                'action' => "promotional_push"
            );

            $fields = array(
                'topic'  => 'harvester_promotion',
                'data' => $msg
            );

            $message = [
                'message' => $fields
            ];

            $headers = array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/658116520484/messages:send');
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $message ) );
            $result = curl_exec($ch);
            curl_close( $ch );

            return response()->json([
                'status'    => 'success',
                'message' => 'Notification successfully sent.',
                'result' => $result
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'status'    => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function getAllNotification(){
        $notification = PushNotification::query()->where('product_type','harvester')->get();
        return response()->json([
           'notification' => $notification
        ]);
    }

    private function getAccessToken(){
        $credentialsPath = public_path('notification_json/'.'aci-applications-firebase-adminsdk-a3zvh-1579f9c032.json'); // Path to your service account file
        $client = new Google_Client(['verify' => false]);
        $client->setAuthConfig($credentialsPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->useApplicationDefaultCredentials();
        $token = $client->fetchAccessTokenWithAssertion();
        return $token['access_token'];
    }

}

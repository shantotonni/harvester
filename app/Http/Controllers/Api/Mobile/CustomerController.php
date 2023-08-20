<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceRequest\ServiceRequestCollection;
use App\Http\Resources\WarrantyPartsCollection;
use App\Models\Customer;
use App\Models\PartsDetail;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\WarrantyClaimInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerController extends Controller
{
    function __construct()
    {
        Config::set('jwt.user', Customer::class);
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => Customer::class,
        ]]);
    }

    public function warrantyParts(Request $request){
        $chassis = $request->ChassisNumber;
        //$customer_warranty_parts = WarrantyClaimInfo::where('ChassisNumber',$request->ChassisNumber)->where('Status','Approved')->get();
        $parts = PartsDetail::with('warranty_claim')->whereHas('warranty_claim', function($query) use ($chassis){
            $query->where('ChassisNumber', $chassis);
            $query->where('Status', 'Approved');
        })->get();
        return new WarrantyPartsCollection($parts);
    }
}

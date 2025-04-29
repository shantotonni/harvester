<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SmartAssist\SmartAssistStoreRequest;
use App\Http\Requests\SmartAssist\SmartAssistUpdateRequest;
use App\Http\Resources\SmartAssist\SmartAssistCollection;
use App\Models\SmartAssist;
use Dotenv\Validator;
use http\Env\Request;
use phpDocumentor\Reflection\Types\Collection;

class SmartAssistController extends Controller
{
    public function index(){
        $smart_assists = SmartAssist::paginate(15);
        return new SmartAssistCollection($smart_assists);
    }

    public function store(SmartAssistStoreRequest $request){
        $existCheck = SmartAssist::query()->where('chassis_no',$request->chassis_no)->exists();
        if ($existCheck){
            return response()->json([
                'status'=>'success',
                'message'=>'Chassis Already Exist'
            ],200);
        }

        $smart_assist = new SmartAssist();
        $smart_assist->chassis_no = $request->chassis_no;
        $smart_assist->SARID = $request->sarid;
        $smart_assist->password = $request->password;
        $smart_assist->save();
        return response()->json([
            'status'=>'success',
            'message'=>'Successfully stored'
        ],200);
    }

    public function update(SmartAssistUpdateRequest $request, $chassis_no ){
        $smart_assist = SmartAssist::where('chassis_no', $chassis_no)->first();
        $smart_assist->SARID = $request->sarid;
        $smart_assist->password = $request->password;
        $smart_assist->save();
        return response()->json([
            'status'=>'success',
            'message'=>'Successfully updated',200]);
    }

    public function destroy($chassis_no){
        SmartAssist::where('chassis_no', $chassis_no)->delete();
        return response()->json([
            'status'=>'success',
            'message' => ' Deleted Successfully', 200]);
    }

    public function search($query)
    {
        return new SmartAssistCollection(SmartAssist::Where('chassis_no', 'like', "%$query%")->paginate(10));
    }
}

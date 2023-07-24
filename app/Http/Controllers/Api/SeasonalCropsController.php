<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeasonalCrops\SeasonalCropsStoreRequest;
use App\Http\Resources\SeasonalCrops\SeasonalCropsCollection;
use App\Models\SeasonalCrops;
use Illuminate\Http\Request;

class SeasonalCropsController extends Controller
{
    public function index(Request $request)
    {

        $district_id =$request->district_id;
        $seasonal_crops = SeasonalCrops::query()->with('District');
        if (!empty($district_id)){
            $seasonal_crops=$seasonal_crops->where('district_id',$district_id);

        }
        $seasonal_crops = $seasonal_crops->paginate();

        return new SeasonalCropsCollection($seasonal_crops);
    }

    public function store(SeasonalCropsStoreRequest $request)
    {
        $seasonal_crop = new SeasonalCrops();
        $seasonal_crop->district_id = $request->district_id;
        $seasonal_crop->seasonal_crops_id = $request->seasonal_crops_id;
        $seasonal_crop->date_from = $request->date_from;
        $seasonal_crop->date_to = $request->date_to;
        $seasonal_crop->save();

        return response()->json(['message' => 'Successfully Created Seasonal Crops', 200]);
    }

    public function update(Request $request, $id)
    {
        $seasonal_crop = SeasonalCrops::where('id', $id)->first();
        $seasonal_crop->district_id = $request->district_id;
        $seasonal_crop->seasonal_crops_id = $request->seasonal_crops_id;
        $seasonal_crop->date_from = $request->date_from;
        $seasonal_crop->date_to = $request->date_to;
        $seasonal_crop->save();
        return response()->json(['message' => 'Successfully Updated Seasonal Crops', 200]);


    }

    public function destroy($id)
    {
        SeasonalCrops::where('id', $id)->delete();
        return response()->json(['message' => 'Seasonal Crop Deleted Successfully']);
    }
public function search($query){
        return new SeasonalCropsCollection(SeasonalCrops::where('seasonal_crops.name','like', "%$query%")
        ->join('seasonal_crops','seasonal_crops.id','district_wise_seasonal_crops.seasonal_crops_id')
            ->paginate(10));
}

}

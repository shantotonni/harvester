<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\HarvesterCost\HarvesterCostStoreRequest;
use App\Http\Resources\HarvestingCostCollection;
use App\Models\HarvestingCost;
use Illuminate\Http\Request;

class HarvestingCostController extends Controller
{
    public function index(){

        $harvesting_cost = HarvestingCost::paginate(15);
        return new HarvestingCostCollection($harvesting_cost);
    }

    public function store(HarvesterCostStoreRequest $request)
    {
        $harvesting_cost = new HarvestingCost();
        $harvesting_cost->cost_title = $request->cost_title;
        $harvesting_cost->cost_measurement = $request->cost_measurement;
        $harvesting_cost->price = $request->price;

        $harvesting_cost->save();
        return response()->json(['message' => 'Harvesting Cost created Successfully', 200]);
    }


    public function update(HarvesterCostStoreRequest $request, $id)
    {
        $harvesting_cost = HarvestingCost::where('id', $id)->first();
        $harvesting_cost->cost_title = $request->cost_title;
        $harvesting_cost->cost_measurement = $request->cost_measurement;
        $harvesting_cost->price = $request->price;
        $harvesting_cost->save();
        return response()->json(['message' => 'Harvesting Cost updated Successfully', 200]);
    }

    public function destroy($id)
    {
        HarvestingCost::where('id', $id)->delete();
        return response()->json(['message' => 'Harvesting Cost Deleted Successfully', 200]);
    }

    public function search($query)
    {
        return new HarvestingCostCollection(HarvestingCost::where('cost_title', 'like', "%$query%")->paginate(10));
    }
}

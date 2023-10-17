<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FuelPump\FuelPumpStoreRequest;
use App\Http\Resources\FuelPump\FuelPumpCollection;
use App\Models\FuelPump;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class FuelPumpController extends Controller
{
    public function index(Request $request)
    {

        $district_id =$request->district_id;
        $fuels = FuelPump::query()->with('District');
        if (!empty($district_id)){
            $fuels=$fuels->where('district_id',$district_id);

        }
        $fuels = $fuels->paginate();

        return new FuelPumpCollection($fuels);
    }

    public function store(FuelPumpStoreRequest $request)
    {
        $this->validate($request, [
            'image' => 'required|min:jpeg,jpg,png,svg'
        ]);
        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid().time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/fuel/').$name);
        } else {
            $name = 'not_found.jpg';
        }

        $fuel = new FuelPump();
        $fuel->owner_name = $request->owner_name;
        $fuel->district_id = $request->district_id;
        $fuel->address = $request->address;
        $fuel->fuel_pump_name = $request->fuel_pump_name;
        $fuel->mobile = $request->mobile;
        $fuel->lat = $request->lat;
        $fuel->lon = $request->lon;
        $fuel->image =$name;
        $fuel->save();
        return response()->json(['message' => 'Showroom created Successfully', 200]);
    }


    public function update(Request $request, $id)
    {
        $fuel = FuelPump::where('fuel_pump_id', $id)->first();
        $this->validate($request, [
            'image' => 'required|min:jpeg,jpg,png,svg'
        ]);

        $image = $request->image;
        if ($image != $fuel->image) {
            if ($request->has('image')) {
                $destinationPath = 'images/fuel/';
                if ($fuel->image){
                    $file_old = public_path('/').$destinationPath.$fuel->image;
                    if (file_exists($file_old)){
                        unlink($file_old);
                    }
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->save(public_path('images/fuel/') . $name);
            } else {
                $name = $fuel->image;
            }

        }
        else{
            $name = $fuel->image;
        }
        $fuel->owner_name = $request->owner_name;
        $fuel->district_id = $request->district_id;;
        $fuel->address = $request->address;
        $fuel->fuel_pump_name = $request->fuel_pump_name;
        $fuel->mobile = $request->mobile;
        $fuel->lat = $request->lat;
        $fuel->lon = $request->lon;
        $fuel->image =$name;
        $fuel->save();
        return response()->json(['message' => 'Fuel Pump updated Successfully', 200]);
    }

    public function destroy($id)
    {
        FuelPump::where('fuel_pump_id', $id)->delete();
        return response()->json(['message' => 'Fuel Pump Deleted Successfully', 200]);
    }

    public function search($query)
    {
        return new FuelPumpCollection(FuelPump::Where('fuel_pump_name', 'like', "%$query%")->latest()->paginate(10));
    }
}

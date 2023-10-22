<?php

namespace App\Http\Requests\FuelPump;

use Illuminate\Foundation\Http\FormRequest;

class FuelPumpStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'owner_name'=>'required',
            'district_id'=>'required',
            'fuel_pump_name'=>'required',
            'address'=>'required',
            'mobile'=>'required',
            //'lat'=>'required',
            //'lon'=>'required',
        ];
    }
}

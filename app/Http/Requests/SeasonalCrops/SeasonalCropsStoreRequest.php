<?php

namespace App\Http\Requests\SeasonalCrops;

use Illuminate\Foundation\Http\FormRequest;

class SeasonalCropsStoreRequest extends FormRequest
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
            'district_id'=>'required',
            'seasonal_crops_id'=>'required',
            'date_to'=>'required',
            'date_from'=>'required'
        ];
    }
}

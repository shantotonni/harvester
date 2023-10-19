<?php

namespace App\Http\Requests\HarvesterCost;

use Illuminate\Foundation\Http\FormRequest;

class HarvesterCostStoreRequest extends FormRequest
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
            'cost_title'=>'required',
            'cost_measurement'=>'required',
            'price'=>'required',
        ];
    }
}

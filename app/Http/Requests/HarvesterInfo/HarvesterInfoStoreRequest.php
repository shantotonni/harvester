<?php

namespace App\Http\Requests\HarvesterInfo;

use Illuminate\Foundation\Http\FormRequest;

class HarvesterInfoStoreRequest extends FormRequest
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
            'details'=>'required',
            'horse_power'=>'required',
            'product_id'=>'required',
            'model_id'=>'required',
            'image' => 'required|min:jpeg,jpg,png,svg'

        ];
    }
}

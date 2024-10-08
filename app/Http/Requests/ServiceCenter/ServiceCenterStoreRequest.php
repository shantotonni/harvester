<?php

namespace App\Http\Requests\ServiceCenter;

use Illuminate\Foundation\Http\FormRequest;

class ServiceCenterStoreRequest extends FormRequest
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
            'address'=>'required',
            'service_center_name'=>'required',
            'responsible_person'=>'required',
            'mobile'=>'required',
            'lat'=>'required',
            'lon'=>'required',
            'area_id'=>'required',
            ];
    }
}

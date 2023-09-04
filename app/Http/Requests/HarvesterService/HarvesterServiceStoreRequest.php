<?php

namespace App\Http\Requests\HarvesterService;

use Illuminate\Foundation\Http\FormRequest;

class HarvesterServiceStoreRequest extends FormRequest
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
           'model_id'=>'required',
            'servicing_type_id'=>'required',
            'from_hr'=>'required',
            'to_hr'=>'required',
            'servicing_status'=>'required',
        ];
    }
}

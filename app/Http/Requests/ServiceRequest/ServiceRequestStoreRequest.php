<?php

namespace App\Http\Requests\ServiceRequest;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequestStoreRequest extends FormRequest
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
            'customer_mobile'=>'required'
        ];
    }
}

<?php

namespace App\Http\Requests\HarvesterParts;

use Illuminate\Foundation\Http\FormRequest;

class HarvesterPartsStoreRequest extends FormRequest
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
            'ProductCode' => 'required',
            'custom_name' => 'required',
        ];
    }
}

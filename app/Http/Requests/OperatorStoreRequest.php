<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class OperatorStoreRequest extends Request
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
        'name' => 'required|string|unique:operators,name,null,id,country_id,'.request()->get('country_id'),
        'country_id' => 'required|exists:countries,id',
        'image' => '' ,
        'rbt_ussd_code' => '' ,
        'rbt_sms_code' => ''
       ];
    }
}

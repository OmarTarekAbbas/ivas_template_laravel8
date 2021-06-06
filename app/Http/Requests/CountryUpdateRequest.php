<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CountryUpdateRequest extends Request
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
            "title" => 'required|string|unique:countries,title,'.$this->segment(2),
            'country_id' => 'required|exsists:countries,id'
       ];
    }
}
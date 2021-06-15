<?php

namespace App\Http\Requests;

use App\Constants\ContentTypes;
use App\Http\Requests\Request;

class ContentRequest extends Request
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
        $rules =  [
            'title' => 'required|array',
            'title.*' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'content_type_id' => 'required|exists:content_types,id',
            'image_preview' => '',
            'patch_number' => '',
            'path' => ''
        ];

        if(request()->get('content_type_id') == ContentTypes::IMAGE && $this->method()=='POST') {
            $rules['path'] = 'required|mimes:png,jpeg,jpg';
        }

        if(request()->get('content_type_id') == ContentTypes::VIDEO && $this->method()=='POST') {
            $rules['path'] = 'required|mimes:mp4,flv,3gp';
        }

        if(request()->get('content_type_id') == ContentTypes::AUDIO && $this->method()=='POST') {
            $rules['path'] = 'required|mimes:mp3,webm,wav';
        }

        if(request()->get('content_type_id') == ContentTypes::YOUTUBVIDEO && $this->method()=='POST') {
            $rules['path'] = 'required|url';
        }

        if((request()->get('content_type_id') == ContentTypes::ADVANCED_TEXT || request()->get('content_type_id') == ContentTypes::NORMAL_TEXT) && $this->method()=='POST') {
            $rules['path.*'] = 'required';
        }

        return $rules;
    }
}

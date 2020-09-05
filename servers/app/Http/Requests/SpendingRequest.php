<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpendingRequest extends FormRequest
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
            'name' => 'required',
            'total' => 'required',
            'information' => 'required',
            // 'img' => 'required|isImage'
        ];
    }

    public function messages()
    {
        return [
            'img.is_image' => 'Gambar yang di upload hanya boleh JPG dan PNG'
        ];
    }
}

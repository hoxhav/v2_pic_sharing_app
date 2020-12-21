<?php

namespace App\Http\Requests\image;

use Illuminate\Foundation\Http\FormRequest;

class SearchImageRequest extends FormRequest
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
            'name_phrase' => 'required|string|max:255',
        ];
    }
}

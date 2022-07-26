<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGalleryRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'sometimes|string|min:2|max:255',
            'description' => 'sometimes|nullable|string|max:1000',
            'images' => 'array|min:1',
            'images.*.url' => ['sometimes', 'regex:/^(https?:)?\/\/?[^\'"<>]+?\.(jpg|jpeg|png)(.*)?$/']
        ];
    }
}

<?php

namespace App\Http\Requests\Api\History;

use Illuminate\Foundation\Http\FormRequest;

class CreateHistory extends FormRequest
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
            'name' => ['required', 'max:255'],
            'author' => ['required', 'max:255'],
            'inPlaylist' => ['required', 'boolean'],
        ];
    }
}

<?php

namespace App\Http\Requests\Api\Song;

use App\Models\Song;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSong extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $song = Song::find($this->id);

        if (!$song) {
            abort(404);
        }

        return $song->user_id == $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required','max:255'],
        ];
    }
}

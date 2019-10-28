<?php

namespace App\Http\Requests\Api\Playlist;

use App\Models\Playlist;
use Illuminate\Foundation\Http\FormRequest;

class DeletePlaylist extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $playlist = Playlist::find($this->id);

        if (!$playlist) {
            abort(404);
        }

        return $playlist->user_id == $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}

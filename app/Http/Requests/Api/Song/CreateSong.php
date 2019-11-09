<?php

namespace App\Http\Requests\Api\Song;

use App\Models\Playlist;
use Illuminate\Foundation\Http\FormRequest;

class CreateSong extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $playlist = Playlist::find($this->playlist_id);

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
            'name' => ['max:255'],
            'url' => ['max:255'],
            'author_id' => ['exists:authors,id'],
            'genre_id' => ['exists:genres,id'],
            'playlist_id' => ['required','exists:playlists,id'],
            'music.*.' => ['mimes:mp3'],
        ];
    }
}

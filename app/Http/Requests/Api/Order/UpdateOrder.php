<?php

namespace App\Http\Requests\Api\Order;

use App\Models\Playlist;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrder extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $playlist = Playlist::find($this->playlist_id);

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
            'playlist_id' => ['required'],
            'action' => ['required','max:255'],
            //'id' => ['required','exists:orders,pos_id'],
        ];
    }
}

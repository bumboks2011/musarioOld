<?php

namespace App\Http\Requests\Api\Order;

use App\Models\Order;
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
        $order = Order::find($this->id);
        if (!$order) {
            abort(404);
        }
        return $order->playlist->user_id == $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'next' => ['required','numeric'],
        ];
    }
}

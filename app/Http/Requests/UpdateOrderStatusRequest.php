<?php

namespace App\Http\Requests;

use App\Enums\Order\OrderStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'order_id' => ['required', 'numeric', 'exists:orders,id'],
            'status' => ['required', 'string', 'in:shipped,on_way,delivered']
        ];
    }
}

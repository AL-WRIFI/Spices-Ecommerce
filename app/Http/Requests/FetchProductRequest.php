<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FetchProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'filters' => 'sometimes|array',
            'filters.sort_by' => 'sometimes|nullable|in:price,name,created_at',
            'filters.sort_order' => 'sometimes|nullable|in:asc,desc',
            'filters.min_price' => 'sometimes|nullable|numeric|min:0',
            'filters.max_price' => 'sometimes|nullable|numeric|min:0|gte:filters.min_price',
            'filters.sub_category_id' => 'sometimes|nullable|integer',
            'filters.category_id' => 'sometimes|nullable|integer',
            'filters.rating' => 'sometimes|nullable|integer',
            'filters.search' => 'sometimes|nullable|string|max:255',
        ];
    }
}

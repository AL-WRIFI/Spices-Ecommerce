<?php 

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Contracts\Database\Eloquent\Builder;


class FetchProductAction
{
    public function handle(array $filters = [])
    {
        $query = (new Product)->newQuery();
        return $this->applyFilters($filters, $query)->with(['subCategory', 'unit'])->latest()->simplePaginate(10);;
    }


    protected function applyFilters(array $filters = [], $query): Builder
    {
        if(isset($filters['category_id']) && $filters['category_id'] != null){
            $query->whereHas('category', function($q) use($filters){
                $q->where('category_id',$filters['category_id']);
            })->where('status', 1);
        }

        if(isset($filters['sub_category_id']) && $filters['sub_category_id'] != null){
            $query->whereHas('subCategory', function($q) use($filters){
                $q->where('sub_category_id',$filters['sub_category_id']);
            })->where('status', 1);
        }

        if (isset($filters['min_price']) && isset($filters['max_price'])) {
            $query->whereBetween('price', [$filters['min_price'], $filters['max_price']])->where('status', 1);
        }
        
        if(isset($filters['search'])) {
            $query = $this->recordSearch($filters['search'],$query)->where('status', 1);
        }

        // if (isset($filters['rating']) && $filters['rating'] != null) {
        //     $query->whereHas('reviews', function ($query) use ($filters) {
        //         $query->where('rating', $filters['rating']);
        //     });
        // }

        $query->orderBy($filters['sort_by'] ?? 'created_at', $filters['sort_order'] ?? 'asc');

        return $query;
    }

    protected function recordSearch($keyword , $query): Builder
    {
        $query->where(function ($q) use($keyword) {
            $q->where('name', 'like', '%' . $keyword . '%')
                ->orWhere('description', 'like', '%' . $keyword . '%');
        });

        return $query;
    }
}
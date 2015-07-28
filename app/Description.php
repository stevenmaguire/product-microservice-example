<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    /**
     * Get the product record associated with the description.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the description records associated with the given product id.
     */
    public function scopeOfProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Get the description records that match the given keyword.
     */
    public function scopeWithKeyword($query, $keyword)
    {
        return $query->where('body', 'like', '%'.$keyword.'%');
    }
}

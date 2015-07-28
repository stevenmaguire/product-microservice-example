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
}

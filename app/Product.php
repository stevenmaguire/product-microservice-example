<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * Get the description records associated with the product.
     */
    public function descriptions()
    {
        return $this->hasMany(Description::class);
    }
}

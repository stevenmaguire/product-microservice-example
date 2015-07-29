<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Get the description records associated with the product.
     */
    public function descriptions()
    {
        return $this->hasMany(Description::class);
    }

    /**
     * Get the product records that match the given keyword.
     */
    public function scopeWithKeyword($query, $keyword)
    {
        return $query->where('name', 'like', '%'.$keyword.'%');
    }
}

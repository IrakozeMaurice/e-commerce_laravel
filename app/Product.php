<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function presentPrice()
    {
        return money_format('$%i', $this->price / 100);
    }

    public function scopemightAlsoLike($query)
    {
        return $query->inRandomOrder()->take(4);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    //
    protected $fillable = ['name', 'sku', 'category_id', 'price', 'stock', 'min_stock'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMutation(): HasMany
    {
        return $this->hasMany(StockMutation::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'currency_id',
        'tax_cost',
        'manufacturing_cost',
    ];

    public function baseCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'distributor_id',
        'item_unit_id',
        'quantity',
        'price',
    ];

    public function distributor(): BelongsTo
    {
        return $this->belongsTo(Distributor::class);
    }

    public function itemUnit(): BelongsTo
    {
        return $this->belongsTo(ItemUnit::class);
    }

    protected static function boot()
    {
        parent::boot();
        // Order by name ASC
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('name', 'asc');
        });
    }
}

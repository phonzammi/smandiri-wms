<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemUnit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
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

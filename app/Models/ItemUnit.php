<?php

namespace App\Models;

use App\Enums\ItemUnitSize;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemUnit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'size'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'size' => ItemUnitSize::class
        ];
    }

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

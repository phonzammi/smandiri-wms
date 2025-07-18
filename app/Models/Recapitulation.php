<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recapitulation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'recapitulation_code',
        'date',
        'driver',
        'helper',
        'license_plate',
        'salesman_count'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
        ];
    }

    public function outgoing_transactions(): HasMany
    {
        return $this->hasMany(OutgoingTransaction::class);
    }

    public function outgoing_transaction_items(): HasManyThrough
    {
        return $this->hasManyThrough(OutgoingTransactionItem::class, OutgoingTransaction::class);
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'recapitulation_item')
            ->withPivot(['quantity']);
    }
}

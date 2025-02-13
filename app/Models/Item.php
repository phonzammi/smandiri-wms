<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
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

    public function incoming_items(): BelongsToMany
    {
        return $this->belongsToMany(IncomingTransaction::class, 'incoming_transaction_item')
            ->withPivot([
                'quantity',
                'total_price'
            ]);
    }
}

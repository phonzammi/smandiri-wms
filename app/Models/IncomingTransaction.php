<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class IncomingTransaction extends Model
{
    protected $fillable = [
        'invoice_number',
        'date',
        'courier',
        'distributor_id',
        'total_money',
    ];

    public function distributor(): BelongsTo
    {
        return $this->belongsTo(Distributor::class);
    }

    public function incoming_items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'incoming_transaction_item')
            ->withPivot([
                'quantity',
                'total_price'
            ]);
    }
}

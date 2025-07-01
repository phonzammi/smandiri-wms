<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutgoingTransactionItem extends Pivot
{
    protected $table = "outgoing_transaction_item";

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function outgoing_transaction()
    {
        return $this->belongsTo(OutgoingTransaction::class);
    }
}

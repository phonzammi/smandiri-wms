<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class IncomingTransaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_code',
        'date',
        'courier',
        'distributor',
        'total_quantity',
        'total_money',
        'is_completed'
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

    public function incoming_items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'incoming_transaction_item')
            ->withPivot([
                'total_quantity',
                'total_price'
            ])->orderBy('name');
    }
}

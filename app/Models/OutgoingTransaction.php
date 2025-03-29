<?php

namespace App\Models;

use App\Enums\OutgoingTransactionStatus;
use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutgoingTransaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_code',
        'date',
        'salesman_id',
        'store_id',
        'quantities',
        'prices',
        'discounts',
        'net_prices',
        'payment_method',
        'status',
        'cancel_reason'
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
            'payment_method' => PaymentMethod::class,
            'status' => OutgoingTransactionStatus::class
        ];
    }

    public function salesman(): BelongsTo
    {
        return $this->belongsTo(Salesman::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function outgoing_items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'outgoing_transaction_item')
            ->withPivot([
                'quantity',
                'unit_price',
                'discount',
                'total_price',
                'total_net_price'
            ]);
    }

    // public function isDraft(): bool
    // {
    //     return $this->status === OutgoingTransactionStatus::DRAFT;
    // }
}

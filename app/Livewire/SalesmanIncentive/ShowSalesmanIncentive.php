<?php

namespace App\Livewire\SalesmanIncentive;

use App\Enums\OutgoingTransactionStatus;
use App\Models\OutgoingTransaction;
use App\Models\Salesman;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

class ShowSalesmanIncentive extends Component
{
    public Salesman $salesman;
    public ?array $dateRangeFilter = null;

    #[Url(as: "table-filters")]
    public ?array $search = [];
    public int $total_transaction;

    public function mount()
    {
        if ($this->search) {
            $this->dateRangeFilter = $this->search['jarak-tanggal'];
        }
    }

    #[On('update-filter')]
    public function updateFilter(?array $dateRangeFilter = null)
    {
        $this->dateRangeFilter = $dateRangeFilter;
    }

    public function render()
    {
        $this->total_transaction = OutgoingTransaction::where('salesman_id', $this->salesman->id)
            ->where(function (Builder $query) {
                $query->where('status', OutgoingTransactionStatus::DELIVERING)
                    ->orWhere('status', OutgoingTransactionStatus::DELIVERED);
            })
            ->when($this->dateRangeFilter, function (Builder $query, array $dateRangeFilter) {
                $query->whereDate('outgoing_transactions.date', '>=', $dateRangeFilter['minDate'])
                    ->whereDate('outgoing_transactions.date', '<=', $dateRangeFilter['maxDate']);
            })->sum('net_prices');

        return view('livewire.salesman-incentive.show-salesman-incentive');
    }
}

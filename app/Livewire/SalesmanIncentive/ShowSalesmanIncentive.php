<?php

namespace App\Livewire\SalesmanIncentive;

use Livewire\Component;
use App\Models\Salesman;
use App\Models\OutgoingTransaction;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Illuminate\Database\Eloquent\Builder;

class ShowSalesmanIncentive extends Component
{
    public Salesman $salesman;
    public ?array $dateRangeFilter = null;

    #[Url(as: "table-filters")]
    public ?array $search = [];
    public int $transaction_count;

    public function mount()
    {
        if ($this->search) $this->dateRangeFilter = $this->search['jarak-tanggal'];
    }

    #[On('update-filter')]
    public function updateFilter(?array $dateRangeFilter = null)
    {
        $this->dateRangeFilter = $dateRangeFilter;
    }

    public function render()
    {
        $outgoing_transactions = OutgoingTransaction::sold()
            ->where('salesman_id', $this->salesman->id)
            ->when($this->dateRangeFilter, function (Builder $query, array $dateRange) {
                $query->whereBetween('date', [$dateRange['minDate'], $dateRange['maxDate']]);
            })->get();

        $this->transaction_count = $outgoing_transactions->count();

        return view('livewire.salesman-incentive.show-salesman-incentive', compact('outgoing_transactions'));
    }
}

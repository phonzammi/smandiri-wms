<?php

namespace App\Livewire\Dashboard;

use App\Models\OutgoingTransaction;
use App\Models\Salesman;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class SalesmanIncentiveChart extends Component
{
    public int $year, $month;
    public array $labels, $data;
    public bool $allZero = false;

    public function mount($year)
    {
        $this->year = $year;
        $this->month = now()->month;
        $this->generateChartData();
    }

    public function generateChartData()
    {
        $salesman = Salesman::orderBy('id')->get();
        $outgoing_transactions = OutgoingTransaction::sold()->selectRaw("salesman_id, SUM(net_prices) as total_net_prices")
            ->whereMonth("date", $this->month)
            ->whereYear("date", $this->year)
            ->groupBy('salesman_id')
            ->orderBy('salesman_id')
            ->get()
            ->keyBy('salesman_id');

        $this->labels = $salesman->pluck('name')->toArray();

        $this->data = collect($salesman->pluck('id')->toArray())
            ->map(function ($index) use ($outgoing_transactions) {
                $total_net_prices = $outgoing_transactions[$index]?->total_net_prices ?? null;
                return (int) $total_net_prices;
            })->toArray();

        $this->allZero = collect($this->data)->every(fn($v) => $v == 0);
    }

    #[On('year-changed')]
    public function updateYear($year)
    {
        $this->year = $year;
        $this->month = $this->year === now()->year ? now()->month : 1;
        $this->updatedMonth();
    }

    public function updatedMonth()
    {
        $this->generateChartData();
        $this->dispatch('refreshChart')->self();
    }

    public function render()
    {
        $months = collect(range(1, 12))
            ->map(fn($month) => Carbon::create(null, $month));

        return view('livewire.dashboard.salesman-incentive-chart', compact('months'));
    }
}

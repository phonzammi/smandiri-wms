<?php

namespace App\Livewire\Dashboard;

use App\Models\OutgoingTransaction;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class MonthlyIncomeChart extends Component
{
    public int $year;
    public array $labels = [];
    public array $data = [];

    public bool $allZero = false;

    public function mount($year)
    {
        $this->year = $year;
        $this->generateChartData();
    }

    #[On('year-changed')]
    public function updateYear($year)
    {
        $this->year = $year;
        $this->generateChartData();
        $this->dispatch('refreshChart')->self();
    }

    public function generateChartData()
    {

        // Determine start month:
        // - 2025: start at August (8)
        // - >= 2026: start at January (1)
        $startMonth = $this->year == 2025 ? 8 : 1;

        $months = collect(range($startMonth, 12))
            ->map(fn($month) => Carbon::create(null, $month)->isoFormat('MMMM'));

        $outgoing_transactions = OutgoingTransaction::sold()->selectRaw("MONTH(date) as month, SUM(net_prices) as total_net_prices")
            ->whereYear("date", $this->year)
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $this->labels = $months->toArray();

        $this->data = collect(range($startMonth, 12))->map(function ($month) use ($outgoing_transactions) {
            $total_net_prices = $outgoing_transactions[$month]?->total_net_prices ?? null;
            return (int) $total_net_prices;
        })->toArray();

        $this->allZero = collect($this->data)->every(fn($v) => $v == 0);
    }

    public function render()
    {
        return view('livewire.dashboard.monthly-income-chart');
    }
}

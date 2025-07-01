<?php

namespace App\Livewire\Reports\SalesReport;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

class SalesReportComponent extends Component
{
    public ?array $dateRangeFilter = null;

    #[Url(as: "table-filters")]
    public ?array $search = [];

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
        return view('livewire.reports.sales-report.sales-report-component');
    }
}

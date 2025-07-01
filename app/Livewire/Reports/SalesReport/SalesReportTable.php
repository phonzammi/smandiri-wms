<?php

namespace App\Livewire\Reports\SalesReport;

use App\Models\OutgoingTransaction;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateRangeFilter;

class SalesReportTable extends DataTableComponent
{
    protected $index = 0;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setFilterLayoutSlideDown();
        $this->setFilterSlideDownDefaultStatus(true);
        $this->setEagerLoadAllRelationsEnabled();
    }

    public function columns(): array
    {
        return [
            Column::make("#", "id")
                ->format(fn() => ++$this->index + ($this->getPage() - 1) * $this->getPerPage()),
            Column::make("Kode Invoice", "invoice_code")
                ->sortable()
                ->searchable()
                ->footer(fn() => "Total"),
            Column::make("Sales", "salesman.name")
                ->sortable()
                ->searchable(),
            Column::make("Toko", "store.name")
                ->sortable()
                ->searchable(),
            Column::make("Tanggal", "date")
                ->format(fn($value) => $value->isoFormat('DD MMMM Y'))
                ->sortable(),
            Column::make("Total Harga", "net_prices")
                ->searchable()
                ->sortable()
                ->format(fn($value) => "Rp. " . number_format($value, 0, ',', '.'))
                ->footer(fn($rows) => "Rp. " . number_format($rows->sum('net_prices'), 0, ',', '.')),
        ];
    }

    public function filters(): array
    {
        return [
            DateRangeFilter::make('Jarak Tanggal', 'jarak-tanggal')
                ->config(['placeholder' => 'Isi Jarak Tanggal ...']),
        ];
    }

    public function filterReset()
    {
        $appliedFilters = $this->getAppliedFilterWithValue('jarak-tanggal');
        if ($appliedFilters) $this->dispatch('update-filter', null);
    }

    public function builder(): Builder
    {
        return OutgoingTransaction::query()->sold()
            ->when($this->getAppliedFilterWithValue('jarak-tanggal'), function (Builder $builder, array $dateRange) {
                $this->dispatch('update-filter', $dateRange);
                $builder->whereBetween('outgoing_transactions.date', [$dateRange['minDate'], $dateRange['maxDate']]);
            })
            ->when(count($this->getTableStateToArray()['sorts']) === 0, function (Builder $builder) {
                $builder->orderBy('date', 'desc');
            });
    }
}

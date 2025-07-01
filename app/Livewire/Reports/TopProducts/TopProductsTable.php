<?php

namespace App\Livewire\Reports\TopProducts;

use App\Models\OutgoingTransactionItem;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateRangeFilter;

class TopProductsTable extends DataTableComponent
{
    protected $index = 0;

    public function configure(): void
    {
        $this->setPrimaryKey('item_id');
        $this->setFilterLayoutSlideDown();
        $this->setFilterSlideDownDefaultStatus(true);
        $this->setPerPageAccepted([20, 50, 100]);
        $this->setDefaultPerPage(20);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "item_id")
                ->format(fn() => ++$this->index + ($this->getPage() - 1) * $this->getPerPage()),
            Column::make("Nama Barang", "item.name")
                ->sortable()
                ->searchable(),
            Column::make("Terjual", 'sold')
                ->label(fn($row)  => $row->sold)
                ->sortable(fn(Builder $query, string $direction) => $query->orderBy('sold', $direction))
        ];
    }

    public function filters(): array
    {
        return [
            DateRangeFilter::make('Filter Tanggal', 'filter-tanggal')
                ->config(['placeholder' => 'Isi Filter Tanggal ...'])
                ->setFilterDefaultValue([date('Y-m-d', strtotime('-30 days')), date("Y-m-d")]),
        ];
    }

    public function filterReset()
    {
        $appliedFilters = $this->getAppliedFilterWithValue('filter-tanggal');
        if ($appliedFilters) $this->dispatch('update-filter', null);
    }

    public function builder(): Builder
    {
        return OutgoingTransactionItem::query()
            ->selectRaw('sum(outgoing_transaction_item.quantity) as sold')
            ->whereHas('outgoing_transaction', function (Builder $query) {
                $query->sold()->when($this->getAppliedFilterWithValue('filter-tanggal'), function (Builder $builder, array $dateRange) {
                    $this->dispatch('update-filter', $dateRange);
                    $builder->whereBetween('outgoing_transactions.date', [$dateRange['minDate'], $dateRange['maxDate']]);
                });
            })
            ->groupBy('item_id')
            ->when(count($this->getTableStateToArray()['sorts']) === 0, function (Builder $builder) {
                $builder->orderBy('sold', 'desc');
            });
    }
}

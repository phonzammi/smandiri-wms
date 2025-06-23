<?php

namespace App\Livewire\SalesmanIncentive;

use App\Models\Salesman;
use App\Models\OutgoingTransaction;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Actions\Action;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateRangeFilter;

class ShowSalesmanIncentiveTable extends DataTableComponent
{
    public Salesman $salesman;
    protected $model = OutgoingTransaction::class;
    protected $index = 0;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setFilterLayoutSlideDown();
        $this->setFilterSlideDownDefaultStatus(true);
        $this->setEagerLoadAllRelationsEnabled();
    }

    public function filterUpdated()
    {
        $appliedFilters = $this->getAppliedFilters();
        if (isset($appliedFilters['jarak-tanggal'])) {
            $this->dispatch('update-filter', $appliedFilters['jarak-tanggal']);
        }
    }

    public function filterReset()
    {
        $appliedFilters = $this->getAppliedFilters();
        if (isset($appliedFilters['jarak-tanggal'])) {
            $this->dispatch('update-filter', null);
        }
    }

    public function columns(): array
    {
        return [
            Column::make("#", "id")
                ->format(fn() => ++$this->index + ($this->getPage() - 1) * $this->getPerPage()),
            Column::make("Kode Invoice", "invoice_code")
                ->sortable()
                ->searchable(),
            Column::make("Toko", "store.name")
                ->sortable()
                ->searchable(),
            Column::make("Tanggal", "date")
                ->sortable()
                ->searchable()
                ->format(fn($value) => $value->isoFormat('DD MMMM Y')),
            Column::make("Sub Total", "net_prices")
                ->format(fn($value) => "Rp. " . number_format($value, 0, ',', '.'))
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            DateRangeFilter::make('Jarak Tanggal', 'jarak-tanggal')
                ->config([
                    'placeholder' => 'Isi Jarak Tanggal ...',
                ])
                ->filter(function (Builder $builder, array $dateRange) {
                    $this->dispatch('filter-change', dateRange: $dateRange);
                    $builder
                        ->whereDate('outgoing_transactions.date', '>=', $dateRange['minDate'])
                        ->whereDate('outgoing_transactions.date', '<=', $dateRange['maxDate']);
                }),
        ];
    }

    public function builder(): Builder
    {
        return OutgoingTransaction::query()
            ->where("salesman_id", $this->salesman->id)
            ->where(function (Builder $query) {
                $query->where('status', 'delivering')
                    ->orWhere('status', 'delivered');
            })
            ->orderBy('date', 'desc');
    }
}

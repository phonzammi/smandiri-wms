<?php

namespace App\Livewire\Items\OutgoingTransactions;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\OutgoingTransaction;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class OutgoingTransactionTable extends DataTableComponent
{
    protected $model = OutgoingTransaction::class;
    protected $index = 0;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        // TODO: add filter features, e.g. filter by status
    }

    public function columns(): array
    {
        return [
            Column::make("No.", "id")
                ->format(fn() => ++$this->index + ($this->getPage() - 1) * $this->getPerPage()),
            Column::make("Kode Invoice", "invoice_code")
                ->sortable()
                ->searchable(),
            Column::make("Sales", "salesman.name")
                ->sortable()
                ->searchable(),
            Column::make("Toko", "store.name")
                ->sortable()
                ->searchable(),
            Column::make("Total Harga", "net_prices")
                ->format(fn($value) => "Rp. " . number_format($value, 0, ',', '.'))
                ->sortable()
                ->searchable(),
            Column::make("Status", "status")
                ->sortable()
                ->format(fn($value) => $value->getLabelBadge())
                ->html(),
            ButtonGroupColumn::make('Aksi')
                ->attributes(fn($row) => ['class' => 'mx-auto'])
                ->buttons([
                    LinkColumn::make('Detail')
                        ->title(fn($row) => 'Detail')
                        ->location(fn($row) => route('outgoing-transactions.show', $row->invoice_code))
                        ->attributes(fn($row) => ['class' => 'btn btn-xs btn-info']),
                ]),
        ];
    }
}

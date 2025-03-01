<?php

namespace App\Livewire\Items\IncomingTransactions;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\IncomingTransaction;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class IncomingTransactionTable extends DataTableComponent
{
    protected $model = IncomingTransaction::class;
    protected $index = 0;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setThSortButtonAttributes(function (Column $column) {
            if ($column->isField('total_quantity')) {
                return [
                    'class' => 'justify-content-end',
                    'default' => true
                ];
            }
            return [
                'default' => true
            ];
        });
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('total_quantity')) {
                return [
                    'default' => true,
                    'class' => 'text-right',
                ];
            }
            return [
                'default' => true
            ];
        });
    }

    public function columns(): array
    {
        return [
            Column::make("No.", "id")
                ->format(fn() => ++$this->index + ($this->getPage() - 1) * $this->getPerPage()),
            Column::make("Kode Invoice", "invoice_code")
                ->sortable(),
            Column::make("Jumlah", "total_quantity")
                ->sortable()
                ->searchable(),
            Column::make("Kurir", "courier")
                ->sortable()
                ->searchable(),
            Column::make("Distributor", "distributor")
                ->sortable()
                ->searchable(),
            Column::make("Tanggal", "date")
                ->sortable()
                ->searchable()
                ->format(fn($value) => $value->format('d F Y')),
            Column::make("Selesai", "is_completed")
                ->sortable()
                ->format(fn($value) => $value ? '<span class="badge badge-success text-xs">Selesai</span>' : '<span class="badge badge-warning text-xs">Belum Selesai</span>')->html(),
            ButtonGroupColumn::make('Aksi')
                ->attributes(function ($row) {
                    return [
                        'class' => 'mx-auto',
                    ];
                })
                ->buttons([
                    LinkColumn::make('Detail')
                        ->title(fn($row) => 'Detail')
                        ->location(fn($row) => route('incoming-transactions.show', $row->invoice_code))
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-xs btn-info',
                            ];
                        }),
                    LinkColumn::make('Hapus')
                        ->title(fn($row) => 'Hapus')
                        ->location(fn($row) => '')
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-xs btn-danger',
                                'x-on:click.prevent' => '$deleteTransaction(' . $row->id . ', `' . $row->invoice_code . '`, ' . $row->is_completed . ')'
                            ];
                        }),
                ]),
        ];
    }
}

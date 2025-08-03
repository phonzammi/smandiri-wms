<?php

namespace App\Livewire\ItemUnits;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\ItemUnit;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class ItemUnitTable extends DataTableComponent
{
    protected $model = ItemUnit::class;
    protected $index = 0;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setPerPageAccepted([20, 25, 30]);
        $this->setDefaultPerPage(20);
    }

    public function columns(): array
    {
        return [
            Column::make("No.", "id")
                ->format(fn() => ++$this->index + ($this->getPage() - 1) * $this->getPerPage()),
            Column::make("Nama Satuan", "name")
                ->searchable()
                ->sortable(),
            Column::make("Ukuran", "size")
                ->sortable(),
            ButtonGroupColumn::make('Aksi')
                ->attributes(function ($row) {
                    return [
                        'class' => 'mx-auto',
                    ];
                })
                ->buttons([
                    LinkColumn::make('Edit')
                        ->title(fn($row) => 'Edit')
                        ->location(fn($row) => '')
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-xs btn-warning',
                                'data-toggle' => "modal",
                                'data-target' => "#itemUnit-modal",
                                'x-on:click.prevent' => '$wire.$parent.edit(' . $row->id . ");$('#itemUnit-modal').modal('show');"
                            ];
                        }),
                    LinkColumn::make('Hapus')
                        ->title(fn($row) => 'Hapus')
                        ->location(fn($row) => '')
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-xs btn-danger',
                                'x-on:click.prevent' => '$deleteItemUnit(' . $row->id . ', `' . $row->name . '`)'
                            ];
                        }),
                ]),
        ];
    }
}

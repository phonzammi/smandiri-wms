<?php

namespace App\Livewire\Items;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Item;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class ItemTable extends DataTableComponent
{
    protected $model = Item::class;
    protected $index = 0;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("No.", "id")->format(fn() => ++$this->index + ($this->getPage() - 1) * $this->getPerPage()),
            Column::make("Kode Barang", "code")->sortable()->searchable(),
            Column::make("Nama Barang", "name")->sortable()->searchable(),
            Column::make("Distributor", "distributor.name")->sortable()->searchable(),
            Column::make("Satuan", "itemUnit.name")->sortable()->searchable(),
            Column::make("Harga", "price")->format(
                fn($value) => "Rp. " . number_format($value, 0, ',', '.')
            )->sortable()->searchable(),
            Column::make("Jumlah", "quantity")->sortable()->searchable(),
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
                                'data-target' => "#item-modal",
                                'x-on:click.prevent' => '$wire.$parent.edit(' . $row->id . ");$('#item-modal').modal('show');"
                            ];
                        }),
                    LinkColumn::make('Hapus')
                        ->title(fn($row) => 'Hapus')
                        ->location(fn($row) => '')
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-xs btn-danger',
                                'x-on:click.prevent' => 'SwalDialog.fire({
                                        title: `Apakah Anda yakin ingin menghapus Barang ' . $row->name . '?`,
                                        icon: `warning`,
                                        showCancelButton: true,
                                        confirmButtonText: `Hapus!`,
                                        cancelButtonText: `Batal`,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            $wire.$parent.destroy(' . $row->id . ')
                                        }
                                    });'
                            ];
                        }),
                ]),
        ];
    }
}

<?php

namespace App\Livewire\Stores;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Store;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class StoreTable extends DataTableComponent
{
    protected $model = Store::class;
    protected $index = 0;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("No.", "id")
                ->format(fn() => ++$this->index + ($this->getPage() - 1) * $this->getPerPage()),
            Column::make("Nama", "name")
                ->sortable()
                ->searchable(),
            Column::make("No. Hp", "phone")
                ->searchable(),
            Column::make("Alamat", "address")
                ->sortable()
                ->searchable(),
            ButtonGroupColumn::make('Aksi')
                ->attributes(function ($row) {
                    return [
                        'class' => 'mx-auto',
                        'x-data' => '{ config: { 
                            icon: `warning`,
                            confirmButtonText: `Hapus !`,
                            customClass: { confirmButton: `btn btn-danger`},
                        }}'
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
                                'data-target' => "#toko-modal",
                                'x-on:click.prevent' => '$wire.$parent.edit(' . $row->id . ");$('#toko-modal').modal('show');"
                            ];
                        }),
                    LinkColumn::make('Hapus')
                        ->title(fn($row) => 'Hapus')
                        ->location(fn($row) => '')
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-xs btn-danger',
                                'x-on:click.prevent' => 'SwalDialog.fire({
                                        title: `Apakah Anda yakin ingin menghapus toko ' . $row->name . '?`,
                                        ...config
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

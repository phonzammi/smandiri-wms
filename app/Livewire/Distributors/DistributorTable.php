<?php

namespace App\Livewire\Distributors;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Distributor;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class DistributorTable extends DataTableComponent
{
    protected $model = Distributor::class;
    protected $index = 0;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("No.", "id")->format(fn() => ++$this->index + ($this->getPage() - 1) * $this->getPerPage()),
            Column::make("Nama Satuan", "name")
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
                                'data-target' => "#distributor-modal",
                                'x-on:click.prevent' => '$wire.$parent.edit(' . $row->id . ");$('#distributor-modal').modal('show');"
                            ];
                        }),
                    LinkColumn::make('Hapus')
                        ->title(fn($row) => 'Hapus')
                        ->location(fn($row) => '')
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-xs btn-danger',
                                'x-on:click.prevent' => 'SwalDialog.fire({
                                        title: `Apakah Anda yakin ingin menghapus distributor ' . $row->name . '?`,
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

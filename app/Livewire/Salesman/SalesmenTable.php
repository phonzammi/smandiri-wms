<?php

namespace App\Livewire\Salesman;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Salesman;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class SalesmenTable extends DataTableComponent
{
    protected $model = Salesman::class;
    protected $index = 0;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        // $this->index = $this->getPage() > 1 ? ($this->getPage() - 1) * $this->perPage : 0;
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
                                'data-target' => "#salesman-modal",
                                'x-on:click.prevent' => '$wire.$parent.edit(' . $row->id . ");$('#salesman-modal').modal('show');"
                            ];
                        }),
                    LinkColumn::make('Hapus')
                        ->title(fn($row) => 'Hapus')
                        ->location(fn($row) => '')
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-xs btn-danger',
                                'x-on:click.prevent' => 'SwalDialog.fire({
                                        title: `Apakah Anda yakin ingin menghapus sales ' . $row->name . '?`,
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

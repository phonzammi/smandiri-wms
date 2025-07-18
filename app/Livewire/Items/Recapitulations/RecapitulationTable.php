<?php

namespace App\Livewire\Items\Recapitulations;

use App\Models\Recapitulation;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class RecapitulationTable extends DataTableComponent
{
    protected $index = 0;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("#", "id")
                ->format(fn() => ++$this->index + ($this->getPage() - 1) * $this->getPerPage()),
            Column::make("Kode Rekap", "recapitulation_code")
                ->sortable()
                ->searchable(),
            Column::make("Tanggal", "date")
                ->sortable()
                ->searchable()
                ->format(fn($value) => $value->isoFormat('DD MMMM Y')),
            Column::make("Driver", "driver")
                ->sortable(),
            Column::make("Helper", "helper")
                ->sortable(),
            Column::make("No. Kendaraan", "license_plate")
                ->sortable(),
            Column::make("Salesman", "salesman_count")
                ->sortable(),
            ButtonGroupColumn::make('#Aksi')
                ->attributes(fn($row) => ['class' => 'mx-auto'])
                ->buttons([
                    LinkColumn::make('Cetak')
                        ->title(fn($row) => 'Cetak')
                        ->location(fn($row) => route('recapitulations.print', $row->id))
                        ->attributes(fn($row) => ['class' => 'btn btn-xs btn-info', 'target' => '_blank']),
                ]),
        ];
    }

    public function builder(): Builder
    {
        return Recapitulation::query()
            ->when(count($this->getTableStateToArray()['sorts']) === 0, function (Builder $builder) {
                $builder->orderBy('date', 'desc');
            });
    }
}

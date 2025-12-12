<div>
    @section('plugins.Chartjs', true)
    @section('content_header_title', 'Dashboard')

    <div class=" card card-primary">
        <div class="card-header">
            <h3 class="card-title m-1">Grafik Pendapatan dan Insentif Salesman</h3>
            <div class="card-tools">
                <x-adminlte-select fgroupClass="d-flex m-0" label="Tahun" labelClass="mr-2" name="year"
                    wire:model.live="year" igroup-size="sm">
                    @foreach (range(2025, now()->year) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </x-adminlte-select>
            </div>
        </div>
        <div class="row card-body">
            <div class="col-12 col-md-6 col-sm-12">
                <livewire:dashboard.monthly-income-chart :$year />
            </div>
            <div class="col-12 col-md-6 col-sm-12">
                <livewire:dashboard.salesman-incentive-chart :$year />
            </div>
        </div>
    </div>
</div>

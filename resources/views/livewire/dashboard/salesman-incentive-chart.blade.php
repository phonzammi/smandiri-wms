<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title m-1">Grafik Insentif Salesman</h3>
        <div class="card-tools">
            <x-adminlte-select fgroupClass="d-flex m-0" label="Bulan" labelClass="mr-2" wire:model.live='month'
                name="month" igroup-size="sm">
                @foreach ($months as $month)
                    <option value="{{ $month->isoFormat('M') }}">{{ $month->isoFormat('MMMM') }}</option>
                @endforeach
            </x-adminlte-select>
        </div>
    </div>
    <div class="card-body" style="position: relative;">
        <canvas wire:ignore.self class="w-100 h-auto" id="salesman-incentive-chart"></canvas>
    </div>
</div>

@script
    <script>
        $wire.on('refreshChart', () => {
            updateChart()
        })
        const ctx = document.getElementById('salesman-incentive-chart');

        let chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: $wire.get('labels'),
                datasets: [{
                    label: 'Insentif Salesman',
                    data: $wire.get('data'),
                    borderWidth: 2,
                }],
            },
            options: {
                indexAxis: 'x',
                responsive: true,

                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let value = context.raw ?? 0;
                                return `Rp ${value.toLocaleString('id-ID')}`
                            }
                        }
                    }
                },

                scales: {
                    y: {
                        min: $wire.get('allZero') ? 0 : undefined,
                        max: $wire.get('allZero') ? 10000000 : undefined,
                        ticks: {
                            stepSize: $wire.get('allZero') ? 2000000 : undefined,
                            callback: function(value) {
                                return `Rp ${value.toLocaleString('id-ID')}`
                            }
                        }
                    }
                }
            }
        });

        function updateChart() {
            const allZero = $wire.get('allZero');

            chart.options.scales.y.min = allZero ? 0 : undefined;
            chart.options.scales.y.max = allZero ? 10000000 : undefined;
            chart.options.scales.y.ticks.stepSize = allZero ? 2000000 : undefined;

            chart.data.labels = $wire.get('labels');
            chart.data.datasets[0].data = $wire.get('data');

            chart.update();
        }
    </script>
@endscript

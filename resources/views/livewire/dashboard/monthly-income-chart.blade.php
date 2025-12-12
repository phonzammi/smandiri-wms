 <div class="card card-primary">
     <div class="card-header">
         <h3 class="card-title m-1">Grafik Pendapatan Per Bulan</h3>
         <div class="card-tools">
             <span class="text-light">{{ $year }}</span>
         </div>
     </div>
     <div class="card-body" style="position: relative;">
         <canvas wire:ignore.self class="w-100 h-auto" id="monthly-income-chart"></canvas>
     </div>
 </div>

 @script
     <script>
         $wire.on('refreshChart', () => {
             updateChart()
         })

         const ctx = document.getElementById('monthly-income-chart');

         let chart = new Chart(ctx, {
             type: 'bar',
             data: {
                 labels: $wire.get('labels'),
                 datasets: [{
                     label: 'Pendapatan Per Bulan',
                     data: $wire.get('data'),
                     borderWidth: 2,
                 }],
             },
             options: {
                 indexAxis: 'y',
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
                     x: {
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

             chart.options.scales.x.min = allZero ? 0 : undefined;
             chart.options.scales.x.max = allZero ? 10000000 : undefined;
             chart.options.scales.x.ticks.stepSize = allZero ? 2000000 : undefined;

             chart.data.labels = $wire.get('labels');
             chart.data.datasets[0].data = $wire.get('data');

             chart.update();
         }
     </script>
 @endscript

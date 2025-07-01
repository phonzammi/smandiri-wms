<div>
    @section('content_header_title', 'Laporan Transaksi Penjualan')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title m-1">Laporan Transaksi Penjualan</h3>
            <div class="card-tools">
                @if ($dateRangeFilter)
                    <a href="{{ route('reports.sales-report.print', ['minDate' => $dateRangeFilter['minDate'], 'maxDate' => $dateRangeFilter['maxDate']]) }}"
                        class="btn btn-sm btn-info" target="_blank">
                        <i class="mr-1 fas fa-print"></i>Cetak
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <livewire:reports.sales-report.sales-report-table />
        </div>
    </div>
</div>

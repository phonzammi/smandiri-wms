<div>
    @section('content_header_title', "Detail Insentif Sales : $salesman->name")
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('salesman-incentive') }}">Insentif Sales</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Insentif Sales : {{ $salesman->name }}</li>
        </ol>
    </nav>
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title m-1">Data Sales</h3>
        </div>
        <div class="card-body">
            <table class="table table-sm table-borderless text-nowrap">
                <tbody class="font-weight-bold">
                    <tr>
                        <th scope="row" style="width:20%;">Nama Lengkap</th>
                        <td>: {{ $salesman->name }}</td>
                    </tr>
                    <tr>
                        <th scope="row" style="width:20%;">Alamat</th>
                        <td>: {{ $salesman->address }}</td>
                    </tr>
                    <tr>
                        <th scope="row" style="width:20%;">No. Hp</th>
                        <td>: {{ $salesman->phone }}</td>
                    </tr>
                    <tr>
                        <th scope="row" style="width:20%;">Total Transaksi</th>
                        <td>: Rp. {{ number_format($outgoing_transactions->sum('net_prices'), 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title m-1">Detail Invoice Barang Keluar</h3>
            <div class="card-tools">
                @if ($dateRangeFilter && $transaction_count)
                    <a href="{{ route('salesman-incentive.print', ['salesman' => $salesman, 'minDate' => $dateRangeFilter['minDate'], 'maxDate' => $dateRangeFilter['maxDate']]) }}"
                        class="btn btn-sm btn-info" target="_blank">
                        <i class="mr-1 fas fa-print"></i>Cetak
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <livewire:salesman-incentive.show-salesman-incentive-table :salesman="$salesman" />
        </div>
    </div>
</div>

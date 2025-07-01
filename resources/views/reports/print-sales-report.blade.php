<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    <title>Cetak Laporan Transaksi Penjualan </title>
</head>

<body class="container">
    <div class="">
        <div class="row justify-content-between">
            <div class="col-4 align-self-end">
                <h3 class="text-muted text-nowrap">
                    Laporan Transaksi Penjualan
                </h3>
            </div>
            <div class="col-4">
                <img src="{{ asset('assets/img/logo_dark.png') }}" class="img-fluid" alt="...">
            </div>
        </div>
    </div>

    <div class="card bg-light mb-3">
        <div class="card-header">
            <h3 class="card-title">
                Transaksi Penjualan | Tanggal
                @if ($minDate && $maxDate)
                    {{ \Carbon\Carbon::createFromFormat('Y-m-d', $minDate)->isoFormat('DD MMMM Y') }}
                    <span class="text-sm font-italic font-weight-bold"> s.d.</span>
                    {{ \Carbon\Carbon::createFromFormat('Y-m-d', $maxDate)->isoFormat('DD MMMM Y') }}
                @endif
            </h3>
        </div>
        <div class="card-body bg-white">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Kode Invoice</th>
                            <th scope="col">Sales</th>
                            <th scope="col">Toko</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col" class="text-right" style="width:20%">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($outgoing_transactions as $transaction)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $transaction->invoice_code }}</td>
                                <td>{{ $transaction->salesman->name }}</td>
                                <td>{{ $transaction->store->name }}</td>
                                <td>{{ $transaction->date->isoFormat('DD MMMM Y') }}</td>
                                <td class="text-right">
                                    <div class="row justify-content-end text-nowrap">
                                        <span class="col-5">Rp. </span>
                                        <span class="col-7">
                                            {{ number_format($transaction->net_prices, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="table-danger">
                                <td colspan="6">Tidak ada Transaksi Penjualan yang ditemukan</td>
                            </tr>
                        @endforelse
                        @if ($outgoing_transactions->count())
                            <tr class="font-weight-bold">
                                <td colspan="5">Total</td>
                                <td class="text-right">
                                    <div class="row justify-content-between text-nowrap">
                                        <span class="col-4">Rp. </span>
                                        <span class="col-8">
                                            {{ number_format($outgoing_transactions->sum('net_prices'), 0, ',', '.') }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>

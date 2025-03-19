<html>

<head>
    <title>Faktur Pembayaran</title>

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
</head>

<body style='width:700px;' class="mx-auto">
    <div class="row my-1" style="font-family: calibri;">
        <div class="col-7">
            <h6 class="font-weight-bold mb-0">UD. Sukses Mandiri</h6>
            <span class="text-sm">
                Jln. Medan - Banda Aceh Meunasah Mesjid Cunda - Kota Lhokseumawe, Telp : 0822-7216-3053
            </span>
            <table class="w-75 mt-1">
                <tbody>
                    <tr>
                        <td class="w-25">Sales</td>
                        <td>: {{ $outgoingTransaction->salesman->name }}</td>
                    </tr>
                    <tr>
                        <td class="w-25">No. Telp</td>
                        <td>: {{ $outgoingTransaction->salesman->phone }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-5">
            <h6 class="font-weight-bold mb-1">FAKTUR PENJUALAN</h6>
            <table class="w-100">
                <tbody>
                    <tr>
                        <td class="w-25">No Trans.</td>
                        <td>: {{ $outgoingTransaction->invoice_code }}</td>
                    </tr>
                    <tr>
                        <td class="w-25">Tanggal</td>
                        <td>: {{ $outgoingTransaction->date->isoFormat('DD MMMM Y') }}</td>
                    </tr>
                    <tr>
                        <td class="w-25">Toko</td>
                        <td>: {{ $outgoingTransaction->store->name }}</td>
                    </tr>
                    <tr>
                        <td class="w-25">Alamat</td>
                        <td>: {{ $outgoingTransaction->store->address }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <table class="w-100 mb-1" style='font-size:12pt; font-family:calibri;' border="1">
        <thead class="text-center">
            <tr>
                <th scope="col">Barang</th>
                <th scope="col">Jumlah</th>
                <th scope="col">Satuan</th>
                <th scope="col">Harga@</th>
                <th scope="col">Diskon</th>
                <th scope="col">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($outgoingTransaction->outgoing_items as $outgoing_item)
                <tr>
                    <td>{{ $outgoing_item->name }}</td>
                    <td class="text-center">{{ $outgoing_item->pivot->quantity }}</td>
                    <td class="text-center">{{ $outgoing_item->itemUnit->name }}</td>
                    <td class="text-right">
                        <div class="d-flex flex-nowrap justify-content-between">
                            <span>Rp. </span>
                            <span>
                                {{ number_format($outgoing_item->pivot->unit_price, 0, ',', '.') }}
                            </span>
                        </div>
                    </td>
                    <td class="text-right">
                        <div class="d-flex flex-nowrap justify-content-between">
                            <span>Rp. </span>
                            <span>
                                {{ number_format($outgoing_item->pivot->discount, 0, ',', '.') }}
                            </span>
                        </div>
                    </td>
                    <td class="text-right">
                        <div class="d-flex flex-nowrap justify-content-between">
                            <span>Rp. </span>
                            <span>
                                {{ number_format($outgoing_item->pivot->total_net_price, 0, ',', '.') }}
                            </span>
                        </div>
                    </td>
                </tr>
            @endforeach
            <tr class="font-weight-bold text-md">
                <td colspan="1" class="text-right pr-2">Jumlah :</td>
                <td class="text-center">
                    {{ $outgoingTransaction->outgoing_items->sum('pivot.quantity') }}
                </td>
                <td colspan="4" class="text-right">
                    Total : Rp. {{ number_format($outgoingTransaction->prices, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td colspan="7" class="text-right font-weight-bold text-md">
                    Diskon : Rp. {{ number_format($outgoingTransaction->discounts, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td colspan="7" class="text-right font-weight-bold text-md">
                    Diskon : Rp. {{ number_format($outgoingTransaction->net_prices, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>
    <p class="text-sm pb-3">Diterima Oleh,</p>
    <p><u>{{ $outgoingTransaction->store->name }}</u></p>
</body>

</html>

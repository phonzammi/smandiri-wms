<html>

<head>
    <title>Faktur Pembayaran</title>

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <style>
        @media print {
            @page {
                /* size: 9.5in 11in; */
                size: landscape;
            }

            .page {
                break-after: page;
            }
        }

        table {
            border-collapse: collapse;
        }

        table td {
            vertical-align: top !important;
            padding: 2px;
        }
    </style>
</head>

<body style='width: 1056px; font-family: tahoma; font-size: 12pt;' class="mx-auto">
    @foreach ($outgoingTransaction->outgoing_items->chunk(15) as $chunk)
        <div class="page">
            <div class="row my-2" style="font-size: 12pt; font-family: calibri;">
                <div class="col-8" style="font-weight: 570;">
                    <span class="d-block text-lg"><b>UD. Sukses Mandiri</b></span>
                    <p class="p-0 m-0" style="font-size: 11.5pt;">
                        Jln. Medan - Banda Aceh Meunasah Mesjid Cunda -
                    </p>
                    <p class="p-0 m-0" style="font-size: 11.5pt;">
                        Kota Lhokseumawe, Telp : 0822-7216-3053
                    </p>
                    <table class="w-75 mt-1" style="font-weight: 570;" border="0">
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
                <div class="col-4">
                    <span class="d-block text-lg"><b>FAKTUR PENJUALAN</b></span>
                    <table class="w-100" style="font-weight: 570;" border="0">
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
                            <tr>
                                <td class="w-25">Pembayaran</td>
                                <td>: {{ $outgoingTransaction->payment_method->name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <table class="w-100" style='font-size: 14pt; font-family: calibri; border-spacing: 0; font-weight: 570;'
                border="1">
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
                    @foreach ($chunk as $outgoing_item)
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
                                        {{ number_format($outgoing_item->pivot->total_price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if ($loop->last)
                        <tr class="font-weight-bold ">
                            <td colspan="1" class="text-right pr-2">Jumlah :</td>
                            <td class="text-center">
                                {{ $outgoingTransaction->outgoing_items->sum('pivot.quantity') }}
                            </td>
                            <td colspan="3" class="text-right" style="border-right: 1px solid transparent">
                                Total :
                            </td>
                            <td>
                                <div class="d-flex justify-content-between">
                                    <span>Rp.</span>
                                    <span>
                                        {{ number_format($outgoingTransaction->prices, 0, ',', '.') }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr class="font-weight-bold">
                            <td colspan="4" rowspan="2">
                                Deskripsi :
                            </td>
                            <td class="text-right " style="border-right: 1px solid transparent">
                                Diskon :
                            </td>
                            <td>
                                <div class="d-flex justify-content-between">
                                    <span>Rp.</span>
                                    <span>
                                        {{ number_format($outgoingTransaction->discounts, 0, ',', '.') }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr class="font-weight-bold">
                            <td class="text-right " style="border-right: 1px solid transparent">
                                NETT :
                            </td>
                            <td>
                                <div class="d-flex justify-content-between">
                                    <span>Rp.</span>
                                    <span>
                                        {{ number_format($outgoingTransaction->net_prices, 0, ',', '.') }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
            @if ($loop->last)
                <div class="pt-3 row justify-content-end" style="font-size:12pt; font-weight: 570;">
                    <div class="col-2">
                        <p>TTD Driver/Helper,</p>
                    </div>
                    <div class="col-10">
                        <p>Diterima Oleh,</p>
                        <p><u>{{ $outgoingTransaction->store->name }}</u></p>
                    </div>
                </div>
            @endif
        </div>
    @endforeach
</body>

</html>

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
    </style>
</head>

<body style='width: 700px; font-family: tahoma; font-size: 12pt;' class="mx-auto">
    @foreach ($outgoingTransaction->outgoing_items->chunk(15) as $chunk)
        <div class="page">
            <table style="font-family: calibri; width: 100%; border-collapse: collapse;" class="my-2">
                <tbody>
                    <tr>
                        <th colspan="2" style="max-width: 60%;">UD. Sukses Mandiri</th>
                        <th colspan="2">FAKTUR PENJUALAN</th>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size: 11.5pt;">
                            Jln. Medan - Banda Aceh Meunasah Mesjid Cunda,
                        </td>
                        <td>No Trans.</td>
                        <td>: {{ $outgoingTransaction->invoice_code }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">Kota Lhokseumawe, Telp : 0822-7216-3053</td>
                        <td>Tanggal</td>
                        <td>: {{ $outgoingTransaction->date->isoFormat('DD MMMM Y') }}</td>
                    </tr>
                    <tr>
                        <td style="width: 10%;">Sales</td>
                        <td>: {{ $outgoingTransaction->salesman->name }}</td>
                        <td>Toko</td>
                        <td>: {{ $outgoingTransaction->store->name }}</td>
                    </tr>
                    <tr>
                        <td style="width: 10%;">No. Telp</td>
                        <td>: {{ $outgoingTransaction->salesman->phone }}</td>
                        <td>Alamat</td>
                        <td>: {{ $outgoingTransaction->store->address }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Pembayaran</td>
                        <td>: {{ $outgoingTransaction->payment_method->name }}</td>
                    </tr>
                </tbody>
            </table>
            <table
                style='width: 100%; font-size: 12pt; font-family: calibri; border-collapse: collapse; font-weight: 570;'
                border="1">
                <thead style="text-align: center;">
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
                            <td style="text-align: center;">{{ $outgoing_item->pivot->quantity }}</td>
                            <td style="font-size: 11pt;">{{ $outgoing_item->itemUnit->name }}</td>
                            <td>
                                <div style="display: flex; flex-wrap: nowrap; justify-content: space-between;">
                                    <span>Rp. </span>
                                    <span>
                                        {{ number_format($outgoing_item->pivot->unit_price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; flex-wrap: nowrap; justify-content: space-between;">
                                    <span>Rp. </span>
                                    <span>
                                        {{ number_format($outgoing_item->pivot->discount, 0, ',', '.') }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; flex-wrap: nowrap; justify-content: space-between;">
                                    <span>Rp. </span>
                                    <span>
                                        {{ number_format($outgoing_item->pivot->total_price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if ($loop->last)
                        <tr style="font-size: medium; font-weight: 700;">
                            <td colspan="1" style="text-align: right; padding-right: 8px;">
                                Jumlah :
                            </td>
                            <td style="text-align: center;">
                                {{ $outgoingTransaction->outgoing_items->sum('pivot.quantity') }}
                            </td>
                            <td colspan="3" style="text-align: right; border-right-color: transparent">
                                Total :
                            </td>
                            <td>
                                <div style="display: flex; flex-wrap: nowrap; justify-content: space-between;">
                                    <span>Rp.</span>
                                    <span>
                                        {{ number_format($outgoingTransaction->prices, 0, ',', '.') }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr style="font-size: medium; font-weight: 700;">
                            <td colspan="4" rowspan="2" style="vertical-align: top;">
                                Deskripsi :
                            </td>
                            <td style="text-align: right; border-right-color: transparent">
                                Diskon :
                            </td>
                            <td>
                                <div style="display: flex; flex-wrap: nowrap; justify-content: space-between;">
                                    <span>Rp.</span>
                                    <span>
                                        {{ number_format($outgoingTransaction->discounts, 0, ',', '.') }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr style="font-size: medium; font-weight: 700;">
                            <td style="text-align: right; border-right-color: transparent">
                                NETT :
                            </td>
                            <td>
                                <div style="display: flex; flex-wrap: nowrap; justify-content: space-between;">
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
                <table style="width:50%; font-size: 11pt; margin-top: 20px" cellspacing='2'>
                    <tbody>
                        <tr>
                            <th>TTD Driver/Helper,</th>
                            <th>Diterima Oleh,</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="padding-top: 30px;">
                                <u>
                                    {{ $outgoingTransaction->store->name }}
                                </u>
                            </td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>
    @endforeach
</body>

</html>

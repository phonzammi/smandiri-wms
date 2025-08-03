<html>


<head>
    <title>Faktur Pembayaran</title>
    <style>
        #tabel {
            font-size: 15px;
            border-collapse: collapse;
        }

        #tabel td {
            padding-left: 5px;
            border: 1px solid black;
        }

        @media print {
            .page {
                break-after: page;
            }
        }
    </style>
</head>

<body style='font-family:tahoma; font-size:12pt;'>
    <center>
        @foreach ($outgoingTransaction->outgoing_items->chunk(15) as $chunk)
            <div class="page" style="width:700px;">
                <table
                    style="width: 100%; font-family: calibri; border-collapse: collapse; margin: 2px 0; text-align: left;">
                    <tbody>
                        <tr>
                            <th colspan="2" style="width: 60%;">UD. Sukses Mandiri</th>
                            <th colspan="2" style="width: 40%;">FAKTUR PENJUALAN</th>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-size: 11.5pt;">
                                Jln. Medan - Banda Aceh Meunasah Mesjid Cunda,
                            </td>
                            <td>No Trans.</td>
                            <td style="white-space: nowrap !important;">: {{ $outgoingTransaction->invoice_code }}</td>
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
                <table cellspacing='0'
                    style='width:700px; font-size:12pt; font-family:calibri;  border-collapse: collapse; margin-top: 5px; margin-bottom:20px;'
                    border='1'>
                    <thead>
                        <tr align='center'>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Harga@</th>
                            <th>Diskon</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($chunk as $outgoing_item)
                            <tr>
                                <td>{{ $outgoing_item->name }}</td>
                                <td style="text-align: center;">{{ $outgoing_item->pivot->quantity }}</td>
                                <td>{{ $outgoing_item->itemUnit->name }}</td>
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
                            <tr>
                                <td colspan="1" style="text-align: right; font-size:medium;">Jumlah : </td>
                                <td style="font-size:medium; text-align: center">
                                    <b>{{ $outgoingTransaction->outgoing_items->sum('pivot.quantity') }}</b>
                                </td>
                                <td colspan="3"
                                    style="text-align: right; font-size:18px; border-right-color: transparent;">
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
                            <tr>
                                <td colspan="4" rowspan="2" style="vertical-align: top;">
                                    Deskripsi :
                                </td>
                                <td style="text-align: right; font-size:18px; border-right-color: transparent;">
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
                            <tr>
                                <td style="text-align: right; font-size:18px; border-right-color: transparent;">
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
                    <table style='width:700px; font-size:11pt;' cellspacing='2'>
                        <tr>
                            <td style="width: 25%;">TTD Driver/Helper,</td>
                            <td>Diterima Oleh,</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="padding-top: 30px;">
                                <u>{{ $outgoingTransaction->store->name }}</u>
                            </td>
                        </tr>
                    </table>
                @endif
            </div>
        @endforeach
    </center>
</body>

</html>

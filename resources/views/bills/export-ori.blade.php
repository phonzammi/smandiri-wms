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
    </style>
</head>

<body style='font-family:tahoma; font-size:12pt;'>
    <center>
        @foreach ($outgoingTransaction->outgoing_items->chunk(15) as $chunk)
            <table style='width:700px; font-size:12pt; font-family:calibri; border-collapse: collapse;' border='0'>
                <td width='60%' align='left' style='padding-right:80px; vertical-align:top'>
                    <span style='font-size:12pt'><b>UD. Sukses Mandiri</b></span></br>
                    <span style='font-size:11.5pt'>Jln. Medan - Banda Aceh Meunasah Mesjid Cunda, Kota Lhokseumawe,
                        Telp : 0822 7216 3053</span>
                </td>
                <td style='vertical-align:top' width='40%' align='left'>
                    <b><span style='font-size:12pt'>FAKTUR PENJUALAN</span></b></br>
                    No Trans. : {{ $outgoingTransaction->invoice_code }}</br>
                    Tanggal : {{ $outgoingTransaction->date->isoFormat('DD MMMM Y') }}</br>
                </td>
            </table>
            <table style='width:700px; font-size:12pt; font-family:calibri; border-collapse: collapse;' border='0'>
                <td width='60%' align='left' style='padding-right:80px; vertical-align:top'>
                    Sales : {{ $outgoingTransaction->salesman->name }} <br>
                    No. Telp : {{ $outgoingTransaction->salesman->phone }} </td>
                <td style='vertical-align:top' width='40%' align='left'>
                    Toko : {{ $outgoingTransaction->store->name }} <br>
                    Alamat : {{ $outgoingTransaction->store->address }}<br>
                    Pembayaran : {{ $outgoingTransaction->payment_method->name }} </td>
            </table>
            <table cellspacing='0'
                style='width:700px; font-size:12pt; font-family:calibri;  border-collapse: collapse; margin-top: 5px; margin-bottom:30px;'
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
                            <td>{{ $outgoing_item->pivot->quantity }}</td>
                            <td>{{ $outgoing_item->itemUnit->name }}</td>
                            <td>
                                Rp. {{ number_format($outgoing_item->pivot->unit_price, 0, ',', '.') }}
                            </td>
                            <td>
                                Rp. {{ number_format($outgoing_item->pivot->discount, 0, ',', '.') }}
                            </td>
                            <td>
                                Rp. {{ number_format($outgoing_item->pivot->total_price, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                    @if ($loop->last)
                        <tr>
                            <td colspan="1" style="text-align: right; font-size:medium;">Jumlah : </td>
                            <td style="font-size:medium;">
                                <b>{{ $outgoingTransaction->outgoing_items->sum('pivot.quantity') }}</b>
                            </td>
                            <td colspan="4" style="text-align: right; font-size:18px;">Total : Rp.
                                {{ number_format($outgoingTransaction->prices, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="7" style="text-align: right; font-size:18px;">Diskon : Rp.
                                {{ number_format($outgoingTransaction->discounts, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="7" style="text-align: right; font-size:18px;">NETT : Rp.
                                {{ number_format($outgoingTransaction->net_prices, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endforeach
        <table style='width:700; font-size:11pt; margin-top: -20px' cellspacing='2'>
            <tr>
                <td align='left'>Diterima Oleh,</br></br></br><u>{{ $outgoingTransaction->store->name }}</u></td>
                <td align=''></br></br><u></u></td>
            </tr>
        </table>
    </center>
</body>

</html>

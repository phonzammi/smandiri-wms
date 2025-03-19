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
        <table style='width:700px; font-size:12pt; font-family:calibri; border-collapse: collapse;' border='0'>
            <td width='70%' align='left' style='padding-right:80px; vertical-align:top'>
                <span style='font-size:12pt'><b>UD. Sukses Mandiri</b></span></br>
                Jln. Medan-Banda Aceh Meunasah Mesjid Cunda-Kota Lhokseumawe /
                Telp : 0822 7216 3053
            </td>
            <td style='vertical-align:top' width='30%' align='left'>
                <b><span style='font-size:12pt'>FAKTUR PENJUALAN</span></b></br>
                No Trans. : {{ $outgoingTransaction->invoice_code }}</br>
                Tanggal : {{ $outgoingTransaction->date->isoFormat('DD MMMM Y') }}</br>
            </td>
        </table>
        <table style='width:700px; font-size:12pt; font-family:calibri; border-collapse: collapse;' border='0'>
            <td width='70%' align='left' style='padding-right:80px; vertical-align:top'>
                Sales : {{ $outgoingTransaction->salesman->name }} <br>
                No. Telp : {{ $outgoingTransaction->salesman->phone }} </td>
            <td style='vertical-align:top' width='30%' align='left'>
                Toko : {{ $outgoingTransaction->store->name }} <br>
                Alamat : {{ $outgoingTransaction->store->address }} </td>
        </table>
        <table cellspacing='0'
            style='width:700px; font-size:12pt; font-family:calibri;  border-collapse: collapse; margin-bottom:180px;'
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
                @foreach ($outgoingTransaction->outgoing_items as $outgoing_item)
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
                            Rp. {{ number_format($outgoing_item->pivot->total_net_price, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
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
            </tbody>
        </table>
        <table style='width:700; font-size:11pt;margin-top: -180px;' cellspacing='2'>
            <tr>
                <td align='left'>Diterima Oleh,</br></br></br><u>{{ $outgoingTransaction->store->name }}</u></td>
                <td align=''></br></br><u></u></td>
            </tr>
        </table>
    </center>
</body>

</html>

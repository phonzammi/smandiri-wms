<html>

<head>
    <title>Rekapitulasi Per Barang</title>
</head>

<body style='width: 1024px; font-family: tahoma; font-size: 12pt; margin: 0 auto;'>
    <div style="display:flex; font-size: 12pt; justify-content: space-between; font-family: calibri; margin: 8px 0">
        <div>
            <span style='font-size:12pt'><b>UD. Sukses Mandiri</b></span>
            <p style="padding:0; margin:0; font-size: 11.5pt; line-height: 1.2;">
                Jln. Medan - Banda Aceh Meunasah Mesjid Cunda,
            </p>
            <p style="padding:0; margin:0; font-size: 11.5pt; line-height: 1.2;">
                Kota Lhokseumawe, Telp : 0822-7216-3053
            </p>
            <table style="margin-top: 4px" border="0">
                <tbody>
                    <tr>
                        <td>Kendaraan</td>
                        <td>: {{ $recapitulation->license_plate }}</td>
                    </tr>
                    <tr>
                        <td>Supir</td>
                        <td>: {{ $recapitulation->driver }}</td>
                    </tr>
                    <tr>
                        <td>Helper</td>
                        <td>: {{ $recapitulation->helper }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <span style='font-size:12pt'><b>REKAPITULASI PER BARANG</b></span></br>
            <table border="0">
                <tbody>
                    <tr>
                        <td>Kode Rekapitulasi</td>
                        <td>
                            : {{ $recapitulation->recapitulation_code }}-{{ $salesman->id }}
                        </td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>: {{ $recapitulation->date->isoFormat('DD MMMM Y') }}</td>
                    </tr>
                    <tr>
                        <td>Sales</td>
                        <td>: {{ $salesman->name }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <table cellspacing='0' style='width: 100%; font-size: 12pt; font-family: calibri; border-collapse: collapse;'
        border="1">
        <thead style="text-align: center;">
            <tr>
                <th scope="col">No.</th>
                <th scope="col">Nama Barang</th>
                <th scope="col">Besar</th>
                <th scope="col">Tengah</th>
                <th scope="col">Kecil</th>
                <th scope="col">Terkirim</th>
                <th scope="col">Tidak Terkirim</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($recapitulation->items as $item)
                <tr>
                    <th style="text-align: center;" scope=row>{{ $loop->iteration }}.</th>
                    <td>{{ $item->name }}</td>
                    <td style="text-align: center;">
                        {{ $item->itemUnit->size->isLarge() ? $item->pivot->total_quantity : '' }}
                    </td>
                    <td style="text-align: center;">
                        {{ $item->itemUnit->size->isMedium() ? $item->pivot->total_quantity : '' }}
                    </td>
                    <td style="text-align: center;">
                        {{ $item->itemUnit->size->isSmall() ? $item->pivot->total_quantity : '' }}
                    </td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div
        style="display: flex; justify-content: space-evenly; align-items: flex-start; font-family: calibri; margin: 8px 0;">
        <table border="0">
            <tbody style="font-weight: bold; font-size: medium;">
                <tr>
                    <td>Total Barang</td>
                    <td>: {{ $recapitulation->items->sum('pivot.total_quantity') }}</td>
                </tr>
            </tbody>
        </table>

        <table border="0">
            <tbody style="font-weight: bold; font-size: medium;">
                <tr>
                    <td>Total Faktur</td>
                    <td>: {{ $recapitulation->outgoing_transactions->count() }}</td>
                </tr>
                <tr>
                    <td>Total Bruto</td>
                    <td>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="margin-right: 8px;">: Rp.</span>
                            <span>{{ number_format($recapitulation->items->sum('pivot.total_price'), 0, ',', '.') }}</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Total Diskon</td>
                    <td>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="margin-right: 8px;">: Rp.</span>
                            <span>{{ number_format($recapitulation->items->sum('pivot.total_discount'), 0, ',', '.') }}</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="">Total Neto</td>
                    <td>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="margin-right: 8px;">: Rp.</span>
                            <span>{{ number_format($recapitulation->items->sum('pivot.total_net_price'), 0, ',', '.') }}</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="font-size: 11pt; display: flex; justify-content: space-evenly; flex-wrap: nowrap; padding-top: 16px;">
        <p>Diperiksa oleh,</p>
        <p>Diterima oleh,</p>
        <p>Diterima kembali,</p>
    </div>
</body>

</html>

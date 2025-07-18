<html>

<head>
    <title>Rekapitulasi Per Barang</title>

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <style>
        @media print {
            @page {
                /* size: 9.5in 11in; */
                size: portrait;
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
            font-size: 12pt !important;
        }
    </style>
</head>

<body style='width: 1056px; font-family: tahoma; font-size: 12pt;' class="mx-auto">

    <div class="page">
        <div class="row my-2" style="font-size: 12pt; font-family: calibri;">
            <div class="col-8">
                <span class="d-block"><b>UD. Sukses Mandiri</b></span>
                <p class="p-0 m-0" style="font-size: 11.5pt;">
                    Jln. Medan - Banda Aceh Meunasah Mesjid Cunda -
                </p>
                <p class="p-0 m-0" style="font-size: 11.5pt;">
                    Kota Lhokseumawe, Telp : 0822-7216-3053
                </p>
                <table class="w-75 mt-1" border="0">
                    <tbody>
                        <tr>
                            <td class="w-25">Kendaraan</td>
                            <td>: {{ $recapitulation->license_plate }}</td>
                        </tr>
                        <tr>
                            <td class="w-25">Supir</td>
                            <td>: {{ $recapitulation->driver }}</td>
                        </tr>
                        <tr>
                            <td class="w-25">Helper</td>
                            <td>: {{ $recapitulation->helper }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-4">
                <span class="d-block"><b>REKAPITULASI PER BARANG</b></span>
                <table class="w-100" border="0">
                    <tbody>
                        <tr>
                            <td class="">Kode Rekapitulasi</td>
                            <td>: {{ $recapitulation->recapitulation_code }}</td>
                        </tr>
                        <tr>
                            <td class="">Tanggal</td>
                            <td>: {{ $recapitulation->date->isoFormat('DD MMMM Y') }}</td>
                        </tr>
                        <tr>
                            <td class="">Jumlah Salesman</td>
                            <td>: {{ $recapitulation->salesman_count }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <table class="w-100" style='font-size: 12pt; font-family: calibri; border-spacing: 0;' border="1">
            <thead class="text-center">
                <tr>
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
                        <td>{{ $item->name }}</td>
                        <td class="text-center">
                            {{ $item->itemUnit->size->isLarge() ? $item->pivot->quantity : '' }}
                        </td>
                        <td class="text-center">
                            {{ $item->itemUnit->size->isMedium() ? $item->pivot->quantity : '' }}
                        </td>
                        <td class="text-center">
                            {{ $item->itemUnit->size->isSmall() ? $item->pivot->quantity : '' }}
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="row my-2" style="font-family: calibri;">
            <div class="col-6">
                <table class="w-100" border="0">
                    <tbody class="font-weight-bold text-md">
                        <tr>
                            <td class="w-25">Total Barang</td>
                            <td>: {{ $recapitulation->items->sum('pivot.quantity') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-4">
                <table class="w-100" border="0">
                    <tbody class="font-weight-bold text-md">
                        <tr>
                            <td class="">Total Faktur</td>
                            <td>: {{ $recapitulation->outgoing_transactions->count() }}</td>
                        </tr>
                        <tr>
                            <td class="">Total Bruto</td>
                            <td style="width:45%">
                                <div class="d-flex justify-content-between">
                                    <span>: Rp.</span>
                                    <span>{{ number_format($recapitulation->outgoing_transactions->sum('prices'), 0, ',', '.') }}</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Total Diskon</td>
                            <td style="width:45%">
                                <div class="d-flex justify-content-between">
                                    <span>: Rp.</span>
                                    <span>{{ number_format($recapitulation->outgoing_transactions->sum('discounts'), 0, ',', '.') }}</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="">Total Neto</td>
                            <td style="width:45%">
                                <div class="d-flex justify-content-between">
                                    <span>: Rp.</span>
                                    <span>{{ number_format($recapitulation->outgoing_transactions->sum('net_prices'), 0, ',', '.') }}</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div style="font-size: 11pt;" class="pt-3 row justify-content-end">
            <div class="col-4">
                <p class="text-sm">Diperiksa oleh,</p>
            </div>
            <div class="col-4">
                <p class="text-sm">Diterima oleh,</p>
            </div>
            <div class="col-4">
                <p class="text-sm">Diterima kembali,</p>
                <p><u></u></p>
            </div>
        </div>

    </div>

</body>

</html>

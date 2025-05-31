<div>
    @section('plugins.Select2', true)
    @section('content_header_title', 'Transaksi Barang Keluar')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('outgoing-transactions') }}">Transaksi Barang Keluar</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Transaksi</li>
        </ol>
    </nav>
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title m-1">Detail Transaksi</h3>
            <div class="card-tools">
                @if ($outgoingTransaction->status->isDraft())
                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                        data-target="#outgoing-transaction-edit">
                        <i class="mr-1 fas fa-edit"></i>Edit Transaksi
                    </button>
                @else
                    <a href="{{ route('bills.export', $outgoingTransaction->invoice_code) }}"
                        class="btn btn-sm btn-info" target="_blank">
                        <i class="mr-1 fas fa-print"></i>Cetak Bon
                    </a>
                @endif
                @if (!$outgoingTransaction->status->isDelivered() && !$outgoingTransaction->status->isCanceled())
                    @if ($outgoingTransaction->status->isReady())
                        <button class="btn btn-sm btn-success"
                            x-on:click.prevent="SwalDialog.fire().then((res) => {if (res.isConfirmed) $wire.deliver();});">
                            <i class="mr-1 fas fa-truck"></i>Berangkat
                        </button>
                    @endif
                    @if ($outgoingTransaction->status->isDelivering())
                        <button class="btn btn-sm btn-success"
                            x-on:click.prevent="SwalDialog.fire().then((res) => {if (res.isConfirmed) $wire.delivered();});">
                            <i class="mr-1 fas fa-truck-loading"></i>Barang Sampai
                        </button>
                    @endif
                    <button class="btn btn-sm btn-danger"
                        x-on:click.prevent="SwalDialog.fire({
                                    icon: `warning`,
                                    input: `text`,
                                    inputLabel: `Alasan pembatalan (Opsional):`,
                                    inputPlaceholder: `Alasan pembatalan (misal: Toko tutup) ...`,
                                    customClass: { confirmButton: `bg-danger` }
                                }).then((res) => {
                                    if (res.isConfirmed) {
                                        $wire.cancel(res.value);
                                    }
                                });">
                        <i class="mr-1 fas fa-ban"></i>Batal
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success !</strong> {{ session('status') }}.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-borderless text-nowrap">
                    <tbody class="font-weight-bold">
                        <tr>
                            <th scope="row" style="width:20%;">Tanggal Transaksi</th>
                            <td>: {{ $outgoingTransaction->date->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th scope="row" style="width:20%;">Kode Invoice</th>
                            <td>: {{ $outgoingTransaction->invoice_code }}</td>
                        </tr>
                        <tr>
                            <th scope="row" style="width:20%;">Toko</th>
                            <td>: {{ $outgoingTransaction->store->name }}</td>
                        </tr>
                        <tr>
                            <th scope="row" style="width:20%;">Sales</th>
                            <td>: {{ $outgoingTransaction->salesman->name }}</td>
                        </tr>
                        <tr>
                            <th scope="row" style="width:20%;">Total Transaksi</th>
                            <td>: Rp.
                                {{ number_format($outgoingTransaction->outgoing_items->sum('pivot.total_price'), 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" style="width:20%;">Diskon</th>
                            <td>: Rp.
                                {{ number_format($outgoingTransaction->outgoing_items->sum('pivot.discount'), 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" style="width:20%;">NETT</th>
                            <td>: Rp.
                                {{ number_format($outgoingTransaction->outgoing_items->sum('pivot.total_net_price'), 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" style="width:20%;">Pembayaran</th>
                            <td>: {{ $outgoingTransaction->payment_method->name }}</td>
                        </tr>
                        <tr>
                            <th scope="row" style="width:20%;">Status</th>
                            <td>
                                : {!! $outgoingTransaction->status->getLabelBadge() !!}
                                @if ($outgoingTransaction->status->isCanceled())
                                    <span class="badge badge-info">{{ $outgoingTransaction->cancel_reason }}</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title m-1">List Barang Keluar</h3>
            <div class="card-tools">
                @if ($outgoingTransaction->status->isDraft())
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                        data-target="#outgoing-item-modal">
                        <i class="mr-1 fas fa-plus"></i>{{ __('Add') }} Barang Keluar
                    </button>
                    <button type="button" class="btn btn-sm btn-success"
                        x-on:click.prevent="SwalDialog.fire({
                            title: `Apakah Anda yakin ?`,
                            icon: `info`,
                            html: `<p class='text-info'>Harap pastikan data transaksi sudah lengkap dan sesuai. Setelah proses ini, data transaksi tidak dapat diubah!</p>`,
                            customClass: {
                                confirmButton: 'bg-success',
                            },
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $wire.complete();
                            }
                        });">
                        <i class="mr-1 fas fa-check"></i>Selesai & Siap Diantar
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead class="text-nowrap">
                        <th scope="col">No.</th>
                        <th scope="col">Kode Barang</th>
                        <th scope="col">Nama Barang</th>
                        <th scope="col" style="width: 10%">Harga Satuan</th>
                        <th scope="col" class="text-right">Jumlah</th>
                        <th scope="col">Diskon</th>
                        <th scope="col">Total Harga</th>
                        @if ($outgoingTransaction->status->isDraft())
                            <th scope="col">Aksi</th>
                        @endif
                    </thead>
                    <tbody>
                        @foreach ($outgoingTransaction->outgoing_items as $outgoing_item)
                            <tr>
                                <th scope=row>{{ $loop->iteration }}</th>
                                <td>{{ $outgoing_item->code }}</td>
                                <td class="text-nowrap">{{ $outgoing_item->name }}</td>
                                <td>
                                    <div class="d-flex flex-nowrap justify-content-between">
                                        <span>Rp. </span>
                                        <span>
                                            {{ number_format($outgoing_item->pivot->unit_price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="text-right">{{ $outgoing_item->pivot->quantity }}</td>
                                <td>
                                    <div class="d-flex flex-nowrap justify-content-between font-weight-bold">
                                        <span>Rp. </span>
                                        <span>
                                            {{ number_format($outgoing_item->pivot->discount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-nowrap justify-content-between font-weight-bold">
                                        <span>Rp. </span>
                                        <span>
                                            {{ number_format($outgoing_item->pivot->total_price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </td>
                                @if ($outgoingTransaction->status->isDraft())
                                    <td>
                                        <a href="" class="btn btn-xs btn-danger"
                                            x-on:click.prevent="$deleteItem({{ $outgoing_item->id }}, '{{ $outgoing_item->name }}')">
                                            {{ __('Delete') }}
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add Item Modal Form --}}
    <div class="modal fade" id="outgoing-item-modal" wire:ignore.self data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Tambah Barang Keluar
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click="$dispatch('close-modal')">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent='store'
                        x-data='{ config: {allowClear: true,dropdownParent: $("#outgoing-item-modal"),theme: "bootstrap4"}, items: $wire.items, selectedItem: null, disabled:true }'>
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h5 class="alert-heading">Kesalahan validasi!</h5>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <div class="form-group" wire:ignore>
                            <label for="distributor_id">Distributor</label>
                            <div class="input-group">
                                <select id="distributor_id" name="distributor_id" wire:model.live='distributor_id'
                                    class="form-control" style="width: 100%"
                                    x-init='$($el).select2({
                                        ...config,
                                        placeholder: "Pilih Distributor ...",
                                    });
                                    $($el).on("change", function(e) {
                                        $("#item_id").val(null).trigger("change");
                                        $wire.set("distributor_id", e.target.value);
                                    });'>
                                    <option value=""></option>
                                    @foreach ($distributors as $distributor)
                                        <option value="{{ $distributor->id }}">{{ $distributor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group" wire:ignore>
                            <label for="item_id">Barang</label>
                            <div class="input-group">
                                <select id="item_id" name="item_id" class="form-control" style="width: 100%"
                                    wire:key='{{ $distributor_id }}' wire:model.live='item_id'
                                    x-init='$wire.on("items-updated", (event) => {
                                        items = event.items;
                                        if($wire.item_id){
                                            $($el).val(null).trigger("change");
                                        }
                                        // $($el).find("option").not(":first").remove();
                                    });
                                    $wire.on("selectedItem-updated", (event) => {
                                        selectedItem = JSON.parse(event.value);
                                        disabled = !selectedItem?.quantity > 0
                                    });
                                    $($el).select2({
                                        ...config,
                                        placeholder: "Pilih Barang ...",
                                    });
                                    $($el).on("change", function(e) {
                                        $wire.$set("item_id", e.target.value);
                                    });'>
                                    <option value="">Pilih Barang ...</option>
                                    <template x-for="item in items" :key="item.id">
                                        <option :value="item.id" x-text="item.name"></option>
                                    </template>
                                </select>
                                <template x-if="selectedItem">
                                    <small>Stok barang di gudang :
                                        <strong x-text="selectedItem.quantity"></strong>
                                    </small>
                                </template>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Jumlah Barang</label>
                            <div class="input-group">
                                <input id="quantity" name="quantity" class="form-control" type="number"
                                    placeholder="Jumlah Barang ..." wire:model="quantity" autocomplete="quantity"
                                    :disabled="disabled" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="discount">Diskon (Rp)</label>
                            <div class="input-group">
                                <input id="discount" name="discount" class="form-control" type="number"
                                    placeholder="Diskon (Rp) ..." wire:model="discount" autocomplete="discount"
                                    :disabled="disabled" />
                            </div>
                            <small>Ketik tanpa tanda titik (.) cth: 7000</small>
                        </div>

                        <div>
                            <button class="btn btn-warning" type="button" disabled wire:loading wire:target="store">
                                <span class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true"></span>
                                Loading...
                            </button>
                            <div class="float-right">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="mr-1 fas fa-plus"></i>{{ __('Add') }}
                                </button>
                                <button id="hapus" type="button" wire:click='hapus'
                                    x-on:click="$('#distributor_id').val(null).trigger('change');"
                                    class="btn btn-sm btn-danger">
                                    <i class="mr-1 fas fa-backspace"></i>
                                    {{ __('Delete') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default bg-secondary" data-dismiss="modal"
                        wire:click="$dispatch('close-modal')">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        $wire.on('close-modal', (event) => {
            $('#distributor_id').val(null).trigger('change');
            $('#outgoing-item-modal').modal('hide');
        });
        Alpine.magic('deleteItem', () => {
            return (id, name) => SwalDialog.fire({
                title: `Apakah Anda yakin ingin menghapus Barang "${name}" ?`,
                icon: `warning`,
                confirmButtonText: `Hapus !`,
                customClass: {
                    confirmButton: `bg-danger`
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.destroy(id)
                }
            })
        })
    </script>
@endscript

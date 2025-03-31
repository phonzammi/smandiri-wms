<div>
    @section('plugins.Select2', true)
    @section('content_header_title', 'Transaksi Barang Masuk')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('incoming-transactions') }}">Transaksi Barang Masuk</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Transaksi</li>
        </ol>
    </nav>
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title m-1">Detail Transaksi</h3>
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
                            <th scope="row" class="w-25">Tanggal Transaksi</th>
                            <td>: {{ $incomingTransaction->date->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="w-25">Kode Invoice</th>
                            <td>: {{ $incomingTransaction->invoice_code }}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="w-25">Kurir</th>
                            <td>: {{ $incomingTransaction->courier }}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="w-25">Distributor</th>
                            <td>: {{ $incomingTransaction->distributor }}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="w-25">Total Transaksi</th>
                            <td>: Rp.
                                {{ number_format($incomingTransaction->total_money, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title m-1">List Barang Masuk</h3>
            <div class="card-tools">
                @if (!$incomingTransaction->is_completed)
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                        data-target="#incoming-item-modal">
                        <i class="mr-1 fas fa-plus"></i>{{ __('Add') }} Barang Masuk
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
                        <i class="mr-1 fas fa-check"></i>Selesai
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
                        <th scope="col">Sub Total</th>
                        @if (!$incomingTransaction->is_completed)
                            <th scope="col">Aksi</th>
                        @endif
                    </thead>
                    <tbody>
                        @foreach ($incomingTransaction->incoming_items as $incoming_item)
                            <tr>
                                <th scope=row>{{ $loop->iteration }}</th>
                                <td>{{ $incoming_item->code }}</td>
                                <td class="text-nowrap">{{ $incoming_item->name }}</td>
                                <td class="d-flex flex-nowrap justify-content-between">
                                    <div>Rp. </div>
                                    <div>
                                        {{ number_format($incoming_item->price, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="text-right">{{ $incoming_item->pivot->quantity }}</td>
                                <td class="d-flex flex-nowrap justify-content-between font-weight-bold">
                                    <div>Rp. </div>
                                    <div>
                                        {{ number_format($incoming_item->pivot->total_price, 0, ',', '.') }}
                                    </div>
                                </td>
                                @if (!$incomingTransaction->is_completed)
                                    <td>
                                        <a href="" class="btn btn-xs btn-danger"
                                            x-on:click.prevent="$deleteItem({{ $incoming_item->id }}, '{{ $incoming_item->name }}')">
                                            {{ __('Delete') }}
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        <tr>
                            <th scope="row" colspan="5">Total</th>
                            <td class="d-flex flex-nowrap justify-content-between font-weight-bold">
                                <div>Rp. </div>
                                <div>
                                    {{ number_format($incomingTransaction->incoming_items->sum('pivot.total_price'), 0, ',', '.') }}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Form --}}
    <div class="modal fade" id="incoming-item-modal" wire:ignore.self data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Tambah Barang Masuk
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click="$dispatch('close-modal');">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    @error('is_completed')
                        <span class="alert alert-danger text-sm">{{ $message }}</span>
                    @enderror
                    <form wire:submit.prevent='store'
                        x-data='{ config: {allowClear: true,dropdownParent: $("#incoming-item-modal"),theme: "bootstrap4"}, items: $wire.items}'>
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
                                        placeholder: "Pilih Distributor ...",
                                        ...config
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
                                    });
                                    $($el).select2({
                                        placeholder: "Pilih Barang ...",
                                        ...config
                                    });
                                    $($el).on("change", function(e) {
                                        $wire.$set("item_id", e.target.value)
                                    })'>
                                    <option value="">Pilih Barang ...</option>
                                    <template x-for="item in items" :key="item.id">
                                        <option :value="item.id" x-text="item.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="quantity">Jumlah Barang</label>
                            <div class="input-group">
                                <input id="quantity" name="quantity" class="form-control" type="number"
                                    placeholder="Jumlah Barang ..." wire:model.blur="quantity"
                                    autocomplete="quantity" />
                            </div>
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
                        wire:click="$dispatch('close-modal');">
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
            $('#incoming-item-modal').modal('hide');
        });
        Alpine.magic('deleteItem', () => {
            return (id, name) => SwalDialog.fire({
                title: `Apakah Anda yakin ingin menghapus Barang ${name} ?`,
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

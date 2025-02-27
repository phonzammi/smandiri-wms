<div>
    @section('content_header_title', 'Transaksi Barang Masuk')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title m-1">Transaksi Barang Masuk</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                    data-target="#incoming-transaction-modal">
                    <i class="mr-1 fas fa-plus"></i>{{ __('Add') }}
                </button>
            </div>
        </div>
        <div class="card-body">
            <livewire:items.incoming-transactions.incoming-transaction-table />
        </div>
    </div>
    {{-- Modal Form --}}
    <div class="modal fade" id="incoming-transaction-modal" wire:ignore.self data-backdrop="static"
        data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Tambah Transaksi Barang Masuk
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click="$dispatch('close-modal')">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-info" role="alert">
                        Silahkan masukkan informasi transaksi terlebih dahulu, lalu klik
                        <span class="badge badge-primary text-md">Simpan & Tambah Barang</span>.
                    </div>
                    <form wire:submit.prevent='store'>
                        @csrf
                        <x-adminlte-input name="invoice_code" type="text" label="Kode Invoice"
                            placeholder="Kode Invoice ..." wire:model='invoice_code' autocomplete="invoice_code" />
                        <x-adminlte-input name="distributor" type="text" label="Distributor"
                            placeholder="Distributor ..." wire:model='distributor' autocomplete="distributor" />
                        <x-adminlte-input name="courier" type="text" label="Kurir" placeholder="Kurir ..."
                            wire:model='courier' autocomplete="courier" />
                        <x-adminlte-input name="date" type="date" label="Tanggal" placeholder="Tanggal ..."
                            wire:model='date' autocomplete="date" />

                        <div>
                            <button class="btn btn-warning" type="button" disabled wire:loading wire:target="store">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                            </button>
                            <div class="float-right">
                                <button type="submit" class="btn btn-sm btn-primary" wire:dirty.remove.attr='disabled'
                                    disabled>
                                    <i class="mr-1 fas fa-plus"></i>Simpan & Tambah Barang
                                </button>
                                <button id="hapus" type="button" wire:click='hapus' class="btn btn-sm btn-danger"
                                    wire:dirty.remove.attr='disabled' disabled>
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

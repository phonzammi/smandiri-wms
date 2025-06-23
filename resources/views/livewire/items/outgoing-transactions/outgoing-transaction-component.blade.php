<div>
    @section('plugins.Select2', true)
    @section('content_header_title', 'Transaksi Penjualan')
    <div class="card card-primary" x-on:close-modal.window="$('#store_id').val(null).trigger('change');">
        <div class="card-header">
            <h3 class="card-title m-1">Transaksi Penjualan</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                    data-target="#outgoing-transaction-modal">
                    <i class="mr-1 fas fa-plus"></i>{{ __('Add') }}
                </button>
            </div>
        </div>
        <div class="card-body">
            <livewire:items.outgoing-transactions.outgoing-transaction-table />
        </div>
    </div>

    {{-- Modal Form --}}
    <div class="modal fade" id="outgoing-transaction-modal" wire:ignore.self data-backdrop="static"
        data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Tambah Transaksi Barang Keluar
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
                    <form wire:submit.prevent='store' x-data="{ hasStoreIdError: $wire.entangle('hasStoreIdError') }">
                        @csrf
                        <x-adminlte-input name="invoice_code" type="text" label="Kode Invoice"
                            placeholder="Di Generate Otomatis Oleh Sistem ..." autocomplete="invoice_code" readonly />
                        <x-adminlte-input name="date" type="date" label="Tanggal Keluar"
                            placeholder="Tanggal Keluar ..." wire:model='date' autocomplete="date" />
                        <x-adminlte-select label="Sales" name="salesman_id" wire:model="salesman_id" required>
                            <option value="" class="text-muted">Pilih Sales ...</option>
                            @foreach ($salesmen as $salesman)
                                <option value="{{ $salesman->id }}">{{ $salesman->name }}</option>
                            @endforeach
                        </x-adminlte-select>

                        <div class="form-group" wire:ignore :class="hasStoreIdError && 'mb-0'">
                            <label for="store_id">Toko</label>
                            <div class="input-group"
                                :class="hasStoreIdError && 'adminlte-invalid-igroup is-invalid has-error'">
                                <select id="store_id" name="store_id" class="form-control" wire:model.live='store_id'
                                    style="width: 100%" x-init="$($el).select2({
                                        placeholder: 'Pilih Toko ...',
                                        allowClear: true,
                                        dropdownParent: $('#outgoing-transaction-modal'),
                                        theme: 'bootstrap4'
                                    });
                                    $($el).on('change', function(e) {
                                        $wire.$set('store_id', e.target.value, false);
                                    });">
                                    <option value=""></option>
                                    @foreach ($stores as $store)
                                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @error('store_id')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <x-adminlte-select label="Pembayaran" id="payment_method" name="payment_method"
                            wire:model="payment_method" required>
                            <option value="" class="text-muted">Pembayaran ...</option>
                            @foreach ($payment_methods as $key => $payment_method)
                                <option value="{{ $key }}">{{ $payment_method }}</option>
                            @endforeach
                        </x-adminlte-select>
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
                                    wire:dirty.remove.attr='disabled' disabled
                                    x-on:click="$('#store_id').val(null).trigger('change');">
                                    <i class="mr-1 fas fa-backspace"></i>
                                    {{ __('Delete') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

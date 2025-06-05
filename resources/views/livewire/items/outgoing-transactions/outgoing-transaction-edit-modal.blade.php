<div>
    <div class="modal fade" id="outgoing-transaction-edit-modal" wire:ignore.self data-backdrop="static"
        data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Edit Transaksi Barang Keluar
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click="$dispatch('close-modal')">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form wire:submit.prevent='save'>
                        @csrf
                        <x-adminlte-input name="invoice_code" type="text" label="Kode Invoice"
                            placeholder="{{ $invoice_code }}" autocomplete="invoice_code" readonly />
                        <x-adminlte-input name="date" type="date" label="Tanggal Keluar"
                            placeholder="Tanggal Keluar ..." wire:model='date' autocomplete="date" />
                        <x-adminlte-select label="Sales" name="salesman_id" wire:model="salesman_id" required>
                            <option value="" class="text-muted">Pilih Sales ...</option>
                            @foreach ($salesmen as $salesman)
                                <option value="{{ $salesman->id }}">{{ $salesman->name }}</option>
                            @endforeach
                        </x-adminlte-select>

                        <x-adminlte-select label="Toko" name="store_id" wire:model="store_id" required>
                            <option value="" class="text-muted">Pilih Toko ...</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </x-adminlte-select>

                        <x-adminlte-select label="Pembayaran" id="payment_method" name="payment_method"
                            wire:model="payment_method" required>
                            <option value="" class="text-muted">Pembayaran ...</option>
                            @foreach ($payment_methods as $key => $payment_method)
                                <option value="{{ $key }}">{{ $payment_method }}</option>
                            @endforeach
                        </x-adminlte-select>
                        <div>
                            <button class="btn btn-warning" type="button" disabled wire:loading wire:target="save">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                            </button>
                            <div class="float-right">
                                <button type="submit" class="btn btn-sm btn-primary" wire:dirty.remove.attr='disabled'
                                    disabled>
                                    <i class="mr-1 fas fa-plus"></i>{{ __('Save') }}
                                </button>
                                <button type="button" wire:click='cancel' data-dismiss="modal"
                                    class="btn btn-sm btn-danger">
                                    <i class="mr-1 fas fa-ban"></i>{{ __('Cancel') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

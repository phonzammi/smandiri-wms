<div>
    @section('content_header_title', 'Rekapitulasi Per Barang')
    <div class="card card-primary" x-on:close-modal.window="">
        <div class="card-header">
            <h3 class="card-title m-1">Rekapitulasi Per Barang</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                    data-target="#recapitulation-modal">
                    <i class="mr-1 fas fa-plus"></i>{{ __('Add') }}
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-light" role="alert">
                <strong>Catatan:</strong> Hanya transaksi dengan status <a
                    href="{{ route('outgoing-transactions', ['table-filters' => ['status' => [0 => 'ready']]]) }}"
                    class="text-decoration-none badge badge-pill badge-info" target="_blank">Siap
                    Direkap/Diantar</a> yang akan masuk ke dalam rekap.
            </div>
            <livewire:items.recapitulations.recapitulation-table />
        </div>
    </div>

    {{-- Modal Form --}}
    <div class="modal fade" id="recapitulation-modal" wire:ignore.self data-backdrop="static" data-keyboard="false"
        x-on:recap-ok.window="$('#recapitulation-modal').modal('hide')">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Tambah Rekapitulasi
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click="$dispatch('close-modal')">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form wire:submit.prevent='store'>
                        @csrf
                        <x-adminlte-input name="recapitulation_code" type="text" label="No. Rekapitulasi"
                            placeholder="Di Generate Otomatis Oleh Sistem ..." autocomplete="recapitulation_code"
                            required readonly />

                        <x-adminlte-input name="date" type="date" label="Tanggal Transaksi"
                            placeholder="Tanggal Transaksi ..." wire:model='date' autocomplete="date" required>
                            <x-slot name="bottomSlot">
                                <span class="text-sm text-gray">
                                    Hanya transaksi dengan status <a
                                        href="{{ route('outgoing-transactions', ['table-filters' => ['status' => [0 => 'ready']]]) }}"
                                        class="text-decoration-none badge badge-pill badge-info" target="_blank">Siap
                                        Direkap/Diantar</a> yang direkap.
                                </span>
                            </x-slot>
                        </x-adminlte-input>

                        <x-adminlte-input name="driver" type="text" label="Driver" placeholder="Driver ..."
                            wire:model='driver' autocomplete="driver" required />

                        <x-adminlte-input name="helper" type="text" label="Helper" placeholder="Helper ..."
                            wire:model='helper' autocomplete="helper" />

                        <x-adminlte-input name="license_plate" type="text" label="No. Kendaraan"
                            placeholder="No. Kendaraan ..." wire:model='license_plate' autocomplete="license_plate"
                            required />
                        <div>
                            <button class="btn btn-warning" type="button" disabled wire:loading
                                wire:target="store,update">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                            </button>
                            <div class="float-right">
                                <button type="submit" class="btn btn-sm btn-primary" wire:dirty.remove.attr='disabled'
                                    disabled>
                                    <i class="mr-1 fas fa-plus"></i>{{ $is_edit ? __('Save') : __('Add') }}
                                </button>
                                @if ($is_edit)
                                    <button id="batal" type="button" wire:click='hapus' data-dismiss="modal"
                                        class="btn btn-sm btn-danger">
                                        <i class="mr-1 fas fa-ban"></i>{{ __('Cancel') }}
                                    </button>
                                @else
                                    <button id="hapus" type="button" wire:click='hapus'
                                        class="btn btn-sm btn-danger" wire:dirty.remove.attr='disabled' disabled>
                                        <i class="mr-1 fas fa-backspace"></i>
                                        {{ __('Delete') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

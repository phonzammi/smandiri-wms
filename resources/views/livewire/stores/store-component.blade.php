<div>
    @section('content_header_title', 'Toko')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title m-1">Data Toko</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#toko-modal">
                    <i class="mr-1 fas fa-plus"></i>{{ __('Add') }}
                </button>
            </div>
        </div>
        <div class="card-body">
            <livewire:stores.store-table />
        </div>
    </div>

    {{-- Modal Form --}}
    <div class="modal fade" id="toko-modal" wire:ignore.self data-backdrop="static" data-keyboard="false"
        x-on:toko-ok.window="$('#toko-modal').modal('hide')">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        {{ $form_title }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click="$dispatch('close-modal')">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form wire:submit.prevent={{ $is_edit ? 'update' : 'store' }}>
                        @csrf
                        <x-adminlte-input name="name" type="text" label="Nama Toko" placeholder="Nama Toko ..."
                            wire:model='name' autocomplete="name" required />
                        <x-adminlte-input name="phone" type="text" label="No. Hp" placeholder="No. Hp ..."
                            wire:model='phone' autocomplete="phone" required />
                        <x-adminlte-input name="address" type="text" label="Alamat" placeholder="Alamat ..."
                            wire:model='address' autocomplete="address" required />

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

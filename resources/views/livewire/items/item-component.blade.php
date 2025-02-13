<div>
    @section('content_header_title', 'Barang')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title m-1">Stok Barang</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#item-modal"
                    wire:click="$dispatch('open-item-modal')">
                    <i class="mr-1 fas fa-plus"></i>{{ __('Add') }}
                </button>
            </div>
        </div>
        <div class="card-body">
            <livewire:items.item-table />
        </div>
    </div>

    {{-- Modal Form --}}
    <div class="modal fade" id="item-modal" wire:ignore.self data-backdrop="static" data-keyboard="false"
        x-on:item-ok.window="$('#item-modal').modal('hide')">
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
                        <x-adminlte-input name="code" type="text" label="Kode Barang"
                            placeholder="Kode Barang ..." wire:model='code' autocomplete="code" required readonly />
                        <x-adminlte-input name="name" type="text" label="Nama Barang"
                            placeholder="Nama Barang ..." wire:model='name' autocomplete="name" required />
                        <x-adminlte-select label="Distributor" name="distributor_id" wire:model="distributor_id"
                            required>
                            <option value="" class="text-muted">Pilih Distributor ...</option>
                            @foreach ($distributors as $distributor)
                                <option value="{{ $distributor->id }}">{{ $distributor->name }}</option>
                            @endforeach
                        </x-adminlte-select>
                        <x-adminlte-select label="Satuan" name="item_unit_id" wire:model="item_unit_id" required>
                            <option value="" class="text-muted">Pilih Satuan ...</option>
                            @foreach ($item_units as $item_unit)
                                <option value="{{ $item_unit->id }}">{{ $item_unit->name }}</option>
                            @endforeach
                        </x-adminlte-select>
                        <x-adminlte-input name="price" type="number" label="Harga Satuan"
                            placeholder="Harga Satuan ..." wire:model='price' autocomplete="price" required />
                        <x-adminlte-input name="quantity" type="number" label="Jumlah Barang"
                            placeholder="Jumlah Barang ..." wire:model='quantity' autocomplete="quantity" required />

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

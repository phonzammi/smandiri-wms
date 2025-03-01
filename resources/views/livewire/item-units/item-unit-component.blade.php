<div>
    @section('content_header_title', 'Satuan')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title m-1">Data Satuan</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#itemUnit-modal">
                    <i class="mr-1 fas fa-plus"></i>{{ __('Add') }}
                </button>
            </div>
        </div>
        <div class="card-body">
            <livewire:item-units.item-unit-table />
        </div>
    </div>

    {{-- Modal Form --}}
    <div class="modal fade" id="itemUnit-modal" wire:ignore.self data-backdrop="static" data-keyboard="false"
        x-on:satuan-ok.window="$('#itemUnit-modal').modal('hide')">
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
                        <x-adminlte-input name="name" type="text" label="Nama Lengkap"
                            placeholder="Nama Lengkap ..." wire:model='name' autofocus autocomplete="name" required />

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

@script
    <script>
        Alpine.magic('deleteItemUnit', () => {
            return (id, name) => SwalDialog.fire({
                title: `Apakah Anda yakin ingin menghapus satuan ${name} ?`,
                icon: `warning`,
                confirmButtonText: `Hapus !`,
                customClass: {
                    confirmButton: `btn btn-danger`
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.destroy(id)
                }
            })
        })
    </script>
@endscript

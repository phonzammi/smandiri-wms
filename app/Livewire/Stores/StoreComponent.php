<?php

namespace App\Livewire\Stores;

use App\Models\Store;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;

class StoreComponent extends Component
{
    public Store $toko;
    public string $name, $phone, $address = '';
    public string $form_title = 'Tambah Toko';
    public bool $is_edit = false;

    public function store()
    {
        $validated = $this->validate();
        Store::create($validated);
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => 'Toko baru berhasil ditambahkan',
        ]);
        $this->dispatch('refreshDatatable');
        $this->dispatch('toko-ok');
        $this->reset();
    }

    public function edit(Store $toko)
    {
        $this->toko = $toko;
        $this->is_edit = true;
        $this->form_title = "Edit Toko : $toko->name";
        $this->name = $toko->name;
        $this->phone = $toko->phone;
        $this->address = $toko->address;
    }

    public function update()
    {
        $validated = $this->validate();
        $this->toko->update($validated);
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => "Toko {$this->toko->name} berhasil diperbarui",
        ]);
        $this->dispatch('refreshDatatable');
        $this->dispatch('toko-ok');
        $this->reset();
    }

    public function destroy(Store $toko)
    {
        $toko->delete();
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => "Toko {$toko->name} berhasil dihapus",
        ]);
        $this->dispatch('refreshDatatable');
    }

    #[On('close-modal')]
    public function hapus()
    {
        $this->resetValidation();
        $this->reset();
    }

    public function rules()
    {
        return [
            'name' => ['required'],
            'phone' => [
                'required',
                'numeric',
                Rule::unique(table: 'stores')->ignore($this->is_edit ? $this->toko->id : null),
            ],
            'address' => ['required']
        ];
    }

    public function updated($fields)
    {
        $this->name = str($this->name)->squish();
        $this->phone = str($this->phone)->replaceMatches('/[^0-9,+]/', '');
        $this->address = str($this->address)->squish();
        $this->validateOnly($fields);
    }

    public function render()
    {
        return view('livewire.stores.store-component');
    }
}

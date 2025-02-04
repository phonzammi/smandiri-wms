<?php

namespace App\Livewire\Distributors;

use App\Models\Distributor;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;

class DistributorComponent extends Component
{
    public Distributor $distributor;
    public string $name = '';
    public string $form_title = 'Tambah Distributor';
    public bool $is_edit = false;

    public function store()
    {
        $validated = $this->validate();
        Distributor::create($validated);
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => 'Distributor baru berhasil ditambahkan',
        ]);
        $this->dispatch('refreshDatatable');
        $this->dispatch('distributor-ok');
        $this->reset();
    }

    public function edit(Distributor $distributor)
    {
        $this->distributor = $distributor;
        $this->is_edit = true;
        $this->form_title = "Edit Distributor : $distributor->name";
        $this->name = $distributor->name;
    }

    public function update()
    {
        $validated = $this->validate();
        $this->distributor->update($validated);
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => "Distributor {$this->distributor->name} berhasil diperbarui",
        ]);
        $this->dispatch('refreshDatatable');
        $this->dispatch('distributor-ok');
        $this->reset();
    }

    public function destroy(Distributor $distributor)
    {
        $distributor->delete();
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => "Distributor {$distributor->name} berhasil dihapus",
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
            'name' => [
                'required',
                'uppercase',
                Rule::unique('distributors')->ignore($this->is_edit ? $this->distributor->id : null),
            ],
        ];
    }

    public function updated($fields)
    {
        $this->name = str($this->name)->upper();
        $this->validateOnly($fields);
    }
    public function render()
    {
        return view('livewire.distributors.distributor-component');
    }
}

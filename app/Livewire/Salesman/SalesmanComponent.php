<?php

namespace App\Livewire\Salesman;

use App\Models\Salesman;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;

class SalesmanComponent extends Component
{
    public Salesman $salesman;
    public string $name, $phone, $address = '';
    public string $form_title = 'Tambah Sales';
    public bool $is_edit = false;

    public function store()
    {
        $validated = $this->validate();
        Salesman::create($validated);
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => 'Sales baru berhasil ditambahkan',
        ]);
        $this->dispatch('refreshDatatable');
        $this->dispatch('salesman-ok');
        $this->reset();
    }

    public function edit(Salesman $salesman)
    {
        $this->salesman = $salesman;
        $this->is_edit = true;
        $this->form_title = "Edit Sales : $salesman->name";
        $this->name = $salesman->name;
        $this->phone = $salesman->phone;
        $this->address = $salesman->address;
    }

    public function update()
    {
        $validated = $this->validate();
        $this->salesman->update($validated);
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => "Sales {$this->salesman->name} berhasil diperbarui",
        ]);
        $this->dispatch('refreshDatatable');
        $this->dispatch('salesman-ok');
        $this->reset();
    }

    public function destroy(Salesman $salesman)
    {
        $salesman->delete();
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => "Sales {$salesman->name} berhasil dihapus",
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
                Rule::unique('salesmen')->ignore($this->is_edit ? $this->salesman->id : null),
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
        return view('livewire.salesman.salesman-component');
    }
}

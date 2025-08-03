<?php

namespace App\Livewire\ItemUnits;

use App\Enums\ItemUnitSize;
use App\Models\ItemUnit;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;

class ItemUnitComponent extends Component
{
    public ItemUnit $itemUnit;
    public string $name, $size = '';
    public string $form_title = 'Tambah Satuan';
    public bool $is_edit = false;

    public function store()
    {
        $validated = $this->validate();
        ItemUnit::create($validated);
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => 'Satuan baru berhasil ditambahkan',
        ]);
        $this->dispatch('refreshDatatable');
        $this->dispatch('satuan-ok');
        $this->reset();
    }

    public function edit(ItemUnit $itemUnit)
    {
        $this->itemUnit = $itemUnit;
        $this->is_edit = true;
        $this->form_title = "Edit Satuan : $itemUnit->name";
        $this->name = $itemUnit->name;
        $this->size = $itemUnit->size->value;
    }

    public function update()
    {
        $validated = $this->validate();
        $this->itemUnit->update($validated);
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => "Satuan {$this->itemUnit->name} berhasil diperbarui",
        ]);
        $this->dispatch('refreshDatatable');
        $this->dispatch('satuan-ok');
        $this->reset();
    }

    public function destroy(ItemUnit $itemUnit)
    {
        $itemUnit->delete();
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => "Satuan {$itemUnit->name} berhasil dihapus",
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
                Rule::unique('item_units')->ignore($this->is_edit ? $this->itemUnit->id : null),
            ],
            'size' => ['required', Rule::enum(ItemUnitSize::class)]
        ];
    }

    public function updated($fields)
    {
        $this->name = str($this->name)->upper();
        $this->validateOnly($fields);
    }

    public function render()
    {
        $sizes = collect(ItemUnitSize::cases())->mapWithKeys(fn($status) => [$status->value => $status->getLabelText()])->all();
        return view('livewire.item-units.item-unit-component', compact('sizes'));
    }
}

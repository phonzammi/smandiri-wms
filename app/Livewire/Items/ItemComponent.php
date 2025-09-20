<?php

namespace App\Livewire\Items;

use App\Models\Distributor;
use App\Models\Item;
use App\Models\ItemUnit;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class ItemComponent extends Component
{
    public Item $item;
    public $form_title = "Tambah Barang";
    public bool $is_edit = false;
    public string $name, $code, $price;
    public int $distributor_id, $item_unit_id, $quantity;

    public function store()
    {
        $validated = $this->validate();
        Item::create($validated);
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => 'Barang baru berhasil ditambahkan',
        ]);
        $this->dispatch('refreshDatatable');
        $this->dispatch('item-ok');
        $this->reset();
    }

    public function edit(Item $item)
    {
        $this->item = $item;
        $this->is_edit = true;
        $this->form_title = "Edit Barang : $item->name";
        $this->code = $item->code;
        $this->name = $item->name;
        $this->distributor_id = $item->distributor_id;
        $this->item_unit_id = $item->item_unit_id;
        $this->price = $item->price;
        $this->quantity = $item->quantity;
    }

    public function update()
    {
        $validated = $this->validate();
        $this->item->update($validated);
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => "Barang {$this->item->name} berhasil diperbarui",
        ]);
        $this->dispatch('refreshDatatable');
        $this->dispatch('item-ok');
        $this->reset();
    }

    public function destroy(Item $item)
    {
        $item->delete();
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => "Barang {$item->name} berhasil dihapus",
        ]);
        $this->dispatch('refreshDatatable');
    }

    public function rules()
    {
        return [
            'code' => [
                'required',
                Rule::unique('items')->ignore($this->is_edit ? $this->item->id : null),
            ],
            'name' => ['required'],
            'distributor_id' => ['required', 'integer', 'exists:distributors,id'],
            'item_unit_id' => ['required', 'integer', 'exists:item_units,id'],
            'price' => ['required', 'numeric'],
            'quantity' => ['required', 'numeric'],
        ];
    }

    public function validationAttributes()
    {
        return [
            "code" => "Kode Barang",
            "nama" => "Nama Barang",
            "distributor_id" => "Distributor",
            "item_unit_id" => "Satuan Barang",
            "price" => "Harga Satuan",
            "quantity" => "Jumlah Barang",
        ];
    }

    public function updated($fields)
    {
        $this->code = str($this->code)->squish()->upper();
        $this->name = str($this->name)->squish();
        $this->price = str($this->price)->replace('.', '');
        $this->validateOnly($fields);
    }

    private function generateCode()
    {
        $index = 100000;
        $last_item = Item::withTrashed()->latest('id')->first();
        $new_code_index = $last_item ? $index + $last_item->id + 1 : $index + 1;
        $this->code = str("SMBRG{$new_code_index}")->replace("G1", "G0");
    }

    #[On('open-item-modal')]
    public function openModal()
    {
        $this->generateCode();
    }

    #[On('close-modal')]
    public function hapus()
    {
        $this->resetValidation();
        $this->reset();
    }

    public function render()
    {
        $item_units = ItemUnit::select(["id", "name"])->orderBy('name')->get();
        $distributors = Distributor::select(["id", "name"])->orderBy('name')->get();
        return view('livewire.items.item-component', compact('item_units', 'distributors'));
    }
}

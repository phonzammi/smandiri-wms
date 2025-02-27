<?php

namespace App\Livewire\Items\IncomingTransactions;

use App\Models\Distributor;
use App\Models\IncomingTransaction;
use App\Models\Item;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowIncomingTransaction extends Component
{
    public IncomingTransaction $incomingTransaction;
    public int $distributor_id, $item_id, $total_quantity, $total_price;
    public $distributors;
    public $items;
    public ?Item $selectedItem = null;

    public function mount()
    {
        $this->incomingTransaction->loadMissing(['incoming_items']);
        $this->distributors = Distributor::select(["id", "name"])->orderBy('name')->get();
    }

    public function store()
    {
        if ($this->incomingTransaction->is_completed) {
            $this->dispatch('toast-message', props: [
                'icon' => 'error',
                'text' => "Transaksi Barang Masuk sudah selesai! Tidak dapat menambah Barang Masuk.",
            ]);
            return;
        }
        $validated = $this->validate();
        $this->total_price = $this->selectedItem->price * $validated['total_quantity'];
        $this->selectedItem->quantity += $validated['total_quantity'];
        if ($this->selectedItem->save()) {
            $this->incomingTransaction->incoming_items()->attach($this->selectedItem->id, [
                'total_quantity' => $validated['total_quantity'],
                'total_price' => $this->total_price
            ]);
            $this->syncTotals();
        }
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => 'Barang Masuk berhasil ditambahkan',
        ]);
        $this->dispatch('incoming-item-ok');
        $this->hapus();
    }

    public function complete()
    {
        if ($this->incomingTransaction->is_completed) {
            $this->dispatch('toast-message', props: [
                'icon' => 'info',
                'text' => "Transaksi Barang Masuk sudah selesai!",
            ]);
            return;
        }
        $this->incomingTransaction->is_completed = true;
        if ($this->incomingTransaction->save()) {
            $this->dispatch('toast-message', props: [
                'icon' => 'success',
                'text' => 'Transaksi Barang Masuk Selesai',
            ]);
            $this->syncTotals();
        }
    }

    public function destroy(int $item_id)
    {
        if ($this->incomingTransaction->is_completed) {
            $this->dispatch('toast-message', props: [
                'icon' => 'error',
                'text' => "Transaksi Barang Masuk sudah selesai! Tidak dapat menghapus Barang Masuk.",
            ]);
            return;
        }
        $item = Item::find($item_id);
        $incoming_item = $this->incomingTransaction->incoming_items->find($item_id);

        if ($incoming_item && $this->incomingTransaction->incoming_items()->detach($item_id)) {
            $item->quantity -= $incoming_item->pivot->total_quantity;
            $item->save();
            $this->syncTotals();
        }
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => "Barang $item->name berhasil Dihapus",
        ]);
        $this->dispatch('refresh');
    }

    public function rules()
    {
        return [
            'distributor_id' => ['required', 'integer', 'exists:distributors,id'],
            'item_id' => ['required', 'integer', 'exists:items,id', 'unique:incoming_transaction_item,item_id'],
            'total_quantity' => ['required', 'numeric']
        ];
    }

    public function validationAttributes()
    {
        return [
            "distributor_id" => "Distributor",
            "item_id" => "Barang",
            "total_quantity" => "Jumlah Barang",
        ];
    }

    public function updatedDistributorId()
    {
        $this->resetValidation();
        $this->items = isset($this->distributor_id) && $this->distributor_id ? Item::where("distributor_id", $this->distributor_id)->orderBy("name")->get() : null;
    }

    public function updatedItemId()
    {
        $this->selectedItem = isset($this->item_id) && $this->item_id ? Item::find($this->item_id) : null;
    }

    #[On('refresh')]
    public function refresh()
    {
        return;
    }

    #[On('close-modal')]
    public function hapus()
    {
        $this->resetValidation();
        $this->reset(['distributor_id', 'item_id', 'total_quantity', 'total_price', 'items', 'selectedItem']);
    }

    private function syncTotals()
    {
        $this->incomingTransaction->total_quantity = $this->incomingTransaction->incoming_items->sum('pivot.total_quantity');
        $this->incomingTransaction->total_money = $this->incomingTransaction->incoming_items->sum('pivot.total_price');
        $this->incomingTransaction->save();
    }

    public function render()
    {
        return view('livewire.items.incoming-transactions.show-incoming-transaction');
    }
}

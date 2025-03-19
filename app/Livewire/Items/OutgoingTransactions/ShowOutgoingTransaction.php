<?php

namespace App\Livewire\Items\OutgoingTransactions;

use App\Enums\OutgoingTransactionStatus;
use App\Models\Distributor;
use App\Models\Item;
use App\Models\OutgoingTransaction;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Validation\Rule;

class ShowOutgoingTransaction extends Component
{
    public OutgoingTransaction $outgoingTransaction;
    public Collection $distributors;
    public ?array $items = [];
    public ?Item $selectedItem = null;
    public int $selectedItemQuantity = 0;
    public int $distributor_id, $item_id, $quantity, $unit_price, $total_price, $total_net_price;
    public int $discount = 0;

    public function mount()
    {
        $this->outgoingTransaction->loadMissing(['outgoing_items', 'salesman', 'store']);
        $this->distributors = Distributor::select(["id", "name"])->get();
    }

    public function store()
    {
        $validated = $this->validate();
        $total_price = $this->selectedItem->price * $validated['quantity'];
        $total_net_price = $total_price - $validated['discount'];

        $this->selectedItem->quantity -= $validated['quantity'];
        if ($this->selectedItem->save()) {
            $this->outgoingTransaction->outgoing_items()->attach($this->selectedItem->id, [
                'quantity' => $validated['quantity'],
                'unit_price' => $this->selectedItem->price,
                'discount' => $validated['discount'],
                'total_price' => $total_price,
                'total_net_price' => $total_net_price,
            ]);
        }
        // $this->syncTotals();
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => 'Barang Keluar berhasil ditambahkan',
        ]);
        $this->dispatch('close-modal');
        $this->dispatch('refresh');
    }

    public function complete()
    {
        if (!$this->outgoingTransaction->status->isDraft()) {
            $this->dispatch('toast-message', props: [
                'icon' => 'info',
                'text' => "Pengisian data Transaksi Barang Keluar sudah selesai!",
            ]);
            return;
        }
        $this->outgoingTransaction->update(['status' => OutgoingTransactionStatus::READY]);
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => 'Pengisian Data Transaksi Barang Keluar Selesai !',
        ]);
        $this->dispatch('refresh');
    }

    public function destroy(Item $item)
    {
        if (!$this->outgoingTransaction->status->isDraft()) {
            $this->dispatch('toast-message', props: [
                'icon' => 'error',
                'text' => "Pengisian data Transaksi Barang Keluar sudah selesai! Tidak dapat menghapus Barang Keluar.",
            ]);
            return;
        }
        $outgoing_item = $this->outgoingTransaction->outgoing_items->find($item->id);
        if ($outgoing_item && $this->outgoingTransaction->outgoing_items()->detach($item->id)) {
            $item->quantity += $outgoing_item->pivot->quantity;
            $item->save();
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
            'item_id' => [
                'required',
                'integer',
                'exists:items,id',
                Rule::unique('outgoing_transaction_item', 'item_id')->where('outgoing_transaction_id', $this->outgoingTransaction->id),
            ],
            'quantity' => ['required', 'numeric', "max:$this->selectedItemQuantity"],
            'discount' => ['nullable', 'numeric']
        ];
    }

    public function validationAttributes()
    {
        return [
            "distributor_id" => "Distributor",
            "item_id" => "Barang",
            "quantity" => "Jumlah Barang",
            "discount" => "Diskon"
        ];
    }

    public function updatedDistributorId()
    {
        $this->resetValidation();
        $this->items = isset($this->distributor_id) && $this->distributor_id ? Item::select(["id", "distributor_id", "name"])->where("distributor_id", $this->distributor_id)->get()?->toArray() : [];
        $this->dispatch('items-updated', items: $this->items);
    }

    public function updatedItemId()
    {
        $this->selectedItem = isset($this->item_id) && $this->item_id ? Item::select(['id', 'quantity', 'price'])->where('id', $this->item_id)->first() : null;
        $this->selectedItemQuantity = $this->selectedItem?->quantity ?: 0;
        $this->dispatch('selectedItem-updated', value: $this->selectedItem?->toJson());
    }

    private function syncTotals()
    {
        $this->outgoingTransaction->update([
            'quantities' => $this->outgoingTransaction->outgoing_items->sum('pivot.quantity'),
            'prices' => $this->outgoingTransaction->outgoing_items->sum('pivot.total_price'),
            'discounts' => $this->outgoingTransaction->outgoing_items->sum('pivot.discount'),
            'net_prices' => $this->outgoingTransaction->outgoing_items->sum('pivot.total_net_price'),
        ]);
    }

    #[On('close-modal')]
    public function hapus()
    {
        $this->resetValidation();
        $this->reset(['distributor_id', 'item_id', 'quantity', 'unit_price', 'total_price', 'discount', 'total_net_price', 'items', 'selectedItem', 'selectedItemQuantity']);
        $this->dispatch('selectedItem-updated', value: null);
    }

    #[On('refresh')]
    public function refresh()
    {
        $this->syncTotals();
        return;
    }

    public function render()
    {
        return view('livewire.items.outgoing-transactions.show-outgoing-transaction');
    }
}

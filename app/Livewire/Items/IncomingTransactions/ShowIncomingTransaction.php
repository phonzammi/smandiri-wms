<?php

namespace App\Livewire\Items\IncomingTransactions;

use App\Models\Distributor;
use App\Models\IncomingTransaction;
use App\Models\Item;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Validation\Rule;

class ShowIncomingTransaction extends Component
{
    public IncomingTransaction $incomingTransaction;
    public Collection $distributors;
    public int $quantity, $total_price;
    public ?int $distributor_id = null;
    public ?int $item_id = null;
    public array $items = [];
    public ?Item $selectedItem = null;

    public function mount()
    {
        $this->incomingTransaction->loadMissing(['incoming_items']);
        $this->distributors = Distributor::select(["id", "name"])->orderBy('name')->get();
        $this->items = Item::select(["id", "distributor_id", "name"])->get()->toArray();
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

        $quantity = $validated["quantity"];
        $total_price = $this->selectedItem->price * $quantity;
        $this->selectedItem->quantity += $quantity;

        DB::transaction(function () use ($total_price, $quantity) {
            $this->selectedItem->save();
            $this->incomingTransaction->incoming_items()->attach($this->selectedItem->id, [
                'quantity' => $quantity,
                'total_price' => $total_price
            ]);
        });

        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => 'Barang Masuk berhasil ditambahkan',
        ]);
        $this->dispatch('close-modal');
        $this->dispatch('refresh');
    }

    public function complete()
    {
        if ($this->incomingTransaction->is_completed) {
            $this->dispatch('toast-message', props: [
                'icon' => 'info',
                'text' => "Pengisian data Transaksi Barang Masuk sudah selesai!",
            ]);
            return;
        }
        $this->incomingTransaction->update(['is_completed' => true]);
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => 'Pengisian Data Transaksi Barang Masuk selesai!',
        ]);
        $this->dispatch('refresh');
    }

    public function destroy(Item $item)
    {
        if ($this->incomingTransaction->is_completed) {
            $this->dispatch('toast-message', props: [
                'icon' => 'error',
                'text' => "Pengisian data Transaksi Barang Masuk sudah selesai! Tidak dapat menghapus Barang Masuk.",
            ]);
            return;
        }

        $incoming_item = $this->incomingTransaction->incoming_items->find($item->id);
        DB::transaction(function () use ($incoming_item, $item) {
            if ($incoming_item) {
                $this->incomingTransaction->incoming_items()->detach($item->id);
                $item->quantity -= $incoming_item->pivot->quantity;
                $item->save();
            }
        });

        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => "Barang $item->name berhasil Dihapus",
        ]);
        $this->dispatch('refresh');
    }

    public function rules()
    {
        return [
            'distributor_id' => ['nullable', 'integer', 'exists:distributors,id'],
            'item_id' => [
                'required',
                'integer',
                'exists:items,id',
                Rule::unique('incoming_transaction_item', 'item_id')->where('incoming_transaction_id', $this->incomingTransaction->id),
            ],
            'quantity' => ['required', 'numeric']
        ];
    }

    public function validationAttributes()
    {
        return [
            "distributor_id" => "Distributor",
            "item_id" => "Barang",
            "quantity" => "Jumlah Barang",
        ];
    }

    public function updated($fields)
    {
        $this->resetValidation();
        if ($fields === 'distributor_id') {
            $this->items = Item::select(["id", "distributor_id", "name"])
                ->when($this->distributor_id, fn($query) => $query->where("distributor_id", $this->distributor_id))
                ->get()->toArray();
            $this->dispatch('items-updated', items: $this->items);
        }
        if ($fields === 'item_id') {
            $this->selectedItem = $this->item_id ? Item::find($this->item_id) : null;
        }
    }

    private function syncTotals()
    {
        $this->incomingTransaction->update([
            'quantities' => $this->incomingTransaction->incoming_items->sum('pivot.quantity'),
            'total_money' => $this->incomingTransaction->incoming_items->sum('pivot.total_price'),
        ]);
    }

    #[On('close-modal')]
    public function hapus()
    {
        $this->resetValidation();
        $this->reset(['distributor_id', 'item_id', 'items', 'selectedItem', 'quantity', 'total_price']);
    }

    #[On('refresh')]
    public function refresh()
    {
        $this->syncTotals();
        return;
    }

    public function render()
    {
        return view('livewire.items.incoming-transactions.show-incoming-transaction');
    }
}

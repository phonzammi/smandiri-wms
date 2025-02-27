<?php

namespace App\Livewire\Items\IncomingTransactions;

use App\Models\IncomingTransaction;
use App\Models\Item;
use Livewire\Attributes\On;
use Livewire\Component;

class IncomingTransactionComponent extends Component
{
    public IncomingTransaction $incomingTransaction;
    public string $invoice_code, $distributor, $courier, $date = "";

    public function store()
    {
        $validated = $this->validate();
        $newIncomingTransaction = IncomingTransaction::create($validated);
        session()->flash('status', 'Transaksi Barang Masuk berhasil ditambahkan. Silahkan tambah Barang Masuk untuk transaksi ini');
        $this->redirectRoute('incoming-transactions.show', ['incomingTransaction' => $newIncomingTransaction]);
    }

    public function destroy(int $incoming_transaction_id)
    {
        $incomingTransaction = IncomingTransaction::with([
            'incoming_items' => function ($query) {
                $query->select(['id', 'name', 'quantity']);
            }
        ])->find($incoming_transaction_id);
        if (!$incomingTransaction->is_completed) {
            foreach ($incomingTransaction->incoming_items as $incoming_item) {
                $item = Item::find($incoming_item->id);
                $item->quantity -= $incoming_item->pivot->total_quantity;
                $item->save();
            }
            $incomingTransaction->forceDelete();
        } else {
            $incomingTransaction->delete();
        }
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => "Transaksi Barang Masuk {$incomingTransaction->invoice_code} berhasil dihapus",
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
            'invoice_code' => [
                'required',
                'unique:incoming_transactions,invoice_code'
            ],
            'distributor' => ['required'],
            'courier' => ['required'],
            'date' => ['required', 'date'],
        ];
    }

    public function validationAttributes()
    {
        return [
            "invoice_code" => "Kode Invoice",
            "courier" => "Kurir",
            "date" => "Tanggal",
        ];
    }

    public function updated($fields)
    {
        $this->invoice_code = str($this->invoice_code ?? "")->squish()->upper();
        $this->distributor = str($this->distributor ?? "")->squish()->upper();
        $this->courier = str($this->courier ?? "")->squish()->upper();
        $this->validateOnly($fields);
    }

    public function render()
    {
        return view('livewire.items.incoming-transactions.incoming-transaction-component');
    }
}

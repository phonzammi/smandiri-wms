<?php

namespace App\Livewire\Items\OutgoingTransactions;

use Livewire\Component;
use App\Enums\PaymentMethod;
use App\Models\OutgoingTransaction;
use App\Models\Salesman;
use App\Models\Store;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;

class OutgoingTransactionEditModal extends Component
{
    public OutgoingTransaction $outgoingTransaction;
    public bool $is_edit = false;
    public string $invoice_code, $date, $payment_method = "";
    public ?int $salesman_id, $store_id = null;

    #[On('edit-outgoing-transaction')]
    public function edit(int $outgoingTransactionId)
    {
        $this->outgoingTransaction = OutgoingTransaction::findOrFail($outgoingTransactionId);
        $this->invoice_code = $this->outgoingTransaction->invoice_code;
        $this->date = $this->outgoingTransaction->date->format('Y-m-d');
        $this->payment_method = $this->outgoingTransaction->payment_method->value;
        $this->salesman_id = $this->outgoingTransaction->salesman_id;
        $this->store_id = $this->outgoingTransaction->store_id;
    }

    public function save()
    {
        $validated = $this->validate();
        $this->outgoingTransaction->update($validated);
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => "Informasi Transaksi berhasil diperbarui",
        ]);
        $this->dispatch('close-modal');
    }

    #[On('close-modal')]
    public function cancel()
    {
        $this->resetValidation();
        $this->reset();
    }

    public function rules()
    {
        return [
            'date' => ['required', 'date'],
            'salesman_id' => ['required', 'integer', 'exists:salesmen,id'],
            'store_id' => ['required', 'integer', 'exists:stores,id'],
            'payment_method' => [
                'required',
                Rule::enum(PaymentMethod::class)
            ]
        ];
    }

    public function render()
    {
        $salesmen = Salesman::all();
        $stores = Store::all();
        $payment_methods = Collection::make(PaymentMethod::cases())->mapWithKeys(function (object $item) {
            return [$item->value => $item->getLabelText()];
        })->all();
        return view('livewire.items.outgoing-transactions.outgoing-transaction-edit-modal', compact(['salesmen', 'stores', 'payment_methods']));
    }
}

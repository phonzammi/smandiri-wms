<?php

namespace App\Livewire\Items\OutgoingTransactions;

use App\Enums\PaymentMethod;
use App\Models\OutgoingTransaction;
use App\Models\Salesman;
use App\Models\Store;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Validation\Rule;

class OutgoingTransactionComponent extends Component
{
    public string $invoice_code, $date, $payment_method = "";
    public ?int $salesman_id, $store_id = null;
    public bool $hasStoreIdError = false;

    public function boot()
    {
        $this->withValidator(function ($validator) {
            $validator->after(function ($validator) {
                $this->hasStoreIdError = $validator->errors()->has('store_id');
            });
        });
    }

    public function store()
    {
        $validated = $this->validate();
        $timestamp = date_timestamp_get(date_create());
        $invoice_code = "SMBRGKLR{$timestamp}";
        $newOutgoingTransaction = OutgoingTransaction::create([
            ...$validated,
            "invoice_code" => $invoice_code,
        ]);
        session()->flash('status', 'Transaksi Barang Keluar berhasil ditambahkan. Silahkan tambah Barang Keluar untuk menyelesaikan transaksi ini');
        $this->redirectRoute('outgoing-transactions.show', ['outgoingTransaction' => $newOutgoingTransaction]);
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
            'date' => ['required', 'date'],
            'salesman_id' => ['required', 'integer', 'exists:salesmen,id'],
            'store_id' => ['required', 'integer', 'exists:stores,id'],
            'payment_method' => [
                'required',
                Rule::enum(PaymentMethod::class)
            ]
        ];
    }

    public function validationAttributes()
    {
        return [
            "salesman_id" => "Sales",
            "date" => "Tanggal Keluar",
            "store_id" => "Toko",
            "payment_method" => "Pembayaran"
        ];
    }

    public function render()
    {
        $salesmen = Salesman::all();
        $stores = Store::all();
        $payment_methods = Collection::make(PaymentMethod::cases())->mapWithKeys(function (object $item) {
            return [$item->value => $item->getLabelText()];
        })->all();

        return view(
            'livewire.items.outgoing-transactions.outgoing-transaction-component',
            compact(['salesmen', 'stores', 'payment_methods'])
        );
    }
}

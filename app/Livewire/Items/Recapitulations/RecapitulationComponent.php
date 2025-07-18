<?php

namespace App\Livewire\Items\Recapitulations;

use App\Enums\OutgoingTransactionStatus;
use App\Models\OutgoingTransaction;
use App\Models\Recapitulation;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;

class RecapitulationComponent extends Component
{
    public string $date, $driver, $helper, $license_plate = "";
    public bool $is_edit;

    public function store()
    {
        $validated = $this->validate();
        $outgoing_transactions = OutgoingTransaction::ready()->whereDate('date', '=', $validated['date'])->get();

        $date = date_create_from_format('Y-m-d', $validated['date']);
        $time = date('Hi');
        // Recapitulation Code Format = "SMRKP/Transaction Date/Recap Creation Time/Salesman Count/Transaction Count
        $salesman_count = $outgoing_transactions->groupBy('salesman_id')->count();
        $recapitulation_code = "SMRKP/{$date->format('ymd')}{$time}/{$salesman_count}/{$outgoing_transactions->count()}";

        $new_recapitulation = Recapitulation::create([
            ...$validated,
            "recapitulation_code" => $recapitulation_code,
            'salesman_count' => $salesman_count
        ]);

        foreach ($outgoing_transactions as $outgoing_transaction) {
            $outgoing_transaction->updateOrFail([
                'recapitulation_id' => $new_recapitulation->id,
                'status' => OutgoingTransactionStatus::DELIVERING
            ]);
        }

        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => 'Rekapitulasi berhasil ditambahkan. Menyinkronkan barang !',
        ]);
        $this->attachRecapitulationItem($new_recapitulation->id);
        $this->dispatch('refreshDatatable');
        $this->dispatch('recap-ok');
        $this->hapus();
    }

    public function attachRecapitulationItem(int $recapitulation_id)
    {
        $recapitulation = Recapitulation::with(['outgoing_transaction_items' => function ($query) {
            $query
                ->selectRaw('outgoing_transaction_item.outgoing_transaction_id, outgoing_transaction_item.item_id, sum(outgoing_transaction_item.quantity) as ordered_quantity')
                ->groupBy('item_id');
        }])->find($recapitulation_id);

        foreach ($recapitulation->outgoing_transaction_items as $outgoing_transaction_items) {
            $recapitulation->items()->attach($outgoing_transaction_items->item_id, [
                'quantity' => $outgoing_transaction_items->ordered_quantity
            ]);
        }
        $this->dispatch('toast-message', props: [
            'icon' => 'success',
            'text' => 'Barang berhasil disinkronisasi. Rekapitulasi siap di cetak.',
        ]);
    }

    // public function syncAll()
    // {
    //     $recapitulations = Recapitulation::with([
    //         'items',
    //         'outgoing_transaction_items' => function ($query) {
    //             $query
    //                 ->selectRaw('outgoing_transaction_item.outgoing_transaction_id, outgoing_transaction_item.item_id, sum(outgoing_transaction_item.quantity) as ordered_quantity')
    //                 ->groupBy('item_id');
    //         }
    //     ])->get();

    //     foreach ($recapitulations as $recapitulation) {
    //         if (!$recapitulation->items->count()) {
    //             foreach ($recapitulation->outgoing_transaction_items as $outgoing_transaction_items) {
    //                 $recapitulation->items()->attach($outgoing_transaction_items->item_id, [
    //                     'quantity' => $outgoing_transaction_items->ordered_quantity
    //                 ]);
    //             }
    //         }
    //     }
    // }

    #[On('close-modal')]
    public function hapus()
    {
        $this->resetValidation();
        $this->reset();
    }

    public function rules()
    {
        return [
            'date' => [
                'required',
                'date',
                Rule::exists('outgoing_transactions')->where(function (Builder $query) {
                    $query->where('status', OutgoingTransactionStatus::READY)->whereNull('recapitulation_id');
                }),
            ],
            'driver' => ['required'],
            'helper' => ['required'],
            'license_plate' => ['required'],
        ];
    }

    public function validationAttributes()
    {
        return [
            "date" => "Tanggal Transaksi",
        ];
    }

    protected function messages()
    {
        return [
            'date.exists' => 'Tidak terdapat :attribute yang valid dari Transaksi Penjualan.',
        ];
    }

    public function updated($fields)
    {
        $this->driver = str($this->driver)->squish()->upper();
        $this->helper = str($this->helper)->squish()->upper();
        $this->license_plate = str($this->license_plate)->squish()->upper();
        $this->validateOnly($fields);
    }

    public function render()
    {
        return view('livewire.items.recapitulations.recapitulation-component');
    }
}

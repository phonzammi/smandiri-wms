<?php

namespace App\Http\Controllers;

use App\Models\Recapitulation;
use App\Models\Salesman;

class PrintRecapitulationController extends Controller
{
    public function printAll(Recapitulation $recapitulation)
    {
        $recapitulation->loadMissing(['items' => function ($query) {
            $query->with(['itemUnit:id,size']);
            $query->selectRaw('items.id, items.name, items.item_unit_id,  sum(recapitulation_item.total_quantity) as total_quantity')->groupBy('item_id');
        }]);

        $recapitulation->loadSum('items as total_price', 'recapitulation_item.total_price');
        $recapitulation->loadSum('items as total_discount', 'recapitulation_item.total_discount');
        $recapitulation->loadSum('items as total_net_price', 'recapitulation_item.total_net_price');

        return view('bills.print-recapitulation', compact('recapitulation'));
    }

    public function printBySalesman($recapitulationId, int $salesmanId)
    {
        $salesman = Salesman::findorFail($salesmanId);
        $recapitulation = Recapitulation::with([
            'outgoing_transactions' => function ($query) use ($salesman) {
                $query->where('salesman_id', $salesman->id);
            },
            'items' => function ($query) use ($salesman) {
                $query->with(['itemUnit'])->wherePivot('salesman_id', $salesman->id);
            }
        ])->find($recapitulationId);

        return view('bills.print-recapitulation-by-salesman', compact('recapitulation', 'salesman'));
    }
}

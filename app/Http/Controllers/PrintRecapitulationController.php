<?php

namespace App\Http\Controllers;

use App\Enums\ItemUnitSize;
use App\Models\Recapitulation;
use Illuminate\Http\Request;

class PrintRecapitulationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Recapitulation $recapitulation)
    {
        $recapitulation->loadMissing(['outgoing_transactions', 'items' => function ($query) {
            $query->with(['itemUnit:id,size'])->select(['id', 'item_unit_id', 'name']);
        }]);

        return view('bills.print-recapitulation', compact('recapitulation'));
    }
}

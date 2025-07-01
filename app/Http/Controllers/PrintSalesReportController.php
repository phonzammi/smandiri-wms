<?php

namespace App\Http\Controllers;

use App\Models\OutgoingTransaction;
use Illuminate\Http\Request;

class PrintSalesReportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $minDate = $request->query("minDate");
        $maxDate = $request->query("maxDate");
        $outgoing_transactions = OutgoingTransaction::sold()
            ->with(['salesman', 'store'])
            ->whereBetween('date', [$minDate, $maxDate])
            ->orderBy('date', 'desc')
            ->get();
        return view(
            'reports.print-sales-report',
            compact(
                'outgoing_transactions',
                'minDate',
                'maxDate'
            )
        );
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Salesman;
use Illuminate\Http\Request;

class PrintSalesmanIncentiveController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Salesman $salesman)
    {
        $minDate = $request->query("minDate");
        $maxDate = $request->query("maxDate");

        $salesman->loadMissing(["outgoing_transactions" => function ($query) use ($minDate, $maxDate) {
            $query->with('store')->sold()->whereBetween('date', [$minDate, $maxDate])->orderBy('date', 'desc');
        }]);

        return view(
            "reports.print-salesman-incentive",
            compact(
                "salesman",
                "minDate",
                "maxDate"
            )
        );
    }
}

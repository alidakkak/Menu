<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVisitRequest;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function store(Request $request) {
        $visit = Visit::create($request->all());
        return $visit;
    }

    public function get(Request $request)
    {
        $state = $request->get('state');

        if ($state === 'day') {
            $date = $request->input('date1');
            $visits = Visit::whereDate('created_at', $date)->get();
            return $visits;
        } else if ($state === 'month') {
            $date = $request->input('date1');
            $visits = Visit::whereMonth('created_at', $date)
                ->get();
            return $visits;
        } else if ($state === 'year') {
            $date = $request->input('date1');
            $visits = Visit::whereYear('created_at', $date)->get();
            return $visits;
        } else if ($state === 'between') {
            $date1 = $request->input('date1');
            $date2 = $request->input('date2');
            $visits = Visit::whereBetween('created_at', [$date1, $date2])->get();
            return $visits;
        } else {
            return response([
                'message' => "state you're trying yo filter on it not avilibale"
            ], 422);
        }
    }
}

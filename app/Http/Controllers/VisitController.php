<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function store(Request $request) {
        $visit = Visit::create($request->all());
        return $visit;
    }

    public function getByDay(){
        $today = Carbon::now()->format('Y-m-d');
        $visit = Visit::whereDate('created_at', $today)->get();
        return $visit;
    }

    public function getByMonth(){
        $month = Carbon::now()->format('m');
        $year = Carbon::now()->format('Y');
        $visits = Visit::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();
        return $visits;
    }

    public function getByYear(){
        $year = Carbon::now()->format('Y');
        $visits = Visit::whereYear('created_at', $year)
            ->get();
        return $visits;
    }


}

<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;

class CollectorController extends Controller
{
    public function index(){
        $nasabahs = Nasabah::with([
            'loans' => function ($query) {
                $query->where('status', 'active')
                    ->orderBy('created_at', 'desc');
            },
            'loans.installments' => function ($query) {
                $query->where('status', '!=', 'paid')
                    ->orderBy('installment_number', 'asc');
            }
        ])->whereHas('loans', function ($query) {
            $query->where('status', 'active');
        })->orderBy('created_at', 'desc')->get();
        return view('collector.dashboard', compact('nasabahs'));
    }
}
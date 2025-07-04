<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use App\Models\CollectorTask;
use App\Models\Nasabah;
use Illuminate\Support\Facades\Auth;

class CollectorController extends Controller
{
    public function index(){
        $tasks = CollectorTask::where('collector_id', Auth::user()->id)->with(['nasabah', 'loan.installments'])->get();
        dd($tasks);
        return view('collector.dashboard', compact('tasks'));
    }
}
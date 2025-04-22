<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userCount = null;
        $totalPenjualanHariIni = 0;

        if (auth()->user()->role == 'admin') {
            $userCount = \App\Models\User::count();
        } else {
            $totalPenjualanHariIni = \App\Models\Sale::whereDate('created_at', today())->sum('total');
        }

        $productCount = \App\Models\Product::count();
        $salesCount = \App\Models\Sale::count();
        $memberCount = \App\Models\Member::count();

        $salesData = \App\Models\Sale::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = $salesData->pluck('month')->map(function ($m) {
            return \Carbon\Carbon::create()->month($m)->translatedFormat('F');
        });

        $salesCounts = $salesData->pluck('count');

        $lastSale = Sale::latest()->first();

        return view('home', compact(
            'userCount',
            'productCount',
            'salesCount',
            'memberCount',
            'months',
            'salesCounts',
            'totalPenjualanHariIni',
            'lastSale' 
        ));
    }
}

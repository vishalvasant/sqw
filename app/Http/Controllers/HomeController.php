<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\User;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;

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
        $totalAssets = Asset::count();
        $totalUsers = User::count();
        $pendingPRs = PurchaseRequest::where('status', 'Pending')->count();
        $completedPOs = PurchaseOrder::where('status', 'Completed')->count();

        // Recent POs & PRs
        $latestPRs = PurchaseRequest::latest()->limit(5)->get();
        $latestPOs = PurchaseOrder::latest()->limit(5)->get();

        // Monthly PO & PR Report
        $months = collect(range(1, 12))->map(function ($month) {
            return date('F', mktime(0, 0, 0, $month, 1));
        });

        $poCounts = PurchaseOrder::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')->toArray();

        $prCounts = PurchaseRequest::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')->toArray();

        return view('home', compact(
            'totalAssets', 'totalUsers', 'pendingPRs', 'completedPOs',
            'latestPRs', 'latestPOs', 'months', 'poCounts', 'prCounts'
        ));
    }
}

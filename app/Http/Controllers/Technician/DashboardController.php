<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $pendingBookings = $user->bookingsAsTechnician()
            ->where('status', 'pending')
            ->with(['customer', 'category'])
            ->latest()
            ->get();

        $activeBookings = $user->bookingsAsTechnician()
            ->whereIn('status', ['accepted', 'in_progress'])
            ->with(['customer', 'category'])
            ->latest()
            ->get();

        $completedCount = $user->bookingsAsTechnician()
            ->where('status', 'completed')
            ->count();

        $subscription = $user->activeSubscription;

        return view('technician.dashboard.index', compact(
            'pendingBookings',
            'activeBookings',
            'completedCount',
            'subscription'
        ));
    }
}

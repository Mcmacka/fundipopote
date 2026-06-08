<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Subscription;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalTechnicians  = User::where('role', 'technician')->count();
        $totalCustomers    = User::where('role', 'customer')->count();
        $activeSubs        = Subscription::where('status', 'active')
                                ->where('expires_at', '>', now())->count();
        $pendingApprovals  = Subscription::where('status', 'pending_approval')->count();
        $totalBookings     = Booking::count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $pendingBookings   = Booking::where('status', 'pending')->count();
        $awaitingPayment   = Booking::where('status', 'awaiting_payment')->count();

        return [
            Stat::make('Total Technicians', $totalTechnicians)
                ->description($activeSubs . ' with active subscription')
                ->descriptionIcon('heroicon-m-check-circle')
                ->icon('heroicon-o-wrench-screwdriver')
                ->color('info'),

            Stat::make('Total Customers', $totalCustomers)
                ->description('Registered customers')
                ->descriptionIcon('heroicon-m-user-group')
                ->icon('heroicon-o-users')
                ->color('success'),

            Stat::make('Pending Approvals', $pendingApprovals)
                ->description('Payments awaiting your verification')
                ->descriptionIcon('heroicon-m-clock')
                ->icon('heroicon-o-clock')
                ->color($pendingApprovals > 0 ? 'warning' : 'gray'),

            Stat::make('Total Bookings', $totalBookings)
                ->description($completedBookings . ' completed · ' . $pendingBookings . ' pending · ' . $awaitingPayment . ' awaiting payment')
                ->descriptionIcon('heroicon-m-document-text')
                ->icon('heroicon-o-document-text')
                ->color('gray'),
        ];
    }
}

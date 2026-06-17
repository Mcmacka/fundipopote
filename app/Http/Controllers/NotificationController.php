<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $notifications = $user->notifications()->latest()->paginate(10);
        
        // Mark zote kuwa zimeshasomwa
        $user->unreadNotifications->markAsRead();

        // Angalia role ili kurudisha view husika
        if ($user->isTechnician()) {
            return view('technician.notifications.index', compact('notifications'));
        }

        return view('customer.notifications.index', compact('notifications'));
    }
}
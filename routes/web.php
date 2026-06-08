<?php

use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Customer\SearchController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Technician\BookingController as TechnicianBookingController;
use App\Http\Controllers\Technician\DashboardController as TechnicianDashboardController;
use App\Http\Controllers\Technician\PortfolioController;
use App\Http\Controllers\Technician\ProfileController;
use App\Http\Controllers\Technician\SubscriptionController as TechnicianSubscriptionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

// ── Home / Welcome Page ──
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ── Language Switcher ──
Route::get('/language/{locale}', [LanguageController::class, 'switch'])
     ->name('language.switch')
     ->where('locale', 'en|sw');

// ══════════════════════════════════════
// CUSTOMER ROUTES
// ══════════════════════════════════════
Route::middleware(['auth', 'verified'])
    ->prefix('app')
    ->name('customer.')
    ->group(function () {
        Route::get('/search', [SearchController::class, 'index'])->name('search');
        Route::get('/technician/{id}', [SearchController::class, 'show'])->name('technician.show');
        Route::get('/bookings', [CustomerBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/create', [CustomerBookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [CustomerBookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{booking}', [CustomerBookingController::class, 'show'])->name('bookings.show');
        Route::patch('/bookings/{booking}/cancel', [CustomerBookingController::class, 'cancel'])->name('bookings.cancel');
        Route::post('/bookings/{booking}/rate', [CustomerBookingController::class, 'rate'])->name('bookings.rate');
        Route::get('/bookings/{booking}/pay', [PaymentController::class, 'create'])->name('payment.create');
        Route::post('/bookings/{booking}/pay', [PaymentController::class, 'store'])->name('payment.store');
    });

// ══════════════════════════════════════
// TECHNICIAN ROUTES
// ══════════════════════════════════════
Route::middleware(['auth', 'verified'])
    ->prefix('fundi')
    ->name('technician.')
    ->group(function () {
        Route::get('/subscription', [TechnicianSubscriptionController::class, 'index'])->name('subscription.index');
        Route::post('/subscription', [TechnicianSubscriptionController::class, 'store'])->name('subscription.store');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
        Route::get('/portfolio/create', [PortfolioController::class, 'create'])->name('portfolio.create');
        Route::post('/portfolio', [PortfolioController::class, 'store'])->name('portfolio.store');
        Route::post('/portfolio/{title}/toggle', [PortfolioController::class, 'toggle'])->name('portfolio.toggle');
        Route::delete('/portfolio/{title}', [PortfolioController::class, 'destroy'])->name('portfolio.destroy');

        Route::middleware(['subscription.active'])->group(function () {
            Route::get('/dashboard', [TechnicianDashboardController::class, 'index'])->name('dashboard');
            Route::get('/bookings', [TechnicianBookingController::class, 'index'])->name('bookings.index');
            Route::patch('/bookings/{booking}/accept', [TechnicianBookingController::class, 'accept'])->name('bookings.accept');
            Route::patch('/bookings/{booking}/reject', [TechnicianBookingController::class, 'reject'])->name('bookings.reject');
            Route::patch('/bookings/{booking}/complete', [TechnicianBookingController::class, 'complete'])->name('bookings.complete');
        });
    });

// ══════════════════════════════════════
// GUEST/PASSWORD RESET ROUTES
// ══════════════════════════════════════
Route::middleware('guest')->group(function () {
    Route::get('forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
});

// ══════════════════════════════════════
// MTEGO WA REDIRECT BAADA YA LOGIN
// ══════════════════════════════════════
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user) {
        if ($user->isAdmin()) {
            // Hapa tunatumia route ya Filament moja kwa moja
            return redirect()->route('filament.admin.pages.dashboard');
        }

        if ($user->isTechnician()) { 
            return redirect()->route('technician.dashboard');
        }

        return redirect()->route('customer.search');
    }

    return redirect()->route('login');
})->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
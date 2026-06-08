<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Controllers za Mteja (Customer)
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Customer\SearchController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;

// Controllers za Fundi (Technician)
use App\Http\Controllers\Technician\BookingController as TechnicianBookingController;
use App\Http\Controllers\Technician\DashboardController as TechnicianDashboardController;
use App\Http\Controllers\Technician\PortfolioController;
use App\Http\Controllers\Technician\ProfileController as TechnicianProfileController;
use App\Http\Controllers\Technician\SubscriptionController as TechnicianSubscriptionController;
use App\Http\Controllers\Technician\PasswordController;

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
        Route::patch('/profile/password', [CustomerProfileController::class, 'updatePassword']) ->name('profile.password');
        
        // Profile
        Route::get('/profile', [CustomerProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');

        // Bookings & Payments
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
        Route::patch('/profile/password', [PasswordController::class, 'update'])
            ->name('profile.password');
        
        // Profile (Kutumia Aliased Controller)
        Route::get('/profile', [TechnicianProfileController::class, 'edit'])->name('profile.edit');
        Route::match(['put', 'patch'], '/profile', [TechnicianProfileController::class, 'update'])
            ->name('profile.update');
        
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
            Route::get('/bookings/{id}', [TechnicianBookingController::class, 'show'])->name('bookings.show');
            Route::patch('/bookings/{booking}', [BookingController::class, 'update'])->name('technician.bookings.update');
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
// REDIRECT LOGIC
// ══════════════════════════════════════
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user) {
        if ($user->isAdmin()) return redirect()->route('filament.admin.pages.dashboard');
        if ($user->isTechnician()) return redirect()->route('technician.dashboard');
        return redirect()->route('customer.search');
    }
    return redirect()->route('login');
})->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
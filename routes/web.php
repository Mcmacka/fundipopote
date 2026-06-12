<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\VerificationController;

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

// ── Logout Route ──
Route::post('/admin/logout', function () {
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/'); 
})->name('filament.admin.auth.logout');

// ── Language Switcher ──
Route::get('/language/{locale}', [LanguageController::class, 'switch'])
     ->name('language.switch')
     ->where('locale', 'en|sw');

// ══════════════════════════════════════
// CUSTOMER ROUTES
// ══════════════════════════════════════
Route::middleware(['auth', 'verified.user'])
    ->prefix('app')
    ->name('customer.')
    ->group(function () {
        Route::get('/search', [SearchController::class, 'index'])->name('search');
        Route::get('/technician/{id}', [SearchController::class, 'show'])->name('technician.show');
        Route::patch('/profile/password', [CustomerProfileController::class, 'updatePassword'])->name('profile.password');
        
        Route::get('/profile', [CustomerProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');
        // Ndani ya group ya 'customer.'
Route::patch('/profile/photo', [CustomerProfileController::class, 'updatePhoto'])->name('profile.photo.update');

        Route::get('/bookings', [CustomerBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/create', [CustomerBookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [CustomerBookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{booking}', [CustomerBookingController::class, 'show'])->name('bookings.show');
        Route::patch('/bookings/{booking}/cancel', [CustomerBookingController::class, 'cancel'])->name('bookings.cancel');
        Route::post('/bookings/{booking}/rate', [CustomerBookingController::class, 'rate'])->name('bookings.rate');
        Route::get('/bookings/{booking}/pay', [PaymentController::class, 'create'])->name('payment.create');
        Route::post('/bookings/{booking}/pay', [PaymentController::class, 'store'])->name('payment.store');
        Route::get('/bookings/{booking}/edit', [CustomerBookingController::class, 'edit'])->name('bookings.edit');
        Route::patch('/bookings/{booking}', [CustomerBookingController::class, 'update'])->name('bookings.update');
        Route::delete('/bookings/{booking}', [CustomerBookingController::class, 'destroy'])->name('bookings.destroy');

    });

// ══════════════════════════════════════
// TECHNICIAN ROUTES
// ══════════════════════════════════════
Route::middleware(['auth', 'verified.user'])
    ->prefix('fundi')
    ->name('technician.')
    ->group(function () {
        Route::get('/subscription', [TechnicianSubscriptionController::class, 'index'])->name('subscription.index');
        Route::post('/subscription', [TechnicianSubscriptionController::class, 'store'])->name('subscription.store');
        
        Route::patch('/profile/password', [PasswordController::class, 'update'])->name('profile.password');
        
        Route::get('/profile', [TechnicianProfileController::class, 'edit'])->name('profile.edit');
        Route::match(['put', 'patch'], '/profile', [TechnicianProfileController::class, 'update'])->name('profile.update');
        
        Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
        Route::get('/portfolio/create', [PortfolioController::class, 'create'])->name('portfolio.create');
        Route::post('/portfolio', [PortfolioController::class, 'store'])->name('portfolio.store');
        Route::post('/portfolio/{title}/toggle', [PortfolioController::class, 'toggle'])->name('portfolio.toggle');
        Route::delete('/portfolio/{title}', [PortfolioController::class, 'destroy'])->name('portfolio.destroy');

        Route::middleware(['subscription.active'])->group(function () {
            Route::get('/dashboard', [TechnicianDashboardController::class, 'index'])->name('dashboard');
            
            // Hizi ndizo routes sahihi za Booking
            Route::get('/bookings', [TechnicianBookingController::class, 'index'])->name('bookings.index');
            Route::get('/bookings/{booking}', [TechnicianBookingController::class, 'show'])->name('bookings.show');
            Route::patch('/bookings/{booking}', [TechnicianBookingController::class, 'update'])->name('bookings.update');
            Route::post('/bookings/{booking}/complete', [TechnicianBookingController::class, 'complete'])->name('bookings.complete');
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
Route::middleware(['auth'])->group(function () {
    Route::get('/otp/verify', [VerificationController::class, 'showOtpForm'])->name('otp.verify.form');
    Route::post('/otp/verify', [VerificationController::class, 'verifyOtp'])->name('otp.verify');
    
    Route::get('/terms', [VerificationController::class, 'showTerms'])->name('terms.show');
    Route::post('/terms', [VerificationController::class, 'acceptTerms'])->name('terms.accept');
}); // Hapa ndipo ilipokuwa inakosekana

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
})->middleware(['auth', 'verified.user'])->name('dashboard'); // <--- HII NDIO ILIKUWA INAKOSEKANA



require __DIR__.'/auth.php';
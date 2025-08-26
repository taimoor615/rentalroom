<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AdminBookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ListingController::class, 'index'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Host-only routes
Route::middleware(['auth', 'host'])->group(function () {
    Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create');
    Route::post('/listings', [ListingController::class, 'store'])->name('listings.store');
    Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->name('listings.edit');
    Route::put('/listings/{listing}', [ListingController::class, 'update'])->name('listings.update');
    Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->name('listings.destroy');
    Route::delete('/media/{media}', [ListingController::class, 'deleteMedia'])->name('media.delete');
});

//Public listing routes
Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');
Route::get('/listings/{listing}', [ListingController::class, 'show'])->name('listings.show');

// Booking routes
Route::middleware('auth')->group(function () {
    Route::post('/listings/{listing}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

// Payment routes
Route::middleware('auth')->group(function () {
    Route::get('/payment/{booking}', [PaymentController::class, 'create'])->name('payment.create');
    Route::get('/payment/{booking}/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/{booking}/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
});

// Review routes
Route::middleware('auth')->group(function () {
    Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('bookings', AdminBookingController::class);
    Route::patch('bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.updateStatus');
});



require __DIR__.'/auth.php';

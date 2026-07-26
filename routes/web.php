<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'index'])->name('welcome');

Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{gallery}', [GalleryController::class, 'show'])->name('gallery.show');

Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/{booking}/pdf', [BookingController::class, 'pdf'])->name('booking.pdf');

// Token-guarded .ics subscription for the agency's own calendar app.
Route::get('/agenda/{token}.ics', [AgendaController::class, 'feed'])->name('agenda.feed');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/gallery-admin/create', [GalleryController::class, 'create'])->name('gallery.create');
    Route::post('/gallery-admin', [GalleryController::class, 'store'])->name('gallery.store');
    Route::get('/gallery-admin/{gallery}/edit', [GalleryController::class, 'edit'])->name('gallery.edit');
    Route::put('/gallery-admin/{gallery}', [GalleryController::class, 'update'])->name('gallery.update');
    Route::delete('/gallery-admin/{gallery}', [GalleryController::class, 'destroy'])->name('gallery.destroy');

    Route::get('/admin/bookings', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/admin/bookings/{booking}', [BookingController::class, 'show'])->name('booking.show');
    Route::patch('/admin/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('booking.status');
});

require __DIR__.'/auth.php';

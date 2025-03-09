<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('kategori', 'kategori')
    ->middleware(['auth', 'verified', 'is_admin'])
    ->name('kategori');

Route::view('histori', 'histori')
    ->middleware(['auth', 'verified'])
    ->name('histori');

Route::view('booking', 'booking')
    ->middleware(['auth', 'verified'])
    ->name('booking');



Route::get('/bookingdetail/{id}', function ($id) {
    return view('bookingdetail', ['id' => $id]);
})->middleware(['auth', 'verified'])
    ->name('bookingdetail');

Route::view('daftarkendaraan', 'daftarkendaraan')
    ->middleware(['auth', 'verified', 'is_admin'])
    ->name('daftarkendaraan');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';

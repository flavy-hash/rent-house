<?php

use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PropertyController::class, 'home'])->name('home');

Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');

// Saved / favourites (browser-based, no login needed)
Route::get('/saved', [PropertyController::class, 'saved'])->name('saved');
Route::get('/saved/cards', [PropertyController::class, 'savedCards'])->name('saved.cards');

Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');

// Landlords list & manage properties through the Filament panel at /admin.

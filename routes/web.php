<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Products\ProductList;
use App\Livewire\Products\ProductForm;
use App\Livewire\Shop\ShopDashboard;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::get('product-list', ProductList::class)
    ->middleware(['auth', 'verified'])
    ->name('product-list');

Route::get('product-form', ProductForm::class)
    ->middleware(['auth', 'verified'])
    ->name('product-form');
  
Route::get('/products/{id}/edit', \App\Livewire\Products\ProductForm::class)
    ->middleware(['auth', 'verified'])
    ->name('product-edit');

Route::get('shop-dashboard', ShopDashboard::class)
    ->name('shop-dashboard');




Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';

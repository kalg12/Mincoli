<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PagesController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Shop Routes
Route::get('/tienda', [ShopController::class, 'index'])->name('shop');
Route::get('/tienda/categoria/{slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/tienda/producto/{slug}', [ShopController::class, 'product'])->name('shop.product');

// Cart Routes
Route::get('/carrito', [CartController::class, 'index'])->name('cart');
Route::post('/carrito/agregar', [CartController::class, 'add'])->name('cart.add');
Route::patch('/carrito/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/carrito', [CartController::class, 'clear'])->name('cart.clear');

// Pages Routes
Route::get('/sobre-nosotros', [PagesController::class, 'about'])->name('about');
Route::get('/politicas-envio', [PagesController::class, 'shipping'])->name('shipping');
Route::get('/devoluciones', [PagesController::class, 'returns'])->name('returns');
Route::get('/preguntas-frecuentes', [PagesController::class, 'faq'])->name('faq');
Route::get('/contacto', [PagesController::class, 'contact'])->name('contact');
Route::get('/terminos-condiciones', [PagesController::class, 'terms'])->name('terms');
Route::get('/politica-privacidad', [PagesController::class, 'privacy'])->name('privacy');
Route::get('/aviso-legal', [PagesController::class, 'legal'])->name('legal');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

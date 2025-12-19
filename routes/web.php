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
Route::get('/api/carrito/datos', [CartController::class, 'getCartData'])->name('cart.data');

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

Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    // Settings
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');

    // Products
    Route::get('/products', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
    Route::post('/products/print-labels', [App\Http\Controllers\Admin\ProductController::class, 'printLabels'])->name('products.printLabels');
    Route::get('/products/{id}/edit', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{id}/toggle-featured', [App\Http\Controllers\Admin\ProductController::class, 'toggleFeatured'])->name('products.toggleFeatured');

    // Categories
    Route::get('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/trash', [App\Http\Controllers\Admin\CategoryController::class, 'trash'])->name('categories.trash');
    Route::get('/categories/{id}/edit', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('/categories/{id}/restore', [App\Http\Controllers\Admin\CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('/categories/{id}/force', [App\Http\Controllers\Admin\CategoryController::class, 'forceDelete'])->name('categories.forceDelete');

    // Banners
    Route::get('/banners', [App\Http\Controllers\Admin\BannerController::class, 'index'])->name('banners.index');
    Route::get('/banners/create', [App\Http\Controllers\Admin\BannerController::class, 'create'])->name('banners.create');
    Route::post('/banners', [App\Http\Controllers\Admin\BannerController::class, 'store'])->name('banners.store');
    Route::get('/banners/{id}/edit', [App\Http\Controllers\Admin\BannerController::class, 'edit'])->name('banners.edit');
    Route::put('/banners/{id}', [App\Http\Controllers\Admin\BannerController::class, 'update'])->name('banners.update');
    Route::delete('/banners/{id}', [App\Http\Controllers\Admin\BannerController::class, 'destroy'])->name('banners.destroy');

    // Orders
    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Customers
    Route::get('/customers', [App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [App\Http\Controllers\Admin\CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [App\Http\Controllers\Admin\CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{id}', [App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('customers.show');
    Route::put('/customers/{id}', [App\Http\Controllers\Admin\CustomerController::class, 'update'])->name('customers.update');
    Route::post('/customers/{id}/notes', [App\Http\Controllers\Admin\CustomerController::class, 'addNote'])->name('customers.notes.store');

    // Payment Methods
    Route::get('/payment-methods', fn() => view('admin.payment-methods.index'))->name('payment-methods.index');
    Route::get('/payment-methods/{method}/edit', fn($method) => view('admin.payment-methods.edit', compact('method')))->name('payment-methods.edit');

    // Shipping
    Route::get('/shipping', fn() => view('admin.shipping.index'))->name('shipping.index');
    Route::get('/shipping/zones/create', fn() => view('admin.shipping.zones.create'))->name('shipping.zones.create');
    Route::get('/shipping/zones/{id}/edit', fn($id) => view('admin.shipping.zones.edit', compact('id')))->name('shipping.zones.edit');
});

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

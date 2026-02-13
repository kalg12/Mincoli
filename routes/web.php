<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\Api\POSApiController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Blog Public Routes
Volt::route('/blog', 'blog.index')->name('blog.index');
Volt::route('/blog/{slug}', 'blog.show')->name('blog.show');


// Shop Routes
Route::get('/tienda', [ShopController::class, 'index'])->name('shop');
Route::get('/tienda/categoria/{slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/tienda/producto/{slug}', [ShopController::class, 'product'])->name('shop.product');
Route::get('/buscar', [ShopController::class, 'search'])->name('shop.search');

// Cart Routes
Route::get('/carrito', [CartController::class, 'index'])->name('cart');
Route::post('/carrito/agregar', [CartController::class, 'add'])->name('cart.add');
Route::get('/carrito/agregar', function() { return redirect()->route('cart'); });
Route::patch('/carrito/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/carrito', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/api/carrito/datos', [CartController::class, 'getCartData'])->name('cart.data');

// Checkout Routes
Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{order}', [App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/failure', [App\Http\Controllers\CheckoutController::class, 'failure'])->name('checkout.failure');
Route::get('/checkout/receipt/{order}', [App\Http\Controllers\CheckoutController::class, 'downloadReceipt'])->name('checkout.receipt');
Route::post('/webhooks/mercadopago', [App\Http\Controllers\CheckoutController::class, 'webhook'])->name('webhooks.mercadopago');


// Pages Routes
Route::get('/sobre-nosotros', [PagesController::class, 'about'])->name('about');
Route::get('/politicas-envio', [PagesController::class, 'shipping'])->name('shipping');
Route::get('/devoluciones', [PagesController::class, 'returns'])->name('returns');
Route::get('/preguntas-frecuentes', [PagesController::class, 'faq'])->name('faq');
Route::get('/contacto', [PagesController::class, 'contact'])->name('contact');
Route::get('/terminos-condiciones', [PagesController::class, 'terms'])->name('terms');
Route::get('/politica-privacidad', [PagesController::class, 'privacy'])->name('privacy');
Route::get('/aviso-legal', [PagesController::class, 'legal'])->name('legal');

// Exclusive Landing (content by phone validation)
Route::get('/exclusivo', [App\Http\Controllers\ExclusiveLandingController::class, 'gate'])->name('exclusive-landing.gate');
Route::post('/exclusivo/validar', [App\Http\Controllers\ExclusiveLandingController::class, 'validatePhone'])->name('exclusive-landing.validate');
Route::get('/exclusivo/salir', [App\Http\Controllers\ExclusiveLandingController::class, 'logout'])->name('exclusive-landing.logout');
Route::get('/exclusivo/tienda', [App\Http\Controllers\ExclusiveLandingController::class, 'index'])->name('exclusive-landing.index');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Order Tracker
Route::get('/rastreo', [App\Http\Controllers\OrderTrackerController::class, 'index'])->name('tracker.index');
Route::post('/rastreo', [App\Http\Controllers\OrderTrackerController::class, 'track'])->name('tracker.track');

Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    // Rutas de captura pública (fuera del panel)
});

// Captura pública de conteos (sin autenticación)
Route::get('/inventory-capture/{token}', [App\Http\Controllers\Admin\InventoryController::class, 'publicCaptureForm'])->name('inventory.public.capture');
Route::post('/inventory-capture/{token}/items/{item}', [App\Http\Controllers\Admin\InventoryController::class, 'savePublicCapture'])->name('inventory.public.items.save');

Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    // POS Module
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [POSController::class, 'index'])->name('index');
        Route::get('/search', [POSController::class, 'searchProduct'])->name('searchProduct');
        Route::get('/customers/search', [POSController::class, 'searchCustomer'])->name('customers.search');
        Route::post('/transactions/store-ajax', [POSController::class, 'storeAjax'])->name('store-ajax'); // Added route
        Route::get('/transactions', [POSController::class, 'transactions'])->name('transactions.index');
        Route::get('/session/open', [POSController::class, 'openSession'])->name('session.open');
        Route::post('/session', [POSController::class, 'storeSession'])->name('session.store');
        Route::post('/session/{session}/close', [POSController::class, 'closeSession'])->name('session.close');
        Route::get('/success/{order}', [POSController::class, 'success'])->name('success'); // New POS success route

        // Transacciones
        Route::get('/{session}/transaction', [POSController::class, 'createTransaction'])->name('transaction.create');
        Route::post('/{session}/transaction', [POSController::class, 'storeTransaction'])->name('transaction.store');
        Route::get('/transaction/{transaction}', [POSController::class, 'editTransaction'])->name('transaction.edit');
        Route::patch('/transaction/{transaction}', [POSController::class, 'updateTransaction'])->name('transaction.update');
        Route::post('/transaction/{transaction}/complete', [POSController::class, 'completeTransaction'])->name('transaction.complete');

        // Items
        Route::post('/transaction/{transaction}/item', [POSController::class, 'addItem'])->name('item.add');
        Route::delete('/transaction/{transaction}/item/{item}', [POSController::class, 'removeItem'])->name('item.remove');
        Route::patch('/transaction/{transaction}/item/{item}/quantity', [POSController::class, 'updateItemQuantity'])->name('item.updateQuantity');

        // Pagos
        Route::post('/transaction/{transaction}/payment', [POSController::class, 'recordPayment'])->name('payment.store');

        // Tickets
        Route::get('/transaction/{transaction}/ticket', [POSController::class, 'printTicket'])->name('ticket.print');
        Route::get('/orders/{order}/ticket', [POSController::class, 'printOrderTicket'])->name('order.ticket'); // New route for Order model tickets

        // Items pendientes por enviar
        Route::get('/pending-shipments', [POSController::class, 'pendingShipments'])->name('pending-shipments.index');
        Route::patch('/item/{item}/shipped', [POSController::class, 'markAsShipped'])->name('item.shipped');
        Route::patch('/item/{item}/completed', [POSController::class, 'markAsCompleted'])->name('item.completed');

        // Cotizaciones
        Route::prefix('quotations')->name('quotations.')->group(function () {
            Route::get('/', [\App\Http\Controllers\QuotationController::class, 'index'])->name('index');
            Route::get('/trash', [\App\Http\Controllers\QuotationController::class, 'trash'])->name('trash');
            Route::post('/', [\App\Http\Controllers\QuotationController::class, 'store'])->name('store');
            Route::get('/{quotation}/edit', [\App\Http\Controllers\QuotationController::class, 'edit'])->name('edit');
            Route::put('/{quotation}', [\App\Http\Controllers\QuotationController::class, 'update'])->name('update');
            Route::get('/{quotation}', [\App\Http\Controllers\QuotationController::class, 'show'])->name('show');
            Route::patch('/{quotation}/status', [\App\Http\Controllers\QuotationController::class, 'updateStatus'])->name('update-status');
            Route::post('/{id}/restore', [\App\Http\Controllers\QuotationController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force', [\App\Http\Controllers\QuotationController::class, 'forceDelete'])->name('force-delete');
            Route::delete('/{quotation}', [\App\Http\Controllers\QuotationController::class, 'destroy'])->name('destroy');
        });
    });

    // Settings
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');

    // Products
    Route::get('/products', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/trash', [App\Http\Controllers\Admin\ProductController::class, 'trash'])->name('products.trash');
    Route::get('/products/create', [App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
    Route::post('/products/print-labels', [App\Http\Controllers\Admin\ProductController::class, 'printLabels'])->name('products.printLabels');
    Route::get('/products/{id}/edit', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{id}/restore', [App\Http\Controllers\Admin\ProductController::class, 'restore'])->name('products.restore');
    Route::delete('/products/{id}/force', [App\Http\Controllers\Admin\ProductController::class, 'forceDelete'])->name('products.forceDelete');
    Route::post('/products/{id}/toggle-featured', [App\Http\Controllers\Admin\ProductController::class, 'toggleFeatured'])->name('products.toggleFeatured');
    Route::patch('/products/{id}/toggle-active', [App\Http\Controllers\Admin\ProductController::class, 'toggleActive'])->name('products.toggleActive');
    Route::post('/products/{id}/images/reorder', [App\Http\Controllers\Admin\ProductController::class, 'reorderImages'])->name('products.images.reorder');
    Route::delete('/products/{id}/images/{imageId}', [App\Http\Controllers\Admin\ProductController::class, 'destroyImage'])->name('products.images.destroy');

    // Product Variants
    Route::post('/products/{id}/variants', [App\Http\Controllers\Admin\ProductController::class, 'storeVariant'])->name('products.variants.store');
    Route::put('/products/{id}/variants/{variantId}', [App\Http\Controllers\Admin\ProductController::class, 'updateVariant'])->name('products.variants.update');
    Route::delete('/products/{id}/variants/{variantId}', [App\Http\Controllers\Admin\ProductController::class, 'destroyVariant'])->name('products.variants.destroy');

    // Inventory Management
    Route::post('/products/{id}/adjust-stock', [App\Http\Controllers\Admin\ProductController::class, 'adjustStock'])->name('products.adjustStock');

    // Inventory Module
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('index');
        Route::get('/movements', [App\Http\Controllers\Admin\InventoryController::class, 'movements'])->name('movements');
        Route::get('/movements/create', [App\Http\Controllers\Admin\InventoryController::class, 'createMovement'])->name('movements.create');
        Route::post('/movements', [App\Http\Controllers\Admin\InventoryController::class, 'storeMovement'])->name('movements.store');

        // Conteos físicos
        Route::get('/counts', [App\Http\Controllers\Admin\InventoryController::class, 'counts'])->name('counts.index');
        Route::get('/counts/create', [App\Http\Controllers\Admin\InventoryController::class, 'createCount'])->name('counts.create');
        Route::post('/counts', [App\Http\Controllers\Admin\InventoryController::class, 'storeCount'])->name('counts.store');
        Route::get('/counts/{count}', [App\Http\Controllers\Admin\InventoryController::class, 'showCount'])->name('counts.show');
        Route::post('/counts/{count}/start', [App\Http\Controllers\Admin\InventoryController::class, 'startCount'])->name('counts.start');
        Route::get('/counts/{count}/capture', [App\Http\Controllers\Admin\InventoryController::class, 'captureForm'])->name('counts.capture');
        Route::post('/counts/{count}/items/{item}', [App\Http\Controllers\Admin\InventoryController::class, 'saveCapture'])->name('counts.items.save');
        Route::post('/counts/{count}/complete', [App\Http\Controllers\Admin\InventoryController::class, 'completeCount'])->name('counts.complete');
        Route::post('/counts/{count}/reopen', [App\Http\Controllers\Admin\InventoryController::class, 'reopenCount'])->name('counts.reopen');
        Route::post('/counts/{count}/review', [App\Http\Controllers\Admin\InventoryController::class, 'reviewCount'])->name('counts.review');
        Route::get('/counts/{count}/export', [App\Http\Controllers\Admin\InventoryController::class, 'exportCount'])->name('counts.export');
    });

    // Categories
    Route::get('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/trash', [App\Http\Controllers\Admin\CategoryController::class, 'trash'])->name('categories.trash');
    Route::get('/categories/{id}/edit', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::patch('/categories/{id}/toggle-active', [App\Http\Controllers\Admin\CategoryController::class, 'toggleActive'])->name('categories.toggleActive');
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

    // Exclusive Landing
    Route::get('/exclusive-landing', [App\Http\Controllers\Admin\ExclusiveLandingConfigController::class, 'index'])->name('exclusive-landing.config');
    Route::put('/exclusive-landing', [App\Http\Controllers\Admin\ExclusiveLandingConfigController::class, 'update'])->name('exclusive-landing.update');
    Route::get('/exclusive-landing/phones', [App\Http\Controllers\Admin\AuthorizedPhoneController::class, 'index'])->name('exclusive-landing.phones.index');
    Route::post('/exclusive-landing/phones', [App\Http\Controllers\Admin\AuthorizedPhoneController::class, 'store'])->name('exclusive-landing.phones.store');
    Route::post('/exclusive-landing/phones/from-customer/{customer}', [App\Http\Controllers\Admin\AuthorizedPhoneController::class, 'addFromCustomer'])->name('exclusive-landing.phones.add-from-customer');
    Route::delete('/exclusive-landing/phones/from-customer/{customer}', [App\Http\Controllers\Admin\AuthorizedPhoneController::class, 'removeFromCustomer'])->name('exclusive-landing.phones.remove-from-customer');
    Route::post('/exclusive-landing/phones/from-customers-all', [App\Http\Controllers\Admin\AuthorizedPhoneController::class, 'addAllFromCustomers'])->name('exclusive-landing.phones.add-all-from-customers');
    Route::delete('/exclusive-landing/phones/{authorized_phone}', [App\Http\Controllers\Admin\AuthorizedPhoneController::class, 'destroy'])->name('exclusive-landing.phones.destroy');
    Route::patch('/exclusive-landing/phones/{authorized_phone}/toggle', [App\Http\Controllers\Admin\AuthorizedPhoneController::class, 'toggleActive'])->name('exclusive-landing.phones.toggle');

    // Orders
    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('/orders/{order}/payments-pdf', [App\Http\Controllers\Admin\OrderController::class, 'exportPaymentsPdf'])->name('orders.payments-pdf'); // New payments PDF route
    Route::post('/orders/{order}/payments', [App\Http\Controllers\Admin\OrderController::class, 'addPayment'])->name('orders.payments.store');
    Route::delete('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('orders.destroy');
    Route::put('/orders/{order}/customer', [App\Http\Controllers\Admin\OrderController::class, 'updateCustomer'])->name('orders.update-customer');
    Route::put('/orders/{order}/link-customer', [App\Http\Controllers\Admin\OrderController::class, 'linkCustomer'])->name('orders.link-customer');
    Route::post('/orders/{order}/register-customer', [App\Http\Controllers\Admin\OrderController::class, 'registerAsCustomer'])->name('orders.register-customer');
    Route::delete('/orders/{order}/payments/{payment}', [App\Http\Controllers\Admin\OrderController::class, 'destroyPayment'])->name('orders.payments.destroy');

    // Customers
    Route::get('/customers', [App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [App\Http\Controllers\Admin\CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [App\Http\Controllers\Admin\CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{id}', [App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('customers.show');
    Route::put('/customers/{id}', [App\Http\Controllers\Admin\CustomerController::class, 'update'])->name('customers.update');
    Route::post('/customers/{id}/notes', [App\Http\Controllers\Admin\CustomerController::class, 'addNote'])->name('customers.notes.store');

    // Payment Methods
    // Payment Methods
    Route::get('/payment-methods', [App\Http\Controllers\Admin\PaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::get('/payment-methods/create', [App\Http\Controllers\Admin\PaymentMethodController::class, 'create'])->name('payment-methods.create');
    Route::post('/payment-methods', [App\Http\Controllers\Admin\PaymentMethodController::class, 'store'])->name('payment-methods.store');
    Route::get('/payment-methods/{id}/edit', [App\Http\Controllers\Admin\PaymentMethodController::class, 'edit'])->name('payment-methods.edit');
    Route::put('/payment-methods/{id}', [App\Http\Controllers\Admin\PaymentMethodController::class, 'update'])->name('payment-methods.update');
    Route::delete('/payment-methods/{id}', [App\Http\Controllers\Admin\PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');

    // Shipping
    Route::get('/shipping', fn() => view('admin.shipping.index'))->name('shipping.index');
    Route::get('/shipping/zones/create', fn() => view('admin.shipping.zones.create'))->name('shipping.zones.create');
    Route::get('/shipping/zones/{id}/edit', fn($id) => view('admin.shipping.zones.edit', compact('id')))->name('shipping.zones.edit');

    // Administration
    Route::patch('users/{user}/role', [App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.update-role');
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::patch('assignments/{assignment}/status', [App\Http\Controllers\Admin\ProductAssignmentController::class, 'updateStatus'])->name('assignments.update-status');
    Route::get('assignments/export-pdf', [App\Http\Controllers\Admin\ProductAssignmentController::class, 'exportPdf'])->name('assignments.export-pdf');
    Route::get('assignments/export-excel', [App\Http\Controllers\Admin\ProductAssignmentController::class, 'exportExcel'])->name('assignments.export-excel');
    Route::resource('assignments', App\Http\Controllers\Admin\ProductAssignmentController::class);
    // Blog Module
    Volt::route('blog/posts', 'admin.blog.posts.index')->name('blog.posts.index');
    Volt::route('blog/posts/create', 'admin.blog.posts.create')->name('blog.posts.create');
    Volt::route('blog/posts/{post}/edit', 'admin.blog.posts.edit')->name('blog.posts.edit');
    Volt::route('blog/categories', 'admin.blog.categories.index')->name('blog.categories.index');
    Volt::route('blog/categories/create', 'admin.blog.categories.create')->name('blog.categories.create');
    Volt::route('blog/categories/{category}/edit', 'admin.blog.categories.edit')->name('blog.categories.edit');
    Volt::route('blog/categories/{category}/edit', 'admin.blog.categories.edit')->name('blog.categories.edit');
    Route::post('upload/image', [App\Http\Controllers\Admin\UploadController::class, 'upload'])->name('upload.image');

    // Tutorials
    Volt::route('tutorials', 'admin.tutorials.index')->name('tutorials.index');

    // Live Sessions
    Route::get('/live-sessions', function() {
        return view('admin.live-sessions');
    })->name('live-sessions.index');
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

<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\CompanyProfileController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProjectCategoryController;
use App\Http\Controllers\Admin\ProductTypeController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\ContactMessageController;

// Public Routes
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/projects', [PublicController::class, 'projects'])->name('projects.index');
Route::get('/projects/{slug}', [PublicController::class, 'projectDetail'])->name('projects.show');
Route::get('/clients', [PublicController::class, 'clients'])->name('clients.index');
Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [\App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
Route::get('/checkout/{slug}', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/{slug}', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/payment/{invoice_number}', [\App\Http\Controllers\CheckoutController::class, 'payment'])->name('checkout.payment');

Route::get('/download/{token}', [\App\Http\Controllers\DownloadController::class, 'download'])->name('products.download');
Route::post('/api/webhooks/midtrans/callback', [\App\Http\Controllers\WebhookController::class, 'midtrans']);

Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'storeContact'])->name('contact.store');

Route::get('/login', [\App\Http\Controllers\Auth\AuthController::class, 'create'])->name('login')->middleware('guest');
Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'store'])->middleware('guest');
Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'destroy'])->name('logout')->middleware('auth');

Route::middleware(['auth', 'is_tenant'])->prefix('tenant')->name('tenant.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Tenant\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/store', [\App\Http\Controllers\Tenant\StoreController::class, 'index'])->name('store.index');
    Route::post('/store', [\App\Http\Controllers\Tenant\StoreController::class, 'store'])->name('store.store');
    
    Route::resource('products', \App\Http\Controllers\Tenant\ProductController::class);
    Route::delete('products/image/{image}', [\App\Http\Controllers\Tenant\ProductController::class, 'destroyImage'])->name('products.image.destroy');
    Route::patch('products/{product}/toggle-active', [\App\Http\Controllers\Tenant\ProductController::class, 'toggleActive'])->name('products.toggle_active');
    Route::patch('products/image/{image}/set-main', [\App\Http\Controllers\Tenant\ProductController::class, 'setMainImage'])->name('products.image.set_main');
    
    Route::get('orders', [\App\Http\Controllers\Tenant\OrderController::class, 'index'])->name('orders.index');
    
    // Future routes for tenant
    Route::resource('payouts', \App\Http\Controllers\Tenant\PayoutController::class);
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('company', CompanyProfileController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('product_categories', ProductCategoryController::class)->except(['create', 'edit', 'show']);
    Route::resource('product_types', ProductTypeController::class)->except(['create', 'edit', 'show']);
    Route::resource('project_categories', ProjectCategoryController::class)->except(['create', 'edit', 'show']);
    Route::resource('products', ProductController::class);
    Route::delete('products/image/{image}', [ProductController::class, 'destroyImage'])->name('products.image.destroy');
    Route::patch('products/{product}/toggle-active', [ProductController::class, 'toggleActive'])->name('products.toggle_active');
    Route::patch('products/image/{image}/set-main', [ProductController::class, 'setMainImage'])->name('products.image.set_main');
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::resource('projects', ProjectController::class);
    Route::resource('testimonials', TestimonialController::class);
    Route::resource('messages', ContactMessageController::class);
    Route::resource('gateway_apps', \App\Http\Controllers\Admin\GatewayAppController::class);
    
    // Multi-tenant features
    Route::get('stores', [\App\Http\Controllers\Admin\StoreController::class, 'index'])->name('stores.index');
    Route::get('payouts', [\App\Http\Controllers\Admin\PayoutController::class, 'index'])->name('payouts.index');
    Route::patch('payouts/{payout}', [\App\Http\Controllers\Admin\PayoutController::class, 'update'])->name('payouts.update');
});

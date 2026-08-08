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

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('company', CompanyProfileController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('product_categories', ProductCategoryController::class)->except(['create', 'edit', 'show']);
    Route::resource('product_types', ProductTypeController::class)->except(['create', 'edit', 'show']);
    Route::resource('products', ProductController::class);
    Route::delete('products/image/{image}', [ProductController::class, 'destroyImage'])->name('products.image.destroy');
    Route::patch('products/{product}/toggle-active', [ProductController::class, 'toggleActive'])->name('products.toggle_active');
    Route::patch('products/image/{image}/set-main', [ProductController::class, 'setMainImage'])->name('products.image.set_main');
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::resource('projects', ProjectController::class);
    Route::resource('testimonials', TestimonialController::class);
    Route::resource('messages', ContactMessageController::class);
});

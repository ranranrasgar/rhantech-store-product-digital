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
use App\Http\Controllers\Admin\ProjectTypeController;
use App\Http\Controllers\Admin\ProductTypeController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\ContactMessageController;

// Public Routes
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/projects', [PublicController::class, 'projects'])->name('projects.index');
Route::get('/projects/{slug}', [PublicController::class, 'projectDetail'])->name('projects.show');
Route::get('/projects/{slug}/brochure', [PublicController::class, 'downloadBrochure'])->name('projects.brochure');
Route::get('/clients', [PublicController::class, 'clients'])->name('clients.index');
Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [\App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
Route::get('/products/{slug}/brochure', [\App\Http\Controllers\ProductController::class, 'brochure'])->name('products.brochure');

// Cart Routes
Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

// Checkout Routes
Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process')->middleware('throttle:checkout');
Route::post('/checkout/select', [\App\Http\Controllers\CheckoutController::class, 'selectItems'])->name('checkout.select');
Route::post('/checkout/apply-voucher', [\App\Http\Controllers\CheckoutController::class, 'applyVoucher'])->name('checkout.apply_voucher');
Route::post('/checkout/remove-voucher', [\App\Http\Controllers\CheckoutController::class, 'removeVoucher'])->name('checkout.remove_voucher');
Route::get('/checkout/store-vouchers', [\App\Http\Controllers\CheckoutController::class, 'getStoreVouchers'])->name('checkout.store_vouchers');
Route::get('/payment/{invoice_number}', [\App\Http\Controllers\CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/checkout/finish/{invoice_number}', [\App\Http\Controllers\CheckoutController::class, 'checkStatus'])->name('checkout.finish');
Route::get('/toko/{slug}', [\App\Http\Controllers\PublicStoreController::class, 'show'])->name('store.show');
Route::post('/toko/{store}/follow', [\App\Http\Controllers\PublicStoreController::class, 'toggleFollow'])->name('store.follow')->middleware('auth');
Route::get('/download/{token}', [\App\Http\Controllers\DownloadController::class, 'download'])->name('products.download');
Route::get('/download/{token}/file/{item}', [\App\Http\Controllers\DownloadController::class, 'downloadFile'])->name('products.download.file');
Route::post('/products/{product}/review', [\App\Http\Controllers\ProductReviewController::class, 'store'])->name('products.review.store');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'storeContact'])->name('contact.store');

// Help Center Routes
Route::get('/help', [\App\Http\Controllers\HelpController::class, 'index'])->name('help.index');
Route::get('/help/article/{slug}', [\App\Http\Controllers\HelpController::class, 'show'])->name('help.show');
Route::post('/help/article/{id}/feedback', [\App\Http\Controllers\HelpController::class, 'feedback'])->name('help.feedback');

// Legal & Compliance Routes (Regulasi RI: UU ITE, PP PMSE, UU PDP, UU Hak Cipta, UU Perlindungan Konsumen)
Route::get('/terms', [\App\Http\Controllers\LegalController::class, 'terms'])->name('legal.terms');
Route::get('/privacy', [\App\Http\Controllers\LegalController::class, 'privacy'])->name('legal.privacy');
Route::get('/copyright', [\App\Http\Controllers\LegalController::class, 'copyright'])->name('legal.copyright');
Route::get('/refund-policy', [\App\Http\Controllers\LegalController::class, 'refund'])->name('legal.refund');

Route::get('/login', [\App\Http\Controllers\Auth\AuthController::class, 'create'])->name('login')->middleware('guest');
Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'store'])->middleware(['guest', 'throttle:auth']);
Route::get('/register', [\App\Http\Controllers\Auth\AuthController::class, 'showRegisterForm'])->name('register')->middleware('guest');
Route::post('/register', [\App\Http\Controllers\Auth\AuthController::class, 'register'])->middleware(['guest', 'throttle:auth']);

// Socialite OAuth Routes (Google)
Route::get('/auth/{provider}', [\App\Http\Controllers\Auth\SocialiteController::class, 'redirectToProvider'])->name('social.redirect')->middleware('guest');
Route::get('/auth/{provider}/callback', [\App\Http\Controllers\Auth\SocialiteController::class, 'handleProviderCallback'])->name('social.callback')->middleware('guest');
Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'create'])->name('password.request')->middleware('guest');
Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'store'])->name('password.email')->middleware('guest');
Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\PasswordResetController::class, 'edit'])->name('password.reset')->middleware('guest');
Route::post('/reset-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'update'])->name('password.update')->middleware('guest');
Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'destroy'])->name('logout')->middleware('auth');

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('tenant.dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Email verifikasi telah dikirim ulang!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Backward compatibility redirect /tenant/{any?} -> /dashboard/{any?}
Route::any('/tenant/{any?}', function ($any = null) {
    return redirect('/dashboard' . ($any ? '/' . $any : ''), 301);
})->where('any', '.*');

Route::middleware(['auth', 'verified', 'is_tenant'])->prefix('dashboard')->name('tenant.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Tenant\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/store', [\App\Http\Controllers\Tenant\StoreController::class, 'index'])->name('store.index');
    Route::post('/store', [\App\Http\Controllers\Tenant\StoreController::class, 'store'])->name('store.store');
    
    // User Profile
    Route::get('/profile', [\App\Http\Controllers\Tenant\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [\App\Http\Controllers\Tenant\ProfileController::class, 'update'])->name('profile.update');
    
    // User Purchases
    Route::get('/purchases', [\App\Http\Controllers\Tenant\PurchaseController::class, 'index'])->name('purchases.index');
    
    Route::resource('products', \App\Http\Controllers\Tenant\ProductController::class);
    Route::delete('products/{product}/images/delete-all', [\App\Http\Controllers\Tenant\ProductController::class, 'destroyAllImages'])->name('products.images.destroy_all');
    Route::delete('products/image/{image}', [\App\Http\Controllers\Tenant\ProductController::class, 'destroyImage'])->name('products.image.destroy');
    Route::patch('products/{product}/toggle-active', [\App\Http\Controllers\Tenant\ProductController::class, 'toggleActive'])->name('products.toggle_active');
    Route::patch('products/image/{image}/set-main', [\App\Http\Controllers\Tenant\ProductController::class, 'setMainImage'])->name('products.image.set_main');
    
    Route::get('orders', [\App\Http\Controllers\Tenant\OrderController::class, 'index'])->name('orders.index');
    Route::put('orders/{order}', [\App\Http\Controllers\Tenant\OrderController::class, 'update'])->name('orders.update');
    Route::delete('orders/{order}', [\App\Http\Controllers\Tenant\OrderController::class, 'destroy'])->name('orders.destroy');
    Route::patch('orders/{order}/mark-paid', [\App\Http\Controllers\Tenant\OrderController::class, 'markPaid'])->name('orders.mark_paid');
    Route::post('orders/{order}/resend-email', [\App\Http\Controllers\Tenant\OrderController::class, 'resendEmail'])->name('orders.resend_email');
    
    Route::resource('payouts', \App\Http\Controllers\Tenant\PayoutController::class);
    Route::get('balance', [\App\Http\Controllers\Tenant\BalanceController::class, 'index'])->name('balance.index');
    Route::get('bank', [\App\Http\Controllers\Tenant\BankController::class, 'index'])->name('bank.index');
    Route::post('bank', [\App\Http\Controllers\Tenant\BankController::class, 'update'])->name('bank.update');
    Route::get('performance', [\App\Http\Controllers\Tenant\PerformanceController::class, 'index'])->name('performance.index');
    Route::get('appearance', [\App\Http\Controllers\Tenant\AppearanceController::class, 'index'])->name('appearance.index');
    Route::post('appearance', [\App\Http\Controllers\Tenant\AppearanceController::class, 'update'])->name('appearance.update');
    Route::post('appearance/upload', [\App\Http\Controllers\Tenant\AppearanceController::class, 'uploadImage'])->name('appearance.upload');
    Route::post('appearance/voucher-placement', [\App\Http\Controllers\Tenant\AppearanceController::class, 'saveVoucherPlacement'])->name('appearance.voucher-placement');

    // kerja sama
    Route::resource('affiliates', \App\Http\Controllers\Tenant\AffiliateController::class);
    Route::get('showcase', [\App\Http\Controllers\Tenant\ShowcaseController::class, 'index'])->name('showcase.index');
    Route::post('showcase/{product}/toggle', [\App\Http\Controllers\Tenant\ShowcaseController::class, 'toggle'])->name('showcase.toggle');
    
    Route::get('pro', [\App\Http\Controllers\Tenant\ProController::class, 'index'])->name('pro.index');
    Route::post('pro/upgrade', [\App\Http\Controllers\Tenant\ProController::class, 'upgrade'])->name('pro.upgrade');
    Route::get('pro/payment/{reference_no}', [\App\Http\Controllers\Tenant\ProController::class, 'payment'])->name('pro.payment');
    Route::get('pro/finish/{reference_no}', [\App\Http\Controllers\Tenant\ProController::class, 'finishPayment'])->name('pro.finish-payment');

    Route::get('broadcast', [\App\Http\Controllers\Tenant\BroadcastController::class, 'index'])->name('broadcast.index');
    Route::post('broadcast/send', [\App\Http\Controllers\Tenant\BroadcastController::class, 'send'])->name('broadcast.send');

    Route::resource('projects', \App\Http\Controllers\Tenant\TenantProjectController::class);

    // Kategori & Tipe Custom Toko Tenant
    Route::post('categories/quick-store', [\App\Http\Controllers\Tenant\ProductController::class, 'quickStoreCategory'])->name('categories.quick-store');
    Route::post('types/quick-store', [\App\Http\Controllers\Tenant\ProductController::class, 'quickStoreType'])->name('types.quick-store');

    // Iklan Diskon & Voucher Toko
    Route::resource('campaigns', \App\Http\Controllers\Tenant\CampaignController::class);

    // Pusat Iklan Toko & Promosi (Rhantech Seller Ads)
    Route::get('ads', [\App\Http\Controllers\Tenant\AdController::class, 'index'])->name('ads.index');
    Route::post('ads/claim-voucher', [\App\Http\Controllers\Tenant\AdController::class, 'claimWelcomeVoucher'])->name('ads.claim-voucher');
    Route::get('ads/top-up', [\App\Http\Controllers\Tenant\AdController::class, 'topUp'])->name('ads.top-up');
    Route::post('ads/top-up', [\App\Http\Controllers\Tenant\AdController::class, 'processTopUp'])->name('ads.process-top-up');
    Route::get('ads/payment/{reference_no}', [\App\Http\Controllers\Tenant\AdController::class, 'payment'])->name('ads.payment');
    Route::get('ads/finish/{reference_no}', [\App\Http\Controllers\Tenant\AdController::class, 'finishTopUp'])->name('ads.finish-top-up');
    Route::get('ads/create', [\App\Http\Controllers\Tenant\AdController::class, 'create'])->name('ads.create');
    Route::post('ads', [\App\Http\Controllers\Tenant\AdController::class, 'store'])->name('ads.store');
    Route::patch('ads/{ad}/toggle', [\App\Http\Controllers\Tenant\AdController::class, 'toggle'])->name('ads.toggle');
    Route::delete('ads/{ad}', [\App\Http\Controllers\Tenant\AdController::class, 'destroy'])->name('ads.destroy');

    // Chat Seller Center
    Route::get('chat', [\App\Http\Controllers\Tenant\ChatController::class, 'index'])->name('chat.index');
    Route::get('chat/conversations', [\App\Http\Controllers\Tenant\ChatController::class, 'getConversations'])->name('chat.conversations');
    Route::get('chat/messages/{userId}', [\App\Http\Controllers\Tenant\ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('chat/send', [\App\Http\Controllers\Tenant\ChatController::class, 'sendMessage'])->name('chat.send');
});

// Buyer Floating Chat Routes (Authenticated users)
Route::middleware(['auth'])->prefix('chat')->name('chat.')->group(function () {
    Route::get('/conversations', [\App\Http\Controllers\ChatController::class, 'getConversations'])->name('conversations');
    Route::get('/messages/{storeId}', [\App\Http\Controllers\ChatController::class, 'getMessages'])->name('messages');
    Route::post('/send', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('send');
    Route::post('/fcm-token', [\App\Http\Controllers\FcmController::class, 'storeToken'])->name('fcm-token');
});

Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::post('company/optimize-database', [CompanyProfileController::class, 'optimizeDatabase'])->name('company.optimize_database');
    Route::post('company/backup', [CompanyProfileController::class, 'backupDatabase'])->name('company.backup');
    Route::get('company/backup/download/{filename}', [CompanyProfileController::class, 'downloadBackup'])->name('company.backup.download')->where('filename', '.*');
    Route::delete('company/backup/{filename}', [CompanyProfileController::class, 'deleteBackup'])->name('company.backup.delete')->where('filename', '.*');
    Route::post('company/restore', [CompanyProfileController::class, 'restoreDatabase'])->name('company.restore');
    Route::post('company/backup-media', [CompanyProfileController::class, 'backupMedia'])->name('company.backup_media');
    Route::get('company/backup-media/download/{filename}', [CompanyProfileController::class, 'downloadMediaBackup'])->name('company.backup_media.download')->where('filename', '.*');
    Route::delete('company/backup-media/{filename}', [CompanyProfileController::class, 'deleteMediaBackup'])->name('company.backup_media.delete')->where('filename', '.*');
    Route::post('company/restore-media', [CompanyProfileController::class, 'restoreMedia'])->name('company.restore_media');
    Route::resource('company', CompanyProfileController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('product_categories', ProductCategoryController::class)->except(['create', 'edit', 'show']);
    Route::resource('product_types', ProductTypeController::class)->except(['create', 'edit', 'show']);
    Route::resource('project_categories', ProjectCategoryController::class)->except(['create', 'edit', 'show']);
    Route::resource('project_types', ProjectTypeController::class)->except(['create', 'edit', 'show']);
    Route::resource('products', ProductController::class);
    Route::patch('products/{product}/approve', [ProductController::class, 'approve'])->name('products.approve');
    Route::patch('products/{product}/reject', [ProductController::class, 'reject'])->name('products.reject');
    Route::delete('products/{product}/images/delete-all', [ProductController::class, 'destroyAllImages'])->name('products.images.destroy_all');
    Route::delete('products/image/{image}', [ProductController::class, 'destroyImage'])->name('products.image.destroy');
    Route::patch('products/{product}/toggle-active', [ProductController::class, 'toggleActive'])->name('products.toggle_active');
    Route::patch('products/image/{image}/set-main', [ProductController::class, 'setMainImage'])->name('products.image.set_main');
    Route::delete('products/review/{review}', [\App\Http\Controllers\ProductReviewController::class, 'destroy'])->name('products.reviews.destroy');
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::patch('orders/{order}/approve', [OrderController::class, 'approve'])->name('orders.approve');
    Route::post('orders/{order}/sync-status', [OrderController::class, 'syncStatus'])->name('orders.sync_status');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('projects/check-slug', [ProjectController::class, 'checkSlug'])->name('projects.check_slug');
    Route::delete('projects/image/{image}', [ProjectController::class, 'destroyImage'])->name('projects.image.destroy');
    Route::resource('projects', ProjectController::class);
    Route::resource('testimonials', TestimonialController::class);
    Route::resource('messages', ContactMessageController::class);
    Route::resource('gateway_apps', \App\Http\Controllers\Admin\GatewayAppController::class);
    Route::resource('popup_ads', \App\Http\Controllers\Admin\PopupAdController::class);
    
    // Banners
    Route::get('banners', [\App\Http\Controllers\Admin\BannerController::class, 'index'])->name('banners.index');
    Route::post('banners/update', [\App\Http\Controllers\Admin\BannerController::class, 'update'])->name('banners.update');
    
    // Help Center
    Route::resource('help_categories', \App\Http\Controllers\Admin\HelpCategoryController::class);
    Route::resource('help_articles', \App\Http\Controllers\Admin\HelpArticleController::class);
    
    // Multi-tenant features
    Route::get('stores', [\App\Http\Controllers\Admin\StoreController::class, 'index'])->name('stores.index');
    Route::patch('stores/{store}', [\App\Http\Controllers\Admin\StoreController::class, 'update'])->name('stores.update');
    Route::get('payouts', [\App\Http\Controllers\Admin\PayoutController::class, 'index'])->name('payouts.index');
    Route::patch('payouts/{payout}', [\App\Http\Controllers\Admin\PayoutController::class, 'update'])->name('payouts.update');
    Route::get('ads', [\App\Http\Controllers\Admin\AdController::class, 'index'])->name('ads.index');

    // Manajemen Paket Toko PRO
    Route::patch('pro_plans/{pro_plan}/toggle-active', [\App\Http\Controllers\Admin\ProPlanController::class, 'toggleActive'])->name('pro_plans.toggle-active');
    Route::resource('pro_plans', \App\Http\Controllers\Admin\ProPlanController::class);

    // Buyer Search Analytics
    Route::get('searches', [\App\Http\Controllers\Admin\SearchAnalyticsController::class, 'index'])->name('searches.index');
    Route::delete('searches/{search}', [\App\Http\Controllers\Admin\SearchAnalyticsController::class, 'destroy'])->name('searches.destroy');
});

// Cloudflare R2 Media Proxy / Redirect Fallback for local /storage/{path} requests
Route::get('/storage/{path}', function (string $path) {
    // If local file exists, serve it directly
    $localFilePath = storage_path('app/public/' . $path);
    if (file_exists($localFilePath)) {
        return response()->file($localFilePath);
    }

    // Otherwise redirect to Cloudflare R2 CDN
    $r2Url = rtrim(config('filesystems.disks.r2.url', 'https://cdn.rhantech.com'), '/') . '/' . ltrim($path, '/');
    return redirect()->away($r2Url, 302);
})->where('path', '.*');

// Direct Store URL: http://127.0.0.1:8000/<nama-toko> (e.g., http://127.0.0.1:8000/gudang-aplikasi)
Route::get('/{slug}', [\App\Http\Controllers\PublicStoreController::class, 'show'])
    ->where('slug', '^(?!admin|tenant|dashboard|about|projects|products|clients|cart|checkout|payment|download|contact|help|terms|privacy|copyright|refund-policy|login|register|logout|forgot-password|reset-password|email|storage|chat|toko).*$')
    ->name('store.direct');


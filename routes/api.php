<?php

use Illuminate\Support\Facades\Route;

Route::match(['get', 'post', 'head'], '/webhooks/midtrans/callback', [\App\Http\Controllers\WebhookController::class, 'midtrans']);

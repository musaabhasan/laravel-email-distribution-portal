<?php

use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\ComplianceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MfaController;
use App\Http\Controllers\RecipientController;
use App\Http\Controllers\RecipientGroupController;
use App\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/mfa/challenge', [MfaController::class, 'show'])->name('mfa.challenge');
    Route::post('/mfa/challenge', [MfaController::class, 'verify'])->name('mfa.verify');
});

Route::get('/unsubscribe/{recipient:uuid}', [RecipientController::class, 'unsubscribe'])
    ->middleware('signed')
    ->name('recipients.unsubscribe');

Route::middleware(['auth', 'mfa', 'audit.admin'])->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('recipients', RecipientController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('groups', RecipientGroupController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('templates', TemplateController::class);
    Route::resource('broadcasts', BroadcastController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('/broadcasts/{broadcast}/approve', [BroadcastController::class, 'approve'])->name('broadcasts.approve');
    Route::post('/broadcasts/{broadcast}/queue', [BroadcastController::class, 'queue'])->name('broadcasts.queue');
    Route::get('/compliance', ComplianceController::class)->name('compliance.index');
});

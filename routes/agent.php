<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Agent\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Agent\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Agent\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Agent\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Agent\Auth\NewPasswordController;
use App\Http\Controllers\Agent\Auth\PasswordResetLinkController;
use App\Http\Controllers\Agent\Auth\RegisteredUserController;
use App\Http\Controllers\Agent\Auth\VerifyEmailController;
use App\Http\Controllers\Agent\DashboardController;
use App\Http\Controllers\Agent\ProducteurController;
use App\Http\Controllers\Agent\CollecteController;

Route::prefix('agent')->name('agent.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])
        ->middleware('agent.auth');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('agent.auth')
        ->name('dashboard');

    Route::resource('producteurs', ProducteurController::class)->middleware('agent.auth');
    Route::resource('collectes', CollecteController::class)->middleware('agent.auth');

    Route::get('/register', [RegisteredUserController::class, 'create'])
                ->middleware('guest:agent')
                ->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store'])
                ->middleware('guest:agent');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
                ->middleware('guest:agent')
                ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware('guest:agent');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->middleware('guest:agent')
                ->name('password.request');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->middleware('guest:agent')
                ->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->middleware('guest:agent')
                ->name('password.reset');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
                ->middleware('guest:agent')
                ->name('password.update');

    Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->middleware('agent.auth')
                ->name('verification.notice');

    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware([
                    'agent.auth', 
                    'signed', 
                    'throttle:6,1'
                ])
                ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware([
                    'agent.auth', 
                    'throttle:6,1'
                ])
                ->name('verification.send');

    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->middleware('agent.auth')
                ->name('password.confirm');

    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
                ->middleware('agent.auth');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('agent.auth')
                ->name('logout');
});

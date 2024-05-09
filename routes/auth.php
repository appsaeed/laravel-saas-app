<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Route;

if ( config( 'account.can_register' ) ) {
    // Registration Routes...
    Route::get( 'register', [RegisterController::class, 'showRegistrationForm'] )->name( 'register' );
    Route::post( 'register', [RegisterController::class, 'register'] );

    Route::get( '/email/verify', [VerificationController::class, 'verificationNotice'] )->middleware( ['auth'] )->name( 'verification.notice' );
    Route::get( '/email/verify/{id}/{hash}', [VerificationController::class, 'verificationVerify'] )->middleware( ['auth', 'signed'] )->name( 'verification.verify' );
    Route::post( '/email/verification-notification', [VerificationController::class, 'verificationSend'] )->middleware( ['auth', 'throttle:6,1'] )->name( 'verification.send' );
}

// Authentication Routes...
Route::get( 'login', [LoginController::class, 'showLoginForm'] )->name( 'login' );
Route::post( 'login', [LoginController::class, 'login'] );
Route::post( 'logout', [LoginController::class, 'logout'] )->name( 'logout' );

Route::get( 'login/{provider}', [LoginController::class, 'redirectToProvider'] )->name( 'social.login' );
Route::get( 'login/{provider}/callback', [LoginController::class, 'handleProviderCallback'] )->name( 'social.callback' );

// Password Reset Routes...
Route::get( 'password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'] )->name( 'password.request' );
Route::post( 'password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'] )->name( 'password.email' );
Route::get( 'password/reset/{token}', [ResetPasswordController::class, 'showResetForm'] )->name( 'password.reset' );
Route::post( 'password/reset', [ResetPasswordController::class, 'reset'] )->name( 'password.update' );

//two-step verification routes
Route::get( 'verify/resend', [TwoFactorController::class, 'resend'] )->name( 'verify.resend' );
Route::get( 'verify/backup-code', [TwoFactorController::class, 'backUpCode'] )->name( 'verify.backup' );
Route::post( 'verify/backup-code', [TwoFactorController::class, 'updateBackUpCode'] );
Route::resource( 'verify', TwoFactorController::class )->only( ['index', 'store'] );

//common or public data access routes
Route::get( 'download-sample-file', [LoginController::class, 'downloadSampleFile'] )->name( 'sample.file' );

//test or debug or var_dump route
Route::get( 'debug', [LoginController::class, 'debug'] )->name( 'debug' );

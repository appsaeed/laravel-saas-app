<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

if (config('account.can_register')) {
    // Registration Routes...
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    Route::get('/email/verify', [VerificationController::class, 'verificationNotice'])->middleware(['auth'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verificationVerify'])->middleware(['auth', 'signed'])->name('verification.verify');
    Route::post('/email/verification-notification', [VerificationController::class, 'verificationSend'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');
}


// Authentication Routes...
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('login/{provider}', [LoginController::class, 'redirectToProvider'])->name('social.login');
Route::get('login/{provider}/callback', [LoginController::class, 'handleProviderCallback'])->name('social.callback');

// Password Reset Routes...
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

//two-step verification routes
Route::get('verify/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');
Route::get('verify/backup-code', [TwoFactorController::class, 'backUpCode'])->name('verify.backup');
Route::post('verify/backup-code', [TwoFactorController::class, 'updateBackUpCode']);
Route::resource('verify', TwoFactorController::class)->only(['index', 'store']);

//common or public data access routes
Route::get('download-sample-file', [LoginController::class, 'downloadSampleFile'])->name('sample.file');

//test or debug or var_dump route
Route::get('debug', [LoginController::class, 'debug'])->name('debug');

Route::group(
    [
        'as'         => 'user.',
        'middleware' => ['auth', 'verified'],
    ],
    function () {
        /*
         * User Dashboard Specific
         */
        Route::get('/dashboard', [UserController::class, 'index'])->name('home');

        /*
         * switch view
         */
        Route::get('/switch-view', [AccountController::class, 'switchView'])->name('switch_view');

        /*
         * User Account Specific
         */
        Route::get('account', [AccountController::class, 'index'])->name('account');
        Route::get('avatar', [AccountController::class, 'avatar'])->name('avatar');
        Route::post('avatar', [AccountController::class, 'updateAvatar'])->name('avatar.post');
        Route::post('remove-avatar', [AccountController::class, 'removeAvatar'])
            ->name('remove_avatar');

        /*
         * User Profile Update
         */
        Route::patch('account/update', [AccountController::class, 'update'])->name('account.update');
        Route::post('account/update-information', [AccountController::class, 'updateInformation'])->name('account.update_information');

        Route::post('account/change-password', [AccountController::class, 'changePassword'])->name('account.change.password');

        Route::get('account/two-factor/{status}', [AccountController::class, "twoFactorAuthentication"])->name('account.twofactor.auth');

        Route::get('account/generate-two-factor-code', [AccountController::class, 'generateTwoFactorAuthenticationCode'])->name('account.twofactor.generate_code');

        Route::post('account/two-factor/{status}', [AccountController::class, 'updateTwoFactorAuthentication']);

        //notifications
        Route::post('account/notifications', [AccountController::class, 'notifications'])
            ->name('account.notifications');
        Route::post('account/notifications/mark-open', [AccountController::class, 'notificationsMarkOpen'])
            ->name('account.notifications.mark_open');
        Route::post('account/notifications/{notification}/active', [AccountController::class, 'notificationToggle'])
            ->name('account.notifications.toggle');

        Route::post('account/notifications/{notification}/delete', [AccountController::class, 'deleteNotification'])->name('account.notifications.delete');

        Route::post('notifications/batch_action', [AccountController::class, 'notificationBatchAction'])->name('account.notifications.batch_action');


        if (config('account.can_delete')) {
            /*
             * Account delete
             */
            Route::delete('account/delete', [AccountController::class, 'delete'])->name('account.delete');
        }
    }
);

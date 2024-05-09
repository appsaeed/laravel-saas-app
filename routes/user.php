<?php

use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

/*
 * User Dashboard Specific
 */
Route::get( '/dashboard', [UserController::class, 'index'] )->name( 'home' );

/*
 * switch view
 */
Route::get( '/switch-view', [AccountController::class, 'switchView'] )->name( 'switch_view' );

/*
 * User Account Specific
 */
Route::get( 'account', [AccountController::class, 'index'] )->name( 'account' );
Route::get( 'avatar', [AccountController::class, 'avatar'] )->name( 'avatar' );
Route::post( 'avatar', [AccountController::class, 'updateAvatar'] )->name( 'avatar.post' );
Route::post( 'remove-avatar', [AccountController::class, 'removeAvatar'] )
    ->name( 'remove_avatar' );

/*
 * User Profile Update
 */
Route::patch( 'account/update', [AccountController::class, 'update'] )->name( 'account.update' );
Route::post( 'account/update-information', [AccountController::class, 'updateInformation'] )->name( 'account.update_information' );

Route::post( 'account/change-password', [AccountController::class, 'changePassword'] )->name( 'account.change.password' );

Route::get( 'account/two-factor/{status}', [AccountController::class, "twoFactorAuthentication"] )->name( 'account.twofactor.auth' );

Route::get( 'account/generate-two-factor-code', [AccountController::class, 'generateTwoFactorAuthenticationCode'] )->name( 'account.twofactor.generate_code' );

Route::post( 'account/two-factor/{status}', [AccountController::class, 'updateTwoFactorAuthentication'] );

//notifications
Route::post( 'account/notifications', [AccountController::class, 'notifications'] )
    ->name( 'account.notifications' );
Route::post( 'account/notifications/mark-open', [AccountController::class, 'notificationsMarkOpen'] )
    ->name( 'account.notifications.mark_open' );
Route::post( 'account/notifications/{notification}/active', [AccountController::class, 'notificationToggle'] )
    ->name( 'account.notifications.toggle' );

Route::post( 'account/notifications/{notification}/delete', [AccountController::class, 'deleteNotification'] )->name( 'account.notifications.delete' );

Route::post( 'notifications/batch_action', [AccountController::class, 'notificationBatchAction'] )->name( 'account.notifications.batch_action' );

if ( config( 'account.can_delete' ) ) {
    Route::delete( 'account/delete', [AccountController::class, 'delete'] )->name( 'account.delete' );
}

<?php

use App\Http\Controllers\Customer\ChatBoxController;
use App\Http\Controllers\Customer\DeveloperController;
use App\Http\Controllers\Customer\TodosController;
use App\Http\Controllers\User\AccountController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Todos module
|--------------------------------------------------------------------------
 */

Route::get( 'get-avatar/{user}', [AccountController::class, 'getAvatar'] )->name( 'getAvatar' );
/*
|--------------------------------------------------------------------------
| Todos module
|--------------------------------------------------------------------------
 */
Route::group( ['prefix' => 'tasks', 'as' => 'tasks.'], function () {

    Route::post( '/{task}/will-do', [TodosController::class, 'will_do'] )->name( 'will_do' );
    //task send to review
    Route::post( '/{task}/review', [TodosController::class, 'send_review'] )->name( 'send_review' );
    //task mark as completed
    Route::post( '/{task}/mark-as-ompete', [TodosController::class, 'markAsComplete'] )
        ->name( 'mark_as_complete' );

    //task pause and continue
    Route::post( '/{task}/pause_task', [TodosController::class, 'pauseTask'] )->name( 'pause' );
    Route::post( '/{task}/continue_task', [TodosController::class, 'continueTask'] )
        ->name( 'continueTask' );

    Route::get( '/reviews', [TodosController::class, 'reviews'] )->name( 'reviews' );
    Route::post( '/reviews', [TodosController::class, 'reviewsSearch'] )
        ->name( 'reviewsSearch' );

    Route::get( '/in-progress', [TodosController::class, 'inProgress'] )->name( 'in_progress' );
    Route::post( '/in-progress', [TodosController::class, 'inProgressSearch'] )
        ->name( 'in_progress.search' );

    Route::post( '/batch_action', [TodosController::class, 'batchAction'] )
        ->name( 'batch_action' );

    Route::get( '/receives', [TodosController::class, 'received'] )
        ->name( 'receives' );

    Route::post( '/receives', [TodosController::class, 'receivedSearch'] )
        ->name( 'receivedSearch' );

    Route::get( '/complete', [TodosController::class, 'complete'] )->name( 'complete' );
    Route::post( '/complete', [TodosController::class, 'completeSearch'] )
        ->name( 'complete.search' );

    Route::get( '/yours-tasks', [TodosController::class, 'mytasks'] )->name( 'mytasks' );
    Route::post( '/yours-tasks', [TodosController::class, 'mytasksSearch'] )->name( 'mytasksSearch' );

    Route::post( '/search', [TodosController::class, 'search'] )->name( 'search' );
} );
Route::resource( 'tasks', TodosController::class );

/*
|-------------------
| Check box module
|-------------------
 */
Route::prefix( 'chat' )->name( 'chat.' )->group( function () {
    // Route::get('/', [ChatBoxController::class, 'index'])->name('index');
    Route::get( '/{task}', [ChatBoxController::class, 'open'] )->name( 'open' );
    Route::get( '/{task}/{box}', [ChatBoxController::class, 'open'] )->name( 'opne_with_user' );
    Route::post( '/{box}/messages', [ChatBoxController::class, 'messages'] )->name( 'messages' );
    Route::post( '/{box}/notification', [ChatBoxController::class, 'messagesWithNotification'] )->name( 'notification' );
    Route::post( '/{box}/reply', [ChatBoxController::class, 'reply'] )->name( 'reply' );
    Route::post( '/{box}/reply-to', [ChatBoxController::class, 'replyTo'] )->name( 'reply_to' );
    Route::post( '/{box}/delete', [ChatBoxController::class, 'delete'] )->name( 'delete' );
    Route::post( '/{box}/block', [ChatBoxController::class, 'block'] )->name( 'block' );
} );
Route::get( 'messages/{task}/new', [ChatBoxController::class, 'new'] )->name( 'chat.new' );
Route::post( 'messages/store', [ChatBoxController::class, 'store'] )->name( 'chat.store' );
Route::get( 'messages/{task}/me', [ChatBoxController::class, 'receiver'] )->name( 'chat.receiver' );

/*
|--------------------------------------------------------------------------
| Todos module
|--------------------------------------------------------------------------
 */
Route::group( ['prefix' => 'projects', 'as' => 'projects.'], function () {

    Route::post( '/{task}/will-do', [TodosController::class, 'will_do'] )->name( 'will_do' );
    //task send to review
    Route::post( '/{task}/review', [TodosController::class, 'send_review'] )->name( 'send_review' );
    //task mark as completed
    Route::post( '/{task}/mark-as-ompete', [TodosController::class, 'markAsComplete'] )
        ->name( 'mark_as_complete' );

    //task pause and continue
    Route::post( '/{task}/pause_task', [TodosController::class, 'pauseTask'] )->name( 'pause' );
    Route::post( '/{task}/continue_task', [TodosController::class, 'continueTask'] )
        ->name( 'continueTask' );

    Route::get( '/reviews', [TodosController::class, 'reviews'] )->name( 'reviews' );
    Route::post( '/reviews', [TodosController::class, 'reviewsSearch'] )
        ->name( 'reviewsSearch' );

    Route::get( '/in-progress', [TodosController::class, 'inProgress'] )->name( 'in_progress' );
    Route::post( '/in-progress', [TodosController::class, 'inProgressSearch'] )
        ->name( 'in_progress.search' );

    Route::post( '/batch_action', [TodosController::class, 'batchAction'] )
        ->name( 'batch_action' );

    Route::get( '/receives', [TodosController::class, 'received'] )
        ->name( 'receives' );

    Route::post( '/receives', [TodosController::class, 'receivedSearch'] )
        ->name( 'receivedSearch' );

    Route::get( '/complete', [TodosController::class, 'complete'] )->name( 'complete' );
    Route::post( '/complete', [TodosController::class, 'completeSearch'] )
        ->name( 'complete.search' );

    Route::get( '/yours-tasks', [TodosController::class, 'mytasks'] )->name( 'mytasks' );
    Route::post( '/yours-tasks', [TodosController::class, 'mytasksSearch'] )->name( 'mytasksSearch' );

    Route::post( '/search', [TodosController::class, 'search'] )->name( 'search' );
} );
Route::resource( 'projects', TodosController::class );

/*
|--------------------------------------------------------------------------
| Developer module
|--------------------------------------------------------------------------
 */
Route::get( 'developers', [DeveloperController::class, 'settings'] )
    ->name( 'developer.settings' );
Route::post( 'developers/generate', [DeveloperController::class, 'generate'] )
    ->name( 'developer.generate' );
Route::get( 'developers/docs', [DeveloperController::class, 'docs'] )->name( 'developer.docs' );

<?php

use App\Http\Controllers\Customer\ChatBoxController;
use App\Http\Controllers\Customer\TodosController;
use App\Http\Controllers\User\AccountController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Todos module
|--------------------------------------------------------------------------
 */

Route::get('get-avatar/{user}', [AccountController::class, 'getAvatar'])->name('getAvatar');
/*
|--------------------------------------------------------------------------
| Todos module
|--------------------------------------------------------------------------
 */





Route::post('todos/complete/search', [TodosController::class, 'completeSearch'])
    ->name('todos.complete.search');
Route::post('todos/reviews/search', [TodosController::class, 'reviewsSearch'])
    ->name('todos.reviewsSearch');


Route::post('todos/{todo}will-do', [TodosController::class, 'will_do'])->name('todos.will_do');
Route::post('todos/{todo}review', [TodosController::class, 'review'])->name('todos.send_review');
Route::post('todos/{todo}/mark-as-ompete', [TodosController::class, 'markAsComplete'])
    ->name('todos.mark_as_complete');
Route::post('todos/{todo}pause_task', [TodosController::class, 'pauseTask'])->name('todos.pause');
Route::post('todos/{todo}continue_task', [TodosController::class, 'continueTask'])
    ->name('todos.continueTask');


Route::get('todos/complete', [TodosController::class, 'complete'])->name('todos.complete');
Route::get('todos/reviews', [TodosController::class, 'reviews'])->name('todos.reviews');
Route::get('todos/all', [TodosController::class, 'created'])->name('todos.all');

Route::post('todos/search', [TodosController::class, 'search'])->name('todos.search');

Route::get('todos/created', [TodosController::class, 'created'])->name('todos.created');
Route::post('todos/_created', [TodosController::class, 'createdSearch'])->name('todos._created');


Route::get('todos/in-progress', [TodosController::class, 'inProgress'])->name('todos.in_progress');
Route::post('todos/inp-search', [TodosController::class, 'inProgressSearch'])
    ->name('todos.in_progress.search');


Route::post('todos/batch_action', [TodosController::class, 'batchAction'])
    ->name('todos.batch_action');

Route::get('todos/receives', [TodosController::class, 'received'])->name('todos.receives');
Route::post('todos/received-all', [TodosController::class, 'receivedSearch'])
    ->name('todos.receivedSearch');

Route::resource('todos', TodosController::class);

/*
|-------------------
| Check box module
|-------------------
*/
Route::prefix('chat')->name('chat.')->group(function () {
    // Route::get('/', [ChatBoxController::class, 'index'])->name('index');
    Route::get('/{todo}', [ChatBoxController::class, 'open'])->name('open');
    Route::get('/{todo}/{box}', [ChatBoxController::class, 'open'])->name('opne_with_user');
    Route::post('/{box}/messages', [ChatBoxController::class, 'messages'])->name('messages');
    Route::post('/{box}/notification', [ChatBoxController::class, 'messagesWithNotification'])->name('notification');
    Route::post('/{box}/reply', [ChatBoxController::class, 'reply'])->name('reply');
    Route::post('/{box}/reply-to', [ChatBoxController::class, 'replyTo'])->name('reply_to');
    Route::post('/{box}/delete', [ChatBoxController::class, 'delete'])->name('delete');
    Route::post('/{box}/block', [ChatBoxController::class, 'block'])->name('block');
});
Route::get('messages/{todo}/new', [ChatBoxController::class, 'new'])->name('chat.new');
Route::post('messages/store', [ChatBoxController::class, 'store'])->name('chat.store');
Route::get('messages/{todo}/me', [ChatBoxController::class, 'receiver'])->name('chat.receiver');

/*
|--------------------------------------------------------------------------
| Developer module
|--------------------------------------------------------------------------
 */
// Route::get('developers', 'DeveloperController@settings')
//     ->name('developer.settings');
// Route::post('developers/generate', 'DeveloperController@generate')
//     ->name('developer.generate');
// Route::post('developers/sending-server', 'DeveloperController@sendingServer')
//     ->name('developer.server');
// Route::get('developers/docs', 'DeveloperController@docs')->name('developer.docs');

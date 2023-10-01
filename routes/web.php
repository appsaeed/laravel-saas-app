<?php

use App\Console\Commands\TaskDeadlineReminder;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Testcontroller;
use Arcanedev\Html\Elements\File;
use Database\Seeders\Countries;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/test', [Testcontroller::class, 'index']);

Route::get('/clear', function () {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');

    return "Cleared!";
});

Route::get('/readme', function () {
    return Markdown::parse(file_get_contents(base_path('/README.md')));
});


Route::get('/', function () {

    //redirect for install when application mode is new
    if (config('app.stage') == 'new') {
        return redirect('install');
    }

    //redirect for update
    // if (config('app.stage') == 'Live' && config('app.version') == '2.8') {
    //     return redirect('update');
    // }

    return redirect('login');
});

// locale Route
Route::get('lang/{locale}', [LanguageController::class, 'swap']);
Route::any('languages', [LanguageController::class, 'languages'])->name('languages');

if (config('app.stage') == 'local') {


    Route::get('update-file', function () {
        $app_path = base_path() . '/bootstrap/cache/';
        if (File::isDirectory($app_path)) {
            File::cleanDirectory($app_path);
        }
    });

    Route::get('update-country', function () {
        $countries = new Countries();
        $countries->run();
    });
}

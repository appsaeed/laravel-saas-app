<?php

use App\Http\Controllers\InstallerController;
use App\Http\Controllers\UpdateController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| installer file
|--------------------------------------------------------------------------
|
|
|
 */

Route::group(['prefix' => 'install', 'as' => 'Installer::', 'middleware' => ['web', 'install']], function () {

    Route::get('/', [InstallerController::class, 'welcome'])->name('welcome');
    Route::get('environment', [InstallerController::class, 'environment'])->name('environment');
    Route::get('environment/wizard', [InstallerController::class, 'environmentWizard'])
        ->name('environmentWizard');
    Route::post('environment/database', [InstallerController::class, 'saveDatabase'])
        ->name('environmentDatabase');
    Route::get('requirements', [InstallerController::class, 'requirements'])->name('requirements');
    Route::get('permissions', [InstallerController::class, 'permissions'])->name('permissions');
    Route::post('database', [InstallerController::class, 'database'])->name('database');
    Route::get('final', [InstallerController::class, 'finish'])->name('final');
});

Route::group(['prefix' => 'update', 'as' => 'Updater::', 'middleware' => ['web', 'update']], function () {

    Route::get('/', [UpdateController::class, 'welcome'])->name('welcome');
    Route::post('/', [UpdateController::class, 'verifyProduct'])->name('verify_product');
});

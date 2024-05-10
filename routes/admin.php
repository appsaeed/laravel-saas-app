<?php

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Controllers\Admin\AdministratorController;
use App\Http\Controllers\Admin\CountriesController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\PluginsController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\ThemeCustomizerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * @link htps://appsaeed.github.io/
 * @author Appsaeed <appsaeed@gmail.com>
 * Create admin dashboard routes
 */

Route::resource( 'dashboard', AdminBaseController::class, [
    'only' => ['index'],
] )->names( ['index' => 'home'] );

Route::get( '/serve', [AdminBaseController::class, 'serverDBBackup'] )->name( 'serverDBBackup' );

/*
|--------------------------------------------------------------------------
| Customer module
|--------------------------------------------------------------------------
|
| Route for Customer module
|
 */
Route::post( 'users/search', [CustomerController::class, 'search'] )->name( 'customers.search' );
Route::get( 'users/export', [CustomerController::class, 'export'] )->name( 'customers.export' );

Route::get( 'users/{customer}/show', [CustomerController::class, 'show'] )
    ->name( 'customers.show' );
Route::get( 'users/{customer}/login-as', [CustomerController::class, 'impersonate'] )
    ->name( 'customers.login_as' );
Route::get( 'users/{customer}/avatar', [CustomerController::class, 'avatar'] )
    ->name( 'customers.avatar' );
Route::post( 'users/{customer}/avatar', [CustomerController::class, 'updateAvatar'] );
Route::post( 'users/{customer}/remove-avatar', [CustomerController::class, 'removeAvatar'] );
Route::post( 'users/{customer}/add-unit', [CustomerController::class, 'addUnit'] )
    ->name( 'customers.add_unit' );
Route::post( 'users/{customer}/remove-unit', [CustomerController::class, 'removeUnit'] )
    ->name( 'customers.remove_unit' );
Route::post( 'users/{customer}/update-information', [
    CustomerController::class, 'updateInformation',
] )->name( 'customers.update_information' );
Route::post( 'users/{customer}/permissions', [CustomerController::class, 'permissions'] )
    ->name( 'customers.permissions' );
Route::post( 'users/{customer}/active', [CustomerController::class, 'activeToggle'] )
    ->name( 'customers.active' );
Route::post( 'users/batch_action', [
    CustomerController::class, 'batchAction',
] )->name( 'customers.batch_action' );

Route::resource( 'users', CustomerController::class, [
    'only' => ['index', 'create', 'store', 'update', 'destroy'],
] )->names( [
    'index' => 'customers.index',
    'create' => 'customers.create',
    'update' => 'customers.update',
    'store' => 'customers.store',
    'destroy' => 'customers.destroy',
] )->parameters( [
    'users' => 'customer',
] );
/*
|--------------------------------------------------------------------------
| Currency module
|--------------------------------------------------------------------------
|
| Route for Currency module
|
 */
// Define specific routes first
Route::post( 'currencies/search', [CurrencyController::class, 'search'] )->name( 'currencies.search' );
Route::get( 'currencies/export', [CurrencyController::class, 'export'] )->name( 'currencies.export' );
Route::get( 'currencies/{currency}/show', [CurrencyController::class, 'show'] )->name( 'currencies.show' );
Route::post( 'currencies/{currency}/active', [CurrencyController::class, 'activeToggle'] )->name( 'currencies.active' );
Route::post( 'currencies/batch_action', [CurrencyController::class, 'batchAction'] )->name( 'currencies.batch_action' );

// Define the resource route with specific actions
Route::resource( 'currencies', CurrencyController::class )->only( ['index', 'create', 'store', 'update', 'destroy'] );

/*
|--------------------------------------------------------------------------
| Administrator Module
|--------------------------------------------------------------------------
|
| working with different types of admin and associate admin role
|
 */

//Admin Role Module
// Define specific routes first
Route::post( 'roles/search', [RoleController::class, 'search'] )
    ->name( 'roles.search' );

Route::get( 'roles/export', [RoleController::class, 'export'] )
    ->name( 'roles.export' );

Route::get( 'roles/{role}/show', [RoleController::class, 'show'] )
    ->name( 'roles.show' );

Route::post( 'roles/{role}/active', [RoleController::class, 'activeToggle'] )
    ->name( 'roles.active' );

Route::post( 'roles/batch_action', [RoleController::class, 'batchAction'] )
    ->name( 'roles.batch_action' );

Route::resource( 'roles', RoleController::class )->only( [
    'index', 'create', 'store', 'update', 'destroy',
] );

//Administrator Module
Route::post( 'administrators/search', [AdministratorController::class, 'search'] )
    ->name( 'administrators.search' );

Route::get( 'administrators/export', [AdministratorController::class, 'export'] )
    ->name( 'administrators.export' );

Route::get( 'administrators/{administrator}/show', [AdministratorController::class, 'show'] )
    ->name( 'administrators.show' );

Route::post( 'administrators/{administrator}/active', [AdministratorController::class, 'activeToggle'] )
    ->name( 'administrators.active' );

Route::post( 'administrators/batch_action', [AdministratorController::class, 'batchAction'] )
    ->name( 'administrators.batch_action' );

// Define the resource route with specific actions
Route::resource( 'administrators', AdministratorController::class )
    ->only( ['index', 'create', 'store', 'update', 'destroy'] );

/*
|--------------------------------------------------------------------------
| settings module
|--------------------------------------------------------------------------
|
| All settings related routes describe here
|
 */

//All Settings
Route::get( 'settings', [SettingsController::class, 'general'] )
    ->name( 'settings.general' );
Route::post( 'settings', [SettingsController::class, 'postGeneral'] );
Route::post( 'settings/email', [SettingsController::class, 'email'] )->name( 'settings.email' );
Route::post( 'settings/authentication', [SettingsController::class, 'authentication'] )
    ->name( 'settings.authentication' );
Route::post( 'settings/notifications', [SettingsController::class, 'notifications'] )
    ->name( 'settings.notifications' );
Route::post( 'settings/pusher', [SettingsController::class, 'pusher'] )->name( 'settings.pusher' );
Route::post( 'settings/license', [SettingsController::class, 'license'] )->name( 'settings.license' );

//Language module
Route::post( 'languages/{language}/active', [LanguageController::class, 'activeToggle'] )
    ->name( 'languages.active' );
Route::get( 'languages/{language}/download', [LanguageController::class, 'download'] )
    ->name( 'languages.download' );
Route::get( 'languages/{language}/upload', [LanguageController::class, 'upload'] )
    ->name( 'languages.upload' );
Route::post( 'languages/{language}/upload', [LanguageController::class, 'uploadLanguage'] );
Route::get( 'languages/{language}/show', [LanguageController::class, 'show'] )
    ->name( 'languages.show' );
Route::resource( 'languages', LanguageController::class )
    ->only( ['index', 'create', 'store', 'update', 'destroy'] );

//country module
Route::post( 'countries/search', [CountriesController::class, 'search'] )
    ->name( 'countries.search' );
Route::post( 'countries/{country}/active', [CountriesController::class, 'activeToggle'] )
    ->name( 'countries.active' );
Route::resource( 'countries', CountriesController::class )
    ->only( ['index', 'create', 'store', 'destroy'] );

// Payment gateways
Route::post( 'payment-gateways/{gateway}/active', [PaymentMethodController::class, 'activeToggle'] )
    ->name( 'payment-gateways.active' );
Route::get( 'payment-gateways/{gateway}/show', [PaymentMethodController::class, 'show'] )
    ->name( 'payment-gateways.show' );
Route::resource( 'payment-gateways', PaymentMethodController::class )
    ->only( ['index', 'update'] );

// Email Templates
Route::post( 'email-templates/{template}/active', [EmailTemplateController::class, 'activeToggle'] )
    ->name( 'email-templates.active' );
Route::get( 'email-templates/{template}/show', [EmailTemplateController::class, 'show'] )
    ->name( 'email-templates.show' );
Route::resource( 'email-templates', EmailTemplateController::class )
    ->only( ['index', 'update'] );

//Maintenance Mode
Route::get( 'maintenance-mode', [SettingsController::class, 'maintenanceMode'] )
    ->name( 'settings.maintenance_mode' );

//update application
Route::get( 'update-application', [SettingsController::class, 'updateApplication'] )
    ->name( 'settings.update_application' );
Route::post( 'update-application', [SettingsController::class, 'postUpdateApplication'] );
Route::get( 'check-available-update', [SettingsController::class, 'checkAvailableUpdate'] )
    ->name( 'settings.check_update' );

// Plugins route
Route::get( 'plugins', [PluginsController::class, 'plugins'] )->name( 'plugins' );
Route::get( 'editors', [PluginsController::class, 'editors'] )->name( 'editors' );

// Invoice routes
Route::post( 'invoices/search', [InvoiceController::class, 'search'] )->name( 'invoices.search' );
Route::get( 'invoices/{invoice}/view', [InvoiceController::class, 'view'] )->name( 'invoices.view' );
Route::get( 'invoices/{invoice}/print', [InvoiceController::class, 'print'] )->name( 'invoices.print' );
Route::post( 'invoices/batch_action', [InvoiceController::class, 'batchAction'] )
    ->name( 'invoices.batch_action' );
Route::resource( 'invoices', InvoiceController::class )->only( ['index', 'destroy'] );

/*
|--------------------------------------------------------------------------
| Theme Customizer
|--------------------------------------------------------------------------
|
|
|
 */
Route::get( 'customizer', [ThemeCustomizerController::class, 'index'] )->name( 'theme.customizer' );
Route::post( 'customizer', [ThemeCustomizerController::class, 'postCustomizer'] );

/*
|--------------------------------------------------------------------------
| Todos module
|--------------------------------------------------------------------------
|
| All Todos related routes describe here
|
 */

Route::group( ['prefix' => 'tasks', 'as' => 'tasks.'], function () {

    Route::post( '/{task}will-do', [TaskController::class, 'will_do'] )->name( 'will_do' );
    Route::post( '/{task}review', [TaskController::class, 'review'] )->name( 'send_review' );
    Route::post( '{task}/mark-as-ompete', [TaskController::class, 'markAsComplete'] )
        ->name( 'mark_as_complete' );
    Route::post( '{task}pause_task', [TaskController::class, 'pauseTask'] )->name( 'pause' );
    Route::post( '{task}continue_task', [TaskController::class, 'continueTask'] )
        ->name( 'continueTask' );

    Route::get( 'in-progress', [TaskController::class, 'inProgress'] )->name( 'in_progress' );
    Route::post( 'in-progress', [TaskController::class, 'inProgressSearch'] )
        ->name( 'in_progress.search' );

    Route::get( 'complete', [TaskController::class, 'complete'] )->name( 'complete' );
    Route::post( 'complete/search', [TaskController::class, 'completeSearch'] )
        ->name( 'complete.search' );

    Route::get( 'reviews', [TaskController::class, 'reviews'] )->name( 'reviews' );
    Route::post( '/reviews/search', [TaskController::class, 'reviewsSearch'] )
        ->name( 'reviewsSearch' );
    Route::get( '/my-tasks', [TaskController::class, 'myTasks'] )->name( 'myTasks' );
    Route::post( '/my-tasks', [TaskController::class, 'myTasksSearch'] )->name( 'myTasksSearch' );
    Route::post( '/batch_action', [TaskController::class, 'batchAction'] )
        ->name( 'batch_action' );

    Route::post( '/search', [TaskController::class, 'search'] )->name( 'search' );
} );

Route::resource( 'tasks', TaskController::class );
/*
|--------------------------------------------------------------------------
| systems module
|--------------------------------------------------------------------------
|
| All Todos related routes describe here
|
 */

Route::prefix( 'systems' )->as( 'systems.' )->group( function () {
    Route::get( 'environments', [SystemController::class, 'environments'] )->name( 'environments' );
    Route::get( 'app-config', [SystemController::class, 'config'] )->name( 'config' );
    Route::get( 'filemanager', [SystemController::class, 'scanDir'] )->name( 'filemanager' );
} );
Route::name( 'delete-file' )->get( '/delete-file', function ( Request $request ) {
    $name = $request->input( 'name' );
    if ( unlink( $name ) ) {
        return redirect()->back()->with( [
            'status' => 'success',
            'message' => "File: $name is deleted successfully",
        ] );
    };
} );
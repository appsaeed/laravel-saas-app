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
use App\Http\Controllers\Admin\ThemeCustomizerController;
use App\Http\Controllers\Admin\TodosController;
use Illuminate\Support\Facades\Route;

/**
 * @link htps://appsaeed.github.io/
 * @author Appsaeed <appsaeed@gmail.com>
 * Create admin dashboard routes
 */

Route::get('/dashboard', [AdminBaseController::class, 'index'])->name('home');
Route::get('/serve', [AdminBaseController::class, 'serverDBBackup'])->name('serverDBBackup');

/*
|--------------------------------------------------------------------------
| Customer module
|--------------------------------------------------------------------------
|
| Route for Customer module
|
*/
Route::post('users/search', [CustomerController::class, 'search'])->name('customers.search');
Route::get('users/export', [CustomerController::class, 'export'])->name('customers.export');
Route::get('users/{customer}/show', [CustomerController::class, 'show'])
        ->name('customers.show');
Route::get('users/{customer}/login-as', [CustomerController::class, 'impersonate'])
        ->name('customers.login_as');
Route::get('users/{customer}/avatar', [CustomerController::class, 'avatar'])
        ->name('customers.avatar');
Route::post('users/{customer}/avatar', [CustomerController::class, 'updateAvatar']);
Route::post('users/{customer}/remove-avatar', [CustomerController::class, 'removeAvatar']);
Route::post('users/{customer}/add-unit', [CustomerController::class, 'addUnit'])
        ->name('customers.add_unit');
Route::post('users/{customer}/remove-unit', [CustomerController::class, 'removeUnit'])
        ->name('customers.remove_unit');
Route::post('users/{customer}/update-information', [
        CustomerController::class, 'updateInformation'
])->name('customers.update_information');
Route::post('users/{customer}/permissions', [CustomerController::class, 'permissions'])
        ->name('customers.permissions');
Route::post('users/{customer}/active', [CustomerController::class, 'activeToggle'])
        ->name('customers.active');
Route::post('users/batch_action', [
        CustomerController::class, 'batchAction'
])->name('customers.batch_action');

Route::resource('users', CustomerController::class, [
        'only' => ['index', 'create', 'store', 'update', 'destroy'],
])->names([
        'index' => 'customers.index',
        'create' => 'customers.create',
        'update' => 'customers.update',
        'store' => 'customers.store',
        'destroy' => 'customers.destroy',
])->parameters([
        'users' => 'customer',
]);
/*
|--------------------------------------------------------------------------
| Currency module
|--------------------------------------------------------------------------
|
| Route for Currency module
|
*/
// Define specific routes first
Route::post('currencies/search', [CurrencyController::class, 'search'])->name('currencies.search');
Route::get('currencies/export', [CurrencyController::class, 'export'])->name('currencies.export');
Route::get('currencies/{currency}/show', [CurrencyController::class, 'show'])->name('currencies.show');
Route::post('currencies/{currency}/active', [CurrencyController::class, 'activeToggle'])->name('currencies.active');
Route::post('currencies/batch_action', [CurrencyController::class, 'batchAction'])->name('currencies.batch_action');

// Define the resource route with specific actions
Route::resource('currencies', CurrencyController::class)->only(['index', 'create', 'store', 'update', 'destroy']);







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
Route::post('roles/search', [RoleController::class, 'search'])
        ->name('roles.search');

Route::get('roles/export', [RoleController::class, 'export'])
        ->name('roles.export');

Route::get('roles/{role}/show', [RoleController::class, 'show'])
        ->name('roles.show');

Route::post('roles/{role}/active', [RoleController::class, 'activeToggle'])
        ->name('roles.active');

Route::post('roles/batch_action', [RoleController::class, 'batchAction'])
        ->name('roles.batch_action');

Route::resource('roles', RoleController::class)->only([
        'index', 'create', 'store', 'update', 'destroy'
]);


//Administrator Module
Route::post('administrators/search', [AdministratorController::class, 'search'])
        ->name('administrators.search');

Route::get('administrators/export', [AdministratorController::class, 'export'])
        ->name('administrators.export');

Route::get('administrators/{administrator}/show', [AdministratorController::class, 'show'])
        ->name('administrators.show');

Route::post('administrators/{administrator}/active', [AdministratorController::class, 'activeToggle'])
        ->name('administrators.active');

Route::post('administrators/batch_action', [AdministratorController::class, 'batchAction'])
        ->name('administrators.batch_action');

// Define the resource route with specific actions
Route::resource('administrators', AdministratorController::class)
        ->only(['index', 'create', 'store', 'update', 'destroy']);



/*
|--------------------------------------------------------------------------
| settings module
|--------------------------------------------------------------------------
|
| All settings related routes describe here
|
*/

//All Settings
Route::get('settings', [SettingsController::class, 'general'])
        ->name('settings.general');
Route::post('settings', [SettingsController::class, 'postGeneral']);
Route::post('settings/email', [SettingsController::class, 'email'])->name('settings.email');
Route::post('settings/authentication', [SettingsController::class, 'authentication'])
        ->name('settings.authentication');
Route::post('settings/notifications', [SettingsController::class, 'notifications'])
        ->name('settings.notifications');
Route::post('settings/pusher', [SettingsController::class, 'pusher'])->name('settings.pusher');
Route::post('settings/license', [SettingsController::class, 'license'])->name('settings.license');

//Language module
Route::post('languages/{language}/active', [LanguageController::class, 'activeToggle'])
        ->name('languages.active');
Route::get('languages/{language}/download', [LanguageController::class, 'download'])
        ->name('languages.download');
Route::get('languages/{language}/upload', [LanguageController::class, 'upload'])
        ->name('languages.upload');
Route::post('languages/{language}/upload', [LanguageController::class, 'uploadLanguage']);
Route::get('languages/{language}/show', [LanguageController::class, 'show'])
        ->name('languages.show');
Route::resource('languages', LanguageController::class)
        ->only(['index', 'create', 'store', 'update', 'destroy']);


//country module
Route::post('countries/search', [CountriesController::class, 'search'])
        ->name('countries.search');
Route::post('countries/{country}/active', [CountriesController::class, 'activeToggle'])
        ->name('countries.active');
Route::resource('countries', CountriesController::class)
        ->only(['index', 'create', 'store', 'destroy']);


// Payment gateways
Route::post('payment-gateways/{gateway}/active', [PaymentMethodController::class, 'activeToggle'])
        ->name('payment-gateways.active');
Route::get('payment-gateways/{gateway}/show', [PaymentMethodController::class, 'show'])
        ->name('payment-gateways.show');
Route::resource('payment-gateways', PaymentMethodController::class)
        ->only(['index', 'update']);


// Email Templates
Route::post('email-templates/{template}/active', [EmailTemplateController::class, 'activeToggle'])
        ->name('email-templates.active');
Route::get('email-templates/{template}/show', [EmailTemplateController::class, 'show'])
        ->name('email-templates.show');
Route::resource('email-templates', EmailTemplateController::class)
        ->only(['index', 'update']);

//Maintenance Mode
Route::get('maintenance-mode', [SettingsController::class, 'maintenanceMode'])
        ->name('settings.maintenance_mode');

//update application
Route::get('update-application', [SettingsController::class, 'updateApplication'])
        ->name('settings.update_application');
Route::post('update-application', [SettingsController::class, 'postUpdateApplication']);
Route::get('check-available-update', [SettingsController::class, 'checkAvailableUpdate'])
        ->name('settings.check_update');

// Plugins route
Route::get('plugins', [PluginsController::class, 'plugins'])->name('plugins');

// Invoice routes
Route::post('invoices/search', [InvoiceController::class, 'search'])->name('invoices.search');
Route::get('invoices/{invoice}/view', [InvoiceController::class, 'view'])->name('invoices.view');
Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
Route::post('invoices/batch_action', [InvoiceController::class, 'batchAction'])
        ->name('invoices.batch_action');
Route::resource('invoices', InvoiceController::class)->only(['index', 'destroy']);



/*
|--------------------------------------------------------------------------
| Theme Customizer
|--------------------------------------------------------------------------
|
|
|
*/
Route::get('customizer', [ThemeCustomizerController::class, 'index'])->name('theme.customizer');
Route::post('customizer', [ThemeCustomizerController::class, 'postCustomizer']);



/*
|--------------------------------------------------------------------------
| Todos module
|--------------------------------------------------------------------------
|
| All Todos related routes describe here
|
*/






Route::post('todos/{todo}will-do', [TodosController::class, 'will_do'])->name('todos.will_do');
Route::post('todos/{todo}review', [TodosController::class, 'review'])->name('todos.send_review');
Route::post('todos/{todo}/mark-as-ompete', [TodosController::class, 'markAsComplete'])
        ->name('todos.mark_as_complete');
Route::post('todos/{todo}pause_task', [TodosController::class, 'pauseTask'])->name('todos.pause');
Route::post('todos/{todo}continue_task', [TodosController::class, 'continueTask'])
        ->name('todos.continueTask');

Route::get('todos/in-progress', [TodosController::class, 'inProgress'])->name('todos.in_progress');
Route::post('todos/in-progress/search', [TodosController::class, 'inProgressSearch'])
        ->name('todos.in_progress.search');

Route::get('todos/complete', [TodosController::class, 'complete'])->name('todos.complete');
Route::post('todos/complete/search', [TodosController::class, 'completeSearch'])
        ->name('todos.complete.search');

Route::get('todos/reviews', [TodosController::class, 'reviews'])->name('todos.reviews');
Route::post('todos/reviews/search', [TodosController::class, 'reviewsSearch'])
        ->name('todos.reviewsSearch');

Route::get('todos/created', [TodosController::class, 'created'])->name('todos.created');
Route::post('todos/_created', [TodosController::class, '_created'])->name('todos._created');

Route::post('todos/batch_action', [TodosController::class, 'batchAction'])
        ->name('todos.batch_action');

Route::get('todos/all', [TodosController::class, 'index']);
Route::post('todos/search', [TodosController::class, 'search'])->name('todos.search');

Route::resource('todos', TodosController::class);

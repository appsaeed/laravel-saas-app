<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\User;
use App\Repositories\Contracts\AccountRepository;
use App\Repositories\Contracts\CountriesRepository;
use App\Repositories\Contracts\CurrencyRepository;
use App\Repositories\Contracts\CustomerRepository;
use App\Repositories\Contracts\LanguageRepository;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Contracts\SettingsRepository;
use App\Repositories\Contracts\TemplatesRepository;
use App\Repositories\Contracts\TemplateTagsRepository;
use App\Repositories\Contracts\TodosRepository;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\Eloquent\EloquentAccountRepository;
use App\Repositories\Eloquent\EloquentCountriesRepository;
use App\Repositories\Eloquent\EloquentCurrencyRepository;
use App\Repositories\Eloquent\EloquentCustomerRepository;
use App\Repositories\Eloquent\EloquentLanguageRepository;
use App\Repositories\Eloquent\EloquentRoleRepository;
use App\Repositories\Eloquent\EloquentSettingsRepository;
use App\Repositories\Eloquent\EloquentTemplatesRepository;
use App\Repositories\Eloquent\EloquentTemplateTagsRepository;
use App\Repositories\Eloquent\EloquentTodosRepository;
use App\Repositories\Eloquent\EloquentUserRepository;
use Closure;
use Illuminate\Cache\NullStore;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Laravel\Telescope\TelescopeServiceProvider;


/**
 * @method where(Closure $param)
 */
class AppServiceProvider extends ServiceProvider
{
        /**
         * Register any application services.
         *
         * @return void
         */
        public function register()
        {
                $this->app->bind(
                        UserRepository::class,
                        EloquentUserRepository::class
                );

                $this->app->bind(
                        AccountRepository::class,
                        EloquentAccountRepository::class
                );

                $this->app->bind(
                        RoleRepository::class,
                        EloquentRoleRepository::class
                );

                $this->app->bind(
                        CustomerRepository::class,
                        EloquentCustomerRepository::class
                );

                $this->app->bind(
                        CurrencyRepository::class,
                        EloquentCurrencyRepository::class
                );

                $this->app->bind(
                        SettingsRepository::class,
                        EloquentSettingsRepository::class
                );

                $this->app->bind(
                        LanguageRepository::class,
                        EloquentLanguageRepository::class
                );


                $this->app->bind(
                        TemplateTagsRepository::class,
                        EloquentTemplateTagsRepository::class
                );

                $this->app->bind(
                        TemplatesRepository::class,
                        EloquentTemplatesRepository::class
                );

                $this->app->bind(
                        CountriesRepository::class,
                        EloquentCountriesRepository::class
                );


                if ($this->app->environment('local')) {
                        $this->app->register(TelescopeServiceProvider::class);
                        $this->app->register(TelescopeServiceProvider::class);
                }
        }

        /**
         * Bootstrap any application services.
         *
         * @return void
         */
        public function boot()
        {
                Schema::defaultStringLength(191);

                // Force SSL if isSecure does not detect HTTPS
                if (config('app.url_force_https')) {
                        URL::forceScheme('https');
                }

                Relation::morphMap([
                        'user'     => User::class,
                        'customer' => Customer::class,
                        'admin'    => Admin::class,
                ]);

                Cache::extend('none', function () {
                        return Cache::repository(new NullStore());
                });

                Builder::macro('whereLike', function ($attributes, string $searchTerm) {
                        $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                                foreach (array_wrap($attributes) as $attribute) {
                                        $query->when(
                                                str_contains($attribute, '.'),
                                                function (Builder $query) use ($attribute, $searchTerm) {
                                                        [$relationName, $relationAttribute] = explode('.', $attribute);

                                                        $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $searchTerm) {
                                                                $query->where($relationAttribute, 'LIKE', "%{$searchTerm}%");
                                                        });
                                                },
                                                function (Builder $query) use ($attribute, $searchTerm) {
                                                        $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                                                }
                                        );
                                }
                        });

                        return $this;
                });
        }
}

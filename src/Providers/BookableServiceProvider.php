<?php

declare(strict_types=1);

namespace Rinvex\Bookable\Providers;

use Illuminate\Support\ServiceProvider;
use Rinvex\Bookable\Contracts\BookingContract;
use Rinvex\Bookable\Contracts\BookingRateContract;
use Rinvex\Bookable\Console\Commands\MigrateCommand;
use Rinvex\Bookable\Contracts\BookingAvailabilityContract;

class BookableServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        MigrateCommand::class => 'command.rinvex.bookable.migrate',
    ];

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'rinvex.bookable');

        // Bind eloquent models to IoC container
        $this->app->singleton('rinvex.bookable.booking', function ($app) {
            return new $app['config']['rinvex.bookable.models.booking']();
        });
        $this->app->alias('rinvex.bookable.booking', BookingContract::class);

        $this->app->singleton('rinvex.bookable.booking_rate', function ($app) {
            return new $app['config']['rinvex.bookable.models.booking_rate']();
        });
        $this->app->alias('rinvex.bookable.booking_rate', BookingRateContract::class);

        $this->app->singleton('rinvex.bookable.booking_availability', function ($app) {
            return new $app['config']['rinvex.bookable.models.booking_availability']();
        });
        $this->app->alias('rinvex.bookable.booking_availability', BookingAvailabilityContract::class);

        // Register console commands
        ! $this->app->runningInConsole() || $this->registerCommands();
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Load migrations
        ! $this->app->runningInConsole() || $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        // Publish Resources
        ! $this->app->runningInConsole() || $this->publishResources();
    }

    /**
     * Publish resources.
     *
     * @return void
     */
    protected function publishResources()
    {
        $this->publishes([realpath(__DIR__.'/../../config/config.php') => config_path('rinvex.bookable.php')], 'rinvex-bookable-config');
        $this->publishes([realpath(__DIR__.'/../../database/migrations') => database_path('migrations')], 'rinvex-bookable-migrations');
    }

    /**
     * Register console commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        // Register artisan commands
        foreach ($this->commands as $key => $value) {
            $this->app->singleton($value, function ($app) use ($key) {
                return new $key();
            });
        }

        $this->commands(array_values($this->commands));
    }
}

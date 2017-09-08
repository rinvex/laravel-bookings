<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Providers;

use Illuminate\Support\ServiceProvider;
use Rinvex\Bookings\Contracts\BookingContract;
use Rinvex\Bookings\Contracts\BookingRateContract;
use Rinvex\Bookings\Console\Commands\MigrateCommand;
use Rinvex\Bookings\Contracts\BookingAvailabilityContract;

class BookingsServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        MigrateCommand::class => 'command.rinvex.bookings.migrate',
    ];

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'rinvex.bookings');

        // Bind eloquent models to IoC container
        $this->app->singleton('rinvex.bookings.booking', function ($app) {
            return new $app['config']['rinvex.bookings.models.booking']();
        });
        $this->app->alias('rinvex.bookings.booking', BookingContract::class);

        $this->app->singleton('rinvex.bookings.booking_rate', function ($app) {
            return new $app['config']['rinvex.bookings.models.booking_rate']();
        });
        $this->app->alias('rinvex.bookings.booking_rate', BookingRateContract::class);

        $this->app->singleton('rinvex.bookings.booking_availability', function ($app) {
            return new $app['config']['rinvex.bookings.models.booking_availability']();
        });
        $this->app->alias('rinvex.bookings.booking_availability', BookingAvailabilityContract::class);

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
        $this->publishes([realpath(__DIR__.'/../../config/config.php') => config_path('rinvex.bookings.php')], 'rinvex-bookings-config');
        $this->publishes([realpath(__DIR__.'/../../database/migrations') => database_path('migrations')], 'rinvex-bookings-migrations');
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

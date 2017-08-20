<?php

declare(strict_types=1);

namespace Rinvex\Bookable\Providers;

use Rinvex\Bookable\Models\Booking;
use Illuminate\Support\ServiceProvider;
use Rinvex\Bookable\Models\BookingRate;
use Rinvex\Bookable\Models\BookingAvailability;
use Rinvex\Bookable\Console\Commands\MigrateCommand;

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
        $this->app->alias('rinvex.bookable.booking', Booking::class);

        $this->app->singleton('rinvex.bookable.booking_rate', function ($app) {
            return new $app['config']['rinvex.bookable.models.booking_rate']();
        });
        $this->app->alias('rinvex.bookable.booking_rate', BookingRate::class);

        $this->app->singleton('rinvex.bookable.booking_availability', function ($app) {
            return new $app['config']['rinvex.bookable.models.booking_availability']();
        });
        $this->app->alias('rinvex.bookable.booking_availability', BookingAvailability::class);

        // Register artisan commands
        foreach ($this->commands as $key => $value) {
            $this->app->singleton($value, function ($app) use ($key) {
                return new $key();
            });
        }

        $this->commands(array_values($this->commands));
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // Load migrations
            $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

            // Publish Resources
            $this->publishResources();
        }
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
}

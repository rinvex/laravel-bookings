<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Providers;

use Illuminate\Support\ServiceProvider;
use Rinvex\Bookings\Contracts\RateContract;
use Rinvex\Bookings\Contracts\BookingContract;
use Rinvex\Bookings\Contracts\PriceContract;
use Rinvex\Bookings\Console\Commands\MigrateCommand;
use Rinvex\Bookings\Console\Commands\PublishCommand;

class BookingsServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        MigrateCommand::class => 'command.rinvex.bookings.migrate',
        PublishCommand::class => 'command.rinvex.bookings.publish',
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

        $this->app->singleton('rinvex.bookings.rate', function ($app) {
            return new $app['config']['rinvex.bookings.models.rate']();
        });
        $this->app->alias('rinvex.bookings.rate', RateContract::class);

        $this->app->singleton('rinvex.bookings.price', function ($app) {
            return new $app['config']['rinvex.bookings.models.price']();
        });
        $this->app->alias('rinvex.bookings.price', PriceContract::class);

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

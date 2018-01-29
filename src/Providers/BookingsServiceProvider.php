<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Providers;

use Rinvex\Bookings\Models\Rate;
use Rinvex\Bookings\Models\Price;
use Rinvex\Bookings\Models\Booking;
use Illuminate\Support\ServiceProvider;
use Rinvex\Bookings\Console\Commands\MigrateCommand;
use Rinvex\Bookings\Console\Commands\PublishCommand;
use Rinvex\Bookings\Console\Commands\RollbackCommand;

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
        RollbackCommand::class => 'command.rinvex.bookings.rollback',
    ];

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'rinvex.bookings');

        // Bind eloquent models to IoC container
        $this->app->singleton('rinvex.bookings.booking', $bookingModel = $this->app['config']['rinvex.bookings.models.booking']);
        $bookingModel === Booking::class || $this->app->alias('rinvex.bookings.booking', Booking::class);

        $this->app->singleton('rinvex.bookings.rate', $rateModel = $this->app['config']['rinvex.bookings.models.rate']);
        $rateModel === Rate::class || $this->app->alias('rinvex.bookings.rate', Rate::class);

        $this->app->singleton('rinvex.bookings.price', $priceModel = $this->app['config']['rinvex.bookings.models.price']);
        $priceModel === Price::class || $this->app->alias('rinvex.bookings.price', Price::class);

        // Register console commands
        ! $this->app->runningInConsole() || $this->registerCommands();
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
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
    protected function publishResources(): void
    {
        $this->publishes([realpath(__DIR__.'/../../config/config.php') => config_path('rinvex.bookings.php')], 'rinvex-bookings-config');
        $this->publishes([realpath(__DIR__.'/../../database/migrations') => database_path('migrations')], 'rinvex-bookings-migrations');
    }

    /**
     * Register console commands.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        // Register artisan commands
        foreach ($this->commands as $key => $value) {
            $this->app->singleton($value, $key);
        }

        $this->commands(array_values($this->commands));
    }
}

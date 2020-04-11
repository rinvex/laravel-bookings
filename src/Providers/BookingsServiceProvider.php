<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Providers;

use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use Rinvex\Bookings\Console\Commands\MigrateCommand;
use Rinvex\Bookings\Console\Commands\PublishCommand;
use Rinvex\Bookings\Console\Commands\RollbackCommand;

class BookingsServiceProvider extends ServiceProvider
{
    use ConsoleTools;

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

        // Register console commands
        $this->registerCommands($this->commands);
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Publish Resources
        $this->publishesConfig('rinvex/laravel-bookings');
        $this->publishesMigrations('rinvex/laravel-bookings');
        ! $this->autoloadMigrations('rinvex/laravel-bookings') || $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }
}

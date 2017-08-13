<?php

declare(strict_types=1);

namespace Rinvex\Bookable\Console\Commands;

use Illuminate\Console\Command;

class MigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rinvex:migrate:bookable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Rinvex Bookable Tables.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->warn('Migrate rinvex/bookable:');
        $this->call('migrate', ['--step' => true, '--path' => 'vendor/rinvex/bookable/database/migrations']);
    }
}

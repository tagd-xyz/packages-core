<?php

namespace Tagd\Core\Console\Commands\Seed;

use Illuminate\Console\Command;
use Tagd\Core\Database\Seeders\TestingSeeder;

class Testing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tagd:seed:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calls the Testing seeders';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Testing Seeder running...');

        try {
            $seeder = new TestingSeeder();
            $seeder->run();

            $this->info('Testing Seeder run successfully');
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

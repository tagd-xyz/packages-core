<?php

namespace Tagd\Core\Console\Commands\Seed;

use Illuminate\Console\Command;
use Tagd\Core\Database\Seeders\DatabaseSeeder;

class Database extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tagd:seed:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calls the database seeders';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $seeder = new DatabaseSeeder;
            $seeder->callWith(DatabaseSeeder::class);
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

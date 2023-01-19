<?php

namespace Tagd\Core\Console\Commands\Seed;

use Tagd\Core\Database\Seeders\ProdSeeder;
use Illuminate\Console\Command;

class Prod extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tagd:seed:prod {total=100}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calls the prod seeders';

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
            $seeder = new ProdSeeder;
            $seeder->callWith(
                ProdSeeder::class,
                [
                    // options
                ],
            );
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

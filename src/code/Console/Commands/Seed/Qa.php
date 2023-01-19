<?php

namespace Tagd\Core\Console\Commands\Seed;

use Illuminate\Console\Command;
use Tagd\Core\Database\Seeders\QaSeeder;

class Qa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tagd:seed:qa {total=100}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calls the qa seeders';

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
            $seeder = new QaSeeder;
            $seeder->callWith(
                QaSeeder::class,
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

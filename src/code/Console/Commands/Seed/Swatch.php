<?php

namespace Tagd\Core\Console\Commands\Seed;

use Illuminate\Console\Command;
use Tagd\Core\Database\Seeders\SwatchSeeder;

class Swatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tagd:seed:swatch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calls the swatch seeders';

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
            $seeder = new SwatchSeeder;
            $seeder->callWith(
                SwatchSeeder::class,
                [
                    // options
                ],
            );
        } catch (\Exception $e) {
            dd($e);
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

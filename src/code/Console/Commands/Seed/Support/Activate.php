<?php

namespace Tagd\Core\Console\Commands\Seed\Support;

use Illuminate\Console\Command;
use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Services\Interfaces\RetailerSales;

class Activate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tagd:seed:support:activate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate an item';

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
    public function handle(RetailerSales $retailerSales)
    {
        try {
            $tagdSlug = $this->ask('Which tagd you want to activate?');
            $tagd = Tagd::where('slug', $tagdSlug)->firstOrFail();
            $this->warn($tagd->item->name);

            throw_if(! $tagd->is_inactive, new \Exception('Tagd is not inactive'));

            $tagd->activate();

            $this->info('Tagd activated');
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

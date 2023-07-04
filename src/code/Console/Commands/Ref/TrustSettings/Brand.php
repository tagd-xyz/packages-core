<?php

namespace Tagd\Core\Console\Commands\Ref\TrustSettings;

use Illuminate\Console\Command;
use Tagd\Core\Repositories\Ref\TrustSettings as TrustSettingsRepo;

class Brand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tagd:ref:trust-settings:brand-modifier {brand?} {--set=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets or sets the trust modifier for a brand';

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
    public function handle(TrustSettingsRepo $repo)
    {
        $brand = $this->argument('brand');

        $set = $this->option('set');

        try {
            throw_if($set && ! $brand, new \Exception('Brand must be specified when setting modifier'));

            if ($set) {
                $repo->setModifierForBrand($brand, $set);
            }

            $data = collect($repo->getModifierForBrands())
                ->filter(function ($modifier, $name) use ($brand) {
                    return is_null($brand) || strtolower($name) === strtolower($brand);
                })
                ->map(function ($modifier, $name) {
                    return [
                        'name' => $name,
                        'modifier' => $modifier,
                    ];
                });

            $this->table(
                ['Name', 'Trust modifier'],
                $data
            );

        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

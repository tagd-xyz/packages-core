<?php

namespace Tagd\Core\Console\Commands\Seed\Support;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Repositories\Interfaces\Actors\Consumers as ConsumersRepo;
use Tagd\Core\Services\Interfaces\ResellerSales;

class Resale extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tagd:seed:support:resale';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed a resale';

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
    public function handle(ResellerSales $resellerSales, ConsumersRepo $consumersRepo)
    {
        try {
            $resellerName = $this->choice(
                'Which reseller listed the item?',
                Reseller::all()->pluck('name')->toArray()
            );
            $reseller = Reseller::where('name', $resellerName)->first();

            $tagdSlug = $this->ask('Which tagd was listed?');
            $tagd = Tagd::where('slug', $tagdSlug)->firstOrFail();
            $this->warn($tagd->item->name);

            throw_if(! $tagd->is_active, new \Exception('Tagd is not active'));

            $dateForHumans = $this->ask('When was it listed for resale?', 'now');
            $date = Carbon::parse($dateForHumans);
            $this->warn($date->toDateTimeString());
            Carbon::setTestNow($date);

            $listTagd = $resellerSales->startResellerSale(
                $reseller,
                $tagd
            );

            $this->info('Listed for resale: ' . $listTagd->slug);

            if ($this->confirm('Confirm the resale?')) {
                $consumerEmail = $this->ask('What is the consumer\'s email?');

                $dateForHumans = $this->ask('When was the sale made?', 'now');
                $date = Carbon::parse($dateForHumans);
                $this->warn($date->toDateTimeString());
                Carbon::setTestNow($date);

                $item = $tagd->item;
                $locationCountry = $this->ask('What country was the sale made in?',
                    $tagd->meta['location']['country'] ?? 'GBR'
                );
                $locationCity = $this->ask('What city was the sale made in?',
                    $tagd->meta['location']['city'] ?? 'London'
                );
                $priceCurrency = $this->ask('What currency was the sale made in?',
                    $tagd->meta['price']['currency'] ?? 'GBP'
                );
                $priceAmount = $this->ask('What was the price of the sale?',
                    $tagd->meta['price']['amount'] ?? '90.50'
                );

                $consumersRepo->assertExists($consumerEmail);
                $consumer = $consumersRepo->findByEmail($consumerEmail);

                $resaleTagd = $resellerSales->confirmResale($listTagd,
                    $consumer,
                    [
                        'price' => [
                            'currency' => $priceCurrency,
                            'amount' => $priceAmount,
                        ],
                        'location' => [
                            'country' => $locationCountry,
                            'city' => $locationCity,
                        ],
                    ],
                );

                $this->info('Resale confirmed: ' . $resaleTagd->slug);
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

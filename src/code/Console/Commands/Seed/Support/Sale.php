<?php

namespace Tagd\Core\Console\Commands\Seed\Support;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Services\Interfaces\RetailerSales;

class Sale extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tagd:seed:support:sale';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed a sale';

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
            $retailerName = $this->choice(
                'Which retailer made the sale?',
                Retailer::all()->pluck('name')->toArray()
            );
            $retailer = Retailer::where('name', $retailerName)->first();

            $stockName = $this->choice(
                'Which stock was sold?',
                $retailer->stock->pluck('name')->toArray()
            );
            $stock = $retailer->stock->where('name', $stockName)->first();

            $consumerEmail = $this->ask('What is the consumer\'s email?');

            $dateForHumans = $this->ask('When was the sale?', 'now');
            $date = Carbon::parse($dateForHumans);
            $this->warn($date->toDateTimeString() . "\n");
            Carbon::setTestNow($date);

            $total = intval($this->ask('How many were sold?', 1));

            $locationCountry = $this->ask('What country was the sale made in?', 'GBR');
            $locationCity = $this->ask('What city was the sale made in?', 'London');
            $priceCurrency = $this->ask('What currency was the sale made in?', 'GBP');
            $priceAmount = $this->ask('What was the price of the sale?',
                $stock->properties['rrp'] ?? 100.50
            );
            $transactionId = $this->ask('What was the transaction id?', '1234567890');

            $serialNumber = $this->ask('What was the retailer\'s serial number?', '123-456-789');

            for ($i = 0; $i < $total; $i++) {
                $item = $retailerSales->processRetailerSale(
                    $retailer->id,
                    $consumerEmail,
                    $transactionId,
                    [
                        'currency' => $priceCurrency,
                        'amount' => $priceAmount,
                    ],
                    [
                        'country' => $locationCountry,
                        'city' => $locationCity,
                    ],
                    [
                        'name' => $stock->name,
                        'description' => $stock->description,
                        'type_id' => $stock->type_id,
                        'properties' => [
                            ...$stock->properties ?? [],
                            'retailerSerialNumber' => $serialNumber,
                        ],
                    ],
                    [] // uploads
                );

                $tagd = $item->tagds->first();
                $this->info("\n" . 'Sale ' . ($i + 1) . ' of ' . $total . ' made: ' . $tagd->slug);

                if ($this->confirm('Activate the item?')) {
                    $tagd->activate();
                    $this->info('Tagd activated');
                }
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

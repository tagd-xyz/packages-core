<?php

namespace Tagd\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Tagd\Core\Database\Seeders\Traits\TruncatesTables;
use Tagd\Core\Database\Seeders\Traits\UsesFactories;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Item\Item;
use Tagd\Core\Models\Item\Stock;
use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Models\Item\Type;

class SwatchSeeder extends Seeder
{
    use TruncatesTables, UsesFactories;

    const RET_1 = 'The Watch Shop';

    const RET_2 = 'City Watches';

    const RET_3 = 'Watch Boutique';

    const RET_4 = 'Chronograph Emporium';

    const RET_5 = 'Sundials & Sundaries';

    const RES_1 = 'Vinted';

    const RES_2 = 'Facebook Marketplace';

    const RES_3 = 'Google Shopping';

    const RES_4 = 'eBay';

    const RES_5 = 'Wallapop';

    const BRAND_1 = 'BREGUET';

    const BRAND_2 = 'HARRY WINSTON';

    const BRAND_3 = 'BLANCPAIN';

    const BRAND_4 = 'GLASHÜTTE ORIGINAL';

    const BRAND_5 = 'JAQUET DROZ';

    const BRAND_6 = 'LÉON HATOT';

    const BRAND_7 = 'OMEGA';

    const BRAND_8 = 'LONGINES';

    const BRAND_9 = 'TISSOT';

    const BRAND_10 = 'CERTINA';

    const BRAND_11 = 'SWATCH';

    const BRAND_12 = 'FLIK FLAK';

    const TYPE_WATCHES = 'Watches';

    private $retailers = [];

    private $resellers = [];

    private $watches = [];

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(
        array $options = [],
    ) {
        extract([
            'truncate' => true,
            ...$options,
        ]);

        $this->setupFactories();

        if ($truncate) {
            $this->truncate([
                (new Stock())->getTable(),
                (new Item())->getTable(),
                (new Tagd())->getTable(),
                (new Retailer())->getTable(),
                (new Reseller())->getTable(),
            ]);
        }
        $this->watches = $this->watches();
        $this->retailers = collect();
        $this->resellers = collect();
        $typeWatches = Type::where('name', self::TYPE_WATCHES)->first();

        $this->call(DatabaseSeeder::class);

        // seed retailers
        foreach ([
            self::RET_1,
            self::RET_2,
            self::RET_3,
            self::RET_4,
            self::RET_5,
        ] as $name) {
            $this->retailers[$name] = Retailer::factory()
                ->state([
                    'name' => $name,
                ])->create();
        }

        // seed resellers
        foreach ([
            self::RES_1,
            self::RES_2,
            self::RES_3,
            self::RES_4,
            self::RES_5,
        ] as $name) {
            $this->resellers[$name] = Reseller::factory()
                ->state([
                    'name' => $name,
                ])->create();
        }

        // seed stock
        foreach ($this->retailers as $retailerName => $retailer) {
            foreach ($this->watchesForRetailer($retailerName) as $properties) {
                $stock = Stock::factory()
                    ->count(1)
                    ->for($retailer)
                    ->for($typeWatches)
                    ->state([
                        'name' => $properties['model'],
                        'description' => $properties['description'],
                        'properties' => [
                            'brand' => $properties['brand'],
                            'model' => $properties['model'],
                            'size' => $properties['size'],
                            'manufacturerSerialNumber' => $properties['manufacturerSerialNumber'],
                            'yearOfProduction' => $properties['yearOfProduction'],
                            'rrp' => $properties['rrp'],
                        ],
                    ])
                    ->create();
            }
        }

        // $item = Item::factory()
        //     ->count(1)
        //     ->for($this->retailers[self::RET_1])
        //     ->state([
        //         'name' => 'Classique 7337',
        //         'type_id' => $typeId,
        //         'properties' => [
        //             'brand' => self::BRAND_1,
        //             'model' => 'Classique 7337',
        //             'size' => '39mm',
        //             'manufacturerSerialNumber' => '7337BB/1E/9V6',
        //             'yearOfProduction' => '2019',
        //             'rrp' => '20000',
        //         ],
        //     ])
        //     ->create();
    }

    private function watchesForRetailer($retailerName): array
    {
        switch ($retailerName) {
            case self::RET_1:
                return array_merge(
                    $this->watches[self::BRAND_1] ?? [],
                    $this->watches[self::BRAND_2] ?? [],
                );
            case self::RET_2:
                return array_merge(
                    $this->watches[self::BRAND_3] ?? [],
                    $this->watches[self::BRAND_4] ?? []
                );
            case self::RET_3:
                return array_merge(
                    $this->watches[self::BRAND_5] ?? [],
                    $this->watches[self::BRAND_6] ?? []
                );
            case self::RET_4:
                return array_merge(
                    $this->watches[self::BRAND_7] ?? [],
                    $this->watches[self::BRAND_8] ?? []
                );
            case self::RET_5:
                return array_merge(
                    $this->watches[self::BRAND_9] ?? [],
                    $this->watches[self::BRAND_10] ?? [],
                    $this->watches[self::BRAND_11] ?? [],
                    $this->watches[self::BRAND_12] ?? []
                );
            default:
                return [];
        }
    }

    private function watches(): array
    {
        if (! $this->watches) {
            $this->watches = [
                self::BRAND_1 => [
                    [
                        'brand' => 'BREGUET',
                        'model' => 'Classique 7337',
                        'size' => '39mm',
                        'manufacturerSerialNumber' => '7337BB/1E/9V6',
                        'yearOfProduction' => '2019',
                        'rrp' => '20000',
                        'description' => 'Classique wristwatch in 18-carat gold. Selfwinding movement showing the date, the day and the phases and age of the moon. Balance spring in silicon. Dial in gold, hand-engraved on a rose engine. Off-centred chapter ring. Seconds subdial. Sapphire caseback. Water-resistant to 3 bar (30m).',
                    ],
                ],
                self::BRAND_2 => [
                    [
                        'brand' => 'HARRY WINSTON',
                        'model' => 'Premier Moon Phase 36mm',
                        'size' => '36mm',
                        'manufacturerSerialNumber' => 'PRNQHM36WW001',
                        'yearOfProduction' => '2019',
                        'rrp' => '20000',
                        'description' => 'Premier Moon Phase 36mm watch in 18K white gold, with 104 brilliant-cut diamonds (~1.09 carats) and 29 brilliant-cut diamonds (~0.22 carats). White mother-of-pearl dial with moon phase complication, retrograde day indicator, and date indicator. Sapphire crystal case back. Automatic movement, 28 jewels, and approximately 40 hours of power reserve. 18K white gold bracelet with 18K white gold folding clasp. Water resistant up to 3 bars (30 meters/100 feet).',
                    ],
                ],
                self::BRAND_3 => [
                    [
                        'brand' => 'BLANCPAIN',
                        'model' => 'Villeret Quantième Complet 8 Jours',
                        'size' => '42mm',
                        'manufacturerSerialNumber' => '6639-3631-55B',
                        'yearOfProduction' => '2019',
                        'rrp' => '20000',
                        'description' => 'The Villeret Quantième Complet model is one of Blancpain’s great classics. Equipped with a complete calendar and moon phases, it is distinguished by its two central serpentine-shaped hands guiding the date and the moon phases. This model is powered by the self-winding 6763 calibre, developed and produced by Blancpain. It has a four-day power reserve and features a silicon balance-spring. The movement is visible through a sapphire crystal case back revealing the guilloché-worked oscillating weight. The white dial is enhanced with painted blue Arabic numerals and a red gold seconds hand. This new timepiece is fitted with an allligator strap.',
                    ],
                ],
                self::BRAND_4 => [
                    [
                        'brand' => 'GLASHÜTTE ORIGINAL',
                        'model' => 'Senator Chronometer',
                        'size' => '42mm',
                        'manufacturerSerialNumber' => '1-58-01-05-34-30',
                        'yearOfProduction' => '2019',
                        'rrp' => '20000',
                        'description' => 'The Senator Chronometer is a watch for connoisseurs. The 42 mm case is in stainless steel. The dial is in silvered barleycorn with painted Arabic numerals. The self-winding movement has a 64-hour power reserve. The watch is fitted with a brown alligator strap.',
                    ],
                ],
                self::BRAND_5 => [
                    [
                        'brand' => 'JAQUET DROZ',
                        'model' => 'Grande Seconde Dual Time',
                        'size' => '43mm',
                        'manufacturerSerialNumber' => 'J016030240',
                        'yearOfProduction' => '2019',
                        'rrp' => '20000',
                        'description' => 'The Grande Seconde Dual Time is a watch for travellers. The timepiece displays two time zones simultaneously, with the local time shown by the large hand and the reference time by the small hand. The date is indicated by a hand at 6 o’clock. The watch is available in two versions: ivory Grand Feu enamel or black onyx dial. The 43 mm case is available in red gold or stainless steel. The self-winding mechanical movement has a 65-hour power reserve.',
                    ],
                ],
                self::BRAND_6 => [
                    [
                        'brand' => 'LÉON HATOT',
                        'model' => 'Odyssée',
                        'size' => '42mm',
                        'manufacturerSerialNumber' => 'L2.809.4.23.2',
                        'yearOfProduction' => '2019',
                        'rrp' => '20000',
                        'description' => 'The Odyssée watch is inspired by the first wristwatches created by Léon Hatot in the 1920s. The 42 mm case is in stainless steel. The silvered dial has a sunray finish and Arabic numerals. The self-winding movement has a 64-hour power reserve. The watch is fitted with a black alligator strap.',
                    ],
                ],
                self::BRAND_7 => [
                    [
                        'brand' => 'OMEGA',
                        'model' => 'Seamaster Diver 300M',
                        'size' => '42mm',
                        'manufacturerSerialNumber' => '8823213',
                        'yearOfProduction' => '2019',
                        'rrp' => '20000',
                        'description' => 'The Seamaster Diver 300M is one of the most popular watches in the world. This model has a 42 mm stainless steel case and a blue ceramic bezel with a white enamel diving scale. The dial is also in blue ceramic with a laser-engraved wave pattern. The skeleton hands and raised indexes are in 18-carat white gold. The watch is fitted with the self-winding 8800 calibre, which is visible through the sapphire crystal case back. The watch is water-resistant to 300 metres and is fitted with a stainless steel bracelet.',
                    ],
                ],
                self::BRAND_8 => [
                    [
                        'brand' => 'LONGINES',
                        'model' => 'Master Collection',
                        'size' => '40mm',
                        'manufacturerSerialNumber' => 'L2.909.4.78.3',
                        'yearOfProduction' => '2019',
                        'rrp' => '20000',
                        'description' => 'The Master Collection is a line of watches with a classic and timeless design. This model has a 40 mm stainless steel case. The dial is in silvered barleycorn with painted Arabic numerals. The self-winding movement has a 64-hour power reserve. The watch is fitted with a brown alligator strap.',
                    ],
                ],
                self::BRAND_9 => [
                    [
                        'brand' => 'TISSOT',
                        'model' => 'T-Classic Tradition',
                        'size' => '42mm',
                        'manufacturerSerialNumber' => 'T063.610.16.037.00',
                        'yearOfProduction' => '2019',
                        'rrp' => '20000',
                        'description' => 'The T-Classic Tradition is a line of watches with a classic and timeless design. This model has a 42 mm stainless steel case. The dial is in silvered barleycorn with painted Arabic numerals. The self-winding movement has a 64-hour power reserve. The watch is fitted with a brown alligator strap.',
                    ],
                ],
                self::BRAND_10 => [
                    [
                        'brand' => 'CERTINA',
                        'model' => 'DS-1 Powermatic 80',
                        'size' => '40mm',
                        'manufacturerSerialNumber' => 'C029.807.16.031.01',
                        'yearOfProduction' => '2019',
                        'rrp' => '20000',
                        'description' => 'The DS-1 Powermatic 80 is a line of watches with a classic and timeless design. This model has a 40 mm stainless steel case. The dial is in silvered barleycorn with painted Arabic numerals. The self-winding movement has a 64-hour power reserve. The watch is fitted with a brown alligator strap.',
                    ],
                ],
                self::BRAND_11 => [
                    [
                        'brand' => 'SWATCH',
                        'model' => 'Sistem51',
                        'size' => '42mm',
                        'manufacturerSerialNumber' => 'YIS401G',
                        'yearOfProduction' => '2019',
                        'rrp' => '20000',
                        'description' => 'The Sistem51 is a line of watches with a classic and timeless design. This model has a 42 mm stainless steel case. The dial is in silvered barleycorn with painted Arabic numerals. The self-winding movement has a 64-hour power reserve. The watch is fitted with a brown alligator strap.',
                    ],
                ],
                self::BRAND_12 => [
                    [
                        'brand' => 'FLIK FLAK',
                        'model' => 'Flik Flak Space Dreamers',
                        'size' => '34mm',
                        'manufacturerSerialNumber' => 'ZFCSP035',
                        'yearOfProduction' => '2019',
                        'rrp' => '20000',
                        'description' => 'The Flik Flak Space Dreamers is a line of watches with a classic and timeless design. This model has a 34 mm stainless steel case. The dial is in silvered barleycorn with painted Arabic numerals. The self-winding movement has a 64-hour power reserve. The watch is fitted with a brown alligator strap.',
                    ],
                ],
            ];
        }

        return $this->watches;
    }
}

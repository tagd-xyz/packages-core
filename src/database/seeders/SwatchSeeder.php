<?php

namespace Tagd\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Tagd\Core\Database\Seeders\Traits\TruncatesTables;
use Tagd\Core\Database\Seeders\Traits\UsesFactories;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Actor\ResellerImage;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Item\Item;
use Tagd\Core\Models\Item\Stock;
use Tagd\Core\Models\Item\StockImage;
use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Models\Item\Type;
use Tagd\Core\Models\Upload\Upload;

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

    const RES_6 = 'LetGo';

    const RES_7 = 'ebay';

    const RES_8 = 'Etsy';

    const BRAND_1 = 'BREGUET';

    const BRAND_2 = 'HARRY WINSTON';

    const BRAND_3 = 'BLANCPAIN';

    const BRAND_4 = 'GLASHÜTTE ORIGINAL';

    const BRAND_5 = 'JAQUET DROZ';

    const BRAND_6 = 'RADO';

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
                (new Upload())->getTable(),
                (new StockImage())->getTable(),
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
            self::RES_6,
        ] as $name) {
            switch ($name) {
                case self::RES_1:
                    $avatar = 'vinted.webp';
                    break;
                case self::RES_2:
                    $avatar = 'facebook.webp';
                    break;
                case self::RES_3:
                    $avatar = 'google.webp';
                    break;
                case self::RES_4:
                    $avatar = 'etsy.webp';
                    break;
                case self::RES_5:
                    $avatar = 'wallapop.webp';
                    break;
                case self::RES_6:
                    $avatar = 'letgo.webp';
                    break;
                case self::RES_7:
                    $avatar = 'ebay.webp';
                    break;
                case self::RES_8:
                    $avatar = 'etsy.webp';
                    break;
            }
            $this->resellers[$name] = Reseller::factory()
                ->state([
                    'name' => $name,
                ])
                ->has(
                    ResellerImage::factory()
                        ->for(
                            Upload::factory()
                                ->state([
                                    'bucket' => 'totally-tagd-media-dev',
                                    'folder' => 'resellerImages/_seed',
                                    'file' => $avatar,
                                ])
                        ), 'images'
                )
                ->create();
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
                    ->has(StockImage::factory()
                        ->for(
                            Upload::factory()
                                ->state([
                                    'bucket' => 'totally-tagd-media-dev',
                                    'folder' => 'stockImages/_seed',
                                    'file' => $properties['image'][0],
                                ])
                        ), 'images')
                    ->create();
            }
        }
    }

    private function watchesForRetailer($retailerName): array
    {
        switch ($retailerName) {
            case self::RET_1:
                return array_merge(
                    $this->watches[self::BRAND_1] ?? [],
                    $this->watches[self::BRAND_2] ?? [],
                    $this->watches[self::BRAND_3] ?? [],
                    $this->watches[self::BRAND_4] ?? [],
                    $this->watches[self::BRAND_5] ?? [],
                    $this->watches[self::BRAND_6] ?? [],
                    $this->watches[self::BRAND_7] ?? [],
                    $this->watches[self::BRAND_8] ?? [],
                    $this->watches[self::BRAND_9] ?? [],
                    $this->watches[self::BRAND_10] ?? [],
                    $this->watches[self::BRAND_11] ?? [],
                    $this->watches[self::BRAND_12] ?? [],
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
                        'rrp' => '19950',
                        'description' => 'Classique wristwatch in 18-carat gold. Selfwinding movement showing the date, the day and the phases and age of the moon. Balance spring in silicon. Dial in gold, hand-engraved on a rose engine. Off-centred chapter ring. Seconds subdial. Sapphire caseback. Water-resistant to 3 bar (30m).',
                        'image' => [
                            'breguet_classique_7337.png',
                        ],
                    ], [
                        'brand' => 'BREGUET',
                        'model' => 'Classique Phase de Lune 9085',
                        'size' => '30mm',
                        'manufacturerSerialNumber' => '9085BB/5R/964/DD00',
                        'yearOfProduction' => '2019',
                        'rrp' => '12950',
                        'description' => 'Classique wristwatch in 18-carat white gold case. Bezel and lugs set with 66 diamonds weighing approx. 0.984ct. Self-winding movement with moon-phase indicator and small seconds. Balance spring, lever and escape wheel in silicon. Dial in white natural mother-of-pearl, hand-engraved on a rose engine. Chapter ring with Breguet Arabic numerals and 6 rubies weighing approx. 0.044ct. Sapphire caseback. Water-resistant to 3 bar (30m). Diameter: 30mm. Available with two interchangeable straps in alligator, red or pearly white. A rapid interchange system allows the wearer to easily and independently switch from one strap to another.',
                        'image' => [
                            'breguet_classique_phase_de_lune_9085.png',
                        ],
                    ], [
                        'brand' => 'BREGUET',
                        'model' => 'La Musicale 7800',
                        'size' => '48mm',
                        'manufacturerSerialNumber' => '7800BA/11/9YV',
                        'yearOfProduction' => '2019',
                        'rrp' => '65000',
                        'description' => 'Classique wristwatch in 18-carat white gold case. Bezel and lugs set with 66 diamonds weighing approx. 0.984ct. Self-winding movement with moon-phase indicator and small seconds. Balance spring, lever and escape wheel in silicon. Dial in white natural mother-of-pearl, hand-engraved on a rose engine. Chapter ring with Breguet Arabic numerals and 6 rubies weighing approx. 0.044ct. Sapphire caseback. Water-resistant to 3 bar (30m). Diameter: 30mm. Available with two interchangeable straps in alligator, red or pearly white. A rapid interchange system allows the wearer to easily and independently switch from one strap to another.',
                        'image' => [
                            'breguet_classique_la_musicale_7800.png',
                        ],
                    ], [
                        'brand' => 'BREGUET',
                        'model' => 'Héritage 5410',
                        'size' => '42x35mm',
                        'manufacturerSerialNumber' => '5410BR/12/9VV',
                        'yearOfProduction' => '2019',
                        'rrp' => '30200',
                        'description' => 'Case curved tonneau in 18-carat rose gold with a delicately fluted caseband. Dimensions 42 x 35mm. Welded lugs with screw bars. Water-resistant to 3 bar (30m).
                        Dial curved, in silvered 18-carat gold, engine-turned.  Individually numbered and signed Breguet. Hours chapter with luminescent Roman numerals. Small seconds at 6 o’clock. Date indication at 12 o’clock. Luminescent Breguet open-tipped hands in blued steel
                        Self-winding movement, numbered and signed Breguet. Cal. 516GG. 111/2 lignes. 30 jewels. 65 hours power reserve. In-line Swiss lever escapement. Silicon balance-spring. Balance frequency 4Hz. Adjusted in 6 positions.
                        Leather strap.',
                        'image' => [
                            'breguet_heritage_5410.png',
                        ],
                    ],
                ],
                self::BRAND_2 => [
                    [
                        'brand' => 'HARRY WINSTON',
                        'model' => 'Premier Moon Phase 36mm',
                        'size' => '36mm',
                        'manufacturerSerialNumber' => 'PRNQHM36WW001',
                        'yearOfProduction' => '2019',
                        'rrp' => '11662',
                        'description' => 'Classique wristwatch in 18-carat gold. Self-winding movement. Balance spring, lever and escape wheel in silicon. Rotating platinum-plated dial, hand-engraved on a rose engine. Silvered gold chapter ring. Melody on/off and autonomy indicators. Water-resistant to 3 bar (30 m).',
                        'image' => [
                            'harry_winston_preimere_moon_phase_36.png',
                        ],
                    ],
                    [
                        'brand' => 'HARRY WINSTON',
                        'model' => 'Project Z16',
                        'size' => '42.2mm',
                        'manufacturerSerialNumber' => 'OCEACC42ZZ001',
                        'yearOfProduction' => '2019',
                        'rrp' => '15270',
                        'description' => 'Project Z16 is the sixteenth model in the Project Z series and is the first to be equipped with a full calendar delivering the date, day of the week and month. Made of Zalium and limited to 100 pieces, the Z16 is a contemporary and exuberant creation that showcases an openworked 3D cityscape of gears and mechanical components, supported by a symmetrical network of bridges.',
                        'image' => [
                            'harry_winston_project_z16.png',
                        ],
                    ],
                    [
                        'brand' => 'HARRY WINSTON',
                        'model' => 'Midnight Diamond Drops Chocolate 39mm',
                        'size' => '39mm',
                        'manufacturerSerialNumber' => 'MIDQMP39RR004',
                        'yearOfProduction' => '2019',
                        'rrp' => '11323.17',
                        'description' => 'Following in the steps of the beloved white gold Midnight Diamond Drops timepiece, Harry Winston unveils the Midnight Diamond Drops Chocolate, a radiant tribute to nature’s most breath-taking spectacles, with its recreation of snowfall and the phases of the moon. Housed in a luxurious rose gold case, the delectable chocolate mother-of-pearl dial offers a warm background for the icy scenery.',
                        'image' => [
                            'harry_winston_midnight_diamond_drops_chocolate_39.png',
                        ],
                    ],
                    [
                        'brand' => 'HARRY WINSTON',
                        'model' => 'Histoire de Tourbillon 6',
                        'size' => '55x49mm',
                        'manufacturerSerialNumber' => 'HCOMTT55WW001',
                        'yearOfProduction' => '2019',
                        'rrp' => '550000',
                        'description' => 'The curtain rises on the sixth chapter of Histoire de Tourbillon. This High Watchmaking and High Complication by Harry Winston pushes the envelope in both technical sophistication and exclusivity. A genuine first in the history of horology, it combines two independent hour indications: one regulated by a tri-axial tourbillon and the other by a karussel.',
                        'image' => [
                            'harry_winston_histoire_de_tourbillon_6.png',
                        ],
                    ],
                ],
                self::BRAND_3 => [
                    [
                        'brand' => 'BLANCPAIN',
                        'model' => 'Villeret Quantième Complet 8 Jours',
                        'size' => '42mm',
                        'manufacturerSerialNumber' => '6639-3631-55B',
                        'yearOfProduction' => '2019',
                        'rrp' => '35100',
                        'description' => 'The Villeret Quantième Complet model is one of Blancpain’s great classics. Equipped with a complete calendar and moon phases, it is distinguished by its two central serpentine-shaped hands guiding the date and the moon phases. This model is powered by the self-winding 6763 calibre, developed and produced by Blancpain. It has a four-day power reserve and features a silicon balance-spring. The movement is visible through a sapphire crystal case back revealing the guilloché-worked oscillating weight. The white dial is enhanced with painted blue Arabic numerals and a red gold seconds hand. This new timepiece is fitted with an allligator strap.',
                        'image' => [
                            'blancpain_villeret_quantieme_complet_8.png',
                        ],
                    ],
                    [
                        'brand' => 'BLANCPAIN',
                        'model' => 'Villeret Tourbillon Carrousel',
                        'size' => '44.6mm',
                        'manufacturerSerialNumber' => '2322 3631 55B',
                        'yearOfProduction' => '2019',
                        'rrp' => '474294',
                        'description' => 'The tourbillon and the carrousel (karussel) are two of the major devices aimed at reducing gravity-related effects on the running of the movement. For the first time in horological history, Blancpain introduces a wristwatch combining these two regulators.',
                        'image' => [
                            'blancpain_villeret_tourbillon_carrousel.png',
                        ],
                    ],
                    [
                        'brand' => 'BLANCPAIN',
                        'model' => 'Villeret Ultraplate',
                        'size' => '29mm',
                        'manufacturerSerialNumber' => '6104 3642 55A',
                        'yearOfProduction' => '2019',
                        'rrp' => '9100',
                        'description' => 'A marriage of technical refinement and aesthetic purity, Blancpain’s ultra-slim watch is set apart by its elegance and the technical accomplishments needed to create it.',
                        'image' => [
                            'blancpain_villeret_ultraplate.png',
                        ],
                    ],
                    [
                        'brand' => 'BLANCPAIN',
                        'model' => 'Villeret Quantième Phase de Lune',
                        'size' => '33.2mm',
                        'manufacturerSerialNumber' => '6126 2987 55B',
                        'yearOfProduction' => '2019',
                        'rrp' => '15200',
                        'description' => 'The reproduction of the lunar cycles on the dials of Blancpain complete calendar watches evokes the time-honoured ties between watchmaking and astronomy.',
                        'image' => [
                            'blancpain_villeret_quantieme_phase_de_lune_6.png',
                        ],
                    ],
                ],
                self::BRAND_4 => [
                    [
                        'brand' => 'GLASHÜTTE ORIGINAL',
                        'model' => 'Senator Chronometer',
                        'size' => '42mm',
                        'manufacturerSerialNumber' => '1-58-01-05-34-30',
                        'yearOfProduction' => '2019',
                        'rrp' => '24700',
                        'description' => 'The Senator Chronometer is a watch for connoisseurs. The 42 mm case is in stainless steel. The dial is in silvered barleycorn with painted Arabic numerals. The self-winding movement has a 64-hour power reserve. The watch is fitted with a brown alligator strap.',
                        'image' => [
                            'glashutte_senator_chronometer.png',
                        ],
                    ],
                    [
                        'brand' => 'GLASHÜTTE ORIGINAL',
                        'model' => 'Seventies Chronograph Panorama Date',
                        'size' => '40mm',
                        'manufacturerSerialNumber' => '1-37-02-13-02-70',
                        'yearOfProduction' => '2019',
                        'rrp' => '14400',
                        'description' => 'With its streamlined case, this chronograph is reminiscent of the design classics of the 1970s: dynamic lines and gentle curves give it an unmistakable, retro-modern character. The stainless steel case is satin-polished and has a diameter of 40 x 40 mm.  For this limited model, there are two strap variants to choose from: black rubber or cool stainless steel.',
                        'image' => [
                            'glashutte_vintage_seventies_chronograph_panorama_date.png',
                        ],
                    ],
                    [
                        'brand' => 'GLASHÜTTE ORIGINAL',
                        'model' => 'Sixties Chronograph Annual Edition',
                        'size' => '42mm',
                        'manufacturerSerialNumber' => '1-39-34-05-22-04',
                        'yearOfProduction' => '2019',
                        'rrp' => '7800',
                        'description' => 'In the 1960s the world kicked into gear, and the decade known as the Swinging Sixties had an unprecedented impact on the way people felt about life. Like the trend-setters of the time, this expressive model from our Vintage Collection leaves nothing to chance in terms of look and rhythm. This chronograph features a hand-crafted dial and a highly precise automatic movement along with charisma as entrancing as anything in the music and fashion of the Sixties.',
                        'image' => [
                            'glashutte_vintage_sixties_chronograph_anual_edition.png',
                        ],
                    ],
                    [
                        'brand' => 'GLASHÜTTE ORIGINAL',
                        'model' => 'SeaQ Panorama Date',
                        'size' => '43.20mm',
                        'manufacturerSerialNumber' => '1-36-13-02-81-70',
                        'yearOfProduction' => '2019',
                        'rrp' => '11600',
                        'description' => 'Cool stainless steel and intense blue give this sporty timepiece a striking look. The 43.2 mm case has a fine vertical satin-brushed finish on the sides and a polished facet highlighting the contours. The sapphire crystal case back offers unobstructed views of the highly refined decorative finishing of the automatic movement Calibre 36-13. The SeaQ Panorama Date comes with a particularly robust synthetic fabric or rubber strap, or with a stainless steel bracelet with 8-step fine adjustment mechanism.',
                        'image' => [
                            'glashutte_seaq_panorama_date.png',
                        ],
                    ],
                ],
                self::BRAND_5 => [
                    [
                        'brand' => 'JAQUET DROZ',
                        'model' => 'Grande Seconde Dual Time',
                        'size' => '43mm',
                        'manufacturerSerialNumber' => 'J016030240',
                        'yearOfProduction' => '2019',
                        'rrp' => '6106.96',
                        'description' => 'The Grande Seconde Dual Time is a watch for travellers. The timepiece displays two time zones simultaneously, with the local time shown by the large hand and the reference time by the small hand. The date is indicated by a hand at 6 o’clock. The watch is available in two versions: ivory Grand Feu enamel or black onyx dial. The 43 mm case is available in red gold or stainless steel. The self-winding mechanical movement has a 65-hour power reserve.',
                        'image' => [
                            'jaquet_droz_grande_seconde.png',
                        ],
                    ],
                    [
                        'brand' => 'JAQUET DROZ',
                        'model' => 'The Rolling Stones Automaton',
                        'size' => '43mm',
                        'manufacturerSerialNumber' => 'J0328330011',
                        'yearOfProduction' => '2019',
                        'rrp' => '250000',
                        'description' => 'Black onyx dial and 18-karat red gold applied ring. 18-karat red gold case. Hand-engraved and hand-painted 18-karat white and red gold appliques. Hand-wound mechanical automaton movement with push-button triggering mechanism. Mechanical automaton with animated record and logo. Self-winding mechanical hours and minutes movement. Power reserve of 68 hours. Diameter 43 mm. Unique piece.',
                        'image' => [
                            'jaquet_droz_rolling_stones.png',
                        ],
                    ],
                    [
                        'brand' => 'JAQUET DROZ',
                        'model' => 'Tourbillon SW',
                        'size' => '45mm',
                        'manufacturerSerialNumber' => 'J016030240',
                        'yearOfProduction' => '2019',
                        'rrp' => '105500',
                        'description' => 'Black dial with rubber treatment. 18-carat red gold case. Self-winding tourbillon movement. Power reserve of 7 days. Hours and minutes indicators at 6 o\'clock, tourbillon frame and seconds bridge at 12 o\'clock. Diameter 45 mm.',
                        'image' => [
                            'jaquet_droz_tourbillon_sw.png',
                        ],
                    ],
                    [
                        'brand' => 'JAQUET DROZ',
                        'model' => 'Tourbillon Paillonnée',
                        'size' => '43mm',
                        'manufacturerSerialNumber' => 'J030033240',
                        'yearOfProduction' => '2019',
                        'rrp' => '119200',
                        'description' => 'Red Grand Feu paillonné-enameled dial. Silver opaline subdial. 18-karat red gold case and applied ring. Self-winding mechanical tourbillon movement. Power reserve of 7 days. Diameter 43 mm. Numerus Clausus of 8.',
                        'image' => [
                            'jaquet_droz_tourbillon_paillonnee.png',
                        ],
                    ],
                ],
                self::BRAND_6 => [
                    [
                        'brand' => 'RADO',
                        'model' => 'Captain Cook Automatic',
                        'size' => '42mm',
                        'manufacturerSerialNumber' => 'R32136323',
                        'yearOfProduction' => '2019',
                        'rrp' => '2350',
                        'description' => 'This polished yellow gold coloured Captain Cook has a water resistance to 30 bar. The case and the turning bezel are made of stainless steel, while the polished green high-tech ceramic bezel insert is engraved with yellow gold coloured metallised numbers and markers.',
                        'image' => [
                            'rado_captain_cook_automatic.png',
                        ],
                    ],
                    [
                        'brand' => 'RADO',
                        'model' => 'True Square Automatic Skeleton',
                        'size' => '38mm',
                        'manufacturerSerialNumber' => 'R27125152',
                        'yearOfProduction' => '2019',
                        'rrp' => '2600',
                        'description' => 'The new True Square Skeleton adds another highlight to the popular Rado True Square collection. What catches the eye is the R808 skeletonised movement and the two-level dial, which practically merges with the movement.',
                        'image' => [
                            'rado_square_automatic_skeleton.png',
                        ],
                    ],
                    [
                        'brand' => 'RADO',
                        'model' => 'Integral',
                        'size' => '22.7mm',
                        'manufacturerSerialNumber' => 'R20845162',
                        'yearOfProduction' => '2019',
                        'rrp' => '1850',
                        'description' => 'The iconic Integral has been redesigned to suit the needs and preferences of modern wearers, but the new models remain true to the original in look and feel. The first Rado to feature high-tech ceramic in 1986, it is an enduring piece that continues to delight its fans and attract new enthusiasts.',
                        'image' => [
                            'rado_integral.png',
                        ],
                    ],
                    [
                        'brand' => 'RADO',
                        'model' => 'DiaMaster 1314',
                        'size' => '33mm',
                        'manufacturerSerialNumber' => 'R14064745',
                        'yearOfProduction' => '2019',
                        'rrp' => '1075',
                        'description' => 'The sacred numerical symbol 1314 was widely known to poets and lovers in the Ancient Far East, for its profound resonance with the concepts of reliability and permanence.',
                        'image' => [
                            'rado_diamaster_1314.png',
                        ],
                    ],
                ],
                self::BRAND_7 => [
                    [
                        'brand' => 'OMEGA',
                        'model' => 'Seamaster Diver 300M',
                        'size' => '41mm',
                        'manufacturerSerialNumber' => '234.30.41.21.03.002',
                        'yearOfProduction' => '2019',
                        'rrp' => '6800',
                        'description' => 'This 41 mm Seamaster 300, with its symmetrical case in polished and brushed stainless steel, has a matching bracelet.',
                        'image' => [
                            'omega_seamaster_diver_300.png',
                        ],
                    ],
                    [
                        'brand' => 'OMEGA',
                        'model' => 'Super Racing',
                        'size' => '44.25mm',
                        'manufacturerSerialNumber' => '329.30.44.51.01.003',
                        'yearOfProduction' => '2023',
                        'rrp' => '10700',
                        'description' => 'This 44.25 mm Speedmaster in stainless steel is the first OMEGA timepiece to include the Spirate™ System released in 2023.',
                        'image' => [
                            'omega_super_racing.png',
                        ],
                    ],
                    [
                        'brand' => 'OMEGA',
                        'model' => 'Prestige',
                        'size' => '42mm',
                        'manufacturerSerialNumber' => '434.53.41.21.10.001',
                        'yearOfProduction' => '2019',
                        'rrp' => '12900',
                        'description' => 'A timeless collection since 1994, the OMEGA De Ville Prestige is now in its third generation. Continuing to represent classical design and refined elegance, the timepieces offer a range of patterns, finishes and colours to suit different lifestyles and personalities.',
                        'image' => [
                            'omega_prestige.png',
                        ],
                    ],
                    [
                        'brand' => 'OMEGA',
                        'model' => 'Diver 300M',
                        'size' => '42mm',
                        'manufacturerSerialNumber' => '522.21.42.20.04.001',
                        'yearOfProduction' => '2019',
                        'rrp' => '8000',
                        'description' => 'The Olympic Games Paris 2024 will mark the 31st time since 1932 that OMEGA has fulfilled the vital role of Official Timekeeper at the Olympic Games. To celebrate that historic occasion, this special Seamaster Diver 300M offers a sporty design in white, gold, and black - following the official Olympic colour chart.',
                        'image' => [
                            'omega_diver_300.png',
                        ],
                    ],
                ],
                self::BRAND_8 => [
                    [
                        'brand' => 'LONGINES',
                        'model' => 'Master Collection',
                        'size' => '40mm',
                        'manufacturerSerialNumber' => 'L2.909.4.78.3',
                        'yearOfProduction' => '2019',
                        'rrp' => '7250',
                        'description' => 'The Master Collection is a line of watches with a classic and timeless design. This model has a 40 mm stainless steel case. The dial is in silvered barleycorn with painted Arabic numerals. The self-winding movement has a 64-hour power reserve. The watch is fitted with a brown alligator strap.',
                        'image' => [
                            'longines_master_collection.png',
                        ],
                    ],
                    [
                        'brand' => 'LONGINES',
                        'model' => 'Record',
                        'size' => '38.50mm',
                        'manufacturerSerialNumber' => 'L2.820.8.92.2',
                        'yearOfProduction' => '2019',
                        'rrp' => '6800',
                        'description' => 'n Longines’ purest watchmaking tradition, the Record automatic models combine classic elegance and excellence, aspiring to become the spearheads of the brand. And there is no shortage of arguments for these exceptional timepieces, whose movement includes a single-crystal silicon balance spring with unique properties.',
                        'image' => [
                            'longines_record.png',
                        ],
                    ],
                    [
                        'brand' => 'LONGINES',
                        'model' => 'Dolcevita',
                        'size' => '20.50x32.00mm',
                        'manufacturerSerialNumber' => 'L5.255.9.71.0',
                        'yearOfProduction' => '2019',
                        'rrp' => '8200',
                        'description' => 'Since its inception, the Longines DolceVita collection has illustrated contemporary elegance of the Longines watchmaking brand worldwide. Inspired by the "Dolce Vita", it is an homage to the sweetness of life. Today, a new chapter in this collection opens with a unique interpretation featuring softened lines. These new variations will not fail to impress women who have made charm a way of life.',
                        'image' => [
                            'longines_dolcevita.png',
                        ],
                    ],
                    [
                        'brand' => 'LONGINES',
                        'model' => 'Elegant Collection',
                        'size' => '37mm',
                        'manufacturerSerialNumber' => 'L2.909.4.78.3',
                        'yearOfProduction' => '2019',
                        'rrp' => '4300',
                        'description' => 'Since its earliest days, Longines has always focused on elegance. This quality can be found not only in its products but also in its communication, with the now famous slogan "Elegance is an attitude". So it comes as no surprise that the brand is now launching the Longines Elegant Collection, which comprises models that are the perfect embodiment of the classical design and sleek lines typical of Longines timepieces.',
                        'image' => [
                            'longines_elegant_collection.png',
                        ],
                    ],
                ],
                self::BRAND_9 => [
                    [
                        'brand' => 'TISSOT',
                        'model' => 'T-Classic Tradition',
                        'size' => '42mm',
                        'manufacturerSerialNumber' => 'T063.610.16.037.00',
                        'yearOfProduction' => '2019',
                        'rrp' => '275',
                        'description' => 'The T-Classic Tradition is a line of watches with a classic and timeless design. This model has a 42 mm stainless steel case. The dial is in silvered barleycorn with painted Arabic numerals. The self-winding movement has a 64-hour power reserve. The watch is fitted with a brown alligator strap.',
                        'image' => [
                            'tissot_classic_tradition.png',
                        ],
                    ],
                    [
                        'brand' => 'TISSOT',
                        'model' => 'Sideral S',
                        'size' => '41mm',
                        'manufacturerSerialNumber' => 'T145.407.97.057.00',
                        'yearOfProduction' => '2019',
                        'rrp' => '275',
                        'description' => 'Discover the Tissot Sideral, a captivating fusion of vintage allure and modern craftsmanship. The watch features a 41mm wide forged carbon case and a vibrant yellow strap, paying homage to its iconic 1970s roots. The dial displays a multi-coloured luminescent animation, enhancing its unique charm. Water-resistant up to 30 bar (300 meters) and equipped with Super-LumiNova® coated indices, the Sideral is a statement piece for trendsetters and history enthusiasts. The interchangeable perforated rubber strap allows for effortless style adaptation. Embrace the extraordinary with the Tissot Sideral.',
                        'image' => [
                            'tissot_sideral_s.png',
                        ],
                    ],
                    [
                        'brand' => 'TISSOT',
                        'model' => 'Seastar 1000',
                        'size' => '40mm',
                        'manufacturerSerialNumber' => 'T120.410.27.051.00',
                        'yearOfProduction' => '2019',
                        'rrp' => '395',
                        'description' => 'The T-Classic Tradition is a line of watches with a classic and timeless design. This model has a 42 mm stainless steel case. The dial is in silvered barleycorn with painted Arabic numerals. The self-winding movement has a 64-hour power reserve. The watch is fitted with a brown alligator strap.',
                        'image' => [
                            'tissot_seastar_100.png',
                        ],
                    ],
                    [
                        'brand' => 'TISSOT',
                        'model' => 'Gentleman powermatic 80 silicium',
                        'size' => '42mm',
                        'manufacturerSerialNumber' => 'T127.407.11.091.01',
                        'yearOfProduction' => '2019',
                        'rrp' => '765',
                        'description' => 'The Tissot Gentleman is a multi-purpose watch, both ergonomic and elegant in any circumstance. It is equally suitable for wearing in a business environment, where conventional dress codes apply, as at the weekend, when it adapts easily to leisure activities. As part of the life of a modern, active man, the Tissot Gentleman becomes the perfect companion for every day, every occasion and every style. Discover this chic and elegant automatic version with its deep green dial and textured stainless steel bracelet.',
                        'image' => [
                            'tissot_gentleman_powermatic_80.png',
                        ],
                    ],
                ],
                self::BRAND_10 => [
                    [
                        'brand' => 'CERTINA',
                        'model' => 'DS-1 Powermatic 80',
                        'size' => '41mm',
                        'manufacturerSerialNumber' => 'C038.407.16.097.00',
                        'yearOfProduction' => '2019',
                        'rrp' => '825',
                        'description' => 'The DS Powermatic 80 brings together traditional watchmaking with modern elements. Classic, delicate hands and indices are combined with intense colours, satin-finished surfaces and high-quality leather or NATO straps. At the heart of the timepiece\'s inner workings, a Powermatic calibre with Nivachron™ balance spring ensures contemporary precision.',
                        'image' => [
                            'certina_ds_1_powermatic_80.png',
                        ],
                    ],
                    [
                        'brand' => 'CERTINA',
                        'model' => 'DS Caimano',
                        'size' => '39mm',
                        'manufacturerSerialNumber' => 'C035.410.16.037.01',
                        'yearOfProduction' => '2019',
                        'rrp' => '280',
                        'description' => 'The art of timekeeping reduced to its essentials: there is nothing here to distract from the magic of the instant. The elegant-classic design, narrow silhouette and uncompromising reliability give the watch its timeless character.',
                        'image' => [
                            'certina_ds_caimano.png',
                        ],
                    ],
                    [
                        'brand' => 'CERTINA',
                        'model' => 'DS Podium',
                        'size' => '44mm',
                        'manufacturerSerialNumber' => 'C034.427.16.087.01',
                        'yearOfProduction' => '2019',
                        'rrp' => '870',
                        'description' => 'This professional piece makes a bold statement with its harmonious colours and multi-layered architectural design. Its robust exterior is complemented by an automatic calibre and magnetic field-resistant NivachronTM balance spring – just the precision and durability wearers expect from a Certina watch.',
                        'image' => [
                            'certina_ds_podium.png',
                        ],
                    ],
                    [
                        'brand' => 'CERTINA',
                        'model' => 'DS Super PH500M',
                        'size' => '43mm',
                        'manufacturerSerialNumber' => 'C037.407.17.280.10',
                        'yearOfProduction' => '2019',
                        'rrp' => '870',
                        'description' => 'The striking DS Super PH500M is sure to attract attention thanks to its retro style and state-of-the-art mechanism. A number of functions such as its magnetic field-resistant Nivachron™ balance spring and water resistance of up to 500 m make this diver\'s watch the ideal companion on every journey – even underwater.',
                        'image' => [
                            'certina_ds_super_ph500.png',
                        ],
                    ],
                ],
                self::BRAND_11 => [
                    [
                        'brand' => 'SWATCH',
                        'model' => 'Sistem51',
                        'size' => '42mm',
                        'manufacturerSerialNumber' => 'YIS401G',
                        'yearOfProduction' => '2019',
                        'rrp' => '164',
                        'description' => 'The Sistem51 is a line of watches with a classic and timeless design. This model has a 42 mm stainless steel case. The dial is in silvered barleycorn with painted Arabic numerals. The self-winding movement has a 64-hour power reserve. The watch is fitted with a brown alligator strap.',
                        'image' => [
                            'swatch_sistem51.png',
                        ],
                    ],
                    [
                        'brand' => 'SWATCH',
                        'model' => 'What If Black',
                        'size' => '41.80mm',
                        'manufacturerSerialNumber' => 'SO29B703',
                        'yearOfProduction' => '2019',
                        'rrp' => '91',
                        'description' => 'The square BIOCERAMIC watch case features edge-to-edge biosourced glass that gives a side view of the watch’s indexes. The square dial has glow-in-the-dark hands and a day-date window at 3 o\'clock. The dial is complemented by an integrated biosourced strap. A printed battery cover on the back depicts one of the watch faces that inspired this black, square watch.',
                        'image' => [
                            'swatch_what_if_black.png',
                        ],
                    ],
                    [
                        'brand' => 'SWATCH',
                        'model' => 'Twice Again',
                        'size' => '41mm',
                        'manufacturerSerialNumber' => 'SO29B703',
                        'yearOfProduction' => '2019',
                        'rrp' => '73',
                        'description' => 'TWICE AGAIN (SO29B703) reminds us that there’s a first time for everything and once isn’t enough. The minimalistic watch has a black biosourced strap and day and date windows on the white dial.',
                        'image' => [
                            'swatch_twice_again.png',
                        ],
                    ],
                    [
                        'brand' => 'SWATCH',
                        'model' => 'Ultralavande',
                        'size' => '34mm',
                        'manufacturerSerialNumber' => 'GE718',
                        'yearOfProduction' => '2019',
                        'rrp' => '69',
                        'description' => 'Lovely in lavender, ULTRALAVANDE (GE718) has a mesmerizing Swatch Solar Spectrum Glass covering the purple dial. The strap fades from light purple to white.',
                        'image' => [
                            'swatch_ultralavande.png',
                        ],
                    ],
                ],
                self::BRAND_12 => [
                    [
                        'brand' => 'BALMAIN',
                        'model' => 'Classic R',
                        'size' => '34mm',
                        'manufacturerSerialNumber' => 'B4101.31.72',
                        'yearOfProduction' => '2019',
                        'rrp' => '480',
                        'description' => '-',
                        'image' => [
                            'balmain_classic_r.png',
                        ],
                    ],
                    [
                        'brand' => 'BALMAIN',
                        'model' => 'Beleganza',
                        'size' => '40mm',
                        'manufacturerSerialNumber' => 'B1341.33.22',
                        'yearOfProduction' => '2019',
                        'rrp' => '410',
                        'description' => '-',
                        'image' => [
                            'balmain_beleganza.png',
                        ],
                    ],
                    [
                        'brand' => 'BALMAIN',
                        'model' => 'Eirini',
                        'size' => '25+33mm',
                        'manufacturerSerialNumber' => 'B4391.32.66',
                        'yearOfProduction' => '2019',
                        'rrp' => '590',
                        'description' => '-',
                        'image' => [
                            'balmain_eirini.png',
                        ],
                    ],
                    [
                        'brand' => 'BALMAIN',
                        'model' => 'Elegance Chic',
                        'size' => '33mm',
                        'manufacturerSerialNumber' => 'B7691.33.16',
                        'yearOfProduction' => '2019',
                        'rrp' => '400',
                        'description' => '-',
                        'image' => [
                            'balmain_elegance_chic.png',
                        ],
                    ],
                ],
            ];
        }

        return $this->watches;
    }
}

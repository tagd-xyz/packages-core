<?php

namespace Tagd\Core\Database\Seeders\Items;

use Illuminate\Database\Seeder;
use Tagd\Core\Database\Seeders\Traits\TruncatesTables;
use Tagd\Core\Database\Seeders\Traits\UsesFactories;
use Tagd\Core\Models\Item\Type;

class TypesSeeder extends Seeder
{
    use TruncatesTables, UsesFactories;

    /**
     * Seed the application's database for development purposes.
     *
     * @return void
     */
    public function run(array $options = [])
    {
        extract([
            'truncate' => false,
            ...$options,
        ]);

        $this->setupFactories();

        if ($truncate) {
            $this->truncate([
                (new Type())->getTable(),
            ]);
        }

        if (! empty(Type::count())) {
            return;
        }

        $types = [
            // 'Cars',
            // 'Motorbike',
            'Motors & Accessories' => [
                'Cars & van spare parts',
                'GPS & electronics',
                'Mechanical tools',
                'Motorbike and four-stroke spare parts',
                'Others',
            ],
            'Fashion & Accessories' => [
                'Accessories' => [
                    'Bags & Backpacks' => [
                        'Wallets',
                        'Backpacks',
                        'Purses',
                        'Shoulder Bags',
                        'Handbags',
                        'Other bags',
                        'Bum bags',
                        'Briefcases',
                        'Washbag',
                        'Luggage & suitcases',
                        'Tote bags',
                        'Clutches',
                        'Make-up bags',
                        'Satchels',
                        'Patterned & embroidered bags',
                        'Holdalls',
                        'Billfolds',
                        'Laptop bags',
                    ],
                    'Glasses & sunglasses',
                    'Hats & caps' => [
                        'Caps',
                        'Beanies',
                        'Hats',
                        'Winter hats',
                        'Berets',
                        'Headbands',
                        'Other hats & caps',
                    ],
                    'Scarves & shawls' => [
                        'Scarves',
                        'Hankerchief',
                        'Head scarves',
                        'Oversized scarves & shawls',
                        'Other scarves & shawls',
                    ],
                    'Belts',
                    'Gloves',
                    'Hair accesories',
                    'Other accesories',
                    'Ties & pocket squares',
                    'Umbrellas',
                    'Watches',
                ],
                'Beauty' => [
                    'Cologne',
                    'Make-up',
                    'Other beauty items',
                    'Perfume',
                    'Personal care' => [
                        'Face care',
                        'Hand care',
                        'Nail care',
                        'Body care',
                        'Hair care',
                        'Grooming items and kits',
                        'aftershave & cologne',
                        'Other persinal care',
                    ],
                    'Tools & accesories' => [
                        'Hair-styling tools',
                        'Face-care tools',
                        'Body-care tools',
                        'Nail-care tools',
                        'Make-up tools',
                        'Shaving tools',
                        'Grooming tools',
                        'Other tools',
                    ],
                ],
                'Clothing items' => [
                    'Bibs',
                    'Blazers',
                    'Blouses',
                    'Bodies',
                    'Camisoles',
                    'Crops',
                    'Dresses',
                    'Gowns',
                    'Jeans',
                    'Kimono',
                    'Mini skirts',
                    'Overalls',
                    'Pajamas',
                    'Polo shirts',
                    'Pullovers',
                    'Shirts',
                    'Shorts',
                    'Skirts',
                    'Sweaters',
                    'Sweatshirts',
                    'T-shirts',
                    'Tops',
                    'Trousers',
                    'Waistcoats',
                ],
                'Footwear' => [
                    'Ballet flats',
                    'Boots and ankle boots',
                    'Clogs',
                    'Flip-flops and sandasls',
                    'Heels',
                    'Moccasins',
                    'Shoes',
                    'Sneakers',
                    'Wedges',
                ],
                'Jewellery' => [
                    'Anklets',
                    'Beads',
                    'Bracelets',
                    'Brooches',
                    'Chains',
                    'Cufflinks',
                    'Earrings',
                    'Jewellery sets',
                    'Jewellery boxes',
                    'Necklaces',
                    'Other jewellery',
                    'Pendants',
                    'Piercings',
                    'Rings',
                ],
                'Maternity clothes',
                'Outwear' => [
                    'Bolero jackets',
                    'Bomber jackets',
                    'Coats',
                    'Jackets',
                    'Parkas',
                    'Ponchos',
                    'Rain jackets',
                    'Trench coats',
                ],
                'Sportswear' => [
                    'Jumpsuits and sports pants',
                    'Official clothing',
                    'Sports T-shirts',
                    'Sports bras',
                    'Tracksuits',
                ],
                'Suits, parties and weddings' => [
                    'Men suits',
                    'Party dresses',
                    'Traditional costumes',
                    'Wedding dresses',
                ],
                'Swimsuits & bikinis' => [
                    'Bikini',
                    'Swimear',
                ],
                'Underwear & socks' => [
                    'Bras',
                    'Lingerie',
                    'Socks',
                    'Stocking,Underpants',
                ],
                'Other clothing',
            ],
            // 'Real Estate',
            'TV, Audio & Cameras' => [
                'Batteris & charges' => [
                    'Charges', 'Cechargeable batteries',
                ],
                'Cameras & photography' => [
                    'Camera & flash accesories',
                    'Camera lens & filters',
                    'Camera parts & tools',
                    'Cameras',
                    'Studio lighting',
                    'Tripods & supports',
                    'Vintage photography',
                ],
                'Drones',
                'Headphones & earphones' => [
                    'Headphones',
                    'In-ear headphones',
                ],
                'Music Players',
                'Projectors & accesories',
                'Security cameras',
                'TV & accesories' => [
                    'Accesories',
                    'Antennas',
                    'Home cinema',
                    'Remote controls',
                    'Televisions',
                    'Vintage televisions',
                    'Wires',
                ],
                'Video & accesories',
                'Other TV Audio & Cameras,',
            ],
            'Cell Phones & Accessories',
            'Computers & Electronic' => [
                'Cables',
                'Charges & batteries',
                'Computers & accesories',
                'Monitors',
                'Printers & scanners',
                'Software',
                'Virtual & augmented reality',
                'Other computers & electronic',
            ],
            'Sports & Leiusure' => [
                'Basketball',
                'Crafts Arts & crafts',
                'Exercise bikes',
                'Fitness, running & yoga',
                'Golf',
                'Handball',
                'Mountain & ski',
                'Other sports',
                'Recreational & board games',
                'Rugby',
                'Scooters & skating',
                'Soccer',
                'Swimming & pool accesories',
                'Tennis & paddle tennis',
                'Voleyball,',
            ],
            'Bikes' => [
                'Bike accesories',
                'Bike components & spare parts',
                'Bikes & tricycles',
                'Protective gear & clothing',
                'Other bikes',
            ],
            'Games & Consoles' => [
                'Console accesories',
                'Console spare parts',
                'Consoles',
                'Gaming merchandise',
                'Manual & guides',
                'Video games',
                'Other games & consoles',
            ],
            'Home & Garden' => [
                'Bathroom supplies',
                'Decoration',
                'Furniture',
                'Garden & outdoors',
                'Interior lighting',
                'Kitchen, dinning room & bar',
                'Mattrasses & bedding',
                'Pet supplies',
                'Storage',
                'Other home & garden',
            ],
            'Appliances' => [
                'Air conditioning & heating',
                'Kitchen appliances',
                'Laundry appliances',
                'Replacement parts',
                'Small appliances',
                'Vitroceramic hobs',
                'Other appliances',
            ],
            'Movies, Books & Music' => [
                'Books',
                'CDs & vinyls',
                'Comics & graphic novels',
                'Magazines',
                'Movies & series',
                'Musical instruments',
                'Posters and merchandising',
                'Procfessional sound system',
                'Record players',
                'Scores & musical librettos',
            ],
            'Baby & Child' => [
                'Baby feeding',
                'Baby furniture',
                'Baby transport',
                'Bath accesories',
                'Children\'s clothes',
                'Children\s costumes',
                'Cots and beds',
                'High chairs & baby walkers',
                'Maternity',
                'Safety & care',
                'School supplies',

                'Toys, ganes and teddies',
            ],
            'Collectibles & Art' => [
                'Antiques',
                'Cars & motorcycles',
                'Coins & bills',
                'Desek suuplies',
                'Flags',
                'Handcrafts & decoration',
                'Key holders',
                'Magnets',
                'Playing cards',
                'Postcards & souvenirs',
                'Sporting collectibles',
                'Stamp collecting',
                'Toy collections',
                'Wartime collectinles',
                'Watchers',
                'Other',
            ],
            'Construction' => [
                'Bathtubs',
                'Bathrooms',
                'Doors & windows',
                'Electricity & home lighting',
                'Flooring & paving',
                'Hardware',
                'Kitchens',
                'Ladder & scaffolings',
                'Paints & vanishes',
                'Tools & machinery',
                'Wood & other materials',
                'Other construction',
            ],
            'Agriculture & Industrial' => [
                'Agricultural & farmings' => [
                    'Agricultural machinery',
                    'Spare parts for agricultural machinery',
                    'Tractors',
                ],
                'Industrial' => [
                    'Industrial machinery',
                    'Spare parts for industrial machinery',
                    'Industrial tools',
                ],
            ],
        ];
        $this->process($types);
    }

    /**
     * @return void
     */
    private function process(array $types, Type $parent = null)
    {
        foreach ($types as $key => $value) {
            if (is_array($value)) {
                $item = Type::firstOrCreate([
                    'name' => $key,
                    'parent_id' => $parent ? $parent->id : null,
                ]);

                $this->process($value, $item);
            } else {
                Type::firstOrCreate([
                    'name' => $value,
                    'parent_id' => $parent ? $parent->id : null,
                ]);
            }
        }
    }
}

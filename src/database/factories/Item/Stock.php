<?php

namespace Tagd\Core\Database\Factories\Item;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tagd\Core\Models\Item\Type;

class Stock extends Factory
{
    private function randomName(Type $type): string
    {
        switch ($type->name) {
            case 'Handbags':
                return collect([
                    'Leather Tote Bag',
                    'Leather Mini Cross-Body',
                    'Basic Tote Bag',
                    'Mini City Bag',
                    'Horsebit 1955 shoulder Bag',
                    'Nojum Diana Mini Python Bag',
                ])->random();

            case 'Footwear':
                return collect([
                    'Ca Pro Lux',
                    'Gazelle',
                    'Kick Lo Leather',
                    'Teveris Nitro',
                    'Balance 327 Grey Day',
                    'Chuck Taylor All',
                    'Waffle Trainer 2',
                ])->random();

            default:
                return $this->faker->words(2, true);
        }
    }

    private function randomType(): Type
    {
        return Type::inRandomOrder()->first();
    }

    private function randomBrand(Type $type): string
    {
        switch ($type->name) {
            case 'Handbags':
                return collect([
                    'Gucci',
                    'Louis Vuitton',
                    'Cartier',
                    'Zara',
                    'H&M',
                    'Uniqlo',
                    'Hermès',
                ])->random();

            case 'Footwear':
                return collect([
                    'Nike',
                    'Adidas',
                    'Puma',
                    'Vans',
                    'Converse',
                ])->random();

            default:
                return $this->faker->company();
        }
    }

    private function randomSize(Type $type): string
    {
        switch ($type->name) {
            case 'Handbags':
                return collect([
                    'S',
                    'M',
                    'L',
                ])->random();

            case 'Footwear':
                return collect([
                    '6',
                    '6.5',
                    '7',
                    '7.5',
                    '8',
                    '8.5',
                    '9',
                    '9.5',
                ])->random();

            default:
                return '';
        }
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = $this->randomType();

        return [
            'name' => $this->randomName($type),
            'type_id' => $type->id,
            'description' => $this->faker->paragraph(),
            'properties' => [
                'brand' => $this->randomBrand($type),
                'model' => $this->faker->words(2, true),
                'size' => $this->randomSize($type),
                'manufacturerSerialNumber' => $this->faker->uuid,
                'yearOfProduction' => $this->faker->year,
                'rrp' => $this->faker->randomFloat(2, 0, 1000),
            ],
        ];
    }

    /**
     * Set as given type
     */
    public function type(Type $type): self
    {
        return $this->state(function (array $attributes) use ($type) {
            return [
                'name' => $this->randomName($type),
                'type_id' => $type->id,
                'description' => $this->faker->paragraph(),
                'properties' => [
                    'brand' => $this->randomBrand($type),
                    'model' => $this->faker->words(2, true),
                    'size' => $this->randomSize($type),
                ],
            ];
        });
    }
}

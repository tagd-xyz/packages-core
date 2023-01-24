<?php

namespace Tagd\Core\Database\Factories\Item;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tagd\Core\Models\Item\Type;

class Item extends Factory
{
    private function randomType(): string
    {
        $types = Type::cases();

        return  $types[array_rand($types)]->value ?? null;
    }

    private function randomBrand(string $type): string
    {
        switch ($type) {
            case Type::FASHION->value:
                return collect([
                    'Gucci',
                    'Louis Vuitton',
                    'Cartier',
                    'Zara',
                    'H&M',
                    'Uniqlo',
                    'Hermès',
                ])->random();

            case Type::SNEAKERS->value:
                return collect([
                    'Nike',
                    'Adidas',
                ])->random();
        }
    }

    private function randomSize(string $type): string
    {
        switch ($type) {
            case Type::FASHION->value:
                return collect([
                    'S',
                    'M',
                    'L',
                ])->random();

            case Type::SNEAKERS->value:
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
            'name' => $this->faker->words(3, true),
            'type' => $type,
            'description' => $this->faker->paragraph(),
            'properties' => [
                'brand' => $this->randomBrand($type),
                'model' => $this->faker->words(2, true),
            ],
        ];
    }

    /**
     * Set as fashion type
     *
     * @return self
     */
    public function type(Type $type): self
    {
        return $this->state(function (array $attributes) use ($type) {
            return [
                'type' => $type->value,
                'properties' => [
                    'brand' => $this->randomBrand($type->value),
                    'model' => $this->faker->words(2, true),
                    'size' => $this->randomSize($type->value),
                ],
            ];
        });
    }
}

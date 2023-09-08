<?php

namespace Tagd\Core\Database\Factories\Upload;

use Illuminate\Database\Eloquent\Factories\Factory;

class Upload extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'bucket' => 'a_bucket',
            'folder' => 'a_folder',
            'file' => 'a_file',
        ];
    }

    // /**
    //  * Set tagd as active
    //  */
    // public function forStock(bool $isActive = true): self
    // {
    //     return $this->state(function (array $attributes) use ($isActive) {
    //         return [
    //             'status' => $isActive ? TagdStatus::ACTIVE : TagdStatus::INACTIVE,
    //             'status_at' => Carbon::now(),
    //         ];
    //     });
    // }
}

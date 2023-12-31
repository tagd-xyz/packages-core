<?php

namespace Tagd\Core\Database\Seeders\Ref;

use Illuminate\Database\Seeder;
use Tagd\Core\Database\Seeders\Traits\TruncatesTables;
use Tagd\Core\Database\Seeders\Traits\UsesFactories;
use Tagd\Core\Models\Ref\TrustSetting;
use Tagd\Core\Models\Ref\TrustSettingName;

class TrustSettingsSeeder extends Seeder
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
                (new TrustSetting())->getTable(),
            ]);
        }

        if (! empty(TrustSetting::count())) {
            return;
        }

        $brandModifiers = [
            'adidas' => 20,
            'gucci' => 50,
        ];

        TrustSetting::firstOrCreate([
            'name' => TrustSettingName::BRAND_MODIFIER,
        ], [
            'setting' => $brandModifiers,
        ]);
    }
}

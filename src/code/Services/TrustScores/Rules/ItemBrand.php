<?php

namespace Tagd\Core\Services\TrustScores\Rules;

use Illuminate\Contracts\Container\BindingResolutionException;
use Tagd\Core\Models\Item\TagdStatus;
use Tagd\Core\Models\Ref\TrustSetting;
use Tagd\Core\Repositories\Ref\TrustSettings as TrustSettingsRepo;

class ItemBrand extends Base
{
    /**
     * Apply the rule
     *
     * @throws BindingResolutionException
     */
    public function apply(): float
    {
        // // apply only on resale
        // if (TagdStatus::RESALE != $this->tagd->status) {
        //     return 0.0;
        // }

        // apply only once
        if (! $this->tagd->is_root) {
            return TrustSetting::SCORE_DEFAULT;
        }

        // get brand from item properties
        $brand = $this->tagd->item->properties['brand'] ?? '';

        // get modifier for brand
        $repo = app(TrustSettingsRepo::class);
        $modifier = $repo->getModifierForBrand($brand);

        // calculate score based on modifier
        if ($modifier == TrustSetting::MODIFIER_MIN) {
            return 0.0;
        } else {
            switch ($this->modifier2step($modifier, 4)) {
                case 1:
                    return 0.0;
                case 2:
                    return 5.0;
                case 3:
                    return 10.0;
                case 4:
                    return 20.0;
            }
        }
    }
}

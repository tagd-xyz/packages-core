<?php

namespace Tagd\Core\Repositories\Ref;

use Tagd\Core\Models\Ref\TrustSetting as Model;
use Tagd\Core\Models\Ref\TrustSettingName;
use Tagd\Core\Repositories\Interfaces\Ref\TrustSettings as TrustSettingsInterface;
use Tagd\Core\Support\Repository\Exceptions\InvalidData as InvalidDataException;
use Tagd\Core\Support\Repository\Repository;

class TrustSettings extends Repository implements TrustSettingsInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Constructor.
     */
    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    /**
     * Set the modifier for a given brand.
     */
    public function setModifierForBrand(string $brandName, float $value): float
    {
        if ($value < Model::MODIFIER_MIN || $value > Model::MODIFIER_MAX) {
            throw InvalidDataException::withMessages(['value' => 'Out of range']);
        }

        $data = Model::where('name', TrustSettingName::BRAND_MODIFIER)->first();

        $data->update([
            'setting' => [
                ...(array) $data->setting,
                $brandName => $value,
            ],
        ]);

        return $value;
    }

    /**
     * Return the modifier for a given brand.
     */
    public function getModifierForBrand(string $brandName): float
    {
        $data = $this->getModifierForBrands();

        return $data[$brandName] ?? Model::MODIFIER_DEFAULT;
    }

    /**
     * Return the modifiers for all brands.
     */
    public function getModifierForBrands(): array
    {
        $data = Model::where('name', TrustSettingName::BRAND_MODIFIER)->first();

        return $data->setting;
    }
}

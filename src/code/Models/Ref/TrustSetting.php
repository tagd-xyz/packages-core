<?php

// phpcs:disable Symfony.Commenting.FunctionComment.Missing
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Models\Ref;

use Tagd\Core\Models\Model;

class TrustSetting extends Model
{
    public const TABLE = 'trust_settings';

    public const MODIFIER_MIN = 0;

    public const MODIFIER_MAX = 100;

    public const MODIFIER_DEFAULT = 0;

    public const SCORE_MIN = 0;

    public const SCORE_MAX = 100;

    public const SCORE_DEFAULT = 0;

    protected $fillable = [
        'name',
        'setting',
    ];

    protected $casts = [
        'name' => TrustSettingName::class,
        'setting' => 'array',
    ];

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();
    }
}

<?php

// phpcs:disable Symfony.Commenting.FunctionComment.Missing
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Models\User;

use Tagd\Core\Models\Model;

class Role extends Model
{
    public const RETAILER = 'retailer';

    public const RESELLER = 'reseller';

    public const CONSUMER = 'consumer';

    public const ADMIN = 'admin';

    public const UNKNOWN = 'unknown';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'actor_type',
        'actor_id',
        'user_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get the user who has this role
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent actor model (retailer, reseller, consumer).
     */
    public function actor()
    {
        return $this->morphTo();
    }
}

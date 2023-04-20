<?php

// phpcs:disable Symfony.Commenting.FunctionComment.Missing
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Models\Resale;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Model;
use Tagd\Core\Support\Slug;

class AccessRequest extends Model
{
    use SoftDeletes;

    protected $table = 'reseller_access_requests';

    protected $fillable = [
        'reseller_id',
        'consumer_id',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    // protected $observables = [
    // ];

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->code = (new Slug(Slug::ALPHABET_DIGITS_ONLY, 6, 1))->toString();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function reseller()
    {
        return $this->belongsTo(Reseller::class);
    }

    public function consumer()
    {
        return $this->belongsTo(Consumer::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    protected function isApproved(): Attribute
    {
        return Attribute::make(
            get: function () {
                return ! is_null($this->approved_at) && is_null($this->rejected_at);
            }
        );
    }

    protected function isRejected(): Attribute
    {
        return Attribute::make(
            get: function () {
                return ! is_null($this->rejected_at);
            }
        );
    }

    protected function isRevoked(): Attribute
    {
        return Attribute::make(
            get: function () {
                return ! is_null($this->approved_at) && ! is_null($this->rejected_at);
            }
        );
    }

    protected function isPending(): Attribute
    {
        return Attribute::make(
            get: function () {
                return is_null($this->approved_at) && is_null($this->rejected_at);
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACTIONS
    |--------------------------------------------------------------------------
    */

    public function approve()
    {
        return $this->update([
            'approved_at' => Carbon::now(),
        ]);
    }

    public function reject()
    {
        return $this->update([
            'rejected_at' => Carbon::now(),
        ]);
    }
}

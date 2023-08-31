<?php

// phpcs:disable Symfony.Commenting.FunctionComment.Missing
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Models\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Tagd\Core\Models\Actor\Admin;
use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Traits\HasUuidKey;

class User extends Authenticatable
{
    use HasUuidKey,
        HasFactory,
        Notifiable;

    /**
     * Constructor
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnection(config('tagd.database.connection'));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firebase_id',
        'firebase_tenant',
        'name',
        'email',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();
        static::autoUuidKey();
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get the roles for this user
     */
    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN HELPERS
    |--------------------------------------------------------------------------
    */

    public function justLoggedIn()
    {
        $this->update([
            'last_login_at' => Carbon::now(),
        ]);

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | ACTING HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Get the actors for this user
     */
    public function actors(): Collection
    {
        return $this
            ->roles()
            ->with('actor')
            ->get()
            ->map(function ($role) {
                return $role->actor;
            });
    }

    /**
     * Get the actors for this user
     */
    public function actorsOfType(string $type): Collection
    {
        return $this
            ->roles()
            ->with('actor')
            ->get()
            ->filter(function ($v, $k) use ($type) {
                return $v->actor_type == $type;
            })->map(function ($role) {
                return $role->actor;
            });
    }

    /**
     * checks whether or not can act as the given actor
     */
    public function canActAs(Consumer|Reseller|Retailer|Admin $actor): bool
    {
        return $this->roles()->get()->contains(function ($v, $k) use ($actor) {
            return $v->actor_id == $actor->id;
        });
    }

    // /**
    //  * checks whether or not can act as the given actor type / id
    //  *
    //  * @param  Reseller  $actor
    //  */
    // public function canActAsTypeAndIdOf(string $type, string $id): bool
    // {
    //     return $this->roles()->get()->contains(function ($v, $k) use ($type, $id) {
    //         return $v->actor_type == $type && $v->actor_id == $id;
    //     });
    // }

    // /**
    //  * checks whether or not can act as the given actor type
    //  */
    // public function canActAsTypeOf(string $type): bool
    // {
    //     return $this->roles()->get()->contains(function ($v, $k) use ($type) {
    //         return $v->actor_type == $type;
    //     });
    // }

    /**
     * start acting as the given actor
     */
    public function startActingAs(Consumer|Reseller|Retailer|Admin $actor): static
    {
        if (! $this->canActAs($actor)) {
            DB::transaction(function () use ($actor) {
                $role = new Role();
                $role->actor()->associate($actor);
                $this->roles()->save($role);
            }, 5);
        }

        return $this;
    }

    public function stopActingAs(Consumer|Reseller|Retailer|Admin $actor): static
    {
        $this->roles()->whereHas('actor', function (Builder $query) use ($actor) {
            $query->where('id', $actor->id);
        })->delete();

        return $this;
    }
}

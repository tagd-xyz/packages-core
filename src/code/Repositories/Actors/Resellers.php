<?php

namespace Tagd\Core\Repositories\Actors;

use Illuminate\Support\Facades\DB;
use Tagd\Core\Models\Actor\Reseller as Model;
use Tagd\Core\Repositories\Interfaces\Actors\Resellers as ResellersInterface;
use Tagd\Core\Support\Repository\Repository;

class Resellers extends Repository implements ResellersInterface
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
     * Asserts a reseller exists
     *
     * @param  mixed  $email
     * @param  string  $name
     */
    public function assertExists($email, $name = null): Model
    {
        return DB::transaction(function () use ($email, $name) {
            return Model::firstOrCreate([
                'email' => $email,
            ], [
                'name' => $name,
            ]);
        }, 5);
    }

    /**
     * Update avatar
     */
    public function updateAvatar(string $resellerId, string $uploadId): Model
    {
        $reseller = Model::findOrFail($resellerId);

        $reseller->images()->updateOrCreate([
            'upload_id' => $uploadId,
        ]);

        $reseller->load('images');

        return $reseller;
    }
}

<?php

namespace Tagd\Core\Support\Repository\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Tagd\Core\Support\Repository\Exceptions\Duplicated as DuplicatedException;
use Tagd\Core\Support\Repository\Exceptions\Generic as GenericException;

trait Create
{
    /**
     * Create a model.
     *
     * @param  array  $payload
     * @return Model
     */
    public function create(array $payload): ?Model
    {
        if ($this->isAuthorizationEnabled()) {
            $this->authorize(
                static::AUTH_ACTION_CREATE,
                [get_class($this->model), $payload]
            );
        }

        return DB::transaction(function () use ($payload) {
            try {
                $model = $this->model->create($payload);
            } catch (QueryException $e) {
                if (str_contains($e->getMessage(), 'Duplicate entry')) {
                    throw new DuplicatedException();
                } else {
                    throw new GenericException($e->getMessage());
                }
            }

            return $model->refresh();
        });
    }
}

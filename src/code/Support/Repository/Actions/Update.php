<?php

namespace Tagd\Core\Support\Repository\Actions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Tagd\Core\Models\Exceptions\InvalidData as InvalidDataModel;
use Tagd\Core\Support\Repository\Exceptions\InvalidData;
use Tagd\Core\Support\Repository\Exceptions\NotAllowed;
use Tagd\Core\Support\Repository\Exceptions\NotFound;

trait Update
{
    /**
     * Update existing model.
     *
     * @param  int|string  $modelId
     * @param  array  $payload
     * @return Model
     */
    public function update(
        $modelId,
        array $payload,
        array $options = [],
        callable $check = null
    ): ?Model {
        extract([
            'trashed' => false,
            'trashedOnly' => false,
            ...$options,
        ]);

        if ($trashedOnly) {
            $model = $this->model->onlyTrashed();
        } elseif ($trashed) {
            $model = $this->model->withTrashed();
        } else {
            $model = $this->model;
        }

        $model = $model->where('id', $modelId)->firstOrFail();

        if ($this->isAuthorizationEnabled()) {
            $this->authorize(static::AUTH_ACTION_UPDATE, $model);
        }

        if ($check) {
            $check($model);
        }

        return DB::transaction(function () use (
            $payload,
            $model
        ) {
            try {
                $model->update($payload);
            } catch (AuthenticationException $e) {
                throw new NotAllowed($e);
            } catch (AuthorizationException $e) {
                throw new NotAllowed($e);
            } catch (InvalidDataModel $e) {
                throw InvalidData::withMessages(['id' => $e->getMessage()]);
            } catch (\Exception $e) {
                throw new NotFound($e);
            } //TODO catch other exceptions

            return $model->refresh();
        });
    }
}

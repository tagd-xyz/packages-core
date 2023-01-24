<?php

namespace Tagd\Core\Support\Repository\Actions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\DB;
use Tagd\Core\Support\Repository\Exceptions\NotAllowed;
use Tagd\Core\Support\Repository\Exceptions\NotFound;

trait Delete
{
    /**
     * Delete model by id.
     *
     * @param  int|string  $modelId
     * @return bool
     */
    public function deleteById($modelId): bool
    {
        return DB::transaction(function () use ($modelId) {
            try {
                $model = $this->model
                    ->findOrFail($modelId);

                if ($this->isAuthorizationEnabled()) {
                    $this->authorize(static::AUTH_ACTION_DELETE, $model);
                }

                return $model->delete();
            } catch (AuthenticationException $e) {
                throw new NotAllowed($e);
            } catch (AuthorizationException $e) {
                throw new NotAllowed($e);
            } catch (\Exception $e) {
                throw new NotFound($e);
            } //TODO catch other exceptions
        });
    }

    /**
     * Restore model by id.
     *
     * @param  int|string  $modelId
     * @return bool
     */
    public function restoreById($modelId): bool
    {
        return DB::transaction(function () use ($modelId) {
            try {
                $model = $this->model->withTrashed()->findOrFail($modelId);

                if ($this->isAuthorizationEnabled()) {
                    $this->authorize(static::AUTH_ACTION_RESTORE, $model);
                }

                return $model->restore();
            } catch (AuthenticationException $e) {
                throw new NotAllowed($e);
            } catch (AuthorizationException $e) {
                throw new NotAllowed($e);
            } catch (\Exception $e) {
                throw new NotFound($e);
            } //TODO catch other exceptions
        });
    }

    /**
     * Permanently delete model by id.
     *
     * @param  int|string  $modelId
     * @return bool
     */
    public function permanentlyDeleteById($modelId): bool
    {
        return DB::transaction(function () use ($modelId) {
            try {
                $model = $this->model->withTrashed()->findById($modelId);

                if (! $model) {
                    throw new Exceptions\NotFound($e);
                }

                if ($this->isAuthorizationEnabled()) {
                    $this->authorize(static::AUTH_ACTION_DELETE_FORCE, $model);
                }

                return $model->forceDelete();
            } catch (AuthenticationException $e) {
                throw new NotAllowed($e);
            } catch (AuthorizationException $e) {
                throw new NotAllowed($e);
            } catch (\Exception $e) {
                throw new NotFound($e);
            }
        });
    }

    /**
     * Deletes all models.
     *
     * @return bool
     */
    public function truncate(): bool
    {
        return DB::transaction(function () {
            try {
                if ($this->isAuthorizationEnabled()) {
                    $this->authorize(static::AUTH_ACTION_TRUNCATE);
                }

                return $this->model->whereNull('deleted_at')->delete();
            } catch (AuthenticationException $e) {
                throw new NotAllowed($e);
            } catch (AuthorizationException $e) {
                throw new NotAllowed($e);
            }

            return false;
        });
    }

    /**
     * Permanently deletes all models.
     *
     * @return bool
     */
    public function permanentlyTruncate(): bool
    {
        try {
            if ($this->isAuthorizationEnabled()) {
                $this->authorize(static::AUTH_ACTION_TRUNCATE_FORCE);
            }

            $class = get_class($this->model);
            $class::truncate();

            return true;
        } catch (AuthenticationException $e) {
            throw new NotAllowed($e);
        } catch (AuthorizationException $e) {
            throw new NotAllowed($e);
        }

        return false;
    }
}

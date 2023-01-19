<?php

namespace Tagd\Core\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface Repository
{
    public const AUTH_ACTION_INDEX = 'index';

    public const AUTH_ACTION_VIEW = 'view';

    public const AUTH_ACTION_CREATE = 'create';

    public const AUTH_ACTION_UPDATE = 'update';

    public const AUTH_ACTION_DELETE = 'delete';

    public const AUTH_ACTION_DELETE_FORCE = 'forceDelete';

    public const AUTH_ACTION_RESTORE = 'restore';

    public const AUTH_ACTION_TRUNCATE = 'truncate';

    public const AUTH_ACTION_TRUNCATE_FORCE = 'forceTruncate';

    /**
     * Returns true if authorization is enabled
     *
     * @return bool
     */
    public function isAuthorizationEnabled(): bool;

    /**
     * List of searchable fields
     *
     * @return array
     */
    public function searchableFields(): array;

    /**
     * Get all models.
     *
     * @param  array  $options
     * @return Collection
     */
    public function all(array $options = []): Collection;

    /**
     * Get all models (paginated).
     *
     * @param  array  $options
     * @return LengthAwarePaginator
     */
    public function allPaginated(array $options = []): LengthAwarePaginator;

    /**
     * Find model by id.
     *
     * @param  int|string  $modelId
     * @param  array  $options
     * @return Model
     */
    public function findById($modelId, array $options = []): ?Model;

    /**
     * Create a model.
     *
     * @param  array  $payload
     * @return Model
     */
    public function create(array $payload): ?Model;

    /**
     * Update existing model.
     *
     * @param  int|string  $modelId
     * @param  array  $payload
     * @param  array  $options
     * @param  callable  $check
     * @return Model
     */
    public function update($modelId, array $payload, array $options = [], callable $check = null): ?Model;

    /**
     * Delete model by id.
     *
     * @param  int|string  $modelId
     * @return bool
     */
    public function deleteById($modelId): bool;

    /**
     * Restore model by id.
     *
     * @param  int|string  $modelId
     * @return bool
     */
    public function restoreById($modelId): bool;

    /**
     * Permanently delete model by id.
     *
     * @param  int|string  $modelId
     * @return bool
     */
    public function permanentlyDeleteById($modelId): bool;
}

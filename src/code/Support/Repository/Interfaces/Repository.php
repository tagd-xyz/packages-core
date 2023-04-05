<?php

namespace Tagd\Core\Support\Repository\Interfaces;

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
     */
    public function isAuthorizationEnabled(): bool;

    /**
     * List of searchable fields
     */
    public function searchableFields(): array;

    /**
     * List of filtrable fields
     */
    public function filtrableFields(): array;

    /**
     * Get all models.
     */
    public function all(array $options = []): Collection;

    /**
     * Get all models (paginated).
     */
    public function allPaginated(array $options = []): LengthAwarePaginator;

    /**
     * Find model by id.
     *
     * @param  int|string  $modelId
     * @return Model
     */
    public function findById($modelId, array $options = []): ?Model;

    /**
     * Create a model.
     *
     * @return Model
     */
    public function create(array $payload): ?Model;

    /**
     * Update existing model.
     *
     * @param  int|string  $modelId
     * @param  callable  $check
     * @return Model
     */
    public function update($modelId, array $payload, array $options = [], callable $check = null): ?Model;

    /**
     * Delete model by id.
     *
     * @param  int|string  $modelId
     */
    public function deleteById($modelId): bool;

    /**
     * Restore model by id.
     *
     * @param  int|string  $modelId
     */
    public function restoreById($modelId): bool;

    /**
     * Permanently delete model by id.
     *
     * @param  int|string  $modelId
     */
    public function permanentlyDeleteById($modelId): bool;
}

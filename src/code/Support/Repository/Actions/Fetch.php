<?php

namespace Tagd\Core\Support\Repository\Actions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Tagd\Core\Support\Repository\Exceptions\InvalidFilter;
use Tagd\Core\Support\Repository\Exceptions\NotFound;

trait Fetch
{
    /**
     * List of searchable fields
     */
    public function searchableFields(): array
    {
        return [
            'id',
        ];
    }

    /**
     * List of filtrable fields
     */
    public function filtrableFields(): array
    {
        return ['id'];
    }

    /**
     * Build query
     *
     * @param  mixed  $options
     */
    protected function buildQuery($options): Builder
    {
        if ($this->isAuthorizationEnabled()) {
            $this->authorize(static::AUTH_ACTION_INDEX, get_class($this->model));
        }

        extract([
            'columns' => ['*'],
            'relations' => [],
            'append' => [],
            'orderBy' => 'id',
            'direction' => 'asc',
            'searchFunc' => null,
            'searchKeyword' => null,
            'filterFunc' => null,
            'filterBy' => null,
            'scopes' => null,
            'trashed' => false,
            'trashedOnly' => false,
            ...$options,
        ]);

        $query = $this->model;

        if ($trashedOnly) {
            $query = $query->onlyTrashed();
        } elseif ($trashed) {
            $query = $query->withTrashed();
        }

        if ($scopes && is_array($scopes)) {
            foreach ($scopes as $scope) {
                $query = $query->$scope();
            }
        }

        if (! is_null($searchKeyword)) {
            if (is_null($searchFunc) || ! is_callable($searchFunc)) {
                $searchFunc = function ($query, $keyword) {
                    $query->where(function ($query) use ($keyword) {
                        foreach ($this->searchableFields() as $field) {
                            $query->orWhere($field, 'like', "%{$keyword}%");
                        }
                    });
                };
            }
        } else {
            if (is_null($searchFunc) || ! is_callable($searchFunc)) {
                $searchFunc = function ($query) {
                };
            }
        }

        if (! is_null($filterBy)) {
            if (is_null($filterFunc) || ! is_callable($filterFunc)) {
                $filterFunc = function ($query, $filterBy) {
                    // main property
                    // i.e title => miss
                    foreach ($filterBy as $field => $value) {
                        if (! in_array($field, $this->filtrableFields())) {
                            throw InvalidFilter::forField($field);
                        }

                        if (! str_contains($field, '.')) {
                            $query->whereIn($field, is_array($value) ? $value : [$value]);
                        }
                    }

                    // related property
                    // i.e. country.code = GB
                    foreach ($filterBy as $field => $value) {
                        if (! in_array($field, $this->filtrableFields())) {
                            throw InvalidFilter::forField($field);
                        }

                        if (str_contains($field, '.')) {
                            [$field, $fieldRelated] = explode('.', $field, 2);
                            $query->whereHas($field, function ($q) use ($fieldRelated, $value) {
                                $q->whereIn($fieldRelated, is_array($value) ? $value : [$value]);
                            });
                        }
                    }
                };
            }
        }

        $query = $query
            ->append($append)
            ->with($relations)
            ->select($columns)
            ->orderBy($orderBy, $direction);

        if (! is_null($searchKeyword)) {
            $searchFunc($query, $searchKeyword);
        } else {
            $searchFunc($query);
        }

        if (! is_null($filterFunc)) {
            $filterFunc($query, $filterBy);
        }

        return $query;
    }

    /**
     * Fetches all entries
     */
    public function all(array $options = []): Collection
    {
        return $this->buildQuery($options)->get();
    }

    /**
     * Fetches all entries (paginated)
     */
    public function allPaginated(array $options = []): LengthAwarePaginator
    {
        extract([
            'perPage' => 25,
            'page' => 1,
            'columns' => ['*'],
            ...$options,
        ]);

        return $this
            ->buildQuery($options)
            ->paginate($perPage, $columns, 'page', $page);
    }

    /**
     * Find model by id.
     *
     * @param  int|string  $modelId
     * @return Model
     */
    public function findById(
        $modelId,
        array $options = []
    ): ?Model {
        extract([
            'columns' => ['*'],
            'relations' => [],
            'appends' => [],
            'trashed' => false,
            'trashedOnly' => false,
            ...$options,
        ]);

        try {
            if ($trashedOnly) {
                $model = $this->model->trashedOnly();
            } elseif ($trashed) {
                $model = $this->model->withTrashed();
            } else {
                $model = $this->model;
            }

            $model = $model
                ->select($columns)
                ->with($relations)
                ->where('id', $modelId)
                ->firstOrFail()
                ->append($appends);
        } catch (\Exception $e) {
            throw new NotFound($e);
        }

        if ($this->isAuthorizationEnabled()) {
            $this->authorize(static::AUTH_ACTION_VIEW, $model);
        }

        return $model;
    }
}

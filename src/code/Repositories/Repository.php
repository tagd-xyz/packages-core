<?php

namespace Tagd\Core\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class Repository implements Interfaces\Repository
{
    use
        Actions\Fetch,
        Actions\Create,
        Actions\Update,
        Actions\Delete;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var bool
     */
    protected $isAuthorizationEnabled = true;

    /**
     * Repository constructor.
     *
     * @param  Model  $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Getter/Setter if authorization is enabled
     *
     * @param  null|bool  $itIs
     * @return bool
     */
    public function isAuthorizationEnabled(?bool $itIs = null): bool
    {
        if (! is_null($itIs)) {
            $this->isAuthorizationEnabled = $itIs;
        }

        return $this->isAuthorizationEnabled;
    }
}

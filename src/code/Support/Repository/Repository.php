<?php

namespace Tagd\Core\Support\Repository;

use Illuminate\Database\Eloquent\Model;

abstract class Repository implements Interfaces\Repository
{
    use Actions\Create,
        Actions\Delete,
        Actions\Fetch,
        Actions\Update;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var bool
     */
    protected $isAuthorizationEnabled;

    /**
     * Repository constructor.
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->isAuthorizationEnabled = false;
        // TODO THIS
    }

    /**
     * Getter/Setter if authorization is enabled
     */
    public function isAuthorizationEnabled(bool $itIs = null): bool
    {
        if (! is_null($itIs)) {
            $this->isAuthorizationEnabled = $itIs;
        }

        return $this->isAuthorizationEnabled;
    }
}

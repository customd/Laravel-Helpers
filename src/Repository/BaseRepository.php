<?php

namespace CustomD\LaravelHelpers\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

/**
 * @template TModelClass
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     *
     * @var TModelClass|string
     */
    protected $model;

    protected Request $request;

    /**
     * Default attributes to automatically except from request treatments.
     *
     * @var array<int, string>
     */
    protected array $defaultAttributesToExcept = ['_token', '_method'];

    /**
     * Automatically except defined $defaultAttributesToExcept from the request treatments.
     */
    protected bool $exceptDefaultAttributes = true;

    public function __construct()
    {
        if ($this->model) {
            $this->setModel($this->model);
        }
        $this->setRequest(request());
    }

    public function setRequest(Request $request): BaseRepository
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Find a model by its primary key.
     *
     * @param  int|string  $id
     * @param  array<int, (model-property<TModelClass>|'*')>  $columns
     * @phpstan-return TModelClass|null
     */
    public function findOne(string|int $id, array $columns = ['*'])
    {
        /** @var TModelClass|null */
        $res = $this->getModel()->find($id, $columns);
        return $res;
    }


    /** @phpstan-return TModelClass */
    public function getModel()
    {
        if ($this->model instanceof Model) {
            return $this->model;
        }
        throw new ModelNotFoundException(
            'You must declare your repository $model attribute with an Illuminate\Database\Eloquent\Model '
            . 'namespace to use this feature.'
        );
    }

    /**
     * Set the repository model class to instantiate.
     *
     * @param string $modelClass
     *
     * @return BaseRepository
     */
    public function setModel(string $modelClass): BaseRepository
    {
        $this->model = app($modelClass);

        return $this;
    }
}

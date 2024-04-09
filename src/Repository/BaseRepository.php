<?php

namespace CustomD\LaravelHelpers\Repository;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @template TModelClass of Model
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
     *
     * @var string|array<int, string>|null
     */
    public string|array|null $withoutScopes = null;

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

    public function withoutScopes(Closure $callback, array|string $scopes = '*')
    {
        $this->withoutScopes = $scopes;
        $result = $callback($this);
        $this->withoutScopes = null;

        return $result;
    }


    /**
     * @phpstan-param \Illuminate\Database\Eloquent\Builder<TModelClass> $builder
     * @phpstan-param string|array<int, string>|null $scopes
     * @phpstan-return \Illuminate\Database\Eloquent\Builder<TModelClass>
     * @return \Illuminate\Database\Eloquent\Builder<TModelClass>
    */
    protected function handleWithoutScopes(Builder $builder, string|array|null $scopes): Builder
    {
        if ($scopes === null) {
            return $builder;
        }
        if ($scopes === '*' || $scopes === ['*']) {
            return $builder->withoutGlobalScopes();
        }

        foreach ((array) $scopes as $scope) {
            $builder->withoutGlobalScope($scope);
        }

        return $builder;
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

    /**
     * Find multiple models by their primary keys.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable<array-key, mixed>|array<mixed>  $ids
     * @param  array<int, (model-property<TModelClass>|'*')>|model-property<TModelClass>|'*'  $columns
     * @phpstan-return \Illuminate\Database\Eloquent\Collection<int, TModelClass>
     */
    public function findMany($ids, $columns = ['*'])
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, TModelClass> */
        $res = $this->getModel()->findMany($ids, $columns);
        return $res;
    }

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param  mixed  $id
     * @param  array<int, (model-property<TModelClass>|'*')>|model-property<TModelClass>|'*'  $columns
     * @phpstan-return ($id is (\Illuminate\Contracts\Support\Arrayable<array-key, mixed>|array<mixed>) ? \Illuminate\Database\Eloquent\Collection<int, TModelClass> : TModelClass)
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail($id, $columns = ['*'])
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, TModelClass>|TModelClass */
        $res = $this->getModel()->findOrFail($id, $columns);
        return $res;
    }

    /**
     * @phpstan-param array<model-property<TModelClass>, mixed> $attributes
     * @phpstan-return TModelClass
     */
    public function create(array $attributes = [])
    {
        /** @var TModelClass */
        $res = $this->getModel()->create($attributes);
        return $res;
    }

    /**
     * @phpstan-param array<model-property<TModelClass>, mixed> $attributes
     * @phpstan-param array<model-property<TModelClass>, mixed> $values
     * @phpstan-return TModelClass
     */
    public function firstOrCreate(array $attributes = [], array $values = [])
    {
        /** @var TModelClass */
        $res = $this->getModel()->firstOrCreate($attributes, $values);
        return $res;
    }

    /**
     * @phpstan-param array<model-property<TModelClass>, mixed> $attributes
     * @phpstan-return TModelClass
     */
    public function make(array $attributes = [])
    {
        /** @var TModelClass */
        $res = $this->getModel()->make($attributes);
        return $res;
    }


    /** @phpstan-return \Illuminate\Database\Eloquent\Builder<TModelClass> */
    public function getModel()
    {
        if ($this->model instanceof Model) {
            return $this->model->newQuery()->when(filled($this->withoutScopes), function (Builder $query) {
                return $this->handleWithoutScopes($query, $this->withoutScopes);
            });
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


    /**
     * @param  array<int|string, mixed> $arguments
     */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->getModel()->$name(...$arguments);
    }
}

<?php

namespace CustomD\LaravelHelpers\Repository;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @template TModelClass
 */
interface BaseRepositoryInterface
{
    public function setModel(string $modelClass): BaseRepository;

    public function setRequest(Request $request): BaseRepository;

    /**
     * Find a model by its primary key.
     *
     * @param  int|string  $id
     * @param  array<int, (model-property<TModelClass>|'*')>  $columns
     * @phpstan-return TModelClass|null
     */
    public function findOne(string|int $id, array $columns = ['*']);


     /**
     * Find multiple models by their primary keys.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable<array-key, mixed>|array<mixed>  $ids
     * @param  array<int, (model-property<TModelClass>|'*')>|model-property<TModelClass>|'*'  $columns
     * @phpstan-return \Illuminate\Database\Eloquent\Collection<int, TModelClass>
     */
    public function findMany($ids, $columns = ['*']);


    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param  mixed  $id
     * @param  array<int, (model-property<TModelClass>|'*')>|model-property<TModelClass>|'*'  $columns
     * @phpstan-return ($id is (\Illuminate\Contracts\Support\Arrayable<array-key, mixed>|array<mixed>) ? \Illuminate\Database\Eloquent\Collection<int, TModelClass> : TModelClass)
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail($id, $columns = ['*']);


    /**
     * @phpstan-param array<model-property<TModelClass>, mixed> $attributes
     * @phpstan-return TModelClass
     */
    public function create(array $attributes = []);

    /**
     * @phpstan-param array<model-property<TModelClass>, mixed> $attributes
     * @phpstan-param array<model-property<TModelClass>, mixed> $values
     * @phpstan-return TModelClass
     */
    public function firstOrCreate(array $attributes = [], array $values = []);

    /**
     * @phpstan-param array<model-property<TModelClass>, mixed> $attributes
     * @phpstan-return TModelClass
     */
    public function make(array $attributes = []);
}

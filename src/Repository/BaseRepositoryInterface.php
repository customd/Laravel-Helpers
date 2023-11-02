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
}

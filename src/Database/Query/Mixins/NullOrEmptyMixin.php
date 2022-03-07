<?php

namespace CustomD\LaravelHelpers\Database\Query\Mixins;

use Illuminate\Database\Query\Builder;

/** @mixin \Illuminate\Database\Query\Builder */

class NullOrEmptyMixin
{

    public function whereNullOrEmpty()
    {
        return function (string $column) {
            /** @var \Illuminate\Database\Query\Builder $this */
            return $this->where(fn (Builder $builder) => $builder->where($column, '=', '')->orWhereNull($column));
        };
    }

    public function orWhereNullOrEmpty()
    {
        return function (string $column) {
            /** @var \Illuminate\Database\Query\Builder $this */
            return $this->orWhere(fn (Builder $builder) => $builder->where($column, '=', '')->orWhereNull($column));
        };
    }


    public function whereNotNullOrEmpty()
    {
        return function (string $column) {
            /** @var \Illuminate\Database\Query\Builder $this */
            return $this->where(fn(Builder $builder) => $builder->where($column, '!=', '')->whereNotNull($column));
        };
    }

    public function orWhereNotNullOrEmpty()
    {
        return function (string $column) {
            /** @var \Illuminate\Database\Query\Builder $this */
            return $this->orWhere(fn(Builder $builder) => $builder->where($column, '!=', '')->whereNotNull($column));
        };
    }
}

<?php

namespace CustomD\LaravelHelpers\Database\Query\Mixins;

use Closure;
use Illuminate\Database\Query\Builder;

/** @mixin \Illuminate\Database\Query\Builder */

class NullOrEmptyMixin
{

    public function whereNullOrEmpty(): Closure
    {
        return function (string $column) {
            /** @var \Illuminate\Database\Query\Builder $this */
            return $this->where(fn (Builder $builder) => $builder->where($column, '=', '')->orWhereNull($column));
        };
    }

    public function orWhereNullOrEmpty(): Closure
    {
        return function (string $column) {
            /** @var \Illuminate\Database\Query\Builder $this */
            return $this->orWhere(fn (Builder $builder) => $builder->where($column, '=', '')->orWhereNull($column));
        };
    }


    public function whereNotNullOrEmpty(): Closure
    {
        return function (string $column) {
            /** @var \Illuminate\Database\Query\Builder $this */
            return $this->where(fn(Builder $builder) => $builder->where($column, '!=', '')->whereNotNull($column));
        };
    }

    public function orWhereNotNullOrEmpty(): Closure
    {
        return function (string $column) {
            /** @var \Illuminate\Database\Query\Builder $this */
            return $this->orWhere(fn(Builder $builder) => $builder->where($column, '!=', '')->whereNotNull($column));
        };
    }

    public function whereNullOrValue(): Closure
    {
        /** @param $value mixed **/
        return function (string $column, $operator = null, $value = null, $boolean = 'and') {
            /** @var \Illuminate\Database\Query\Builder $this */
            [$value, $operator] = $this->prepareValueAndOperator(
                $value, $operator, func_num_args() === 2
            );

            return $this->where(fn (Builder $builder) => $builder->whereNull($column)->when($value, fn($sbuilder) => $sbuilder->orWhere($column, $operator, $value, $boolean)));
        };
    }
}

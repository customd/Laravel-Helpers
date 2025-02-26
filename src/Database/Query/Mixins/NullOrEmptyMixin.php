<?php

namespace CustomD\LaravelHelpers\Database\Query\Mixins;

use Closure;
use Illuminate\Database\Query\Builder;

/**
 * @mixin \Illuminate\Database\Query\Builder
 */

class NullOrEmptyMixin
{

    public function whereNullOrEmpty(): Closure
    {
        return function (string $column): Builder {
            return $this->where(fn (Builder $builder) => $builder->where($column, '=', '')->orWhereNull($column));
        };
    }

    public function orWhereNullOrEmpty(): Closure
    {
        return function (string $column): Builder {
            return $this->orWhere(fn (Builder $builder) => $builder->where($column, '=', '')->orWhereNull($column));
        };
    }


    public function whereNotNullOrEmpty(): Closure
    {
        return function (string $column): Builder {
            return $this->where(fn(Builder $builder) => $builder->where($column, '!=', '')->whereNotNull($column));
        };
    }

    public function orWhereNotNullOrEmpty(): Closure
    {
        return function (string $column): Builder {
            return $this->orWhere(fn(Builder $builder) => $builder->where($column, '!=', '')->whereNotNull($column));
        };
    }

    public function whereNullOrValue(): Closure
    {
        /** @param $value mixed **/
        return function (string $column, $operator = null, $value = null, $boolean = 'and'): Builder {
            [$value, $operator] = $this->prepareValueAndOperator(
                $value,
                $operator,
                func_num_args() === 2
            );

            return $this->where(fn (Builder $builder) => $builder->whereNull($column)->when($value, fn($sbuilder) => $sbuilder->orWhere($column, $operator, $value, $boolean)));
        };
    }

    public function iWhere(): Closure
    {
        return function (string|array $column, $operator = null, $value = null, $boolean = 'and'): Builder {
            if (is_array($column)) {
                return $this->addArrayOfWheres($column, $boolean, 'iWhere'); //@phpstan-ignore-line
            }

            [$value, $operator] = $this->prepareValueAndOperator(
                $value,
                $operator,
                func_num_args() === 2
            );

            return $this->whereRaw("LOWER({$column}) {$operator} ?", [strtolower($value)], $boolean);
        };
    }

    public function orIWhere(): Closure
    {
        return function (string|array $column, $operator = null, $value = null): Builder {
            [$value, $operator] = $this->prepareValueAndOperator(
                $value,
                $operator,
                func_num_args() === 2
            );
            return $this->iWhere($column, $operator, $value, 'or'); //@phpstan-ignore  return.type
        };
    }
}

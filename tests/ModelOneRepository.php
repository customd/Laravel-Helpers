<?php
namespace CustomD\LaravelHelpers\Tests;

use CustomD\LaravelHelpers\Tests\ModelOne;
use CustomD\LaravelHelpers\Repository\BaseRepository;

/**
 * @template TModelClass of ModelOne
 */
class ModelOneRepository extends BaseRepository
{
    protected $model = ModelOne::class;

    public function findByName(string $name): ?ModelOne
    {
        return $this->getModel()->where('name', $name)->first();
    }
}

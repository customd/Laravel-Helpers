<?php
namespace CustomD\LaravelHelpers\Tests;

use CustomD\LaravelHelpers\Tests\ModelOne;
use CustomD\LaravelHelpers\Repository\BaseRepository;

class ModelOneRepository extends BaseRepository
{
    protected $model = ModelOne::class;
}

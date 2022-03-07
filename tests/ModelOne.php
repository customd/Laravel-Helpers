<?php
namespace CustomD\LaravelHelpers\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ModelOne extends Model
{

    protected static $unguarded = true;

    public function modelTwo(): HasOne
    {
        return $this->hasOne(ModelTwo::class);
    }

    public function modelTwoForced(): HasOne
    {
        return $this->hasOne(ModelTwo::class)->orFail();
    }
}

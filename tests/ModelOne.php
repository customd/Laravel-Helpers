<?php
namespace CustomD\LaravelHelpers\Tests;

use CustomD\LaravelHelpers\Traits\PermissionBasedAccess;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelOne extends Model
{
    use SoftDeletes;
    use PermissionBasedAccess;

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

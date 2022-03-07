<?php
namespace CustomD\LaravelHelpers\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModelTwo extends Model
{

    protected static $unguarded = true;

    public function modelOne(): BelongsTo
    {
        return $this->belongsTo(ModelOne::class);
    }

    public function modelOneForced(): BelongsTo
    {
        return $this->belongsTo(ModelOne::class, 'model_one_id')->orFail();
    }
}
